<x-app-layout>
    <style>
        /* Scrollbar custom premium untuk daftar siswa */
        .scrollbar-custom::-webkit-scrollbar { width: 8px; }
        .scrollbar-custom::-webkit-scrollbar-track { background: #f8fafc; border-radius: 10px; margin-block: 4px; }
        .scrollbar-custom::-webkit-scrollbar-thumb { background-color: #cbd5e1; border-radius: 10px; border: 2px solid #f8fafc; }
        .scrollbar-custom::-webkit-scrollbar-thumb:hover { background-color: #94a3b8; }
    </style>

    <x-slot name="header">
        <div class="flex items-center gap-3 md:gap-4 relative z-10">
            <a href="{{ route('admin.dashboard') }}" class="w-10 h-10 md:w-12 md:h-12 bg-white rounded-xl md:rounded-2xl shadow-sm border border-slate-200 hover:bg-slate-50 hover:shadow-md transition-all transform hover:-translate-x-1 flex items-center justify-center text-slate-500 hover:text-emerald-600 shrink-0 group">
                <span class="group-hover:-translate-x-1 transition-transform">⬅️</span>
            </a>
            
            <h2 class="font-black text-xl md:text-3xl text-white tracking-tight flex items-center gap-2 md:gap-3">
                <span class="text-2xl md:text-3xl bg-white/10 p-2 rounded-xl md:rounded-2xl border border-white/20">🎓</span>
                Konfirmasi Kelulusan
            </h2>
        </div>
    </x-slot>

    <div class="py-6 md:py-12 bg-slate-50 min-h-screen relative overflow-hidden">
        
        <div class="absolute top-0 right-0 w-[30rem] h-[30rem] bg-emerald-200 rounded-full mix-blend-multiply filter blur-[100px] opacity-20 pointer-events-none -translate-y-1/2 translate-x-1/3"></div>
        <div class="absolute bottom-0 left-0 w-[30rem] h-[30rem] bg-blue-200 rounded-full mix-blend-multiply filter blur-[100px] opacity-20 pointer-events-none translate-y-1/3 -translate-x-1/3"></div>

        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            
            <div class="bg-white rounded-[2rem] md:rounded-[3rem] shadow-2xl shadow-slate-200/50 border border-slate-100 overflow-hidden relative animate-fade-in-down">
                
                <div class="p-8 md:p-12 bg-slate-800 text-center relative overflow-hidden border-b-[6px] border-emerald-500">
                    <div class="absolute top-0 right-0 p-8 opacity-[0.03] text-[10rem] translate-x-10 -translate-y-10 pointer-events-none select-none">🎓</div>
                    <div class="absolute -left-10 -bottom-10 w-40 h-40 bg-emerald-500 rounded-full blur-3xl opacity-20 pointer-events-none"></div>

                    <div class="relative z-10">
                        <div class="w-16 h-16 md:w-20 md:h-20 bg-white/10 backdrop-blur-md border border-white/20 rounded-[1.5rem] md:rounded-3xl flex items-center justify-center text-3xl md:text-4xl mx-auto mb-5 shadow-xl group-hover:scale-110 transition-transform">
                            ⚖️
                        </div>
                        <h3 class="font-black text-2xl md:text-3xl text-white mb-3 tracking-tight">Persetujuan Akhir Akademik</h3>
                        <p class="text-slate-300 text-sm md:text-base font-medium max-w-lg mx-auto leading-relaxed">
                            Terdapat <span class="px-2.5 py-1 bg-gradient-to-r from-amber-400 to-yellow-500 text-amber-900 rounded-lg font-black shadow-sm mx-1">{{ $siswas->count() }} siswa</span> yang telah direkomendasikan secara resmi oleh Guru untuk naik kelas atau lulus.
                        </p>
                    </div>
                </div>

                <div class="p-6 md:p-8 lg:p-10 bg-slate-50/50">
                    
                    <div class="flex items-center justify-between mb-5 px-2 md:px-4">
                        <h4 class="text-[10px] md:text-xs font-black text-slate-400 uppercase tracking-[0.2em] flex items-center gap-2">
                            <span>📋</span> Daftar Siswa Siap Lulus
                        </h4>
                        <span class="bg-emerald-50 text-emerald-600 border border-emerald-100 px-3 py-1 rounded-lg text-[9px] md:text-[10px] font-black uppercase tracking-widest shadow-sm">
                            Status Valid ✅
                        </span>
                    </div>
                    
                    <div class="space-y-3 mb-8 md:mb-10 max-h-[400px] overflow-y-auto px-2 md:px-4 scrollbar-custom scroll-smooth">
                        @forelse($siswas as $s)
                            <div class="group flex flex-col sm:flex-row sm:items-center justify-between p-4 md:p-5 bg-white rounded-[1.5rem] md:rounded-[2rem] border border-slate-200 hover:border-emerald-300 hover:shadow-lg hover:shadow-emerald-100/50 transition-all duration-300 gap-4 relative overflow-hidden">
                                
                                <div class="absolute left-0 top-0 bottom-0 w-1.5 bg-slate-100 group-hover:bg-emerald-400 transition-colors"></div>

                                <div class="flex items-center gap-4 pl-2">
                                    <div class="w-12 h-12 md:w-14 md:h-14 shrink-0 group-hover:scale-105 transition-transform">
                                        @if($s->avatar)
                                            {{-- 🚀 PERBAIKAN: Menggunakan asset() agar pemanggilan gambar dari storage lokal tepat --}}
                                            <img src="{{ asset('storage/' . $s->avatar) }}" 
                                                alt="Avatar" 
                                                class="w-full h-full rounded-[1rem] md:rounded-2xl object-cover shadow-sm border border-slate-100">
                                        @else
                                            {{-- Tampilan inisial jika tidak ada foto --}}
                                            <div class="w-full h-full bg-gradient-to-br from-slate-50 to-slate-100 border border-slate-200 rounded-[1rem] md:rounded-2xl flex items-center justify-center font-black text-slate-400 text-lg md:text-xl shadow-sm group-hover:bg-emerald-500 group-hover:text-white group-hover:border-emerald-600 transition-colors">
                                                {{ substr($s->name, 0, 1) }}
                                            </div>
                                        @endif
                                    </div>
                                    
                                    <div>
                                        <p class="font-black text-slate-800 text-base md:text-lg leading-tight group-hover:text-emerald-600 transition-colors">{{ $s->name }}</p>
                                        <p class="text-[9px] md:text-[10px] font-bold text-slate-400 uppercase mt-1.5 tracking-widest flex items-center gap-1">
                                            <span class="text-blue-500">📍</span> {{ $s->kelas->nama_kelas ?? 'Tanpa Kelas' }}
                                        </p>
                                    </div>
                                </div>
                                
                                <div class="px-4 py-2 md:py-2.5 bg-emerald-50 border border-emerald-100 text-emerald-600 rounded-xl text-[10px] md:text-xs font-black uppercase tracking-widest shadow-sm shrink-0 text-center sm:text-right">
                                    Siap Lulus ✅
                                </div>
                            </div>
                        @empty
                            <div class="py-16 md:py-20 text-center flex flex-col items-center bg-white rounded-[2rem] border-2 border-dashed border-slate-200 shadow-sm">
                                <span class="text-6xl md:text-7xl block mb-4 opacity-40 grayscale">📭</span>
                                <h4 class="text-xl md:text-2xl font-black text-slate-700 mb-1">Belum Ada Rekomendasi</h4>
                                <p class="text-slate-400 font-bold text-xs md:text-sm max-w-sm">Saat ini belum ada siswa yang direkomendasikan oleh Guru untuk diluluskan.</p>
                                <a href="{{ route('admin.dashboard') }}" class="text-emerald-600 hover:text-emerald-700 bg-emerald-50 hover:bg-emerald-100 px-6 py-2.5 rounded-xl font-black text-[10px] md:text-xs uppercase tracking-widest mt-6 inline-block transition-colors border border-emerald-200">
                                    ⬅️ Kembali ke Dashboard
                                </a>
                            </div>
                        @endforelse
                    </div>

                    @if($siswas->count() > 0)
                        <div class="bg-white p-6 md:p-8 rounded-[2rem] md:rounded-[2.5rem] border border-slate-200 shadow-sm text-center relative overflow-hidden group">
                            <div class="absolute inset-0 bg-gradient-to-br from-emerald-50 to-teal-50 opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none"></div>

                            <form action="{{ route('admin.kelulusan.proses') }}" method="POST" class="relative z-10">
                                @csrf
                                <div class="mb-6 max-w-xl mx-auto">
                                    <span class="inline-block p-2 bg-rose-50 text-rose-500 rounded-full mb-3 text-xl border border-rose-100 shadow-sm">⚠️</span>
                                    <p class="text-[10px] md:text-xs text-slate-500 font-bold uppercase tracking-widest leading-relaxed">
                                        Dengan menekan tombol di bawah, semua siswa di atas akan diproses dan otomatis naik ke kelas berikutnya secara permanen.
                                    </p>
                                </div>
                                
                                <button type="submit" 
                                    onclick="return confirm('⚠️ PERHATIAN: Anda akan meluluskan {{ $siswas->count() }} siswa secara massal. Tindakan ini tidak dapat dibatalkan. Apakah Anda yakin ingin melanjutkan?')" 
                                    class="w-full md:w-auto bg-gradient-to-r from-emerald-500 to-teal-600 text-white font-black px-8 md:px-12 py-4 md:py-5 rounded-xl md:rounded-2xl shadow-lg shadow-emerald-200/50 hover:shadow-xl hover:scale-105 active:scale-95 transition-all duration-300 border border-emerald-400/50 text-[10px] md:text-xs uppercase tracking-widest flex items-center justify-center gap-2 mx-auto">
                                    <span>Konfirmasi Luluskan Semua</span>
                                    <span class="text-base md:text-lg">🚀</span>
                                </button>
                            </form>
                        </div>
                    @endif
                </div>

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