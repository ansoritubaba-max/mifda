<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * FITUR BARU: Integrasi Absensi <-> Mifda (kode penghubung akun ortu).
 *
 * Migration ini cuma NAMBAH kolom baru ke tabel users, tidak mengubah
 * atau menghapus kolom yang sudah ada. Aman dijalankan di data production
 * yang sudah ada isinya — semua user lama (siswa/ortu/guru/admin) otomatis
 * dapat link_status = 'unlinked', tidak ada yang berubah/hilang. Kolom
 * `nisn` yang sudah ada sebelumnya dipakai apa adanya (diisi otomatis
 * begitu link berhasil, kalau sebelumnya masih kosong).
 *
 * - link_status      : 'unlinked' (default, belum nyambung ke Absensi)
 *                      atau 'verified' (sudah nyambung, lewat kode WA yang
 *                      sudah tervalidasi — tidak ada status 'pending',
 *                      karena proses ini auto-link begitu kode valid).
 * - linked_student_id : ID siswa versi aplikasi Absensi (bukan foreign key
 *                      asli karena beda database/server, cuma referensi
 *                      angka). Unique supaya 1 siswa Absensi cuma bisa
 *                      kepasang ke 1 akun siswa Mifda.
 * - linked_at         : kapan link ini terjadi, buat keperluan audit/tampilan.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('link_status', 20)->default('unlinked')->after('nisn');
            $table->unsignedBigInteger('linked_student_id')->nullable()->unique()->after('link_status');
            $table->timestamp('linked_at')->nullable()->after('linked_student_id');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['link_status', 'linked_student_id', 'linked_at']);
        });
    }
};
