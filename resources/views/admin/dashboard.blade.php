<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-5 relative z-10">
            <div>
                <p class="text-indigo-300 text-xs font-black uppercase tracking-[0.2em] mb-1">Control Panel</p>
                <h2 class="font-black text-2xl md:text-3xl text-white tracking-tight flex items-center gap-3">
                    Selamat Datang, Admin! <span class="animate-pulse origin-bottom-right">👋</span>
                </h2>
                <p class="text-indigo-200/70 font-bold text-xs md:text-sm mt-1">Hari ini adalah hari yang baik untuk memajukan pendidikan.</p>
            </div>

            <div class="flex items-center gap-4 bg-white/10 backdrop-blur-sm p-3 md:p-2 md:pr-5 md:pl-3 rounded-2xl border border-white/20 w-fit">
                <div class="w-12 h-12 md:w-14 md:h-14 bg-gradient-to-br from-indigo-400 to-violet-500 rounded-[1.2rem] shadow-lg flex items-center justify-center text-white text-2xl md:text-3xl shrink-0">
                    👑
                </div>
                <div class="text-left md:text-right">
                    <p class="text-[9px] md:text-[10px] font-black text-indigo-200 uppercase tracking-widest">Waktu Sistem</p>
                    <p class="text-xs md:text-sm font-black text-white leading-tight">{{ now()->format('d M Y') }}</p>
                    <p class="text-[10px] font-bold text-indigo-200">{{ now()->format('H:i') }} WIB</p>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-6 md:py-12 bg-slate-50 min-h-screen relative overflow-hidden">
        
        <div class="absolute top-0 right-0 w-[30rem] h-[30rem] bg-indigo-200 rounded-full mix-blend-multiply filter blur-[120px] opacity-15 pointer-events-none -translate-y-1/2 translate-x-1/3"></div>
        <div class="absolute bottom-0 left-0 w-[30rem] h-[30rem] bg-violet-200 rounded-full mix-blend-multiply filter blur-[120px] opacity-15 pointer-events-none translate-y-1/3 -translate-x-1/3"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6 md:space-y-8 relative z-10">

            @if(session('success'))
                <div id="flash-success" class="bg-gradient-to-r from-emerald-400 to-teal-500 text-white p-5 rounded-2xl md:rounded-[2rem] shadow-xl shadow-emerald-200/50 font-black flex items-center gap-4 animate-fade-in-down border border-emerald-300/50 transition-all duration-500">
                    <span class="text-3xl drop-shadow-md">🎉</span>
                    <span class="text-sm md:text-base tracking-wide">{{ session('success') }}</span>
                </div>
            @endif

            @if(session('error'))
                <div id="flash-error" class="bg-gradient-to-r from-rose-500 to-red-600 text-white p-5 rounded-2xl md:rounded-[2rem] shadow-xl shadow-red-200/50 font-black flex items-center gap-4 animate-fade-in-down border border-red-400/50 transition-all duration-500">
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

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 md:gap-6">
                
                <div class="bg-white p-6 md:p-8 rounded-[2rem] md:rounded-[2.5rem] shadow-sm border border-slate-100 relative overflow-hidden group hover:shadow-xl hover:shadow-blue-100/50 transition-all duration-300 transform hover:-translate-y-1">
                    <div class="absolute -right-4 -bottom-4 w-24 h-24 bg-blue-50 rounded-full blur-2xl group-hover:bg-blue-100 transition-colors"></div>
                    <div class="absolute right-2 -top-2 text-7xl md:text-8xl opacity-[0.03] group-hover:scale-110 group-hover:rotate-12 transition-transform duration-500 pointer-events-none">👦</div>
                    
                    <p class="text-slate-400 font-black uppercase text-[10px] tracking-[0.2em] relative z-10">Total Siswa</p>
                    <p class="text-4xl md:text-5xl font-black text-slate-800 mt-2 relative z-10">{{ $totalSiswa }}</p>
                    <div class="mt-4 flex items-center gap-2 relative z-10">
                        <span class="bg-blue-50 border border-blue-100 text-blue-600 text-[10px] font-black px-3 py-1.5 rounded-xl uppercase tracking-widest shadow-sm flex items-center gap-1.5">
                            <span class="w-1.5 h-1.5 bg-blue-500 rounded-full animate-pulse"></span> Aktif
                        </span>
                    </div>
                </div>

                <div class="bg-white p-6 md:p-8 rounded-[2rem] md:rounded-[2.5rem] shadow-sm border border-slate-100 relative overflow-hidden group hover:shadow-xl hover:shadow-emerald-100/50 transition-all duration-300 transform hover:-translate-y-1">
                    <div class="absolute -right-4 -bottom-4 w-24 h-24 bg-emerald-50 rounded-full blur-2xl group-hover:bg-emerald-100 transition-colors"></div>
                    <div class="absolute right-2 -top-2 text-7xl md:text-8xl opacity-[0.03] group-hover:scale-110 group-hover:rotate-12 transition-transform duration-500 pointer-events-none">👩‍🏫</div>
                    
                    <p class="text-slate-400 font-black uppercase text-[10px] tracking-[0.2em] relative z-10">Tenaga Pengajar</p>
                    <p class="text-4xl md:text-5xl font-black text-slate-800 mt-2 relative z-10">{{ $totalGuru }}</p>
                    <div class="mt-4 flex items-center gap-2 text-emerald-600 font-bold text-[10px] md:text-xs relative z-10 bg-emerald-50 w-fit px-3 py-1.5 rounded-xl border border-emerald-100">
                        Profesional & Berdedikasi
                    </div>
                </div>

                <div class="bg-white p-6 md:p-8 rounded-[2rem] md:rounded-[2.5rem] shadow-sm border border-slate-100 relative overflow-hidden group hover:shadow-xl hover:shadow-orange-100/50 transition-all duration-300 transform hover:-translate-y-1">
                    <div class="absolute -right-4 -bottom-4 w-24 h-24 bg-orange-50 rounded-full blur-2xl group-hover:bg-orange-100 transition-colors"></div>
                    <div class="absolute right-2 -top-2 text-7xl md:text-8xl opacity-[0.03] group-hover:scale-110 group-hover:rotate-12 transition-transform duration-500 pointer-events-none">📚</div>
                    
                    <p class="text-slate-400 font-black uppercase text-[10px] tracking-[0.2em] relative z-10">Mata Pelajaran</p>
                    <p class="text-4xl md:text-5xl font-black text-slate-800 mt-2 relative z-10">{{ $mapels->count() }}</p>
                    <div class="mt-4 flex items-center gap-2 text-orange-600 font-bold text-[10px] md:text-xs relative z-10 bg-orange-50 w-fit px-3 py-1.5 rounded-xl border border-orange-100">
                        Kurikulum MI Aktif
                    </div>
                </div>

                <a href="{{ route('admin.mapel.index') }}" class="block bg-gradient-to-br from-blue-600 to-indigo-700 p-6 md:p-8 rounded-[2rem] md:rounded-[2.5rem] shadow-xl shadow-blue-200/50 relative overflow-hidden group hover:shadow-2xl hover:-translate-y-1 transition-all duration-300">
                    <div class="absolute -right-4 -top-4 text-white/10 group-hover:rotate-12 group-hover:scale-110 transition-transform duration-500">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-32 w-32 md:h-40 md:w-40" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                    </div>
                    <p class="text-blue-200 font-black uppercase text-[10px] tracking-[0.2em] relative z-10">Aksi Cepat</p>
                    <p class="text-xl md:text-2xl font-black text-white mt-2 relative z-10 leading-tight">Tambah<br>Materi Baru</p>
                    <div class="mt-6 md:mt-8 inline-flex items-center gap-2 bg-white text-blue-600 px-5 md:px-6 py-2.5 rounded-xl md:rounded-2xl font-black text-[10px] md:text-xs shadow-lg uppercase tracking-widest relative z-10 group-hover:bg-blue-50 transition-colors">
                        Gas Sekarang <span class="text-sm">🚀</span>
                    </div>
                </a>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 md:gap-8">
                
                <div class="lg:col-span-2 space-y-6 md:space-y-8">
                    <div class="bg-white rounded-[2rem] md:rounded-[2.5rem] shadow-sm border border-slate-100 overflow-hidden relative">
                        
                        <div class="h-1 w-full bg-gradient-to-r from-indigo-500 via-violet-500 to-cyan-500"></div>

                        <div class="p-6 md:p-8 border-b border-slate-50 flex flex-col sm:flex-row justify-between sm:items-center gap-4">
                            <h3 class="font-black text-lg md:text-xl text-slate-800 flex items-center gap-2">
                                <span class="bg-slate-50 p-2 rounded-xl border border-slate-100 shadow-sm">📋</span> 
                                Monitoring Pengampu Pelajaran
                            </h3>
                            <a href="{{ route('admin.mapel.index') }}" class="bg-slate-50 text-slate-600 border border-slate-200 px-5 py-2.5 rounded-xl md:rounded-2xl font-black text-[10px] uppercase tracking-widest hover:bg-indigo-50 hover:text-indigo-600 hover:border-indigo-200 transition-all text-center">Lihat Semua</a>
                        </div>
                        
                        <div class="overflow-x-auto p-4 md:p-5">
                            <table class="w-full text-left whitespace-nowrap md:whitespace-normal">
                                <thead>
                                    <tr class="text-[9px] md:text-[10px] uppercase font-black text-slate-400 tracking-widest bg-slate-50/50 rounded-xl">
                                        <th class="p-4 px-6 rounded-l-xl">Mata Pelajaran</th>
                                        <th class="p-4">Tingkat Kelas</th>
                                        <th class="p-4 rounded-r-xl">Guru Pengampu</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-50 text-sm md:text-base">
                                    @foreach($mapels->take(5) as $mapel)
                                    <tr class="group hover:bg-slate-50/80 transition-colors">
                                        <td class="p-4 md:p-5 px-6">
                                            <div class="flex items-center gap-3 md:gap-4">
                                                <div class="w-3.5 h-3.5 rounded-full shadow-sm shrink-0 border border-white" style="background-color: {{ $mapel->warna_tema ?? '#94a3b8' }}"></div>
                                                <span class="font-black text-slate-700 group-hover:text-indigo-600 transition-colors">{{ $mapel->nama_mapel }}</span>
                                            </div>
                                        </td>
                                        <td class="p-4 md:p-5">
                                            <span class="bg-white border border-slate-200 text-slate-600 px-3 py-1.5 rounded-lg text-[9px] md:text-[10px] font-black uppercase tracking-widest shadow-sm">
                                                {{ $mapel->kelas->nama_kelas }}
                                            </span>
                                        </td>
                                        <td class="p-4 md:p-5">
                                            <div class="flex -space-x-2">
                                                @forelse($mapel->pengampu as $p)
                                                    <div class="h-8 w-8 md:h-10 md:w-10 rounded-[0.8rem] md:rounded-[1rem] bg-gradient-to-br from-indigo-500 to-violet-600 border-2 border-white flex items-center justify-center text-[10px] md:text-xs font-black text-white shadow-md z-10 hover:z-20 hover:scale-110 hover:-translate-y-1 transition-all cursor-help" title="{{ $p->name }}">
                                                        {{ substr($p->name, 0, 2) }}
                                                    </div>
                                                @empty
                                                    <span class="bg-rose-50 border border-rose-100 text-rose-500 px-3 py-1.5 rounded-lg text-[9px] md:text-[10px] font-black uppercase tracking-widest">Kosong</span>
                                                @endforelse
                                            </div>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="space-y-6 md:space-y-8">
                    
                    <div class="bg-white p-6 md:p-8 rounded-[2rem] md:rounded-[2.5rem] shadow-sm border border-slate-100">
                        <h3 class="font-black text-lg md:text-xl text-slate-800 mb-5 text-center md:text-left">Akses Cepat Menu</h3>
                        <div class="grid grid-cols-2 gap-3 md:gap-4">
                            <a href="{{ route('admin.user.index') }}" class="flex flex-col items-center justify-center p-5 md:p-6 bg-slate-50 border border-slate-100 rounded-[1.5rem] md:rounded-[2rem] hover:bg-blue-600 hover:text-white hover:border-blue-600 transition-all duration-300 group shadow-sm hover:shadow-lg">
                                <span class="text-3xl md:text-4xl mb-3 group-hover:scale-110 group-hover:-translate-y-1 transition-transform drop-shadow-sm">👥</span>
                                <span class="text-[9px] md:text-[10px] font-black text-blue-600 group-hover:text-white uppercase tracking-widest">User</span>
                            </a>
                            <a href="{{ route('admin.kelas.index') }}" class="flex flex-col items-center justify-center p-5 md:p-6 bg-slate-50 border border-slate-100 rounded-[1.5rem] md:rounded-[2rem] hover:bg-violet-500 hover:text-white hover:border-violet-500 transition-all duration-300 group shadow-sm hover:shadow-lg">
                                <span class="text-3xl md:text-4xl mb-3 group-hover:scale-110 group-hover:-translate-y-1 transition-transform drop-shadow-sm">🏫</span>
                                <span class="text-[9px] md:text-[10px] font-black text-violet-600 group-hover:text-white uppercase tracking-widest">Kelas</span>
                            </a>
                            <a href="{{ route('admin.mapel.index') }}" class="flex flex-col items-center justify-center p-5 md:p-6 bg-slate-50 border border-slate-100 rounded-[1.5rem] md:rounded-[2rem] hover:bg-orange-500 hover:text-white hover:border-orange-500 transition-all duration-300 group shadow-sm hover:shadow-lg">
                                <span class="text-3xl md:text-4xl mb-3 group-hover:scale-110 group-hover:-translate-y-1 transition-transform drop-shadow-sm">📖</span>
                                <span class="text-[9px] md:text-[10px] font-black text-orange-600 group-hover:text-white uppercase tracking-widest">Mapel</span>
                            </a>
                            <div class="flex flex-col items-center justify-center p-5 md:p-6 bg-slate-50 border border-slate-100 rounded-[1.5rem] md:rounded-[2rem] cursor-not-allowed opacity-50 grayscale shadow-sm">
                                <span class="text-3xl md:text-4xl mb-3">⚙️</span>
                                <span class="text-[9px] md:text-[10px] font-black text-slate-500 uppercase tracking-widest">Setting</span>
                            </div>
                        </div>
                    </div>

                    <div class="bg-gradient-to-br from-slate-900 to-indigo-950 p-6 md:p-8 rounded-[2rem] md:rounded-[2.5rem] shadow-xl text-white relative overflow-hidden border-t-4 border-cyan-500">
                        <div class="absolute -right-6 -bottom-10 text-[8rem] md:text-9xl opacity-[0.04] rotate-12 font-black pointer-events-none select-none text-indigo-400">MI</div>
                        <div class="absolute top-0 right-0 w-32 h-32 bg-indigo-500 rounded-full blur-[50px] opacity-20"></div>

                        <h4 class="font-black text-base md:text-lg mb-2 relative z-10 text-cyan-400 flex items-center gap-2">
                            <span>💡</span> Tips Admin
                        </h4>
                        <p class="text-slate-300 text-xs md:text-sm font-medium leading-relaxed relative z-10">
                            Jangan lupa untuk selalu mengecek relasi guru pengampu setiap awal semester baru agar materi yang diberikan tetap relevan dan akurat.
                        </p>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 md:gap-8 mt-4 md:mt-8">
                
                <a href="{{ route('admin.kelulusan.index') }}" class="group relative overflow-hidden bg-white p-6 md:p-8 rounded-[2rem] md:rounded-[2.5rem] shadow-sm border border-slate-100 hover:border-indigo-200 hover:shadow-2xl hover:shadow-indigo-100/50 transition-all duration-300 hover:-translate-y-1">
                    <div class="absolute -right-6 -top-6 text-8xl md:text-9xl opacity-[0.03] group-hover:rotate-12 group-hover:scale-110 transition-transform duration-500 pointer-events-none">🎓</div>

                    <div class="relative z-10">
                        <div class="w-14 h-14 md:w-16 md:h-16 bg-indigo-50 text-indigo-600 rounded-[1.2rem] md:rounded-2xl flex items-center justify-center text-2xl md:text-3xl mb-5 md:mb-6 shadow-sm border border-indigo-100 group-hover:scale-110 transition-transform">
                            ⚖️
                        </div>
                        <h3 class="font-black text-xl md:text-2xl text-slate-800 group-hover:text-indigo-600 transition-colors">Eksekusi Kelulusan</h3>
                        <p class="text-xs md:text-sm text-slate-500 mt-2 font-medium leading-relaxed">Konfirmasi & naikkan kelas siswa yang telah direkomendasikan secara resmi oleh Wali Kelas / Guru.</p>

                        <div class="mt-6 md:mt-8 inline-flex items-center gap-2 bg-indigo-50 text-indigo-600 border border-indigo-100 px-5 py-2.5 rounded-xl font-black text-[10px] md:text-xs uppercase tracking-widest group-hover:bg-indigo-600 group-hover:text-white transition-all shadow-sm">
                            Buka Gerbang Kelulusan <span class="text-sm">🚀</span>
                        </div>
                    </div>
                </a>

                <a href="{{ route('admin.monitoring.guru') }}" class="group relative overflow-hidden bg-white p-6 md:p-8 rounded-[2rem] md:rounded-[2.5rem] shadow-sm border border-slate-100 hover:border-blue-200 hover:shadow-2xl hover:shadow-blue-100/50 transition-all duration-300 hover:-translate-y-1">
                    <div class="absolute -right-6 -top-6 text-8xl md:text-9xl opacity-[0.03] group-hover:rotate-12 group-hover:scale-110 transition-transform duration-500 pointer-events-none">🕵️</div>
                    
                    <div class="relative z-10">
                        <div class="w-14 h-14 md:w-16 md:h-16 bg-blue-50 text-blue-600 rounded-[1.2rem] md:rounded-2xl flex items-center justify-center text-2xl md:text-3xl mb-5 md:mb-6 shadow-sm border border-blue-100 group-hover:scale-110 transition-transform">
                            🔍
                        </div>
                        <h3 class="font-black text-xl md:text-2xl text-slate-800 group-hover:text-blue-600 transition-colors">Monitoring Guru</h3>
                        <p class="text-xs md:text-sm text-slate-500 mt-2 font-medium leading-relaxed">Pantau secara *real-time* keaktifan tenaga pendidik dalam mengunggah materi, misi, dan soal harian.</p>
                        
                        <div class="mt-6 md:mt-8 inline-flex items-center gap-2 bg-blue-50 text-blue-600 border border-blue-100 px-5 py-2.5 rounded-xl font-black text-[10px] md:text-xs uppercase tracking-widest group-hover:bg-blue-600 group-hover:text-white transition-all shadow-sm">
                            Lihat Laporan Aktivitas <span class="text-sm">🕵️‍♂️</span>
                        </div>
                    </div>
                </a>
            </div>
            
        </div>
    </div>

    <style>
        @keyframes fade-in-down {
            0% { opacity: 0; transform: translateY(-20px); }
            100% { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in-down {
            animation: fade-in-down 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        }
    </style>
</x-app-layout>