<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notifikasi extends Model
{
    protected $fillable = [
        'user_id',
        'judul',
        'pesan',
        'tipe',
        'icon',
        'url',
        'dibaca_at',
    ];

    protected $casts = [
        'dibaca_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeBelumDibaca($query)
    {
        return $query->whereNull('dibaca_at');
    }
}
