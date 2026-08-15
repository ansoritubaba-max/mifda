<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Nilai;
use App\Models\Materi;
use App\Models\RiwayatKehadiran;
use App\Services\LinkCodeService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class OrtuController extends Controller
{
    public function dashboard()
    {
        $ortu = Auth::user();

        // Ambil semua anak dengan relasi lengkap
        $anaks = $ortu->anak()->with([
            'kelas',
            'riwayat_belajar' => fn($q) => $q->latest()->take(5),
            'riwayat_belajar.materi.mapel',
            'nilais'          => fn($q) => $q->latest()->take(5),
            'nilais.materi',
        ])->get();

        foreach ($anaks as $anak) {

            // --- Data grafik nilai (7 kuis terakhir) ---
            $dataNilai = Nilai::where('user_id', $anak->id)
                ->orderBy('created_at', 'asc')
                ->take(7)
                ->get();

            $anak->label_grafik = $dataNilai->map(fn($n) => $n->created_at->format('d/m'))->toArray();
            $anak->skor_grafik  = $dataNilai->pluck('skor')->toArray();

            // --- Rata-rata nilai ---
            $anak->rata_nilai = $dataNilai->count() > 0
                ? round($dataNilai->avg('skor'))
                : null;

            // --- Peringkat di kelas ---
            if ($anak->kelas_id) {
                $ranked = User::where('role', 'siswa')
                    ->where('kelas_id', $anak->kelas_id)
                    ->orderBy('xp', 'desc')
                    ->pluck('id')
                    ->toArray();

                $pos = array_search($anak->id, $ranked);
                $anak->peringkat_kelas = ($pos !== false) ? $pos + 1 : null;
                $anak->total_siswa_kelas = count($ranked);
            } else {
                $anak->peringkat_kelas = null;
                $anak->total_siswa_kelas = 0;
            }

            // --- Lencana anak ---
            $anak->lencanas = DB::table('user_lencanas')
                ->where('user_id', $anak->id)
                ->orderBy('created_at', 'asc')
                ->get();

            // --- Ujian mendatang di kelas anak ---
            $anak->ujian_mendatang = Materi::with('mapel')
                ->whereHas('mapel', fn($q) => $q->where('kelas_id', $anak->kelas_id))
                ->whereIn('jenis', ['ujian_ganjil', 'ujian_genap'])
                ->where(function ($q) {
                    $q->whereNull('jadwal_mulai')
                      ->orWhere('jadwal_selesai', '>=', now());
                })
                ->orderBy('jadwal_mulai', 'asc')
                ->take(3)
                ->get();

            // TAMBAHAN: Integrasi Absensi <-> Mifda — riwayat kehadiran
            // (kosong kalau anak ini belum pernah di-link, dan itu wajar,
            // bukan error). Dibungkus try/catch, pola yang sama persis
            // kayak PushSubscription di NotificationController — jaga-jaga
            // migration tabel ini belum sempat dijalankan di server, biar
            // dashboard tetap kebuka normal (cuma card riwayat yang kosong)
            // bukannya 500.
            try {
                $anak->riwayat_kehadiran = RiwayatKehadiran::where('user_id', $anak->id)
                    ->orderBy('tanggal', 'desc')
                    ->orderBy('waktu', 'desc')
                    ->take(10)
                    ->get();
            } catch (\Throwable $e) {
                $anak->riwayat_kehadiran = collect();
            }
        }

        return view('ortu.dashboard', compact('anaks'));
    }

    /**
     * FITUR BARU: Integrasi Absensi <-> Mifda (kode penghubung akun ortu).
     *
     * Dipanggil dari form kecil di dashboard ortu — opsional, ortu boleh
     * isi kapan saja atau tidak sama sekali. Dicek dulu anak yang dipilih
     * beneran anak dari ortu yang login (bukan siswa sembarangan).
     */
    public function hubungkanKode(Request $request)
    {
        $request->validate([
            'siswa_id' => ['required', 'integer'],
            'kode_penghubung' => ['required', 'digits:6'],
        ]);

        $ortu = Auth::user();

        $siswa = $ortu->anak()->where('users.id', $request->siswa_id)->first();

        if (!$siswa) {
            return back()->withErrors(['kode_penghubung' => 'Data ananda tidak ditemukan di akun Anda.']);
        }

        // Defense-in-depth: LinkCodeService sendiri sudah nangkep semua
        // \Throwable di dalamnya, tapi tetep dibungkus lagi di sini biar
        // kalau suatu saat ada perubahan yang bikin bocor, ortu tetap
        // lihat pesan yang rapi, bukan halaman 500.
        try {
            $result = app(LinkCodeService::class)->linkByCode($siswa, $request->kode_penghubung);
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::error('[LinkCode] Error tak terduga di hubungkanKode: ' . $e->getMessage());

            return back()->withErrors(['kode_penghubung' => 'Terjadi kendala teknis, coba lagi nanti.']);
        }

        if ($result['success']) {
            return back()->with('success', $result['message']);
        }

        return back()->withErrors(['kode_penghubung' => $result['message']]);
    }
}
