<?php
// File: app/Models/Materi.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Materi extends Model
{
    use HasFactory;

    protected $fillable = [
        'mapel_id',
        'user_id',
        'judul',
        'konten',
        'tipe',
        'xp_reward',
        'youtube_link',
        'file_path',
        'jenis',           // [FIX] latihan / ujian_ganjil / ujian_genap
        'jadwal_mulai',    // [FIX] jadwal ujian
        'jadwal_selesai',  // [FIX]
    ];

    protected $casts = [
        'jadwal_mulai'   => 'datetime',
        'jadwal_selesai' => 'datetime',
    ];

    public function mapel()    { return $this->belongsTo(Mapel::class); }
    public function guru()     { return $this->belongsTo(User::class, 'user_id'); }
    public function soals()    { return $this->hasMany(Soal::class); }
    public function riwayats() { return $this->hasMany(RiwayatBelajar::class); }

    // Helper: apakah ini ujian?
    public function isUjian(): bool
    {
        return in_array($this->jenis, ['ujian_ganjil', 'ujian_genap']);
    }

    // Helper: apakah ujian sedang aktif?
    public function isUjianAktif(): bool
    {
        if (!$this->isUjian()) return false;
        $sekarang = now();
        if ($this->jadwal_mulai && $sekarang->lt($this->jadwal_mulai)) return false;
        if ($this->jadwal_selesai && $sekarang->gt($this->jadwal_selesai)) return false;
        return true;
    }
}
