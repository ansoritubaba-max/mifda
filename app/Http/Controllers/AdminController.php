<?php

namespace App\Http\Controllers;

use App\Models\Mapel;
use App\Models\Kelas;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class AdminController extends Controller
{
    public function dashboard()
    {
        $totalSiswa = User::where('role', 'siswa')->count();
        $totalGuru = User::where('role', 'guru')->count();
        $mapels = Mapel::with('kelas', 'pengampu')->latest()->get();
        $kelas = Kelas::all();
        $gurus = User::where('role', 'guru')->get();

        return view('admin.dashboard', compact('totalSiswa', 'totalGuru', 'mapels', 'kelas', 'gurus'));
    }

    // --- KELOLA USER (GURU, SISWA, ORTU, ADMIN) ---
    public function indexUser()
    {
        // Tarik data relasi anak juga agar bisa ditampilkan di tabel
        $users = User::with(['kelas', 'mengampu_mapel', 'anak'])->latest()->get(); 
        $kelas = \App\Models\Kelas::all();
        
        // Tarik daftar siswa untuk dipilih oleh Ortu
        $siswas = User::where('role', 'siswa')->orderBy('name', 'asc')->get(); 
        
        return view('admin.user.index', compact('users', 'kelas', 'siswas'));
    }

    public function storeUser(Request $request)
    {
        // 1. Validasi tetap sama, pastikan 'avatar' ada untuk handle file
        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users',
            'email' => 'nullable|email|unique:users',
            'password' => 'required|string|min:6',
            'role' => 'required|in:admin,guru,ortu,siswa',
            'kelas_id' => 'nullable|exists:kelas,id',
            'nisn' => 'nullable|string|unique:users,nisn',
            'no_telp' => 'nullable|string',
            'alamat' => 'nullable|string',
            'anak_id' => 'nullable|array', 
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048', 
        ]);

        // 2. Proses upload foto ke LOCAL STORAGE (Diperbaiki agar masuk folder 'avatars')
        $avatarPath = null;
        if ($request->hasFile('avatar')) {
            $file = $request->file('avatar');
            // Menghapus spasi pada nama file asli agar lebih aman
            $safeFileName = str_replace(' ', '_', $file->getClientOriginalName());
            $avatarName = time() . '_' . $safeFileName;
            
            // 🚀 Simpan ke Local Storage di dalam folder 'avatars'
            $avatarPath = $file->storeAs('avatars', $avatarName, 'public');
        }

        // 3. Simpan User Sesuai Logika Asli (Avatar diisi path lengkap: avatars/namafile.jpg)
        $user = \App\Models\User::create([
            'name' => $request->name,
            'username' => $request->username,
            'email' => $request->email,
            'password' => bcrypt($request->password),
            'role' => $request->role,
            'kelas_id' => $request->role === 'siswa' ? $request->kelas_id : null,
            'nisn' => $request->role === 'siswa' ? $request->nisn : null,
            'no_telp' => $request->no_telp,
            'alamat' => $request->alamat,
            'avatar' => $avatarPath, // Menyimpan path: avatars/....jpg
        ]);

        // 4. Hubungkan ke Pivot Table
        if ($request->role === 'ortu' && $request->has('anak_id')) {
            $user->anak()->attach($request->anak_id);
        }

        // 🚀 5. Kirim WA Otomatis via Fonnte
        if (!empty($user->no_telp)) {
            $token = env('FONNTE_TOKEN');
            
            if ($token) {
                $namaRole = match($user->role) {
                    'admin' => '👑 Admin',
                    'guru'  => '👩‍🏫 Guru',
                    'ortu'  => '👨‍👩‍👧 Orang Tua (Wali Murid)',
                    'siswa' => '👦 Siswa',
                    default => $user->role
                };

                $pesan = "🎉 *Pemberitahuan Sistem* 🎉\n\n";
                $pesan .= "Halo *{$user->name}*,\n";
                $pesan .= "Data Anda telah berhasil ditambahkan ke dalam MIFDA (MI Miftahul Huda).\n\n";
                $pesan .= "Berikut detail akun Anda:\n";
                $pesan .= "👤 *Username:* {$user->username}\n";
                $pesan .= "🔑 *Password:* {$request->password}\n";
                $pesan .= "🏷️ *Peran (Role):* {$namaRole}\n\n";
                $pesan .= "Silakan login menggunakan data di atas. Selamat bergabung! 🚀";

                try {
                    \Illuminate\Support\Facades\Http::withoutVerifying()
                        ->withHeaders(['Authorization' => $token])
                        ->post('https://api.fonnte.com/send', [
                            'target' => $user->no_telp,
                            'message' => $pesan,
                        ]);
                } catch (\Exception $e) {
                    \Illuminate\Support\Facades\Log::error('Fonnte Error: ' . $e->getMessage());
                }
            }
        }

        return back()->with('success', 'User berhasil dibuat, foto tersimpan di Local Storage & WA terkirim! ✅');
    }

    public function updateUser(Request $request, $id)
    {
        $user = \App\Models\User::findOrFail($id);
        
        $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username,'.$id, 
            'email' => 'nullable|email|unique:users,email,'.$id,
            'role' => 'required|in:admin,guru,ortu,siswa',
            'nisn' => 'nullable|string|unique:users,nisn,'.$id,
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // Ambil data dasar
        $data = $request->only(['name', 'username', 'email', 'role', 'no_telp', 'alamat']);
        
        // Update password jika diisi
        if ($request->filled('password')) {
            $data['password'] = bcrypt($request->password);
        }

        // Atur Kelas & NISN
        if ($request->role === 'siswa') {
            $data['kelas_id'] = $request->kelas_id;
            $data['nisn'] = $request->nisn;
        } else {
            $data['kelas_id'] = null;
            $data['nisn'] = null;
        }

        // Eksekusi update data teks
        $user->update($data);

        // Sinkronisasi Relasi Anak jika role-nya Ortu
        if ($request->role === 'ortu') {
            if ($request->has('anak_id')) {
                $user->anak()->sync($request->anak_id); 
            } else {
                $user->anak()->detach(); 
            }
        }

        // 🚀 LOGIKA UPLOAD AVATAR KE LOCAL STORAGE (Diperbaiki agar masuk folder 'avatars')
        if ($request->hasFile('avatar')) {
            
            // 1. Hapus foto lama di Local Storage jika ada
            if ($user->avatar && \Illuminate\Support\Facades\Storage::disk('public')->exists($user->avatar)) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($user->avatar);
            }

            // 2. Simpan foto baru ke folder 'avatars' di Local Storage
            $file = $request->file('avatar');
            $safeFileName = str_replace(' ', '_', $file->getClientOriginalName());
            $fileName = time() . '_' . $safeFileName;
            
            $path = $file->storeAs('avatars', $fileName, 'public');
            
            // 3. Update nama file di database
            $user->avatar = $path;
            $user->save();
        }

        return back()->with('success', 'Alhamdulillah, data user dan foto di Local Storage berhasil diperbarui! ✏️✅');
    }

    // --- KELOLA RELASI GURU & MAPEL ---
    public function setPengampu(Request $request)
    {
        $request->validate([
            'guru_id' => 'required|exists:users,id',
            'mapel_id' => 'nullable|array' // array dari checkbox
        ]);

        // Hapus semua relasi mengajar lama milik guru ini
        \Illuminate\Support\Facades\DB::table('guru_mapels')->where('user_id', $request->guru_id)->delete();

        // Jika ada mapel yang dicentang, masukkan satu per satu ke database
        if ($request->has('mapel_id')) {
            $dataInsert = [];
            foreach ($request->mapel_id as $mapelId) {
                $dataInsert[] = [
                    'user_id'  => $request->guru_id,
                    'mapel_id' => $mapelId,
                ];
            }
            // Insert massal yang aman dari error 'Unknown column 0'
            \Illuminate\Support\Facades\DB::table('guru_mapels')->insert($dataInsert);
        }

        return back()->with('success', 'Tugas mengajar Guru berhasil diperbarui! 👩‍🏫');
    }

    // --- KELOLA KELAS ---
    public function indexKelas() {
        $kelas = Kelas::withCount('mapels')->get();
        return view('admin.kelas.index', compact('kelas'));
    }

    public function storeKelas(Request $request) {
        $request->validate(['nama_kelas' => 'required|unique:kelas,nama_kelas']);
        Kelas::create($request->all());
        return back()->with('success', 'Kelas berhasil ditambah!');
    }

    public function destroyKelas($id) {
        Kelas::findOrFail($id)->delete();
        return back()->with('success', 'Kelas dihapus!');
    }

    // --- KELOLA MAPEL ---
    public function indexMapel() {
        $mapels = Mapel::with('kelas', 'pengampu')->latest()->get();
        $kelas = Kelas::all();
        return view('admin.mapel.index', compact('mapels', 'kelas'));
    }

    public function storeMapel(Request $request)
    {
        $request->validate([
            'kelas_id' => 'required|exists:kelas,id',
            'nama_mapel' => 'required|string',
            'warna_tema' => 'required',
        ]);

        Mapel::create($request->all());
        return back()->with('success', 'Pelajaran baru siap diisi materi! 📚');
    }

    public function destroyMapel($id)
    {
        Mapel::findOrFail($id)->delete();
        return back()->with('success', 'Pelajaran Dihapus! 🗑️');
    }

    // --- FITUR SUPERVISI & KELULUSAN ---
    public function kelulusanMassal()
    {
        $siswas = User::where('role', 'siswa')
                      ->where('siap_lulus', true)
                      ->with('kelas')
                      ->get();
                      
        return view('admin.kelulusan.index', compact('siswas'));
    }

    public function prosesLulusSemua(Request $request)
    {
        // [FIX] Jangan pakai kelas_id + 1 (asumsi ID berurutan = bahaya)
        // Gunakan query seperti naikKelasMassal: cari kelas berikutnya berdasarkan urutan
        $semuaKelas = \App\Models\Kelas::orderBy('nama_kelas', 'asc')->get();
        $siswas     = User::where('role', 'siswa')->where('siap_lulus', true)->get();

        foreach ($siswas as $siswa) {
            $currentKelas = $semuaKelas->firstWhere('id', $siswa->kelas_id);
            $nextKelas    = $currentKelas
                ? $semuaKelas->where('id', '>', $currentKelas->id)->first()
                : null;

            if ($nextKelas) {
                // Naik ke kelas berikutnya
                $siswa->update([
                    'kelas_id'  => $nextKelas->id,
                    'siap_lulus'=> false,
                    // [FIX] XP tidak di-reset agar konsisten dengan naikKelasMassal()
                ]);
            } else {
                // Sudah kelas tertinggi → alumni
                $siswa->update([
                    'role'      => 'alumni',
                    'kelas_id'  => null,
                    'siap_lulus'=> false,
                ]);
            }
        }

        return back()->with('success', 'Selamat! Semua siswa yang direkomendasikan berhasil naik kelas! 🎓');
    }

    public function naikKelasMassal()
    {
        try {
            DB::transaction(function () {
                $semuaKelas = Kelas::orderBy('nama_kelas', 'asc')->get();
                $siswas = User::where('role', 'siswa')->whereNotNull('kelas_id')->get();

                foreach ($siswas as $siswa) {
                    $currentKelas = $semuaKelas->where('id', $siswa->kelas_id)->first();
                    $nextKelas = $semuaKelas->where('id', '>', $currentKelas->id)->first();

                    if ($nextKelas) {
                        $siswa->update(['kelas_id' => $nextKelas->id]);
                    } else {
                        $siswa->update(['role' => 'alumni', 'kelas_id' => null]);
                    }
                }
            });
            return back()->with('success', 'Seluruh siswa berhasil naik kelas & lulus! 🎓🚀');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memproses naik kelas: ' . $e->getMessage());
        }
    }

    public function pantauGuru()
    {
        $gurus = User::where('role', 'guru')
                    ->withCount(['materis', 'mengampu_mapel'])
                    ->get();
                    
        return view('admin.aktivitas_guru', compact('gurus'));
    }

    // ==========================================
    // FITUR KELOLA RELASI (HALAMAN BARU)
    // ==========================================
    public function indexRelasi()
    {
        $gurus = User::where('role', 'guru')->with('mengampu_mapel.kelas')->get();
        $ortus = User::where('role', 'ortu')->with('anak.kelas')->get();
        
        $siswas = User::where('role', 'siswa')->orderBy('name')->get();
        $mapels = \App\Models\Mapel::with('kelas')->orderBy('nama_mapel')->get();

        return view('admin.relasi.index', compact('gurus', 'ortus', 'siswas', 'mapels'));
    }

    public function storeRelasiOrtu(Request $request)
    {
        $request->validate([
            'ortu_id' => 'required|exists:users,id',
            'anak_id' => 'nullable|array' // array dari checkbox
        ]);

        // Hapus semua daftar anak lama milik ortu ini
        \Illuminate\Support\Facades\DB::table('ortu_siswa')->where('ortu_id', $request->ortu_id)->delete();

        // Jika ada anak yang dicentang, masukkan satu per satu ke database
        if ($request->has('anak_id')) {
            $dataInsert = [];
            foreach ($request->anak_id as $anakId) {
                $dataInsert[] = [
                    'ortu_id'    => $request->ortu_id,
                    'siswa_id'   => $anakId,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
            // Insert massal yang aman
            \Illuminate\Support\Facades\DB::table('ortu_siswa')->insert($dataInsert);
        }

        return back()->with('success', 'Relasi Orang Tua dan Anak berhasil diperbarui! 👨‍👩‍👧');
    }

    public function destroyUser($id)
    {
        $user = \App\Models\User::findOrFail($id);

        try {
            // Hapus avatar dari storage dulu (tidak ada FK, aman dilakukan duluan)
            if ($user->avatar) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($user->avatar);
            }

            // Hapus user — semua relasi di database sudah pakai ON DELETE CASCADE,
            // jadi MySQL otomatis hapus: chats, guru_mapels, ortu_siswa, materis,
            // soals, nilais, riwayat_belajars, notifikasis, push_subscriptions, user_lencanas
            $user->delete();

            return back()->with('success', 'Berhasil! Akun pengguna dihapus permanen. 🗑️');

        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('destroyUser error id=' . $id . ': ' . $e->getMessage());
            return back()->with('error', 'Gagal menghapus: ' . $e->getMessage());
        }
    }
}