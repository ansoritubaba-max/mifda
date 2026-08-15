<x-app-layout>
    <style>
        .scrollbar-chat::-webkit-scrollbar { width: 6px; }
        .scrollbar-chat::-webkit-scrollbar-track { background: transparent; }
        .scrollbar-chat::-webkit-scrollbar-thumb { background-color: #cbd5e1; border-radius: 10px; }
        .scrollbar-chat::-webkit-scrollbar-thumb:hover { background-color: #94a3b8; }
    </style>

    <x-slot name="header">
        <div class="flex items-center gap-3 md:gap-4 relative z-10">
            <a href="{{ route('guru.dashboard') }}" class="w-10 h-10 md:w-12 md:h-12 bg-white rounded-xl md:rounded-2xl shadow-sm border border-slate-200 hover:bg-slate-50 hover:shadow-md transition-all transform hover:-translate-x-1 flex items-center justify-center text-slate-500 hover:text-emerald-600 shrink-0 group">
                <span class="group-hover:-translate-x-1 transition-transform">⬅️</span>
            </a>
            
            <h2 class="font-black text-xl md:text-3xl text-white tracking-tight flex items-center gap-2 md:gap-3">
                <span class="text-2xl md:text-3xl bg-white/10 p-2 rounded-xl md:rounded-2xl border border-white/20">💬</span>
                Bimbingan Personal
            </h2>
        </div>
    </x-slot>

    <div class="py-6 md:py-8 bg-slate-50 min-h-screen relative overflow-hidden">
        
        <div class="absolute top-0 right-0 w-[30rem] h-[30rem] bg-emerald-200 rounded-full mix-blend-multiply filter blur-[100px] opacity-20 pointer-events-none -translate-y-1/2 translate-x-1/3"></div>
        <div class="absolute bottom-0 left-0 w-[30rem] h-[30rem] bg-blue-200 rounded-full mix-blend-multiply filter blur-[100px] opacity-20 pointer-events-none translate-y-1/3 -translate-x-1/3"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col md:flex-row gap-6 h-[calc(100vh-12rem)] min-h-[600px] relative z-10">
            
            <div class="w-full md:w-1/3 lg:w-1/4 bg-white/80 backdrop-blur-xl rounded-[2rem] md:rounded-[2.5rem] shadow-xl shadow-slate-200/50 border border-slate-100 flex flex-col relative overflow-hidden shrink-0 h-[30vh] md:h-full">
                
                <div class="p-5 md:p-6 border-b border-slate-100 bg-white z-10 shrink-0">
                    <h4 class="font-black text-slate-800 text-lg flex items-center gap-2">
                        <span>👥</span> Kontak Siswa
                    </h4>
                    <p class="font-bold text-slate-400 uppercase text-[9px] md:text-[10px] tracking-widest mt-1">Pilih siswa untuk bimbingan</p>
                </div>
                
                <div class="flex-grow overflow-y-auto p-4 space-y-2 scrollbar-chat">
                    @forelse($siswas as $s)
                        <a href="?siswa_id={{ $s->id }}" class="flex items-center gap-3 md:gap-4 p-3 md:p-4 rounded-2xl md:rounded-[1.5rem] transition-all duration-300 border {{ request('siswa_id') == $s->id ? 'bg-gradient-to-r from-emerald-500 to-teal-600 text-white shadow-lg shadow-emerald-200/50 border-transparent scale-[1.02]' : 'bg-white border-slate-100 hover:bg-slate-50 hover:border-emerald-200 text-slate-700' }}">
                            
                                <div class="relative shrink-0">
                                    @if($s->avatar)
                                        {{-- 🚀 PERBAIKAN: Menggunakan asset() agar foto siswa muncul dari storage lokal --}}
                                        <img src="{{ asset('storage/' . $s->avatar) }}" 
                                            alt="Avatar" 
                                            class="w-10 h-10 md:w-12 md:h-12 rounded-xl md:rounded-2xl object-cover shadow-sm border-2 {{ request('siswa_id') == $s->id ? 'border-white/30' : 'border-slate-100' }}">
                                    @else
                                        {{-- Tampilan inisial jika tidak ada foto --}}
                                        <div class="w-10 h-10 md:w-12 md:h-12 rounded-xl md:rounded-2xl flex items-center justify-center font-black text-lg md:text-xl shadow-sm border {{ request('siswa_id') == $s->id ? 'bg-white/20 border-white/20' : 'bg-slate-50 text-slate-500 border-slate-100' }}">
                                            {{ substr($s->name, 0, 1) }}
                                        </div>
                                    @endif
                                    
                                    {{-- Indikator Status Online/Aktif --}}
                                    <span class="absolute -bottom-0.5 -right-0.5 w-3 h-3 border-2 {{ request('siswa_id') == $s->id ? 'border-teal-600' : 'border-white' }} rounded-full bg-emerald-400"></span>
                                </div>

                            <div class="truncate">
                                <span class="font-black truncate text-sm md:text-base block">{{ $s->name }}</span>
                                <span class="text-[9px] md:text-[10px] font-bold uppercase tracking-widest {{ request('siswa_id') == $s->id ? 'text-emerald-100' : 'text-slate-400' }}">Siswa</span>
                            </div>
                        </a>
                    @empty
                        <div class="text-center py-10 flex flex-col items-center">
                            <span class="text-4xl mb-2 opacity-30 grayscale">👥</span>
                            <p class="text-slate-400 font-bold text-[10px] md:text-xs uppercase tracking-widest">Belum ada siswa di kelas</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <div class="w-full md:w-2/3 lg:w-3/4 bg-white/90 backdrop-blur-xl rounded-[2rem] md:rounded-[2.5rem] shadow-2xl shadow-slate-200/50 border border-slate-100 flex flex-col relative overflow-hidden h-[50vh] md:h-full">
                
                @if(request('siswa_id'))
                    @php $siswaTerpilih = $siswas->where('id', request('siswa_id'))->first(); @endphp
                        <div class="p-4 md:p-6 border-b border-slate-100 bg-white/95 backdrop-blur-md z-20 flex items-center justify-between gap-4 shadow-sm shrink-0">
                            <div class="flex items-center gap-4">
                                @if($siswaTerpilih->avatar)
                                    {{-- 🚀 PERBAIKAN: Menggunakan asset() untuk foto siswa di header chat --}}
                                    <img src="{{ asset('storage/' . $siswaTerpilih->avatar) }}" 
                                        alt="Avatar" 
                                        class="w-10 h-10 md:w-12 md:h-12 rounded-full object-cover shadow-sm shrink-0 border-2 border-emerald-100">
                                @else
                                    {{-- Tampilan inisial jika tidak ada foto --}}
                                    <div class="w-10 h-10 md:w-12 md:h-12 bg-gradient-to-br from-emerald-50 to-teal-50 text-emerald-600 border border-emerald-100 rounded-full flex items-center justify-center font-black text-lg shrink-0 shadow-sm">
                                        {{ substr($siswaTerpilih->name, 0, 1) }}
                                    </div>
                                @endif

                                <div>
                                    <h3 class="font-black text-slate-800 text-base md:text-lg leading-tight">{{ $siswaTerpilih->name ?? 'Siswa' }}</h3>
                                    <p class="text-[9px] md:text-[10px] font-bold text-emerald-500 uppercase tracking-widest mt-0.5 flex items-center gap-1.5">
                                        <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full animate-pulse"></span> Online
                                    </p>
                                </div>
                            </div>
                        </div>
                    
                    <div id="chat-container" class="flex-grow p-5 md:p-8 overflow-y-auto bg-slate-50/50 flex flex-col gap-5 scrollbar-chat relative">
                        <div class="absolute inset-0 flex items-center justify-center pointer-events-none opacity-[0.03]">
                            <span class="text-[15rem] grayscale">💬</span>
                        </div>

                        @if(isset($chats) && $chats->count() > 0)
                            @foreach($chats as $chat)
                                @if($chat->sender_id == Auth::id())
                                    <div class="self-end bg-gradient-to-br from-emerald-500 to-teal-600 text-white p-4 md:p-5 rounded-[1.5rem] md:rounded-[2rem] rounded-tr-sm shadow-md max-w-[85%] relative z-10 transform transition-transform hover:-translate-y-0.5">
                                        <p class="text-sm md:text-base font-medium leading-relaxed">{{ $chat->pesan }}</p>
                                        <div class="flex items-center justify-end gap-1 mt-2">
                                            <p class="text-[9px] md:text-[10px] text-emerald-100 font-black uppercase">{{ $chat->created_at->format('H:i') }}</p>
                                            <span class="text-[10px]">✓✓</span>
                                        </div>
                                    </div>
                                @else
                                    <div class="self-start bg-white border border-slate-200 p-4 md:p-5 rounded-[1.5rem] md:rounded-[2rem] rounded-tl-sm shadow-sm max-w-[85%] relative z-10 transform transition-transform hover:-translate-y-0.5">
                                        <p class="text-sm md:text-base text-slate-700 font-medium leading-relaxed">{{ $chat->pesan }}</p>
                                        <p class="text-[9px] md:text-[10px] text-slate-400 mt-2 font-black uppercase text-left">{{ $chat->created_at->format('H:i') }}</p>
                                    </div>
                                @endif
                            @endforeach
                        @else
                            <div class="self-center text-center text-slate-400 my-auto bg-white/60 p-6 md:p-8 rounded-[2rem] border border-slate-100 shadow-sm backdrop-blur-sm z-10">
                                <span class="text-5xl md:text-6xl block mb-4 opacity-50 grayscale">👋</span>
                                <h4 class="text-lg font-black text-slate-700 mb-1">Mulai Obrolan</h4>
                                <p class="text-xs md:text-sm font-bold max-w-xs">Sapa siswa, berikan motivasi, atau tanyakan kesulitan belajar yang mereka alami.</p>
                            </div>
                        @endif
                    </div>

                    <form id="form-chat-guru" action="{{ route('guru.chat.send') }}" method="POST" class="p-3 md:p-4 bg-white border-t border-slate-100 flex items-center gap-2 md:gap-3 shrink-0 relative z-20">
                        @csrf
                        <input type="hidden" name="receiver_id" value="{{ request('siswa_id') }}">
                        
                        <div class="relative flex-1">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-xl">✍️</span>
                            <input type="text" name="pesan" placeholder="Tulis motivasi atau bimbingan..." class="w-full rounded-xl md:rounded-2xl border border-slate-200 bg-slate-50 focus:bg-white focus:ring-2 focus:ring-emerald-500 focus:border-transparent font-medium px-4 py-3 md:py-4 pl-12 text-sm md:text-base transition-all shadow-inner" required autocomplete="off">
                        </div>
                        
                        <button type="submit" class="bg-gradient-to-r from-emerald-500 to-teal-600 text-white px-5 md:px-8 py-3 md:py-4 rounded-xl md:rounded-2xl font-black hover:shadow-lg hover:shadow-emerald-200/50 hover:-translate-y-1 active:scale-95 transition-all flex items-center justify-center gap-2 border border-emerald-400/50">
                            <span class="hidden md:inline text-xs md:text-sm tracking-widest uppercase">Kirim</span>
                            <span class="text-lg md:text-xl">🚀</span>
                        </button>
                    </form>

                @else
                    <div class="flex-grow flex flex-col items-center justify-center text-slate-400 bg-slate-50/50 relative overflow-hidden">
                        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-64 h-64 bg-emerald-200/20 rounded-full blur-3xl"></div>
                        
                        <div class="relative z-10 flex flex-col items-center text-center p-6">
                            <span class="text-7xl md:text-8xl mb-6 opacity-40 grayscale animate-pulse">💬</span>
                            <h3 class="font-black text-xl md:text-2xl text-slate-600 mb-2">Ruang Bimbingan</h3>
                            <p class="font-bold text-xs md:text-sm text-slate-400 max-w-xs leading-relaxed border border-slate-200 bg-white p-3 rounded-xl shadow-sm">Pilih nama siswa di daftar sebelah kiri untuk melihat riwayat atau memulai obrolan baru.</p>
                        </div>
                    </div>
                @endif
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

            // Otomatis scroll chat ke paling bawah
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
    // [FIX] Chat AJAX Guru — cegah tab baru & reload halaman saat kirim pesan
    function escapeHtml(str) {
        const div = document.createElement('div');
        div.appendChild(document.createTextNode(str));
        return div.innerHTML;
    }

    document.addEventListener('DOMContentLoaded', function () {
        const form = document.getElementById('form-chat-guru');
        if (!form) return;

        form.addEventListener('submit', function (e) {
            e.preventDefault();

            const input      = form.querySelector('input[name="pesan"]');
            const pesan      = input.value.trim();
            const receiverId = form.querySelector('input[name="receiver_id"]').value;
            const csrfToken  = form.querySelector('input[name="_token"]').value;
            const container  = document.getElementById('chat-container');
            const btnKirim   = form.querySelector('button[type="submit"]');

            if (!pesan) return;
            btnKirim.disabled = true;

            fetch(form.action, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrfToken, 'Accept': 'application/json' },
                body: JSON.stringify({ receiver_id: receiverId, pesan: pesan }),
            })
            .then(r => r.json())
            .then(data => {
                if (data.success) {
                    const bubble = document.createElement('div');
                    bubble.className = 'self-end bg-gradient-to-br from-emerald-500 to-teal-600 text-white p-4 md:p-5 rounded-[1.5rem] rounded-tr-sm shadow-md max-w-[85%] relative z-10';
                    bubble.innerHTML = '<p class="text-sm md:text-base font-medium leading-relaxed">' + escapeHtml(data.pesan) + '</p>' +
                        '<div class="flex items-center justify-end gap-1 mt-2"><p class="text-[9px] text-emerald-100 font-black uppercase">' + data.waktu + '</p><span class="text-[10px]">✓✓</span></div>';
                    container.appendChild(bubble);
                    container.scrollTop = container.scrollHeight;
                    input.value = '';
                }
            })
            .catch(() => alert('Gagal mengirim pesan.'))
            .finally(() => { btnKirim.disabled = false; });
        });
    });
    </script>
</x-app-layout>