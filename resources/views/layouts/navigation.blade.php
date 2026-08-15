<nav x-data="{ open: false, notifOpen: false, notifCount: 0, notifList: [], notifLoaded: false }"
     x-init="
         fetch('/notifikasi/count').then(r => r.json()).then(d => { notifCount = d.count });
         setInterval(() => fetch('/notifikasi/count').then(r => r.json()).then(d => { notifCount = d.count }), 60000);
     "
     class="bg-white border-b border-slate-200/80 sticky top-0 z-50" style="box-shadow: 0 1px 20px rgba(79,70,229,0.07);">
    {{-- Rainbow accent bar --}}
    <div class="h-[3px] w-full bg-gradient-to-r from-indigo-500 via-violet-500 to-cyan-400"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-14">

            {{-- ===== LOGO ===== --}}
            <div class="flex items-center min-w-0">
                @php
                    $homeRoute = match(Auth::user()->role) {
                        'admin' => route('admin.dashboard'),
                        'guru'  => route('guru.dashboard'),
                        'ortu'  => route('ortu.dashboard'),
                        default => route('siswa.dashboard'),
                    };
                @endphp
                <a href="{{ $homeRoute }}" class="flex items-center gap-2.5 mr-6 shrink-0 group">
                    {{-- Logo image: fixed 32×32, constrained --}}
                    <span class="w-8 h-8 shrink-0 flex items-center justify-center rounded-lg overflow-hidden group-hover:scale-105 transition-transform bg-indigo-50">
                        <img src="{{ asset('images/logo.png') }}" alt="Logo"
                             class="w-full h-full object-contain">
                    </span>
                    {{-- Brand text (hidden on mobile) --}}
                    <span class="hidden lg:flex flex-col leading-none">
                        <span class="font-black text-[13px] text-slate-800 tracking-tight">MIFDA</span>
                        <span class="text-[8px] font-bold text-indigo-500 tracking-[0.12em] uppercase mt-0.5">Learning Platform</span>
                    </span>
                </a>

                {{-- ===== DESKTOP NAV LINKS ===== --}}
                <div class="hidden sm:flex items-center gap-0.5">
                    @if(Auth::user()->role === 'admin')
                        <x-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')" class="!text-[12px] !font-bold !px-3 !py-2 !rounded-lg hover:!bg-indigo-50 hover:!text-indigo-700 !text-slate-600">🏠 Dashboard</x-nav-link>
                        <x-nav-link :href="route('admin.kelas.index')" :active="request()->routeIs('admin.kelas.*')" class="!text-[12px] !font-bold !px-3 !py-2 !rounded-lg hover:!bg-indigo-50 hover:!text-indigo-700 !text-slate-600">🏫 Kelas</x-nav-link>
                        <x-nav-link :href="route('admin.mapel.index')" :active="request()->routeIs('admin.mapel.*')" class="!text-[12px] !font-bold !px-3 !py-2 !rounded-lg hover:!bg-indigo-50 hover:!text-indigo-700 !text-slate-600">📚 Mapel</x-nav-link>
                        <x-nav-link :href="route('admin.user.index')" :active="request()->routeIs('admin.user.*')" class="!text-[12px] !font-bold !px-3 !py-2 !rounded-lg hover:!bg-indigo-50 hover:!text-indigo-700 !text-slate-600">👥 User</x-nav-link>
                        <x-nav-link :href="route('admin.relasi.index')" :active="request()->routeIs('admin.relasi.*')" class="!text-[12px] !font-bold !px-3 !py-2 !rounded-lg hover:!bg-indigo-50 hover:!text-indigo-700 !text-slate-600">🤝 Relasi</x-nav-link>

                    @elseif(Auth::user()->role === 'guru')
                        <x-nav-link :href="route('guru.dashboard')" :active="request()->routeIs('guru.dashboard')" class="!text-[12px] !font-bold !px-3 !py-2 !rounded-lg hover:!bg-indigo-50 hover:!text-indigo-700 !text-slate-600">🏠 Misi</x-nav-link>
                        <x-nav-link :href="route('guru.ujian.index')" :active="request()->routeIs('guru.ujian.*')" class="!text-[12px] !font-bold !px-3 !py-2 !rounded-lg hover:!bg-indigo-50 hover:!text-indigo-700 !text-slate-600">📋 Ujian</x-nav-link>
                        <x-nav-link :href="route('guru.game.index')" :active="request()->routeIs('guru.game.*')" class="!text-[12px] !font-bold !px-3 !py-2 !rounded-lg hover:!bg-indigo-50 hover:!text-indigo-700 !text-slate-600">🎮 Game</x-nav-link>
                        <x-nav-link :href="route('guru.laporan.index')" :active="request()->routeIs('guru.laporan.*')" class="!text-[12px] !font-bold !px-3 !py-2 !rounded-lg hover:!bg-indigo-50 hover:!text-indigo-700 !text-slate-600">📊 Progres</x-nav-link>
                        <x-nav-link :href="route('guru.chat.index')" :active="request()->routeIs('guru.chat.*')" class="!text-[12px] !font-bold !px-3 !py-2 !rounded-lg hover:!bg-indigo-50 hover:!text-indigo-700 !text-slate-600">💬 Bimbingan</x-nav-link>
                        <x-nav-link :href="route('guru.kelulusan.index')" :active="request()->routeIs('guru.kelulusan.*')" class="!text-[12px] !font-bold !px-3 !py-2 !rounded-lg hover:!bg-indigo-50 hover:!text-indigo-700 !text-slate-600">🎓 Kelulusan</x-nav-link>

                    @elseif(Auth::user()->role === 'ortu')
                        <x-nav-link :href="route('ortu.dashboard')" :active="request()->routeIs('ortu.*')" class="!text-[12px] !font-bold !px-3 !py-2 !rounded-lg hover:!bg-indigo-50 hover:!text-indigo-700 !text-slate-600">👨‍👩‍👧 Pantau Anak</x-nav-link>

                    @elseif(Auth::user()->role === 'siswa')
                        <x-nav-link :href="route('siswa.dashboard')" :active="request()->routeIs('siswa.dashboard')" class="!text-[12px] !font-bold !px-3 !py-2 !rounded-lg hover:!bg-indigo-50 hover:!text-indigo-700 !text-slate-600">🏠 Beranda</x-nav-link>
                        <x-nav-link :href="route('siswa.belajar')" :active="request()->routeIs('siswa.belajar') || request()->routeIs('siswa.materi*') || request()->routeIs('siswa.kuis*')" class="!text-[12px] !font-bold !px-3 !py-2 !rounded-lg hover:!bg-indigo-50 hover:!text-indigo-700 !text-slate-600">📚 Belajar</x-nav-link>
                        <x-nav-link :href="route('siswa.ujian.index')" :active="request()->routeIs('siswa.ujian*')" class="!text-[12px] !font-bold !px-3 !py-2 !rounded-lg hover:!bg-indigo-50 hover:!text-indigo-700 !text-slate-600">📋 Ujian</x-nav-link>
                        <x-nav-link :href="route('siswa.leaderboard')" :active="request()->routeIs('siswa.leaderboard')" class="!text-[12px] !font-bold !px-3 !py-2 !rounded-lg hover:!bg-indigo-50 hover:!text-indigo-700 !text-slate-600">🏆 Peringkat</x-nav-link>

                    @elseif(Auth::user()->role === 'alumni')
                        <span class="text-[11px] font-black text-amber-600 bg-amber-50 px-3 py-1.5 rounded-lg border border-amber-200">🎓 Alumni</span>
                    @endif
                </div>
            </div>

            {{-- ===== KANAN: NOTIF BELL + DROPDOWN PROFIL (DESKTOP) ===== --}}
            <div class="hidden sm:flex items-center gap-2">

                {{-- PWA INSTALL BUTTON — Android/Chrome: muncul via beforeinstallprompt --}}
                <button id="pwa-install-btn"
                    class="hidden items-center gap-1.5 h-9 px-3 bg-indigo-600 hover:bg-indigo-700 text-white text-[11px] font-black rounded-xl shadow-sm shadow-indigo-200 transition-all"
                    title="Install aplikasi MIFDA di HP kamu">
                    📲 <span class="hidden md:inline">Install App</span>
                </button>

                {{-- PWA GUIDE BUTTON — iOS & browser lain yang tidak support beforeinstallprompt --}}
                <button id="pwa-guide-btn"
                    class="hidden items-center gap-1.5 h-9 px-3 bg-violet-600 hover:bg-violet-700 text-white text-[11px] font-black rounded-xl shadow-sm shadow-violet-200 transition-all"
                    title="Cara pasang MIFDA di HP kamu">
                    📲 <span class="hidden md:inline">Pasang App</span>
                </button>

                {{-- NOTIFICATION BELL --}}
                <div class="relative" x-data>
                    <button @click="
                            notifOpen = !notifOpen;
                            if (notifOpen && !notifLoaded) {
                                fetch('/notifikasi').then(r => r.json()).then(d => {
                                    notifList = d.notifikasis;
                                    notifCount = d.belum_dibaca;
                                    notifLoaded = true;
                                });
                            }
                        "
                        class="relative w-9 h-9 flex items-center justify-center rounded-xl bg-slate-50 hover:bg-indigo-50 text-slate-500 hover:text-indigo-600 border border-slate-200 hover:border-indigo-300 transition-all">
                        {{-- Bell icon --}}
                        <svg class="w-4.5 h-4.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6 6 0 10-12 0v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                        </svg>
                        {{-- Badge --}}
                        <span x-show="notifCount > 0" x-text="notifCount > 9 ? '9+' : notifCount"
                              class="absolute -top-1 -right-1 min-w-[16px] h-4 bg-red-500 text-white text-[9px] font-black rounded-full flex items-center justify-center px-0.5 leading-none ring-2 ring-white">
                        </span>
                    </button>

                    {{-- Notification Dropdown Panel --}}
                    <div x-show="notifOpen" @click.outside="notifOpen = false"
                         x-transition:enter="transition ease-out duration-150"
                         x-transition:enter-start="opacity-0 scale-95 -translate-y-1"
                         x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                         class="absolute right-0 top-11 w-80 bg-white rounded-2xl shadow-2xl border border-slate-100 overflow-hidden z-50">

                        {{-- Header --}}
                        <div class="px-4 py-3 bg-gradient-to-r from-indigo-50 to-violet-50 border-b border-indigo-100 flex items-center justify-between gap-2">
                            <span class="text-[13px] font-black text-slate-800">🔔 Notifikasi</span>
                            <div class="flex items-center gap-2.5 shrink-0">
                                <button @click="
                                        fetch('/notifikasi/read-all', {method:'POST', headers:{'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content}})
                                        .then(() => { notifList = notifList.map(n => ({...n, dibaca: true})); notifCount = 0; notifLoaded = false; })
                                    "
                                    class="text-[10px] font-bold text-indigo-500 hover:text-indigo-700">
                                    Tandai dibaca
                                </button>
                                {{-- TAMBAHAN (upgrade #2): hapus semua notifikasi --}}
                                <button x-show="notifList.length > 0" @click="
                                        if (confirm('Hapus semua notifikasi?')) {
                                            fetch('/notifikasi/hapus-semua', {method:'POST', headers:{'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content}})
                                            .then(() => { notifList = []; notifCount = 0; });
                                        }
                                    "
                                    class="text-[10px] font-bold text-red-500 hover:text-red-700">
                                    Hapus semua
                                </button>
                            </div>
                        </div>

                        {{-- List --}}
                        <div class="max-h-80 overflow-y-auto divide-y divide-slate-50">
                            <template x-if="notifList.length === 0">
                                <div class="px-4 py-8 text-center">
                                    <div class="text-3xl mb-2">🎉</div>
                                    <p class="text-[12px] text-slate-400 font-bold">Tidak ada notifikasi baru</p>
                                </div>
                            </template>
                            <template x-for="n in notifList" :key="n.id">
                                <div :class="n.dibaca ? 'bg-white' : 'bg-indigo-50/60'"
                                     class="flex items-start gap-3 px-4 py-3 hover:bg-slate-50 transition group">
                                    <a :href="n.url || '#'"
                                       @click="
                                           fetch('/notifikasi/' + n.id + '/read', {method:'POST', headers:{'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content}});
                                           n.dibaca = true;
                                           notifCount = Math.max(0, notifCount - 1);
                                       "
                                       class="flex items-start gap-3 min-w-0 flex-1">
                                        <span class="text-xl mt-0.5 shrink-0" x-text="n.icon"></span>
                                        <div class="min-w-0 flex-1">
                                            <p class="text-[12px] font-black text-slate-800 leading-tight truncate" x-text="n.judul"></p>
                                            <p class="text-[11px] text-slate-500 leading-snug mt-0.5 line-clamp-2" x-text="n.pesan"></p>
                                            <p class="text-[10px] text-slate-400 mt-1 font-bold" x-text="n.waktu"></p>
                                        </div>
                                        <span x-show="!n.dibaca" class="w-2 h-2 bg-indigo-500 rounded-full shrink-0 mt-2"></span>
                                    </a>
                                    {{-- TAMBAHAN (upgrade #2): hapus 1 notifikasi --}}
                                    <button @click="
                                            fetch('/notifikasi/' + n.id + '/hapus', {method:'POST', headers:{'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content}});
                                            notifList = notifList.filter(x => x.id !== n.id);
                                            if (!n.dibaca) notifCount = Math.max(0, notifCount - 1);
                                        "
                                        class="shrink-0 mt-0.5 w-5 h-5 flex items-center justify-center rounded-full text-slate-300 hover:text-red-500 hover:bg-red-50 opacity-0 group-hover:opacity-100 transition"
                                        title="Hapus notifikasi ini">
                                        ✕
                                    </button>
                                </div>
                            </template>
                        </div>
                    </div>
                </div>
                @php
                    $roleConfig = [
                        'admin'  => ['bg' => 'bg-violet-100', 'text' => 'text-violet-700', 'border' => 'border-violet-300', 'label' => 'Admin'],
                        'guru'   => ['bg' => 'bg-indigo-100', 'text' => 'text-indigo-700', 'border' => 'border-indigo-300', 'label' => 'Guru'],
                        'ortu'   => ['bg' => 'bg-orange-100', 'text' => 'text-orange-700', 'border' => 'border-orange-300', 'label' => 'Ortu'],
                        'siswa'  => ['bg' => 'bg-cyan-100',   'text' => 'text-cyan-700',   'border' => 'border-cyan-300',   'label' => 'Siswa'],
                        'alumni' => ['bg' => 'bg-amber-100',  'text' => 'text-amber-700',  'border' => 'border-amber-300',  'label' => 'Alumni'],
                    ];
                    $rc       = $roleConfig[Auth::user()->role] ?? ['bg' => 'bg-slate-100', 'text' => 'text-slate-600', 'border' => 'border-slate-300', 'label' => ucfirst(Auth::user()->role)];
                    $rBg      = $rc['bg'];
                    $rText    = $rc['text'];
                    $rBorder  = $rc['border'];
                    $roleTeks = $rc['label'];
                @endphp

                <x-dropdown align="right" width="56">
                    <x-slot name="trigger">
                        <button type="button"
                            class="flex items-center gap-2 h-9 pl-1.5 pr-3 border border-slate-200 rounded-xl bg-white hover:bg-slate-50 hover:border-indigo-300 focus:outline-none focus:ring-2 focus:ring-indigo-100 transition-all shadow-sm">

                            {{-- Avatar: 28×28, strict clipping --}}
                            <span class="w-7 h-7 shrink-0 rounded-lg overflow-hidden border-2 {{ $rBorder }} flex items-center justify-center {{ $rBg }}">
                                @if(Auth::user()->avatar)
                                    <img src="{{ asset('storage/' . Auth::user()->avatar) }}"
                                         alt="Avatar"
                                         class="w-full h-full object-cover block">
                                @else
                                    <span class="text-xs font-black {{ $rText }}">{{ substr(Auth::user()->name, 0, 1) }}</span>
                                @endif
                            </span>

                            {{-- Name --}}
                            <span class="text-[12px] font-black text-slate-700 max-w-[80px] truncate leading-none">{{ explode(' ', Auth::user()->name)[0] }}</span>

                            {{-- Chevron --}}
                            <svg class="w-3 h-3 text-slate-400 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        {{-- Header dropdown --}}
                        <div class="px-4 py-3 border-b border-slate-100 bg-gradient-to-br from-indigo-50 to-violet-50 flex items-center gap-3">
                            <span class="w-10 h-10 shrink-0 rounded-xl overflow-hidden border-2 {{ $rBorder }} flex items-center justify-center {{ $rBg }}">
                                @if(Auth::user()->avatar)
                                    <img src="{{ asset('storage/' . Auth::user()->avatar) }}"
                                         alt="Avatar"
                                         class="w-full h-full object-cover block">
                                @else
                                    <span class="text-sm font-black {{ $rText }}">{{ substr(Auth::user()->name, 0, 1) }}</span>
                                @endif
                            </span>
                            <div class="min-w-0">
                                <p class="text-[11px] font-black text-slate-800 truncate">{{ Auth::user()->name }}</p>
                                <p class="text-[9px] text-slate-400 font-bold">@ {{ Auth::user()->username }}</p>
                                <span class="inline-block mt-1 text-[8px] font-black uppercase tracking-widest border px-1.5 py-0.5 rounded {{ $rBg }} {{ $rText }} {{ $rBorder }}">{{ $roleTeks }}</span>
                            </div>
                        </div>

                        <x-dropdown-link :href="route('profile.edit')" class="!font-bold !text-[13px] !text-slate-600 hover:!bg-indigo-50 hover:!text-indigo-700 !px-4 !py-2.5 !transition flex items-center gap-2">
                            ⚙️ Pengaturan Profil
                        </x-dropdown-link>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();"
                                class="!text-red-500 !font-black !text-[13px] hover:!bg-red-50 !border-t !border-slate-100 !px-4 !py-2.5 !transition flex items-center gap-2">
                                🚪 Keluar
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            {{-- ===== HAMBURGER (MOBILE) ===== --}}
            <div class="flex items-center sm:hidden">
                <button @click="open = !open"
                    class="w-9 h-9 flex items-center justify-center rounded-lg text-slate-500 bg-slate-50 hover:bg-indigo-50 hover:text-indigo-600 focus:outline-none transition">
                    <svg class="h-5 w-5" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': !open}" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        <path :class="{'hidden': !open, 'inline-flex': open}" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>
        </div>
    </div>

    {{-- ===== MOBILE MENU ===== --}}
    <div :class="{'block': open, 'hidden': !open}" class="hidden sm:hidden bg-white border-t border-slate-100 absolute w-full shadow-xl pb-3 z-40">

        {{-- Profile card mobile --}}
        <div class="flex items-center gap-3 px-4 py-3 bg-gradient-to-r from-indigo-50 to-violet-50 border-b border-indigo-100">
            <span class="w-10 h-10 shrink-0 rounded-xl overflow-hidden border-2 {{ $rBorder ?? 'border-slate-300' }} flex items-center justify-center {{ $rBg ?? 'bg-slate-100' }}">
                @if(Auth::user()->avatar)
                    <img src="{{ asset('storage/' . Auth::user()->avatar) }}"
                         alt="Avatar"
                         class="w-full h-full object-cover block">
                @else
                    <span class="text-sm font-black {{ $rText ?? 'text-slate-600' }}">{{ substr(Auth::user()->name, 0, 1) }}</span>
                @endif
            </span>
            <div class="min-w-0">
                <p class="font-black text-sm text-slate-800 truncate">{{ Auth::user()->name }}</p>
                <p class="text-[10px] text-slate-400 font-bold">@ {{ Auth::user()->username }}</p>
            </div>
            <span class="ml-auto shrink-0 text-[9px] font-black uppercase tracking-widest border px-2 py-0.5 rounded-lg {{ $rBg ?? 'bg-slate-100' }} {{ $rText ?? 'text-slate-600' }} {{ $rBorder ?? 'border-slate-300' }}">{{ $roleTeks ?? '' }}</span>
        </div>

        {{-- Nav links mobile --}}
        <div class="pt-2 pb-1 px-3 space-y-0.5">
            @if(Auth::user()->role === 'admin')
                <x-responsive-nav-link :href="route('admin.dashboard')" class="!rounded-lg !font-bold !text-[13px] !py-2.5 !px-3 hover:!bg-indigo-50 hover:!text-indigo-700 !transition">🏠 Dashboard</x-responsive-nav-link>
                <x-responsive-nav-link :href="route('admin.kelas.index')" class="!rounded-lg !font-bold !text-[13px] !py-2.5 !px-3 hover:!bg-indigo-50 hover:!text-indigo-700 !transition">🏫 Kelas</x-responsive-nav-link>
                <x-responsive-nav-link :href="route('admin.mapel.index')" class="!rounded-lg !font-bold !text-[13px] !py-2.5 !px-3 hover:!bg-indigo-50 hover:!text-indigo-700 !transition">📚 Mapel</x-responsive-nav-link>
                <x-responsive-nav-link :href="route('admin.user.index')" class="!rounded-lg !font-bold !text-[13px] !py-2.5 !px-3 hover:!bg-indigo-50 hover:!text-indigo-700 !transition">👥 User</x-responsive-nav-link>
                <x-responsive-nav-link :href="route('admin.relasi.index')" class="!rounded-lg !font-bold !text-[13px] !py-2.5 !px-3 hover:!bg-indigo-50 hover:!text-indigo-700 !transition">🤝 Relasi</x-responsive-nav-link>
            @elseif(Auth::user()->role === 'guru')
                <x-responsive-nav-link :href="route('guru.dashboard')" class="!rounded-lg !font-bold !text-[13px] !py-2.5 !px-3 hover:!bg-indigo-50 hover:!text-indigo-700 !transition">🏠 Misi</x-responsive-nav-link>
                <x-responsive-nav-link :href="route('guru.ujian.index')" class="!rounded-lg !font-bold !text-[13px] !py-2.5 !px-3 hover:!bg-indigo-50 hover:!text-indigo-700 !transition">📋 Ujian</x-responsive-nav-link>
                <x-responsive-nav-link :href="route('guru.game.index')" class="!rounded-lg !font-bold !text-[13px] !py-2.5 !px-3 hover:!bg-indigo-50 hover:!text-indigo-700 !transition">🎮 Game</x-responsive-nav-link>
                <x-responsive-nav-link :href="route('guru.laporan.index')" class="!rounded-lg !font-bold !text-[13px] !py-2.5 !px-3 hover:!bg-indigo-50 hover:!text-indigo-700 !transition">📊 Progres</x-responsive-nav-link>
                <x-responsive-nav-link :href="route('guru.chat.index')" class="!rounded-lg !font-bold !text-[13px] !py-2.5 !px-3 hover:!bg-indigo-50 hover:!text-indigo-700 !transition">💬 Bimbingan</x-responsive-nav-link>
                <x-responsive-nav-link :href="route('guru.kelulusan.index')" class="!rounded-lg !font-bold !text-[13px] !py-2.5 !px-3 hover:!bg-indigo-50 hover:!text-indigo-700 !transition">🎓 Kelulusan</x-responsive-nav-link>
            @elseif(Auth::user()->role === 'ortu')
                <x-responsive-nav-link :href="route('ortu.dashboard')" class="!rounded-lg !font-bold !text-[13px] !py-2.5 !px-3 hover:!bg-indigo-50 hover:!text-indigo-700 !transition">👨‍👩‍👧 Pantau Anak</x-responsive-nav-link>
            @elseif(Auth::user()->role === 'siswa')
                <x-responsive-nav-link :href="route('siswa.dashboard')" class="!rounded-lg !font-bold !text-[13px] !py-2.5 !px-3 hover:!bg-indigo-50 hover:!text-indigo-700 !transition">🏠 Beranda</x-responsive-nav-link>
                <x-responsive-nav-link :href="route('siswa.belajar')" class="!rounded-lg !font-bold !text-[13px] !py-2.5 !px-3 hover:!bg-indigo-50 hover:!text-indigo-700 !transition">📚 Belajar</x-responsive-nav-link>
                <x-responsive-nav-link :href="route('siswa.ujian.index')" class="!rounded-lg !font-bold !text-[13px] !py-2.5 !px-3 hover:!bg-indigo-50 hover:!text-indigo-700 !transition">📋 Ujian</x-responsive-nav-link>
                <x-responsive-nav-link :href="route('siswa.leaderboard')" class="!rounded-lg !font-bold !text-[13px] !py-2.5 !px-3 hover:!bg-indigo-50 hover:!text-indigo-700 !transition">🏆 Peringkat</x-responsive-nav-link>
            @elseif(Auth::user()->role === 'alumni')
                <div class="px-3 py-2.5 text-[13px] font-black text-amber-600 bg-amber-50 rounded-lg border border-amber-200">🎓 Alumni</div>
            @endif
        </div>

        {{-- Bottom actions mobile --}}
        <div class="px-3 pt-2 border-t border-slate-100 mt-1 space-y-0.5">
            <x-responsive-nav-link :href="route('profile.edit')" class="!rounded-lg !font-bold !text-[13px] !text-slate-600 hover:!bg-indigo-50 hover:!text-indigo-700 !py-2.5 !px-3 !transition flex items-center gap-2">
                ⚙️ Profil Saya
            </x-responsive-nav-link>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <x-responsive-nav-link :href="route('logout')" onclick="event.preventDefault(); this.closest('form').submit();"
                    class="!rounded-lg !font-black !text-[13px] !text-red-500 hover:!bg-red-50 !py-2.5 !px-3 !transition flex items-center gap-2">
                    🚪 Keluar
                </x-responsive-nav-link>
            </form>
        </div>
    </div>
</nav>

{{-- ===== PWA GUIDE MODAL ===== --}}
<div id="pwa-guide-modal"
     class="hidden fixed inset-0 z-[999] flex items-end justify-center p-4"
     style="background: rgba(0,0,0,0.55); backdrop-filter: blur(4px);">
    <div class="w-full max-w-sm bg-white rounded-[2rem] shadow-2xl overflow-hidden animate-slide-up">

        {{-- Header --}}
        <div class="bg-gradient-to-r from-indigo-600 to-violet-600 px-5 py-4 text-white relative">
            <button id="pwa-modal-close" class="absolute top-3 right-4 text-white/70 hover:text-white text-xl font-black leading-none">✕</button>
            <h3 class="font-black text-base">📲 Pasang MIFDA di HP</h3>
            <p class="text-indigo-200 text-xs mt-0.5 font-bold">Akses lebih cepat seperti aplikasi beneran!</p>
        </div>

        {{-- Langkah iOS --}}
        <div id="guide-steps-ios" class="hidden p-5 space-y-4">
            <p class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1">Langkah untuk Safari (iPhone / iPad)</p>
            <div class="flex items-start gap-3">
                <div class="w-7 h-7 bg-indigo-100 text-indigo-700 rounded-xl flex items-center justify-center font-black text-xs shrink-0">1</div>
                <div>
                    <p class="font-bold text-slate-800 text-sm">Tap tombol <strong>Bagikan</strong> di bawah browser</p>
                    <p class="text-slate-400 text-xs mt-0.5">Ikon kotak dengan panah ke atas ⬆️</p>
                </div>
            </div>
            <div class="flex items-start gap-3">
                <div class="w-7 h-7 bg-indigo-100 text-indigo-700 rounded-xl flex items-center justify-center font-black text-xs shrink-0">2</div>
                <div>
                    <p class="font-bold text-slate-800 text-sm">Pilih <strong>"Tambahkan ke Layar Utama"</strong></p>
                    <p class="text-slate-400 text-xs mt-0.5">Scroll ke bawah di menu share jika tidak terlihat</p>
                </div>
            </div>
            <div class="flex items-start gap-3">
                <div class="w-7 h-7 bg-indigo-100 text-indigo-700 rounded-xl flex items-center justify-center font-black text-xs shrink-0">3</div>
                <div>
                    <p class="font-bold text-slate-800 text-sm">Tap <strong>"Tambahkan"</strong> di pojok kanan atas</p>
                    <p class="text-slate-400 text-xs mt-0.5">Ikon MIFDA langsung muncul di layar utama! 🎉</p>
                </div>
            </div>
        </div>

        {{-- Langkah Android non-Chrome --}}
        <div id="guide-steps-android" class="hidden p-5 space-y-4">
            <p class="text-[10px] font-black uppercase tracking-widest text-slate-400 mb-1">Langkah untuk Android</p>
            <div class="flex items-start gap-3">
                <div class="w-7 h-7 bg-indigo-100 text-indigo-700 rounded-xl flex items-center justify-center font-black text-xs shrink-0">1</div>
                <div>
                    <p class="font-bold text-slate-800 text-sm">Buka halaman ini di <strong>Google Chrome</strong></p>
                    <p class="text-slate-400 text-xs mt-0.5">Fitur install otomatis hanya tersedia di Chrome</p>
                </div>
            </div>
            <div class="flex items-start gap-3">
                <div class="w-7 h-7 bg-indigo-100 text-indigo-700 rounded-xl flex items-center justify-center font-black text-xs shrink-0">2</div>
                <div>
                    <p class="font-bold text-slate-800 text-sm">Tap menu <strong>⋮</strong> di pojok kanan atas Chrome</p>
                    <p class="text-slate-400 text-xs mt-0.5">Lalu pilih "Tambahkan ke layar utama"</p>
                </div>
            </div>
            <div class="flex items-start gap-3">
                <div class="w-7 h-7 bg-indigo-100 text-indigo-700 rounded-xl flex items-center justify-center font-black text-xs shrink-0">3</div>
                <div>
                    <p class="font-bold text-slate-800 text-sm">Tap <strong>"Tambahkan"</strong> untuk konfirmasi</p>
                    <p class="text-slate-400 text-xs mt-0.5">MIFDA akan muncul di layar utama HP kamu! 🎉</p>
                </div>
            </div>
        </div>

        {{-- Footer --}}
        <div class="px-5 pb-5 pt-1">
            <button id="pwa-modal-close-btn"
                class="w-full py-3 bg-slate-100 text-slate-600 rounded-2xl font-black text-sm hover:bg-slate-200 transition-all">
                Mengerti, Tutup
            </button>
        </div>
    </div>
</div>

<style>
    @keyframes slideUp { from { transform: translateY(100%); opacity: 0; } to { transform: translateY(0); opacity: 1; } }
    .animate-slide-up { animation: slideUp 0.3s ease-out; }
</style>

{{-- ===== PWA INSTALL PROMPT ===== --}}
<script>
(function () {
    const isIOS       = /iPad|iPhone|iPod/.test(navigator.userAgent) && !window.MSStream;
    const isInstalled = window.navigator.standalone === true
                     || window.matchMedia('(display-mode: standalone)').matches;

    // Sudah diinstall — tidak perlu tampilkan apapun
    if (isInstalled) return;

    let deferredPrompt = null;
    const btnAndroid  = document.getElementById('pwa-install-btn');
    const btnGuide    = document.getElementById('pwa-guide-btn');
    const modal       = document.getElementById('pwa-guide-modal');
    const stepsIOS    = document.getElementById('guide-steps-ios');
    const stepsAndroid= document.getElementById('guide-steps-android');

    function showModal(type) {
        if (!modal) return;
        if (type === 'ios') {
            stepsIOS && stepsIOS.classList.remove('hidden');
            stepsAndroid && stepsAndroid.classList.add('hidden');
        } else {
            stepsAndroid && stepsAndroid.classList.remove('hidden');
            stepsIOS && stepsIOS.classList.add('hidden');
        }
        modal.classList.remove('hidden');
    }
    function hideModal() { modal && modal.classList.add('hidden'); }
    function showBtn(btn) { btn && btn.classList.remove('hidden') && btn.classList.add('flex'); }

    // Tutup modal
    document.getElementById('pwa-modal-close')     && document.getElementById('pwa-modal-close').addEventListener('click', hideModal);
    document.getElementById('pwa-modal-close-btn') && document.getElementById('pwa-modal-close-btn').addEventListener('click', hideModal);
    modal && modal.addEventListener('click', (e) => { if (e.target === modal) hideModal(); });

    // iOS → tampilkan guide button langsung
    if (isIOS && btnGuide) {
        btnGuide.classList.remove('hidden');
        btnGuide.classList.add('flex');
        btnGuide.addEventListener('click', () => showModal('ios'));
        return; // iOS tidak punya beforeinstallprompt, selesai di sini
    }

    // Android/Chrome → tangkap beforeinstallprompt
    window.addEventListener('beforeinstallprompt', (e) => {
        e.preventDefault();
        deferredPrompt = e;
        if (btnAndroid) {
            btnAndroid.classList.remove('hidden');
            btnAndroid.classList.add('flex');
        }
    });

    // Klik install button → tampilkan native prompt
    btnAndroid && btnAndroid.addEventListener('click', async () => {
        if (deferredPrompt) {
            deferredPrompt.prompt();
            const { outcome } = await deferredPrompt.userChoice;
            deferredPrompt = null;
            btnAndroid.classList.add('hidden');
            btnAndroid.classList.remove('flex');
        } else {
            // Tidak ada native prompt → tampilkan panduan manual
            showModal('android');
        }
    });

    // Guide button untuk Android non-Chrome (beforeinstallprompt tidak pernah muncul setelah 4 detik)
    btnGuide && btnGuide.addEventListener('click', () => showModal('android'));

    // Fallback: jika setelah 4 detik tidak ada beforeinstallprompt (browser tidak support) → tampilkan guide btn
    setTimeout(() => {
        if (!deferredPrompt && btnGuide && btnGuide.classList.contains('hidden')) {
            btnGuide.classList.remove('hidden');
            btnGuide.classList.add('flex');
        }
    }, 4000);

    // Sudah terinstall → sembunyikan semua
    window.addEventListener('appinstalled', () => {
        deferredPrompt = null;
        btnAndroid && (btnAndroid.classList.add('hidden'), btnAndroid.classList.remove('flex'));
        btnGuide   && (btnGuide.classList.add('hidden'),   btnGuide.classList.remove('flex'));
        hideModal();
    });
})();
</script>

{{-- ===== PWA PUSH NOTIFICATION SUBSCRIPTION ===== --}}
<script>
(function() {
    const VAPID_PUBLIC_KEY = '{{ config("app.vapid_public_key", env("VAPID_PUBLIC_KEY", "")) }}';

    if (!VAPID_PUBLIC_KEY || !('serviceWorker' in navigator) || !('PushManager' in window)) return;

    function urlBase64ToUint8Array(base64String) {
        const padding = '='.repeat((4 - base64String.length % 4) % 4);
        const base64 = (base64String + padding).replace(/-/g, '+').replace(/_/g, '/');
        const rawData = window.atob(base64);
        return Uint8Array.from([...rawData].map(c => c.charCodeAt(0)));
    }

    async function subscribePush() {
        try {
            const reg = await navigator.serviceWorker.ready;
            let sub = await reg.pushManager.getSubscription();

            if (!sub) {
                sub = await reg.pushManager.subscribe({
                    userVisibleOnly:      true,
                    applicationServerKey: urlBase64ToUint8Array(VAPID_PUBLIC_KEY),
                });
            }

            const key      = sub.getKey('p256dh');
            const authToken = sub.getKey('auth');

            await fetch('/notifikasi/subscribe', {
                method:  'POST',
                headers: {
                    'Content-Type':  'application/json',
                    'X-CSRF-TOKEN':  document.querySelector('meta[name=csrf-token]').content,
                },
                body: JSON.stringify({
                    endpoint:         sub.endpoint,
                    public_key:       key     ? btoa(String.fromCharCode(...new Uint8Array(key)))      : null,
                    auth_token:       authToken ? btoa(String.fromCharCode(...new Uint8Array(authToken))) : null,
                    content_encoding: (PushManager.supportedContentEncodings || ['aesgcm'])[0],
                }),
            });
        } catch (e) {
            console.warn('[PWA] Push subscribe failed:', e);
        }
    }

    // Minta izin notifikasi setelah pengguna interaksi pertama
    function requestPermission() {
        if (Notification.permission === 'granted') {
            subscribePush();
        } else if (Notification.permission !== 'denied') {
            Notification.requestPermission().then(perm => {
                if (perm === 'granted') subscribePush();
            });
        }
    }

    // Tunggu SW ready lalu subscribe
    navigator.serviceWorker.ready.then(() => {
        if (Notification.permission === 'granted') {
            subscribePush();
        } else {
            // Trigger permission request on first user interaction
            document.addEventListener('click', function onFirstClick() {
                requestPermission();
                document.removeEventListener('click', onFirstClick);
            }, { once: true });
        }
    });
})();
</script>
