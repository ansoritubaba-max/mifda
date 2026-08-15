<x-app-layout>
    <style>
        [x-cloak] { display: none !important; }
        /* Scrollbar custom premium untuk daftar kelas */
        .scrollbar-custom::-webkit-scrollbar { width: 8px; height: 8px; }
        .scrollbar-custom::-webkit-scrollbar-track { background: transparent; margin-block: 4px; }
        .scrollbar-custom::-webkit-scrollbar-thumb { background-color: #cbd5e1; border-radius: 10px; border: 2px solid #ffffff; }
        .scrollbar-custom::-webkit-scrollbar-thumb:hover { background-color: #94a3b8; }
    </style>

    <x-slot name="header">
        <div class="flex items-center gap-3 md:gap-4 relative z-10">
            <a href="{{ route('admin.dashboard') }}" class="w-10 h-10 md:w-12 md:h-12 bg-white rounded-xl md:rounded-2xl shadow-sm border border-slate-200 hover:bg-slate-50 hover:shadow-md transition-all transform hover:-translate-x-1 flex items-center justify-center text-slate-500 hover:text-blue-600 shrink-0 group">
                <span class="group-hover:-translate-x-1 transition-transform">⬅️</span>
            </a>
            
            <h2 class="font-black text-xl md:text-3xl text-white tracking-tight flex items-center gap-2 md:gap-3">
                <span class="text-2xl md:text-3xl bg-white/10 p-2 rounded-xl md:rounded-2xl border border-white/20">🏫</span>
                Kelola Kelas
            </h2>
        </div>
    </x-slot>

    <div class="py-6 md:py-12 bg-slate-50 min-h-screen relative overflow-hidden">
        
        <div class="absolute top-0 right-0 w-[30rem] h-[30rem] bg-emerald-200 rounded-full mix-blend-multiply filter blur-[100px] opacity-20 pointer-events-none -translate-y-1/2 translate-x-1/3"></div>
        <div class="absolute bottom-0 left-0 w-[30rem] h-[30rem] bg-blue-200 rounded-full mix-blend-multiply filter blur-[100px] opacity-20 pointer-events-none translate-y-1/3 -translate-x-1/3"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 grid grid-cols-1 lg:grid-cols-12 gap-6 md:gap-8 relative z-10">
            
            <div class="lg:col-span-4 lg:sticky lg:top-24 h-fit">
                <div class="bg-white rounded-[2rem] md:rounded-[2.5rem] shadow-xl shadow-slate-200/50 border border-slate-100 overflow-hidden animate-fade-in-down">
                    
                    <div class="h-3 w-full bg-gradient-to-r from-blue-500 to-indigo-600"></div>

                    <div class="p-6 md:p-8">
                        <div class="flex items-center gap-3 mb-6">
                            <div class="w-12 h-12 md:w-14 md:h-14 bg-gradient-to-br from-blue-50 to-indigo-100 text-blue-600 rounded-[1.25rem] md:rounded-2xl flex items-center justify-center text-2xl md:text-3xl shadow-sm border border-blue-100">➕</div>
                            <div>
                                <h3 class="font-black text-lg md:text-xl text-slate-800 leading-tight">Tambah Kelas</h3>
                                <p class="text-[9px] md:text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1">Ruang Belajar Baru</p>
                            </div>
                        </div>

                        @if(session('success'))
                            <div class="bg-gradient-to-r from-emerald-50 to-teal-50 text-emerald-700 p-4 rounded-2xl mb-6 text-xs font-black uppercase tracking-widest flex items-center gap-3 border border-emerald-200 shadow-sm animate-fade-in-down">
                                <span class="text-xl">✅</span> {{ session('success') }}
                            </div>
                        @endif

                        <form action="{{ route('admin.kelas.store') }}" method="POST" class="space-y-4 md:space-y-5">
                            @csrf
                            
                            <div>
                                <label class="block text-[10px] md:text-xs font-black text-slate-500 uppercase tracking-widest mb-2 px-1">Nama Kelas <span class="text-rose-500">*</span></label>
                                <input type="text" name="nama_kelas" placeholder="Contoh: Kelas 1 A" class="w-full rounded-2xl border border-slate-200 bg-slate-50 focus:bg-white focus:ring-2 focus:ring-blue-500 focus:border-transparent font-bold text-slate-800 px-4 py-3.5 transition-all shadow-sm" required>
                            </div>
                            
                            <button type="submit" class="w-full bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-black py-4 rounded-2xl shadow-lg shadow-blue-200/50 hover:shadow-xl hover:-translate-y-1 active:scale-95 transition-all mt-6 uppercase tracking-widest text-[10px] md:text-xs border border-blue-400/50 flex items-center justify-center gap-2">
                                Simpan Kelas 🚀
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-8">
                <div class="bg-white rounded-[2rem] md:rounded-[2.5rem] shadow-xl shadow-slate-200/40 border border-slate-100 flex flex-col relative overflow-hidden h-full">
                    
                    <div class="p-6 md:p-8 bg-white border-b border-slate-100 shrink-0 flex items-center justify-between">
                        <h3 class="font-black text-xl md:text-2xl text-slate-800 flex items-center gap-3">
                            <span class="p-2 bg-slate-50 border border-slate-200 rounded-xl shadow-sm">📋</span> 
                            Daftar Kelas Terdaftar
                        </h3>
                        <span class="bg-blue-50 text-blue-600 px-3 py-1.5 rounded-lg text-[10px] md:text-xs font-black uppercase tracking-widest border border-blue-100 shadow-sm hidden sm:inline-block">
                            Total: {{ $kelas->count() }}
                        </span>
                    </div>

                    <div class="overflow-x-auto overflow-y-auto flex-grow scrollbar-custom max-h-[600px] scroll-smooth p-2 md:p-4">
                        <table class="w-full text-left border-collapse">
                            <thead class="bg-slate-50/80 text-[9px] md:text-[10px] font-black uppercase text-slate-400 tracking-widest sticky top-0 z-10 backdrop-blur-sm">
                                <tr>
                                    <th class="p-4 md:p-5 pl-5 md:pl-6 rounded-l-xl">Nama Kelas</th>
                                    <th class="p-4 md:p-5 text-center">Jumlah Pelajaran</th>
                                    <th class="p-4 md:p-5 text-right pr-5 md:pr-6 rounded-r-xl">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100 text-sm">
                                @forelse($kelas as $k)
                                <tr class="hover:bg-slate-50/80 transition-colors group">
                                    
                                    <td class="p-4 md:p-5 pl-5 md:pl-6">
                                        <div class="flex items-center gap-3">
                                            <div class="w-10 h-10 rounded-[0.8rem] bg-blue-50 text-blue-600 flex items-center justify-center font-black text-lg border border-blue-100 shadow-sm group-hover:bg-blue-500 group-hover:text-white transition-colors shrink-0">
                                                {{ substr($k->nama_kelas, 0, 1) }}
                                            </div>
                                            <span class="font-black text-lg md:text-xl text-slate-800 group-hover:text-blue-600 transition-colors">{{ $k->nama_kelas }}</span>
                                        </div>
                                    </td>
                                    
                                    <td class="p-4 md:p-5 text-center">
                                        <span class="bg-gradient-to-r from-blue-50 to-indigo-50 text-blue-700 px-3 md:px-4 py-1.5 rounded-xl text-[9px] md:text-[10px] font-black uppercase tracking-widest shadow-sm border border-blue-100/50 inline-block">
                                            {{ $k->mapels_count }} Mapel
                                        </span>
                                    </td>
                                    
                                    <td class="p-4 md:p-5 text-right pr-5 md:pr-6">
                                        <form action="{{ route('admin.kelas.destroy', $k->id) }}" method="POST" class="inline-block">
                                            @csrf @method('DELETE')
                                            <button onclick="return confirm('⚠️ Yakin ingin menghapus kelas ini? Tindakan ini dapat memengaruhi relasi siswa dan mapel di dalamnya.')" type="submit" class="bg-white border border-rose-200 text-rose-500 hover:bg-rose-500 hover:text-white px-3 md:px-4 py-2.5 rounded-xl text-[10px] md:text-xs font-black uppercase tracking-widest transition-all shadow-sm flex items-center justify-center gap-1.5 ml-auto">
                                                <span class="hidden md:inline">Hapus</span> <span>🗑️</span>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3" class="p-16 md:p-20 text-center">
                                        <div class="flex flex-col items-center justify-center text-slate-400">
                                            <span class="text-5xl md:text-6xl mb-4 opacity-40 grayscale">🏫</span>
                                            <p class="font-black text-lg md:text-xl text-slate-600 mb-1">Belum Ada Kelas</p>
                                            <p class="font-bold text-xs md:text-sm">Silakan buat ruang kelas baru melalui form di sebelah kiri.</p>
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
    </div>

    <style>
        @keyframes fade-in-down {
            0% { opacity: 0; transform: translateY(-10px); }
            100% { opacity: 1; transform: translateY(0); }
        }
        .animate-fade-in-down { animation: fade-in-down 0.5s ease-out forwards; }
    </style>
</x-app-layout>