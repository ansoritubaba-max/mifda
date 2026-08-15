<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Mapel extends Model
{
    use HasFactory;
    
    protected $guarded = ['id'];

    // Relasi: Mapel ini milik 1 Kelas
    public function kelas()
    {
        return $this->belongsTo(Kelas::class);
    }

    // Relasi: 1 Mapel punya banyak Materi
    public function materis()
    {
        return $this->hasMany(Materi::class);
    }

    // Relasi: Pivot Table untuk Guru Pengampu
    public function pengampu()
    {
        return $this->belongsToMany(User::class, 'guru_mapels', 'mapel_id', 'user_id');
    }
}