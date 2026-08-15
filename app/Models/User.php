<?php
// File: app/Models/User.php
// FIX: Tambahkan no_wa_wali ke $fillable

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $hidden = ['password', 'remember_token'];

    protected $casts = ['password' => 'hashed'];

    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'role',
        'kelas_id',
        'xp',
        'level',
        'siap_lulus',
        'avatar',
        'no_telp',
        'no_wa_wali',   // [FIX] Ditambahkan ke fillable
        'alamat',
        'nisn',
        // TAMBAHAN: Integrasi Absensi <-> Mifda
        'link_status',
        'linked_student_id',
        'linked_at',
    ];

    public function materis()        { return $this->hasMany(Materi::class, 'user_id'); }
    public function mengampu_mapel() { return $this->belongsToMany(Mapel::class, 'guru_mapels', 'user_id', 'mapel_id'); }
    public function kelas()          { return $this->belongsTo(Kelas::class, 'kelas_id'); }
    public function anak()           { return $this->belongsToMany(User::class, 'ortu_siswa', 'ortu_id', 'siswa_id'); }
    public function orangTua()       { return $this->belongsToMany(User::class, 'ortu_siswa', 'siswa_id', 'ortu_id'); }
    public function nilais()         { return $this->hasMany(Nilai::class, 'user_id'); }
    public function riwayat_belajar(){ return $this->hasMany(RiwayatBelajar::class, 'user_id'); }
}
