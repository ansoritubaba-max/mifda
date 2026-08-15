<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('materis', function (Blueprint $table) {
            $table->id();
            // Menyambungkan materi ke mapel tertentu
            $table->foreignId('mapel_id')->constrained('mapels')->onDelete('cascade');
            
            $table->string('judul'); 
            $table->text('deskripsi')->nullable(); // Penjelasan singkat materi
            $table->enum('tipe', ['video', 'pdf', 'teks', 'animasi']); // Sesuai format Anda
            $table->string('file_url')->nullable(); // Link ke YouTube atau file PDF
            
            // GAMIFIKASI: Berapa XP yang didapat anak jika selesai baca materi ini?
            $table->integer('xp_reward')->default(10); 
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('materis');
    }
};