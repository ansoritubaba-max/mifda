<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage; // 👈 Jangan lupa ini!
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();
        
        $user->fill($request->validated());

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        // 🚀 LOGIKA UPLOAD AVATAR KE LOCAL STORAGE (VERSI ANTI-CRASH & MASUK FOLDER)
        if ($request->hasFile('avatar')) {
            
            // A. Hapus foto lama dengan Try-Catch agar tidak error jika file sudah raib di Storage
            if ($user->avatar) {
                try {
                    // Kita cek dulu apakah file benar-benar ada sebelum hapus
                    if (Storage::disk('public')->exists($user->avatar)) {
                        Storage::disk('public')->delete($user->avatar);
                    }
                } catch (\Exception $e) {
                    // Jika error (file tidak ditemukan/masalah permission), abaikan dan lanjut
                    \Log::warning("Gagal menghapus file lama di Local Storage: " . $user->avatar);
                }
            }

            // B. Ambil file dan bersihkan nama filenya
            $file = $request->file('avatar');
            
            // Ambil ekstensi asli (png, jpg, dll)
            $extension = $file->getClientOriginalExtension();
            
            // Buat nama file baru yang bersih: hanya angka, huruf, dan underscore
            // Format: 1715321234_username_avatar.png
            $safeName = str_replace(' ', '_', strtolower($user->name)); // ganti spasi jadi _
            $safeName = preg_replace('/[^A-Za-z0-9\_]/', '', $safeName); // hapus karakter aneh
            $fileName = time() . '_' . $safeName . '.' . $extension;
            
            // C. Upload ke Local Storage (MASUKKAN KE FOLDER 'avatars')
            try {
                // Menggunakan storeAs bawaan Laravel agar otomatis masuk ke folder 'avatars' dengan nama custom
                $path = $file->storeAs('avatars', $fileName, 'public');
                
                // Simpan path yang lengkap dengan foldernya ke database (hasilnya: avatars/namafile.png)
                $user->avatar = $path;
            } catch (\Exception $e) {
                return Redirect::route('profile.edit')->with('error', 'Gagal mengunggah gambar ke penyimpanan lokal.');
            }
        }

        $user->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}