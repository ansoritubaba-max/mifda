<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * FITUR BARU: Integrasi Absensi <-> Mifda (sinkronisasi data kehadiran).
 *
 * Tabel baru, tidak menyentuh tabel manapun yang sudah ada. Diisi lewat
 * endpoint POST /api/absensi/sync yang dipanggil dari aplikasi Absensi
 * setiap kali guru mengabsen siswa yang akunnya sudah ter-link.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('riwayat_kehadiran', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // akun siswa Mifda
            $table->date('tanggal');
            $table->string('waktu', 20)->nullable(); // contoh: "08:15"
            $table->string('status', 20); // hadir/sakit/izin/alpa (bahasa Indonesia, sama seperti di Absensi)
            $table->string('mapel')->nullable();
            $table->string('guru')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'tanggal']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('riwayat_kehadiran');
    }
};
