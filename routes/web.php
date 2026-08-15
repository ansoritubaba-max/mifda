<?php
// File: routes/web.php
// Perubahan: tambah throttle pada AI chat, hapus Google Drive ref

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\GuruController;
use App\Http\Controllers\OrtuController;
use App\Http\Controllers\SiswaController;
use App\Http\Controllers\NotificationController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn() => view('welcome'));

Route::middleware('auth')->group(function () {

    // ==========================================
    // PUSH NOTIFICATION ROUTES
    // ==========================================
    Route::prefix('notifikasi')->name('notifikasi.')->group(function () {
        Route::get('/',              [NotificationController::class, 'index'])->name('index');
        Route::get('/count',         [NotificationController::class, 'count'])->name('count');
        Route::post('/subscribe',    [NotificationController::class, 'subscribe'])->name('subscribe');
        Route::post('/unsubscribe',  [NotificationController::class, 'unsubscribe'])->name('unsubscribe');
        Route::post('/read-all',     [NotificationController::class, 'markAllRead'])->name('read-all');
        Route::post('/{id}/read',    [NotificationController::class, 'markRead'])->name('read');
        // TAMBAHAN: hapus notifikasi (item #2 upgrade — sebelumnya cuma bisa
        // ditandai dibaca, gak bisa beneran dihapus, jadi numpuk terus).
        Route::post('/hapus-semua',  [NotificationController::class, 'destroyAll'])->name('hapus-semua');
        Route::post('/{id}/hapus',   [NotificationController::class, 'destroy'])->name('hapus');
    });

    Route::get('/dashboard', function () {
        return match(auth()->user()->role) {
            'admin' => redirect()->route('admin.dashboard'),
            'guru'  => redirect()->route('guru.dashboard'),
            'ortu'  => redirect()->route('ortu.dashboard'),
            'siswa' => redirect()->route('siswa.dashboard'),
            default => redirect('/'),
        };
    })->name('dashboard');

    // ==========================================
    // 1. ADMIN
    // ==========================================
    Route::middleware(['role:admin'])->group(function () {
        Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
        Route::get('/admin/kelas', [AdminController::class, 'indexKelas'])->name('admin.kelas.index');
        Route::post('/admin/kelas', [AdminController::class, 'storeKelas'])->name('admin.kelas.store');
        Route::delete('/admin/kelas/{id}', [AdminController::class, 'destroyKelas'])->name('admin.kelas.destroy');
        Route::get('/admin/mapel', [AdminController::class, 'indexMapel'])->name('admin.mapel.index');
        Route::post('/admin/mapel', [AdminController::class, 'storeMapel'])->name('admin.mapel.store');
        Route::delete('/admin/mapel/{id}', [AdminController::class, 'destroyMapel'])->name('admin.mapel.destroy');
        Route::get('/admin/users', [AdminController::class, 'indexUser'])->name('admin.user.index');
        Route::post('/admin/users', [AdminController::class, 'storeUser'])->name('admin.user.store');
        Route::put('/admin/user/{id}', [AdminController::class, 'updateUser'])->name('admin.user.update');
        Route::delete('/admin/user/{id}', [AdminController::class, 'destroyUser'])->name('admin.user.destroy');
        Route::post('/admin/relasi', [AdminController::class, 'setPengampu'])->name('admin.relasi.store');
        Route::get('/admin/relasi-sistem', [AdminController::class, 'indexRelasi'])->name('admin.relasi.index');
        Route::post('/admin/relasi-sistem/ortu', [AdminController::class, 'storeRelasiOrtu'])->name('admin.relasi.ortu.store');
        Route::post('/admin/naik-kelas', [AdminController::class, 'naikKelasMassal'])->name('admin.naik-kelas');
        Route::get('/admin/monitoring-guru', [AdminController::class, 'pantauGuru'])->name('admin.monitoring.guru');
        Route::get('/admin/kelulusan', [AdminController::class, 'kelulusanMassal'])->name('admin.kelulusan.index');
        Route::post('/admin/kelulusan/proses', [AdminController::class, 'prosesLulusSemua'])->name('admin.kelulusan.proses');

        // [FIX] Lindungi route cek AI — hanya admin
        Route::get('/cek-model-ai', function () {
            $apiKey  = env('GEMINI_API_KEY');
            $response = \Illuminate\Support\Facades\Http::get(
                "https://generativelanguage.googleapis.com/v1beta/models?key={$apiKey}"
            );
            return $response->json();
        });
    });

    // ==========================================
    // 2. GURU
    // ==========================================
    Route::middleware(['role:guru'])->group(function () {
        Route::get('/guru/dashboard', [GuruController::class, 'dashboard'])->name('guru.dashboard');
        Route::get('/guru/materi/tambah', [GuruController::class, 'create'])->name('guru.materi.create');
        Route::post('/guru/materi/store', [GuruController::class, 'materiStore'])->name('guru.materi.store');
        Route::get('/guru/materi/{id}/edit', [GuruController::class, 'edit'])->name('guru.materi.edit');
        Route::put('/guru/materi/{id}', [GuruController::class, 'update'])->name('guru.materi.update');
        Route::delete('/guru/materi/{id}', [GuruController::class, 'destroy'])->name('guru.materi.destroy');
        Route::get('/guru/materi/{id}/soal', [GuruController::class, 'kelolaSoal'])->name('guru.soal.index');
        Route::post('/guru/materi/{id}/soal', [GuruController::class, 'storeSoal'])->name('guru.soal.store');
        Route::delete('/guru/soal/{id}', [GuruController::class, 'destroySoal'])->name('guru.soal.destroy');
        Route::get('/guru/game', [GuruController::class, 'gameIndex'])->name('guru.game.index');
        Route::post('/guru/game/simpan', [GuruController::class, 'gameStore'])->name('guru.game.store');
        Route::delete('/guru/game/{id}', [GuruController::class, 'gameDestroy'])->name('guru.game.destroy');
        Route::get('/guru/laporan', [GuruController::class, 'laporanSiswa'])->name('guru.laporan.index');
        Route::get('/guru/chat', [GuruController::class, 'chatIndex'])->name('guru.chat.index');
        Route::post('/guru/chat/kirim', [GuruController::class, 'chatSend'])->name('guru.chat.send');
        Route::get('/guru/ujian', [GuruController::class, 'ujianIndex'])->name('guru.ujian.index');
        Route::get('/guru/kelulusan', [GuruController::class, 'kelulusanIndex'])->name('guru.kelulusan.index');
        Route::post('/guru/kelulusan/{id}/tandai', [GuruController::class, 'tandaiLulus'])->name('guru.kelulusan.tandai');
    });

    // ==========================================
    // 3. ORANG TUA
    // ==========================================
    Route::middleware(['role:ortu'])->group(function () {
        Route::get('/ortu/dashboard', [OrtuController::class, 'dashboard'])->name('ortu.dashboard');

        // TAMBAHAN: Integrasi Absensi <-> Mifda — ortu yang sudah punya
        // akun bisa nyambungin kode penghubung belakangan, kapan saja,
        // lewat dashboard (opsional, gak wajib). Throttle 5x/menit biar
        // kode 6 digit gak bisa ditebak-tebak brute force (auto-link tanpa
        // approval manual, jadi ini satu-satunya pagar keamanannya).
        Route::post('/ortu/hubungkan-kode', [OrtuController::class, 'hubungkanKode'])
            ->name('ortu.hubungkan-kode')
            ->middleware('throttle:5,1');
    });

    // ==========================================
    // 4. SISWA
    // ==========================================
    Route::middleware(['role:siswa'])->group(function () {
        Route::get('/siswa/dashboard', [SiswaController::class, 'dashboard'])->name('siswa.dashboard');
        Route::get('/belajar', [SiswaController::class, 'belajar'])->name('siswa.belajar');
        Route::get('/belajar/leaderboard', [SiswaController::class, 'leaderboard'])->name('siswa.leaderboard');
        Route::get('/belajar/sertifikat', [SiswaController::class, 'unduhSertifikat'])->name('siswa.unduhSertifikat');
        Route::get('/belajar/{id}', [SiswaController::class, 'materi'])->name('siswa.materi');
        Route::get('/belajar/misi/{id}', [SiswaController::class, 'lihatMateri'])->name('siswa.materi.detail');
        Route::post('/belajar/misi/{id}/selesai', [SiswaController::class, 'selesaiMateri'])->name('siswa.materi.selesai');
        Route::get('/belajar/misi/{id}/kuis', [SiswaController::class, 'kuis'])->name('siswa.kuis');
        Route::post('/belajar/misi/{id}/kuis', [SiswaController::class, 'submitKuis'])->name('siswa.kuis.submit');
        Route::get('/siswa/ujian', [SiswaController::class, 'ujianIndex'])->name('siswa.ujian.index');
        Route::post('/siswa/chat/kirim', [SiswaController::class, 'kirimChat'])->name('siswa.chat.kirim');
        Route::get('/siswa/game/{id}', [SiswaController::class, 'playGame'])->name('siswa.game.play');

        // [FIX] Tambah throttle: max 10 request per menit per user
        Route::post('/siswa/misi/{id}/ai-chat', [SiswaController::class, 'aiChat'])
            ->name('siswa.ai.chat')
            ->middleware('throttle:10,1');
    });

    // PROFILE (umum)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
});

require __DIR__ . '/auth.php';
