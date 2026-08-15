<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Kelas;
use App\Models\Mapel;

class KelasMapelSeeder extends Seeder
{
    public function run(): void
    {
        // Buat Kelas 1
        $kelas1 = Kelas::create(['nama_kelas' => 'Kelas 1']);
        
        // Buat Mapel untuk Kelas 1
        Mapel::create([
            'kelas_id' => $kelas1->id,
            'nama_mapel' => 'Matematika',
            'warna_tema' => '#FFD700', // Warna Kuning ceria
            'icon' => 'math_icon.png'
        ]);

        Mapel::create([
            'kelas_id' => $kelas1->id,
            'nama_mapel' => 'Akidah Akhlak',
            'warna_tema' => '#32CD32', // Warna Hijau ceria
            'icon' => 'agama_icon.png'
        ]);
    }
}