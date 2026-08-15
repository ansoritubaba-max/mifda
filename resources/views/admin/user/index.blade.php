<x-app-layout>
    <style>
        [x-cloak] { display: none !important; }
        /* Scrollbar custom premium untuk daftar user */
        .scrollbar-custom::-webkit-scrollbar { width: 8px; height: 8px; }
        .scrollbar-custom::-webkit-scrollbar-track { background: #f8fafc; border-radius: 10px; margin-block: 5px; }
        .scrollbar-custom::-webkit-scrollbar-thumb { background-color: #cbd5e1; border-radius: 10px; border: 2px solid #f8fafc; }
        .scrollbar-custom::-webkit-scrollbar-thumb:hover { background-color: #94a3b8; }
    </style>

    <x-slot name="header">
        <div class="flex items-center gap-3 md:gap-4 relative z-10">
            <a href="{{ route('admin.dashboard') }}" class="w-10 h-10 md:w-12 md:h-12 bg-white rounded-xl md:rounded-2xl shadow-sm border border-slate-200 hover:bg-slate-50 hover:shadow-md transition-all transform hover:-translate-x-1 flex items-center justify-center text-slate-500 hover:text-blue-600 shrink-0 group">
                <span class="group-hover:-translate-x-1 transition-transform">⬅️</span>
            </a>
            
            <h2 class="font-black text-xl md:text-3xl text-white tracking-tight flex items-center gap-2 md:gap-3">
                <span class="text-2xl md:text-3xl bg-white/10 p-2 rounded-xl md:rounded-2xl border border-white/20">👥</span>
                Kelola Identitas Pengguna
            </h2>
        </div>
    </x-slot>

    <div class="py-6 md:py-12 bg-slate-50 min-h-screen relative overflow-hidden">
        
        <div class="absolute top-0 right-0 w-[30rem] h-[30rem] bg-emerald-200 rounded-full mix-blend-multiply filter blur-[100px] opacity-20 pointer-events-none -translate-y-1/2 translate-x-1/3"></div>
        <div class="absolute bottom-0 left-0 w-[30rem] h-[30rem] bg-blue-200 rounded-full mix-blend-multiply filter blur-[100px] opacity-20 pointer-events-none translate-y-1/3 -translate-x-1/3"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 grid grid-cols-1 lg:grid-cols-12 gap-6 md:gap-8 relative z-10">
            
            <div class="lg:col-span-4 lg:sticky lg:top-24 h-fit">
                <div class="bg-white rounded-[2rem] md:rounded-[2.5rem] shadow-xl shadow-slate-200/50 border border-slate-100 overflow-hidden">
                    
                    <div class="h-3 w-full bg-gradient-to-r from-blue-500 to-indigo-600"></div>

                    <div class="p-6 md:p-8">
                        <div class="flex items-center gap-3 mb-6">
                            <div class="w-12 h-12 bg-gradient-to-br from-blue-50 to-indigo-100 text-blue-600 rounded-[1.25rem] flex items-center justify-center text-2xl shadow-sm border border-blue-100">➕</div>
                            <h3 class="font-black text-lg md:text-xl text-slate-800 leading-tight">Tambah Akun Baru</h3>
                        </div>
                        
                        @if(session('success'))
                            <div class="bg-gradient-to-r from-emerald-50 to-teal-50 text-emerald-700 p-4 rounded-2xl mb-6 text-xs font-black uppercase tracking-widest flex items-center gap-3 border border-emerald-200 shadow-sm animate-fade-in-down">
                                <span class="text-xl">✅</span> {{ session('success') }}
                            </div>
                        @endif

                        <form action="{{ route('admin.user.store') }}" method="POST" class="space-y-4 md:space-y-5" enctype="multipart/form-data">
                            @csrf
                            
                            <div>
                                <label class="block text-[10px] md:text-xs font-black text-slate-500 uppercase tracking-widest mb-2 px-1">Foto Profil (Opsional)</label>
                                <div class="relative bg-slate-50 border border-slate-200 rounded-2xl shadow-sm hover:border-blue-300 transition-colors">
                                    <input type="file" name="avatar" accept="image/png, image/jpeg, image/jpg" class="block w-full text-xs text-slate-500 file:mr-4 file:py-2.5 file:px-5 file:rounded-xl file:border-0 file:text-[10px] file:font-black file:uppercase file:tracking-widest file:bg-blue-100 file:text-blue-700 hover:file:bg-blue-200 transition-all cursor-pointer">
                                </div>
                            </div>

                            <div>
                                <label class="block text-[10px] md:text-xs font-black text-slate-500 uppercase tracking-widest mb-2 px-1">Nama Lengkap <span class="text-rose-500">*</span></label>
                                <input type="text" name="name" class="w-full rounded-2xl border border-slate-200 bg-slate-50 focus:bg-white focus:ring-2 focus:ring-blue-500 focus:border-transparent font-bold text-slate-800 px-4 py-3.5 transition-all shadow-sm" required>
                            </div>

                            <div class="grid grid-cols-2 gap-3 md:gap-4">
                                <div>
                                    <label class="block text-[10px] md:text-xs font-black text-slate-500 uppercase tracking-widest mb-2 px-1">Username/Email <span class="text-rose-500">*</span></label>
                                    <input type="text" name="username" class="w-full rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:ring-2 focus:ring-blue-500 focus:border-transparent font-bold text-slate-800 px-4 py-3 transition-all shadow-sm text-sm" required>
                                </div>
                                <div>
                                    <label class="block text-[10px] md:text-xs font-black text-slate-500 uppercase tracking-widest mb-2 px-1">Password <span class="text-rose-500">*</span></label>
                                    <input type="password" name="password" class="w-full rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:ring-2 focus:ring-blue-500 focus:border-transparent font-bold text-slate-800 px-4 py-3 transition-all shadow-sm text-sm" required>
                                </div>
                            </div>

                            <div class="p-4 bg-slate-50 border border-slate-200 rounded-2xl shadow-inner mt-2">
                                <label class="block text-[10px] md:text-xs font-black text-blue-600 uppercase tracking-widest mb-2 px-1">Pilih Peran (Role) <span class="text-rose-500">*</span></label>
                                <div class="relative">
                                    <select name="role" id="roleSelect" class="w-full rounded-xl border border-blue-200 bg-white text-blue-800 font-black px-4 py-3.5 focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all cursor-pointer shadow-sm appearance-none" required>
                                        <option value="" disabled selected>-- Pilih Peran --</option>
                                        <option value="siswa">👦 Siswa</option>
                                        <option value="guru">👩‍🏫 Guru</option>
                                        <option value="ortu">👨‍👩‍👧 Orang Tua</option>
                                        <option value="admin">👑 Admin</option>
                                    </select>
                                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-4 text-blue-600">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"></path></svg>
                                    </div>
                                </div>
                            </div>

                            <div id="siswaFields" class="hidden space-y-4 p-5 bg-gradient-to-br from-blue-50 to-indigo-50 border border-blue-100 rounded-2xl relative overflow-hidden">
                                <div class="absolute top-0 right-0 w-2 h-full bg-blue-400"></div>
                                <div>
                                    <label class="block text-[10px] md:text-xs font-black text-blue-600 uppercase tracking-widest mb-2">NISN (Opsional)</label>
                                    <input type="text" name="nisn" class="w-full rounded-xl border border-white focus:border-blue-300 focus:ring-blue-500 bg-white font-bold text-slate-700 text-sm px-4 py-2.5 shadow-sm transition-all">
                                </div>
                                <div>
                                    <label class="block text-[10px] md:text-xs font-black text-blue-600 uppercase tracking-widest mb-2">Kelas Siswa</label>
                                    <div class="relative">
                                        <select name="kelas_id" class="w-full rounded-xl border border-white focus:border-blue-300 focus:ring-blue-500 bg-white font-bold text-slate-700 text-sm px-4 py-2.5 shadow-sm appearance-none cursor-pointer transition-all">
                                            <option value="">-- Pilih Kelas --</option>
                                            @foreach($kelas as $k)
                                                <option value="{{ $k->id }}">{{ $k->nama_kelas }}</option>
                                            @endforeach
                                        </select>
                                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-blue-400"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg></div>
                                    </div>
                                </div>
                            </div>

                            <div id="kontakFields" class="hidden space-y-4 p-5 bg-gradient-to-br from-emerald-50 to-teal-50 border border-emerald-100 rounded-2xl relative overflow-hidden">
                                <div class="absolute top-0 right-0 w-2 h-full bg-emerald-400"></div>
                                <div>
                                    <label class="block text-[10px] md:text-xs font-black text-emerald-600 uppercase tracking-widest mb-2">No. Handphone</label>
                                    <input type="text" name="no_telp" class="w-full rounded-xl border border-white focus:border-emerald-300 focus:ring-emerald-500 bg-white font-bold text-slate-700 text-sm px-4 py-2.5 shadow-sm transition-all">
                                </div>
                                <div>
                                    <label class="block text-[10px] md:text-xs font-black text-emerald-600 uppercase tracking-widest mb-2">Alamat Lengkap</label>
                                    <textarea name="alamat" class="w-full rounded-xl border border-white focus:border-emerald-300 focus:ring-emerald-500 bg-white font-bold text-slate-700 text-sm px-4 py-2.5 shadow-sm transition-all resize-y" rows="2"></textarea>
                                </div>
                            </div>

                            <button type="submit" class="w-full bg-gradient-to-r from-blue-600 to-indigo-600 text-white rounded-2xl py-4 font-black uppercase tracking-widest text-[10px] md:text-xs shadow-lg shadow-blue-200/50 hover:shadow-xl hover:-translate-y-1 active:scale-95 transition-all mt-6 border border-blue-400/50">
                                Simpan Akun 🚀
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="lg:col-span-8 h-full" x-data="{ activeTab: 'siswa', searchUser: '' }">
                <div class="bg-white rounded-[2rem] md:rounded-[2.5rem] shadow-xl shadow-slate-200/40 border border-slate-100 p-6 md:p-8 h-full flex flex-col">
                    
                    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4 border-b border-slate-100 pb-6 shrink-0">
                        <h3 class="font-black text-xl md:text-2xl text-slate-800 flex items-center gap-3">
                            <span class="p-2 bg-slate-50 border border-slate-200 rounded-xl shadow-sm">📋</span> 
                            Daftar Pengguna
                        </h3>
                        
                        <div class="relative w-full md:w-72">
                            <span class="absolute inset-y-0 left-0 flex items-center pl-4 text-slate-400">🔍</span>
                            <input type="text" x-model="searchUser" placeholder="Cari nama / username..." class="w-full rounded-full border border-slate-200 bg-slate-50 focus:bg-white text-sm px-4 py-2.5 pl-11 focus:ring-2 focus:ring-blue-500 focus:border-transparent font-bold text-slate-700 shadow-sm transition-all">
                        </div>
                    </div>

                    <div class="flex gap-2 overflow-x-auto pb-4 mb-2 scrollbar-custom shrink-0">
                        <button @click="activeTab = 'siswa'; searchUser=''" :class="activeTab === 'siswa' ? 'bg-blue-600 text-white shadow-lg shadow-blue-200/50 border-transparent' : 'bg-slate-50 border-slate-200 text-slate-500 hover:bg-slate-100'" class="px-5 md:px-6 py-2.5 rounded-xl md:rounded-2xl font-black text-[10px] md:text-xs uppercase tracking-widest transition-all whitespace-nowrap border">👦 Siswa</button>
                        <button @click="activeTab = 'guru'; searchUser=''" :class="activeTab === 'guru' ? 'bg-emerald-500 text-white shadow-lg shadow-emerald-200/50 border-transparent' : 'bg-slate-50 border-slate-200 text-slate-500 hover:bg-slate-100'" class="px-5 md:px-6 py-2.5 rounded-xl md:rounded-2xl font-black text-[10px] md:text-xs uppercase tracking-widest transition-all whitespace-nowrap border">👩‍🏫 Guru</button>
                        <button @click="activeTab = 'ortu'; searchUser=''" :class="activeTab === 'ortu' ? 'bg-orange-500 text-white shadow-lg shadow-orange-200/50 border-transparent' : 'bg-slate-50 border-slate-200 text-slate-500 hover:bg-slate-100'" class="px-5 md:px-6 py-2.5 rounded-xl md:rounded-2xl font-black text-[10px] md:text-xs uppercase tracking-widest transition-all whitespace-nowrap border">👨‍👩‍👧 Ortu</button>
                        <button @click="activeTab = 'admin'; searchUser=''" :class="activeTab === 'admin' ? 'bg-rose-500 text-white shadow-lg shadow-rose-200/50 border-transparent' : 'bg-slate-50 border-slate-200 text-slate-500 hover:bg-slate-100'" class="px-5 md:px-6 py-2.5 rounded-xl md:rounded-2xl font-black text-[10px] md:text-xs uppercase tracking-widest transition-all whitespace-nowrap border">👑 Admin</button>
                    </div>

                    <div class="overflow-y-auto flex-grow pr-2 scrollbar-custom max-h-[500px] scroll-smooth border border-slate-50 rounded-2xl p-1">
                        <table class="w-full text-left">
                            <tbody class="divide-y divide-slate-100 text-sm block md:table-row-group">
                                @foreach($users as $u)
                                    <tr x-show="activeTab === '{{ $u->role }}' && ('{{ strtolower($u->name) }}'.includes(searchUser.toLowerCase()) || '{{ strtolower($u->username) }}'.includes(searchUser.toLowerCase()))" 
                                        x-transition:enter="transition ease-out duration-300 transform"
                                        x-transition:enter-start="opacity-0 translate-y-4"
                                        x-transition:enter-end="opacity-100 translate-y-0"
                                        class="hover:bg-slate-50/80 transition-colors group block md:table-row border-b border-slate-100 md:border-0 mb-4 md:mb-0" 
                                        x-data="{ openEdit: false }">
                                        
                                        <td class="py-4 md:py-5 pl-2 block md:table-cell">
                                            <div class="flex items-center gap-4">
                                                @if($u->avatar)
                                                    {{-- 🚀 PERBAIKAN: Menggunakan asset() untuk memanggil foto user dari storage lokal --}}
                                                    <img src="{{ asset('storage/' . $u->avatar) }}" 
                                                        alt="Avatar" 
                                                        class="w-12 h-12 md:w-14 md:h-14 rounded-[1rem] object-cover shadow-sm shrink-0 border border-slate-200">
                                                @else
                                                    {{-- Inisial Nama --}}
                                                    <div class="w-12 h-12 md:w-14 md:h-14 rounded-[1rem] flex items-center justify-center font-black text-white shadow-sm shrink-0 text-xl
                                                        {{ $u->role == 'siswa' ? 'bg-gradient-to-br from-blue-400 to-indigo-500' : ($u->role == 'guru' ? 'bg-gradient-to-br from-emerald-400 to-teal-500' : ($u->role == 'ortu' ? 'bg-gradient-to-br from-orange-400 to-amber-500' : 'bg-gradient-to-br from-rose-400 to-red-500')) }}">
                                                        {{ substr($u->name, 0, 1) }}
                                                    </div>
                                                @endif

                                                <div>
                                                    <h4 class="font-black text-slate-800 text-base md:text-lg group-hover:text-blue-600 transition-colors">{{ $u->name }}</h4>
                                                    <span class="text-[9px] md:text-[10px] font-bold text-slate-400 uppercase tracking-widest bg-slate-100 px-2 py-0.5 rounded-md inline-block mt-1">@ {{ $u->username }}</span>
                                                    
                                                    @if($u->role == 'siswa') 
                                                        <div class="text-[9px] md:text-[10px] font-black text-blue-500 mt-1.5 flex items-center gap-1">📍 Kelas: {{ $u->kelas->nama_kelas ?? 'Tanpa Kelas' }}</div>
                                                    @elseif($u->role == 'guru' || $u->role == 'ortu') 
                                                        <div class="text-[9px] md:text-[10px] font-black text-emerald-500 mt-1.5 flex items-center gap-1">📞 {{ $u->no_telp ?? 'No HP Tidak Ada' }}</div> 
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        
                                        <td class="py-4 md:py-5 text-left md:text-right pr-2 block md:table-cell mt-2 md:mt-0 align-middle">
                                            <div class="flex flex-col md:flex-row items-center justify-end gap-2">
                                                
                                                <button @click="openEdit = true" class="w-full md:w-auto bg-slate-50 hover:bg-amber-50 text-slate-500 hover:text-amber-600 px-4 py-2.5 rounded-xl md:rounded-2xl text-[10px] md:text-xs font-black uppercase tracking-widest transition-all shadow-sm border border-slate-200 hover:border-amber-300 flex items-center justify-center gap-1.5">
                                                    <span>✏️</span> <span class="hidden md:inline">Edit</span>
                                                </button>

                                                <form action="{{ route('admin.user.destroy', $u->id) }}" method="POST" class="w-full md:w-auto m-0" onsubmit="return confirm('⚠️ PERINGATAN!\n\nApakah Anda yakin ingin menghapus akun {{ strtoupper($u->name) }}?\n\nSemua data yang berhubungan (nilai, tugas, riwayat) akan ikut terhapus dan TIDAK BISA KEMBALI!');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="w-full md:w-auto bg-slate-50 hover:bg-rose-50 text-slate-500 hover:text-rose-600 px-4 py-2.5 rounded-xl md:rounded-2xl text-[10px] md:text-xs font-black uppercase tracking-widest transition-all shadow-sm border border-slate-200 hover:border-rose-300 flex items-center justify-center gap-1.5">
                                                        <span>🗑️</span> <span class="hidden md:inline">Hapus</span>
                                                    </button>
                                                </form>
                                                
                                            </div>

                                            <div x-show="openEdit" style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center bg-slate-900/60 backdrop-blur-sm px-4 text-left" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0">
                                                
                                                <div @click.away="openEdit = false" class="bg-white rounded-[2rem] md:rounded-[2.5rem] shadow-2xl w-full max-w-lg p-6 md:p-8 text-left relative max-h-[90vh] overflow-y-auto scrollbar-custom border border-slate-100" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 scale-95" x-transition:enter-end="opacity-100 translate-y-0 scale-100" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 scale-100" x-transition:leave-end="opacity-0 translate-y-4 scale-95">
                                                    
                                                    <button @click="openEdit = false" type="button" class="absolute top-5 right-5 w-8 h-8 flex items-center justify-center bg-slate-50 text-slate-400 hover:text-rose-500 hover:bg-rose-50 rounded-full font-black text-lg transition-colors border border-slate-100">✕</button>
                                                    
                                                    <div class="flex items-center gap-3 mb-6 border-b border-slate-100 pb-4">
                                                        <span class="p-2 bg-amber-50 text-amber-600 rounded-xl text-xl border border-amber-100 shadow-sm">✏️</span>
                                                        <h3 class="font-black text-xl md:text-2xl text-slate-800">Edit Data Pengguna</h3>
                                                    </div>
                                                    
                                                    <form action="{{ route('admin.user.update', $u->id) }}" method="POST" class="space-y-4 md:space-y-5" enctype="multipart/form-data">
                                                        @csrf @method('PUT')
                                                        <input type="hidden" name="role" value="{{ $u->role }}">

                                                            <div class="flex items-center gap-4 p-4 bg-slate-50 rounded-2xl border border-slate-200 mb-2">
                                                                @if($u->avatar)
                                                                    {{-- 🚀 PERBAIKAN: Menggunakan asset() untuk foto user di modal edit --}}
                                                                    <img src="{{ asset('storage/' . $u->avatar) }}" 
                                                                        alt="Avatar" 
                                                                        class="w-14 h-14 rounded-[1rem] object-cover shadow-sm border-2 border-white shrink-0">
                                                                @else
                                                                    {{-- Tampilan inisial --}}
                                                                    <div class="w-14 h-14 rounded-[1rem] bg-slate-200 flex items-center justify-center text-xl font-black text-slate-400 shrink-0 border-2 border-white">
                                                                        {{ substr($u->name, 0, 1) }}
                                                                    </div>
                                                                @endif
                                                                
                                                                <div class="flex-grow overflow-hidden">
                                                                    <label class="block text-[10px] md:text-xs font-black text-slate-500 uppercase tracking-widest mb-1.5">Ganti Foto Profil (Opsional)</label>
                                                                    <input type="file" name="avatar" accept="image/png, image/jpeg, image/jpg" 
                                                                        class="w-full rounded-xl border border-slate-200 bg-white font-bold px-2 py-1.5 text-xs file:mr-3 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-[10px] file:font-black file:uppercase file:bg-blue-50 file:text-blue-600 hover:file:bg-blue-100 cursor-pointer shadow-sm transition-all">
                                                                </div>
                                                            </div>

                                                        <div><label class="block text-[10px] md:text-xs font-black text-slate-500 uppercase tracking-widest mb-1.5">Nama Lengkap</label><input type="text" name="name" value="{{ $u->name }}" class="w-full rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:ring-2 focus:ring-amber-400 font-bold px-4 py-3 text-sm md:text-base text-slate-800 transition-all shadow-sm" required></div>
                                                        <div><label class="block text-[10px] md:text-xs font-black text-slate-500 uppercase tracking-widest mb-1.5">Username / Email</label><input type="text" name="username" value="{{ $u->username }}" class="w-full rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:ring-2 focus:ring-amber-400 font-bold px-4 py-3 text-sm md:text-base text-slate-800 transition-all shadow-sm" required></div>
                                                        <div><label class="block text-[10px] md:text-xs font-black text-slate-500 uppercase tracking-widest mb-1.5">Password Baru (Opsional)</label><input type="password" name="password" placeholder="Kosongkan jika tidak diganti" class="w-full rounded-xl border border-slate-200 bg-slate-50 focus:bg-white focus:ring-2 focus:ring-amber-400 font-bold px-4 py-3 text-sm md:text-base text-slate-800 transition-all shadow-sm"></div>
                                                        
                                                        @if($u->role === 'siswa')
                                                            <div class="grid grid-cols-2 gap-3 md:gap-4 p-4 bg-blue-50/50 border border-blue-100 rounded-2xl">
                                                                <div><label class="block text-[10px] md:text-xs font-black text-blue-600 uppercase tracking-widest mb-1.5">NISN</label><input type="text" name="nisn" value="{{ $u->nisn }}" class="w-full rounded-xl border border-white focus:border-blue-300 focus:ring-blue-500 bg-white font-bold px-3 py-2.5 text-sm text-slate-700 shadow-sm transition-all"></div>
                                                                <div><label class="block text-[10px] md:text-xs font-black text-blue-600 uppercase tracking-widest mb-1.5">Kelas</label>
                                                                    <div class="relative">
                                                                        <select name="kelas_id" class="w-full rounded-xl border border-white focus:border-blue-300 focus:ring-blue-500 bg-white font-bold px-3 py-2.5 text-sm text-slate-700 shadow-sm appearance-none cursor-pointer transition-all">
                                                                            <option value="">-- Pilih Kelas --</option>
                                                                            @foreach($kelas as $k)<option value="{{ $k->id }}" {{ $u->kelas_id == $k->id ? 'selected' : '' }}>{{ $k->nama_kelas }}</option>@endforeach
                                                                        </select>
                                                                        <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-blue-400"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg></div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @elseif($u->role === 'ortu' || $u->role === 'guru')
                                                            <div class="space-y-4 p-4 bg-emerald-50/50 border border-emerald-100 rounded-2xl">
                                                                <div><label class="block text-[10px] md:text-xs font-black text-emerald-600 uppercase tracking-widest mb-1.5">No Handphone</label><input type="text" name="no_telp" value="{{ $u->no_telp }}" class="w-full rounded-xl border border-white focus:border-emerald-300 focus:ring-emerald-500 bg-white font-bold px-4 py-2.5 text-sm text-slate-700 shadow-sm transition-all"></div>
                                                                <div><label class="block text-[10px] md:text-xs font-black text-emerald-600 uppercase tracking-widest mb-1.5">Alamat Lengkap</label><textarea name="alamat" class="w-full rounded-xl border border-white focus:border-emerald-300 focus:ring-emerald-500 bg-white font-bold px-4 py-2.5 text-sm text-slate-700 shadow-sm transition-all resize-y" rows="2">{{ $u->alamat }}</textarea></div>
                                                            </div>
                                                        @endif
                                                        
                                                        <button type="submit" class="w-full bg-gradient-to-r from-amber-400 to-orange-500 text-white border border-amber-400 rounded-xl md:rounded-2xl py-4 font-black uppercase tracking-widest text-[10px] md:text-xs shadow-lg shadow-amber-200/50 mt-6 hover:-translate-y-1 hover:shadow-xl active:scale-95 transition-all flex items-center justify-center gap-2">
                                                            Simpan Perubahan <span class="text-base md:text-lg">💾</span>
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                            
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const roleSelect = document.getElementById('roleSelect');
            const siswaFields = document.getElementById('siswaFields');
            const kontakFields = document.getElementById('kontakFields');
            function toggleFields() {
                const role = roleSelect.value;
                siswaFields.classList.add('hidden'); kontakFields.classList.add('hidden');
                if (role === 'siswa') siswaFields.classList.remove('hidden');
                else if (role === 'ortu' || role === 'guru') kontakFields.classList.remove('hidden');
            }
            roleSelect.addEventListener('change', toggleFields); toggleFields();
        });
    </script>
</x-app-layout>