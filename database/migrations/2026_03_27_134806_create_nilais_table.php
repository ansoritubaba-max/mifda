<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('nilais', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // ID Siswa
            $table->foreignId('materi_id')->constrained('materis')->onDelete('cascade'); // Kuis dari materi apa
            
            $table->integer('skor'); // Nilai 0-100
            $table->integer('total_benar');
            $table->integer('total_salah');
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('nilais');
    }
};