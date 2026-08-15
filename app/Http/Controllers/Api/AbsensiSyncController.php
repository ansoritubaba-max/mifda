<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\RiwayatKehadiran;
use App\Models\User;
use App\Services\NotificationService;
use Illuminate\Http\Request;

/**
 * FITUR BARU: Integrasi Absensi <-> Mifda (sinkronisasi data kehadiran).
 *
 * Endpoint server-to-server yang dipanggil dari aplikasi Absensi setiap
 * kali guru submit absensi siswa (lihat TeacherAppController::
 * storeStudentAttendance di aplikasi Absensi). Dilindungi middleware
 * 'integration.token' (lihat routes/api.php) — bukan endpoint publik.
 */
class AbsensiSyncController extends Controller
{
    /**
     * POST /api/absensi/sync
     *
     * Body: {
     *   "linked_student_id": 12,     // wajib — ID siswa versi Absensi
     *   "nisn": "1234567890",        // opsional, cadangan buat pencocokan
     *   "status": "hadir",           // hadir | sakit | izin | alpa
     *   "mapel": "Matematika",
     *   "guru": "Bu Siti",
     *   "tanggal": "2026-08-14",
     *   "jam": "08:15"
     * }
     *
     * Kalau siswa dengan linked_student_id itu belum ada yang ter-link di
     * Mifda (ortu belum pernah pakai kode penghubung), endpoint ini tetap
     * balas 200 tapi cuma bilang "dilewati" — bukan error, karena ini
     * kondisi normal, bukan semua siswa Absensi otomatis punya akun Mifda.
     */
    public function sync(Request $request)
    {
        $validated = $request->validate([
            'linked_student_id' => ['required', 'integer'],
            'nisn' => ['nullable', 'string'],
            'status' => ['required', 'string', 'in:hadir,sakit,izin,alpa'],
            'mapel' => ['nullable', 'string'],
            'guru' => ['nullable', 'string'],
            'tanggal' => ['required', 'date'],
            'jam' => ['nullable', 'string'],
        ]);

        $siswa = User::where('role', 'siswa')
            ->where('linked_student_id', $validated['linked_student_id'])
            ->first();

        if (!$siswa) {
            return response()->json([
                'success' => true,
                'processed' => false,
                'message' => 'Siswa ini belum ter-link ke akun Mifda manapun, data dilewati.',
            ]);
        }

        $riwayat = RiwayatKehadiran::create([
            'user_id' => $siswa->id,
            'tanggal' => $validated['tanggal'],
            'waktu' => $validated['jam'] ?? null,
            'status' => $validated['status'],
            'mapel' => $validated['mapel'] ?? null,
            'guru' => $validated['guru'] ?? null,
        ]);

        $this->kirimNotifikasiKeOrtu($siswa, $validated);

        return response()->json([
            'success' => true,
            'processed' => true,
            'riwayat_id' => $riwayat->id,
        ]);
    }

    /**
     * Kirim notifikasi ke semua ortu yang terhubung ke siswa ini.
     *
     * BUGFIX: sebelumnya di sini langsung `Notifikasi::create()` doang —
     * cuma nambah baris di database, TIDAK beneran ngirim Web Push ke HP.
     * Ternyata Mifda sudah punya NotificationService yang benar (nyimpen
     * ke database SEKALIGUS ngirim Web Push asli ke semua device ortu yang
     * subscribe, plus otomatis bersihin subscription yang mati). Sekarang
     * dipakai itu, biar notifikasi absensi juga beneran muncul sebagai
     * push notification di HP, bukan cuma nongol di lonceng dalam app.
     */
    private function kirimNotifikasiKeOrtu(User $siswa, array $data): void
    {
        $labelStatus = match ($data['status']) {
            'hadir' => 'Hadir',
            'sakit' => 'Sakit',
            'izin' => 'Izin',
            'alpa' => 'Alfa / Tanpa Keterangan',
            default => ucfirst($data['status']),
        };

        $pesan = "{$siswa->name} tercatat *{$labelStatus}*";
        if (!empty($data['mapel'])) {
            $pesan .= " pada mapel {$data['mapel']}";
        }
        if (!empty($data['jam'])) {
            $pesan .= " pukul {$data['jam']}";
        }
        $pesan .= '.';

        NotificationService::kirim(
            $siswa->orangTua,
            'Info Kehadiran ' . $siswa->name,
            $pesan,
            'absensi',
            '📋',
            route('ortu.dashboard')
        );
    }
}
