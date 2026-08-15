<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use Illuminate\View\View;

class PasswordResetLinkController extends Controller
{
    public function create(): View
    {
        return view('auth.forgot-password');
    }

    public function store(Request $request): RedirectResponse
    {
        // 1. Validasi Username
        $request->validate(['username' => 'required|string']);

        // 2. Cari User
        $user = User::where('username', $request->username)->first();

        if (!$user || !$user->no_telp) {
            return back()->withErrors(['username' => 'Username tidak ditemukan atau nomor WA belum terdaftar.']);
        }

        // 3. Buat Token Reset
        $token = Str::random(64);

        // 4. Simpan ke database (Pinjam kolom 'email' untuk simpan 'username')
        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $user->username],
            [
                'token' => Hash::make($token),
                'created_at' => now()
            ]
        );

        // 5. Kirim WhatsApp via Fonnte
        $url = url("/reset-password/{$token}?username={$user->username}");
        $pesan = "🔐 *Permintaan Reset Password*\n\n";
        $pesan .= "Bismillah, silakan klik link di bawah untuk mengatur ulang password Anda:\n\n";
        $pesan .= $url . "\n\n";
        $pesan .= "⚠️ *PENTING:* Link ini hanya berlaku *1 MENIT* demi keamanan akun Anda. Segera lakukan pembaruan!";

        try {
            Http::withoutVerifying()->withHeaders([
                'Authorization' => env('FONNTE_TOKEN'),
            ])->post('https://api.fonnte.com/send', [
                'target' => $user->no_telp,
                'message' => $pesan,
            ]);
        } catch (\Exception $e) {
            return back()->withErrors(['username' => 'Gagal mengirim WA: ' . $e->getMessage()]);
        }

        return back()->with('status', 'Alhamdulillah, link reset telah dikirim ke WhatsApp Anda! (Berlaku 1 Menit)');
    }
}