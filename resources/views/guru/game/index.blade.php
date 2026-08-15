<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3 md:gap-4 relative z-10">
            <a href="{{ route('guru.dashboard') }}" class="w-10 h-10 md:w-12 md:h-12 bg-white rounded-xl md:rounded-2xl shadow-sm border border-slate-200 hover:bg-slate-50 hover:shadow-md transition-all transform hover:-translate-x-1 flex items-center justify-center text-slate-500 hover:text-emerald-600 shrink-0 group">
                <span class="group-hover:-translate-x-1 transition-transform">⬅️</span>
            </a>
            
            <h2 class="font-black text-xl md:text-3xl text-white tracking-tight flex items-center gap-2 md:gap-3">
                <span class="text-2xl md:text-3xl bg-white/10 p-2 rounded-xl md:rounded-2xl border border-white/20">🎮</span>
                Game Edukasi Eksternal
            </h2>
        </div>
    </x-slot>

    <div class="py-6 md:py-12 bg-slate-50 min-h-screen relative overflow-hidden">
        
        <div class="absolute top-0 right-0 w-[30rem] h-[30rem] bg-emerald-200 rounded-full mix-blend-multiply filter blur-[100px] opacity-20 pointer-events-none -translate-y-1/2 translate-x-1/3"></div>
        <div class="absolute bottom-0 left-0 w-[30rem] h-[30rem] bg-blue-200 rounded-full mix-blend-multiply filter blur-[100px] opacity-20 pointer-events-none translate-y-1/3 -translate-x-1/3"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 grid grid-cols-1 lg:grid-cols-12 gap-6 md:gap-8 relative z-10">
            
            <div class="lg:col-span-4 lg:sticky lg:top-24 h-fit">
                <div class="bg-white rounded-[2rem] md:rounded-[2.5rem] shadow-xl shadow-slate-200/50 border border-slate-100 overflow-hidden">
                    
                    <div class="h-3 w-full bg-gradient-to-r from-emerald-400 to-teal-500"></div>

                    <div class="p-6 md:p-8">
                        <div class="flex items-center gap-3 mb-6">
                            <div class="w-12 h-12 bg-gradient-to-br from-emerald-50 to-teal-100 text-emerald-600 rounded-[1.25rem] flex items-center justify-center text-2xl shadow-sm border border-emerald-100">➕</div>
                            <h3 class="font-black text-lg md:text-xl text-slate-800 leading-tight">Tambah Game</h3>
                        </div>
                        
                        @if(session('success'))
                            <div class="bg-gradient-to-r from-emerald-50 to-teal-50 text-emerald-700 p-4 rounded-2xl mb-6 text-xs font-black uppercase tracking-widest flex items-center gap-3 border border-emerald-200 shadow-sm animate-fade-in-down">
                                <span class="text-xl">✅</span> {{ session('success') }}
                            </div>
                        @endif

                        <form action="{{ route('guru.game.store') }}" method="POST" class="space-y-5">
                            @csrf
                            
                            <div>
                                <label class="block text-[10px] md:text-xs font-black text-slate-500 uppercase tracking-widest mb-2 px-1">Judul Game <span class="text-rose-500">*</span></label>
                                <input type="text" name="judul" placeholder="Contoh: Teka-Teki Silang IPA" class="w-full rounded-2xl border border-slate-200 bg-slate-50 focus:bg-white focus:ring-2 focus:ring-emerald-500 focus:border-transparent font-bold text-slate-800 px-4 py-3.5 transition-all shadow-sm" required>
                            </div>
                            
                            <div>
                                <label class="block text-[10px] md:text-xs font-black text-slate-500 uppercase tracking-widest mb-2 px-1">Mata Pelajaran <span class="text-rose-500">*</span></label>
                                <div class="relative">
                                    <select name="mapel_id" class="w-full rounded-2xl border border-slate-200 bg-slate-50 focus:bg-white focus:ring-2 focus:ring-emerald-500 focus:border-transparent font-bold text-slate-700 px-4 py-3.5 transition-all shadow-sm appearance-none cursor-pointer" required>
                                        <option value="">-- Pilih Mapel --</option>
                                        @foreach($mapels as $m)
                                            <option value="{{ $m->id }}">{{ $m->nama_mapel }} ({{ $m->kelas->nama_kelas ?? 'Umum' }})</option>
                                        @endforeach
                                    </select>
                                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-slate-400">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"></path></svg>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="p-4 bg-slate-50 border border-slate-200 rounded-2xl shadow-inner group focus-within:border-emerald-300 transition-colors">
                                <label class="block text-[10px] md:text-xs font-black text-emerald-600 uppercase tracking-widest mb-2 px-1 flex items-center gap-1.5">
                                    <span>🔗</span> Link Game (URL Embed) <span class="text-rose-500">*</span>
                                </label>
                                <input type="url" name="link_game" placeholder="https://wordwall.net/embed/..." class="w-full rounded-xl border border-slate-200 bg-white focus:ring-2 focus:ring-emerald-500 focus:border-transparent font-medium text-slate-700 text-sm px-4 py-3 shadow-sm transition-all" required>
                                
                                <div class="mt-3 p-3 bg-white border border-slate-100 rounded-xl flex items-start gap-2 shadow-sm">
                                    <span class="text-sm">💡</span>
                                    <p class="text-[9px] md:text-[10px] text-slate-500 font-bold leading-relaxed">
                                        Pastikan link yang dimasukkan mengandung kata <strong class="text-slate-800 bg-slate-100 px-1 py-0.5 rounded">/embed/</strong> atau pilih opsi <strong class="text-slate-800">"Sematkan"</strong> pada website penyedia game.
                                    </p>
                                </div>
                            </div>
                            
                            <button type="submit" class="w-full bg-gradient-to-r from-emerald-500 to-teal-600 text-white font-black py-4 rounded-2xl shadow-lg shadow-emerald-200/50 hover:shadow-xl hover:-translate-y-1 active:scale-95 transition-all mt-6 uppercase tracking-widest text-[10px] md:text-xs border border-emerald-400/50 flex items-center justify-center gap-2">
                                Publikasikan Game 🚀
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-8 space-y-4 md:space-y-6">
                
                <div class="flex items-center justify-between px-2 mb-4">
                    <h3 class="font-black text-xl md:text-2xl text-slate-800 flex items-center gap-3">
                        <span class="p-2 bg-white shadow-sm border border-slate-100 rounded-xl">📋</span> 
                        Daftar Game Tersedia
                    </h3>
                </div>

                <div class="space-y-4">
                    @forelse($games as $game)
                        <div class="bg-white p-5 md:p-6 rounded-[1.5rem] md:rounded-[2rem] shadow-sm border border-slate-200 flex flex-col md:flex-row justify-between md:items-center gap-5 group hover:shadow-xl hover:shadow-emerald-100/40 hover:border-emerald-200 transition-all duration-300 relative overflow-hidden">
                            
                            <div class="absolute left-0 top-0 bottom-0 w-1.5 bg-slate-200 group-hover:bg-emerald-400 transition-colors duration-300"></div>
                            
                            <div class="flex items-center gap-4 md:gap-5 overflow-hidden pl-2 md:pl-4">
                                <div class="w-14 h-14 md:w-16 md:h-16 shrink-0 rounded-[1.2rem] flex items-center justify-center text-3xl shadow-sm border group-hover:scale-110 group-hover:rotate-12 transition-transform duration-300 relative" style="background-color: {{ $game->warna_tema ?? '#10b981' }}15; border-color: {{ $game->warna_tema ?? '#10b981' }}30; color: {{ $game->warna_tema ?? '#10b981' }}">
                                    🎮
                                </div>
                                <div class="overflow-hidden">
                                    <div class="flex items-center gap-2 mb-1.5 flex-wrap">
                                        <h4 class="font-black text-lg md:text-xl text-slate-800 truncate group-hover:text-emerald-600 transition-colors">{{ $game->judul }}</h4>
                                    </div>
                                    <div class="flex items-center gap-2 flex-wrap">
                                        <span class="px-2.5 py-1 rounded-lg text-[9px] font-black text-white uppercase tracking-widest shrink-0 shadow-sm" style="background-color: {{ $game->warna_tema ?? '#10b981' }}">
                                            {{ $game->nama_mapel }}
                                        </span>
                                        <p class="text-[10px] md:text-xs text-slate-400 font-bold truncate max-w-[200px] md:max-w-[300px]" title="{{ $game->link_game }}">🔗 {{ $game->link_game }}</p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="flex items-center gap-2 md:gap-3 shrink-0 w-full md:w-auto mt-2 md:mt-0 ml-2 md:ml-0">
                                <a href="{{ $game->link_game }}" target="_blank" class="flex-1 md:flex-none bg-blue-50 border border-blue-100 text-blue-600 px-5 md:px-6 py-3 rounded-xl font-black text-[10px] uppercase tracking-widest hover:bg-blue-600 hover:text-white hover:border-blue-600 hover:shadow-md transition-all text-center flex items-center justify-center gap-1.5">
                                    <span>↗️</span> Test
                                </a>
                                
                                <form action="{{ route('guru.game.destroy', $game->id) }}" method="POST" onsubmit="return confirm('⚠️ Yakin ingin menghapus game ini? Tindakan ini tidak bisa dibatalkan.')" class="flex-1 md:flex-none">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="w-full bg-rose-50 text-rose-600 border border-rose-100 hover:bg-rose-600 hover:text-white px-5 md:px-6 py-3 rounded-xl font-black text-[10px] uppercase tracking-widest hover:shadow-md transition-all flex items-center justify-center gap-1.5 cursor-pointer">
                                        <span>🗑️</span> Hapus
                                    </button>
                                </form>
                            </div>

                        </div>
                    @empty
                        <div class="bg-white p-16 md:p-24 rounded-[2.5rem] md:rounded-[3rem] text-center border-2 border-dashed border-slate-200 flex flex-col items-center justify-center shadow-sm">
                            <span class="text-6xl md:text-7xl opacity-40 mb-5 block grayscale">🕹️</span>
                            <h4 class="font-black text-xl md:text-2xl text-slate-700 mb-2">Belum Ada Game</h4>
                            <p class="text-slate-500 font-bold text-xs md:text-sm max-w-sm">Jadikan kelas lebih interaktif! Tambahkan game pertamamu melalui form di sebelah kiri.</p>
                        </div>
                    @endforelse
                </div>

            </div>
        </div>
    </div>

    <style>
        @keyframes fade-in-down {
            0% { opacity: 0; transform: translateY(-10px); }
            100% { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in-down { animation: fade-in-down 0.5s ease-out forwards; }
    </style>
</x-app-layout>