<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * FITUR BARU: Integrasi Absensi <-> Mifda (kode penghubung akun ortu).
 *
 * Titik masuk tunggal buat proses "nyambungin" akun siswa Mifda ke data
 * siswa di aplikasi Absensi, dipanggil dari 2 tempat:
 * - RegisteredUserController::store (ortu isi kode pas daftar akun baru)
 * - OrtuController::hubungkanKode   (ortu isi kode belakangan lewat dashboard)
 *
 * Alurnya: panggil API Absensi (POST /api/link/verify-code). Kalau kode
 * valid & belum dipakai, Absensi langsung menandainya 'claimed' di sisi
 * dia dan balikin data siswa — di sini langsung di-auto-link tanpa
 * approval manual tambahan, karena kode itu sendiri sudah terverifikasi
 * lewat pengiriman WA ke nomor resmi orang tua (setara verifikasi OTP).
 */
class LinkCodeService
{
    /**
     * @return array{success: bool, message: string}
     */
    public function linkByCode(User $siswa, string $code): array
    {
        if ($siswa->link_status === 'verified') {
            return [
                'success' => false,
                'message' => "Akun {$siswa->name} sudah tersambung ke data absensi sebelumnya.",
            ];
        }

        $baseUrl = config('services.integration.absensi_base_url');
        $token = config('services.integration.token');

        if (empty($baseUrl) || empty($token)) {
            Log::error('[LinkCode] Konfigurasi integrasi Absensi belum lengkap (ABSENSI_API_BASE_URL / INTEGRATION_API_TOKEN kosong di .env).');

            return [
                'success' => false,
                'message' => 'Fitur penghubungan sedang tidak tersedia, coba lagi nanti.',
            ];
        }

        // PENTING: seluruh proses manggil + baca response dibungkus 1
        // try/catch(\Throwable) besar — bukan cuma bagian Http::post()
        // doang. Sebelumnya $response->json(...) dipanggil DI LUAR
        // try/catch, jadi kalau Absensi (lewat ngrok) lagi mati/gak
        // kejangkau dan balikin halaman HTML (bukan JSON), proses parsing
        // itu ikut gagal dan bikin request Mifda 500 — bukan pesan error
        // yang rapi. \Throwable dipakai (bukan cuma \Exception) supaya
        // ke-tangkep juga error tipe \Error/\TypeError yang gak diwarisi
        // dari \Exception.
        try {
            $response = Http::withToken($token)
                // Absensi lokal diakses lewat tunnel ngrok. Tanpa header
                // ini, ngrok versi gratis bisa nyelipin halaman "warning"
                // HTML sebelum request nyampe ke Laravel. Aman dibiarkan
                // nempel terus meskipun nanti pindah ke domain live asli.
                ->withHeaders(['ngrok-skip-browser-warning' => 'true'])
                ->timeout(10)
                ->post(rtrim($baseUrl, '/') . '/api/link/verify-code', [
                    'code' => $code,
                ]);

            if ($response->status() === 404) {
                return [
                    'success' => false,
                    'message' => 'Kode tidak ditemukan. Periksa kembali kode yang dikirim sekolah.',
                ];
            }

            if ($response->status() === 409) {
                return [
                    'success' => false,
                    'message' => 'Kode ini sudah pernah dipakai untuk menghubungkan akun lain. Hubungi sekolah kalau ini keliru.',
                ];
            }

            if (!$response->successful() || !$response->json('success')) {
                Log::warning('[LinkCode] Respons tak terduga dari Absensi: ' . $response->status() . ' — ' . $response->body());

                return [
                    'success' => false,
                    'message' => 'Gagal memverifikasi kode, coba lagi nanti. Pastikan aplikasi Absensi sedang aktif.',
                ];
            }

            $student = $response->json('student');

            if (empty($student['id'])) {
                Log::warning('[LinkCode] Respons Absensi sukses tapi data student kosong/rusak: ' . $response->body());

                return [
                    'success' => false,
                    'message' => 'Gagal memverifikasi kode, coba lagi nanti.',
                ];
            }
        } catch (\Throwable $e) {
            Log::error('[LinkCode] Gagal menghubungi/membaca respons Absensi: ' . $e->getMessage());

            return [
                'success' => false,
                'message' => 'Tidak bisa menghubungi sistem sekolah saat ini. Pastikan aplikasi Absensi sedang aktif, lalu coba lagi.',
            ];
        }

        // BUGFIX: query cek NISN bentrok sebelumnya ada DI LUAR try/catch
        // di bawah ini — kalau query itu sendiri gagal (misal koneksi DB
        // kepusat), errornya gak ketangkep. Sekarang semua digabung dalam
        // 1 try/catch(\Throwable).
        try {
            $dataUpdate = [
                'link_status' => 'verified',
                'linked_student_id' => $student['id'],
                'linked_at' => now(),
            ];

            // Isi NISN otomatis kalau masih kosong di akun Mifda — aman
            // dilakukan di sini karena sudah lewat verifikasi kode WA
            // (bukan tebak-tebakan nama+kelas). Tetap dicek dulu belum
            // dipakai akun lain, karena kolom nisn di Mifda unique.
            if (empty($siswa->nisn) && !empty($student['nisn'])) {
                $nisnDipakaiAkunLain = User::where('nisn', $student['nisn'])
                    ->where('id', '!=', $siswa->id)
                    ->exists();

                if (!$nisnDipakaiAkunLain) {
                    $dataUpdate['nisn'] = $student['nisn'];
                }
            }

            $siswa->update($dataUpdate);
        } catch (\Throwable $e) {
            Log::error("[LinkCode] Gagal simpan hasil link untuk {$siswa->name}: " . $e->getMessage());

            return [
                'success' => false,
                'message' => 'Kode valid, tapi terjadi kendala teknis saat menyimpan. Coba lagi.',
            ];
        }

        Log::info("[LinkCode] Berhasil link {$siswa->name} (Mifda #{$siswa->id}) ke Student Absensi #{$student['id']}.");

        return [
            'success' => true,
            'message' => "Berhasil! Data kehadiran {$student['name']} akan mulai muncul di dashboard.",
        ];
    }
}
