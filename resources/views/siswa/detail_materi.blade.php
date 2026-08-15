<x-app-layout>
    {{-- Reading progress bar (fixed top) --}}
    <div id="reading-progress" class="fixed top-0 left-0 h-[3px] z-[200] transition-none"
         style="width:0%; background: linear-gradient(90deg, #10b981, #06b6d4, #10b981); background-size: 200% 100%;"></div>

    {{-- Back to top button --}}
    <button id="back-to-top" onclick="window.scrollTo({top:0,behavior:'smooth'})"
        title="Kembali ke atas"
        class="fixed bottom-6 right-6 z-50 w-12 h-12 bg-emerald-600 text-white rounded-full shadow-2xl flex items-center justify-center text-xl font-black hover:bg-emerald-500 hover:-translate-y-1 active:scale-95"
        style="opacity:0; transform:translateY(16px); pointer-events:none; transition: opacity .3s ease, transform .3s ease;">
        ↑
    </button>

    <style>
        /* Scrollbar */
        .scrollbar-custom::-webkit-scrollbar { width: 6px; }
        .scrollbar-custom::-webkit-scrollbar-track { background: rgba(30,41,59,.1); border-radius:10px; }
        .scrollbar-custom::-webkit-scrollbar-thumb { background:#6366f1; border-radius:10px; }

        /* Float animation */
        @keyframes float { 0%,100% { transform:translateY(0) } 50% { transform:translateY(-8px) } }
        .animate-float { animation: float 3s ease-in-out infinite; }

        /* ====================================================
           PROSE — Rich text content styling
           Nyaman dibaca seperti artikel/buku
        ==================================================== */
        .konten-materi {
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 1.0625rem;
            line-height: 1.9;
            color: #374151;
            word-break: break-word;
            -webkit-font-smoothing: antialiased;
        }
        /* Headings */
        .konten-materi h1 { font-size: 1.9rem; font-weight: 900; color: #111827; margin: 2rem 0 0.75rem; letter-spacing: -0.025em; border-bottom: 3px solid #d1fae5; padding-bottom: 0.5rem; }
        .konten-materi h2 { font-size: 1.45rem; font-weight: 800; color: #1e293b; margin: 1.75rem 0 0.6rem; letter-spacing: -0.015em; padding-left: 0.75rem; border-left: 4px solid #10b981; }
        .konten-materi h3 { font-size: 1.2rem; font-weight: 800; color: #374151; margin: 1.5rem 0 0.5rem; }
        .konten-materi h4 { font-size: 1.05rem; font-weight: 700; color: #4b5563; margin: 1.25rem 0 0.4rem; }
        /* Paragraphs */
        .konten-materi p  { margin: 1rem 0; }
        .konten-materi p:first-child { margin-top: 0; }
        .konten-materi p:last-child  { margin-bottom: 0; }
        /* Inline styles */
        .konten-materi strong, .konten-materi b { font-weight: 800; color: #111827; }
        .konten-materi em, .konten-materi i { font-style: italic; color: #374151; }
        .konten-materi u  { text-decoration: underline; text-decoration-color: #6366f1; text-underline-offset: 3px; }
        .konten-materi s  { text-decoration: line-through; color: #9ca3af; }
        .konten-materi a  { color: #4f46e5; font-weight: 700; text-decoration: underline; text-underline-offset: 3px; transition: color .15s; }
        .konten-materi a:hover { color: #3730a3; }
        /* Lists */
        .konten-materi ul { list-style: disc; padding-left: 1.75rem; margin: 1rem 0; }
        .konten-materi ol { list-style: decimal; padding-left: 1.75rem; margin: 1rem 0; }
        .konten-materi li { margin: 0.45rem 0; line-height: 1.75; }
        .konten-materi ul > li::marker { color: #10b981; font-size: 1.1em; }
        .konten-materi ol > li::marker { color: #10b981; font-weight: 800; }
        .konten-materi ul ul, .konten-materi ol ul { list-style: circle; margin-top: 0.3rem; }
        .konten-materi ul ul ul { list-style: square; }
        /* Blockquote */
        .konten-materi blockquote {
            border-left: 4px solid #10b981;
            background: linear-gradient(135deg, #f0fdf4, #ecfdf5);
            border-radius: 0 0.75rem 0.75rem 0;
            padding: 0.9rem 1.4rem;
            margin: 1.25rem 0;
            color: #065f46;
            font-style: italic;
            font-size: 1.05em;
        }
        /* Code */
        .konten-materi code {
            background: #eef2ff;
            color: #4338ca;
            padding: 0.15rem 0.45rem;
            border-radius: 0.35rem;
            font-size: 0.875em;
            font-weight: 600;
        }
        .konten-materi pre {
            background: #0f172a;
            color: #e2e8f0;
            padding: 1.35rem 1.5rem;
            border-radius: 0.75rem;
            overflow-x: auto;
            font-size: 0.875rem;
            margin: 1.25rem 0;
            box-shadow: inset 0 2px 8px rgba(0,0,0,.3);
        }
        .konten-materi pre code { background: transparent; color: inherit; padding: 0; }
        /* Table */
        .konten-materi table { width: 100%; border-collapse: collapse; margin: 1.25rem 0; font-size: 0.9rem; border-radius: 0.75rem; overflow: hidden; box-shadow: 0 1px 8px rgba(0,0,0,.07); }
        .konten-materi thead { background: linear-gradient(135deg, #059669, #10b981); color: #fff; }
        .konten-materi th { padding: 0.7rem 1rem; font-weight: 800; text-align: left; font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.05em; }
        .konten-materi td { padding: 0.65rem 1rem; border-bottom: 1px solid #e5e7eb; color: #4b5563; vertical-align: top; }
        .konten-materi tbody tr:nth-child(even) { background: #f9fafb; }
        .konten-materi tbody tr:hover { background: #f0fdf4; transition: background .15s; }
        /* Images */
        .konten-materi img {
            max-width: 100%;
            height: auto;
            border-radius: 0.75rem;
            box-shadow: 0 4px 20px rgba(0,0,0,.12);
            margin: 1.25rem auto;
            display: block;
        }
        /* HR */
        .konten-materi hr { border: none; border-top: 2px solid #d1fae5; margin: 2rem 0; }
        /* Superscript / Subscript */
        .konten-materi sup, .konten-materi sub { font-size: 0.7em; font-weight: 700; }
    </style>

    <x-slot name="header">
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 relative z-10">
            <div class="flex items-center gap-4">
                <a href="{{ route('siswa.materi', $materi->mapel_id) }}" class="bg-white/10 border border-white/20 hover:bg-white/20 transition-all p-2.5 rounded-xl font-bold text-white text-sm flex items-center gap-2 group">
                    <span>⬅️</span> <span class="hidden sm:inline">Kembali</span>
                </a>
                <div>
                    <h2 class="font-black text-2xl md:text-3xl text-white tracking-tight leading-none">Misi Belajar</h2>
                    <p class="text-[10px] md:text-xs font-bold text-indigo-300 uppercase tracking-[0.2em] mt-1">Tholabul 'Ilmi Faridhotun</p>
                </div>
            </div>
            <div class="flex items-center gap-3">
                <span class="px-5 py-3 rounded-2xl text-[10px] md:text-xs font-black text-white uppercase tracking-widest shadow-lg border-b-4 border-black/20 inline-block text-center" style="background-color: {{ $materi->mapel->warna_tema ?? '#4f46e5' }}">
                    📚 {{ $materi->mapel->nama_mapel }}
                </span>
            </div>
        </div>
    </x-slot>

    <div class="py-6 md:py-12 bg-slate-50 min-h-screen relative overflow-hidden bg-islamic">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="bg-white rounded-[2.5rem] md:rounded-[3.5rem] shadow-2xl shadow-emerald-200/50 border border-emerald-100 overflow-hidden relative">
                <div class="h-4 w-full bg-gradient-to-r from-emerald-600 via-yellow-400 to-emerald-600"></div>

                <div class="p-6 md:p-12 lg:p-16">
                    {{-- Judul Materi --}}
                    <div class="text-center mb-8 relative">
                        <div class="inline-block mb-4 p-4 bg-emerald-50 rounded-3xl animate-float">
                            <span class="text-5xl md:text-6xl">📖</span>
                        </div>
                        <h1 class="text-3xl md:text-5xl font-black text-slate-800 mb-4 tracking-tight leading-tight px-4 italic">{{ $materi->judul }}</h1>

                        {{-- Metadata: reading time + date --}}
                        <div class="flex items-center justify-center flex-wrap gap-3 text-slate-400 text-xs font-bold mb-4">
                            <span class="flex items-center gap-1">
                                ⏱️ &nbsp;<span id="read-time">...</span>&nbsp; baca
                            </span>
                            <span class="w-1 h-1 bg-slate-300 rounded-full"></span>
                            <span class="flex items-center gap-1">
                                📅&nbsp;{{ $materi->created_at->translatedFormat('d F Y') }}
                            </span>
                            <span class="w-1 h-1 bg-slate-300 rounded-full"></span>
                            <span class="flex items-center gap-1">
                                👨‍🏫&nbsp;{{ $materi->user->name ?? 'Guru' }}
                            </span>
                        </div>
                    </div>

                    {{-- ========================================== --}}
                    {{-- AREA MEDIA (VIDEO / PDF / GAMBAR) --}}
                    {{-- ========================================== --}}
                    <div class="mb-12 space-y-8">
                        
                        {{-- 1. VIDEO YOUTUBE (Tampil Jika youtube_link tidak kosong) --}}
                        @if(!empty($materi->youtube_link))
                            @php
                                $videoId = "";
                                if(preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $materi->youtube_link, $match)) {
                                    $videoId = $match[1];
                                }
                            @endphp
                            @if($videoId)
                                <div class="rounded-[2rem] overflow-hidden shadow-2xl border-8 border-emerald-50 aspect-video">
                                    <iframe class="w-full h-full" src="https://www.youtube.com/embed/{{ $videoId }}" frameborder="0" allowfullscreen></iframe>
                                </div>
                            @endif
                        @endif

                        {{-- CEK FILE UPLOAD LOKAL --}}
                        @if(!empty($materi->file_path))
                            @php
                                // Ambil ekstensi file dari path (misal: pdf, mp4, jpg)
                                $ext = strtolower(pathinfo($materi->file_path, PATHINFO_EXTENSION));
                            @endphp

                            {{-- 2. VIDEO LOKAL (.mp4, .webm) --}}
                            @if(in_array($ext, ['mp4', 'webm', 'ogg']))
                                <div class="rounded-[2rem] overflow-hidden shadow-2xl border-8 border-emerald-50 bg-black">
                                    <video controls class="w-full max-h-[500px]">
                                        <source src="{{ asset('storage/' . $materi->file_path) }}" type="video/mp4">
                                        Browser kamu tidak mendukung video.
                                    </video>
                                </div>

                            {{-- 3. GAMBAR (.jpg, .png, .jpeg) --}}
                            @elseif(in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp']))
                                <div class="rounded-[2rem] overflow-hidden shadow-xl border-4 border-emerald-50 text-center bg-slate-100 p-2">
                                    <img src="{{ asset('storage/' . $materi->file_path) }}" alt="{{ $materi->judul }}" class="mx-auto max-h-[500px] rounded-xl object-contain">
                                </div>

                            {{-- 4. PDF VIEWER (.pdf) --}}
                            @elseif($ext == 'pdf')
                                <div class="rounded-[2rem] overflow-hidden shadow-2xl border-2 border-emerald-100 bg-slate-100">
                                    <iframe src="{{ asset('storage/' . $materi->file_path) }}" class="w-full h-[600px]" frameborder="0"></iframe>
                                </div>
                                <div class="text-center mt-4">
                                    <a href="{{ asset('storage/' . $materi->file_path) }}" target="_blank" class="text-xs font-bold text-emerald-600 underline">Buka PDF Layar Penuh ↗️</a>
                                </div>

                            {{-- 5. DOKUMEN LAIN (.doc, .docx, .xls, dll) --}}
                            @else
                                 <div class="p-8 rounded-[2rem] border-2 border-dashed border-slate-300 bg-slate-50 text-center shadow-inner">
                                    <span class="text-5xl mb-4 block">📁</span>
                                    <h5 class="font-bold text-slate-700 mb-4 text-lg">Materi ini memiliki lampiran dokumen</h5>
                                    <a href="{{ asset('storage/' . $materi->file_path) }}" download class="inline-flex items-center gap-2 px-8 py-4 bg-emerald-600 text-white rounded-2xl font-black text-sm uppercase shadow-lg hover:bg-emerald-500 transition-all hover:-translate-y-1">
                                        Unduh File Lampiran 📥
                                    </a>
                                 </div>
                            @endif
                        @endif
                    </div>

                    {{-- Konten Teks Artikel --}}
                    @if(!empty($materi->konten))
                        <div class="relative mb-12">
                            {{-- Label --}}
                            <div class="flex items-center gap-3 mb-5">
                                <div class="w-1 h-8 bg-gradient-to-b from-indigo-500 to-violet-500 rounded-full"></div>
                                <h3 class="font-black text-lg text-slate-700 uppercase tracking-widest">Penjelasan Materi</h3>
                            </div>
                            {{-- Konten prose — styled oleh @tailwindcss/typography --}}
                            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-6 md:p-10 lg:p-12">
                                {{-- konten-materi = custom CSS prose (fallback) --}}
                                {{-- prose prose-slate = Tailwind Typography plugin (aktif setelah npm run build) --}}
                                <div class="konten-materi prose prose-slate lg:prose-lg max-w-none">
                                    {!! $materi->konten !!}
                                </div>
                            </div>
                        </div>
                    @endif

                    {{-- ASISTEN AI (MIBI) --}}
                    <div class="mt-16 bg-slate-900 rounded-[3rem] p-1 md:p-2 shadow-2xl relative overflow-hidden" id="ai-section">
                        <div class="bg-slate-800/50 backdrop-blur-xl rounded-[2.8rem] p-6 md:p-10 border border-white/10 relative z-10">
                            <div class="flex flex-col md:flex-row gap-10">
                                <div class="w-full md:w-1/3 flex flex-col items-center md:items-start text-center md:text-left">
                                    <div class="relative mb-6">
                                        <div class="w-20 h-20 md:w-24 md:h-24 bg-gradient-to-br from-emerald-400 to-teal-600 rounded-[2rem] flex items-center justify-center text-5xl shadow-lg border-2 border-white/20 animate-float">🤖</div>
                                        <div class="absolute -bottom-2 -right-2 bg-green-500 w-6 h-6 rounded-full border-4 border-slate-800 animate-pulse"></div>
                                    </div>
                                    <h3 class="text-2xl font-black text-white mb-2">Asisten Cerdas</h3>
                                    <p class="text-emerald-400 font-bold text-xs uppercase tracking-[0.2em] mb-6">Pendamping Belajarmu</p>
                                    <button type="button" onclick="kirimPesanKeAI('Ringkaskan materi ini dengan bahasa yang sangat sederhana.')" class="w-full bg-emerald-600 hover:bg-emerald-500 text-white font-black py-4 px-6 rounded-2xl shadow-lg transition-all border-b-4 border-emerald-800 active:border-0 active:translate-y-1 flex items-center justify-center gap-2 text-xs uppercase tracking-widest">
                                        ✨ Ringkas Materi
                                    </button>
                                </div>

                                <div class="w-full md:w-2/3 flex flex-col h-[500px]">
                                    <div id="chat-box" class="flex-grow overflow-y-auto pr-4 space-y-6 mb-6 scrollbar-custom p-2 text-left">
                                        <div class="flex items-start gap-4">
                                            <div class="w-10 h-10 bg-emerald-500 rounded-2xl flex items-center justify-center text-xl shrink-0">🤖</div>
                                            <div class="bg-slate-700/80 text-emerald-50 p-5 rounded-3xl rounded-tl-none text-sm font-medium leading-relaxed border border-white/5 max-w-[85%]">
                                                Assalamu'alaikum <strong>{{ explode(' ', Auth::user()->name)[0] }}</strong>! Ada yang bisa saya bantu jelaskan?
                                            </div>
                                        </div>
                                    </div>

                                    <form id="ai-form" onsubmit="handleChatSubmit(event)" class="relative group">
                                        <input type="text" id="ai-input" placeholder="Tanyakan kesulitanmu..." class="w-full bg-slate-900/80 border-2 border-slate-700 rounded-[2rem] text-white px-8 py-5 pr-20 focus:border-emerald-500 focus:ring-0 font-bold" required autocomplete="off">
                                        <button type="submit" id="ai-btn" class="absolute right-3 top-2 bottom-2 bg-emerald-500 text-white px-6 rounded-[1.5rem] font-black hover:bg-emerald-400 flex items-center gap-2">
                                            🚀
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- TOMBOL AKSI --}}
                    <div class="mt-16 text-center">
                        <div class="bg-emerald-50 p-8 rounded-[3rem] border-2 border-dashed border-emerald-200">
                            @if($sudahMengerjakan)
                                <div class="bg-white p-6 rounded-2xl shadow-sm border border-emerald-200">
                                    <span class="text-4xl mb-2 block">🏆</span>
                                    <h4 class="text-xl font-black text-emerald-700 uppercase">Sudah Selesai!</h4>
                                    <p class="text-slate-500 font-bold text-sm">Kamu sudah menyelesaikan misi ini. Hebat!</p>
                                </div>
                            @elseif($materi->soals_count > 0)
                                <h4 class="text-xl font-black text-slate-800 mb-6 uppercase tracking-widest">Sudah Paham? Yuk Uji Ilmu!</h4>
                                <a href="{{ route('siswa.kuis', $materi->id) }}" class="inline-flex items-center gap-4 px-12 py-6 rounded-[2rem] font-black text-xl text-white shadow-2xl hover:scale-105 transition-all duration-300 border-b-8 border-blue-800 bg-blue-500">
                                    Mulai Kuis 📝
                                </a>
                            @else
                                <form action="{{ route('siswa.materi.selesai', $materi->id) }}" method="POST">
                                    @csrf
                                    <button type="submit" class="px-12 py-6 rounded-[2rem] font-black text-xl text-white shadow-2xl hover:scale-105 transition-all duration-300 border-b-8 border-emerald-800 bg-emerald-500">
                                        Selesai Belajar ✅
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    {{-- Script AI Mibi --}}
    <script>
        const chatBox = document.getElementById('chat-box');
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

        function handleChatSubmit(e) {
            e.preventDefault();
            const pesan = aiInput.value.trim();
            if(pesan) { kirimPesanKeAI(pesan); aiInput.value = ''; }
        }

        function kirimPesanKeAI(pesan) {
            chatBox.innerHTML += `<div class="flex items-start gap-4 flex-row-reverse mb-4"><div class="w-10 h-10 bg-emerald-200 rounded-2xl flex items-center justify-center text-xl shrink-0">👦</div><div class="bg-emerald-600 text-white p-5 rounded-3xl rounded-tr-none text-sm font-bold shadow-xl max-w-[85%] border-b-4 border-emerald-800 text-left">${pesan}</div></div>`;
            chatBox.scrollTop = chatBox.scrollHeight;

            const typingId = 'typing-' + Date.now();
            chatBox.innerHTML += `<div id="${typingId}" class="flex items-start gap-4 mb-4"><div class="w-10 h-10 bg-emerald-500 rounded-2xl flex items-center justify-center text-xl shrink-0 shadow-lg">🤖</div><div class="bg-slate-700/50 text-white p-5 rounded-3xl rounded-tl-none flex items-center gap-2"><div class="w-2 h-2 bg-emerald-400 rounded-full animate-bounce"></div><div class="w-2 h-2 bg-emerald-400 rounded-full animate-bounce" style="animation-delay: 0.2s"></div><div class="w-2 h-2 bg-emerald-400 rounded-full animate-bounce" style="animation-delay: 0.4s"></div></div></div>`;
            chatBox.scrollTop = chatBox.scrollHeight;

            aiBtn.disabled = true; aiInput.disabled = true;

            fetch("{{ route('siswa.ai.chat', $materi->id) }}", {
                method: "POST",
                headers: { "Content-Type": "application/json", "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute('content') },
                body: JSON.stringify({ pesan: pesan })
            })
            .then(res => res.json())
            .then(data => {
                document.getElementById(typingId).remove();
                const replyId = 'reply-' + Date.now();
                chatBox.innerHTML += `<div class="flex items-start gap-4 mb-4"><div class="w-10 h-10 bg-emerald-500 rounded-2xl flex items-center justify-center text-xl shrink-0 border border-white/20">🤖</div><div class="flex flex-col gap-1.5 max-w-[85%]"><div id="${replyId}" class="bg-slate-700/80 text-emerald-50 p-5 rounded-3xl rounded-tl-none text-sm font-medium shadow-xl border border-white/5 leading-relaxed text-left">${data.reply}</div><button type="button" onclick="bacakanSuara(document.getElementById('${replyId}').innerText)" class="self-start text-[10px] font-black text-emerald-400 hover:text-emerald-300 flex items-center gap-1">🔊 Dengarkan Lagi</button></div></div>`;
                chatBox.scrollTop = chatBox.scrollHeight;
                bacakanSuara(document.getElementById(replyId).innerText);
            })
            .catch(error => {
                if(document.getElementById(typingId)) document.getElementById(typingId).remove();
                chatBox.innerHTML += `<div class="flex items-start gap-4 mb-4"><div class="w-10 h-10 bg-rose-500 rounded-2xl flex items-center justify-center text-xl shrink-0">🤖</div><div class="bg-rose-900/50 text-rose-200 p-5 rounded-3xl rounded-tl-none text-sm font-medium max-w-[85%]">Mibi sedang tidak bisa dihubungi. Coba lagi beberapa saat ya! 🙏</div></div>`;
                chatBox.scrollTop = chatBox.scrollHeight;
            })
            .finally(() => { aiBtn.disabled = false; aiInput.disabled = false; aiInput.focus(); });
        }

        // =====================================================
        // READING PROGRESS BAR + BACK TO TOP
        // =====================================================
        const progressBar  = document.getElementById('reading-progress');
        const backToTopBtn = document.getElementById('back-to-top');

        window.addEventListener('scroll', () => {
            const scrollTop  = window.scrollY;
            const docHeight  = document.documentElement.scrollHeight - window.innerHeight;
            const pct        = docHeight > 0 ? Math.min((scrollTop / docHeight) * 100, 100) : 0;
            progressBar.style.width = pct + '%';

            if (scrollTop > 400) {
                backToTopBtn.style.opacity      = '1';
                backToTopBtn.style.transform    = 'translateY(0)';
                backToTopBtn.style.pointerEvents = 'auto';
            } else {
                backToTopBtn.style.opacity      = '0';
                backToTopBtn.style.transform    = 'translateY(16px)';
                backToTopBtn.style.pointerEvents = 'none';
            }
        }, { passive: true });

        // =====================================================
        // ESTIMATED READING TIME
        // =====================================================
        (function() {
            const el = document.querySelector('.konten-materi');
            const rtEl = document.getElementById('read-time');
            if (!el || !rtEl) return;
            const words = el.innerText.trim().split(/\s+/).filter(w => w.length > 0).length;
            const mins  = Math.max(1, Math.round(words / 180)); // 180 kata/menit
            rtEl.textContent = mins + ' menit';
        })();
    </script>
</x-app-layout>