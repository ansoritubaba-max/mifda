<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3 md:gap-4 relative z-10">
            <a href="{{ route('admin.dashboard') }}" class="w-10 h-10 md:w-12 md:h-12 bg-white rounded-xl md:rounded-2xl shadow-sm border border-slate-200 hover:bg-slate-50 hover:shadow-md transition-all transform hover:-translate-x-1 flex items-center justify-center text-slate-500 hover:text-blue-600 shrink-0 group">
                <span class="group-hover:-translate-x-1 transition-transform">⬅️</span>
            </a>
            
            <h2 class="font-black text-xl md:text-3xl text-white tracking-tight flex items-center gap-2 md:gap-3">
                <span class="text-2xl md:text-3xl bg-white/10 p-2 rounded-xl md:rounded-2xl border border-white/20">🕵️</span>
                Monitoring Aktivitas Guru
            </h2>
        </div>
    </x-slot>

    <div class="py-6 md:py-12 bg-slate-50 min-h-screen relative overflow-hidden">
        
        <div class="absolute top-0 right-0 w-[30rem] h-[30rem] bg-emerald-200 rounded-full mix-blend-multiply filter blur-[100px] opacity-20 pointer-events-none -translate-y-1/2 translate-x-1/3"></div>
        <div class="absolute bottom-0 left-0 w-[30rem] h-[30rem] bg-blue-200 rounded-full mix-blend-multiply filter blur-[100px] opacity-20 pointer-events-none translate-y-1/3 -translate-x-1/3"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            
            <div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4 bg-white p-5 md:p-6 rounded-[1.5rem] md:rounded-[2rem] shadow-sm border border-slate-100 animate-fade-in-down">
                <div>
                    <h3 class="font-black text-lg text-slate-800">Laporan Keaktifan Tenaga Pendidik</h3>
                    <p class="text-xs font-bold text-slate-400 mt-1 uppercase tracking-widest">Pantau kontribusi materi dari setiap guru</p>
                </div>
                <div class="bg-blue-50 text-blue-600 border border-blue-100 px-4 py-2 rounded-xl text-xs font-black uppercase tracking-widest shadow-sm flex items-center gap-2 w-fit">
                    Total: <span class="bg-blue-600 text-white px-2 py-0.5 rounded-md">{{ $gurus->count() }} Guru</span>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 md:gap-8">
                @forelse($gurus as $guru)
                <div class="bg-white p-6 md:p-8 rounded-[2rem] md:rounded-[2.5rem] shadow-sm border border-slate-100 relative overflow-hidden group hover:shadow-xl hover:shadow-blue-100/50 hover:border-blue-200 hover:-translate-y-1 transition-all duration-300">
                    
                    <div class="absolute -right-4 -bottom-4 text-[7rem] md:text-8xl opacity-[0.03] group-hover:scale-110 group-hover:rotate-12 transition-transform duration-500 pointer-events-none select-none">👩‍🏫</div>
                    
                    <div class="absolute left-0 top-0 bottom-0 w-1.5 bg-slate-100 group-hover:bg-blue-500 transition-colors duration-300"></div>

                    <div class="flex items-center gap-4 mb-6 md:mb-8 relative z-10 pl-2">
                        <div class="h-14 w-14 md:h-16 md:w-16 rounded-[1.2rem] md:rounded-2xl bg-gradient-to-br from-blue-500 to-indigo-600 text-white flex items-center justify-center font-black text-2xl md:text-3xl shadow-lg shadow-blue-200/50 group-hover:scale-105 transition-transform">
                            {{ substr($guru->name, 0, 1) }}
                        </div>
                        <div class="overflow-hidden">
                            <h3 class="font-black text-lg md:text-xl text-slate-800 truncate group-hover:text-blue-600 transition-colors">{{ $guru->name }}</h3>
                            <span class="inline-block px-2.5 py-1 bg-slate-100 text-slate-500 border border-slate-200 rounded-lg text-[9px] md:text-[10px] font-black uppercase tracking-widest mt-1.5 shadow-sm">
                                📖 Mengampu {{ $guru->mengampu_mapel_count }} Mapel
                            </span>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-3 md:gap-4 relative z-10 pl-2">
                        
                        <div class="bg-slate-50 p-4 md:p-5 rounded-[1.2rem] md:rounded-2xl text-center border border-slate-100 group-hover:bg-white group-hover:border-blue-100 transition-colors shadow-sm">
                            <p class="text-[9px] md:text-[10px] font-black text-slate-400 uppercase tracking-widest mb-1">Materi Dibuat</p>
                            <div class="flex items-center justify-center gap-2">
                                <p class="text-2xl md:text-3xl font-black text-blue-600">{{ $guru->materis_count }}</p>
                                <span class="text-sm">📚</span>
                            </div>
                        </div>

                        <div class="bg-slate-50 p-4 md:p-5 rounded-[1.2rem] md:rounded-2xl text-center border border-slate-100 flex flex-col justify-center items-center group-hover:bg-white transition-colors shadow-sm">
                            <p class="text-[9px] md:text-[10px] font-black text-slate-400 uppercase tracking-widest mb-2">Status Mengajar</p>
                            <span class="bg-emerald-50 border border-emerald-200 text-emerald-600 px-3 py-1.5 rounded-xl text-[10px] md:text-xs font-black uppercase tracking-widest shadow-sm flex items-center gap-1.5">
                                <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full animate-pulse"></span> Aktif
                            </span>
                        </div>

                    </div>
                </div>
                @empty
                <div class="col-span-1 md:col-span-2 lg:col-span-3 bg-white p-16 md:p-20 rounded-[3rem] text-center border-2 border-dashed border-slate-200 shadow-sm flex flex-col items-center">
                    <span class="text-6xl md:text-7xl block mb-4 opacity-40 grayscale">👩‍🏫</span>
                    <h4 class="font-black text-xl md:text-2xl text-slate-700 mb-2">Belum Ada Guru</h4>
                    <p class="text-slate-400 font-bold text-xs md:text-sm max-w-sm">Belum ada data tenaga pendidik yang terdaftar di dalam sistem.</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>

    <style>
        @keyframes fade-in-down {
            0% { opacity: 0; transform: translateY(-15px); }
            100% { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in-down {
            animation: fade-in-down 0.5s ease-out forwards;
        }
    </style>
</x-app-layout>