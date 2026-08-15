<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * FITUR BARU: Integrasi Absensi <-> Mifda.
 *
 * Middleware sederhana buat lindungi endpoint API server-to-server (bukan
 * request dari user biasa). Cek header "Authorization: Bearer <token>"
 * dan cocokkan ke satu shared secret yang sama-sama disimpan di .env
 * kedua aplikasi (INTEGRATION_API_TOKEN). Sengaja gak pakai Sanctum token
 * per-user karena ini komunikasi antar-server, bukan antar-user — jadi
 * satu token statis sudah cukup dan gampang di-manage di kedua sisi.
 */
class VerifyIntegrationToken
{
    public function handle(Request $request, Closure $next): Response
    {
        $expected = config('services.integration.token');
        $given = $request->bearerToken();

        if (empty($expected) || empty($given) || !hash_equals($expected, $given)) {
            abort(401, 'Token integrasi tidak valid.');
        }

        return $next($request);
    }
}
