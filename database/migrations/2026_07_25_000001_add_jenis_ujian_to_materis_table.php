<?php
// File: database/migrations/2026_07_25_000001_add_jenis_ujian_to_materis_table.php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('materis', function (Blueprint $table) {
            // Guard: skip kalau kolom sudah ada (aman untuk database lama)
            if (!Schema::hasColumn('materis', 'jenis')) {
                $table->enum('jenis', ['latihan', 'ujian_ganjil', 'ujian_genap'])
                      ->default('latihan')
                      ->after('xp_reward');
            }
            if (!Schema::hasColumn('materis', 'jadwal_mulai')) {
                $table->dateTime('jadwal_mulai')->nullable()->after('jenis');
            }
            if (!Schema::hasColumn('materis', 'jadwal_selesai')) {
                $table->dateTime('jadwal_selesai')->nullable()->after('jadwal_mulai');
            }
        });
    }

    public function down(): void
    {
        Schema::table('materis', function (Blueprint $table) {
            $table->dropColumn(['jenis', 'jadwal_mulai', 'jadwal_selesai']);
        });
    }
};
