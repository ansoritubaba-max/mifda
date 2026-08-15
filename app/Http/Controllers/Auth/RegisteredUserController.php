<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    public function create(): View
    {
        $daftarKelas = \App\Models\Kelas::all();
        return view('auth.register', compact('daftarKelas'));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255', 'unique:'.User::class],
            'no_telp' => ['required', 'string', 'max:20'], // Ubah ke no_telp sesuai model
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            
            'siswa' => ['required', 'array', 'min:1'],
            'siswa.*.name' => ['required', 'string', 'max:255'],
            'siswa.*.username' => ['required', 'string', 'max:255', 'unique:'.User::class.',username'],
            'siswa.*.kelas_id' => ['required', 'exists:kelas,id'],
            // TAMBAHAN: kode penghubung opsional per anak, buat nyambungin
            // ke data siswa di aplikasi Absensi.
            'siswa.*.kode_penghubung' => ['nullable', 'digits:6'],
        ]);

        DB::beginTransaction();

        try {
            // 2. Simpan Akun Orang Tua (Menggunakan no_telp)
            $ortu = User::create([
                'name' => $request->name,
                'username' => $request->username,
                'email' => $request->username . '@belajar.mi', // Isi email dummy agar tidak error di DB
                'no_telp' => $request->no_telp, // Sesuaikan dengan model
                'role' => 'ortu',
                'password' => Hash::make($request->password),
            ]);

            $teksDaftarAnak = "";
            // TAMBAHAN: kumpulin dulu siswa yang isi kode penghubung, baru
            // diproses SETELAH transaction commit (di bawah). Sengaja gak
            // dipanggil di dalam transaction ini, karena manggil API
            // Absensi itu request jaringan ke luar — kalau ditaruh di
            // dalam transaction, koneksi lambat/gagal ke Absensi bisa
            // bikin transaksi DB ketahan lama atau malah ikut ke-rollback,
            // padahal pendaftaran akun sendiri sudah sukses dan seharusnya
            // tidak boleh gagal gara-gara fitur tambahan ini.
            $siswaUntukLink = [];

            foreach ($request->siswa as $dataSiswa) {
                $siswa = User::create([
                    'name' => $dataSiswa['name'],
                    'username' => $dataSiswa['username'],
                    'email' => $dataSiswa['username'] . '@belajar.mi',
                    'kelas_id' => $dataSiswa['kelas_id'],
                    'role' => 'siswa',
                    'level' => 1,
                    'xp' => 0,
                    'password' => Hash::make($request->password),
                ]);

                DB::table('ortu_siswa')->insert([
                    'ortu_id' => $ortu->id,
                    'siswa_id' => $siswa->id,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                $teksDaftarAnak .= "👨‍🎓 {$siswa->name} (User: {$siswa->username})\n";

                if (!empty($dataSiswa['kode_penghubung'])) {
                    $siswaUntukLink[] = ['siswa' => $siswa, 'kode' => $dataSiswa['kode_penghubung']];
                }
            }

            DB::commit();

            // Kirim WA menggunakan data no_telp yang baru disimpan
            // KODE BARU (Tambahkan $request->password)
            $this->kirimWaPendaftaranSukses($ortu, $teksDaftarAnak, $request->password);

            // TAMBAHAN: proses kode penghubung (kalau ada yang diisi).
            // Dibungkus try/catch per-anak — kalau 1 kode gagal diproses
            // (misal API Absensi lagi down), gak boleh ganggu proses
            // pendaftaran yang sudah selesai duluan.
            foreach ($siswaUntukLink as $item) {
                try {
                    app(\App\Services\LinkCodeService::class)->linkByCode($item['siswa'], $item['kode']);
                } catch (\Throwable $e) {
                    \Illuminate\Support\Facades\Log::warning("[LinkCode] Gagal proses kode saat registrasi untuk {$item['siswa']->name}: " . $e->getMessage());
                }
            }

            event(new Registered($ortu));
            Auth::login($ortu);

            return redirect('/dashboard');

        } catch (\Illuminate\Database\QueryException $e) {
            DB::rollBack();

            // BUGFIX: sebelumnya pesan SQLSTATE mentah ditampilkan langsung
            // ke user (contoh: "SQLSTATE[23000]: Integrity constraint
            // violation: 1062 Duplicate entry..."). Sekarang dideteksi
            // spesifik buat kasus username bentrok (kode error 1062/23000)
            // dan dikasih pesan yang manusiawi. Kemungkinan besar
            // penyebabnya: submit form 2x (klik ganda/reload), atau
            // username anak kebetulan sama dengan akun yang sudah ada.
            if (str_contains($e->getMessage(), '1062') || $e->getCode() === '23000') {
                return back()
                    ->withErrors(['error' => 'Username yang dipilih sudah ada yang pakai (mungkin dari percobaan daftar sebelumnya). Coba ganti username wali atau ananda, lalu daftar ulang.'])
                    ->withInput();
            }

            \Illuminate\Support\Facades\Log::error('[Registrasi] QueryException: ' . $e->getMessage());

            return back()->withErrors(['error' => 'Terjadi kendala teknis saat menyimpan data. Coba lagi beberapa saat lagi.'])->withInput();
        } catch (\Throwable $e) {
            DB::rollBack();
            \Illuminate\Support\Facades\Log::error('[Registrasi] Error tak terduga: ' . $e->getMessage());

            return back()->withErrors(['error' => 'Terjadi kendala teknis, coba lagi beberapa saat lagi.'])->withInput();
        }
    }

/**
     * Fungsi privat untuk mengirim pesan Fonnte
     */
    private function kirimWaPendaftaranSukses($ortu, $teksDaftarAnak, $passwordAsli)
    {
        $token = env('FONNTE_TOKEN');
        if (!$token) return;

        $pesan = "🎉 *Pendaftaran Berhasil!* 🎉\n\n";
        $pesan .= "Halo Wali Murid *{$ortu->name}*.\n";
        $pesan .= "Username Anda: *{$ortu->username}*\n";
        $pesan .= "Password Anda: *{$passwordAsli}*\n\n"; // Menampilkan password asli
        $pesan .= "📚 *Akun Ananda:*\n{$teksDaftarAnak}\n";
        $pesan .= "Password ananda disamakan dengan password Anda. Silakan login! 🚀";

        try {
            \Illuminate\Support\Facades\Http::withoutVerifying()
                ->withHeaders(['Authorization' => $token])
                ->post('https://api.fonnte.com/send', [
                    'target' => $ortu->no_telp, 
                    'message' => $pesan,
                ]);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Fonnte Error: ' . $e->getMessage());
        }
    }
}