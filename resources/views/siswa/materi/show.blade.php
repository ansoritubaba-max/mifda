<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 relative z-10">
            <div class="flex items-center gap-3 md:gap-4">
                <a href="{{ route('siswa.materi', $materi->mapel_id) }}" class="bg-white p-2.5 md:p-3 rounded-xl md:rounded-2xl shadow-sm border border-slate-200 hover:bg-slate-50 hover:shadow-md transition-all transform hover:-translate-x-1 font-bold text-slate-600 text-xs md:text-sm flex items-center gap-2 group">
                    <span class="group-hover:-translate-x-1 transition-transform">⬅️</span> 
                    <span class="hidden sm:inline">Kembali</span>
                </a>
                
                <h2 class="font-black text-xl md:text-3xl text-white leading-tight tracking-tight flex items-center gap-2 md:gap-3">
                    <span class="text-3xl md:text-4xl animate-bounce">📖</span> Misi Belajar
                </h2>
            </div>
            
            <span class="px-5 md:px-6 py-2.5 md:py-3 rounded-xl md:rounded-2xl text-[10px] md:text-xs font-black text-white uppercase tracking-widest shadow-md border border-white/20 inline-block text-center" style="background-color: {{ $materi->mapel->warna_tema ?? '#10b981' }}">
                {{ $materi->mapel->nama_mapel ?? 'Mata Pelajaran' }}
            </span>
        </div>
    </x-slot>

    <div class="py-6 md:py-12 bg-slate-50 min-h-screen relative overflow-hidden">
        
        <div class="absolute top-0 right-0 w-96 h-96 bg-emerald-200 rounded-full mix-blend-multiply filter blur-[100px] opacity-30 pointer-events-none -translate-y-1/2 translate-x-1/3"></div>
        <div class="absolute bottom-0 left-0 w-96 h-96 bg-blue-200 rounded-full mix-blend-multiply filter blur-[100px] opacity-20 pointer-events-none translate-y-1/2 -translate-x-1/3"></div>

        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            
            <div class="bg-white rounded-[2rem] md:rounded-[3rem] shadow-2xl shadow-slate-200/50 border border-slate-100 overflow-hidden relative">
                
                <div class="h-3 md:h-4 w-full" style="background-color: {{ $materi->mapel->warna_tema ?? '#10b981' }}"></div>

                <div class="p-6 md:p-12 border-b border-slate-50 text-center relative overflow-hidden bg-gradient-to-b from-white to-slate-50/50">
                    <div class="absolute top-0 right-0 w-64 h-64 bg-emerald-50 rounded-full blur-3xl -mr-20 -mt-20 opacity-60 z-0"></div>
                    
                    <div class="relative z-10 max-w-3xl mx-auto">
                        <h1 class="text-3xl md:text-4xl lg:text-5xl font-black text-slate-800 mb-5 md:mb-6 leading-tight tracking-tight">{{ $materi->judul }}</h1>
                        
                        <div class="flex flex-wrap items-center justify-center gap-3 md:gap-4">
                            <span class="bg-white border border-slate-200 text-slate-500 px-4 py-2 rounded-xl md:rounded-2xl font-black text-[10px] md:text-xs uppercase tracking-widest shadow-sm flex items-center gap-2">
                                <span class="text-sm">📌</span> FORMAT: {{ str_replace('_', ' ', $materi->tipe) }}
                            </span>
                            <span class="bg-gradient-to-r from-amber-50 to-yellow-100 text-amber-700 border border-yellow-200 px-4 py-2 rounded-xl md:rounded-2xl font-black text-[10px] md:text-xs uppercase tracking-widest shadow-sm flex items-center gap-2 transform hover:scale-105 transition-transform">
                                <span class="text-sm">🎁</span> HADIAH: +{{ $materi->xp_reward }} XP
                            </span>
                        </div>
                    </div>
                </div>

                <div class="p-6 md:p-12 space-y-8 md:space-y-12 relative z-10">
                    
                    @if($materi->tipe === 'youtube' && $materi->youtube_link)
                        @php
                            $ytLink = $materi->youtube_link;
                            if (str_contains($ytLink, 'watch?v=')) {
                                $ytLink = str_replace('watch?v=', 'embed/', $ytLink);
                            } elseif (str_contains($ytLink, 'youtu.be/')) {
                                $ytLink = str_replace('youtu.be/', 'youtube.com/embed/', $ytLink);
                            }
                        @endphp
                        <div class="w-full aspect-video bg-slate-900 rounded-[1.5rem] md:rounded-[2rem] overflow-hidden shadow-2xl border-4 md:border-[6px] border-slate-100 relative group">
                            <iframe src="{{ $ytLink }}" class="absolute top-0 left-0 w-full h-full border-0 relative z-10" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
                        </div>

                    @elseif($materi->tipe === 'video_lokal' && $materi->file_path)
                        <div class="w-full aspect-video bg-slate-900 rounded-[1.5rem] md:rounded-[2rem] overflow-hidden shadow-2xl border-4 md:border-[6px] border-slate-100 relative flex items-center justify-center">
                            <video controls class="w-full h-full object-contain relative z-10">
                                {{-- 🚀 PERBAIKAN: Menggunakan asset() untuk video lokal --}}
                                <source src="{{ asset('storage/' . $materi->file_path) }}" type="video/mp4">
                                Browser Anda tidak mendukung pemutar video.
                            </video>
                        </div>

                    @elseif(in_array($materi->tipe, ['dokumen', 'pdf', 'file']) && $materi->file_path)
                        @php 
                            $extension = strtolower(pathinfo($materi->file_path, PATHINFO_EXTENSION)); 
                            if(empty($extension) && $materi->tipe === 'pdf') {
                                $extension = 'pdf';
                            }
                        @endphp
                        
                        @if($extension === 'pdf')
                            <div class="w-full h-[500px] md:h-[800px] bg-slate-200 rounded-[1.5rem] md:rounded-[2rem] overflow-hidden shadow-inner border-2 border-slate-200 flex flex-col">
                                <div class="bg-slate-800 text-white p-4 md:p-5 flex flex-col md:flex-row justify-between items-center gap-4 border-b border-slate-900">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 bg-slate-700 rounded-xl flex items-center justify-center text-xl shadow-inner">📖</div>
                                        <span class="font-black text-xs md:text-sm uppercase tracking-widest text-emerald-400">Buku Digital (PDF)</span>
                                    </div>
                                    
                                    <div class="flex items-center gap-2 md:gap-3 w-full md:w-auto">
                                        {{-- 🚀 PERBAIKAN: Menggunakan asset() untuk link PDF --}}
                                        <a href="{{ asset('storage/' . $materi->file_path) }}" 
                                           target="_blank" 
                                           class="flex-1 md:flex-none text-center text-[10px] md:text-xs uppercase font-black tracking-widest text-slate-300 hover:text-white px-4 py-3 transition bg-slate-700 hover:bg-slate-600 rounded-xl shadow-sm">
                                           Layar Penuh ↗️
                                        </a>

                                        <a href="{{ asset('storage/' . $materi->file_path) }}" 
                                           download 
                                           class="flex-1 md:flex-none justify-center text-[10px] md:text-xs uppercase font-black tracking-widest bg-emerald-500 text-white px-5 py-3 rounded-xl hover:bg-emerald-400 transition shadow-sm flex items-center gap-2">
                                           Unduh 📥
                                        </a>
                                    </div>
                                </div>
                                <iframe src="{{ asset('storage/' . $materi->file_path) }}#view=FitH&toolbar=1&navpanes=0" class="w-full flex-grow border-0 bg-slate-100"></iframe>
                            </div>
                        
                        @else
                            <div class="bg-slate-50 border-2 border-dashed border-slate-200 p-8 md:p-12 rounded-[2rem] text-center flex flex-col items-center justify-center">
                                <span class="text-6xl md:text-7xl block mb-4 grayscale opacity-50">📁</span>
                                <h4 class="font-black text-xl md:text-2xl text-slate-800 mb-2">Dokumen ({{ strtoupper($extension) }})</h4>
                                <p class="text-slate-500 font-bold mb-8 text-xs md:text-sm">Silakan unduh materi untuk mempelajarinya.</p>
                                <a href="{{ asset('storage/' . $materi->file_path) }}" 
                                   download 
                                   class="bg-slate-800 text-white px-8 md:px-10 py-4 md:py-5 rounded-xl md:rounded-2xl font-black uppercase tracking-widest shadow-xl hover:bg-slate-700 hover:-translate-y-1 transition-all text-xs md:text-sm flex items-center gap-3">
                                   Unduh Materi 📥
                                </a>
                            </div>
                        @endif
                    @endif

                    @if($materi->konten)
                        <div class="bg-white p-6 md:p-10 rounded-[1.5rem] md:rounded-[2rem] border border-slate-100 shadow-sm relative overflow-hidden">
                            <div class="absolute left-0 top-0 bottom-0 w-2" style="background-color: {{ $materi->mapel->warna_tema ?? '#10b981' }}"></div>
                            
                            <h4 class="font-black text-slate-400 uppercase tracking-widest text-[10px] md:text-xs mb-6 flex items-center gap-2 pl-4">
                                <span class="text-base">📝</span> Penjelasan Materi
                            </h4>
                            <div class="prose prose-slate prose-sm md:prose-base lg:prose-lg max-w-none text-slate-700 font-medium leading-relaxed whitespace-pre-wrap pl-4 text-justify">{{ $materi->konten }}</div>
                        </div>
                    @endif

                </div>

                <div class="m-6 md:m-12 mt-0 bg-slate-900 rounded-[2rem] md:rounded-[2.5rem] p-6 md:p-10 shadow-2xl relative overflow-hidden border border-slate-800" id="ai-section">
                    <div class="absolute top-0 right-0 w-[500px] h-[500px] bg-emerald-500/10 rounded-full blur-[100px] pointer-events-none"></div>
                    
                    <div class="relative z-10 flex flex-col md:flex-row gap-8 lg:gap-12 items-start">
                        <div class="w-full md:w-1/3">
                            <div class="flex items-center gap-4 mb-5">
                                <div class="w-14 h-14 md:w-16 md:h-16 bg-gradient-to-br from-emerald-400 to-teal-500 rounded-[1.25rem] flex items-center justify-center text-3xl md:text-4xl shadow-lg border border-white/20">🤖</div>
                                <div>
                                    <h3 class="font-black text-xl md:text-2xl text-white tracking-tight">AI Asisten</h3>
                                    <p class="text-emerald-400 font-bold text-[10px] md:text-xs uppercase tracking-widest mt-1">Teman Belajarmu Pintar</p>
                                </div>
                            </div>
                            <p class="text-slate-300 font-medium text-xs md:text-sm mb-8 leading-relaxed">Tanyakan apa saja tentang materi ini, AI akan membantumu memahami lebih cepat!</p>
                            
                            <button onclick="kirimPesanKeAI('Tolong ringkaskan materi ini dengan poin-poin yang sangat mudah dimengerti.')" class="w-full bg-white/10 hover:bg-white/20 text-white font-black py-4 px-6 rounded-xl md:rounded-2xl shadow-lg border border-white/10 transition-all flex items-center justify-center gap-3 uppercase tracking-widest text-[10px] md:text-xs group backdrop-blur-sm">
                                <span class="text-lg group-hover:rotate-12 transition-transform">✨</span> Ringkas Materi Ini
                            </button>
                        </div>

                        <div class="w-full md:w-2/3 bg-slate-800/80 backdrop-blur-md rounded-[1.5rem] md:rounded-[2rem] border border-slate-700/50 p-4 md:p-6 flex flex-col h-[400px] md:h-[450px] shadow-inner">
                            <div id="chat-box" class="flex-grow overflow-y-auto pr-3 space-y-5 mb-5 scrollbar-custom">
                                <div class="flex items-start gap-3 md:gap-4">
                                    <div class="w-8 h-8 md:w-10 md:h-10 bg-emerald-500 rounded-full flex items-center justify-center text-sm md:text-base border-2 border-slate-800 shrink-0 shadow-md">🤖</div>
                                    <div class="bg-slate-700 text-slate-100 p-4 rounded-2xl rounded-tl-none text-xs md:text-sm font-medium leading-relaxed shadow-sm max-w-[85%]">
                                        Halo <strong class="text-white">{{ explode(' ', Auth::user()->name)[0] }}</strong>! Ada materi yang bikin kamu bingung? Ketik aja pertanyaanmu di bawah!
                                    </div>
                                </div>
                            </div>
                            
                            <form id="ai-form" class="flex gap-2 relative mt-auto">
                                <input type="text" id="ai-input" placeholder="Tanya sesuatu tentang materi ini..." class="w-full bg-slate-900 border border-slate-700 rounded-xl md:rounded-2xl text-white placeholder-slate-500 font-medium px-5 py-3.5 focus:ring-2 focus:ring-emerald-500 focus:border-transparent transition-all text-xs md:text-sm shadow-inner" required autocomplete="off">
                                <button type="submit" id="ai-btn" class="shrink-0 bg-gradient-to-br from-emerald-500 to-teal-600 text-white px-5 md:px-8 rounded-xl md:rounded-2xl font-black shadow-lg hover:shadow-emerald-500/30 transition-all flex items-center justify-center hover:scale-105 active:scale-95">
                                    <span class="hidden md:block uppercase tracking-widest text-xs">KIRIM</span>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="p-8 md:p-12 border-t border-slate-100 bg-slate-50 flex flex-col items-center justify-center text-center">
                    <p class="text-slate-400 font-bold text-xs uppercase tracking-widest mb-4">Sudah paham materi ini?</p>
                    @if($sudahMengerjakan)
                        <div class="bg-gradient-to-r from-slate-400 to-slate-500 text-white px-8 md:px-12 py-4 md:py-5 rounded-xl md:rounded-2xl font-black text-sm md:text-lg shadow-md cursor-not-allowed uppercase tracking-widest flex items-center justify-center gap-3 opacity-90">
                            Kuis Telah Diselesaikan <span class="text-xl md:text-2xl bg-white/20 rounded-full p-1">✅</span>
                        </div>
                    @else
                        <a href="{{ route('siswa.kuis', $materi->id) }}" class="bg-gradient-to-r from-emerald-500 to-blue-600 text-white px-8 md:px-12 py-4 md:py-5 rounded-xl md:rounded-2xl font-black text-sm md:text-lg shadow-xl hover:shadow-2xl hover:-translate-y-1 hover:scale-105 transition-all uppercase tracking-widest flex items-center justify-center gap-3 w-full sm:w-auto">
                            Lanjut Kerjakan Kuis <span class="text-xl md:text-2xl bg-white/20 rounded-full p-1">📝</span>
                        </a>
                    @endif
                </div>

            </div>
        </div>
    </div>

    <style>
        .scrollbar-custom::-webkit-scrollbar { width: 6px; }
        .scrollbar-custom::-webkit-scrollbar-track { background: rgba(30, 41, 59, 0.5); border-radius: 10px;}
        .scrollbar-custom::-webkit-scrollbar-thumb { background-color: #475569; border-radius: 10px; }
    </style>

    <script>
        const chatBox = document.getElementById('chat-box');
        const aiForm = document.getElementById('ai-form');
        const aiInput = document.getElementById('ai-input');
        const aiBtn = document.getElementById('ai-btn');

        // FITUR BARU (upgrade #6): bacakan balasan Mibi pakai suara, biar
        // siswa yang belum lancar baca tetap bisa "dengar" jawabannya.
        // Pakai Web Speech API bawaan browser (gratis, gak perlu server
        // tambahan). Suara perempuan Indonesia dipilih kalau tersedia di
        // device siswa — kalau enggak, browser pakai suara default-nya.
        function bacakanSuara(teks) {
            if (!('speechSynthesis' in window) || !teks) return;
            window.speechSynthesis.cancel(); // hentikan suara sebelumnya kalau masih ngomong
            const utter = new SpeechSynthesisUtterance(teks);
            utter.lang = 'id-ID';
            utter.pitch = 1.1;
            utter.rate = 0.95;
            const suara = window.speechSynthesis.getVoices();
            const suaraCewek = suara.find(v => v.lang.startsWith('id') && /female|perempuan|wanita/i.test(v.name))
                || suara.find(v => v.lang.startsWith('id'));
            if (suaraCewek) utter.voice = suaraCewek;
            window.speechSynthesis.speak(utter);
        }

        aiForm.addEventListener('submit', function(e) {
            e.preventDefault();
            if(aiInput.value.trim() !== '') {
                kirimPesanKeAI(aiInput.value);
                aiInput.value = '';
            }
        });

        function kirimPesanKeAI(pesan) {
            chatBox.innerHTML += `
                <div class="flex items-start gap-3 md:gap-4 flex-row-reverse">
                    <div class="w-8 h-8 md:w-10 md:h-10 bg-emerald-100 rounded-full flex items-center justify-center text-sm md:text-base border-2 border-slate-800 shrink-0 shadow-md">👦</div>
                    <div class="bg-emerald-600 text-white p-4 rounded-2xl rounded-tr-none text-xs md:text-sm font-medium shadow-sm leading-relaxed whitespace-pre-wrap max-w-[85%]">${pesan}</div>
                </div>
            `;
            chatBox.scrollTop = chatBox.scrollHeight;

            const typingId = 'typing-' + Date.now();
            chatBox.innerHTML += `
                <div id="${typingId}" class="flex items-start gap-3 md:gap-4">
                    <div class="w-8 h-8 md:w-10 md:h-10 bg-emerald-500 rounded-full flex items-center justify-center text-sm md:text-base border-2 border-slate-800 shrink-0 shadow-md">🤖</div>
                    <div class="bg-slate-700 text-white p-4 rounded-2xl rounded-tl-none text-xs md:text-sm font-medium flex items-center gap-2 max-w-[85%]">
                        <div class="w-2 h-2 bg-emerald-400 rounded-full animate-bounce"></div>
                        <div class="w-2 h-2 bg-emerald-400 rounded-full animate-bounce" style="animation-delay: 0.2s"></div>
                        <div class="w-2 h-2 bg-emerald-400 rounded-full animate-bounce" style="animation-delay: 0.4s"></div>
                    </div>
                </div>
            `;
            chatBox.scrollTop = chatBox.scrollHeight;

            aiBtn.disabled = true;
            aiInput.disabled = true;

            fetch("{{ route('siswa.ai.chat', $materi->id) }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                body: JSON.stringify({ pesan: pesan })
            })
            .then(response => response.json())
            .then(data => {
                document.getElementById(typingId).remove();
                const replyId = 'reply-' + Date.now();
                chatBox.innerHTML += `
                    <div class="flex items-start gap-3 md:gap-4">
                        <div class="w-8 h-8 md:w-10 md:h-10 bg-emerald-500 rounded-full flex items-center justify-center text-sm md:text-base border-2 border-slate-800 shrink-0 shadow-md">🤖</div>
                        <div class="flex flex-col gap-1.5 max-w-[85%]">
                            <div id="${replyId}" class="bg-slate-700 text-slate-100 p-4 rounded-2xl rounded-tl-none text-xs md:text-sm font-medium shadow-sm leading-relaxed whitespace-pre-wrap">${data.reply}</div>
                            <button type="button" onclick="bacakanSuara(document.getElementById('${replyId}').innerText)" class="self-start text-[10px] font-black text-emerald-400 hover:text-emerald-300 flex items-center gap-1">🔊 Dengarkan Lagi</button>
                        </div>
                    </div>
                `;
                chatBox.scrollTop = chatBox.scrollHeight;
                bacakanSuara(document.getElementById(replyId).innerText);
            })
            .catch(error => {
                document.getElementById(typingId).remove();
                alert('Gagal menghubungi AI.');
            })
            .finally(() => {
                aiBtn.disabled = false;
                aiInput.disabled = false;
                aiInput.focus();
            });
        }
    </script>
</x-app-layout>