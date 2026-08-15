<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3 md:gap-4 relative z-10">
            <a href="{{ route('guru.dashboard') }}" class="w-10 h-10 md:w-12 md:h-12 bg-white rounded-xl md:rounded-2xl shadow-sm border border-slate-200 hover:bg-slate-50 hover:shadow-md transition-all transform hover:-translate-x-1 flex items-center justify-center text-slate-500 hover:text-emerald-600 shrink-0 group">
                <span class="group-hover:-translate-x-1 transition-transform">⬅️</span>
            </a>
            
            <h2 class="font-black text-xl md:text-3xl text-white tracking-tight flex items-center gap-2 md:gap-3">
                <span class="text-2xl md:text-3xl bg-white/10 p-2 rounded-xl md:rounded-2xl border border-white/20">🎓</span>
                Rekomendasi Kelulusan
            </h2>
        </div>
    </x-slot>

    <div class="py-6 md:py-12 bg-slate-50 min-h-screen relative overflow-hidden">
        
        <div class="absolute top-0 right-0 w-[30rem] h-[30rem] bg-emerald-200 rounded-full mix-blend-multiply filter blur-[100px] opacity-20 pointer-events-none -translate-y-1/2 translate-x-1/3"></div>
        <div class="absolute bottom-0 left-0 w-[30rem] h-[30rem] bg-blue-200 rounded-full mix-blend-multiply filter blur-[100px] opacity-20 pointer-events-none translate-y-1/3 -translate-x-1/3"></div>

        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            
            <div class="bg-white rounded-[2rem] md:rounded-[3rem] shadow-xl shadow-slate-200/50 border border-slate-100 overflow-hidden relative">
                
                <div class="p-8 md:p-12 bg-slate-800 text-white relative overflow-hidden border-b-[6px] border-emerald-500">
                    <div class="absolute right-0 top-0 text-[10rem] opacity-[0.03] translate-x-10 -translate-y-10 pointer-events-none select-none">🎓</div>
                    <div class="absolute -left-10 -bottom-10 w-40 h-40 bg-emerald-500 rounded-full blur-3xl opacity-20 pointer-events-none"></div>

                    <h3 class="font-black text-2xl md:text-3xl relative z-10 tracking-tight flex items-center gap-3">
                        Daftar Siswa Kelas Anda
                    </h3>
                    <p class="text-slate-300 text-xs md:text-sm mt-3 font-bold relative z-10 max-w-xl leading-relaxed">
                        Silakan berikan tanda (<span class="text-emerald-400">✅</span>) bagi siswa yang telah memenuhi kriteria akademik dan layak direkomendasikan untuk naik kelas atau lulus.
                    </p>
                </div>
                
                <div class="p-5 md:p-8 bg-slate-50/50 min-h-[300px]">
                    <div class="space-y-4 md:space-y-5">
                        
                        @forelse($siswas as $s)
                        <div class="flex flex-col sm:flex-row justify-between items-center p-5 md:p-6 bg-white rounded-[1.5rem] md:rounded-[2rem] border border-slate-200 hover:border-emerald-300 hover:shadow-lg hover:shadow-emerald-100/50 transition-all duration-300 gap-4 md:gap-6 group relative overflow-hidden">
                            
                            <div class="absolute left-0 top-0 bottom-0 w-1.5 transition-colors duration-300 {{ $s->siap_lulus ? 'bg-emerald-500' : 'bg-slate-200 group-hover:bg-emerald-300' }}"></div>

                                <div class="flex items-center gap-4 md:gap-5 w-full sm:w-auto pl-2 md:pl-4">
                                    
                                    <div class="w-14 h-14 md:w-16 md:h-16 shrink-0 group-hover:scale-105 transition-transform relative">
                                        @if($s->avatar)
                                            {{-- 🚀 PERBAIKAN: Menggunakan asset() untuk memanggil foto profil siswa --}}
                                            <img src="{{ asset('storage/' . $s->avatar) }}" 
                                                alt="Avatar" 
                                                class="w-full h-full rounded-[1rem] object-cover shadow-sm border border-slate-100">
                                        @else
                                            {{-- Tampilan inisial jika tidak ada foto --}}
                                            <div class="w-full h-full bg-gradient-to-br from-slate-50 to-slate-100 rounded-[1rem] flex items-center justify-center font-black text-slate-400 shadow-sm text-xl md:text-2xl border border-slate-200 group-hover:text-emerald-500 group-hover:border-emerald-200 transition-colors">
                                                {{ substr($s->name, 0, 1) }}
                                            </div>
                                        @endif
                                        
                                        {{-- Badge Siap Lulus --}}
                                        @if($s->siap_lulus)
                                            <div class="absolute -bottom-1 -right-1 bg-white rounded-full p-0.5 shadow-sm">
                                                <div class="bg-emerald-500 text-white w-4 h-4 md:w-5 md:h-5 rounded-full flex items-center justify-center text-[8px] md:text-[10px] border border-emerald-100">✓</div>
                                            </div>
                                        @endif
                                    </div>
                                    
                                    <div>
                                        <h4 class="font-black text-slate-800 text-lg md:text-xl leading-tight group-hover:text-emerald-600 transition-colors mb-1.5">{{ $s->name }}</h4>
                                        <span class="inline-flex items-center gap-1.5 px-3 md:px-3.5 py-1 bg-gradient-to-r from-amber-50 to-yellow-50 text-amber-600 border border-yellow-200 rounded-lg text-[10px] md:text-xs font-black uppercase shadow-sm">
                                            <span class="text-sm">⚡</span> Level {{ $s->level ?? 1 }}
                                        </span>
                                    </div>
                                </div>

                            <div class="w-full sm:w-auto text-right sm:pr-2">
                                <form action="{{ route('guru.kelulusan.tandai', $s->id) }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="status" value="{{ $s->siap_lulus ? 0 : 1 }}">
                                    
                                    <button type="submit" class="w-full sm:w-auto px-6 md:px-8 py-3.5 md:py-4 rounded-xl md:rounded-2xl font-black text-[10px] md:text-xs uppercase tracking-widest transition-all duration-300 flex items-center justify-center gap-2 {{ $s->siap_lulus ? 'bg-gradient-to-r from-emerald-500 to-teal-600 text-white shadow-lg shadow-emerald-200/50 hover:shadow-xl hover:scale-105 border border-emerald-400/50' : 'bg-white border-2 border-slate-200 text-slate-400 hover:bg-emerald-50 hover:text-emerald-600 hover:border-emerald-300 shadow-sm hover:-translate-y-1' }}">
                                        @if($s->siap_lulus)
                                            <span class="bg-white/20 rounded-full w-4 h-4 md:w-5 md:h-5 flex items-center justify-center text-[8px] md:text-[10px]">✅</span> DIREKOMENDASIKAN
                                        @else
                                            Tandai Layak Lulus
                                        @endif
                                    </button>
                                </form>
                            </div>

                        </div>
                        @empty
                        <div class="py-16 md:py-24 text-center flex flex-col items-center">
                            <span class="text-6xl md:text-7xl block mb-5 opacity-40 grayscale">📭</span>
                            <h4 class="text-xl md:text-2xl font-black text-slate-700 mb-2">Belum Ada Siswa</h4>
                            <p class="text-slate-500 font-bold text-xs md:text-sm max-w-sm leading-relaxed">Belum ada siswa yang tergabung di dalam kelas yang Anda ampu.</p>
                        </div>
                        @endforelse

                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>