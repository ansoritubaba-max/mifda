<x-app-layout>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <x-slot name="header">
        <div class="flex items-center gap-4">
            <div class="w-12 h-12 bg-white/10 rounded-2xl border border-white/20 flex items-center justify-center text-2xl">👨‍👩‍👧</div>
            <div>
                <p class="text-indigo-300 text-xs font-black uppercase tracking-[0.2em] mb-0.5">Portal Orang Tua</p>
                <h2 class="font-black text-2xl md:text-3xl text-white tracking-tight">Dashboard Orang Tua</h2>
                <p class="text-xs font-bold text-indigo-200/70 uppercase tracking-widest mt-0.5">Pantau perkembangan belajar anak</p>
            </div>
        </div>
    </x-slot>

    <div class="py-6 md:py-12 bg-slate-50 min-h-screen relative overflow-hidden">

        <div class="absolute top-0 right-0 w-[30rem] h-[30rem] bg-blue-200 rounded-full mix-blend-multiply filter blur-[120px] opacity-20 pointer-events-none -translate-y-1/2 translate-x-1/3"></div>
        <div class="absolute bottom-0 left-0 w-[30rem] h-[30rem] bg-indigo-200 rounded-full mix-blend-multiply filter blur-[120px] opacity-20 pointer-events-none translate-y-1/3 -translate-x-1/3"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">

            {{-- TAMBAHAN: notifikasi hasil submit form "Hubungkan Kode" --}}
            @if(session('success'))
                <div class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-700 px-5 py-4 rounded-2xl font-bold text-sm">
                    ✅ {{ session('success') }}
                </div>
            @endif
            @if($errors->has('kode_penghubung'))
                <div class="mb-6 bg-red-50 border border-red-200 text-red-600 px-5 py-4 rounded-2xl font-bold text-sm">
                    ⚠️ {{ $errors->first('kode_penghubung') }}
                </div>
            @endif

            @forelse($anaks as $anak)
            <div class="mb-12 space-y-6">

                {{-- ===== HEADER ANAK ===== --}}
                <div class="bg-gradient-to-br from-blue-600 via-indigo-600 to-purple-700 rounded-[2.5rem] p-6 md:p-8 text-white relative overflow-hidden shadow-2xl shadow-blue-900/30">
                    <div class="absolute right-0 top-0 w-64 h-64 bg-white/10 rounded-full blur-3xl -mr-20 -mt-20 pointer-events-none"></div>
                    <div class="absolute -right-4 -bottom-6 text-9xl opacity-10 rotate-12 pointer-events-none">👦</div>

                    <div class="relative z-10 flex flex-col md:flex-row md:items-center justify-between gap-6">
                        <div class="flex items-center gap-5">
                            @if($anak->avatar)
                                <img src="{{ asset('storage/' . $anak->avatar) }}" alt="Avatar"
                                     class="w-20 h-20 rounded-[1.5rem] object-cover border-4 border-white/30 shadow-xl shrink-0">
                            @else
                                <div class="w-20 h-20 bg-white/20 rounded-[1.5rem] flex items-center justify-center text-4xl font-black shadow-inner border border-white/20 shrink-0">
                                    {{ substr($anak->name, 0, 1) }}
                                </div>
                            @endif
                            <div>
                                <h3 class="text-2xl md:text-3xl font-black drop-shadow-sm">{{ $anak->name }}</h3>
                                <div class="flex flex-wrap gap-2 mt-2">
                                    <span class="bg-white/20 text-white px-3 py-1 rounded-lg text-[10px] font-black uppercase tracking-widest border border-white/10">
                                        📍 {{ $anak->kelas->nama_kelas ?? 'Tanpa Kelas' }}
                                    </span>
                                    <span class="bg-yellow-400 text-yellow-900 px-3 py-1 rounded-lg text-[10px] font-black uppercase tracking-widest shadow-sm">
                                        Level {{ $anak->level ?? 1 }}
                                    </span>
                                    <span class="bg-white/20 text-white px-3 py-1 rounded-lg text-[10px] font-black uppercase tracking-widest border border-white/10">
                                        ⚡ {{ number_format($anak->xp ?? 0, 0, ',', '.') }} XP
                                    </span>
                                </div>
                            </div>
                        </div>

                        {{-- Peringkat Kelas --}}
                        <div class="flex gap-4 shrink-0">
                            @if($anak->peringkat_kelas)
                                <div class="bg-white/15 backdrop-blur-sm border border-white/20 rounded-2xl px-5 py-4 text-center">
                                    <p class="text-[9px] font-black uppercase tracking-widest text-blue-200 mb-1">Peringkat Kelas</p>
                                    <p class="text-4xl font-black">
                                        @if($anak->peringkat_kelas == 1) 🥇
                                        @elseif($anak->peringkat_kelas == 2) 🥈
                                        @elseif($anak->peringkat_kelas == 3) 🥉
                                        @else #{{ $anak->peringkat_kelas }}
                                        @endif
                                    </p>
                                    <p class="text-[9px] text-blue-200 mt-0.5 font-bold">dari {{ $anak->total_siswa_kelas }} siswa</p>
                                </div>
                            @endif
                            @if($anak->rata_nilai !== null)
                                <div class="bg-white/15 backdrop-blur-sm border border-white/20 rounded-2xl px-5 py-4 text-center">
                                    <p class="text-[9px] font-black uppercase tracking-widest text-blue-200 mb-1">Rata-rata Nilai</p>
                                    <p class="text-4xl font-black leading-none">{{ $anak->rata_nilai }}</p>
                                    <p class="text-[9px] text-blue-200 mt-0.5 font-bold">dari kuis terakhir</p>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- ===== TAMBAHAN: Kode Penghubung Absensi (cuma muncul kalau belum tersambung) ===== --}}
                @if(($anak->link_status ?? 'unlinked') !== 'verified')
                <div class="bg-white rounded-[2rem] shadow-sm border border-slate-100 p-6">
                    <h4 class="font-black text-base text-slate-800 flex items-center gap-2 mb-2">
                        <span class="w-8 h-8 bg-sky-50 text-sky-600 rounded-xl flex items-center justify-center text-sm border border-sky-100">🔗</span>
                        Hubungkan Data Kehadiran (Opsional)
                    </h4>
                    <p class="text-xs text-slate-500 font-bold mb-4">Punya kode penghubung dari sekolah (dikirim via WA)? Masukkan di sini supaya rekap kehadiran {{ $anak->name }} otomatis muncul di dashboard ini. Belum punya? Lewati saja, tidak wajib.</p>
                    <form method="POST" action="{{ route('ortu.hubungkan-kode') }}" class="flex flex-col sm:flex-row gap-3">
                        @csrf
                        <input type="hidden" name="siswa_id" value="{{ $anak->id }}">
                        <input type="text" inputmode="numeric" maxlength="6" pattern="[0-9]{6}" name="kode_penghubung" placeholder="Kode 6 digit" required
                               class="flex-1 border-slate-200 focus:border-sky-500 focus:ring-sky-500 rounded-xl shadow-sm text-sm font-bold" />
                        <button type="submit" class="bg-sky-600 hover:bg-sky-700 text-white px-5 py-2.5 rounded-xl text-sm font-black transition-colors shadow-sm">
                            Hubungkan
                        </button>
                    </form>
                </div>
                @endif

                {{-- ===== LENCANA ===== --}}
                <div class="bg-white rounded-[2rem] shadow-sm border border-slate-100 p-6">
                    <h4 class="font-black text-base text-slate-800 flex items-center gap-2 mb-4">
                        <span class="w-8 h-8 bg-amber-50 text-amber-500 rounded-xl flex items-center justify-center text-sm border border-amber-100">🏅</span>
                        Lencana yang Diraih
                    </h4>
                    <div class="flex flex-wrap gap-3">
                        @forelse($anak->lencanas as $lencana)
                            <div class="flex items-center gap-2 bg-gradient-to-br from-amber-50 to-yellow-50 border border-amber-200 rounded-2xl px-4 py-2.5 shadow-sm hover:shadow-md transition group">
                                <span class="text-2xl">{{ $lencana->icon ?? '🏅' }}</span>
                                <span class="text-xs font-black text-amber-800">{{ $lencana->nama_lencana }}</span>
                            </div>
                        @empty
                            <div class="flex items-center gap-3 text-slate-400 py-2">
                                <span class="text-2xl opacity-40">🔒</span>
                                <div>
                                    <p class="text-sm font-black text-slate-500">Belum ada lencana</p>
                                    <p class="text-xs text-slate-400">Anak perlu aktif belajar & kerjakan kuis untuk mendapat lencana</p>
                                </div>
                            </div>
                        @endforelse
                    </div>
                </div>

                {{-- ===== GRAFIK + MISI TERAKHIR ===== --}}
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

                    {{-- Grafik Nilai --}}
                    <div class="bg-white rounded-[2rem] shadow-sm border border-slate-100 p-6 flex flex-col">
                        <h4 class="font-black text-base text-slate-800 flex items-center gap-2 mb-4">
                            <span class="w-8 h-8 bg-blue-50 text-blue-600 rounded-xl flex items-center justify-center text-sm border border-blue-100">📊</span>
                            Perkembangan Nilai Kuis
                        </h4>
                        <div class="flex-grow h-64 bg-slate-50/50 p-4 rounded-2xl border border-slate-100 shadow-inner relative">
                            @if(empty($anak->skor_grafik))
                                <div class="absolute inset-0 flex flex-col items-center justify-center text-slate-400">
                                    <span class="text-4xl mb-2 opacity-50">📈</span>
                                    <p class="text-xs font-black uppercase tracking-widest">Belum ada data kuis</p>
                                </div>
                            @else
                                <canvas id="chart-{{ $anak->id }}"></canvas>
                            @endif
                        </div>
                    </div>

                    {{-- Misi Terakhir --}}
                    <div class="bg-white rounded-[2rem] shadow-sm border border-slate-100 p-6 flex flex-col">
                        <h4 class="font-black text-base text-slate-800 flex items-center gap-2 mb-4">
                            <span class="w-8 h-8 bg-emerald-50 text-emerald-600 rounded-xl flex items-center justify-center text-sm border border-emerald-100">📚</span>
                            Misi Terakhir Selesai
                        </h4>
                        <div class="space-y-3 flex-grow">
                            @forelse($anak->riwayat_belajar as $riwayat)
                                <div class="flex items-center justify-between p-4 bg-slate-50 border border-slate-100 rounded-2xl hover:border-blue-100 hover:bg-white transition group">
                                    <div class="flex items-center gap-3 min-w-0">
                                        <div class="w-9 h-9 bg-white rounded-xl flex items-center justify-center text-lg shrink-0 border border-slate-100 group-hover:scale-110 transition">
                                            {{ ($riwayat->materi->tipe ?? '') === 'youtube' ? '🎬' : (($riwayat->materi->tipe ?? '') === 'dokumen' ? '📄' : '📝') }}
                                        </div>
                                        <div class="min-w-0">
                                            <p class="font-black text-slate-800 text-sm truncate group-hover:text-blue-600 transition">{{ $riwayat->materi->judul ?? 'Misi Dihapus' }}</p>
                                            <p class="text-[9px] font-black uppercase tracking-widest mt-0.5 truncate" style="color: {{ $riwayat->materi->mapel->warna_tema ?? '#3b82f6' }}">
                                                {{ $riwayat->materi->mapel->nama_mapel ?? '-' }}
                                            </p>
                                        </div>
                                    </div>
                                    <div class="text-right shrink-0 ml-2">
                                        <span class="block text-[9px] font-black text-slate-400 uppercase tracking-widest">{{ $riwayat->created_at->format('d M') }}</span>
                                        <span class="block text-xs font-black text-emerald-500 mt-0.5">✅ Selesai</span>
                                    </div>
                                </div>
                            @empty
                                <div class="flex flex-col items-center justify-center py-10 border-2 border-dashed border-slate-200 rounded-2xl bg-slate-50/50">
                                    <span class="text-4xl mb-3 opacity-40">📭</span>
                                    <p class="text-sm font-black text-slate-500">Belum ada misi selesai</p>
                                    <p class="text-xs text-slate-400 mt-1">Semangati ananda untuk mulai belajar!</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>

                {{-- ===== TAMBAHAN: RIWAYAT KEHADIRAN (dari integrasi Absensi) ===== --}}
                @if($anak->riwayat_kehadiran->count() > 0)
                <div class="bg-white rounded-[2rem] shadow-sm border border-slate-100 p-6">
                    <h4 class="font-black text-base text-slate-800 flex items-center gap-2 mb-4">
                        <span class="w-8 h-8 bg-indigo-50 text-indigo-600 rounded-xl flex items-center justify-center text-sm border border-indigo-100">📋</span>
                        Riwayat Kehadiran
                    </h4>
                    <div class="space-y-2">
                        @foreach($anak->riwayat_kehadiran as $riwayat)
                            @php
                                $statusMap = [
                                    'hadir' => ['label' => 'Hadir', 'class' => 'bg-emerald-50 text-emerald-700 border-emerald-200', 'emoji' => '✅'],
                                    'izin'  => ['label' => 'Izin', 'class' => 'bg-blue-50 text-blue-700 border-blue-200', 'emoji' => '📝'],
                                    'sakit' => ['label' => 'Sakit', 'class' => 'bg-amber-50 text-amber-700 border-amber-200', 'emoji' => '🤒'],
                                    'alpa'  => ['label' => 'Alfa', 'class' => 'bg-rose-50 text-rose-700 border-rose-200', 'emoji' => '⚠️'],
                                ];
                                $s = $statusMap[$riwayat->status] ?? ['label' => ucfirst($riwayat->status), 'class' => 'bg-slate-50 text-slate-600 border-slate-200', 'emoji' => '•'];
                            @endphp
                            <div class="flex items-center justify-between p-3.5 bg-slate-50 border border-slate-100 rounded-2xl">
                                <div class="flex items-center gap-3 min-w-0">
                                    <span class="inline-flex items-center gap-1.5 border {{ $s['class'] }} px-3 py-1 rounded-lg text-[10px] font-black uppercase tracking-widest shrink-0">
                                        {{ $s['emoji'] }} {{ $s['label'] }}
                                    </span>
                                    <span class="text-xs font-bold text-slate-600 truncate">{{ $riwayat->mapel ?? '-' }}</span>
                                </div>
                                <span class="text-[10px] font-black text-slate-400 uppercase tracking-widest shrink-0 ml-2">
                                    {{ \Carbon\Carbon::parse($riwayat->tanggal)->translatedFormat('d M') }}{{ $riwayat->waktu ? ', ' . $riwayat->waktu : '' }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                </div>
                @endif

                {{-- ===== UJIAN MENDATANG ===== --}}
                @if($anak->ujian_mendatang->count() > 0)
                <div class="bg-white rounded-[2rem] shadow-sm border border-slate-100 p-6">
                    <h4 class="font-black text-base text-slate-800 flex items-center gap-2 mb-4">
                        <span class="w-8 h-8 bg-rose-50 text-rose-500 rounded-xl flex items-center justify-center text-sm border border-rose-100">📋</span>
                        Ujian Mendatang
                        <span class="ml-auto text-[9px] font-black uppercase tracking-widest text-slate-400">{{ $anak->kelas->nama_kelas ?? '' }}</span>
                    </h4>
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3">
                        @foreach($anak->ujian_mendatang as $ujian)
                            @php
                                $isGanjil = $ujian->jenis === 'ujian_ganjil';
                                $colorClass = $isGanjil ? 'border-blue-200 bg-blue-50/50' : 'border-purple-200 bg-purple-50/50';
                                $badgeClass = $isGanjil ? 'bg-blue-100 text-blue-700' : 'bg-purple-100 text-purple-700';
                                $label = $isGanjil ? '📋 Ganjil' : '📋 Genap';
                            @endphp
                            <div class="border {{ $colorClass }} rounded-2xl p-4">
                                <span class="inline-block text-[9px] font-black uppercase tracking-widest {{ $badgeClass }} px-2 py-0.5 rounded-lg mb-2">{{ $label }}</span>
                                <p class="font-black text-slate-800 text-sm leading-tight mb-1">{{ $ujian->judul }}</p>
                                <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest">{{ $ujian->mapel->nama_mapel ?? '-' }}</p>
                                @if($ujian->jadwal_mulai)
                                    <p class="mt-2 text-[10px] font-black text-slate-600">
                                        ⏰ {{ \Carbon\Carbon::parse($ujian->jadwal_mulai)->translatedFormat('d M Y, H:i') }}
                                    </p>
                                @else
                                    <p class="mt-2 text-[10px] font-black text-amber-600">⏰ Jadwal belum ditetapkan</p>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
                @endif

                {{-- ===== NILAI TERBARU ===== --}}
                @if($anak->nilais->count() > 0)
                <div class="bg-white rounded-[2rem] shadow-sm border border-slate-100 p-6">
                    <h4 class="font-black text-base text-slate-800 flex items-center gap-2 mb-4">
                        <span class="w-8 h-8 bg-amber-50 text-amber-600 rounded-xl flex items-center justify-center text-sm border border-amber-100">🎯</span>
                        Nilai Kuis Terbaru
                    </h4>
                    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-3">
                        @foreach($anak->nilais as $nilai)
                            @php
                                if ($nilai->skor >= 80) {
                                    $cardClass = 'bg-emerald-50 border-emerald-200';
                                    $scoreClass = 'text-emerald-600';
                                    $emoji = '🌟';
                                } elseif ($nilai->skor >= 60) {
                                    $cardClass = 'bg-amber-50 border-amber-200';
                                    $scoreClass = 'text-amber-600';
                                    $emoji = '👍';
                                } else {
                                    $cardClass = 'bg-rose-50 border-rose-200';
                                    $scoreClass = 'text-rose-600';
                                    $emoji = '💪';
                                }
                            @endphp
                            <div class="flex items-center justify-between {{ $cardClass }} border rounded-2xl p-4">
                                <div class="min-w-0 flex-1 mr-3">
                                    <p class="font-black text-slate-800 text-sm truncate">{{ $nilai->materi->judul ?? '-' }}</p>
                                    <p class="text-[9px] font-bold text-slate-400 uppercase tracking-widest mt-0.5">{{ $nilai->created_at->format('d M Y') }}</p>
                                </div>
                                <div class="text-right shrink-0">
                                    <p class="text-2xl font-black {{ $scoreClass }}">{{ $nilai->skor }}</p>
                                    <p class="text-xs">{{ $emoji }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                @endif

            </div>

            {{-- Chart JS --}}
            @if(!empty($anak->skor_grafik))
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const ctx = document.getElementById('chart-{{ $anak->id }}').getContext('2d');
                    let gradient = ctx.createLinearGradient(0, 0, 0, 260);
                    gradient.addColorStop(0, 'rgba(79,70,229,0.4)');
                    gradient.addColorStop(1, 'rgba(79,70,229,0.0)');
                    new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels: {!! json_encode($anak->label_grafik) !!},
                            datasets: [{
                                label: 'Skor Kuis',
                                data: {!! json_encode($anak->skor_grafik) !!},
                                borderColor: '#4F46E5',
                                backgroundColor: gradient,
                                borderWidth: 3,
                                tension: 0.4,
                                fill: true,
                                pointBackgroundColor: '#ffffff',
                                pointBorderColor: '#4F46E5',
                                pointBorderWidth: 2,
                                pointRadius: 5,
                                pointHoverRadius: 7
                            }]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            scales: {
                                y: { beginAtZero: true, max: 100,
                                     grid: { color: '#f3f4f6', borderDash: [5,5] },
                                     ticks: { font: { family: 'Nunito, sans-serif', weight: 'bold' }, color: '#9ca3af' } },
                                x: { grid: { display: false },
                                     ticks: { font: { family: 'Nunito, sans-serif', weight: 'bold' }, color: '#9ca3af' } }
                            },
                            plugins: {
                                legend: { display: false },
                                tooltip: {
                                    backgroundColor: '#1f2937', padding: 12, displayColors: false,
                                    titleFont: { size:13, family:'Nunito, sans-serif' },
                                    bodyFont: { size:14, weight:'bold', family:'Nunito, sans-serif' },
                                    callbacks: { label: ctx => 'Skor: ' + ctx.parsed.y }
                                }
                            }
                        }
                    });
                });
            </script>
            @endif

            @empty
                <div class="text-center py-24 bg-white rounded-[3rem] shadow-sm border border-slate-100 flex flex-col items-center">
                    <span class="text-8xl block mb-6 opacity-40">👨‍👩‍👧</span>
                    <h3 class="text-2xl font-black text-slate-800 mb-2">Belum Terhubung</h3>
                    <p class="text-slate-500 font-bold max-w-md text-sm leading-relaxed">Belum ada data anak yang terhubung ke akun Anda. Silakan hubungi Admin atau Wali Kelas untuk menghubungkan akun.</p>
                </div>
            @endforelse

        </div>
    </div>
</x-app-layout>
