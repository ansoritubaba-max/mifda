<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 relative z-10">
            <div class="flex items-center gap-3 md:gap-4">
                <a href="{{ route('guru.dashboard') }}" class="w-10 h-10 md:w-12 md:h-12 bg-white rounded-xl md:rounded-2xl shadow-sm border border-slate-200 hover:bg-slate-50 hover:shadow-md transition-all transform hover:-translate-x-1 flex items-center justify-center text-slate-500 hover:text-emerald-600 shrink-0 group">
                    <span class="group-hover:-translate-x-1 transition-transform">⬅️</span>
                </a>
                
                <div>
                    <h2 class="font-black text-xl md:text-3xl text-white leading-tight tracking-tight flex items-center gap-2 md:gap-3">
                        <span class="text-2xl md:text-3xl bg-white/10 p-2 rounded-xl md:rounded-2xl border border-white/20">📝</span>
                        Kelola Kuis Misi
                    </h2>
                    <div class="flex items-center gap-2 mt-1.5">
                        <span class="px-3 py-1 rounded-lg text-[9px] md:text-[10px] font-black uppercase tracking-widest text-white shadow-sm" style="background-color: {{ $materi->mapel->warna_tema ?? '#10b981' }}">
                            Topik: {{ $materi->judul }}
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </x-slot>

    <div class="py-6 md:py-12 bg-slate-50 min-h-screen relative overflow-hidden">
        
        <div class="absolute top-0 right-0 w-[30rem] h-[30rem] bg-emerald-200 rounded-full mix-blend-multiply filter blur-[100px] opacity-20 pointer-events-none -translate-y-1/2 translate-x-1/3"></div>
        <div class="absolute bottom-0 left-0 w-[30rem] h-[30rem] bg-blue-200 rounded-full mix-blend-multiply filter blur-[100px] opacity-20 pointer-events-none translate-y-1/3 -translate-x-1/3"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 grid grid-cols-1 lg:grid-cols-12 gap-6 md:gap-8 relative z-10">
            
            <div class="lg:col-span-4 lg:sticky lg:top-24 h-fit">
                <div class="bg-white rounded-[2rem] md:rounded-[2.5rem] shadow-xl shadow-slate-200/50 border border-slate-100 overflow-hidden">
                    
                    <div class="h-3 w-full bg-gradient-to-r from-emerald-400 to-teal-500"></div>

                    <div class="p-6 md:p-8">
                        <div class="flex items-center gap-3 mb-6">
                            <div class="w-12 h-12 bg-gradient-to-br from-emerald-50 to-teal-100 text-emerald-600 rounded-[1.25rem] flex items-center justify-center text-2xl shadow-sm border border-emerald-100">➕</div>
                            <h3 class="font-black text-lg md:text-xl text-slate-800 leading-tight">Tambah Soal Baru</h3>
                        </div>
                        
                        @if(session('success'))
                            <div class="bg-gradient-to-r from-emerald-50 to-teal-50 text-emerald-700 p-4 rounded-2xl mb-6 text-xs font-black uppercase tracking-widest flex items-center gap-3 border border-emerald-200 shadow-sm animate-fade-in-down">
                                <span class="text-xl">✅</span> {{ session('success') }}
                            </div>
                        @endif

                        <form action="{{ route('guru.soal.store', $materi->id) }}" method="POST" enctype="multipart/form-data" class="space-y-5">
                            @csrf
                            
                            <div>
                                <label class="block text-[10px] md:text-xs font-black text-slate-500 uppercase tracking-widest mb-2 px-1">Pertanyaan <span class="text-rose-500">*</span></label>
                                <textarea name="pertanyaan" class="w-full rounded-2xl border border-slate-200 bg-slate-50 focus:bg-white focus:ring-2 focus:ring-emerald-500 focus:border-transparent font-medium text-slate-700 px-4 py-3.5 transition-all shadow-sm resize-y" rows="3" required placeholder="Contoh: Berapa hasil 5+5?"></textarea>
                                @error('pertanyaan') <p class="text-rose-500 text-[10px] font-bold mt-1 px-1">{{ $message }}</p> @enderror
                            </div>

                            <div class="p-4 bg-slate-50 border border-slate-200 rounded-2xl hover:border-emerald-300 transition-colors group">
                                <label class="block text-[10px] md:text-xs font-black text-emerald-600 uppercase tracking-widest mb-2 flex items-center gap-2">
                                    <span class="text-base">🖼️</span> Gambar Pendukung (Opsional)
                                </label>
                                <input type="file" name="gambar" accept="image/*" class="block w-full text-[10px] md:text-xs text-slate-500 font-bold file:mr-4 file:py-2.5 file:px-5 file:rounded-xl file:border-0 file:text-[10px] file:font-black file:uppercase file:tracking-widest file:bg-emerald-100 file:text-emerald-700 hover:file:bg-emerald-200 cursor-pointer transition-all"/>
                            </div>

                            <div class="space-y-4 p-5 bg-slate-50/80 border border-slate-100 rounded-2xl shadow-inner">
                                <div>
                                    <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-1 px-1 flex items-center gap-1.5"><span class="bg-slate-200 text-slate-600 w-5 h-5 flex items-center justify-center rounded-md">A</span> Opsi A</label>
                                    <input type="text" name="opsi_a" class="w-full rounded-xl border border-slate-200 bg-white font-bold text-sm px-4 py-2.5 focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all shadow-sm" required>
                                </div>
                                <div>
                                    <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-1 px-1 flex items-center gap-1.5"><span class="bg-slate-200 text-slate-600 w-5 h-5 flex items-center justify-center rounded-md">B</span> Opsi B</label>
                                    <input type="text" name="opsi_b" class="w-full rounded-xl border border-slate-200 bg-white font-bold text-sm px-4 py-2.5 focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all shadow-sm" required>
                                </div>
                                <div>
                                    <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-1 px-1 flex items-center gap-1.5"><span class="bg-slate-200 text-slate-600 w-5 h-5 flex items-center justify-center rounded-md">C</span> Opsi C</label>
                                    <input type="text" name="opsi_c" class="w-full rounded-xl border border-slate-200 bg-white font-bold text-sm px-4 py-2.5 focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all shadow-sm" required>
                                </div>
                                <div>
                                    <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-1 px-1 flex items-center gap-1.5"><span class="bg-purple-200 text-purple-600 w-5 h-5 flex items-center justify-center rounded-md">D</span> Opsi D</label>
                                    <input type="text" name="opsi_d" class="w-full rounded-xl border border-slate-200 bg-white font-bold text-sm px-4 py-2.5 focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all shadow-sm" required>
                                </div>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-[10px] font-black text-slate-500 uppercase tracking-widest mb-2 px-1">Kunci Jawaban</label>
                                    <select name="jawaban_benar" class="w-full rounded-xl border border-emerald-200 bg-emerald-50 text-emerald-700 font-black px-4 py-3 focus:ring-2 focus:ring-emerald-500 focus:border-transparent text-sm appearance-none cursor-pointer shadow-sm text-center">
                                        <option value="a">Opsi A</option>
                                        <option value="b">Opsi B</option>
                                        <option value="c">Opsi C</option>
                                        <option value="d">Opsi D</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-[10px] font-black text-amber-600 uppercase tracking-widest mb-2 px-1 flex items-center gap-1">Hadiah XP</label>
                                    <div class="relative">
                                        <span class="absolute inset-y-0 left-0 flex items-center pl-3 text-amber-500 text-lg">⚡</span>
                                        <input type="number" name="xp_reward" value="10" min="0" class="w-full rounded-xl border border-amber-200 bg-amber-50 text-amber-700 font-black px-4 py-3 pl-9 focus:ring-2 focus:ring-amber-500 focus:border-transparent text-sm shadow-sm transition-all">
                                    </div>
                                </div>
                            </div>

                            <button type="submit" class="w-full bg-gradient-to-r from-emerald-500 to-teal-600 text-white rounded-2xl py-4 font-black uppercase tracking-widest text-[10px] md:text-xs shadow-xl shadow-emerald-200/50 hover:shadow-2xl hover:-translate-y-1 active:scale-95 transition-all mt-6 border border-emerald-400/50 flex items-center justify-center gap-2">
                                Simpan Soal 🚀
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-8">
                <div class="bg-white rounded-[2rem] md:rounded-[2.5rem] shadow-xl shadow-slate-200/40 border border-slate-100 p-6 md:p-8">
                    
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-8 pb-4 border-b border-slate-100 gap-4">
                        <h3 class="font-black text-xl md:text-2xl text-slate-800 flex items-center gap-3">
                            <span class="p-2 bg-slate-50 border border-slate-200 rounded-xl">📋</span> 
                            Daftar Soal Kuis
                        </h3>
                        <span class="bg-gradient-to-r from-blue-50 to-indigo-50 border border-blue-100 text-blue-600 px-5 py-2 rounded-xl text-[10px] md:text-xs font-black uppercase tracking-widest shadow-sm self-start sm:self-auto flex items-center gap-2">
                            Total: <span class="bg-blue-600 text-white px-2 py-0.5 rounded-md">{{ $soals->count() }}</span>
                        </span>
                    </div>
                    
                    <div class="space-y-6">
                        @forelse ($soals as $index => $soal)
                            <div class="p-6 md:p-8 border border-slate-200 rounded-[2rem] bg-slate-50/50 hover:bg-white hover:border-emerald-200 hover:shadow-xl hover:shadow-emerald-100/50 transition-all duration-300 relative group overflow-hidden">
                                
                                <div class="absolute left-0 top-0 bottom-0 w-2 bg-slate-200 group-hover:bg-emerald-400 transition-colors"></div>

                                <form action="{{ route('guru.soal.destroy', $soal->id) }}" method="POST" class="absolute top-4 right-4 md:opacity-0 group-hover:opacity-100 transition-opacity z-10">
                                    @csrf @method('DELETE')
                                    <button type="submit" onclick="return confirm('⚠️ Yakin ingin menghapus soal ini secara permanen?')" class="bg-white border border-rose-200 text-rose-500 p-2 md:p-2.5 rounded-xl hover:bg-rose-500 hover:text-white shadow-sm hover:shadow-md transition-all">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 md:h-5 md:w-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" clip-rule="evenodd" /></svg>
                                    </button>
                                </form>

                                <div class="flex flex-col md:flex-row gap-6 relative pl-2 md:pl-4">
                                    
                                @if($soal->gambar)
                                    <div class="w-full md:w-48 h-32 md:h-auto shrink-0 relative rounded-[1.5rem] overflow-hidden border-4 border-white shadow-md">
                                        <div class="absolute inset-0 bg-slate-900/10 pointer-events-none z-10"></div>
                                        {{-- 🚀 PERBAIKAN: Menggunakan asset() agar gambar soal muncul dari storage lokal --}}
                                        <img src="{{ asset('storage/' . $soal->gambar) }}" 
                                            class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110" 
                                            alt="Gambar Soal">
                                    </div>
                                @endif

                                    <div class="flex-grow">
                                        <div class="flex flex-col md:flex-row md:items-start justify-between gap-4 mb-6">
                                            <p class="font-black text-lg md:text-xl text-slate-800 leading-snug md:pr-12">
                                                <span class="text-emerald-500 mr-1">{{ $index + 1 }}.</span> {{ $soal->pertanyaan }}
                                            </p>
                                            <span class="bg-amber-50 text-amber-600 border border-amber-200 text-[10px] px-3 py-1.5 rounded-xl font-black uppercase tracking-widest shadow-sm shrink-0 inline-flex items-center gap-1.5 h-fit">
                                                <span class="text-sm">⚡</span> +{{ $soal->xp_reward }}
                                            </span>
                                        </div>
                                        
                                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 md:gap-4">
                                            @foreach($soal->opsi_jawaban as $opsi)
                                                <div class="p-4 md:p-5 rounded-2xl border {{ $opsi->is_benar ? 'bg-emerald-50 border-emerald-300 text-emerald-800 shadow-md ring-1 ring-emerald-100' : 'bg-white border-slate-200 text-slate-500 shadow-sm' }} text-sm font-bold flex items-center gap-3 transition-colors">
                                                    @if($opsi->is_benar)
                                                        <div class="w-6 h-6 bg-emerald-500 rounded-full flex items-center justify-center text-white shrink-0 shadow-sm border-2 border-emerald-100">
                                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-3 w-3 md:h-4 md:w-4" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" /></svg>
                                                        </div>
                                                    @else
                                                        <div class="w-6 h-6 rounded-full border-2 border-slate-200 shrink-0 bg-slate-50"></div>
                                                    @endif
                                                    <span class="leading-tight">{{ $opsi->teks_opsi }}</span>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="text-center py-20 px-6 border-2 border-dashed border-slate-200 rounded-[2.5rem] bg-slate-50/50 flex flex-col items-center">
                                <span class="text-6xl md:text-7xl block mb-6 opacity-40 grayscale">📝</span>
                                <h4 class="text-xl md:text-2xl font-black text-slate-700 mb-2">Belum ada soal kuis!</h4>
                                <p class="text-sm md:text-base font-bold text-slate-500 max-w-sm">Silakan buat soal pertama Anda menggunakan formulir di sebelah kiri.</p>
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
        .animate-fade-in-down { animation: fade-in-down 0.5s ease-out forwards; }
    </style>
</x-app-layout>