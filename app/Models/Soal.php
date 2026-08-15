<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Soal extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    // Relasi: Soal dimiliki oleh Materi
    public function materi()
    {
        return $this->belongsTo(Materi::class);
    }

    // Relasi: 1 Soal punya banyak Opsi Jawaban
    public function opsi_jawaban()
    {
        return $this->hasMany(OpsiJawaban::class);
    }
}