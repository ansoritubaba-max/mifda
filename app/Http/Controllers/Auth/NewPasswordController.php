<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class NewPasswordController extends Controller
{
    public function create(Request $request): View
    {
        return view('auth.reset-password', ['request' => $request]);
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'token' => 'required',
            'username' => 'required|string',
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        // 1. Ambil data token dari DB
        $resetData = DB::table('password_reset_tokens')
            ->where('email', $request->username)
            ->first();

        // 2. Cek Validitas Token & Keamanan 1 Menit
        if (!$resetData || !Hash::check($request->token, $resetData->token)) {
            return back()->withErrors(['username' => 'Token tidak valid.']);
        }

        // Cek apakah sudah lewat 1 menit (60 detik)
        $waktuDibuat = \Carbon\Carbon::parse($resetData->created_at);
        if ($waktuDibuat->addMinutes(1)->isPast()) {
            DB::table('password_reset_tokens')->where('email', $request->username)->delete();
            return redirect()->route('password.request')->withErrors(['username' => 'Maaf, link sudah kedaluwarsa (lebih dari 1 menit). Silakan minta link baru.']);
        }

        // 3. Update Password User
        $user = User::where('username', $request->username)->first();
        if (!$user) return back()->withErrors(['username' => 'User tidak ditemukan.']);

        $user->update([
            'password' => Hash::make($request->password)
        ]);

        // 4. Hapus token agar tidak bisa dipakai lagi
        DB::table('password_reset_tokens')->where('email', $request->username)->delete();

        return redirect()->route('login')->with('status', 'Alhamdulillah! Password berhasil diperbarui. Silakan login.');
    }
}