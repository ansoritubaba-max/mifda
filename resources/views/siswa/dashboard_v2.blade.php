<x-app-layout>
    <style>
        [x-cloak] { display: none !important; }
        .scrollbar-hide::-webkit-scrollbar { display: none; }
        .scrollbar-hide { -ms-overflow-style: none; scrollbar-width: none; }
        /* Kustomisasi scrollbar untuk chat agar lebih elegan */
        .scrollbar-custom::-webkit-scrollbar { width: 6px; }
        .scrollbar-custom::-webkit-scrollbar-track { background: transparent; }
        .scrollbar-custom::-webkit-scrollbar-thumb { background-color: #cbd5e1; border-radius: 10px; }
    </style>

    <x-slot name="header">
        <div class="flex flex-col sm:flex-row justify-between sm:items-center gap-4 relative z-10">
            <div class="flex items-center gap-4">
                <div class="w-16 h-16 md:w-20 md:h-20 shrink-0 relative group">
                    @if(Auth::user()->avatar)
                        <img src="{{ asset('storage/' . Auth::user()->avatar) }}"
                            alt="Avatar"
                            class="w-full h-full rounded-[1.25rem] object-cover shadow-lg border-2 border-white/30 group-hover:scale-105 transition-transform duration-300">
                    @else
                        <div class="w-full h-full bg-gradient-to-br from-indigo-400 to-violet-500 text-white rounded-[1.25rem] flex items-center justify-center text-3xl font-black shadow-lg border-2 border-white/30 group-hover:scale-105 transition-transform duration-300">
                            {{ substr(Auth::user()->name, 0, 1) }}
                        </div>
                    @endif
                    <div class="absolute -bottom-1 -right-1 w-5 h-5 bg-cyan-400 border-2 border-indigo-900 rounded-full animate-pulse shadow-sm"></div>
                </div>

                <div>
                    <h2 class="font-black text-2xl md:text-3xl text-white tracking-tight drop-shadow-sm">Halo, {{ explode(' ', Auth::user()->name)[0] }}! 👋</h2>
                    <div class="flex flex-wrap items-center gap-2 mt-2">
                        <span class="bg-white/10 text-indigo-100 font-bold text-xs px-3 py-1.5 rounded-xl border border-white/20 flex items-center gap-1.5 backdrop-blur-sm">
                            📍 {{ Auth::user()->kelas->nama_kelas ?? 'Belum Ada Kelas' }}
                        </span>
                        <span class="bg-gradient-to-r from-amber-400/20 to-yellow-400/20 text-yellow-200 font-bold text-xs px-3 py-1.5 rounded-xl border border-yellow-400/30 flex items-center gap-1.5">
                            ⚡ {{ Auth::user()->xp }} XP
                        </span>
                    </div>
                </div>
            </div>

            @if(Auth::user()->siap_lulus)
                <div class="bg-gradient-to-r from-cyan-500 to-indigo-500 p-4 px-6 rounded-2xl flex items-center gap-4 shadow-lg shadow-indigo-900/30 mt-2 sm:mt-0 border border-white/20 transform hover:scale-105 transition-all">
                    <span class="text-3xl animate-bounce drop-shadow-md">🎓</span>
                    <div>
                        <p class="text-[10px] font-black text-indigo-100 uppercase tracking-widest opacity-90">Status Belajar</p>
                        <p class="text-base font-black text-white drop-shadow-sm">LAYAK LULUS! 🎉</p>
                    </div>
                </div>
            @endif
        </div>
    </x-slot>

    <div class="py-6 md:py-10 bg-slate-50 min-h-screen relative overflow-hidden">
        
        <div class="absolute top-20 left-0 w-96 h-96 bg-indigo-200 rounded-full mix-blend-multiply filter blur-3xl opacity-15 pointer-events-none"></div>
        <div class="absolute bottom-20 right-0 w-96 h-96 bg-violet-200 rounded-full mix-blend-multiply filter blur-3xl opacity-15 pointer-events-none"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 md:gap-8">
                
                <div class="lg:col-span-8 space-y-6 md:space-y-8">
                    
                    <div class="bg-gradient-to-r from-indigo-600 via-violet-600 to-indigo-700 p-8 md:p-10 rounded-[2rem] md:rounded-[2.5rem] shadow-2xl shadow-indigo-300/30 text-white flex flex-col md:flex-row justify-between items-center gap-8 relative overflow-hidden border border-white/10">
                        <div class="absolute right-0 top-0 w-64 h-64 bg-cyan-400 opacity-10 rounded-full blur-3xl -mr-20 -mt-20"></div>
                        <div class="absolute left-0 bottom-0 w-40 h-40 bg-violet-400 opacity-20 rounded-full blur-2xl -ml-10 -mb-10"></div>
                        {{-- Dot grid pattern --}}
                        <div class="absolute inset-0 opacity-[0.04]" style="background-image: radial-gradient(#fff 1px, transparent 1px); background-size: 20px 20px;"></div>

                        <div class="relative z-10 w-full text-center md:text-left">
                            <p class="text-indigo-200 text-xs font-black uppercase tracking-[0.2em] mb-2">Platform Belajar Digital</p>
                            <h4 class="text-3xl md:text-4xl font-black mb-3 tracking-tight drop-shadow-md">Siap Belajar Hari Ini? 🚀</h4>
                            <p class="text-indigo-100 font-medium text-sm md:text-base opacity-90">Selesaikan misi belajarmu dan kumpulkan poin XP sebanyak mungkin!</p>
                        </div>
                        <a href="{{ route('siswa.belajar') }}" class="relative z-10 w-full md:w-auto bg-white text-indigo-600 px-8 py-4 rounded-2xl font-black shadow-xl hover:shadow-2xl hover:-translate-y-1 transition-all text-center whitespace-nowrap flex items-center justify-center gap-2 text-lg">
                            Buka Materi 📖
                        </a>
                    </div>

                    <div class="bg-white rounded-[2rem] p-6 md:p-8 shadow-sm border border-gray-100 hover:shadow-md transition-shadow">
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-6 md:mb-8 gap-4">
                            <div>
                                <h3 class="font-black text-2xl text-gray-800 flex items-center gap-3">
                                    <span class="p-2 bg-blue-50 rounded-xl text-blue-500">🎮</span> Zona Main & Belajar
                               </h3>
                                <p class="text-sm font-bold text-gray-400 mt-2">Mainkan misi interaktif di bawah untuk dapat XP!</p>
                            </div>
                            <a href="{{ route('siswa.belajar') }}" class="shrink-0 text-xs font-black text-indigo-600 hover:text-white bg-indigo-50 hover:bg-indigo-600 px-6 py-3 rounded-xl transition-all text-center uppercase tracking-widest shadow-sm">
                                Semua Misi 🚀
                            </a>
                        </div>
                        
                        <div class="space-y-4">
                            @forelse($games as $game)
                                <div class="bg-slate-50 p-4 rounded-2xl border border-transparent hover:bg-white hover:shadow-lg hover:border-indigo-200 transition-all flex flex-col sm:flex-row sm:items-center gap-4 md:gap-6 group">
                                    <div class="w-full sm:w-32 h-24 bg-white rounded-[1.25rem] flex items-center justify-center text-4xl shadow-sm border border-gray-100 shrink-0 group-hover:scale-105 transition-transform group-hover:rotate-3">
                                        🎮
                                    </div>
                                    
                                    <div class="flex-grow">
                                        <span class="px-3 py-1.5 rounded-lg text-[10px] font-black text-white mb-2 inline-block tracking-wider uppercase shadow-sm" style="background-color: {{ $game->warna_tema ?? '#10b981' }}">
                                            {{ $game->nama_mapel }}
                                        </span>
                                        <h4 class="font-black text-xl text-gray-800 leading-snug line-clamp-2 group-hover:text-indigo-600 transition-colors">{{ $game->judul }}</h4>
                                    </div>
                                    
                                    <a href="{{ route('siswa.game.play', $game->id) }}" class="shrink-0 bg-white border-2 border-gray-100 text-gray-600 font-black px-8 py-3.5 rounded-xl hover:bg-indigo-600 hover:border-indigo-600 hover:text-white transition-all text-sm tracking-widest uppercase text-center shadow-sm w-full sm:w-auto">
                                        Mainkan
                                    </a>
                                </div>
                            @empty
                                <div class="bg-slate-50 p-12 rounded-3xl text-center border-2 border-dashed border-gray-200">
                                    <span class="text-6xl block mb-4 opacity-30 grayscale">😴</span>
                                    <p class="text-gray-500 font-black text-xl">Belum ada game interaktif</p>
                                </div>
                            @endforelse
                        </div>
                    </div>

                    <div class="bg-white rounded-[2rem] p-6 md:p-8 shadow-sm border border-gray-100 hover:shadow-md transition-shadow">
                        <h3 class="font-black text-2xl mb-6 flex items-center gap-3 text-gray-800">
                            <span class="p-2 bg-emerald-50 rounded-xl text-emerald-500">📈</span> Rapor Kuis Terakhir
                        </h3>
                        <div class="space-y-4">
                            @forelse($nilais as $n)
                                <div class="flex items-center justify-between p-4 md:p-5 bg-slate-50 rounded-2xl border border-transparent hover:bg-white hover:border-indigo-100 hover:shadow-md transition-all group">
                                    <div class="flex items-center gap-4 overflow-hidden">
                                        <div class="w-12 h-12 shrink-0 bg-white shadow-sm text-gray-500 rounded-xl flex items-center justify-center font-bold border border-gray-100 group-hover:scale-110 transition-transform">📝</div>
                                        <div class="truncate pr-4">
                                            <h5 class="font-bold text-gray-800 text-base md:text-lg truncate group-hover:text-indigo-600 transition-colors">{{ $n->materi->judul }}</h5>
                                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mt-1">{{ $n->created_at->diffForHumans() }}</p>
                                        </div>
                                    </div>
                                    <div class="bg-white px-4 py-2 rounded-xl shadow-sm border border-gray-50 shrink-0">
                                        <span class="font-black text-2xl md:text-3xl {{ $n->skor >= 75 ? 'text-emerald-500' : 'text-orange-500' }}">
                                            {{ $n->skor }}
                                        </span>
                                    </div>
                                </div>
                            @empty
                                <div class="text-center py-8 text-gray-400 font-bold text-sm bg-slate-50 rounded-2xl border border-dashed border-gray-200">Belum ada nilai kuis yang terkumpul.</div>
                            @endforelse
                        </div>
                    </div>

                </div>

                <div class="lg:col-span-4 space-y-6 md:space-y-8">
                    <div class="lg:sticky lg:top-8 space-y-6 md:space-y-8">
                        
                        <div class="bg-white p-6 md:p-8 rounded-[2rem] shadow-sm border border-gray-100 relative overflow-hidden group hover:shadow-md transition-shadow">
                            <div class="absolute right-0 top-0 w-24 h-24 bg-amber-100 rounded-full blur-2xl opacity-50 -mr-10 -mt-10 group-hover:scale-150 transition-transform duration-700"></div>
                            <h4 class="font-black text-xl text-gray-800 mb-1 relative z-10">Lencana Saya 🏆</h4>
                            <p class="text-xs text-gray-400 font-bold mb-5 relative z-10">Kumpulkan dari misi kuis!</p>
                            <div class="flex flex-wrap gap-3 relative z-10">
                                @forelse($lencanas as $lencana)
                                    <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-amber-50 to-yellow-100 border border-amber-200 flex items-center justify-center text-3xl shadow-sm hover:scale-110 hover:-translate-y-2 transition-all cursor-help" title="{{ $lencana->nama_lencana }}">
                                        {{ $lencana->icon ?? '🏅' }}
                                    </div>
                                @empty
                                    <div class="flex flex-col items-center justify-center w-full py-4 text-center">
                                        <div class="w-14 h-14 rounded-2xl bg-slate-50 border-2 border-dashed border-slate-200 flex items-center justify-center text-2xl opacity-40 mb-2">🔒</div>
                                        <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest">Belum ada lencana</p>
                                        <p class="text-[10px] text-slate-300 mt-1">Selesaikan kuis dengan sempurna!</p>
                                    </div>
                                @endforelse
                            </div>
                        </div>

                        <div x-data="{ chatOpen: false, hasNotif: true }" class="space-y-4">
                            
                            <div @click="chatOpen = !chatOpen; hasNotif = false" class="bg-white p-5 md:p-6 rounded-[2rem] shadow-sm border border-gray-100 flex items-center justify-between cursor-pointer hover:shadow-lg hover:border-indigo-200 transition-all group relative">
                                <div class="flex items-center gap-4">
                                    <div class="w-14 h-14 bg-gradient-to-br from-indigo-50 to-violet-100 text-indigo-600 rounded-2xl flex items-center justify-center text-2xl font-black group-hover:scale-110 group-hover:-rotate-6 transition-all shadow-sm border border-indigo-100">
                                        💬
                                    </div>
                                    <div>
                                        <h4 class="font-black text-gray-800 text-xl leading-tight">Ruang Guru</h4>
                                        <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mt-1">Tanya materi di sini</p>
                                    </div>
                                </div>
                                
                                <div class="relative w-10 h-10 flex items-center justify-center bg-slate-50 rounded-full group-hover:bg-indigo-50 transition-colors">
                                    <svg x-show="!chatOpen" class="w-6 h-6 text-gray-400 group-hover:text-indigo-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"></path></svg>
                                    <svg x-show="chatOpen" x-cloak class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 15l7-7 7 7"></path></svg>
                                </div>

                                <span x-show="hasNotif && !chatOpen" class="absolute top-5 right-5 flex h-4 w-4">
                                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                                    <span class="relative inline-flex rounded-full h-4 w-4 bg-red-500 border-2 border-white"></span>
                                </span>
                            </div>

                            <div x-show="chatOpen" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-[-10px] scale-95" x-transition:enter-end="opacity-100 translate-y-0 scale-100" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 scale-100" x-transition:leave-end="opacity-0 translate-y-[-10px] scale-95" x-cloak class="bg-white rounded-[2rem] shadow-2xl border border-gray-100 flex flex-col h-[550px] overflow-hidden origin-top">
                                
                                <div class="bg-gradient-to-r from-indigo-700 to-violet-700 text-white flex flex-col relative z-10 shadow-md">
                                    
                                    @if(isset($gurus) && $gurus->count() > 1)
                                        <div class="flex gap-2 overflow-x-auto px-5 pt-5 pb-2 scrollbar-hide">
                                            @foreach($gurus as $g)
                                                @php $isActive = request('guru_id') == $g->id || (!request('guru_id') && $loop->first); @endphp
                                                <a href="?guru_id={{ $g->id }}" class="flex shrink-0 items-center gap-2 p-1.5 pr-4 rounded-full transition-all border {{ $isActive ? 'bg-white text-indigo-700 border-white shadow-md' : 'bg-white/10 text-indigo-50 border-white/20 hover:bg-white/20' }}">
                                                    @if($g->avatar)
                                                        {{-- 🚀 PERBAIKAN: Foto daftar guru ditarik dari Local Storage menggunakan asset() --}}
                                                        <img src="{{ asset('storage/' . $g->avatar) }}" class="w-8 h-8 rounded-full object-cover">
                                                    @else
                                                        <div class="w-8 h-8 rounded-full flex items-center justify-center font-black text-xs {{ $isActive ? 'bg-indigo-100 text-indigo-700' : 'bg-white/20 text-white' }}">
                                                            {{ substr($g->name, 0, 1) }}
                                                        </div>
                                                    @endif
                                                    <span class="text-[11px] font-black tracking-wide truncate max-w-[90px]">{{ explode(' ', $g->name)[0] }}</span>
                                                </a>
                                            @endforeach
                                        </div>
                                    @endif

                                    <div class="p-5 {{ isset($gurus) && $gurus->count() > 1 ? 'pt-2' : '' }} flex items-center gap-4">
                                        <div class="w-12 h-12 shrink-0 relative">
                                            @if(isset($guru) && $guru->avatar)
                                                {{-- 🚀 PERBAIKAN: Foto guru terpilih ditarik dari Local Storage menggunakan asset() --}}
                                                <img src="{{ asset('storage/' . $guru->avatar) }}" class="w-full h-full rounded-[1rem] object-cover shadow-sm border-2 border-white/20">
                                            @else
                                                <div class="w-full h-full bg-white/20 backdrop-blur-sm rounded-[1rem] flex items-center justify-center text-xl font-black border-2 border-white/30 shadow-inner">
                                                    {{ isset($guru) ? substr($guru->name, 0, 1) : '👨‍🏫' }}
                                                </div>
                                            @endif
                                            @if(isset($guru))
                                                <span class="absolute -bottom-1 -right-1 w-3.5 h-3.5 bg-cyan-400 border-2 border-indigo-700 rounded-full animate-pulse"></span>
                                            @endif
                                        </div>
                                        <div>
                                            <h3 class="font-black text-base leading-tight text-white drop-shadow-sm">{{ $guru->name ?? 'Pilih Guru' }}</h3>
                                            <p class="text-[10px] text-indigo-200 font-bold mt-1 tracking-widest uppercase flex items-center gap-1">
                                                <span class="w-1.5 h-1.5 bg-green-400 rounded-full inline-block"></span> Online
                                            </p>
                                        </div>
                                    </div>
                                </div>
                                
                                <div id="chat-container" class="flex-grow p-5 overflow-y-auto space-y-5 bg-slate-50 flex flex-col scrollbar-custom relative">
                                    <div class="absolute inset-0 opacity-[0.03] pointer-events-none" style="background-image: radial-gradient(#000 1px, transparent 1px); background-size: 16px 16px;"></div>

                                    <div class="text-center my-2 relative z-10">
                                        <span class="bg-slate-100/80 text-slate-400 text-[10px] font-bold px-4 py-1.5 rounded-xl border border-slate-200/50">Percakapan hanya terlihat oleh kamu & guru 💬</span>
                                    </div>

                                    @forelse($chats as $chat)
                                        @if($chat->sender_id == Auth::id())
                                            <div class="bg-gradient-to-br from-indigo-500 to-violet-600 text-white p-3.5 px-5 rounded-2xl rounded-tr-sm shadow-md shadow-indigo-200/50 self-end max-w-[85%] relative z-10 group">
                                                <p class="text-sm font-medium leading-relaxed">{{ $chat->pesan }}</p>
                                                <p class="text-[9px] text-indigo-100 font-bold mt-1.5 text-right flex items-center justify-end gap-1">
                                                    {{ $chat->created_at->format('H:i') }} 
                                                    <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path></svg>
                                                </p>
                                            </div>
                                        @else
                                            <div class="bg-white text-gray-800 p-3.5 px-5 rounded-2xl rounded-tl-sm shadow-md border border-gray-100 self-start max-w-[85%] relative z-10">
                                                <p class="text-sm font-medium leading-relaxed">{{ $chat->pesan }}</p>
                                                <p class="text-[9px] text-gray-400 font-bold mt-1.5">{{ $chat->created_at->format('H:i') }}</p>
                                            </div>
                                        @endif
                                    @empty
                                        <div class="text-center py-12 text-gray-400 my-auto flex flex-col items-center relative z-10">
                                            <div class="w-20 h-20 bg-white rounded-full flex items-center justify-center text-4xl shadow-sm border border-gray-100 mb-4 animate-bounce">👋</div>
                                            <p class="text-base font-black text-gray-700">Belum ada pesan.</p>
                                            <p class="text-xs mt-2 px-6 font-medium leading-relaxed">Tanyakan materi yang tidak kamu pahami ke Bapak/Ibu Guru sekarang.</p>
                                        </div>
                                    @endforelse
                                </div>
                                
                                <form id="form-chat-siswa" action="{{ route('siswa.chat.kirim') }}" method="POST" class="p-4 bg-white border-t border-gray-100 flex items-end gap-3 z-10 shadow-[0_-10px_20px_-10px_rgba(0,0,0,0.05)]">
                                    @csrf
                                    @if(isset($guru))
                                        <input type="hidden" name="receiver_id" value="{{ $guru->id }}">
                                    @endif

                                    <div class="flex-grow bg-slate-50 border border-slate-200 rounded-2xl focus-within:ring-2 focus-within:ring-indigo-500 focus-within:border-indigo-500 transition-all shadow-inner">
                                        <textarea name="pesan" rows="1" placeholder="Ketik pesan..." 
                                            class="w-full border-0 bg-transparent rounded-2xl text-sm focus:ring-0 focus:outline-none font-medium px-4 py-3.5 resize-none overflow-hidden placeholder-slate-400" 
                                            required autocomplete="off" {{ !isset($guru) ? 'disabled' : '' }}
                                            oninput="this.style.height = ''; this.style.height = this.scrollHeight + 'px'"
                                            style="max-height: 120px; min-height: 48px;"></textarea>
                                    </div>
                                    
                                    <button type="submit" class="shrink-0 w-12 h-12 bg-gradient-to-br from-indigo-500 to-violet-600 text-white rounded-2xl flex items-center justify-center font-black shadow-lg shadow-indigo-200 hover:shadow-xl hover:scale-105 hover:-translate-y-1 transition-all {{ !isset($guru) ? 'opacity-50 cursor-not-allowed grayscale' : '' }}" {{ !isset($guru) ? 'disabled' : '' }}>
                                        <svg class="w-5 h-5 ml-1 transform rotate-45" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
                                    </button>
                                </form>
                            </div>

                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            let scrollPos = sessionStorage.getItem('pageScrollPos');
            if (scrollPos !== null) {
                window.scrollTo(0, parseInt(scrollPos));
                sessionStorage.removeItem('pageScrollPos'); 
            }

            let chatContainer = document.getElementById("chat-container");
            if(chatContainer) {
                chatContainer.scrollTop = chatContainer.scrollHeight;
            }
        });

        window.addEventListener('beforeunload', function() {
            sessionStorage.setItem('pageScrollPos', window.scrollY);
        });
    </script>

    <script>
    // [FIX] Chat AJAX — cegah tab baru & panel tertutup saat kirim pesan
    function escapeHtml(str) {
        const div = document.createElement("div");
        div.appendChild(document.createTextNode(str));
        return div.innerHTML;
    }

    document.addEventListener("DOMContentLoaded", function () {
        const form = document.getElementById("form-chat-siswa");
        if (!form) return;

        form.addEventListener("submit", function (e) {
            e.preventDefault();

            const textarea   = form.querySelector('textarea[name="pesan"]');
            const pesan      = textarea.value.trim();
            const receiverId = form.querySelector('input[name="receiver_id"]').value;
            const csrfToken  = form.querySelector('input[name="_token"]').value;
            const container  = document.getElementById("chat-container");
            const btnKirim   = form.querySelector('button[type="submit"]');

            if (!pesan) return;
            btnKirim.disabled = true;

            fetch(form.action, {
                method: "POST",
                headers: { "Content-Type": "application/json", "X-CSRF-TOKEN": csrfToken, "Accept": "application/json" },
                body: JSON.stringify({ receiver_id: receiverId, pesan: pesan }),
            })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    const bubble = document.createElement("div");
                    bubble.className = "self-end bg-gradient-to-br from-indigo-500 to-violet-600 text-white p-4 rounded-[1.5rem] rounded-tr-sm shadow-md max-w-[85%]";
                    bubble.innerHTML = '<p class="text-sm font-medium leading-relaxed">' + escapeHtml(data.pesan) + '</p>' +
                        '<div class="flex items-center justify-end gap-1 mt-2"><p class="text-[9px] text-emerald-100 font-black uppercase">' + data.waktu + '</p><span class="text-[10px]">✓</span></div>';
                    container.appendChild(bubble);
                    container.scrollTop = container.scrollHeight;
                    textarea.value = "";
                    textarea.style.height = "";
                }
            })
            .catch(() => {
                const errEl = document.createElement("p");
                errEl.className = "text-center text-xs text-red-400 font-bold py-2";
                errEl.textContent = "❌ Gagal mengirim pesan. Coba lagi.";
                container.appendChild(errEl);
                container.scrollTop = container.scrollHeight;
            })
            .finally(() => { btnKirim.disabled = false; });
        });
    });
    </script>
</x-app-layout>