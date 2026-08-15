<?php
// File: database/migrations/2026_03_28_110608_create_guru_extras_table.php
// FIX: Perbaiki method down() yang salah — sebelumnya drop 'guru_extras' yang tidak ada

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('games', function (Blueprint $table) {
            $table->id();
            $table->string('judul');
            $table->text('link_game');
            $table->foreignId('mapel_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });

        Schema::create('chats', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sender_id')->constrained('users');
            $table->foreignId('receiver_id')->constrained('users');
            $table->text('pesan');
            $table->boolean('is_read')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        // [FIX] Drop tabel yang benar-benar dibuat di atas
        Schema::dropIfExists('chats');
        Schema::dropIfExists('games');
    }
};
