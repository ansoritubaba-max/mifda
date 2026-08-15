<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kelas', function (Blueprint $table) {
            $table->id();
            $table->string('nama_kelas'); // Contoh: "Kelas 1", "Kelas 2"
            $table->timestamps();
            $table->foreignId('kelas_id')->nullable()->constrained('kelas')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kelas');
    }
};