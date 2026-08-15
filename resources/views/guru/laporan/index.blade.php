<x-app-layout>
    <style>
        [x-cloak] { display: none !important; }
        /* Scrollbar custom untuk daftar siswa pasif dan history kuis */
        .scrollbar-custom::-webkit-scrollbar { height: 6px; width: 6px; }
        .scrollbar-custom::-webkit-scrollbar-track { background: transparent; }
        .scrollbar-custom::-webkit-scrollbar-thumb { background-color: #cbd5e1; border-radius: 10px; }
        .scrollbar-custom::-webkit-scrollbar-thumb:hover { background-color: #94a3b8; }
    </style>

    <x-slot name="header">
        <div class="flex items-center gap-3 md:gap-4 relative z-10">
            <div class="w-12 h-12 md:w-14 md:h-14 bg-white/10 rounded-[1.25rem] md:rounded-[1.5rem] flex items-center justify-center text-2xl md:text-3xl border border-white/20">
                📊
            </div>
            <div>
                <h2 class="font-black text-2xl md:text-3xl text-white tracking-tight leading-none">Pemantauan Siswa</h2>
                <p class="text-[10px] md:text-xs font-bold text-indigo-300 mt-1 uppercase tracking-widest">Analitik XP & Nilai Real-time</p>
            </div>
        </div>
    </x-slot>

    <div class="py-6 md:py-12 bg-slate-50 min-h-screen relative overflow-hidden" x-data="{ expandedSiswa: null }">
        
        <div class="absolute top-0 right-0 w-[30rem] h-[30rem] bg-emerald-200 rounded-full mix-blend-multiply filter blur-[100px] opacity-20 pointer-events-none -translate-y-1/2 translate-x-1/3"></div>
        <div class="absolute bottom-0 left-0 w-[30rem] h-[30rem] bg-blue-200 rounded-full mix-blend-multiply filter blur-[100px] opacity-20 pointer-events-none translate-y-1/3 -translate-x-1/3"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6 md:space-y-8 relative z-10">
            
            @if($pasif->count() > 0)
                <div class="bg-gradient-to-br from-rose-50 to-red-50 rounded-[2rem] md:rounded-[2.5rem] shadow-lg shadow-rose-100/50 border border-rose-200 overflow-hidden relative p-6 md:p-8 animate-fade-in-down">
                    <div class="absolute right-0 top-0 text-9xl opacity-[0.03] -mt-10 -mr-10 pointer-events-none">⚠️</div>
                    <div class="absolute left-0 top-0 w-2 h-full bg-rose-400"></div>
                    
                    <div class="flex flex-col md:flex-row gap-6 md:items-center relative z-10 pl-2">
                        <div class="shrink-0 md:w-1/3">
                            <h3 class="font-black text-xl md:text-2xl text-rose-600 flex items-center gap-2 drop-shadow-sm">
                                <span class="animate-pulse">⚠️</span> Perhatian Khusus
                            </h3>
                            <p class="text-[10px] md:text-xs font-bold text-rose-500/80 mt-2 leading-relaxed">
                                Terdapat <b class="text-rose-600 text-sm md:text-base bg-rose-100 px-2 py-0.5 rounded-md">{{ $pasif->count() }} siswa</b> yang tidak mengerjakan misi atau absen dalam 3 hari terakhir. Segera lakukan pengecekan!
                            </p>
                        </div>

                        <div class="flex-grow flex gap-4 overflow-x-auto pb-4 scrollbar-custom w-full pt-2">
                            @foreach($pasif as $s)
                                <div class="shrink-0 bg-white border border-rose-100 rounded-[1.5rem] p-4 flex flex-col items-center justify-center text-center w-36 shadow-sm hover:shadow-md hover:border-rose-300 hover:-translate-y-1 transition-all duration-300">
                                    <div class="relative mb-3 group">
                                        @if($s->avatar)
                                            {{-- 🚀 PERBAIKAN: Menggunakan asset() untuk foto siswa pasif --}}
                                            <img src="{{ asset('storage/' . $s->avatar) }}" 
                                                class="w-14 h-14 md:w-16 md:h-16 rounded-[1rem] object-cover border-2 border-rose-100 shadow-inner group-hover:scale-105 transition-transform">
                                        @else
                                            {{-- Tampilan inisial jika tidak ada foto --}}
                                            <div class="w-14 h-14 md:w-16 md:h-16 bg-gradient-to-br from-rose-50 to-red-100 text-rose-500 rounded-[1rem] flex items-center justify-center text-xl md:text-2xl font-black border-2 border-rose-100 shadow-inner group-hover:scale-105 transition-transform">
                                                {{ substr($s->name, 0, 1) }}
                                            </div>
                                        @endif
                                        <div class="absolute -top-2 -right-2 text-xl drop-shadow-md animate-bounce">😴</div>
                                    </div>
                                    <p class="font-black text-slate-800 text-xs md:text-sm truncate w-full" title="{{ $s->name }}">{{ $s->name }}</p>
                                    <span class="bg-rose-100 text-rose-600 px-2.5 py-1 rounded-lg text-[9px] font-black uppercase tracking-widest mt-2 border border-rose-200">
                                        Tidur
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @else
                <div class="bg-gradient-to-br from-emerald-50 to-teal-50 border border-emerald-200 rounded-[2rem] md:rounded-[2.5rem] p-8 md:p-10 flex flex-col items-center text-center shadow-lg shadow-emerald-100/50 relative overflow-hidden animate-fade-in-down">
                    <div class="absolute inset-0 bg-white/40 pointer-events-none"></div>
                    <span class="text-5xl md:text-6xl mb-4 animate-bounce relative z-10 drop-shadow-md">🎉</span>
                    <h3 class="font-black text-emerald-700 text-xl md:text-3xl relative z-10 tracking-tight">Luar Biasa! Semua Siswa Aktif</h3>
                    <p class="text-xs md:text-sm font-bold text-emerald-600/80 mt-2 relative z-10 max-w-md">Tidak ada siswa yang tertinggal pelajaran. Pertahankan semangat belajar yang luar biasa ini!</p>
                </div>
            @endif

            <div class="bg-white rounded-[2rem] md:rounded-[3rem] shadow-xl shadow-slate-200/50 border border-slate-100 overflow-hidden relative">
                
                <div class="p-6 md:p-8 flex flex-col md:flex-row md:items-center justify-between gap-4 bg-slate-800 relative overflow-hidden shadow-md">
                    <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-emerald-400 to-teal-500"></div>
                    <div class="absolute left-0 bottom-0 text-9xl opacity-[0.03] -ml-10 -mb-10 rotate-12 pointer-events-none select-none">📈</div>
                    
                    <div class="relative z-10">
                        <h3 class="font-black text-xl md:text-2xl text-white flex items-center gap-3 tracking-tight">
                            <span class="drop-shadow-md">🏆</span> Papan Peringkat & Analitik
                        </h3>
                        <p class="text-[10px] md:text-xs font-bold text-emerald-400 mt-1.5 uppercase tracking-widest flex items-center gap-1.5">
                            <span class="w-1.5 h-1.5 bg-emerald-400 rounded-full animate-pulse"></span> 
                            Klik nama siswa untuk melihat semua nilainya
                        </p>
                    </div>
                    
                    <div class="relative z-10 shrink-0">
                        <span class="bg-white/10 backdrop-blur-md text-white border border-white/20 px-5 py-2.5 rounded-xl text-[10px] md:text-xs font-black shadow-sm flex items-center gap-2 uppercase tracking-widest">
                            Total: <span class="bg-emerald-500 px-2 py-0.5 rounded-md">{{ $siswas->count() }}</span> Siswa
                        </span>
                    </div>
                </div>

                <div class="p-4 md:p-6 space-y-4 bg-slate-50/50 min-h-[300px]">
                    @forelse($siswas as $index => $s)
                        @php 
                            $isPasif = $pasif->contains('id', $s->id); 
                        @endphp
                        
                        <div class="bg-white border {{ $isPasif ? 'border-rose-200 shadow-rose-100/50' : 'border-slate-200' }} rounded-[1.5rem] md:rounded-[2rem] overflow-hidden transition-all duration-300 shadow-sm relative group"
                             :class="expandedSiswa === {{ $s->id }} ? 'ring-2 ring-emerald-500 border-emerald-500 shadow-lg scale-[1.01]' : 'hover:shadow-md hover:border-emerald-300'">
                            
                            <div class="absolute left-0 top-0 bottom-0 w-1.5 transition-colors duration-300 {{ $isPasif ? 'bg-rose-400' : 'bg-slate-200 group-hover:bg-emerald-400' }}" :class="expandedSiswa === {{ $s->id }} ? 'bg-emerald-500' : ''"></div>

                            <div @click="expandedSiswa = expandedSiswa === {{ $s->id }} ? null : {{ $s->id }}" 
                                 class="p-4 md:p-6 pl-6 md:pl-8 flex items-center justify-between cursor-pointer select-none">
                                
                                <div class="flex items-center gap-3 md:gap-6 w-full overflow-hidden">
                                    <div class="w-8 shrink-0 text-center font-black text-lg md:text-xl {{ $index == 0 ? 'text-amber-500 drop-shadow-sm text-2xl' : ($index == 1 ? 'text-slate-400 text-xl' : ($index == 2 ? 'text-orange-400 text-xl' : 'text-slate-300')) }}">
                                        @if($index == 0) 🥇
                                        @elseif($index == 1) 🥈
                                        @elseif($index == 2) 🥉
                                        @else #{{ $index + 1 }}
                                        @endif
                                    </div>
                                    
                                        <div class="w-12 h-12 md:w-14 md:h-14 shrink-0 relative">
                                            @if($s->avatar)
                                                {{-- 🚀 PERBAIKAN: Menggunakan asset() untuk foto siswa di papan peringkat --}}
                                                <img src="{{ asset('storage/' . $s->avatar) }}" 
                                                    class="w-full h-full rounded-[1rem] object-cover border border-slate-100 shadow-sm"
                                                    alt="Avatar">
                                            @else
                                                <div class="w-full h-full bg-gradient-to-br from-slate-100 to-slate-200 text-slate-500 rounded-[1rem] flex items-center justify-center text-xl font-black border border-slate-200 shadow-sm">
                                                    {{ substr($s->name, 0, 1) }}
                                                </div>
                                            @endif
                                            <span class="absolute -bottom-1 -right-1 w-3.5 h-3.5 border-2 border-white rounded-full shadow-sm {{ $isPasif ? 'bg-rose-500 animate-pulse' : 'bg-emerald-400' }}"></span>
                                        </div>
                                    
                                    <div class="flex-grow truncate pr-4">
                                        <div class="flex flex-col sm:flex-row sm:items-center gap-2 mb-1">
                                            <h4 class="font-black text-slate-800 text-base md:text-lg group-hover:text-emerald-600 transition-colors truncate">{{ $s->name }}</h4>
                                            @if($isPasif)
                                                <span class="bg-rose-50 text-rose-600 border border-rose-200 px-2 py-0.5 rounded-md text-[9px] font-black uppercase tracking-widest shrink-0 w-max shadow-sm">Kurang Aktif</span>
                                            @endif
                                        </div>
                                        <div class="flex items-center gap-2 md:gap-3">
                                            <span class="bg-gradient-to-r from-amber-50 to-yellow-50 text-amber-600 px-2.5 md:px-3 py-1 rounded-lg font-black text-[10px] md:text-xs border border-yellow-200 flex items-center gap-1 shadow-sm">
                                                <span class="text-sm">⚡</span> {{ number_format($s->xp, 0, ',', '.') }} XP
                                            </span>
                                            <span class="text-[9px] md:text-[10px] font-bold text-slate-400 uppercase tracking-widest">
                                                {{ $s->nilais->count() }} Misi Selesai
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <div class="shrink-0 w-8 h-8 md:w-10 md:h-10 bg-slate-50 rounded-xl flex items-center justify-center text-slate-400 group-hover:bg-emerald-50 group-hover:text-emerald-600 transition-all border border-transparent group-hover:border-emerald-100">
                                    <svg :class="expandedSiswa === {{ $s->id }} ? 'rotate-180 text-emerald-600' : ''" class="w-5 h-5 md:w-6 md:h-6 transition-transform duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"></path></svg>
                                </div>
                            </div>

                            <div x-show="expandedSiswa === {{ $s->id }}" x-collapse x-cloak class="border-t border-slate-100 bg-slate-50/80 shadow-inner">
                                <div class="p-4 md:p-8 pl-6 md:pl-10">
                                    
                                    <div class="grid grid-cols-2 md:grid-cols-4 gap-3 md:gap-4 mb-6 md:mb-8">
                                        <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-sm text-center transform hover:-translate-y-1 transition-transform">
                                            <p class="text-[9px] md:text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Status Siswa</p>
                                            <p class="font-black text-xs md:text-sm {{ $isPasif ? 'text-rose-500' : 'text-emerald-500' }}">{{ $isPasif ? 'TIDAK AKTIF' : 'AKTIF BELAJAR' }}</p>
                                        </div>
                                        <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-sm text-center transform hover:-translate-y-1 transition-transform">
                                            <p class="text-[9px] md:text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Rata-rata Nilai</p>
                                            <p class="font-black text-base md:text-lg text-blue-600">
                                                {{ $s->nilais->count() > 0 ? round($s->nilais->avg('skor')) : 0 }}
                                            </p>
                                        </div>
                                        <div class="bg-white p-4 rounded-2xl border border-slate-200 shadow-sm text-center transform hover:-translate-y-1 transition-transform">
                                            <p class="text-[9px] md:text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-1.5">Total Misi</p>
                                            <p class="font-black text-base md:text-lg text-indigo-600">{{ $s->nilais->count() }}</p>
                                        </div>
                                        <div class="bg-white p-3 rounded-2xl border border-slate-200 shadow-sm flex items-center justify-center">
                                            <a href="{{ route('guru.chat.index', ['siswa_id' => $s->id]) }}" class="text-[10px] md:text-xs font-black text-white bg-gradient-to-r from-emerald-500 to-teal-600 hover:from-emerald-600 hover:to-teal-700 px-4 py-3 rounded-xl transition-all shadow-md hover:shadow-lg uppercase tracking-widest w-full text-center flex items-center justify-center gap-2">
                                                <span>💬</span> Hubungi Siswa
                                            </a>
                                        </div>
                                    </div>

                                    <div class="bg-white rounded-2xl border border-slate-200 overflow-hidden shadow-sm">
                                        <div class="p-4 md:p-5 bg-slate-100/50 border-b border-slate-200">
                                            <h5 class="font-black text-xs md:text-sm text-slate-700 flex items-center gap-2">
                                                <span class="bg-white p-1.5 rounded-lg shadow-sm border border-slate-100">📝</span> 
                                                Riwayat Pengerjaan Kuis
                                            </h5>
                                        </div>
                                        
                                        @if($s->nilais->count() > 0)
                                            <div class="overflow-x-auto scrollbar-custom">
                                                <table class="w-full text-left whitespace-nowrap md:whitespace-normal">
                                                    <thead class="text-[9px] md:text-[10px] font-black text-slate-400 uppercase tracking-widest bg-slate-50/50 border-b border-slate-100">
                                                        <tr>
                                                            <th class="p-4 md:p-5 pl-5 md:pl-6">Judul Materi / Misi</th>
                                                            <th class="p-4 md:p-5 text-center">Waktu Submit</th>
                                                            <th class="p-4 md:p-5 text-center pr-5 md:pr-6">Skor Akhir</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody class="divide-y divide-slate-100 text-xs md:text-sm">
                                                        @foreach($s->nilais as $nilai)
                                                            <tr class="hover:bg-slate-50 transition-colors">
                                                                <td class="p-4 md:p-5 pl-5 md:pl-6 font-bold text-slate-700">
                                                                    {{ $nilai->materi->judul ?? 'Materi Dihapus' }}
                                                                </td>
                                                                <td class="p-4 md:p-5 text-center text-[9px] md:text-[10px] font-bold text-slate-400 uppercase tracking-widest">
                                                                    {{ $nilai->created_at->format('d M Y - H:i') }}
                                                                </td>
                                                                <td class="p-4 md:p-5 text-center pr-5 md:pr-6">
                                                                    <span class="px-3 md:px-4 py-1.5 rounded-xl font-black text-[10px] md:text-xs border shadow-sm inline-block min-w-[3rem] 
                                                                        {{ $nilai->skor >= 75 ? 'bg-emerald-50 text-emerald-600 border-emerald-200' : 'bg-rose-50 text-rose-600 border-rose-200' }}">
                                                                        {{ $nilai->skor }}
                                                                    </span>
                                                                </td>
                                                            </tr>
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        @else
                                            <div class="p-10 text-center text-slate-400 flex flex-col items-center">
                                                <div class="w-16 h-16 bg-slate-50 rounded-2xl flex items-center justify-center text-3xl mb-3 border border-slate-100 shadow-inner grayscale opacity-60">📭</div>
                                                <p class="font-bold text-xs md:text-sm">Siswa ini belum pernah mengerjakan misi kuis apapun.</p>
                                            </div>
                                        @endif
                                    </div>

                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-20 text-slate-400 flex flex-col items-center border-2 border-dashed border-slate-200 rounded-[2rem] bg-white">
                            <span class="text-6xl md:text-7xl mb-4 opacity-40 grayscale">📭</span>
                            <h4 class="font-black text-xl md:text-2xl text-slate-700">Belum Ada Siswa</h4>
                            <p class="font-bold text-xs md:text-sm mt-2 max-w-sm">Siswa akan muncul di papan peringkat ini setelah mereka bergabung ke dalam sistem.</p>
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