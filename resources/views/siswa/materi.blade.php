<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 relative z-10">
            <div class="flex items-center gap-3 md:gap-4">
                <a href="{{ route('siswa.belajar') }}" class="bg-white p-2.5 md:p-3 rounded-xl md:rounded-2xl shadow-sm border border-gray-200 hover:bg-slate-50 hover:shadow-md transition-all transform hover:-translate-x-1 font-bold text-gray-600 text-xs md:text-sm flex items-center gap-2 group">
                    <span class="group-hover:-translate-x-1 transition-transform">⬅️</span> 
                    <span class="hidden sm:inline">Kembali</span>
                </a>
                
                <h2 class="font-black text-2xl md:text-3xl leading-tight flex items-center gap-2 md:gap-3 drop-shadow-sm" style="color: {{ $mapel->warna_tema ?? '#10b981' }}">
                    <span class="text-3xl md:text-4xl">🗺️</span> Misi: {{ $mapel->nama_mapel }}
                </h2>
            </div>
            
            <div class="bg-white px-4 md:px-5 py-2 md:py-2.5 rounded-xl shadow-sm text-[10px] md:text-xs font-black uppercase tracking-widest flex items-center gap-2 self-start md:self-auto border-2 border-transparent" style="color: {{ $mapel->warna_tema ?? '#10b981' }}; border-color: {{ $mapel->warna_tema ?? '#10b981' }}40;">
                <span class="w-2 h-2 rounded-full animate-pulse" style="background-color: {{ $mapel->warna_tema ?? '#10b981' }}"></span>
                Total: {{ $mapel->materis->count() }} Misi
            </div>
        </div>
    </x-slot>

    <div class="py-6 md:py-12 bg-slate-50 min-h-screen relative overflow-hidden">
        
        <div class="absolute top-0 right-0 w-96 h-96 bg-emerald-200 rounded-full mix-blend-multiply filter blur-[100px] opacity-30 pointer-events-none -translate-y-1/2 translate-x-1/3"></div>
        <div class="absolute bottom-1/4 left-0 w-96 h-96 bg-blue-200 rounded-full mix-blend-multiply filter blur-[100px] opacity-20 pointer-events-none translate-y-1/2 -translate-x-1/3"></div>

        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            
            @if(session('success'))
                <div id="flash-success" class="bg-gradient-to-r from-emerald-400 to-teal-500 text-white p-5 md:p-6 mb-8 md:mb-10 rounded-2xl md:rounded-[2rem] shadow-xl shadow-emerald-200/50 text-sm md:text-base font-black text-center border-2 border-white uppercase tracking-widest flex items-center justify-center gap-3 transition-all duration-500">
                    <span class="text-3xl drop-shadow-md">🎉</span>
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div id="flash-error" class="bg-gradient-to-r from-rose-500 to-red-600 text-white p-5 md:p-6 mb-8 md:mb-10 rounded-2xl md:rounded-[2rem] shadow-xl shadow-red-200/50 text-sm md:text-base font-black text-center border-2 border-white uppercase tracking-widest flex items-center justify-center gap-3 transition-all duration-500">
                    <span class="text-3xl drop-shadow-md">⚠️</span>
                    {{ session('error') }}
                </div>
            @endif

            <script>
                ['flash-success','flash-error'].forEach(id => {
                    const el = document.getElementById(id);
                    if (el) setTimeout(() => { el.style.opacity = '0'; el.style.transform = 'translateY(-8px)'; setTimeout(() => el.remove(), 500); }, 4000);
                });
            </script>

            {{-- Info ujian semester --}}
            <div class="bg-blue-50 border border-blue-200 rounded-2xl p-4 flex items-center gap-3">
                <span class="text-xl shrink-0">📋</span>
                <p class="text-sm font-bold text-blue-700">
                    Halaman ini untuk <strong>materi latihan</strong>.
                    Untuk <strong>Ujian Semester Ganjil/Genap</strong>,
                    buka menu <a href="{{ route('siswa.ujian.index') }}" class="underline hover:text-blue-900">📋 Ujian</a> di navbar.
                </p>
            </div>

            @if($mapel->materis->isEmpty())
                <div class="bg-white p-12 md:p-16 rounded-[2rem] md:rounded-[3rem] shadow-sm text-center border border-slate-100 flex flex-col items-center relative overflow-hidden">
                    <div class="absolute inset-0 bg-slate-50/50"></div>
                    <span class="text-6xl md:text-7xl mb-6 block animate-pulse opacity-40 grayscale relative z-10">🚧</span>
                    <h3 class="text-2xl md:text-3xl font-black text-slate-800 relative z-10">Ups, misinya belum siap!</h3>
                    <p class="text-xs md:text-sm text-slate-500 mt-3 font-bold uppercase tracking-widest max-w-md mx-auto leading-relaxed relative z-10">Bapak/Ibu Guru sedang menyiapkan misi seru untukmu. Cek lagi nanti ya!</p>
                </div>
            @else
                
                <div class="space-y-6 md:space-y-8">
                    @foreach ($mapel->materis as $index => $materi)
                        <div class="bg-white p-5 md:p-8 rounded-[2rem] md:rounded-[2.5rem] shadow-sm hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 border border-slate-100 flex flex-col md:flex-row items-center justify-between gap-6 group relative overflow-hidden">
                            
                            <div class="absolute left-0 top-0 bottom-0 w-2 md:w-3 transition-all duration-300 group-hover:w-3 md:group-hover:w-4" style="background-color: {{ $mapel->warna_tema ?? '#10b981' }}"></div>

                            <div class="absolute right-6 top-1/2 -translate-y-1/2 text-[8rem] md:text-9xl font-black opacity-[0.02] pointer-events-none group-hover:scale-110 transition-transform duration-500 select-none">
                                {{ str_pad($index + 1, 2, '0', STR_PAD_LEFT) }}
                            </div>

                            <div class="flex flex-col sm:flex-row items-center sm:items-start md:items-center gap-5 md:gap-6 w-full md:w-auto relative z-10 pl-3 md:pl-6">
                                
                                <div class="w-16 h-16 md:w-20 md:h-20 rounded-[1.25rem] md:rounded-[1.5rem] flex shrink-0 items-center justify-center font-black text-2xl md:text-4xl text-white shadow-md border-4 border-white group-hover:rotate-12 group-hover:scale-110 transition-transform duration-300" style="background-color: {{ $mapel->warna_tema ?? '#10b981' }}">
                                    {{ $index + 1 }}
                                </div>
                                
                                <div class="text-center sm:text-left">
                                    <h4 class="text-xl md:text-2xl font-black text-slate-800 leading-tight mb-3 md:mb-4 group-hover:text-emerald-600 transition-colors drop-shadow-sm">
                                        {{ $materi->judul }}
                                    </h4>
                                    
                                    <div class="flex flex-wrap justify-center sm:justify-start items-center gap-2">
                                        @if($materi->tipe === 'youtube')
                                            <span class="bg-red-50 text-red-600 border border-red-100 text-[10px] md:text-xs px-3 py-1.5 rounded-xl font-black uppercase tracking-widest shadow-sm flex items-center gap-1.5">
                                                <span class="text-sm">🎬</span> YouTube
                                            </span>
                                        @elseif($materi->tipe === 'video_lokal')
                                            <span class="bg-indigo-50 text-indigo-600 border border-indigo-100 text-[10px] md:text-xs px-3 py-1.5 rounded-xl font-black uppercase tracking-widest shadow-sm flex items-center gap-1.5">
                                                <span class="text-sm">🎞️</span> Video Lokal
                                            </span>
                                        @elseif($materi->tipe === 'dokumen')
                                            <span class="bg-orange-50 text-orange-600 border border-orange-100 text-[10px] md:text-xs px-3 py-1.5 rounded-xl font-black uppercase tracking-widest shadow-sm flex items-center gap-1.5">
                                                <span class="text-sm">📄</span> Dokumen PDF
                                            </span>
                                        @else
                                            <span class="bg-blue-50 text-blue-600 border border-blue-100 text-[10px] md:text-xs px-3 py-1.5 rounded-xl font-black uppercase tracking-widest shadow-sm flex items-center gap-1.5">
                                                <span class="text-sm">📝</span> Artikel
                                            </span>
                                        @endif

                                        <span class="inline-flex items-center gap-1.5 bg-gradient-to-r from-amber-50 to-yellow-100 border border-yellow-200 text-amber-700 text-[10px] md:text-xs px-3 py-1.5 rounded-xl font-black uppercase tracking-widest shadow-sm">
                                            <span class="text-sm">🎁</span> +{{ $materi->xp_reward }} XP
                                        </span>
                                        @if(($materi->jenis ?? 'latihan') === 'ujian_ganjil')
                                            <span class="inline-flex items-center gap-1.5 bg-blue-50 border border-blue-200 text-blue-700 text-[10px] md:text-xs px-3 py-1.5 rounded-xl font-black uppercase tracking-widest shadow-sm">
                                                <span class="text-sm">📋</span> Ujian Ganjil
                                            </span>
                                        @elseif(($materi->jenis ?? 'latihan') === 'ujian_genap')
                                            <span class="inline-flex items-center gap-1.5 bg-purple-50 border border-purple-200 text-purple-700 text-[10px] md:text-xs px-3 py-1.5 rounded-xl font-black uppercase tracking-widest shadow-sm">
                                                <span class="text-sm">📋</span> Ujian Genap
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            
                            <a href="{{ route('siswa.materi.detail', $materi->id) }}" class="w-full md:w-auto text-center px-8 md:px-10 py-4 md:py-5 rounded-xl md:rounded-2xl font-black text-white text-sm md:text-base shadow-lg hover:shadow-2xl hover:-translate-y-1 transition-all active:scale-95 uppercase tracking-widest relative z-10 shrink-0 flex items-center justify-center gap-2 group-hover:brightness-110" style="background-color: {{ $mapel->warna_tema ?? '#10b981' }}; box-shadow: 0 10px 25px -5px {{ $mapel->warna_tema ?? '#10b981' }}60;">
                                BUKA <span class="group-hover:translate-x-1 group-hover:-translate-y-1 transition-transform">🚀</span>
                            </a>
                        </div>
                    @endforeach
                </div>

            @endif

        </div>
    </div>
</x-app-layout>