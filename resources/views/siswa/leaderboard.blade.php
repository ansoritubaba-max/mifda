<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 relative z-10">
            <div class="flex items-center gap-3 md:gap-4">
                <a href="{{ route('siswa.belajar') }}" class="bg-white p-2.5 md:p-3 rounded-xl md:rounded-2xl shadow-sm border border-gray-200 hover:bg-slate-50 hover:shadow-md transition-all transform hover:-translate-x-1 font-bold text-gray-600 text-xs md:text-sm flex items-center gap-2 group">
                    <span class="group-hover:-translate-x-1 transition-transform">⬅️</span> 
                    <span class="hidden sm:inline">Kembali</span>
                </a>
                
                <h2 class="font-black text-2xl md:text-3xl text-white leading-tight tracking-tight flex items-center gap-2 md:gap-3">
                    <span class="text-3xl md:text-4xl animate-bounce">🏆</span>
                    Papan Juara!
                </h2>
            </div>
            
            <div class="bg-gradient-to-r from-amber-100 to-yellow-100 border border-yellow-200 px-4 py-2 rounded-xl shadow-sm text-xs font-black text-amber-700 uppercase tracking-widest flex items-center gap-2 self-start md:self-auto">
                ⭐ Top 10 Global
            </div>
        </div>
    </x-slot>

    <div class="py-6 md:py-12 bg-slate-50 min-h-screen relative overflow-hidden">
        
        <div class="absolute top-0 right-0 w-[25rem] h-[25rem] bg-amber-200 rounded-full mix-blend-multiply filter blur-[100px] opacity-30 pointer-events-none -translate-y-1/2 translate-x-1/3"></div>
        <div class="absolute bottom-0 left-0 w-[30rem] h-[30rem] bg-emerald-200 rounded-full mix-blend-multiply filter blur-[100px] opacity-20 pointer-events-none translate-y-1/3 -translate-x-1/3"></div>

        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            
            <div class="bg-white overflow-hidden shadow-2xl shadow-slate-200/50 rounded-[2rem] md:rounded-[3rem] border border-slate-100 relative">
                
                <div class="h-3 md:h-4 w-full bg-gradient-to-r from-amber-400 via-yellow-300 to-orange-400"></div>

                <div class="absolute -right-10 top-20 text-9xl opacity-[0.03] rotate-12 pointer-events-none select-none">👑</div>
                
                <div class="p-6 md:p-10 lg:p-14 relative z-10">
                    
                    <div class="text-center mb-10 md:mb-12">
                        <div class="inline-block relative">
                            <span class="text-6xl md:text-7xl block mb-4 animate-bounce drop-shadow-xl relative z-10">👑</span>
                            <div class="absolute bottom-0 left-1/2 -translate-x-1/2 w-20 h-4 bg-yellow-400/30 blur-md rounded-full"></div>
                        </div>
                        <h3 class="text-3xl md:text-4xl lg:text-5xl font-black text-gray-800 tracking-tight">Top 10 Siswa Terhebat</h3>
                        <p class="text-xs md:text-sm font-bold text-gray-400 mt-3 md:mt-4 uppercase tracking-widest">Kumpulkan XP sebanyak-banyaknya untuk menjadi nomor satu!</p>
                    </div>

                    @php
                        $isTopTen = $topSiswa->pluck('id')->contains(Auth::id());
                    @endphp

                    @if($isTopTen)
                        <div class="mb-10 md:mb-12 p-6 md:p-8 bg-gradient-to-r from-amber-400 to-orange-500 rounded-[1.5rem] md:rounded-[2.5rem] shadow-xl shadow-orange-200/50 text-white flex flex-col md:flex-row justify-between items-center gap-6 border-2 md:border-4 border-white transform hover:scale-[1.02] transition-transform duration-300 relative overflow-hidden group">
                            <div class="absolute top-0 -inset-full h-full w-1/2 z-5 block transform -skew-x-12 bg-gradient-to-r from-transparent to-white opacity-20 group-hover:animate-shine"></div>
                            
                            <div class="text-center md:text-left relative z-10">
                                <h4 class="text-2xl md:text-3xl font-black mb-1 drop-shadow-md">Selamat! 🎉</h4>
                                <p class="font-bold text-orange-50 text-sm md:text-base">Kamu masuk 10 besar siswa terhebat!</p>
                            </div>
                            <a href="{{ route('siswa.unduhSertifikat') }}" target="_blank" class="relative z-10 bg-white text-orange-600 px-6 py-3.5 md:px-8 md:py-4 rounded-xl md:rounded-2xl font-black hover:bg-orange-50 hover:shadow-lg transition-all flex items-center justify-center gap-2 uppercase tracking-widest text-[10px] md:text-xs w-full md:w-auto">
                                📥 Unduh Sertifikat
                            </a>
                        </div>
                    @endif

                    <div class="space-y-4 md:space-y-5">
                        @foreach($topSiswa as $index => $siswa)
                            @php
                                // Konfigurasi Style Default (Peringkat 4-10)
                                $bgClass = 'bg-slate-50 hover:bg-white';
                                $borderClass = 'border-slate-100 border-2';
                                $medali = '#' . ($index + 1);
                                $ukuranText = 'text-lg md:text-xl text-slate-700';
                                $avatarBg = 'bg-white text-emerald-600 border border-slate-200';
                                $medaliStyle = 'text-slate-400 font-black text-xl md:text-2xl';
                                
                                // Override Style Khusus Juara 1, 2, 3
                                if($index == 0) {
                                    $bgClass = 'bg-gradient-to-r from-amber-50 to-yellow-50 shadow-xl transform md:scale-[1.03] z-10';
                                    $borderClass = 'border-yellow-400 border-2 shadow-[0_0_15px_rgba(250,204,21,0.3)]';
                                    $medali = '🥇';
                                    $ukuranText = 'text-xl md:text-2xl text-yellow-900';
                                    $avatarBg = 'bg-gradient-to-br from-yellow-400 to-amber-500 text-white border-2 border-white shadow-md';
                                    $medaliStyle = 'text-3xl md:text-4xl drop-shadow-md';
                                } elseif($index == 1) {
                                    $bgClass = 'bg-gradient-to-r from-slate-100 to-gray-50 shadow-lg transform md:scale-[1.01] z-10';
                                    $borderClass = 'border-slate-300 border-2';
                                    $medali = '🥈';
                                    $ukuranText = 'text-lg md:text-xl text-slate-800';
                                    $avatarBg = 'bg-gradient-to-br from-slate-300 to-gray-400 text-white border-2 border-white shadow-md';
                                    $medaliStyle = 'text-3xl md:text-4xl drop-shadow-sm';
                                } elseif($index == 2) {
                                    $bgClass = 'bg-gradient-to-r from-orange-50 to-rose-50 shadow-md z-10';
                                    $borderClass = 'border-orange-300 border-2';
                                    $medali = '🥉';
                                    $ukuranText = 'text-lg md:text-xl text-orange-900';
                                    $avatarBg = 'bg-gradient-to-br from-orange-400 to-rose-400 text-white border-2 border-white shadow-md';
                                    $medaliStyle = 'text-3xl md:text-4xl drop-shadow-sm';
                                }
                            @endphp

                            <div class="flex items-center justify-between p-4 md:p-5 lg:p-6 rounded-2xl md:rounded-[2rem] {{ $bgClass }} {{ $borderClass }} transition-all duration-300 hover:shadow-lg relative group">
                                
                                <div class="flex items-center gap-3 md:gap-5 overflow-hidden">
                                    <div class="w-10 h-10 md:w-14 md:h-14 flex items-center justify-center shrink-0 group-hover:scale-125 group-hover:rotate-6 transition-transform {{ $medaliStyle }}">
                                        {{ $medali }}
                                    </div>
                                    
                                    <div class="w-12 h-12 md:w-14 md:h-14 {{ $avatarBg }} rounded-[1rem] md:rounded-[1.25rem] flex shrink-0 items-center justify-center text-xl md:text-2xl font-black shadow-sm transition-transform group-hover:scale-105">
                                        {{ substr($siswa->name, 0, 1) }}
                                    </div>
                                    
                                    <div class="truncate pr-2">
                                        <h4 class="{{ $ukuranText }} font-black flex flex-wrap items-center gap-2 truncate">
                                            <span class="truncate">{{ $siswa->name }}</span>
                                            
                                            @if($siswa->id == Auth::id())
                                                <span class="bg-gradient-to-r from-emerald-500 to-teal-500 text-white text-[9px] md:text-[10px] px-2.5 py-1 md:py-1.5 rounded-lg font-black uppercase tracking-widest shadow-sm flex items-center gap-1 shrink-0">
                                                    <span class="w-1.5 h-1.5 bg-white rounded-full animate-pulse"></span> Kamu
                                                </span>
                                            @endif
                                        </h4>
                                        <p class="text-[10px] md:text-xs font-black text-gray-400 uppercase tracking-widest mt-0.5 md:mt-1">⭐ Level {{ $siswa->level }}</p>
                                    </div>
                                </div>
                                
                                <div class="text-right bg-white/80 backdrop-blur-sm px-3 md:px-5 py-2 md:py-3 rounded-xl md:rounded-2xl border border-gray-100 shadow-sm shrink-0 group-hover:border-emerald-200 transition-colors">
                                    <span class="text-lg md:text-2xl font-black text-emerald-500 tracking-tight">⚡ {{ number_format($siswa->xp, 0, ',', '.') }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>

                </div>
            </div>

        </div>
    </div>

    <style>
        @keyframes shine {
            100% { left: 125%; }
        }
        .animate-shine {
            animation: shine 1.5s infinite;
        }
    </style>
</x-app-layout>