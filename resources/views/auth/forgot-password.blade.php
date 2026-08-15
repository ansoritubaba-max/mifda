<x-guest-layout>
    <div class="text-center mb-8">
        <img src="{{ asset('images/logo.png') }}" alt="Logo Yayasan" class="w-24 h-24 mx-auto mb-4 object-contain">
        <h2 class="text-3xl font-black text-emerald-700">Lupa Password</h2>
        <p class="text-sm font-medium text-emerald-600 mt-2">Masukkan username Anda untuk menerima link reset via WhatsApp</p>
    </div>

    <!-- Menampilkan pesan sukses / error -->
    <x-auth-session-status class="mb-4" :status="session('status')" />
    @if ($errors->any())
        <div class="mb-6 bg-red-50 border border-red-200 text-red-600 px-5 py-4 rounded-xl">
            <ul class="list-disc list-inside text-sm">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('password.email') }}">
        @csrf
        <!-- INPUT USERNAME SAJA (Tanpa Token) -->
        <div>
            <x-input-label for="username" value="Username Wali / Siswa" class="text-emerald-800 font-bold" />
            <x-text-input id="username" class="block mt-1 w-full border-emerald-200 focus:border-emerald-500 focus:ring-emerald-500 rounded-xl" type="text" name="username" :value="old('username')" required autofocus />
        </div>

        <div class="flex items-center justify-end mt-8">
            <button type="submit" class="inline-flex items-center px-8 py-3 bg-emerald-600 rounded-xl font-black text-sm text-white hover:bg-emerald-700 transition-all">
                Kirim Link ke WhatsApp
            </button>
        </div>
    </form>
</x-guest-layout>