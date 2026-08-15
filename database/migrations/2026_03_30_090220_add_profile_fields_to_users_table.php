<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('avatar')->nullable()->after('password'); // Foto Profil
            $table->string('no_telp', 20)->nullable()->after('avatar'); // No HP (Guru/Ortu)
            $table->text('alamat')->nullable()->after('no_telp'); // Alamat
            $table->string('nisn', 20)->unique()->nullable()->after('alamat'); // NISN Siswa
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['avatar', 'no_telp', 'alamat', 'nisn']);
        });
    }
};