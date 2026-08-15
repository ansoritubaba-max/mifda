<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Guard: skip kalau tabel sudah ada
        if (Schema::hasTable('notifikasis')) return;

        // Tabel notifikasi in-app (Laravel standard notifications)
        Schema::create('notifikasis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('judul');
            $table->text('pesan');
            $table->string('tipe')->default('info'); // info, materi, ujian, nilai, pesan
            $table->string('icon')->default('📢');
            $table->string('url')->nullable();        // URL tujuan saat diklik
            $table->timestamp('dibaca_at')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'dibaca_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notifikasis');
    }
};
