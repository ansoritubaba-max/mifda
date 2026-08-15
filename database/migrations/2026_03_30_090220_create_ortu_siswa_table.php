<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ortu_siswa', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ortu_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('siswa_id')->constrained('users')->onDelete('cascade');
            $table->timestamps();

            // Memastikan tidak ada data ganda (1 Ortu tidak bisa dihubungkan ke 1 Anak yang sama 2 kali)
            $table->unique(['ortu_id', 'siswa_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ortu_siswa');
    }
};