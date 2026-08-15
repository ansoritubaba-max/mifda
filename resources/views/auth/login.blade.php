<x-guest-layout>
    {{-- Header --}}
    <div class="mb-8">
        <h2 class="font-black text-2xl md:text-3xl text-slate-800 tracking-tight">Masuk ke Akun</h2>
        <p class="text-slate-500 text-sm font-medium mt-1.5">Platform belajar interaktif MI Digital</p>
    </div>

    {{-- Ayat / tagline --}}
    <div class="bg-gradient-to-r from-indigo-50 to-violet-50 border border-indigo-100 rounded-2xl px-5 py-3.5 mb-7 flex items-start gap-3">
        <span class="text-xl mt-0.5 shrink-0">🌙</span>
        <p class="text-xs font-bold text-indigo-700 leading-relaxed italic">
            "Mari menuntut ilmu dengan niat karena Allah — Ahlan wa Sahlan!"
        </p>
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf

        {{-- USERNAME --}}
        <div>
            <label for="username" class="block text-xs font-black text-slate-600 uppercase tracking-widest mb-2">Username</label>
            <div class="relative">
                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-lg pointer-events-none">👤</span>
                <input id="username" type="text" name="username" value="{{ old('username') }}"
                    required autofocus autocomplete="username"
                    class="block w-full pl-12 pr-4 py-3.5 bg-white border border-slate-200 rounded-xl text-sm font-bold text-slate-800 placeholder-slate-300 shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition"
                    placeholder="Masukkan username...">
            </div>
            <x-input-error :messages="$errors->get('username')" class="mt-2 text-red-500 text-xs" />
        </div>

        {{-- PASSWORD --}}
        <div>
            <label for="password" class="block text-xs font-black text-slate-600 uppercase tracking-widest mb-2">Password</label>
            <div class="relative">
                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-lg pointer-events-none">🔒</span>
                <input id="password" type="password" name="password"
                    required autocomplete="current-password"
                    class="block w-full pl-12 pr-14 py-3.5 bg-white border border-slate-200 rounded-xl text-sm font-bold text-slate-800 placeholder-slate-300 shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition"
                    placeholder="••••••••">
                <button type="button" id="togglePwd"
                    class="absolute right-4 top-1/2 -translate-y-1/2 text-slate-400 hover:text-indigo-600 transition-colors p-1 focus:outline-none"
                    aria-label="Tampilkan/sembunyikan password">
                    <svg id="eyeIconOn" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                    </svg>
                    <svg id="eyeIconOff" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.542 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                    </svg>
                </button>
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-2 text-red-500 text-xs" />
        </div>

        <script>
            document.getElementById('togglePwd').addEventListener('click', function () {
                const pwd = document.getElementById('password');
                const on  = document.getElementById('eyeIconOn');
                const off = document.getElementById('eyeIconOff');
                if (pwd.type === 'password') {
                    pwd.type = 'text'; on.classList.add('hidden'); off.classList.remove('hidden');
                } else {
                    pwd.type = 'password'; on.classList.remove('hidden'); off.classList.add('hidden');
                }
            });
        </script>

        {{-- REMEMBER ME --}}
        <div class="flex items-center justify-between">
            <label for="remember_me" class="inline-flex items-center gap-2.5 cursor-pointer">
                <input id="remember_me" type="checkbox"
                    class="w-4.5 h-4.5 rounded border-slate-300 text-indigo-600 focus:ring-indigo-500 shadow-sm"
                    name="remember">
                <span class="text-sm font-bold text-slate-600">Ingat Saya</span>
            </label>
            @if (Route::has('password.request'))
                <a href="{{ route('password.request') }}"
                    class="text-sm font-bold text-indigo-600 hover:text-indigo-800 underline decoration-2 underline-offset-4 transition-colors">
                    Lupa Password?
                </a>
            @endif
        </div>

        {{-- SUBMIT BUTTON --}}
        <button type="submit"
            class="w-full py-4 bg-gradient-to-r from-indigo-600 to-violet-600 text-white font-black text-sm rounded-xl shadow-lg shadow-indigo-200 hover:shadow-xl hover:-translate-y-0.5 hover:from-indigo-700 hover:to-violet-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-all duration-200 uppercase tracking-widest flex items-center justify-center gap-3">
            <span>Masuk Sekarang</span>
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
            </svg>
        </button>

        @if (Route::has('register'))
            <div class="text-center pt-2">
                <span class="text-sm text-slate-500 font-medium">Belum punya akun?</span>
                <a href="{{ route('register') }}" class="ml-1 text-sm font-black text-indigo-600 hover:text-indigo-800 underline decoration-2 underline-offset-4 transition-colors">
                    Daftar Sekarang
                </a>
            </div>
        @endif
    </form>
</x-guest-layout>
