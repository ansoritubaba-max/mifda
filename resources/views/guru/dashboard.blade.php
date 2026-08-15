<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-5 relative z-10">
            <div>
                <div class="flex items-center gap-3 mb-1">
                    <span class="text-3xl md:text-4xl bg-white/10 p-2 rounded-2xl border border-white/20">👩‍🏫</span>
                    <h2 class="font-black text-2xl md:text-3xl text-white tracking-tight leading-tight">
                        Ruang Guru
                    </h2>
                </div>
                <p class="text-indigo-300 text-sm font-bold ml-16 tracking-wide">Kelola materi & misi belajar siswa</p>
            </div>

            <a href="{{ route('guru.materi.create') }}" class="bg-gradient-to-r from-indigo-500 to-violet-600 text-white px-6 md:px-8 py-3.5 md:py-4 rounded-xl md:rounded-2xl shadow-lg shadow-indigo-900/40 hover:shadow-xl font-black transition-all hover:-translate-y-1 hover:scale-105 flex items-center justify-center gap-3 text-sm md:text-base border border-indigo-400/30">
                <span class="text-xl bg-white/20 rounded-lg w-8 h-8 flex items-center justify-center">➕</span>
                Tambah Misi Baru
            </a>
        </div>
    </x-slot>

    <div class="py-6 md:py-12 bg-slate-50 min-h-screen relative overflow-hidden">
        
        <div class="absolute top-0 right-0 w-[30rem] h-[30rem] bg-indigo-200 rounded-full mix-blend-multiply filter blur-[100px] opacity-15 pointer-events-none -translate-y-1/2 translate-x-1/3"></div>
        <div class="absolute bottom-0 left-0 w-[30rem] h-[30rem] bg-violet-200 rounded-full mix-blend-multiply filter blur-[100px] opacity-15 pointer-events-none translate-y-1/3 -translate-x-1/3"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6 md:space-y-8 relative z-10">
            
            @if(session('success'))
                <div id="flash-success" class="bg-gradient-to-r from-emerald-400 to-teal-500 text-white p-5 md:p-6 rounded-2xl md:rounded-[2rem] shadow-xl shadow-emerald-200/50 font-black flex items-center gap-4 border border-emerald-300 animate-fade-in-down transition-all duration-500">
                    <span class="text-3xl drop-shadow-md">🎉</span>
                    <span class="text-sm md:text-base tracking-wide">{{ session('success') }}</span>
                </div>
            @endif

            @if(session('error'))
                <div id="flash-error" class="bg-gradient-to-r from-rose-500 to-red-600 text-white p-5 md:p-6 rounded-2xl md:rounded-[2rem] shadow-xl shadow-red-200/50 font-black flex items-center gap-4 border border-red-400 animate-fade-in-down transition-all duration-500">
                    <span class="text-3xl drop-shadow-md">⚠️</span>
                    <span class="text-sm md:text-base tracking-wide">{{ session('error') }}</span>
                </div>
            @endif

            <script>
                ['flash-success','flash-error'].forEach(id => {
                    const el = document.getElementById(id);
                    if (el) setTimeout(() => { el.style.opacity = '0'; el.style.transform = 'translateY(-8px)'; setTimeout(() => el.remove(), 500); }, 4000);
                });
            </script>

            <div class="bg-white rounded-[2rem] md:rounded-[3rem] shadow-xl shadow-slate-200/40 border border-slate-100 overflow-hidden relative">
                
                <div class="h-1 w-full bg-gradient-to-r from-indigo-500 via-violet-500 to-cyan-500"></div>

                <div class="p-6 md:p-8 border-b border-slate-50 flex flex-col sm:flex-row sm:items-center justify-between gap-4 bg-white relative z-10">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 bg-indigo-50 text-indigo-600 border border-indigo-100 rounded-[1.2rem] flex items-center justify-center text-2xl shadow-sm">📚</div>
                        <div>
                            <h3 class="font-black text-xl md:text-2xl text-slate-800 tracking-tight">Daftar Misi Latihan Siswa</h3>
                            <p class="text-[10px] md:text-xs font-bold text-slate-400 uppercase tracking-widest mt-1">Materi & latihan harian — ujian semester ada di menu 📋 Ujian</p>
                        </div>
                    </div>
                    <a href="{{ route('guru.ujian.index') }}"
                       class="shrink-0 flex items-center gap-2 bg-blue-50 text-blue-600 border border-blue-200 hover:bg-blue-600 hover:text-white hover:border-blue-600 px-4 py-2.5 rounded-xl text-xs font-black transition-all uppercase tracking-widest shadow-sm">
                        📋 Kelola Ujian Semester
                    </a>
                </div>

                <div class="overflow-x-auto pb-4">
                    <table class="w-full text-left whitespace-nowrap md:whitespace-normal">
                        <thead class="bg-slate-50/80 border-y border-slate-100 text-[10px] md:text-xs font-black uppercase text-slate-500 tracking-widest">
                            <tr>
                                <th class="p-5 md:p-6 px-6 md:px-8">Judul Misi & Pelajaran</th>
                                <th class="p-5 md:p-6 text-center">Tipe Media</th>
                                <th class="p-5 md:p-6 text-center">Hadiah XP</th>
                                <th class="p-5 md:p-6 text-right px-6 md:px-8">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-50 text-sm md:text-base">
                            @forelse ($materis as $materi)
                                <tr class="hover:bg-slate-50/50 transition-colors duration-200 group">
                                    
                                    <td class="p-5 md:p-6 px-6 md:px-8 align-middle">
                                        <div class="flex items-center gap-4">
                                            <div class="w-12 h-12 md:w-14 md:h-14 rounded-2xl flex items-center justify-center text-xl md:text-2xl shadow-sm border shrink-0 transition-transform group-hover:scale-110" style="background-color: {{ $materi->mapel->warna_tema ?? '#10b981' }}15; border-color: {{ $materi->mapel->warna_tema ?? '#10b981' }}30; color: {{ $materi->mapel->warna_tema ?? '#10b981' }}">
                                                @if($materi->tipe === 'youtube' || $materi->tipe === 'video_lokal') 🎬
                                                @elseif($materi->tipe === 'dokumen' || $materi->tipe === 'pdf') 📄
                                                @else 📝
                                                @endif
                                            </div>
                                            <div>
                                                <h4 class="font-black text-base md:text-lg text-slate-800 group-hover:text-indigo-600 transition-colors leading-tight mb-1">{{ $materi->judul }}</h4>
                                                <span class="text-[9px] md:text-[10px] font-black uppercase tracking-widest px-2.5 py-1 rounded-md bg-slate-100 inline-block border border-slate-200" style="color: {{ $materi->mapel->warna_tema ?? '#64748b' }}">
                                                    🏷️ {{ $materi->mapel->nama_mapel ?? 'Mapel Dihapus' }}
                                                </span>
                                            </div>
                                        </div>
                                    </td>
                                    
                                    <td class="p-5 md:p-6 text-center align-middle">
                                        <span class="bg-white border border-slate-200 text-slate-500 px-4 py-2 rounded-xl font-black text-[10px] md:text-xs uppercase tracking-widest shadow-sm inline-block">
                                            {{ str_replace('_', ' ', $materi->tipe) }}
                                        </span>
                                    </td>
                                    
                                    <td class="p-5 md:p-6 text-center align-middle">
                                        <span class="bg-gradient-to-r from-amber-50 to-yellow-50 border border-yellow-200 text-amber-600 px-4 py-2 rounded-xl font-black text-[10px] md:text-xs shadow-sm inline-flex items-center gap-1.5 uppercase tracking-widest">
                                            <span class="text-sm">⚡</span> +{{ $materi->xp_reward }}
                                        </span>
                                    </td>
                                    
                                    <td class="p-5 md:p-6 px-6 md:px-8 text-right align-middle">
                                        <div class="flex items-center justify-end gap-2 md:gap-3">
                                            <a href="{{ route('guru.soal.index', $materi->id) }}" class="bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white border border-blue-100 hover:border-blue-600 px-4 md:px-5 py-2.5 rounded-xl text-[10px] md:text-xs font-black transition-all shadow-sm hover:shadow-md flex items-center gap-1.5">
                                                <span>📝</span> <span class="hidden md:inline">KUIS</span>
                                            </a>
                                            
                                            <a href="{{ route('guru.materi.edit', $materi->id) }}" class="bg-amber-50 text-amber-600 hover:bg-amber-500 hover:text-white border border-amber-100 hover:border-amber-500 px-4 md:px-5 py-2.5 rounded-xl text-[10px] md:text-xs font-black transition-all shadow-sm hover:shadow-md flex items-center gap-1.5">
                                                <span>✏️</span> <span class="hidden md:inline">EDIT</span>
                                            </a>

                                            <form action="{{ route('guru.materi.destroy', $materi->id) }}" method="POST" onsubmit="return confirm('⚠️ Yakin ingin menghapus misi ini? Semua soal kuis di dalamnya juga akan terhapus secara permanen.');" class="inline-block">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="bg-rose-50 text-rose-600 hover:bg-rose-600 hover:text-white border border-rose-100 hover:border-rose-600 px-4 md:px-5 py-2.5 rounded-xl text-[10px] md:text-xs font-black transition-all shadow-sm hover:shadow-md flex items-center gap-1.5 cursor-pointer">
                                                    <span>🗑️</span> <span class="hidden md:inline">HAPUS</span>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="p-12 md:p-20 text-center">
                                        <div class="flex flex-col items-center justify-center text-slate-400">
                                            <div class="w-24 h-24 md:w-28 md:h-28 bg-slate-50 rounded-[2rem] flex items-center justify-center text-5xl md:text-6xl mb-5 border-2 border-dashed border-slate-200 opacity-60 grayscale">📭</div>
                                            <h4 class="text-xl md:text-2xl font-black text-slate-700 mb-2">Belum ada misi!</h4>
                                            <p class="text-xs md:text-sm font-bold mt-1 text-slate-500 max-w-sm leading-relaxed">Siswa menantikan materi belajar darimu. Yuk, mulai buat misi pertama sekarang!</p>
                                            
                                            <a href="{{ route('guru.materi.create') }}" class="mt-6 bg-indigo-50 text-indigo-600 hover:bg-indigo-600 hover:text-white border border-indigo-200 px-6 py-3 rounded-xl text-xs font-black transition-all uppercase tracking-widest shadow-sm">
                                                Buat Misi Sekarang
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
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