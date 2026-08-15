<x-guest-layout>
    <div class="text-center mb-8">
        <img src="{{ asset('images/logo.png') }}" alt="Logo Yayasan" class="w-24 h-24 mx-auto mb-4 object-contain">
        <h2 class="text-3xl font-black text-emerald-700">Atur Ulang Password</h2>
        <p class="text-sm font-medium text-emerald-600 mt-2">Silakan buat password baru yang mudah diingat</p>
    </div>

    <form method="POST" action="{{ route('password.store') }}">
        @csrf

        <!-- 💡 INI KUNCI ANTI ERROR: Menggunakan fungsi global request() -->
        <input type="hidden" name="token" value="{{ request()->route('token') }}">

        <div>
            <x-input-label for="username" value="Username" class="text-emerald-800 font-bold" />
            <x-text-input id="username" class="block mt-1 w-full border-emerald-200 focus:border-emerald-500 focus:ring-emerald-500 rounded-xl" type="text" name="username" :value="old('username', request()->username)" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('username')" class="mt-2 text-red-500" />
        </div>

        <div class="mt-5">
            <x-input-label for="password" value="Password Baru" class="text-emerald-800 font-bold" />
            <x-text-input id="password" class="block mt-1 w-full border-emerald-200 focus:border-emerald-500 focus:ring-emerald-500 rounded-xl" type="password" name="password" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2 text-red-500" />
        </div>

        <div class="mt-5">
            <x-input-label for="password_confirmation" value="Konfirmasi Password Baru" class="text-emerald-800 font-bold" />
            <x-text-input id="password_confirmation" class="block mt-1 w-full border-emerald-200 focus:border-emerald-500 focus:ring-emerald-500 rounded-xl" type="password" name="password_confirmation" required autocomplete="new-password" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2 text-red-500" />
        </div>

        <div class="flex items-center justify-end mt-8">
            <button type="submit" class="inline-flex items-center px-8 py-3 bg-emerald-600 rounded-xl font-black text-sm text-white hover:bg-emerald-700 transition-all">
                Simpan Password Baru
            </button>
        </div>
    </form>
</x-guest-layout>