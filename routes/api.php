<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AbsensiSyncController;
use App\Http\Controllers\Api\StudentLinkController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

/*
|--------------------------------------------------------------------------
| TAMBAHAN: Integrasi Absensi <-> Mifda
|--------------------------------------------------------------------------
| Endpoint server-to-server, dipanggil dari aplikasi Absensi. Dilindungi
| shared secret token (lihat App\Http\Middleware\VerifyIntegrationToken).
*/
Route::middleware('integration.token')->prefix('absensi')->group(function () {
    Route::post('/sync', [AbsensiSyncController::class, 'sync']);
});

// TAMBAHAN: dipakai halaman "Rekonsiliasi Akun Lama" di Filament Absensi.
Route::middleware('integration.token')->prefix('link')->group(function () {
    Route::get('/unlinked-students', [StudentLinkController::class, 'unlinkedStudents']);
    Route::post('/admin-link', [StudentLinkController::class, 'adminLink']);
});
