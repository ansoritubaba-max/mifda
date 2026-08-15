<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('soals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('mapel_id')->constrained()->onDelete('cascade');
            $table->foreignId('materi_id')->nullable()->constrained()->onDelete('cascade');
            $table->text('pertanyaan');
            $table->string('gambar')->nullable(); // Taruh di sini, tidak perlu pakai ->after()
            $table->string('audio')->nullable();
            $table->enum('tipe', ['pilihan_ganda', 'isian'])->default('pilihan_ganda');
            $table->string('kunci_jawaban_isian')->nullable();
            $table->integer('xp_reward')->default(5);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('soals');
    }
};