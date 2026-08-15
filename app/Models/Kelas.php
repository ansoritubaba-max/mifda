<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kelas extends Model
{
    use HasFactory;

    protected $guarded = ['id']; // Amankan hanya ID

    // Relasi: 1 Kelas punya banyak Mapel
    public function mapels()
    {
        return $this->hasMany(Mapel::class);
    }

    // Relasi: 1 Kelas punya banyak Siswa
    public function siswas()
    {
        return $this->hasMany(User::class, 'kelas_id');
    }
}