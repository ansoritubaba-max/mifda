<?php
// Fix: chats.sender_id & chats.receiver_id → ON DELETE CASCADE
// Sebelumnya RESTRICT (default), menyebabkan error #1451 saat hapus user

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('chats', function (Blueprint $table) {
            // Drop FK lama
            $table->dropForeign(['sender_id']);
            $table->dropForeign(['receiver_id']);

            // Buat ulang FK dengan CASCADE
            $table->foreign('sender_id')
                  ->references('id')->on('users')
                  ->onDelete('cascade');

            $table->foreign('receiver_id')
                  ->references('id')->on('users')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('chats', function (Blueprint $table) {
            $table->dropForeign(['sender_id']);
            $table->dropForeign(['receiver_id']);

            // Kembalikan ke RESTRICT (default)
            $table->foreign('sender_id')->references('id')->on('users');
            $table->foreign('receiver_id')->references('id')->on('users');
        });
    }
};
