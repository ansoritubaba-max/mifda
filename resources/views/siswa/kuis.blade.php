<x-app-layout>
    <style>
        /* Fix: peer-checked tidak bekerja untuk elemen nested — pakai :has() */
        label:has(input[type="radio"]:checked) .radio-circle {
            border-color: #10b981;
            background-color: #10b981;
        }
        label:has(input[type="radio"]:checked) .radio-dot {
            transform: scale(1);
        }
        label:has(input[type="radio"]:checked) .choice-option {
            border-color: #10b981;
            background-color: #f0fdf4;
            box-shadow: 0 4px 12px -2px rgba(16,185,129,0.2);
        }
        label:has(input[type="radio"]:checked) .choice-option .choice-text {
            color: #065f46;
        }
    </style>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 relative z-10">
            <div class="flex items-center gap-3 md:gap-4">
                <a href="{{ route('siswa.materi', $materi->mapel_id) }}" class="bg-white p-2.5 md:p-3 rounded-xl md:rounded-2xl shadow-sm border border-gray-200 hover:bg-slate-50 hover:shadow-md transition-all transform hover:-translate-x-1 font-bold text-gray-600 text-xs md:text-sm flex items-center gap-2 group">
                    <span class="group-hover:-translate-x-1 transition-transform">⬅️</span> 
                    <span class="hidden sm:inline">Batal</span>
                </a>
                
                <h2 class="font-black text-xl md:text-3xl leading-tight truncate drop-shadow-sm flex items-center gap-2 md:gap-3" style="color: {{ $materi->mapel->warna_tema ?? '#10b981' }}">
                    <span class="text-3xl md:text-4xl animate-bounce">📝</span> 
                    Kuis: {{ $materi->judul }}
                </h2>
            </div>
            
            <div class="bg-white/80 backdrop-blur-sm border border-gray-200 px-4 md:px-5 py-2 md:py-2.5 rounded-xl shadow-sm text-xs font-black text-gray-500 uppercase tracking-widest flex items-center justify-center gap-2 self-start md:self-auto">
                <span class="w-2 h-2 rounded-full animate-pulse" style="background-color: {{ $materi->mapel->warna_tema ?? '#10b981' }}"></span>
                Mode Ujian Aktif
            </div>
        </div>
    </x-slot>

    <div class="py-6 md:py-12 bg-slate-50 min-h-screen relative overflow-hidden">
        
        <div class="absolute top-0 right-0 w-96 h-96 bg-emerald-200 rounded-full mix-blend-multiply filter blur-[100px] opacity-30 pointer-events-none -translate-y-1/2 translate-x-1/3"></div>
        <div class="absolute bottom-1/2 left-0 w-96 h-96 bg-blue-200 rounded-full mix-blend-multiply filter blur-[100px] opacity-30 pointer-events-none translate-y-1/2 -translate-x-1/3"></div>

        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            
            <form action="{{ route('siswa.kuis.submit', $materi->id) }}" method="POST">
                @csrf
                
                @foreach($materi->soals as $index => $soal)
                    <div class="bg-white shadow-xl shadow-slate-200/50 rounded-[2rem] md:rounded-[3rem] mb-8 md:mb-12 border border-slate-100 relative overflow-hidden group hover:shadow-2xl transition-shadow duration-300">
                        
                        <div class="h-2 md:h-3 w-full" style="background-color: {{ $materi->mapel->warna_tema ?? '#3b82f6' }}"></div>
                        
                        <div class="absolute top-6 right-6 md:top-8 md:right-8 bg-slate-100 border border-slate-200 px-4 py-1.5 md:px-5 md:py-2 rounded-xl font-black text-slate-400 text-xs md:text-sm tracking-widest shadow-inner">
                            SOAL {{ $index + 1 }}
                        </div>

                        <div class="p-6 pt-12 md:p-10 md:pt-14 lg:p-14 lg:pt-16">
                            
                            @if($soal->gambar)
                                <div class="mb-8 md:mb-10 rounded-[1.5rem] md:rounded-[2rem] overflow-hidden border-4 border-slate-50 shadow-md max-w-lg mx-auto relative group-hover:scale-[1.02] transition-transform duration-500">
                                    <div class="absolute inset-0 bg-gradient-to-t from-black/10 to-transparent z-10 pointer-events-none"></div>
                                    {{-- 🚀 PERBAIKAN: Menggunakan asset() untuk memanggil gambar soal kuis --}}
                                    <img src="{{ asset('storage/' . $soal->gambar) }}" 
                                        alt="Gambar Soal" 
                                        class="w-full h-auto object-cover relative z-0">
                                </div>
                            @endif

                            <h3 class="text-xl md:text-2xl lg:text-3xl font-black text-gray-800 mb-8 md:mb-12 leading-snug md:leading-relaxed text-center drop-shadow-sm">
                                {{ $soal->pertanyaan }}
                            </h3>
                            
                            <div class="space-y-4 md:space-y-5">
                                @foreach($soal->opsi_jawaban->shuffle() as $opsi)
                                    <label class="block cursor-pointer transform transition-all duration-200 hover:-translate-y-1 active:scale-[0.98]">
                                        <input type="radio" name="jawaban[{{ $soal->id }}]" value="{{ $opsi->id }}" class="peer sr-only" required>

                                        <div class="choice-option p-5 md:p-6 rounded-[1.5rem] md:rounded-[2rem] border-2 border-slate-100 bg-slate-50
                                                    peer-checked:border-emerald-500 peer-checked:bg-emerald-50 peer-checked:shadow-md
                                                    hover:bg-white hover:border-slate-300 hover:shadow-sm transition-all font-bold text-gray-600
                                                    peer-checked:text-emerald-700 flex items-center gap-4 md:gap-6 text-base md:text-xl relative overflow-hidden">

                                            <div class="absolute inset-0 bg-emerald-100 opacity-0 peer-checked:opacity-20 pointer-events-none transition-opacity"></div>

                                            <div class="radio-circle w-8 h-8 md:w-10 md:h-10 rounded-full border-4 border-slate-300 flex items-center justify-center shrink-0 transition-all duration-200 shadow-sm relative z-10">
                                                <div class="radio-dot w-3 h-3 md:w-4 md:h-4 rounded-full bg-white scale-0 transition-transform duration-300 shadow-sm"></div>
                                            </div>

                                            <span class="choice-text relative z-10">{{ $opsi->teks_opsi }}</span>
                                        </div>
                                    </label>
                                @endforeach
                            </div>

                        </div>
                    </div>
                @endforeach

                <div class="text-center mt-12 md:mt-16 mb-10 md:mb-16">
                    <button type="submit" class="w-full sm:w-auto px-10 md:px-16 py-5 md:py-6 rounded-[1.5rem] md:rounded-[2rem] font-black text-lg md:text-2xl text-white shadow-xl hover:shadow-2xl hover:-translate-y-2 hover:scale-105 transition-all transform duration-300 border border-white/20 uppercase tracking-widest flex items-center justify-center gap-3 mx-auto" style="background-color: {{ $materi->mapel->warna_tema ?? '#10b981' }}; box-shadow: 0 15px 30px -10px {{ $materi->mapel->warna_tema ?? '#10b981' }}80;">
                        <span>🚀</span> Kumpulkan Jawaban! 
                    </button>
                    <p class="mt-4 text-xs font-bold text-gray-400 uppercase tracking-widest">
                        Pastikan semua soal telah terjawab
                    </p>
                </div>
            </form>

        </div>
    </div>
</x-app-layout>