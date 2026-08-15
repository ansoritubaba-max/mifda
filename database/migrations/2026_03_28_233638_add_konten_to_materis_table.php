<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
public function up(): void
{
    Schema::table('materis', function (Blueprint $table) {
        // Kita pakai text agar guru bisa input materi yang panjang
        $table->text('konten')->nullable()->after('judul');
    });
}

public function down(): void
{
    Schema::table('materis', function (Blueprint $table) {
        $table->dropColumn('konten');
    });
}
};
