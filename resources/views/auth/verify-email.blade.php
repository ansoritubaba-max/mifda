<x-guest-layout>
    <!-- ================= BAGIAN LOGO & JUDUL ISLAMI ================= -->
    <div class="text-center mb-8">
        <img src="{{ asset('images/logo.png') }}" alt="Logo Yayasan" class="w-24 h-24 mx-auto mb-4 object-contain">
        <h2 class="text-3xl font-black text-emerald-700">Verifikasi Akun</h2>
        <p class="text-sm font-medium text-emerald-600 mt-2">Selangkah lagi untuk mulai menuntut ilmu</p>
    </div>

    <!-- ================= PESAN INSTRUKSI ================= -->
    <div class="mb-6 text-sm text-emerald-800 bg-emerald-50 p-4 rounded-xl border border-emerald-200 leading-relaxed text-justify">
        Terima kasih telah mendaftar! Sebelum memulai, dapatkah Anda memverifikasi akun Anda dengan mengklik tautan yang baru saja kami kirimkan? Jika Anda tidak menerima pesan tersebut, kami dengan senang hati akan mengirimkannya kembali.
    </div>

    <!-- ================= STATUS PENGIRIMAN ULANG ================= -->
    @if (session('status') == 'verification-link-sent')
        <div class="mb-6 p-4 bg-teal-50 border border-teal-200 rounded-xl font-bold text-sm text-teal-700 flex items-center gap-3">
            <span class="text-xl">✅</span>
            Tautan verifikasi baru telah berhasil dikirimkan ke kontak yang Anda berikan saat pendaftaran.
        </div>
    @endif

    <!-- ================= TOMBOL AKSI ================= -->
    <div class="mt-8 flex flex-col sm:flex-row items-center justify-between gap-4">
        
        <!-- Form Kirim Ulang Verifikasi -->
        <form method="POST" action="{{ route('verification.send') }}" class="w-full sm:w-auto">
            @csrf
            <button type="submit" class="w-full sm:w-auto inline-flex justify-center items-center px-6 py-3 bg-gradient-to-r from-emerald-500 to-emerald-600 border border-transparent rounded-xl font-black text-sm text-white uppercase tracking-widest hover:from-emerald-600 hover:to-emerald-700 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 shadow-lg hover:shadow-xl hover:-translate-y-0.5 transition-all duration-200">
                Kirim Ulang Verifikasi
            </button>
        </form>

        <!-- Form Logout / Keluar -->
        <form method="POST" action="{{ route('logout') }}" class="w-full sm:w-auto">
            @csrf
            <button type="submit" class="w-full sm:w-auto text-center px-6 py-3 bg-white border-2 border-emerald-500 rounded-xl font-black text-sm text-emerald-600 uppercase tracking-widest hover:bg-emerald-50 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2 transition-all duration-200 shadow-sm">
                Keluar
            </button>
        </form>

    </div>
</x-guest-layout>