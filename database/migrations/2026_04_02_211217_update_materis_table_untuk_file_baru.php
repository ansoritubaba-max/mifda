<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('materis', function (Blueprint $table) {
            // Ubah tipe ENUM menjadi String agar bisa menerima semua tipe materi
            $table->string('tipe', 50)->change();
            
            // Tambahkan kolom untuk menyimpan file dan link youtube
            $table->string('youtube_link')->nullable()->after('tipe');
            $table->string('file_path')->nullable()->after('youtube_link');
        });
    }

    public function down(): void
    {
        Schema::table('materis', function (Blueprint $table) {
            $table->dropColumn(['youtube_link', 'file_path']);
        });
    }
};