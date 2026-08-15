<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3 md:gap-4 relative z-10">
            <a href="{{ route('admin.mapel.index') }}" class="w-10 h-10 md:w-12 md:h-12 bg-white rounded-xl md:rounded-2xl shadow-sm border border-slate-200 hover:bg-slate-50 hover:shadow-md transition-all transform hover:-translate-x-1 flex items-center justify-center text-slate-500 hover:text-blue-600 shrink-0 group">
                <span class="group-hover:-translate-x-1 transition-transform">⬅️</span>
            </a>
            
            <h2 class="font-black text-xl md:text-3xl text-white tracking-tight flex items-center gap-2 md:gap-3">
                <span class="text-2xl md:text-3xl bg-white/10 p-2 rounded-xl md:rounded-2xl border border-white/20">➕</span>
                Tambah Pelajaran Baru
            </h2>
        </div>
    </x-slot>

    <div class="py-6 md:py-12 bg-slate-50 min-h-screen relative overflow-hidden">
        
        <div class="absolute top-0 right-0 w-[30rem] h-[30rem] bg-emerald-200 rounded-full mix-blend-multiply filter blur-[100px] opacity-20 pointer-events-none -translate-y-1/2 translate-x-1/3"></div>
        <div class="absolute bottom-0 left-0 w-[30rem] h-[30rem] bg-blue-200 rounded-full mix-blend-multiply filter blur-[100px] opacity-20 pointer-events-none translate-y-1/3 -translate-x-1/3"></div>

        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            
            <div class="bg-white rounded-[2rem] md:rounded-[3rem] shadow-2xl shadow-slate-200/50 border border-slate-100 overflow-hidden relative">
                
                <div class="h-3 md:h-4 w-full bg-gradient-to-r from-emerald-400 to-blue-500"></div>

                <div class="absolute top-0 right-0 w-64 h-64 bg-blue-50 rounded-full blur-3xl -mr-20 -mt-20 opacity-50 z-0 pointer-events-none"></div>

                <div class="p-6 md:p-10 lg:p-12 relative z-10">
                    
                    <div class="mb-10 text-center">
                        <div class="w-20 h-20 md:w-24 md:h-24 bg-gradient-to-br from-blue-50 to-indigo-100 text-blue-600 rounded-[1.5rem] md:rounded-[2rem] flex items-center justify-center text-4xl md:text-5xl mx-auto mb-5 shadow-sm border border-blue-100 rotate-6 hover:rotate-0 transition-transform duration-300">
                            📘
                        </div>
                        <h3 class="text-2xl md:text-3xl font-black text-slate-800 tracking-tight">Detail Mata Pelajaran</h3>
                        <p class="text-xs md:text-sm font-bold text-slate-400 mt-2 max-w-sm mx-auto leading-relaxed">Lengkapi formulir di bawah ini untuk menambahkan kurikulum pembelajaran baru ke dalam sistem.</p>
                    </div>

                    <form action="{{ route('admin.mapel.store') }}" method="POST" class="space-y-6 md:space-y-8">
                        @csrf
                        
                        <div>
                            <label class="block text-[10px] md:text-xs font-black text-slate-500 uppercase tracking-widest mb-2 px-2">Pilih Tingkat Kelas <span class="text-rose-500">*</span></label>
                            <div class="relative">
                                <select name="kelas_id" class="w-full border border-slate-200 rounded-2xl bg-slate-50 focus:bg-white focus:ring-2 focus:ring-blue-500 focus:border-transparent font-bold text-slate-700 px-4 md:px-5 py-3.5 md:py-4 transition-all shadow-sm appearance-none cursor-pointer" required>
                                    <option value="" disabled selected>-- Silakan Pilih Kelas --</option>
                                    @foreach($kelas as $k)
                                        <option value="{{ $k->id }}">{{ $k->nama_kelas }}</option>
                                    @endforeach
                                </select>
                                <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-5 text-slate-400">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"></path></svg>
                                </div>
                            </div>
                            @error('kelas_id') <p class="text-rose-500 text-[10px] md:text-xs mt-2 font-bold px-2">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-[10px] md:text-xs font-black text-slate-500 uppercase tracking-widest mb-2 px-2">Nama Mata Pelajaran <span class="text-rose-500">*</span></label>
                            <input type="text" name="nama_mapel" value="{{ old('nama_mapel') }}" class="w-full border border-slate-200 rounded-2xl bg-slate-50 focus:bg-white focus:ring-2 focus:ring-blue-500 focus:border-transparent font-bold text-slate-800 px-4 md:px-5 py-3.5 md:py-4 transition-all shadow-sm placeholder-slate-400" placeholder="Contoh: Ilmu Pengetahuan Alam" required>
                            @error('nama_mapel') <p class="text-rose-500 text-[10px] md:text-xs mt-2 font-bold px-2">{{ $message }}</p> @enderror
                        </div>

                        <div class="p-5 md:p-6 bg-slate-50/80 rounded-[1.5rem] md:rounded-[2rem] border border-slate-100 shadow-inner group hover:border-blue-200 transition-colors">
                            <label class="block text-[10px] md:text-xs font-black text-slate-500 uppercase tracking-widest mb-3 flex items-center gap-2">
                                <span>🎨</span> Tentukan Warna Tema <span class="text-rose-500">*</span>
                            </label>
                            
                            <div class="flex items-center gap-4 md:gap-6 bg-white p-3 pr-5 rounded-[1.5rem] border border-slate-200 shadow-sm w-fit">
                                <div class="relative overflow-hidden w-12 h-12 md:w-14 md:h-14 rounded-[1rem] border-4 border-white shadow-md shrink-0">
                                    <input type="color" name="warna_tema" value="{{ old('warna_tema', '#3b82f6') }}" class="absolute -top-4 -left-4 w-24 h-24 cursor-pointer p-0 border-0" required>
                                </div>
                                <div>
                                    <h4 class="text-slate-800 font-black text-xs md:text-sm mb-0.5">Identitas Warna</h4>
                                    <p class="text-[9px] md:text-[10px] font-bold text-slate-400 uppercase tracking-widest leading-tight max-w-[150px] md:max-w-none">Warna ini akan menjadi ciri khas mapel di layar siswa.</p>
                                </div>
                            </div>
                            @error('warna_tema') <p class="text-rose-500 text-[10px] md:text-xs mt-3 font-bold px-2">{{ $message }}</p> @enderror
                        </div>

                        <div class="flex flex-col-reverse sm:flex-row justify-end gap-3 md:gap-4 pt-6 md:pt-8 border-t border-slate-100">
                            <a href="{{ route('admin.mapel.index') }}" class="w-full sm:w-auto px-6 md:px-8 py-3.5 md:py-4 bg-slate-100 text-slate-500 rounded-xl md:rounded-2xl font-black hover:bg-slate-200 transition-all uppercase text-[10px] md:text-xs tracking-widest flex items-center justify-center">
                                Batal
                            </a>
                            
                            <button type="submit" class="w-full sm:w-auto px-8 md:px-10 py-3.5 md:py-4 bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-xl md:rounded-2xl font-black shadow-lg shadow-blue-200/50 hover:shadow-xl hover:-translate-y-1 active:scale-95 transition-all uppercase text-[10px] md:text-xs tracking-widest flex items-center justify-center gap-2 border border-blue-400/50">
                                Simpan Pelajaran <span class="text-base md:text-lg">🚀</span>
                            </button>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>