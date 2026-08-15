<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>MIFDA - MI Miftahul Huda | Generasi Cerdas & Islami!</title>

    <!-- PWA -->
    <link rel="manifest" href="/manifest.json">
    <meta name="theme-color" content="#4f46e5">

    <!-- Font: Plus Jakarta Sans + Lateef (Arabic) -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;800;900&family=Lateef:wght@400&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="antialiased bg-blue-50/50 text-gray-800 selection:bg-emerald-400 selection:text-white">

    <div class="relative min-h-screen flex flex-col overflow-hidden w-full max-w-[100vw]">

        <div class="absolute top-0 right-0 w-full max-w-lg h-[500px] bg-emerald-200/50 rounded-full blur-3xl -translate-y-1/3 translate-x-1/3 z-0 pointer-events-none"></div>
        <div class="absolute bottom-0 left-0 w-full max-w-lg h-[500px] bg-blue-200/50 rounded-full blur-3xl translate-y-1/3 -translate-x-1/3 z-0 pointer-events-none"></div>

        <nav class="bg-white/80 backdrop-blur-xl shadow-sm sticky top-0 z-50 border-b border-gray-100">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex justify-between h-20 md:h-24 items-center">
                
                <div class="flex items-center gap-3 md:gap-4 group cursor-pointer z-10">
                        <div class="w-10 h-10 md:w-14 md:h-14 transform -rotate-3 group-hover:rotate-6 group-hover:scale-110 transition-all duration-300">
                            <img src="{{ asset('images/logo.png') }}" alt="Logo MIFDA" class="w-full h-full object-contain drop-shadow-md">
                        </div>
                    <div>
                        <span class="font-black text-xl md:text-2xl text-transparent bg-clip-text bg-gradient-to-r from-emerald-700 to-blue-600 tracking-tight block leading-none">MIFDA</span>
                        <span class="hidden md:block text-[10px] font-black text-gray-400 uppercase tracking-widest mt-1">MI Miftahul Huda · Tumijajar, Lampung</span>
                    </div>
                </div>

                <div class="flex items-center gap-4 z-10">
                    @if (Route::has('login'))
                        @auth
                            @php
                                $dashboardRoute = route('siswa.belajar');
                                if(Auth::user()->role === 'admin') $dashboardRoute = route('admin.dashboard');
                                elseif(Auth::user()->role === 'guru') $dashboardRoute = route('guru.dashboard');
                                elseif(Auth::user()->role === 'ortu') $dashboardRoute = route('ortu.dashboard');
                            @endphp
                            <a href="{{ $dashboardRoute }}" class="px-5 py-2.5 md:px-8 md:py-3 bg-gradient-to-r from-yellow-400 to-orange-400 hover:from-yellow-500 hover:to-orange-500 text-white font-black rounded-xl md:rounded-[1.2rem] transition-all shadow-lg shadow-yellow-200 transform hover:-translate-y-1 tracking-widest text-[10px] md:text-xs flex items-center gap-2">
                                <span>🚀</span> <span class="hidden md:inline">Masuk Kelas</span><span class="md:hidden">Masuk</span>
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="px-5 py-2.5 md:px-8 md:py-3 bg-gradient-to-r from-emerald-500 to-teal-600 hover:from-emerald-600 hover:to-teal-700 text-white font-black rounded-xl md:rounded-[1.2rem] transition-all shadow-lg shadow-emerald-200 transform hover:-translate-y-1 tracking-widest text-[10px] md:text-xs flex items-center gap-2">
                                <span>🔑</span> <span class="hidden md:inline">Mulai Belajar</span><span class="md:hidden">Login</span>
                            </a>
                        @endauth
                    @endif
                </div>
            </div>
        </nav>

        <main class="flex-grow flex items-center justify-center pt-10 pb-20 px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="max-w-7xl mx-auto grid grid-cols-1 lg:grid-cols-2 gap-12 md:gap-16 items-center">
                
                <div class="text-center lg:text-left space-y-6 md:space-y-8">
                    
                    <div class="inline-block animate-fade-in-down">
                        <p class="text-3xl md:text-4xl text-emerald-600 mb-3 md:mb-4 drop-shadow-sm" style="font-family: 'Lateef', serif;">بِسْمِ اللَّهِ الرَّحْمَنِ الرَّحِيم</p>
                        <div class="px-4 py-1.5 md:px-5 md:py-2 bg-emerald-50 border-2 border-emerald-200 text-emerald-700 rounded-full font-black text-[9px] md:text-[10px] uppercase tracking-widest shadow-sm inline-flex items-center gap-2">
                            <span>🌟</span> Platform Edukasi Digital Madrasah
                        </div>
                    </div>
                    
                    <h1 class="text-4xl sm:text-5xl lg:text-7xl font-black text-gray-900 leading-[1.1] tracking-tight">
                        Generasi Cerdas,<br>
                        Berakhlak & <span class="text-transparent bg-clip-text bg-gradient-to-r from-emerald-500 to-blue-600 relative inline-block mt-2 lg:mt-0">Menyenangkan!</span>
                    </h1>
                    
                    <p class="text-base md:text-lg text-gray-600 font-bold leading-relaxed max-w-2xl mx-auto lg:mx-0 px-2 lg:px-0">
                        Insya Allah! Di MIFDA – MI Miftahul Huda Gunung Menanti, belajar ilmu agama dan duniawi terasa seperti bermain game. Selesaikan misi pelajaran, kumpulkan pahala (XP), dan jadilah bintang di kelas! 🌙✨
                    </p>

                    <div class="flex flex-col sm:flex-row gap-4 justify-center lg:justify-start pt-2 md:pt-4">
                        @auth
                            <a href="{{ $dashboardRoute }}" class="px-8 py-4 md:px-10 md:py-5 bg-gradient-to-r from-emerald-500 to-blue-600 hover:from-emerald-600 hover:to-blue-700 text-white text-base md:text-lg font-black rounded-2xl md:rounded-[2rem] shadow-xl shadow-blue-200 transform transition-all hover:-translate-y-1 hover:scale-105 flex items-center justify-center gap-3">
                                <span>Bismillah, Lanjut Belajar</span> 🚀
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="px-8 py-4 md:px-10 md:py-5 bg-gradient-to-r from-emerald-500 to-blue-600 hover:from-emerald-600 hover:to-blue-700 text-white text-base md:text-lg font-black rounded-2xl md:rounded-[2rem] shadow-xl shadow-blue-200 transform transition-all hover:-translate-y-1 hover:scale-105 flex items-center justify-center gap-3">
                                <span>Ayo Mulai Belajar!</span> 🎒
                            </a>
                        @endauth
                    </div>

                    <div class="pt-8 md:pt-10 flex flex-wrap items-center justify-center lg:justify-start gap-4 md:gap-6 text-gray-500 font-black text-xs md:text-sm uppercase tracking-widest">
                        <div class="flex items-center gap-2 bg-white px-3 py-1.5 md:px-4 md:py-2 rounded-xl shadow-sm border border-gray-100">
                            <span class="text-xl md:text-2xl">📖</span> Ilmu & Iman
                        </div>
                        <div class="flex items-center gap-2 bg-white px-3 py-1.5 md:px-4 md:py-2 rounded-xl shadow-sm border border-gray-100">
                            <span class="text-xl md:text-2xl">🎮</span> Misi Seru
                        </div>
                        <div class="flex items-center gap-2 bg-white px-3 py-1.5 md:px-4 md:py-2 rounded-xl shadow-sm border border-gray-100">
                            <span class="text-xl md:text-2xl">👨‍👩‍👧</span> Pantauan
                        </div>
                    </div>
                </div>

                <div class="relative hidden lg:flex h-[600px] items-center justify-center w-full">
                    
                    <div class="absolute top-10 left-10 w-48 h-48 bg-emerald-300 rounded-full mix-blend-multiply filter blur-2xl opacity-70 animate-blob"></div>
                    <div class="absolute top-10 right-10 w-48 h-48 bg-yellow-300 rounded-full mix-blend-multiply filter blur-2xl opacity-70 animate-blob animation-delay-2000"></div>
                    <div class="absolute bottom-10 left-20 w-48 h-48 bg-blue-300 rounded-full mix-blend-multiply filter blur-2xl opacity-70 animate-blob animation-delay-4000"></div>
                    
                    <div class="relative bg-white p-3 rounded-[3rem] shadow-2xl transform rotate-3 hover:rotate-0 transition-all duration-700 z-10 animate-float border border-gray-100 max-w-md">
                        <img src="https://images.unsplash.com/photo-1503676260728-1c00da094a0b?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80" alt="Anak sedang belajar ceria" class="rounded-[2.5rem] object-cover h-[450px] w-full shadow-inner brightness-105">
                        
                        <div class="absolute -left-12 top-20 bg-white px-6 py-4 rounded-3xl shadow-xl border-2 border-emerald-400 transform -rotate-6 hover:scale-110 transition cursor-default">
                            <div class="flex items-center gap-3">
                                <span class="text-3xl">🕌</span>
                                <div>
                                    <span class="block text-[10px] font-black text-gray-400 uppercase tracking-widest">Misi Agama</span>
                                    <span class="font-black text-emerald-600 text-lg">Hafalan Selesai!</span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="absolute -right-12 bottom-24 bg-white px-6 py-4 rounded-3xl shadow-xl border-2 border-yellow-400 transform rotate-6 hover:scale-110 transition cursor-default">
                            <div class="flex items-center gap-3">
                                <span class="text-3xl">⚡</span>
                                <div>
                                    <span class="block text-[10px] font-black text-gray-400 uppercase tracking-widest">Poin Kebaikan</span>
                                    <span class="font-black text-yellow-500 text-lg">+100 XP Hari Ini</span>
                                </div>
                            </div>
                        </div>

                        <div class="absolute -top-8 -right-8 text-6xl drop-shadow-lg animate-pulse">✨</div>
                    </div>
                </div>

            </div>
        </main>

        <footer class="bg-white/80 backdrop-blur-md border-t border-gray-100 py-6 text-center mt-auto relative z-10">
            <p class="text-gray-400 font-bold text-xs md:text-sm px-4">
                © {{ date('Y') }} <span class="text-emerald-600 font-black">MIFDA – MI Miftahul Huda</span> · Gunung Menanti, Tumijajar, Lampung<br>
                <span class="text-gray-300">Yayasan Nahdlatut Tholab · Membangun generasi cerdas berakhlak mulia <span class="text-red-500 animate-pulse inline-block">❤️</span></span>
            </p>
        </footer>

    </div>
    <style>
        .animation-delay-2000 { animation-delay: 2s; }
        .animation-delay-4000 { animation-delay: 4s; }
    </style>
    <script>
        if ('serviceWorker' in navigator) {
            navigator.serviceWorker.register('/sw.js', { scope: '/' });
        }
    </script>

</body>
</html>