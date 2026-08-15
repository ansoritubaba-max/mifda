<x-guest-layout>
    <!-- ================= BAGIAN LOGO & JUDUL ISLAMI ================= -->
    <div class="text-center mb-8">
        <img src="{{ asset('images/logo.png') }}" alt="Logo Yayasan" class="w-24 h-24 mx-auto mb-4 object-contain">
        <h2 class="text-3xl font-black text-emerald-700">Pendaftaran Akun</h2>
        <p class="text-sm font-medium text-emerald-600 mt-2">Bismillah, mari bergabung menuntut ilmu bersama kami</p>
    </div>

    <!-- Tampilkan Error Sistem jika ada -->
    <x-auth-session-status class="mb-4" :status="session('error')" />
<!-- Tampilkan Error Validasi Bawaan Laravel -->
    @if ($errors->any())
        <div class="mb-6 bg-red-50 border border-red-200 text-red-600 px-5 py-4 rounded-xl shadow-sm animate-[fadeIn_0.3s_ease-in-out]">
            <div class="font-bold flex items-center gap-2 mb-2">
                <span class="text-lg">⚠️</span> 
                <span>Mohon maaf, ada beberapa isian yang perlu diperbaiki:</span>
            </div>
            <ul class="list-disc list-inside text-sm space-y-1 ml-1 text-red-500">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('register') }}" id="registerForm">
        @csrf

        <!-- ================= BAGIAN ORANG TUA (WALI) ================= -->
        <div class="bg-emerald-50 border border-emerald-200 p-5 md:p-6 rounded-2xl mb-6 shadow-sm">
            <div class="flex items-center gap-3 mb-4 border-b border-emerald-200 pb-3">
                <span class="text-2xl">👨‍👩‍👧‍👦</span>
                <h3 class="font-bold text-emerald-800 text-lg">Data Wali (Ayah / Ibu)</h3>
            </div>
            
            <div class="space-y-4">
                <div>
                    <x-input-label for="name" value="Nama Lengkap Wali" class="text-emerald-800 font-bold" />
                    <x-text-input id="name" class="block mt-1 w-full border-emerald-200 focus:border-emerald-500 focus:ring-emerald-500 rounded-xl shadow-sm" type="text" name="name" :value="old('name')" required autofocus />
                </div>

                <div>
                    <x-input-label for="no_telp" value="Nomor WhatsApp (Aktif)" class="text-emerald-800 font-bold" />
                    <x-text-input id="no_telp" class="block mt-1 w-full border-emerald-200 focus:border-emerald-500 focus:ring-emerald-500 rounded-xl shadow-sm" 
                        type="number" 
                        name="no_telp" 
                        :value="old('no_telp')" 
                        placeholder="Contoh: 08123456789" 
                        required />
                </div>

                <div>
                    <x-input-label for="username" value="Username Wali (Untuk Login)" class="text-emerald-800 font-bold" />
                    <x-text-input id="username" class="block mt-1 w-full border-emerald-200 focus:border-emerald-500 focus:ring-emerald-500 rounded-xl shadow-sm" type="text" name="username" :value="old('username')" required />
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <x-input-label for="password" value="Password" class="text-emerald-800 font-bold" />
                        <x-text-input id="password" class="block mt-1 w-full border-emerald-200 focus:border-emerald-500 focus:ring-emerald-500 rounded-xl shadow-sm" type="password" name="password" required />
                    </div>
                    <div>
                        <x-input-label for="password_confirmation" value="Konfirmasi Password" class="text-emerald-800 font-bold" />
                        <x-text-input id="password_confirmation" class="block mt-1 w-full border-emerald-200 focus:border-emerald-500 focus:ring-emerald-500 rounded-xl shadow-sm" type="password" name="password_confirmation" required />
                    </div>
                </div>
                <p class="text-xs text-emerald-600 mt-2 font-medium bg-emerald-100/50 p-2 rounded-lg">
                    <span>💡</span> Password ini otomatis akan digunakan juga sebagai password login untuk akun Ananda.
                </p>
            </div>
        </div>

        <!-- ================= BAGIAN ANAK (DINAMIS) ================= -->
        <div class="bg-teal-50 border border-teal-200 p-5 md:p-6 rounded-2xl mb-6 shadow-sm">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 mb-4 border-b border-teal-200 pb-3">
                <div class="flex items-center gap-3">
                    <span class="text-2xl">🎓</span>
                    <h3 class="font-bold text-teal-800 text-lg">Data Ananda (Siswa)</h3>
                </div>
                <button type="button" onclick="tambahAnak()" class="bg-teal-500 hover:bg-teal-600 text-white px-4 py-2 rounded-xl text-sm font-bold transition-all shadow-sm flex items-center gap-2 w-full sm:w-auto justify-center">
                    <span>➕</span> Tambah Ananda Lain
                </button>
            </div>

            <!-- Container untuk menampung form anak-anak -->
            <div id="container-anak" class="space-y-6">
                
                <!-- Form Anak Pertama (Wajib Ada) -->
                <div class="form-anak bg-white p-5 rounded-xl border border-teal-100 shadow-sm relative transition-all">
                    <div class="space-y-4">
                        <div>
                            <x-input-label value="Nama Lengkap Ananda" class="text-teal-800 font-bold" />
                            <x-text-input class="block mt-1 w-full border-teal-200 focus:border-teal-500 focus:ring-teal-500 rounded-xl" type="text" name="siswa[0][name]" required />
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <x-input-label value="Username Ananda" class="text-teal-800 font-bold" />
                                <x-text-input class="block mt-1 w-full border-teal-200 focus:border-teal-500 focus:ring-teal-500 rounded-xl" type="text" name="siswa[0][username]" required />
                            </div>
                            <div>
                                <x-input-label value="Pilih Kelas" class="text-teal-800 font-bold" />
                                <select name="siswa[0][kelas_id]" class="block mt-1 w-full border-teal-200 focus:border-teal-500 focus:ring-teal-500 rounded-xl shadow-sm text-gray-700" required>
                                    <option value="">-- Silakan Pilih Kelas --</option>
                                    @foreach($daftarKelas as $kls)
                                        <option value="{{ $kls->id }}">{{ $kls->nama_kelas ?? $kls->nama }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div>
                            <x-input-label value="Kode Penghubung dari Sekolah (Opsional)" class="text-teal-800 font-bold" />
                            <x-text-input class="block mt-1 w-full border-teal-200 focus:border-teal-500 focus:ring-teal-500 rounded-xl" type="text" inputmode="numeric" maxlength="6" pattern="[0-9]{6}" name="siswa[0][kode_penghubung]" placeholder="6 digit, dikirim via WA dari sekolah" />
                            <p class="text-xs text-teal-600 mt-1">Belum punya kodenya? Lewati saja, bisa dihubungkan nanti lewat dashboard.</p>
                        </div>
                    </div>
                </div>

            </div>
        </div>

        <!-- ================= TOMBOL SUBMIT & LINK LOGIN ================= -->
        <div class="flex flex-col-reverse sm:flex-row items-center justify-between gap-4 mt-8">
            <a class="text-sm font-bold text-emerald-600 hover:text-emerald-800 underline decoration-2 underline-offset-4 focus:outline-none focus:ring-2 focus:ring-emerald-500 transition-colors" href="{{ route('login') }}">
                Sudah punya akun? Masuk di sini
            </a>

            <button type="submit" class="w-full sm:w-auto inline-flex justify-center items-center px-8 py-3 bg-gradient-to-r from-emerald-500 to-emerald-600 border border-transparent rounded-xl font-black text-sm text-white uppercase tracking-widest hover:from-emerald-600 hover:to-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 shadow-lg hover:shadow-xl hover:-translate-y-0.5 transition-all duration-200">
                Daftar & Buat Akun
            </button>
        </div>
    </form>

    <!-- Script untuk menduplikasi form anak -->
    <script>
        let jumlahAnak = 1; 

        function tambahAnak() {
            const container = document.getElementById('container-anak');
            
            const htmlBaru = `
                <div class="form-anak bg-white p-5 rounded-xl border border-teal-100 shadow-sm relative mt-4 animate-[fadeIn_0.3s_ease-in-out]">
                    <button type="button" onclick="hapusAnak(this)" class="absolute -top-3 -right-3 bg-red-100 text-red-600 border border-red-200 rounded-full w-8 h-8 font-black hover:bg-red-500 hover:text-white transition-colors shadow-sm flex items-center justify-center" title="Batal Tambah">✕</button>
                    <div class="space-y-4">
                        <div>
                            <label class="block font-bold text-sm text-teal-800">Nama Lengkap Ananda (Ke-${jumlahAnak + 1})</label>
                            <input type="text" name="siswa[${jumlahAnak}][name]" class="block mt-1 w-full border-teal-200 focus:border-teal-500 focus:ring-teal-500 rounded-xl shadow-sm text-gray-700" required />
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block font-bold text-sm text-teal-800">Username Ananda</label>
                                <input type="text" name="siswa[${jumlahAnak}][username]" class="block mt-1 w-full border-teal-200 focus:border-teal-500 focus:ring-teal-500 rounded-xl shadow-sm text-gray-700" required />
                            </div>
                            <div>
                                <label class="block font-bold text-sm text-teal-800">Pilih Kelas</label>
                                <select name="siswa[${jumlahAnak}][kelas_id]" class="block mt-1 w-full border-teal-200 focus:border-teal-500 focus:ring-teal-500 rounded-xl shadow-sm text-gray-700" required>
                                    <option value="">-- Silakan Pilih Kelas --</option>
                                    @foreach($daftarKelas as $kls)
                                        <option value="{{ $kls->id }}">{{ $kls->nama_kelas ?? $kls->nama }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div>
                            <label class="block font-bold text-sm text-teal-800">Kode Penghubung dari Sekolah (Opsional)</label>
                            <input type="text" inputmode="numeric" maxlength="6" pattern="[0-9]{6}" name="siswa[${jumlahAnak}][kode_penghubung]" class="block mt-1 w-full border-teal-200 focus:border-teal-500 focus:ring-teal-500 rounded-xl shadow-sm text-gray-700" placeholder="6 digit, dikirim via WA dari sekolah" />
                            <p class="text-xs text-teal-600 mt-1">Belum punya kodenya? Lewati saja, bisa dihubungkan nanti lewat dashboard.</p>
                        </div>
                    </div>
                </div>
            `;
            
            container.insertAdjacentHTML('beforeend', htmlBaru);
            jumlahAnak++;
        }

        function hapusAnak(elemenTombol) {
            elemenTombol.parentElement.remove();
        }
    </script>
</x-guest-layout>