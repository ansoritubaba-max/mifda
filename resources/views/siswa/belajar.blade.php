<x-app-layout>
    <x-slot name="header">
        <div>
            <p class="text-indigo-300 text-xs font-black uppercase tracking-[0.2em] mb-1">Zona Belajar</p>
            <h2 class="font-black text-2xl md:text-3xl text-white tracking-tight flex items-center gap-3">
                <span class="text-3xl md:text-4xl">🚀</span> Ayo Belajar Sambil Bermain!
            </h2>
        </div>
    </x-slot>

    <div class="py-8 md:py-12 bg-slate-50 min-h-screen relative overflow-hidden">
        
        <div class="absolute top-0 left-0 w-96 h-96 bg-indigo-200 rounded-full mix-blend-multiply filter blur-3xl opacity-20 pointer-events-none"></div>
        <div class="absolute top-0 right-0 w-96 h-96 bg-violet-200 rounded-full mix-blend-multiply filter blur-3xl opacity-20 pointer-events-none"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-10 relative z-10">
            
            <div class="bg-gradient-to-br from-indigo-600 via-violet-600 to-indigo-700 rounded-[2rem] md:rounded-[3rem] shadow-2xl shadow-indigo-300/30 relative overflow-hidden text-white border border-white/10">
                <div class="absolute inset-0 opacity-[0.04]" style="background-image: radial-gradient(#fff 1px, transparent 1px); background-size: 20px 20px;"></div>
                <div class="absolute -right-10 -top-10 w-64 h-64 bg-cyan-400 rounded-full mix-blend-overlay filter blur-3xl opacity-15 pointer-events-none"></div>
                
                <div class="p-6 md:p-10 lg:p-12 flex flex-col lg:flex-row justify-between items-center gap-8 relative z-10">
                    
                    <div class="flex flex-col sm:flex-row items-center sm:items-start gap-6 text-center sm:text-left w-full lg:w-auto">
                        <div class="w-24 h-24 md:w-32 md:h-32 shrink-0 relative transition-transform duration-300 hover:scale-105">
                            @if(Auth::user()->avatar)
                                {{-- 🚀 PERBAIKAN: Menggunakan asset() untuk memanggil foto profil siswa --}}
                                <img src="{{ asset('storage/' . Auth::user()->avatar) }}" 
                                alt="Avatar" 
                                class="w-full h-full rounded-[1.5rem] md:rounded-[2rem] object-cover shadow-xl border-4 border-white/30 backdrop-blur-sm">
                            @else
                                <div class="w-full h-full bg-white/20 backdrop-blur-md text-white rounded-[1.5rem] md:rounded-[2rem] flex items-center justify-center text-5xl md:text-6xl font-black shadow-xl border-4 border-white/30">
                                    {{ substr(Auth::user()->name, 0, 1) }}
                                </div>
                            @endif
                            <div class="absolute -bottom-1 -right-1 md:-bottom-2 md:-right-2 bg-cyan-400 w-6 h-6 md:w-8 md:h-8 rounded-full border-4 border-indigo-700 shadow-sm animate-pulse"></div>
                        </div>

                        <div class="mt-2">
                            <h3 class="text-3xl md:text-4xl font-black tracking-tight drop-shadow-sm">Halo, {{ Auth::user()->name }}! 👋</h3>
                            <p class="text-blue-50 font-medium text-base md:text-lg mt-2 opacity-90">Petualangan seru menunggumu hari ini!</p>
                        </div>
                    </div>
                    
                    <div class="flex flex-col sm:flex-row items-center gap-4 w-full lg:w-auto">
                        <div class="flex gap-3 w-full sm:w-auto justify-center">
                            <span class="inline-flex items-center justify-center bg-white/10 backdrop-blur-md border border-white/20 text-white font-bold px-5 md:px-6 py-3 md:py-4 rounded-xl md:rounded-2xl shadow-sm text-sm md:text-lg flex-1 sm:flex-none">
                                ⭐ Lvl {{ Auth::user()->level ?? 1 }}
                            </span>
                            <span class="inline-flex items-center justify-center bg-white/10 backdrop-blur-md border border-white/20 text-white font-bold px-5 md:px-6 py-3 md:py-4 rounded-xl md:rounded-2xl shadow-sm text-sm md:text-lg flex-1 sm:flex-none">
                                ⚡ {{ Auth::user()->xp }} XP
                            </span>
                        </div>
                        <a href="{{ route('siswa.leaderboard') }}" class="w-full sm:w-auto bg-white text-indigo-700 hover:bg-indigo-50 font-black py-3 md:py-4 px-6 md:px-8 rounded-xl md:rounded-2xl shadow-xl transition transform hover:scale-105 hover:-translate-y-1 flex items-center justify-center gap-2 text-sm md:text-lg">
                            🏆 Papan Juara
                        </a>
                    </div>
                </div>
            </div>

            @if(Auth::user()->siap_lulus)
                <div class="bg-gradient-to-r from-teal-400 to-emerald-500 text-white p-5 md:p-6 rounded-2xl md:rounded-[2rem] shadow-lg shadow-emerald-200 flex flex-col sm:flex-row items-center justify-center gap-4 font-bold tracking-wide text-sm border-2 border-emerald-300 animate-bounce text-center">
                    <span class="text-3xl md:text-4xl">🎉</span>
                    <span>Selamat! Kamu sudah siap untuk naik kelas! Terus pertahankan prestasimu!</span>
                </div>
            @endif

            <div>
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6 md:mb-8">
                    <h3 class="text-2xl md:text-3xl font-black text-gray-800 flex items-center gap-3">
                        <span class="text-3xl md:text-4xl">📚</span> Pilih Misi Belajarmu
                    </h3>
                    <span class="bg-gradient-to-r from-indigo-50 to-violet-50 text-indigo-700 px-4 py-2 rounded-xl font-bold text-xs uppercase tracking-widest border border-indigo-100 shadow-sm self-start sm:self-auto">
                        Materi Interaktif ✨
                    </span>
                </div>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 md:gap-8">
                    @foreach ($mapels as $mapel)
                        <a href="{{ route('siswa.materi', $mapel->id) }}" class="block transform transition-all duration-300 hover:-translate-y-2 hover:shadow-2xl rounded-3xl md:rounded-[2.5rem] bg-white border border-gray-100 overflow-hidden relative group" style="box-shadow: 0 10px 40px -10px {{ $mapel->warna_tema ?? '#10b981' }}40;">
                            
                            <div class="h-3 w-full" style="background-color: {{ $mapel->warna_tema ?? '#10b981' }};"></div>
                            
                            <div class="p-6 md:p-8 relative z-10 flex flex-col h-full">
                                
                                <div class="flex justify-between items-start mb-6">
                                    <div class="w-20 h-20 md:w-24 md:h-24 rounded-2xl flex items-center justify-center shadow-inner group-hover:rotate-6 transition-transform duration-300" style="background-color: {{ $mapel->warna_tema ?? '#10b981' }}15; border: 2px solid {{ $mapel->warna_tema ?? '#10b981' }}30;"> 
                                        <span class="text-4xl md:text-5xl drop-shadow-sm">
                                            {{ strtolower($mapel->nama_mapel) == 'matematika' ? '🧮' : (strtolower($mapel->nama_mapel) == 'ipa' ? '🔬' : (strtolower($mapel->nama_mapel) == 'bahasa indonesia' ? '📝' : '📖')) }}
                                        </span>
                                    </div>
                                    <div class="w-10 h-10 rounded-full flex items-center justify-center text-white font-bold text-sm shadow-md" style="background-color: {{ $mapel->warna_tema ?? '#10b981' }}" title="Jumlah Materi">
                                        {{ $mapel->materis_count ?? 0 }}
                                    </div>
                                </div>
                                
                                <h4 class="text-xl md:text-2xl font-black text-gray-800 mb-4 tracking-tight">{{ $mapel->nama_mapel }}</h4>
                                
                                <div class="flex flex-wrap gap-2 mb-8">
                                    <span class="bg-gray-50 text-gray-500 border border-gray-200 px-3 py-1.5 rounded-lg text-[10px] md:text-xs font-bold uppercase tracking-wider">📺 Video</span>
                                    <span class="bg-gray-50 text-gray-500 border border-gray-200 px-3 py-1.5 rounded-lg text-[10px] md:text-xs font-bold uppercase tracking-wider">📄 PDF</span>
                                    <span class="bg-gray-50 text-gray-500 border border-gray-200 px-3 py-1.5 rounded-lg text-[10px] md:text-xs font-bold uppercase tracking-wider">📝 Kuis</span>
                                </div>
                                
                                <div class="mt-auto">
                                    <button class="w-full px-6 py-4 rounded-xl md:rounded-2xl font-bold text-white text-sm md:text-base shadow-lg transition-all opacity-90 group-hover:opacity-100 flex items-center justify-center gap-2" style="background-color: {{ $mapel->warna_tema ?? '#10b981' }}">
                                        Mulai Belajar <span class="group-hover:translate-x-1 transition-transform">→</span>
                                    </button>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>

        </div>
    </div>
</x-app-layout>