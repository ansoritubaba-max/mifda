<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 relative z-10">
            <h2 class="font-black text-2xl md:text-3xl text-white tracking-tight flex items-center gap-3">
                <span class="text-3xl md:text-4xl bg-white/10 p-2 rounded-2xl border border-white/20">📋</span>
                Ujian Semester
            </h2>
            <div class="bg-white px-4 py-2 rounded-xl shadow-sm text-xs font-black uppercase tracking-widest flex items-center gap-2 self-start border border-slate-100 text-slate-500">
                <span class="w-2 h-2 rounded-full bg-blue-400 animate-pulse"></span>
                Kelas {{ $user->kelas->nama_kelas ?? '-' }}
            </div>
        </div>
    </x-slot>

    <div class="py-6 md:py-12 bg-slate-50 min-h-screen relative overflow-hidden">

        <div class="absolute top-0 right-0 w-96 h-96 bg-blue-200 rounded-full mix-blend-multiply filter blur-[100px] opacity-30 pointer-events-none -translate-y-1/2 translate-x-1/3"></div>
        <div class="absolute bottom-1/4 left-0 w-96 h-96 bg-purple-200 rounded-full mix-blend-multiply filter blur-[100px] opacity-20 pointer-events-none translate-y-1/2 -translate-x-1/3"></div>

        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 space-y-8">

            @if(session('success'))
                <div id="flash-success" class="bg-gradient-to-r from-emerald-400 to-teal-500 text-white p-5 rounded-2xl shadow-xl font-black flex items-center gap-4 transition-all duration-500">
                    <span class="text-3xl">🎉</span> <span>{{ session('success') }}</span>
                </div>
            @endif
            @if(session('error'))
                <div id="flash-error" class="bg-gradient-to-r from-rose-500 to-red-600 text-white p-5 rounded-2xl shadow-xl font-black flex items-center gap-4 transition-all duration-500">
                    <span class="text-3xl">⚠️</span> <span>{{ session('error') }}</span>
                </div>
            @endif
            <script>
                ['flash-success','flash-error'].forEach(id => {
                    const el = document.getElementById(id);
                    if (el) setTimeout(() => { el.style.opacity='0'; el.style.transform='translateY(-8px)'; setTimeout(()=>el.remove(),500); }, 4000);
                });
            </script>

            {{-- UJIAN GANJIL --}}
            @php
                $ganjil = $ujians->where('jenis', 'ujian_ganjil');
                $genap  = $ujians->where('jenis', 'ujian_genap');
                $sekarang = now();
            @endphp

            {{-- GANJIL --}}
            <div class="bg-white rounded-[2rem] shadow-xl shadow-slate-200/40 border border-slate-100 overflow-hidden">
                <div class="h-2 bg-gradient-to-r from-blue-400 to-indigo-500"></div>
                <div class="p-6 border-b border-slate-50">
                    <h3 class="font-black text-xl text-slate-800 flex items-center gap-3">
                        <span class="w-10 h-10 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center border border-blue-100 text-lg">📋</span>
                        Ujian Semester Ganjil
                    </h3>
                </div>
                <div class="divide-y divide-slate-50">
                    @forelse($ganjil as $u)
                        @php
                            $sudah = in_array($u->id, $sudahDikerjakan);
                            $belumMulai  = $u->jadwal_mulai && $sekarang->lt($u->jadwal_mulai);
                            $sudahSelesai= $u->jadwal_selesai && $sekarang->gt($u->jadwal_selesai);
                            $bisaDikerjakan = !$sudah && !$belumMulai && !$sudahSelesai && $u->soals->count() > 0;
                        @endphp
                        <div class="p-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4 group hover:bg-slate-50/50 transition">
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-2 flex-wrap mb-1">
                                    <h4 class="font-black text-slate-800 text-base group-hover:text-blue-600 transition">{{ $u->judul }}</h4>
                                    @if($sudah)
                                        <span class="text-[10px] font-black bg-emerald-50 text-emerald-600 border border-emerald-200 px-2 py-0.5 rounded-lg">✅ Selesai</span>
                                    @elseif($belumMulai)
                                        <span class="text-[10px] font-black bg-amber-50 text-amber-600 border border-amber-200 px-2 py-0.5 rounded-lg">⏳ Belum Dimulai</span>
                                    @elseif($sudahSelesai)
                                        <span class="text-[10px] font-black bg-slate-100 text-slate-500 border border-slate-200 px-2 py-0.5 rounded-lg">🔒 Waktu Habis</span>
                                    @elseif($u->soals->count() == 0)
                                        <span class="text-[10px] font-black bg-rose-50 text-rose-500 border border-rose-200 px-2 py-0.5 rounded-lg">⚠️ Belum ada soal</span>
                                    @else
                                        <span class="text-[10px] font-black bg-blue-50 text-blue-600 border border-blue-200 px-2 py-0.5 rounded-lg">🟢 Tersedia</span>
                                    @endif
                                </div>
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">{{ $u->mapel->nama_mapel ?? '-' }} &nbsp;·&nbsp; {{ $u->soals->count() }} soal &nbsp;·&nbsp; ⚡ {{ $u->xp_reward }} XP</p>

                                @if($u->jadwal_mulai)
                                    <div class="mt-2 flex flex-wrap gap-2 text-[10px] font-black">
                                        <span class="bg-emerald-50 text-emerald-700 border border-emerald-200 px-2.5 py-1 rounded-lg">
                                            ▶ Mulai: {{ \Carbon\Carbon::parse($u->jadwal_mulai)->translatedFormat('d M Y, H:i') }}
                                        </span>
                                        @if($u->jadwal_selesai)
                                        <span class="bg-rose-50 text-rose-600 border border-rose-200 px-2.5 py-1 rounded-lg">
                                            ■ Selesai: {{ \Carbon\Carbon::parse($u->jadwal_selesai)->translatedFormat('d M Y, H:i') }}
                                        </span>
                                        @endif
                                    </div>
                                @endif
                            </div>

                            <div class="shrink-0">
                                @if($sudah)
                                    <span class="block px-6 py-3 bg-slate-50 text-slate-400 border border-slate-200 rounded-xl font-black text-xs uppercase tracking-widest text-center">Sudah Dikerjakan ✓</span>
                                @elseif($bisaDikerjakan)
                                    <a href="{{ route('siswa.kuis', $u->id) }}"
                                       class="block px-6 py-3.5 bg-gradient-to-r from-blue-500 to-indigo-600 text-white rounded-xl font-black text-sm shadow-lg shadow-blue-200/50 hover:shadow-xl hover:-translate-y-0.5 transition-all text-center uppercase tracking-widest">
                                        Kerjakan Sekarang 🚀
                                    </a>
                                @elseif($belumMulai)
                                    <span class="block px-6 py-3 bg-amber-50 text-amber-600 border border-amber-200 rounded-xl font-black text-xs uppercase tracking-widest text-center">⏳ Belum waktunya</span>
                                @else
                                    <span class="block px-6 py-3 bg-slate-100 text-slate-400 border border-slate-200 rounded-xl font-black text-xs uppercase tracking-widest text-center">Tidak Tersedia</span>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="p-12 text-center text-slate-400">
                            <div class="text-5xl mb-3 opacity-40">📭</div>
                            <p class="text-sm font-black">Belum ada ujian semester ganjil</p>
                            <p class="text-xs font-bold mt-1 text-slate-400">Guru belum membuat ujian untuk kelasmu</p>
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- GENAP --}}
            <div class="bg-white rounded-[2rem] shadow-xl shadow-slate-200/40 border border-slate-100 overflow-hidden">
                <div class="h-2 bg-gradient-to-r from-purple-400 to-pink-500"></div>
                <div class="p-6 border-b border-slate-50">
                    <h3 class="font-black text-xl text-slate-800 flex items-center gap-3">
                        <span class="w-10 h-10 bg-purple-50 text-purple-600 rounded-xl flex items-center justify-center border border-purple-100 text-lg">📋</span>
                        Ujian Semester Genap
                    </h3>
                </div>
                <div class="divide-y divide-slate-50">
                    @forelse($genap as $u)
                        @php
                            $sudah = in_array($u->id, $sudahDikerjakan);
                            $belumMulai  = $u->jadwal_mulai && $sekarang->lt($u->jadwal_mulai);
                            $sudahSelesai= $u->jadwal_selesai && $sekarang->gt($u->jadwal_selesai);
                            $bisaDikerjakan = !$sudah && !$belumMulai && !$sudahSelesai && $u->soals->count() > 0;
                        @endphp
                        <div class="p-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4 group hover:bg-slate-50/50 transition">
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-2 flex-wrap mb-1">
                                    <h4 class="font-black text-slate-800 text-base group-hover:text-purple-600 transition">{{ $u->judul }}</h4>
                                    @if($sudah)
                                        <span class="text-[10px] font-black bg-emerald-50 text-emerald-600 border border-emerald-200 px-2 py-0.5 rounded-lg">✅ Selesai</span>
                                    @elseif($belumMulai)
                                        <span class="text-[10px] font-black bg-amber-50 text-amber-600 border border-amber-200 px-2 py-0.5 rounded-lg">⏳ Belum Dimulai</span>
                                    @elseif($sudahSelesai)
                                        <span class="text-[10px] font-black bg-slate-100 text-slate-500 border border-slate-200 px-2 py-0.5 rounded-lg">🔒 Waktu Habis</span>
                                    @elseif($u->soals->count() == 0)
                                        <span class="text-[10px] font-black bg-rose-50 text-rose-500 border border-rose-200 px-2 py-0.5 rounded-lg">⚠️ Belum ada soal</span>
                                    @else
                                        <span class="text-[10px] font-black bg-purple-50 text-purple-600 border border-purple-200 px-2 py-0.5 rounded-lg">🟢 Tersedia</span>
                                    @endif
                                </div>
                                <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">{{ $u->mapel->nama_mapel ?? '-' }} &nbsp;·&nbsp; {{ $u->soals->count() }} soal &nbsp;·&nbsp; ⚡ {{ $u->xp_reward }} XP</p>

                                @if($u->jadwal_mulai)
                                    <div class="mt-2 flex flex-wrap gap-2 text-[10px] font-black">
                                        <span class="bg-emerald-50 text-emerald-700 border border-emerald-200 px-2.5 py-1 rounded-lg">
                                            ▶ Mulai: {{ \Carbon\Carbon::parse($u->jadwal_mulai)->translatedFormat('d M Y, H:i') }}
                                        </span>
                                        @if($u->jadwal_selesai)
                                        <span class="bg-rose-50 text-rose-600 border border-rose-200 px-2.5 py-1 rounded-lg">
                                            ■ Selesai: {{ \Carbon\Carbon::parse($u->jadwal_selesai)->translatedFormat('d M Y, H:i') }}
                                        </span>
                                        @endif
                                    </div>
                                @endif
                            </div>

                            <div class="shrink-0">
                                @if($sudah)
                                    <span class="block px-6 py-3 bg-slate-50 text-slate-400 border border-slate-200 rounded-xl font-black text-xs uppercase tracking-widest text-center">Sudah Dikerjakan ✓</span>
                                @elseif($bisaDikerjakan)
                                    <a href="{{ route('siswa.kuis', $u->id) }}"
                                       class="block px-6 py-3.5 bg-gradient-to-r from-purple-500 to-pink-600 text-white rounded-xl font-black text-sm shadow-lg shadow-purple-200/50 hover:shadow-xl hover:-translate-y-0.5 transition-all text-center uppercase tracking-widest">
                                        Kerjakan Sekarang 🚀
                                    </a>
                                @elseif($belumMulai)
                                    <span class="block px-6 py-3 bg-amber-50 text-amber-600 border border-amber-200 rounded-xl font-black text-xs uppercase tracking-widest text-center">⏳ Belum waktunya</span>
                                @else
                                    <span class="block px-6 py-3 bg-slate-100 text-slate-400 border border-slate-200 rounded-xl font-black text-xs uppercase tracking-widest text-center">Tidak Tersedia</span>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="p-12 text-center text-slate-400">
                            <div class="text-5xl mb-3 opacity-40">📭</div>
                            <p class="text-sm font-black">Belum ada ujian semester genap</p>
                            <p class="text-xs font-bold mt-1 text-slate-400">Guru belum membuat ujian untuk kelasmu</p>
                        </div>
                    @endforelse
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
