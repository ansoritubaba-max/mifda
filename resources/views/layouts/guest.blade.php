<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Aplikasi Belajar MI') }}</title>

        <!-- Font: Plus Jakarta Sans -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            *, *::before, *::after { font-family: 'Plus Jakarta Sans', ui-sans-serif, system-ui, sans-serif !important; }
            :root { --color-primary: #4f46e5; --color-accent: #06b6d4; }
            body { selection-background-color: #4f46e5; }
            ::selection { background: #4f46e5; color: #fff; }
        </style>
    </head>
    <body class="text-gray-900 antialiased">
        <div class="min-h-screen flex">
            {{-- LEFT PANEL: branding --}}
            <div class="hidden lg:flex lg:w-1/2 xl:w-[55%] bg-gradient-to-br from-slate-900 via-indigo-950 to-slate-900 relative overflow-hidden flex-col items-center justify-center p-12">
                {{-- Dot pattern --}}
                <div class="absolute inset-0 opacity-[0.06]" style="background-image: radial-gradient(#fff 1px, transparent 1px); background-size: 24px 24px;"></div>
                {{-- Glow orbs --}}
                <div class="absolute top-1/4 left-1/4 w-96 h-96 bg-indigo-600 rounded-full blur-[120px] opacity-25"></div>
                <div class="absolute bottom-1/4 right-1/4 w-64 h-64 bg-violet-600 rounded-full blur-[80px] opacity-20"></div>
                <div class="absolute top-10 right-0 w-48 h-48 bg-cyan-500 rounded-full blur-[80px] opacity-10"></div>

                {{-- Content --}}
                <div class="relative z-10 text-center max-w-md">
                    <div class="mb-10">
                        <img src="{{ asset('images/logo.png') }}" alt="Logo" class="w-24 h-24 mx-auto drop-shadow-2xl">
                    </div>

                    <p class="text-indigo-300 text-xs font-black uppercase tracking-[0.3em] mb-4">Platform Belajar Digital</p>
                    <h1 class="text-4xl xl:text-5xl font-black text-white leading-tight tracking-tight mb-4">
                        MIFDA<br>
                        <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-400 to-cyan-400">Berbasis Digital</span>
                    </h1>
                    <p class="text-slate-400 text-sm font-medium leading-relaxed mb-10">
                        Platform pembelajaran interaktif untuk siswa, guru, dan orang tua — dirancang untuk era pendidikan digital.
                    </p>

                    {{-- Feature badges --}}
                    <div class="flex flex-wrap justify-center gap-3">
                        <span class="bg-white/5 border border-white/10 text-slate-300 text-[10px] font-black px-4 py-2 rounded-full uppercase tracking-widest flex items-center gap-2">
                            <span class="text-sm">📚</span> Misi Belajar
                        </span>
                        <span class="bg-white/5 border border-white/10 text-slate-300 text-[10px] font-black px-4 py-2 rounded-full uppercase tracking-widest flex items-center gap-2">
                            <span class="text-sm">🏆</span> Gamifikasi XP
                        </span>
                        <span class="bg-white/5 border border-white/10 text-slate-300 text-[10px] font-black px-4 py-2 rounded-full uppercase tracking-widest flex items-center gap-2">
                            <span class="text-sm">📋</span> Ujian Digital
                        </span>
                        <span class="bg-white/5 border border-white/10 text-slate-300 text-[10px] font-black px-4 py-2 rounded-full uppercase tracking-widest flex items-center gap-2">
                            <span class="text-sm">👨‍👩‍👧</span> Pantau Anak
                        </span>
                    </div>
                </div>

                {{-- Bottom tag --}}
                <div class="absolute bottom-6 left-0 right-0 flex justify-center">
                    <p class="text-slate-600 text-[9px] font-bold uppercase tracking-[0.3em]">Powered by MI Digital Learning System</p>
                </div>
            </div>

            {{-- RIGHT PANEL: form --}}
            <div class="w-full lg:w-1/2 xl:w-[45%] flex items-center justify-center bg-slate-50 relative min-h-screen">
                {{-- BG subtle --}}
                <div class="absolute top-0 right-0 w-64 h-64 bg-indigo-100 rounded-full blur-3xl opacity-40 pointer-events-none -translate-y-1/3 translate-x-1/3"></div>
                <div class="absolute bottom-0 left-0 w-64 h-64 bg-violet-100 rounded-full blur-3xl opacity-30 pointer-events-none translate-y-1/3 -translate-x-1/3"></div>

                <div class="w-full max-w-md px-6 sm:px-10 py-10 relative z-10">
                    {{-- Mobile logo only --}}
                    <div class="lg:hidden text-center mb-8">
                        <img src="{{ asset('images/logo.png') }}" alt="Logo" class="w-16 h-16 mx-auto mb-3 drop-shadow">
                        <h1 class="font-black text-2xl text-slate-800">MIFDA</h1>
                        <p class="text-slate-400 text-xs font-bold">Platform Belajar Digital</p>
                    </div>

                    {{ $slot }}
                </div>
            </div>
        </div>
    </body>
</html>
