<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // 🚀 0. BANGUN RUANG KELASNYA DULU!
        // (Pastikan nama kolomnya sesuai, biasanya 'nama_kelas' atau 'nama')
        DB::table('kelas')->insert([
            'id' => 1,
            'nama_kelas' => 'Kelas 1', // Ganti jadi 'nama' kalau di migrationmu pakenya 'nama'
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 1. Buat akun Admin
        User::create([
            'name' => 'Admin Utama',
            'username' => 'admin',
            'password' => Hash::make('password'),
            'role' => 'admin',
        ]);

        // 2. Buat akun Guru
        User::create([
            'name' => 'Bapak Budi (Guru)',
            'username' => 'guru1',
            'password' => Hash::make('password'),
            'role' => 'guru',
        ]);

        // 3. Buat akun Orang Tua 
        $ortu = User::create([
            'name' => 'Ibu Siti (Ortu)',
            'username' => 'ortu1',
            'password' => Hash::make('password'),
            'role' => 'ortu',
            'no_wa_wali' => '081511191869', // Nomor WA buat ngetes
        ]);

        // 4. Buat akun Siswa 
        $siswa = User::create([
            'name' => 'Andi',
            'username' => 'andi123',
            'password' => Hash::make('password'),
            'role' => 'siswa',
            'kelas_id' => 1, // Sekarang aman, karena Kelas 1 sudah dibangun di atas!
            'xp' => 150, 
            'level' => 2,
        ]);

        // 5. Hubungkan Ortu & Siswa di tabel Jembatan
        DB::table('ortu_siswa')->insert([
            'ortu_id' => $ortu->id,
            'siswa_id' => $siswa->id,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}