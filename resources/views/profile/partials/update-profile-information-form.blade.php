<section>
    <header>
        <h2 class="text-xl font-black text-gray-800">
            {{ __('Informasi Profil') }}
        </h2>
        <p class="mt-1 text-sm text-gray-500 font-bold">
            {{ __("Perbarui nama, email, dan foto profil (avatar) Anda di sini.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6" enctype="multipart/form-data">
        @csrf
        @method('patch')

        <div class="flex items-center gap-6 p-4 bg-gray-50 rounded-3xl border border-gray-100">
            <div class="relative group shrink-0">
                @if($user->avatar)
                    {{-- 🚀 PERBAIKAN: Menggunakan asset() untuk memanggil foto profil sendiri dari storage lokal --}}
                    <img src="{{ asset('storage/' . $user->avatar) }}" 
                         alt="Avatar" 
                         class="w-20 h-20 rounded-2xl object-cover shadow-sm border-2 border-white"
                         onerror="this.onerror=null; this.parentElement.innerHTML='<div class=\'w-20 h-20 rounded-2xl flex items-center justify-center font-black text-2xl bg-slate-100 text-slate-500 border-2 border-white shadow-sm\'>{{ substr($user->name, 0, 1) }}</div>';">
                @else
                    {{-- Tampilan inisial jika tidak ada foto --}}
                    <div class="w-20 h-20 rounded-2xl flex items-center justify-center font-black text-2xl bg-slate-100 text-slate-500 border-2 border-white shadow-sm">
                        {{ substr($user->name, 0, 1) }}
                    </div>
                @endif
            </div>
            
            <div class="flex-grow overflow-hidden">
                <x-input-label for="avatar" :value="__('Ganti Foto Profil (Opsional)')" class="font-black text-[10px] uppercase tracking-widest text-indigo-500 mb-2" />
                <input id="avatar" name="avatar" type="file" accept="image/png, image/jpeg, image/jpg" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2.5 file:px-5 file:rounded-xl file:border-0 file:text-xs file:font-black file:uppercase file:tracking-widest file:bg-indigo-50 file:text-indigo-600 hover:file:bg-indigo-100 transition-all cursor-pointer bg-white border border-gray-100 rounded-2xl shadow-sm" />
                <x-input-error class="mt-2" :messages="$errors->get('avatar')" />
                <p class="text-[10px] font-bold text-gray-400 mt-2 truncate">Format: JPG, JPEG, PNG. Maksimal 2MB.</p>
            </div>
        </div>

        <div>
            <x-input-label for="name" :value="__('Nama Lengkap')" class="font-black" />
            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full rounded-2xl border-gray-200 focus:ring-indigo-500 bg-gray-50 focus:bg-white font-bold" :value="old('name', $user->name)" required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        <div>
            <x-input-label for="email" :value="__('Email (Opsional)')" class="font-black" />
            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full rounded-2xl border-gray-200 focus:ring-indigo-500 bg-gray-50 focus:bg-white font-bold" :value="old('email', $user->email)" autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && ! $user->hasVerifiedEmail() && $user->email)
                <div>
                    <p class="text-sm mt-2 text-gray-800 font-bold">
                        {{ __('Alamat email Anda belum diverifikasi.') }}

                        <button form="send-verification" class="underline text-sm text-indigo-600 hover:text-indigo-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 font-black">
                            {{ __('Klik di sini untuk mengirim ulang email verifikasi.') }}
                        </button>
                    </p>

                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 font-black text-[10px] uppercase tracking-widest text-emerald-600 bg-emerald-50 px-3 py-2 rounded-lg inline-block border border-emerald-100">
                            {{ __('Tautan verifikasi baru telah dikirim ke email Anda.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <div class="flex items-center gap-4 pt-4">
            <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white px-8 py-3 rounded-xl font-black text-xs uppercase tracking-widest shadow-lg shadow-indigo-200 hover:-translate-y-1 transition-all">
                {{ __('Simpan Perubahan 💾') }}
            </button>

            @if (session('status') === 'profile-updated')
                <p
                    x-data="{ show: true }"
                    x-show="show"
                    x-transition
                    x-init="setTimeout(() => show = false, 3000)"
                    class="text-xs font-black uppercase tracking-widest text-emerald-600 bg-emerald-50 border border-emerald-100 px-4 py-2.5 rounded-xl shadow-sm"
                >{{ __('Tersimpan! ✅') }}</p>
            @endif
        </div>
    </form>
</section>