<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Nilai extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    // Relasi: Nilai ini milik 1 User (Siswa)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi: Nilai ini untuk 1 Materi
    public function materi()
    {
        return $this->belongsTo(Materi::class);
    }
}