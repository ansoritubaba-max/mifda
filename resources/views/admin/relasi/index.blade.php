<x-app-layout>
    <style>
        [x-cloak] { display: none !important; }
        /* Scrollbar custom premium untuk daftar relasi */
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
                <span class="text-2xl md:text-3xl bg-white/10 p-2 rounded-xl md:rounded-2xl border border-white/20">🤝</span>
                Kelola Relasi Sistem
            </h2>
        </div>
    </x-slot>

    <div class="py-6 md:py-12 bg-slate-50 min-h-screen relative overflow-hidden">
        
        <div class="absolute top-0 right-0 w-[30rem] h-[30rem] bg-emerald-200 rounded-full mix-blend-multiply filter blur-[100px] opacity-20 pointer-events-none -translate-y-1/2 translate-x-1/3"></div>
        <div class="absolute bottom-0 left-0 w-[30rem] h-[30rem] bg-orange-200 rounded-full mix-blend-multiply filter blur-[100px] opacity-20 pointer-events-none translate-y-1/3 -translate-x-1/3"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 grid grid-cols-1 lg:grid-cols-2 gap-6 md:gap-8 relative z-10">
            
            <div class="bg-white rounded-[2rem] md:rounded-[2.5rem] shadow-xl shadow-slate-200/40 border border-slate-100 p-6 md:p-8 flex flex-col relative overflow-hidden" x-data="{ searchGuru: '' }">
                
                <div class="absolute top-0 left-0 w-full h-2 bg-gradient-to-r from-emerald-400 to-teal-50"></div>

                <div class="flex items-center justify-between border-b border-slate-100 pb-5 mb-5 shrink-0">
                    <h3 class="font-black text-xl text-slate-800 flex items-center gap-3">
                        <span class="p-2 bg-emerald-50 text-emerald-600 rounded-xl shadow-sm border border-emerald-100">👩‍🏫</span> 
                        Guru & Mapel
                    </h3>
                </div>
                
                <div class="relative mb-5 shrink-0">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400">🔍</span>
                    <input type="text" x-model="searchGuru" placeholder="Cari nama guru..." class="w-full rounded-xl md:rounded-2xl border border-slate-200 bg-slate-50 focus:bg-white text-sm px-4 py-3 md:py-3.5 pl-11 focus:ring-2 focus:ring-emerald-500 focus:border-transparent font-bold text-slate-700 shadow-sm transition-all">
                </div>

                <div class="overflow-y-auto space-y-4 pr-2 scrollbar-custom scroll-smooth max-h-[500px]">
                    @forelse($gurus as $guru)
                        <div x-show="searchGuru === '' || '{{ strtolower($guru->name) }}'.includes(searchGuru.toLowerCase())" 
                             x-transition:enter="transition ease-out duration-300 transform"
                             x-transition:enter-start="opacity-0 translate-y-4"
                             x-transition:enter-end="opacity-100 translate-y-0"
                             class="bg-white border border-slate-200 p-5 rounded-[1.5rem] md:rounded-[2rem] shadow-sm hover:shadow-lg hover:shadow-emerald-100/50 hover:border-emerald-200 transition-all duration-300 group relative overflow-hidden" 
                             x-data="{ openRelasiGuru: false }">
                            
                            <div class="absolute left-0 top-0 bottom-0 w-1.5 bg-slate-100 group-hover:bg-emerald-400 transition-colors"></div>

                            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-start md:items-center mb-4 gap-4 pl-2">
                                <div class="flex items-center gap-4">
                                    <div class="w-12 h-12 shrink-0 group-hover:scale-105 transition-transform">
                                        @if($guru->avatar)
                                            {{-- 🚀 PERBAIKAN: Menggunakan asset() untuk foto guru --}}
                                            <img src="{{ asset('storage/' . $guru->avatar) }}" 
                                                alt="Avatar" 
                                                class="w-12 h-12 rounded-[1rem] object-cover shadow-sm border border-emerald-100">
                                        @else
                                            {{-- Tampilan inisial jika guru belum pasang foto --}}
                                            <div class="w-12 h-12 bg-gradient-to-br from-emerald-50 to-teal-100 text-emerald-600 rounded-[1rem] flex items-center justify-center font-black text-xl shadow-sm border border-emerald-200 group-hover:bg-emerald-500 group-hover:text-white transition-colors">
                                                {{ substr($guru->name, 0, 1) }}
                                            </div>
                                        @endif
                                    </div>
                                    <div>
                                        <h4 class="font-black text-slate-800 text-base md:text-lg leading-tight group-hover:text-emerald-600 transition-colors">{{ $guru->name }}</h4>
                                        <p class="text-[9px] md:text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1">Mengajar <span class="text-emerald-500">{{ $guru->mengampu_mapel->count() }}</span> Mapel</p>
                                    </div>
                                </div>
                                <button @click="openRelasiGuru = true" class="w-full sm:w-auto bg-emerald-50 text-emerald-600 hover:bg-emerald-600 hover:text-white px-4 md:px-5 py-2.5 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all shadow-sm border border-emerald-200 hover:border-emerald-600 flex items-center justify-center gap-1.5 shrink-0">
                                    <span>⚙️</span> Atur
                                </button>
                            </div>

                            <div class="flex flex-wrap gap-2 pl-2">
                                @forelse($guru->mengampu_mapel as $mapel)
                                    <span class="bg-gradient-to-r from-emerald-50 to-teal-50 border border-emerald-100 text-emerald-700 px-2.5 py-1 rounded-lg text-[9px] md:text-[10px] font-black shadow-sm uppercase tracking-widest flex items-center gap-1">
                                        <span class="w-1.5 h-1.5 bg-emerald-400 rounded-full"></span>
                                        {{ $mapel->nama_mapel }} ({{ $mapel->kelas->nama_kelas ?? 'All' }})
                                    </span>
                                @empty
                                    <span class="text-[10px] md:text-xs text-rose-400 font-bold italic bg-rose-50 px-3 py-1 rounded-lg border border-rose-100">Belum memiliki jadwal mengajar</span>
                                @endforelse
                            </div>

                            <div x-show="openRelasiGuru" style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-sm px-4" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
                                
                                <div @click.away="openRelasiGuru = false" class="bg-white rounded-[2rem] shadow-2xl w-full max-w-md p-6 md:p-8 text-left relative max-h-[90vh] flex flex-col" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 scale-95" x-transition:enter-end="opacity-100 translate-y-0 scale-100" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 scale-100" x-transition:leave-end="opacity-0 translate-y-4 scale-95" x-data="{ searchMapel: '' }">
                                    
                                    <button @click="openRelasiGuru = false" type="button" class="absolute top-5 right-5 w-8 h-8 flex items-center justify-center bg-slate-50 text-slate-400 hover:text-rose-500 hover:bg-rose-50 rounded-full font-black text-lg transition-colors border border-slate-100">✕</button>
                                    
                                    <div class="shrink-0 mb-5">
                                        <h3 class="font-black text-xl md:text-2xl text-slate-800 mb-1 flex items-center gap-2"><span class="text-emerald-500">⚙️</span> Tugas Mengajar</h3>
                                        <p class="text-[10px] md:text-xs font-bold text-slate-500">Pilih mata pelajaran yang diampu oleh <span class="text-emerald-600 bg-emerald-50 px-1 py-0.5 rounded">{{ $guru->name }}</span></p>
                                    </div>
                                    
                                    <div class="relative mb-4 shrink-0">
                                        <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400">🔍</span>
                                        <input type="text" x-model="searchMapel" placeholder="Cari Mapel / Kelas..." class="w-full rounded-xl border border-slate-200 bg-slate-50 text-xs px-4 py-2.5 pl-9 focus:ring-2 focus:ring-emerald-500 focus:border-transparent font-bold text-slate-700 shadow-sm transition-all">
                                    </div>
                                    
                                    <form action="{{ route('admin.relasi.store') }}" method="POST" class="flex flex-col flex-grow overflow-hidden">
                                        @csrf
                                        <input type="hidden" name="guru_id" value="{{ $guru->id }}">
                                        
                                        <div class="overflow-y-auto p-3 bg-slate-50 rounded-xl border border-slate-200 space-y-2 scrollbar-custom max-h-[300px]">
                                            @php $mapelIds = $guru->mengampu_mapel->pluck('id')->toArray(); @endphp
                                            @foreach($mapels as $mapel)
                                                <label x-show="searchMapel === '' || '{{ strtolower($mapel->nama_mapel . ' ' . ($mapel->kelas->nama_kelas ?? '')) }}'.includes(searchMapel.toLowerCase())" class="flex items-center gap-3 p-3 bg-white rounded-xl border border-slate-200 cursor-pointer hover:border-emerald-300 hover:shadow-md transition-all shadow-sm group">
                                                    
                                                    <div class="relative flex items-center justify-center">
                                                        <input type="checkbox" name="mapel_id[]" value="{{ $mapel->id }}" {{ in_array($mapel->id, $mapelIds) ? 'checked' : '' }} class="peer appearance-none rounded-md border-2 border-slate-300 checked:bg-emerald-500 checked:border-emerald-500 w-5 h-5 transition-all shrink-0 cursor-pointer focus:ring-emerald-500 focus:ring-offset-1">
                                                        <svg class="absolute w-3.5 h-3.5 text-white opacity-0 peer-checked:opacity-100 pointer-events-none transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                                    </div>

                                                    <div class="overflow-hidden">
                                                        <span class="font-bold text-sm md:text-base text-slate-700 block truncate group-hover:text-emerald-700 transition-colors">{{ $mapel->nama_mapel }}</span>
                                                        <span class="text-[9px] md:text-[10px] font-black text-emerald-600 uppercase tracking-widest block truncate">Kelas: {{ $mapel->kelas->nama_kelas ?? 'Umum' }}</span>
                                                    </div>
                                                </label>
                                            @endforeach
                                        </div>
                                        <button type="submit" class="w-full bg-gradient-to-r from-emerald-500 to-teal-600 text-white rounded-xl py-3.5 md:py-4 mt-5 font-black uppercase text-[10px] md:text-xs tracking-widest shadow-lg shadow-emerald-200/50 hover:shadow-xl hover:-translate-y-1 active:scale-95 transition-all shrink-0">
                                            Simpan Relasi 💾
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-16 text-slate-400 flex flex-col items-center">
                            <span class="text-6xl mb-3 opacity-40 grayscale">👩‍🏫</span>
                            <p class="font-bold text-sm">Belum ada data guru.</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <div class="bg-white rounded-[2rem] md:rounded-[2.5rem] shadow-xl shadow-slate-200/40 border border-slate-100 p-6 md:p-8 flex flex-col relative overflow-hidden" x-data="{ searchOrtu: '' }">
                
                <div class="absolute top-0 left-0 w-full h-2 bg-gradient-to-r from-orange-400 to-amber-500"></div>

                <div class="flex items-center justify-between border-b border-slate-100 pb-5 mb-5 shrink-0">
                    <h3 class="font-black text-xl text-slate-800 flex items-center gap-3">
                        <span class="p-2 bg-orange-50 text-orange-600 rounded-xl shadow-sm border border-orange-100">👨‍👩‍👧</span> 
                        Orang Tua & Anak
                    </h3>
                </div>
                
                <div class="relative mb-5 shrink-0">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400">🔍</span>
                    <input type="text" x-model="searchOrtu" placeholder="Cari nama orang tua..." class="w-full rounded-xl md:rounded-2xl border border-slate-200 bg-slate-50 focus:bg-white text-sm px-4 py-3 md:py-3.5 pl-11 focus:ring-2 focus:ring-orange-500 focus:border-transparent font-bold text-slate-700 shadow-sm transition-all">
                </div>

                <div class="overflow-y-auto space-y-4 pr-2 scrollbar-custom scroll-smooth max-h-[500px]">
                    @forelse($ortus as $ortu)
                        <div x-show="searchOrtu === '' || '{{ strtolower($ortu->name) }}'.includes(searchOrtu.toLowerCase())" 
                             x-transition:enter="transition ease-out duration-300 transform"
                             x-transition:enter-start="opacity-0 translate-y-4"
                             x-transition:enter-end="opacity-100 translate-y-0"
                             class="bg-white border border-slate-200 p-5 rounded-[1.5rem] md:rounded-[2rem] shadow-sm hover:shadow-lg hover:shadow-orange-100/50 hover:border-orange-200 transition-all duration-300 group relative overflow-hidden" 
                             x-data="{ openRelasiOrtu: false }">
                            
                            <div class="absolute left-0 top-0 bottom-0 w-1.5 bg-slate-100 group-hover:bg-orange-400 transition-colors"></div>

                            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-start md:items-center mb-4 gap-4 pl-2">
                                <div class="flex items-center gap-4">
                                    <div class="w-12 h-12 shrink-0 group-hover:scale-105 transition-transform">
                                        @if($ortu->avatar)
                                            {{-- 🚀 PERBAIKAN: Menggunakan asset() untuk foto orang tua --}}
                                            <img src="{{ asset('storage/' . $ortu->avatar) }}" 
                                                alt="Avatar" 
                                                class="w-12 h-12 rounded-[1rem] object-cover shadow-sm border border-orange-100">
                                        @else
                                            {{-- Inisial Ortu jika foto kosong --}}
                                            <div class="w-12 h-12 bg-gradient-to-br from-orange-50 to-amber-100 text-orange-600 rounded-[1rem] flex items-center justify-center font-black text-xl shadow-sm border border-orange-200 group-hover:bg-orange-500 group-hover:text-white transition-colors">
                                                {{ substr($ortu->name, 0, 1) }}
                                            </div>
                                        @endif
                                    </div>
                                    <div>
                                        <h4 class="font-black text-slate-800 text-base md:text-lg leading-tight group-hover:text-orange-600 transition-colors">{{ $ortu->name }}</h4>
                                        <p class="text-[9px] md:text-[10px] font-bold text-slate-400 uppercase tracking-widest mt-1">Memantau <span class="text-orange-500">{{ $ortu->anak->count() }}</span> Anak</p>
                                    </div>
                                </div>
                                <button @click="openRelasiOrtu = true" class="w-full sm:w-auto bg-orange-50 text-orange-600 hover:bg-orange-600 hover:text-white px-4 md:px-5 py-2.5 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all shadow-sm border border-orange-200 hover:border-orange-600 flex items-center justify-center gap-1.5 shrink-0">
                                    <span>⚙️</span> Atur
                                </button>
                            </div>

                            <div class="flex flex-wrap gap-2 pl-2">
                                @forelse($ortu->anak as $anak)
                                    <span class="bg-gradient-to-r from-orange-50 to-amber-50 border border-orange-100 text-orange-700 px-2.5 py-1 rounded-lg text-[9px] md:text-[10px] font-black shadow-sm uppercase tracking-widest flex items-center gap-1">
                                        <span class="w-1.5 h-1.5 bg-orange-400 rounded-full"></span>
                                        {{ $anak->name }} ({{ $anak->kelas->nama_kelas ?? 'Tanpa Kelas' }})
                                    </span>
                                @empty
                                    <span class="text-[10px] md:text-xs text-rose-400 font-bold italic bg-rose-50 px-3 py-1 rounded-lg border border-rose-100">Belum ada anak yang ditautkan</span>
                                @endforelse
                            </div>

                            <div x-show="openRelasiOrtu" style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-sm px-4" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
                                
                                <div @click.away="openRelasiOrtu = false" class="bg-white rounded-[2rem] shadow-2xl w-full max-w-md p-6 md:p-8 text-left relative max-h-[90vh] flex flex-col" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 scale-95" x-transition:enter-end="opacity-100 translate-y-0 scale-100" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 scale-100" x-transition:leave-end="opacity-0 translate-y-4 scale-95" x-data="{ searchAnak: '' }">
                                    
                                    <button @click="openRelasiOrtu = false" type="button" class="absolute top-5 right-5 w-8 h-8 flex items-center justify-center bg-slate-50 text-slate-400 hover:text-rose-500 hover:bg-rose-50 rounded-full font-black text-lg transition-colors border border-slate-100">✕</button>
                                    
                                    <div class="shrink-0 mb-5">
                                        <h3 class="font-black text-xl md:text-2xl text-slate-800 mb-1 flex items-center gap-2"><span class="text-orange-500">⚙️</span> Tautkan Anak</h3>
                                        <p class="text-[10px] md:text-xs font-bold text-slate-500">Pilih anak untuk Bpk/Ibu <span class="text-orange-600 bg-orange-50 px-1 py-0.5 rounded">{{ $ortu->name }}</span></p>
                                    </div>
                                    
                                    <div class="relative mb-4 shrink-0">
                                        <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-slate-400">🔍</span>
                                        <input type="text" x-model="searchAnak" placeholder="Cari Nama Siswa / Kelas..." class="w-full rounded-xl border border-slate-200 bg-slate-50 text-xs px-4 py-2.5 pl-9 focus:ring-2 focus:ring-orange-500 focus:border-transparent font-bold text-slate-700 shadow-sm transition-all">
                                    </div>
                                    
                                    <form action="{{ route('admin.relasi.ortu.store') }}" method="POST" class="flex flex-col flex-grow overflow-hidden">
                                        @csrf
                                        <input type="hidden" name="ortu_id" value="{{ $ortu->id }}">
                                        
                                        <div class="overflow-y-auto p-3 bg-slate-50 rounded-xl border border-slate-200 space-y-2 scrollbar-custom max-h-[300px]">
                                            @php $anakIds = $ortu->anak->pluck('id')->toArray(); @endphp
                                            @foreach($siswas as $siswa)
                                                <label x-show="searchAnak === '' || '{{ strtolower($siswa->name . ' ' . ($siswa->kelas->nama_kelas ?? '')) }}'.includes(searchAnak.toLowerCase())" class="flex items-center gap-3 p-3 bg-white rounded-xl border border-slate-200 cursor-pointer hover:border-orange-300 hover:shadow-md transition-all shadow-sm group">
                                                    
                                                    <div class="relative flex items-center justify-center">
                                                        <input type="checkbox" name="anak_id[]" value="{{ $siswa->id }}" {{ in_array($siswa->id, $anakIds) ? 'checked' : '' }} class="peer appearance-none rounded-md border-2 border-slate-300 checked:bg-orange-500 checked:border-orange-500 w-5 h-5 transition-all shrink-0 cursor-pointer focus:ring-orange-500 focus:ring-offset-1">
                                                        <svg class="absolute w-3.5 h-3.5 text-white opacity-0 peer-checked:opacity-100 pointer-events-none transition-opacity" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                                    </div>
                                                    
                                                        <div class="w-8 h-8 md:w-10 md:h-10 shrink-0">
                                                            @if($siswa->avatar)
                                                                {{-- 🚀 PERBAIKAN: Menggunakan asset() untuk foto siswa --}}
                                                                <img src="{{ asset('storage/' . $siswa->avatar) }}" 
                                                                    alt="Avatar" 
                                                                    class="w-full h-full rounded-full object-cover shadow-sm border border-slate-100">
                                                            @else
                                                                {{-- Tampilan inisial jika siswa tidak punya foto --}}
                                                                <div class="w-full h-full rounded-full bg-slate-100 text-slate-500 flex items-center justify-center font-black text-xs md:text-sm shadow-inner border border-slate-200">
                                                                    {{ substr($siswa->name, 0, 1) }}
                                                                </div>
                                                            @endif
                                                        </div>

                                                    <div class="overflow-hidden">
                                                        <span class="font-bold text-sm md:text-base text-slate-700 block truncate group-hover:text-orange-700 transition-colors">{{ $siswa->name }}</span>
                                                        <span class="text-[9px] md:text-[10px] font-black text-orange-600 uppercase tracking-widest block truncate">Kelas: {{ $siswa->kelas->nama_kelas ?? 'Tanpa Kelas' }}</span>
                                                    </div>
                                                </label>
                                            @endforeach
                                        </div>
                                        <button type="submit" class="w-full bg-gradient-to-r from-orange-400 to-amber-500 text-white border border-orange-400 rounded-xl py-3.5 md:py-4 mt-5 font-black uppercase text-[10px] md:text-xs tracking-widest shadow-lg shadow-orange-200/50 hover:shadow-xl hover:-translate-y-1 active:scale-95 transition-all shrink-0">
                                            Simpan Relasi 💾
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-16 text-slate-400 flex flex-col items-center">
                            <span class="text-6xl mb-3 opacity-40 grayscale">👨‍👩‍👧</span>
                            <p class="font-bold text-sm">Belum ada data orang tua.</p>
                        </div>
                    @endforelse
                </div>
            </div>

        </div>
    </div>
</x-app-layout>