<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

/**
 * FITUR BARU: Integrasi Absensi <-> Mifda — rekonsiliasi akun lama.
 *
 * Endpoint server-to-server yang dipanggil dari halaman admin
 * "Rekonsiliasi Akun Lama" di aplikasi Absensi. Dilindungi middleware
 * 'integration.token' (lihat routes/api.php).
 */
class StudentLinkController extends Controller
{
    /**
     * GET /api/link/unlinked-students
     *
     * Balikin daftar akun siswa Mifda yang belum ter-link ke Absensi —
     * dipakai Absensi buat nyari kandidat kecocokan (nama+kelas) buat
     * akun-akun lama yang gak pernah dapat kode penghubung.
     */
    public function unlinkedStudents(Request $request)
    {
        $siswa = User::where('role', 'siswa')
            ->where('link_status', '!=', 'verified')
            ->with('kelas')
            ->get()
            ->map(fn (User $u) => [
                'id' => $u->id,
                'name' => $u->name,
                'username' => $u->username,
                'kelas_name' => $u->kelas->nama_kelas ?? null,
            ]);

        return response()->json([
            'success' => true,
            'students' => $siswa,
        ]);
    }

    /**
     * POST /api/link/admin-link
     *
     * Link manual TANPA kode — dipakai dari halaman rekonsiliasi Absensi,
     * setelah admin sekolah mengonfirmasi kecocokan pasangan akun secara
     * manual. Beda dari verify-code (yang auto-link berbekal bukti kirim
     * WA), endpoint ini murni keputusan admin — makanya cuma dipanggil
     * dari 1 pintu (halaman admin Absensi), bukan dari form ortu.
     */
    public function adminLink(Request $request)
    {
        $validated = $request->validate([
            'mifda_user_id' => ['required', 'integer'],
            'student_id' => ['required', 'integer'],
            'nisn' => ['nullable', 'string'],
        ]);

        $siswa = User::where('id', $validated['mifda_user_id'])->where('role', 'siswa')->first();

        if (!$siswa) {
            return response()->json([
                'success' => false,
                'message' => 'Akun siswa Mifda tidak ditemukan.',
            ], 404);
        }

        if ($siswa->link_status === 'verified') {
            return response()->json([
                'success' => false,
                'message' => 'Akun ini sudah ter-link sebelumnya.',
            ], 409);
        }

        $dataUpdate = [
            'link_status' => 'verified',
            'linked_student_id' => $validated['student_id'],
            'linked_at' => now(),
        ];

        if (empty($siswa->nisn) && !empty($validated['nisn'])) {
            $nisnDipakaiAkunLain = User::where('nisn', $validated['nisn'])
                ->where('id', '!=', $siswa->id)
                ->exists();

            if (!$nisnDipakaiAkunLain) {
                $dataUpdate['nisn'] = $validated['nisn'];
            }
        }

        try {
            $siswa->update($dataUpdate);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan — kemungkinan siswa Absensi ini sudah dipasangkan ke akun lain.',
            ], 409);
        }

        return response()->json(['success' => true]);
    }
}
