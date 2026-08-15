<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mapels', function (Blueprint $table) {
            $table->id();
            // Menyambungkan mapel ke kelas tertentu
            $table->foreignId('kelas_id')->constrained('kelas')->onDelete('cascade'); 
            
            $table->string('nama_mapel'); // Contoh: "Matematika", "Akidah Akhlak"
            $table->string('warna_tema')->nullable(); // Contoh: "#FFD700" (Kuning untuk UI ceria)
            $table->string('icon')->nullable(); // Nama file gambar icon lucu
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mapels');
    }
};