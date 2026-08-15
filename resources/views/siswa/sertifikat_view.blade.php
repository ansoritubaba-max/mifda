<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sertifikat Penghargaan - {{ $user->name }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style> 
        /* Kombinasi font serif klasik (Playfair) untuk kesan resmi dan sans-serif (Nunito) untuk keterbacaan */
        @import url('https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,700;0,900;1,400;1,700&family=Nunito:wght@400;700;900&display=swap');
        
        body { 
            font-family: 'Nunito', sans-serif; 
            background-color: #f1f5f9; /* Slate 100 */
        }
        .font-serif-custom { font-family: 'Playfair Display', serif; }
        
        /* Memaksa ukuran kertas landscape A4 (1122px x 793px) di layar */
        .cert-wrapper {
            width: 1122px;
            min-height: 793px;
            transform-origin: top center;
        }

        /* Pengaturan Khusus Saat Dicetak (Ctrl+P) */
        @media print { 
            @page { size: A4 landscape; margin: 0; }
            body { 
                background-color: white; 
                -webkit-print-color-adjust: exact !important; 
                print-color-adjust: exact !important; 
            }
            .no-print { display: none !important; } 
            
            /* Reset wrapper untuk mode cetak agar pas di kertas */
            .cert-wrapper { 
                width: 100% !important; 
                height: 100vh !important;
                box-shadow: none !important; 
                margin: 0 !important; 
                padding: 0 !important;
                transform: scale(1) !important;
            }
            .print-border-container {
                padding: 15px !important;
            }
        } 
    </style>
</head>
<body class="flex flex-col items-center justify-center min-h-screen py-10">

    <div class="w-full max-w-[100vw] overflow-x-auto pb-8 flex justify-center px-4">
        
        <div class="cert-wrapper bg-white shadow-2xl relative p-3 md:p-4 shrink-0">
            
            <div class="bg-gradient-to-br from-emerald-700 via-teal-600 to-blue-800 p-2 md:p-3 h-full relative print-border-container shadow-inner">
                
                <div class="border-[3px] border-amber-400 h-full p-12 relative flex flex-col justify-between overflow-hidden bg-[#fffcf5] shadow-2xl">
                    
                    <div class="absolute inset-0 flex items-center justify-center opacity-[0.03] pointer-events-none">
                        <span class="text-[500px]">🏆</span>
                    </div>

                    <div class="text-center relative z-10 mt-4">
                        <h1 class="text-5xl md:text-6xl font-serif-custom font-black text-emerald-800 uppercase tracking-widest mb-4 drop-shadow-sm">Sertifikat Penghargaan</h1>
                        <p class="text-lg font-black text-amber-600 uppercase tracking-[0.4em]">Madrasah Ibtidaiyah Miftahul Huda</p>
                        <p class="text-sm font-bold text-slate-500 uppercase tracking-widest mt-1">Gunung Menanti, Tumijajar, Lampung · Yayasan Nahdlatut Tholab</p>
                    </div>
                    
                    <div class="text-center relative z-10 flex-grow flex flex-col items-center justify-center mt-6">
                        
                        <div class="w-36 h-36 rounded-full border-4 border-amber-400 shadow-xl overflow-hidden mb-6 relative bg-white flex shrink-0 z-20 ring-4 ring-emerald-800/10">
                            @if($user->avatar)
                                {{-- 🚀 PERBAIKAN: Menggunakan asset() untuk memanggil foto siswa agar muncul di cetakan sertifikat --}}
                                <img src="{{ asset('storage/' . $user->avatar) }}" alt="Foto {{ $user->name }}" class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-emerald-50 to-teal-100 text-emerald-800 text-6xl font-black font-serif-custom">
                                    {{ substr($user->name, 0, 1) }}
                                </div>
                            @endif
                        </div>

                        <p class="text-xl italic text-slate-600 font-serif-custom mb-2">Diberikan dengan rasa bangga kepada:</p>
                        
                        <h2 class="text-5xl md:text-6xl font-black text-slate-900 my-4 pb-3 border-b-[3px] border-amber-500 inline-block px-16 font-serif-custom tracking-wide">
                            {{ $user->name }}
                        </h2>
                        
                        <p class="text-xl text-slate-700 leading-relaxed max-w-4xl mx-auto mt-4 font-serif-custom">
                            Atas dedikasi, semangat pantang menyerah, dan ketekunannya dalam proses pembelajaran interaktif, sehingga berhasil menorehkan prestasi gemilang sebagai
                            <span class="font-black text-emerald-700 text-2xl mx-1 block my-2">Peringkat {{ $peringkat }} di {{ $user->kelas->nama_kelas ?? 'Kelasnya' }}</span>
                            dengan total perolehan <strong class="text-amber-600 text-xl font-sans bg-amber-50 px-3 py-1 rounded-lg border border-amber-200">⚡ {{ number_format($user->xp, 0, ',', '.') }} XP</strong>.
                        </p>
                    </div>
                    
                    <div class="mt-12 flex justify-between items-end px-16 text-center relative z-10">
                        
                        <div class="w-64">
                            <p class="mb-20 text-slate-600 font-serif-custom text-lg italic">Mengetahui,</p>
                            <div class="border-t-2 border-slate-800 pt-2 font-black text-slate-900 text-lg uppercase tracking-widest font-serif-custom">
                                Kepala Madrasah
                            </div>
                        </div>
                        
                        <div class="w-40 h-40 bg-gradient-to-br from-amber-300 via-yellow-400 to-amber-600 rounded-full flex flex-col items-center justify-center border-4 border-white shadow-2xl relative -mt-12 outline outline-2 outline-amber-500 outline-offset-2 ring-8 ring-amber-100">
                            <div class="absolute inset-2 border-2 border-dashed border-amber-700/30 rounded-full"></div>
                            
                            <div class="font-black text-amber-900 text-center leading-none relative z-10">
                                <span class="text-xs font-bold tracking-[0.2em] uppercase block mb-1">Peringkat</span>
                                <span class="text-5xl md:text-6xl drop-shadow-sm font-serif-custom">{{ $peringkat }}</span>
                            </div>
                        </div>

                        <div class="w-64">
                            <p class="mb-20 text-slate-600 font-serif-custom text-lg italic">Diterbitkan pada: {{ now()->translatedFormat('d F Y') }}</p>
                            <div class="border-t-2 border-slate-800 pt-2 font-black text-slate-900 text-lg uppercase tracking-widest font-serif-custom">
                                Wali Kelas
                            </div>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </div>

    <div class="text-center mt-6 mb-16 no-print flex flex-col items-center px-4">
        <button onclick="window.print()" class="bg-gradient-to-r from-emerald-600 to-blue-700 text-white px-10 py-5 rounded-2xl font-black text-lg md:text-xl shadow-xl shadow-blue-900/20 hover:-translate-y-1 hover:scale-105 transition-all uppercase tracking-widest flex items-center gap-3 border-2 border-white">
            <span class="text-3xl">🖨️</span> Cetak & Simpan PDF
        </button>
        <div class="mt-6 bg-white p-5 rounded-2xl border border-slate-200 shadow-sm max-w-lg text-left relative overflow-hidden">
            <div class="absolute top-0 left-0 w-2 h-full bg-amber-400"></div>
            <p class="text-slate-600 font-bold text-sm leading-relaxed pl-4">
                💡 <strong class="text-slate-800 text-base mb-1 block">Tips Mencetak Sempurna:</strong>
                1. Ubah orientasi kertas menjadi <strong>Landscape</strong>.<br>
                2. Atur ukuran kertas ke <strong>A4</strong>.<br>
                3. Centang opsi <em>"Background graphics"</em> (Grafik Latar Belakang) agar warna emas dan biru-hijaunya tercetak tebal.
            </p>
        </div>
    </div>

</body>
</html>