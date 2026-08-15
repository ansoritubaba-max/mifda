<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Jalankan migration untuk membuat tabel users.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Nama pengguna
            $table->string('username')->unique(); // Untuk login anak-anak, username lebih mudah daripada email
            $table->string('password');
            
            // Kolom Role untuk membedakan hak akses
            $table->enum('role', ['admin', 'guru', 'ortu', 'siswa'])->default('siswa');
            
            // Fitur Gamifikasi Dasar (Khusus untuk role 'siswa')
            $table->integer('xp')->default(0); // Poin experience
            $table->integer('level')->default(1); // Level saat ini
            $table->integer('streak_hari')->default(0); // Untuk fitur daily streak
            
            // Relasi (Opsional saat ini, tapi disiapkan: ID orang tua untuk siswa)
            $table->unsignedBigInteger('ortu_id')->nullable();

            $table->rememberToken();
            $table->timestamps();
            
            // Membuat foreign key agar akun siswa terhubung ke akun orang tua
            $table->foreign('ortu_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Kembalikan (hapus) migration.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};