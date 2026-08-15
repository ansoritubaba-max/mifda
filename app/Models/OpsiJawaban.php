<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OpsiJawaban extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    // Relasi: Opsi Jawaban dimiliki oleh 1 Soal
    public function soal()
    {
        return $this->belongsTo(Soal::class);
    }
}