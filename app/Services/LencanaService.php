<?php

namespace App\Services;

use App\Models\User;
use App\Models\RiwayatBelajar;
use App\Models\Nilai;
use Illuminate\Support\Facades\DB;

class LencanaService
{
    /**
     * Cek & beri semua lencana yang layak diterima siswa.
     * Dipanggil setelah submitKuis() dan selesaiMateri().
     */
    public function cekDanBeriLencana(User $user): void
    {
        $this->cekLencanaRajinBelajar($user);
        $this->cekLencanaJagoanKuis($user);
        $this->cekLencanaLevel($user);
        $this->cekLencanaJuaraKelas($user);
    }

    // -------------------------------------------------------
    // PRIVATE HELPERS
    // -------------------------------------------------------

    private function beriLencana(int $userId, string $namaLencana, string $icon): void
    {
        DB::table('user_lencanas')->updateOrInsert(
            ['user_id' => $userId, 'nama_lencana' => $namaLencana],
            ['icon' => $icon, 'updated_at' => now(), 'created_at' => now()]
        );
    }

    /**
     * 📚 Rajin Belajar  — selesaikan 5+ materi
     * 🔥 Semangat Tinggi — selesaikan 10+ materi
     */
    private function cekLencanaRajinBelajar(User $user): void
    {
        $jumlah = RiwayatBelajar::where('user_id', $user->id)->count();

        if ($jumlah >= 5) {
            $this->beriLencana($user->id, 'Rajin Belajar', '📚');
        }
        if ($jumlah >= 10) {
            $this->beriLencana($user->id, 'Semangat Tinggi', '🔥');
        }
    }

    /**
     * ⚡ Jagoan Kuis — kerjakan 3+ kuis dengan skor ≥ 80
     */
    private function cekLencanaJagoanKuis(User $user): void
    {
        $kuisBagus = Nilai::where('user_id', $user->id)->where('skor', '>=', 80)->count();

        if ($kuisBagus >= 3) {
            $this->beriLencana($user->id, 'Jagoan Kuis', '⚡');
        }
    }

    /**
     * 🌟 Bintang Kelas — capai level 5 ke atas
     */
    private function cekLencanaLevel(User $user): void
    {
        if (($user->level ?? 1) >= 5) {
            $this->beriLencana($user->id, 'Bintang Kelas', '🌟');
        }
    }

    /**
     * 🥇🥈🥉 Juara 1/2/3 Kelas — peringkat XP tertinggi di kelas
     * Hanya diberikan ke top-3 di kelas.
     * Lencana juara lama dihapus dulu supaya tidak ada siswa yang
     * pegang dua medali sekaligus ketika posisinya berubah.
     */
    private function cekLencanaJuaraKelas(User $user): void
    {
        if (!$user->kelas_id) return;

        // Hapus medali juara lama dulu (posisi bisa berubah)
        DB::table('user_lencanas')
            ->where('user_id', $user->id)
            ->whereIn('nama_lencana', ['Juara 1 Kelas', 'Juara 2 Kelas', 'Juara 3 Kelas'])
            ->delete();

        // Ambil top-3 XP di kelas yang sama
        $topIds = User::where('role', 'siswa')
            ->where('kelas_id', $user->kelas_id)
            ->orderBy('xp', 'desc')
            ->take(3)
            ->pluck('id')
            ->toArray();

        $pos = array_search($user->id, $topIds);
        if ($pos === 0) {
            $this->beriLencana($user->id, 'Juara 1 Kelas', '🥇');
        } elseif ($pos === 1) {
            $this->beriLencana($user->id, 'Juara 2 Kelas', '🥈');
        } elseif ($pos === 2) {
            $this->beriLencana($user->id, 'Juara 3 Kelas', '🥉');
        }
    }
}
