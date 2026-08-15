<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('riwayat_belajars', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // ID Siswa
            $table->foreignId('materi_id')->constrained('materis')->onDelete('cascade');
            
            $table->enum('status', ['mulai', 'selesai'])->default('mulai');
            $table->timestamp('tanggal_selesai')->nullable(); // Waktu anak menamatkan materi ini
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('riwayat_belajars');
    }
};
