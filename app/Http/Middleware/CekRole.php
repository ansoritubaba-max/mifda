<?php

namespace App\Http\Middleware; // Pastikan baris ini ada

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class CekRole
{
    public function handle(Request $request, Closure $next, $role): Response
    {
        if (!Auth::check() || Auth::user()->role !== $role) {
            abort(403, 'Ups! Anda tidak memiliki akses ke halaman ini.');
        }

        return $next($request);
    }
}