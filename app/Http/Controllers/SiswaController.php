<?php

namespace App\Http\Controllers;

use App\Models\Mapel;
use App\Models\Materi;
use App\Models\RiwayatBelajar;
use App\Models\Nilai;
use App\Models\OpsiJawaban;
use App\Models\User;
use App\Models\Chat;
use App\Services\LencanaService;
use App\Services\GeminiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;

class SiswaController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();

        $games = DB::table('games')
                    ->join('mapels', 'games.mapel_id', '=', 'mapels.id')
                    ->where('mapels.kelas_id', $user->kelas_id)
                    ->select('games.*', 'mapels.nama_mapel', 'mapels.warna_tema')
                    ->get();

        $nilais = Nilai::where('user_id', $user->id)->with('materi')->latest()->take(5)->get();

        // Guru yang mengampu kelas siswa ini
        $gurus = User::where('role', 'guru')
                ->whereHas('mengampu_mapel', fn($q) => $q->where('kelas_id', $user->kelas_id))
                ->get();

        $guruTerpilihId = request('guru_id');
        $guru = $guruTerpilihId ? $gurus->where('id', $guruTerpilihId)->first() : $gurus->first();

        // [FIX] Batasi chat 50 pesan terbaru saja
        $chats = collect();
        if ($guru) {
            $chats = Chat::where(function ($q) use ($guru, $user) {
                    $q->where('sender_id', $user->id)->where('receiver_id', $guru->id);
                })->orWhere(function ($q) use ($guru, $user) {
                    $q->where('sender_id', $guru->id)->where('receiver_id', $user->id);
                })
                ->latest()
                ->take(50)
                ->get()
                ->reverse()
                ->values();
        }

        $lencanas = DB::table('user_lencanas')
            ->where('user_id', $user->id)
            ->orderBy('created_at', 'asc')
            ->get();

        return view('siswa.dashboard_v2', compact('games', 'chats', 'nilais', 'user', 'guru', 'gurus', 'lencanas'));
    }

    // [FIX] Dukung AJAX agar chat tidak refresh halaman / tab tidak baru
    public function kirimChat(Request $request)
    {
        $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'pesan'       => 'required|string|max:1000',
        ]);

        $chat = Chat::create([
            'sender_id'   => Auth::id(),
            'receiver_id' => $request->receiver_id,
            'pesan'       => $request->pesan,
        ]);

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success'   => true,
                'pesan'     => $chat->pesan,
                'waktu'     => $chat->created_at->format('H:i'),
                'sender_id' => $chat->sender_id,
            ]);
        }

        return back()->with('success', 'Pesan terkirim! ✉️');
    }

    public function belajar()
    {
        $user   = Auth::user();
        // Count hanya materi latihan (ujian ada di halaman tersendiri)
        $mapels = Mapel::where('kelas_id', $user->kelas_id)
            ->withCount(['materis as materis_count' => fn($q) => $q->where('jenis', 'latihan')])
            ->get();
        return view('siswa.belajar', compact('mapels'));
    }

    public function materi($id)
    {
        // Hanya tampilkan materi latihan biasa — ujian ada di halaman Ujian Semester
        $mapel = Mapel::with(['materis' => fn($q) => $q->where('jenis', 'latihan')])->findOrFail($id);
        return view('siswa.materi', compact('mapel'));
    }

    public function lihatMateri($id)
    {
        $materi           = Materi::with('mapel')->withCount('soals')->findOrFail($id);
        $sudahMengerjakan = Nilai::where('user_id', Auth::id())->where('materi_id', $id)->exists();
        return view('siswa.detail_materi', compact('materi', 'sudahMengerjakan'));
    }

    public function selesaiMateri($id)
    {
        $materi  = Materi::findOrFail($id);
        $user    = Auth::user();
        $riwayat = RiwayatBelajar::where('user_id', $user->id)->where('materi_id', $materi->id)->first();

        if (!$riwayat) {
            RiwayatBelajar::create([
                'user_id'       => $user->id,
                'materi_id'     => $materi->id,
                'status'        => 'selesai',
                'tanggal_selesai' => now(),
            ]);
            $user->xp   += $materi->xp_reward;
            $user->level = floor($user->xp / 100) + 1;
            $user->save();

            // Cek & beri lencana otomatis setelah selesai materi
            (new LencanaService())->cekDanBeriLencana($user->fresh());

            $pesan = 'Hore! Kamu hebat! 🎉 Dapat hadiah +' . $materi->xp_reward . ' XP!';
        } else {
            $pesan = 'Kamu sudah pernah membaca materi ini! 👍';
        }

        return redirect()->route('siswa.materi', $materi->mapel_id)->with('success', $pesan);
    }

    public function kuis($materi_id)
    {
        $materi = Materi::with(['mapel', 'soals.opsi_jawaban'])->findOrFail($materi_id);

        // [FIX] Cek apakah ini ujian — validasi jadwal
        if (in_array($materi->jenis ?? 'latihan', ['ujian_ganjil', 'ujian_genap'])) {
            $sekarang = now();
            if ($materi->jadwal_mulai && $sekarang->lt($materi->jadwal_mulai)) {
                return redirect()->back()->with('error',
                    '⏰ Ujian belum dimulai. Jadwal mulai: ' .
                    \Carbon\Carbon::parse($materi->jadwal_mulai)->format('d M Y, H:i') . ' WIB'
                );
            }
            if ($materi->jadwal_selesai && $sekarang->gt($materi->jadwal_selesai)) {
                return redirect()->back()->with('error', '⏰ Waktu ujian sudah berakhir.');
            }
        }

        // Blokir jika sudah pernah mengerjakan
        $cek = Nilai::where('user_id', Auth::id())->where('materi_id', $materi_id)->first();
        if ($cek) {
            return redirect()->back()->with('error', '🛑 Kamu sudah mengerjakan kuis ini!');
        }

        // [FIX] Wajib baca materi dulu sebelum kuis (hanya untuk mode latihan)
        if (($materi->jenis ?? 'latihan') === 'latihan') {
            $sudahBaca = RiwayatBelajar::where('user_id', Auth::id())->where('materi_id', $materi_id)->exists();
            if (!$sudahBaca) {
                return redirect()->back()->with('error', '📖 Baca materi dulu sebelum mengerjakan kuis ya!');
            }
        }

        return view('siswa.kuis', compact('materi'));
    }

    public function submitKuis(Request $request, $materi_id)
    {
        $materi = Materi::with(['soals', 'mapel'])->findOrFail($materi_id);
        $user   = Auth::user();

        // Double-submit protection
        $cekNilai = Nilai::where('user_id', $user->id)->where('materi_id', $materi->id)->first();
        if ($cekNilai) {
            return redirect()->route('siswa.materi', $materi->mapel_id)
                ->with('error', 'Akses ditolak! Kamu sudah punya nilai di misi ini. 🛑');
        }

        $jawabanSiswa = $request->jawaban;
        $totalBenar   = 0;
        $xpTambahan   = 0;

        if ($materi->soals) {
            foreach ($materi->soals as $soal) {
                $opsiTerpilih = $jawabanSiswa[$soal->id] ?? null;
                if ($opsiTerpilih) {
                    $opsi = OpsiJawaban::find($opsiTerpilih);
                    if ($opsi && $opsi->is_benar) {
                        $totalBenar++;
                        $xpTambahan += $soal->xp_reward;
                    }
                }
            }
        }

        $totalSoal = $materi->soals->count();
        $skor      = $totalSoal > 0 ? round(($totalBenar / $totalSoal) * 100) : 0;

        // Lencana: Si Paling Teliti (skor sempurna)
        if ($skor == 100) {
            DB::table('user_lencanas')->updateOrInsert(
                ['user_id' => $user->id, 'nama_lencana' => 'Si Paling Teliti'],
                ['icon' => '🎯', 'updated_at' => now(), 'created_at' => now()]
            );
        }

        // Simpan nilai
        Nilai::create([
            'user_id'     => $user->id,
            'materi_id'   => $materi->id,
            'skor'        => $skor,
            'total_benar' => $totalBenar,
            'total_salah' => $totalSoal - $totalBenar,
        ]);

        // Update XP & Level
        $user->xp   += $xpTambahan;
        $user->level = floor($user->xp / 100) + 1;
        $user->save();

        // Cek & beri lencana otomatis setelah submit kuis
        (new LencanaService())->cekDanBeriLencana($user->fresh());

        // Notif WA ke wali
        $this->kirimNotifWaWali($user, $materi, $skor, $xpTambahan);

        // Notif in-app ke GURU pemilik materi
        try {
            $guru = \App\Models\User::find($materi->user_id);
            if ($guru) {
                \App\Services\NotificationService::kirim(
                    $guru,
                    '📝 Siswa Kumpul Kuis',
                    "{$user->name} mengerjakan \"{$materi->judul}\" — Skor: {$skor}",
                    'kuis',
                    '📝',
                    route('guru.laporan.index')
                );
            }
            // Notif ke semua admin
            $admins = \App\Models\User::where('role', 'admin')->get();
            \App\Services\NotificationService::kirim(
                $admins,
                '📊 Aktivitas Siswa',
                "{$user->name} selesai kuis \"{$materi->judul}\" — Skor: {$skor}",
                'info',
                '📊',
                null
            );
            // Notif in-app ke ORTU siswa ybs
            $ortus = \App\Models\User::where('role', 'ortu')
                ->whereHas('anak', fn($q) => $q->where('id', $user->id))
                ->get();
            if ($ortus->isNotEmpty()) {
                \App\Services\NotificationService::kirim(
                    $ortus,
                    '📝 Anak Selesai Kuis',
                    "{$user->name} mengerjakan \"{$materi->judul}\" — Skor: {$skor} 🎉",
                    'kuis',
                    '📝',
                    route('ortu.dashboard')
                );
            }
        } catch (\Throwable $e) {}

        return redirect()->route('siswa.materi', $materi->mapel_id)
            ->with('success', "Kuis Selesai! Skor kamu: {$skor} 🎉");
    }

    // [FIX] Gunakan no_telp siswa langsung karena nomor anak = nomor wali murid
    private function kirimNotifWaWali($siswa, $materi, $skor, $xp_didapat)
    {
        $noWa = $siswa->no_telp ?? null;
        if (!$noWa) return false;

        $pesan = "🔔 *NOTIFIKASI BELAJAR* 🔔\n"
               . "Ananda *{$siswa->name}* menyelesaikan misi *{$materi->judul}* "
               . "dengan skor *{$skor}*.\n"
               . "Kamu dapat +{$xp_didapat} XP! 🚀\n"
               . "Mari terus semangat belajar! 💪";

        try {
            Http::withHeaders(['Authorization' => env('FONNTE_TOKEN')])
                ->post('https://api.fonnte.com/send', [
                    'target'      => $noWa,
                    'message'     => $pesan,
                    'countryCode' => '62',
                ]);
        } catch (\Exception $e) {
            \Log::error("WA Error: " . $e->getMessage());
        }
    }

    public function leaderboard()
    {
        $user = Auth::user();
        // [FIX] Leaderboard per kelas agar lebih adil
        $topSiswa = User::where('role', 'siswa')
            ->where('kelas_id', $user->kelas_id)
            ->orderBy('xp', 'desc')
            ->take(10)
            ->get();
        return view('siswa.leaderboard', compact('topSiswa'));
    }

    public function unduhSertifikat()
    {
        $user     = Auth::user();
        $topSiswa = User::where('role', 'siswa')
            ->where('kelas_id', $user->kelas_id) // [FIX] per kelas
            ->orderBy('xp', 'desc')
            ->take(10)
            ->pluck('id')
            ->toArray();

        if (!in_array($user->id, $topSiswa)) {
            return back()->with('error', 'Hanya untuk Top 10 Juara di kelasmu!');
        }

        $peringkat = array_search($user->id, $topSiswa) + 1;
        return view('siswa.sertifikat_view', compact('user', 'peringkat'));
    }

    public function ujianIndex()
    {
        $user = Auth::user();

        // Ambil semua ujian dari mapel di kelas siswa, diurut dari yang terbaru
        $ujians = Materi::with(['mapel', 'soals'])
            ->whereHas('mapel', fn($q) => $q->where('kelas_id', $user->kelas_id))
            ->whereIn('jenis', ['ujian_ganjil', 'ujian_genap'])
            ->latest()
            ->get();

        // Tandai ujian yang sudah dikerjakan siswa ini
        $sudahDikerjakan = Nilai::where('user_id', $user->id)
            ->whereIn('materi_id', $ujians->pluck('id'))
            ->pluck('materi_id')
            ->toArray();

        return view('siswa.ujian', compact('ujians', 'sudahDikerjakan', 'user'));
    }

    public function playGame($id)
    {
        $game = DB::table('games')
                    ->join('mapels', 'games.mapel_id', '=', 'mapels.id')
                    ->where('games.id', $id)
                    ->select('games.*', 'mapels.nama_mapel', 'mapels.warna_tema')
                    ->first();

        if (!$game) return redirect()->route('siswa.dashboard');
        return view('siswa.game', compact('game'));
    }

    // [FIX] AI Asisten Mibi — ganti model hemat, tambah cache, perbaiki error handling
    public function aiChat(Request $request, $id)
    {
        $pesanSiswa = $request->input('pesan');
        if (!$pesanSiswa) {
            return response()->json(['reply' => 'Ada yang bisa Mibi bantu, Ananda? ✨']);
        }

        // Cache key agar pertanyaan yang sama tidak query Gemini dua kali
        $cacheKey = 'ai_chat_' . $id . '_' . md5($pesanSiswa);
        $cached   = Cache::get($cacheKey);
        if ($cached) {
            return response()->json(['reply' => $cached]);
        }

        try {
            $materi = Materi::find($id);

            $isiReferensi = "";
            $tipeMateri   = "Teks Artikel";

            // Baca PDF jika ada
            if ($materi->file_path && str_ends_with(strtolower($materi->file_path), '.pdf')) {
                try {
                    $pdfPath = storage_path('app/public/' . $materi->file_path);
                    if (file_exists($pdfPath)) {
                        $parser = new \Smalot\PdfParser\Parser();
                        $pdf    = $parser->parseFile($pdfPath);
                        $isiReferensi = $pdf->getText();
                        $tipeMateri   = "Dokumen PDF";
                    }
                } catch (\Exception $e) {
                    \Log::error("Gagal baca PDF: " . $e->getMessage());
                }
            }

            if (!empty($materi->konten)) {
                $isiReferensi .= "\n" . strip_tags($materi->konten);
            }

            if (empty(trim($isiReferensi)) && !empty($materi->youtube_link)) {
                $isiReferensi = "Materi ini berupa Video YouTube.";
                $tipeMateri   = "Video YouTube";
            }

            // [FIX] Potong lebih pendek untuk hemat token
            $isiReferensi = substr($isiReferensi, 0, 4000);

            $systemPrompt = "Kamu adalah Mibi, Guru MI yang cerdas dan ramah.
Tugasmu menjawab pertanyaan siswa berdasarkan materi berikut.
---
JUDUL: {$materi->judul}
TIPE: {$tipeMateri}
ISI: {$isiReferensi}
---
ATURAN: Gunakan bahasa simpel dan ramah untuk anak MI. Jawab singkat (maks 3 paragraf). Pakai emoji seperlunya.";

            // UPGRADE #5: sebelumnya manggil 1 model Gemini yang di-hardcode
            // langsung lewat Http::post() — begitu limit API key habis,
            // chat mati total. Sekarang lewat GeminiService: daftar model
            // diambil dinamis dari API Google, otomatis coba model
            // berikutnya kalau yang lagi dicoba kena limit.
            $hasil = app(GeminiService::class)->generateContent(
                "{$systemPrompt}\n\nPertanyaan: {$pesanSiswa}",
                [
                    'temperature'    => 0.4,
                    'maxOutputTokens'=> 400,
                ]
            );

            if ($hasil) {
                $formatted = preg_replace('/\*\*(.*?)\*\*/', '<b>$1</b>', $hasil);

                // Cache jawaban selama 1 jam
                Cache::put($cacheKey, nl2br($formatted), now()->addHour());

                return response()->json(['reply' => nl2br($formatted)]);
            }

            // Semua model di daftar sudah dicoba dan tetap gagal (misal
            // API key-nya sendiri yang kena limit total di semua model).
            return response()->json(['reply' => "Mibi sedang sinkronisasi ilmu. Coba tanya lagi ya! 🙏"]);

        } catch (\Exception $e) {
            // [FIX] Jangan expose pesan error teknis ke siswa
            \Log::error("AI Chat Error: " . $e->getMessage());
            return response()->json(['reply' => "Mibi sedang tidak tersedia saat ini. Coba beberapa saat lagi ya! 🙏"]);
        }
    }
}
