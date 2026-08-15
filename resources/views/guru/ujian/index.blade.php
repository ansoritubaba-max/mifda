<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-5 relative z-10">
            <h2 class="font-black text-2xl md:text-3xl text-white tracking-tight flex items-center gap-3">
                <span class="text-3xl md:text-4xl bg-white/10 p-2 rounded-2xl border border-white/20">📋</span>
                Kelola Ujian Semester
            </h2>

            <a href="{{ route('guru.materi.create') }}?mode=ujian"
               class="bg-gradient-to-r from-blue-500 to-indigo-600 text-white px-6 md:px-8 py-3.5 rounded-xl shadow-lg shadow-blue-200/50 hover:shadow-xl font-black transition-all hover:-translate-y-1 flex items-center gap-2 text-sm border border-blue-400/50">
                <span class="text-xl">➕</span> Buat Ujian Baru
            </a>
        </div>
    </x-slot>

    <div class="py-6 md:py-12 bg-slate-50 min-h-screen relative overflow-hidden">

        <div class="absolute top-0 right-0 w-[30rem] h-[30rem] bg-blue-200 rounded-full mix-blend-multiply filter blur-[100px] opacity-20 pointer-events-none -translate-y-1/2 translate-x-1/3"></div>
        <div class="absolute bottom-0 left-0 w-[30rem] h-[30rem] bg-indigo-200 rounded-full mix-blend-multiply filter blur-[100px] opacity-20 pointer-events-none translate-y-1/3 -translate-x-1/3"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6 relative z-10">

            @if(session('success'))
                <div id="flash-success" class="bg-gradient-to-r from-emerald-400 to-teal-500 text-white p-5 rounded-2xl shadow-xl font-black flex items-center gap-4 border border-emerald-300 transition-all duration-500">
                    <span class="text-3xl">🎉</span>
                    <span>{{ session('success') }}</span>
                </div>
            @endif
            @if(session('error'))
                <div id="flash-error" class="bg-gradient-to-r from-rose-500 to-red-600 text-white p-5 rounded-2xl shadow-xl font-black flex items-center gap-4 border border-red-400 transition-all duration-500">
                    <span class="text-3xl">⚠️</span>
                    <span>{{ session('error') }}</span>
                </div>
            @endif
            <script>
                ['flash-success','flash-error'].forEach(id => {
                    const el = document.getElementById(id);
                    if (el) setTimeout(() => { el.style.opacity = '0'; el.style.transform = 'translateY(-8px)'; setTimeout(() => el.remove(), 500); }, 4000);
                });
            </script>

            {{-- PANDUAN --}}
            <div class="bg-blue-50 border border-blue-200 rounded-2xl p-5 flex items-start gap-4">
                <span class="text-2xl shrink-0">💡</span>
                <div>
                    <p class="font-black text-blue-800 text-sm">Cara membuat ujian semester:</p>
                    <p class="text-blue-700 text-xs mt-1 leading-relaxed">Klik <strong>"Buat Ujian Baru"</strong>, isi materi seperti biasa, lalu pilih jenis <strong>Ujian Ganjil</strong> atau <strong>Ujian Genap</strong> dan atur jadwal mulai/selesai. Soal bisa ditambah lewat tombol 📝 di bawah.</p>
                </div>
            </div>

            {{-- UJIAN GANJIL --}}
            @php
                $ganjil = $ujians->where('jenis', 'ujian_ganjil');
                $genap  = $ujians->where('jenis', 'ujian_genap');
            @endphp

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

                {{-- === UJIAN GANJIL === --}}
                <div class="bg-white rounded-[2rem] shadow-xl shadow-slate-200/40 border border-slate-100 overflow-hidden">
                    <div class="h-2 bg-gradient-to-r from-blue-400 to-indigo-500"></div>
                    <div class="p-6 border-b border-slate-50 flex items-center gap-3">
                        <div class="w-11 h-11 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center text-xl border border-blue-100">📋</div>
                        <div>
                            <h3 class="font-black text-lg text-slate-800">Ujian Semester Ganjil</h3>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">{{ $ganjil->count() }} ujian terdaftar</p>
                        </div>
                    </div>

                    <div class="divide-y divide-slate-50">
                        @forelse($ganjil as $u)
                            <div class="p-5 hover:bg-slate-50/50 transition group">
                                <div class="flex items-start justify-between gap-3">
                                    <div class="flex-1 min-w-0">
                                        <h4 class="font-black text-slate-800 text-base group-hover:text-blue-600 transition truncate">{{ $u->judul }}</h4>
                                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-0.5">{{ $u->mapel->nama_mapel ?? '-' }}</p>

                                        @if($u->jadwal_mulai)
                                            <div class="mt-2 flex flex-wrap gap-2">
                                                <span class="inline-flex items-center gap-1 text-[10px] font-black bg-emerald-50 text-emerald-700 border border-emerald-200 px-2.5 py-1 rounded-lg">
                                                    ▶ {{ \Carbon\Carbon::parse($u->jadwal_mulai)->format('d M Y, H:i') }}
                                                </span>
                                                @if($u->jadwal_selesai)
                                                <span class="inline-flex items-center gap-1 text-[10px] font-black bg-rose-50 text-rose-600 border border-rose-200 px-2.5 py-1 rounded-lg">
                                                    ■ {{ \Carbon\Carbon::parse($u->jadwal_selesai)->format('d M Y, H:i') }}
                                                </span>
                                                @endif
                                            </div>
                                        @else
                                            <span class="inline-block mt-2 text-[10px] font-black bg-amber-50 text-amber-600 border border-amber-200 px-2.5 py-1 rounded-lg">⏰ Jadwal belum diset</span>
                                        @endif

                                        <div class="flex items-center gap-2 mt-2">
                                            <span class="text-[10px] font-black text-slate-400">{{ $u->soals->count() ?? 0 }} soal</span>
                                            <span class="text-slate-200">•</span>
                                            <span class="text-[10px] font-black text-amber-600">⚡ {{ $u->xp_reward }} XP</span>
                                        </div>
                                    </div>
                                    <div class="flex flex-col gap-2 shrink-0">
                                        <a href="{{ route('guru.soal.index', $u->id) }}" class="bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white border border-blue-100 hover:border-blue-600 px-3 py-2 rounded-xl text-[10px] font-black transition flex items-center gap-1">
                                            📝 Soal
                                        </a>
                                        <a href="{{ route('guru.materi.edit', $u->id) }}" class="bg-amber-50 text-amber-600 hover:bg-amber-500 hover:text-white border border-amber-100 px-3 py-2 rounded-xl text-[10px] font-black transition flex items-center gap-1">
                                            ✏️ Edit
                                        </a>
                                        <form action="{{ route('guru.materi.destroy', $u->id) }}" method="POST"
                                              onsubmit="return confirm('Hapus ujian ini? Semua soal ikut terhapus.')">
                                            @csrf @method('DELETE')
                                            <button class="w-full bg-rose-50 text-rose-600 hover:bg-rose-600 hover:text-white border border-rose-100 px-3 py-2 rounded-xl text-[10px] font-black transition flex items-center gap-1 cursor-pointer">
                                                🗑️ Hapus
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="p-12 text-center text-slate-400">
                                <div class="text-5xl mb-3 opacity-40">📭</div>
                                <p class="text-sm font-black">Belum ada ujian ganjil</p>
                                <p class="text-xs font-bold mt-1">Buat ujian dengan pilih jenis "Ujian Ganjil"</p>
                            </div>
                        @endforelse
                    </div>
                </div>

                {{-- === UJIAN GENAP === --}}
                <div class="bg-white rounded-[2rem] shadow-xl shadow-slate-200/40 border border-slate-100 overflow-hidden">
                    <div class="h-2 bg-gradient-to-r from-purple-400 to-pink-500"></div>
                    <div class="p-6 border-b border-slate-50 flex items-center gap-3">
                        <div class="w-11 h-11 bg-purple-50 text-purple-600 rounded-xl flex items-center justify-center text-xl border border-purple-100">📋</div>
                        <div>
                            <h3 class="font-black text-lg text-slate-800">Ujian Semester Genap</h3>
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">{{ $genap->count() }} ujian terdaftar</p>
                        </div>
                    </div>

                    <div class="divide-y divide-slate-50">
                        @forelse($genap as $u)
                            <div class="p-5 hover:bg-slate-50/50 transition group">
                                <div class="flex items-start justify-between gap-3">
                                    <div class="flex-1 min-w-0">
                                        <h4 class="font-black text-slate-800 text-base group-hover:text-purple-600 transition truncate">{{ $u->judul }}</h4>
                                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-0.5">{{ $u->mapel->nama_mapel ?? '-' }}</p>

                                        @if($u->jadwal_mulai)
                                            <div class="mt-2 flex flex-wrap gap-2">
                                                <span class="inline-flex items-center gap-1 text-[10px] font-black bg-emerald-50 text-emerald-700 border border-emerald-200 px-2.5 py-1 rounded-lg">
                                                    ▶ {{ \Carbon\Carbon::parse($u->jadwal_mulai)->format('d M Y, H:i') }}
                                                </span>
                                                @if($u->jadwal_selesai)
                                                <span class="inline-flex items-center gap-1 text-[10px] font-black bg-rose-50 text-rose-600 border border-rose-200 px-2.5 py-1 rounded-lg">
                                                    ■ {{ \Carbon\Carbon::parse($u->jadwal_selesai)->format('d M Y, H:i') }}
                                                </span>
                                                @endif
                                            </div>
                                        @else
                                            <span class="inline-block mt-2 text-[10px] font-black bg-amber-50 text-amber-600 border border-amber-200 px-2.5 py-1 rounded-lg">⏰ Jadwal belum diset</span>
                                        @endif

                                        <div class="flex items-center gap-2 mt-2">
                                            <span class="text-[10px] font-black text-slate-400">{{ $u->soals->count() ?? 0 }} soal</span>
                                            <span class="text-slate-200">•</span>
                                            <span class="text-[10px] font-black text-amber-600">⚡ {{ $u->xp_reward }} XP</span>
                                        </div>
                                    </div>
                                    <div class="flex flex-col gap-2 shrink-0">
                                        <a href="{{ route('guru.soal.index', $u->id) }}" class="bg-blue-50 text-blue-600 hover:bg-blue-600 hover:text-white border border-blue-100 hover:border-blue-600 px-3 py-2 rounded-xl text-[10px] font-black transition flex items-center gap-1">
                                            📝 Soal
                                        </a>
                                        <a href="{{ route('guru.materi.edit', $u->id) }}" class="bg-amber-50 text-amber-600 hover:bg-amber-500 hover:text-white border border-amber-100 px-3 py-2 rounded-xl text-[10px] font-black transition flex items-center gap-1">
                                            ✏️ Edit
                                        </a>
                                        <form action="{{ route('guru.materi.destroy', $u->id) }}" method="POST"
                                              onsubmit="return confirm('Hapus ujian ini? Semua soal ikut terhapus.')">
                                            @csrf @method('DELETE')
                                            <button class="w-full bg-rose-50 text-rose-600 hover:bg-rose-600 hover:text-white border border-rose-100 px-3 py-2 rounded-xl text-[10px] font-black transition flex items-center gap-1 cursor-pointer">
                                                🗑️ Hapus
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="p-12 text-center text-slate-400">
                                <div class="text-5xl mb-3 opacity-40">📭</div>
                                <p class="text-sm font-black">Belum ada ujian genap</p>
                                <p class="text-xs font-bold mt-1">Buat ujian dengan pilih jenis "Ujian Genap"</p>
                            </div>
                        @endforelse
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
    </style>
</x-app-layout>
