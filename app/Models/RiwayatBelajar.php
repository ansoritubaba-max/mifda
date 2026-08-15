<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RiwayatBelajar extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    // Relasi: Riwayat belajar terhubung ke 1 Materi
    public function materi()
    {
        return $this->belongsTo(Materi::class);
    }

    // Relasi: Riwayat belajar milik 1 Siswa
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}