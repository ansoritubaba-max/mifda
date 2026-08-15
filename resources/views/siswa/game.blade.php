<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 relative z-10">
            <div class="flex items-center gap-3 md:gap-4">
                <a href="{{ route('siswa.dashboard') }}" class="bg-white p-2.5 md:p-3 rounded-xl md:rounded-2xl shadow-sm border border-gray-200 hover:bg-slate-50 hover:shadow-md transition-all transform hover:-translate-x-1 font-bold text-gray-600 text-xs md:text-sm flex items-center gap-2 group">
                    <span class="group-hover:-translate-x-1 transition-transform">⬅️</span> 
                    <span class="hidden sm:inline">Kembali</span>
                </a>
                
                <h2 class="font-black text-xl md:text-3xl text-white leading-tight tracking-tight flex items-center gap-2 md:gap-3">
                    <span class="text-3xl md:text-4xl animate-bounce">🎮</span> {{ $game->judul }}
                </h2>
            </div>
            
            <span class="px-5 md:px-6 py-2.5 md:py-3 rounded-xl md:rounded-2xl text-xs font-black text-white uppercase tracking-widest shadow-lg border-2 border-white/20 inline-flex items-center justify-center transform hover:scale-105 transition-transform" style="background-color: {{ $game->warna_tema ?? '#10b981' }}">
                {{ $game->nama_mapel }}
            </span>
        </div>
    </x-slot>

    <div class="py-6 md:py-10 bg-slate-900 min-h-screen relative overflow-hidden">
        
        <div class="absolute top-0 left-1/4 w-96 h-96 bg-emerald-500 rounded-full mix-blend-screen filter blur-[120px] opacity-20 pointer-events-none animate-pulse"></div>
        <div class="absolute bottom-0 right-1/4 w-96 h-96 bg-blue-600 rounded-full mix-blend-screen filter blur-[120px] opacity-20 pointer-events-none" style="animation: pulse 4s cubic-bezier(0.4, 0, 0.6, 1) infinite;"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            
            <div class="bg-slate-800/60 backdrop-blur-xl p-4 md:p-8 lg:p-10 rounded-[2rem] md:rounded-[3rem] shadow-2xl border border-slate-700/50 flex flex-col items-center justify-center relative">
                
                <div class="absolute top-0 left-1/2 -translate-x-1/2 w-1/3 h-1.5 rounded-b-full shadow-[0_0_15px_rgba(255,255,255,0.5)]" style="background-color: {{ $game->warna_tema ?? '#10b981' }}"></div>
                
                <div class="w-full h-[60vh] md:h-[75vh] min-h-[400px] md:min-h-[600px] bg-black rounded-[1.5rem] md:rounded-[2rem] overflow-hidden relative shadow-2xl border-4 md:border-8 border-slate-800 group" style="box-shadow: 0 0 50px -10px {{ $game->warna_tema ?? '#10b981' }}50;">
                    
                    <div class="absolute inset-0 flex items-center justify-center text-slate-600 font-black text-xl md:text-2xl tracking-widest z-0">
                        MEMUAT GAME... 🚀
                    </div>

                    <iframe 
                        src="{{ $game->link_game }}" 
                        class="absolute top-0 left-0 w-full h-full border-0 relative z-10 bg-black" 
                        allowfullscreen="true" 
                        webkitallowfullscreen="true" 
                        mozallowfullscreen="true">
                    </iframe>
                </div>
                
                <div class="mt-8 md:mt-10 bg-slate-800/80 border border-slate-700 px-6 py-4 rounded-2xl max-w-2xl w-full flex flex-col sm:flex-row items-center gap-4 shadow-inner hover:bg-slate-700/80 transition-colors">
                    <div class="w-12 h-12 shrink-0 bg-slate-900 rounded-full flex items-center justify-center text-2xl shadow-sm border border-slate-700">💡</div>
                    <div class="text-center sm:text-left">
                        <p class="text-slate-300 font-medium text-xs md:text-sm leading-relaxed">
                            Game tidak muncul atau error? Pastikan guru telah memasukkan tautan tipe <span class="text-white font-bold">"Embed/Sematkan"</span>.
                        </p>
                        <a href="{{ $game->link_game }}" target="_blank" class="text-emerald-400 font-bold hover:text-emerald-300 transition-colors text-xs md:text-sm mt-1.5 inline-flex items-center gap-1.5 group">
                            Mainkan di tab baru 
                            <svg class="w-4 h-4 transform group-hover:translate-x-1 group-hover:-translate-y-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                        </a>
                    </div>
                </div>

            </div>

        </div>
    </div>
</x-app-layout>