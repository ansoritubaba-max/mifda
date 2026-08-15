<?php

use Illuminate\Database\Migrations\Migration;

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('opsi_jawabans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('soal_id')->constrained('soals')->onDelete('cascade');
            
            $table->string('teks_opsi')->nullable();
            $table->string('gambar_opsi')->nullable(); // Jika jawabannya berupa gambar (cocok untuk anak MI)
            $table->boolean('is_benar')->default(false); // Tandai mana jawaban yang benar
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('opsi_jawabans');
    }
};