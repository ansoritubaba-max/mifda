<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RiwayatKehadiran extends Model
{
    protected $table = 'riwayat_kehadiran';

    protected $fillable = [
        'user_id',
        'tanggal',
        'waktu',
        'status',
        'mapel',
        'guru',
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
