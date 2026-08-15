<?php

namespace App\Http\Controllers;

use App\Models\Materi;
use App\Models\Mapel;
use App\Models\Soal;
use App\Models\OpsiJawaban;
use App\Models\User;
use App\Models\Chat;
use App\Services\NotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class GuruController extends Controller
{
    public function dashboard()
    {
        $user = Auth::user();

        // [FIX] Ambil kelas yang diampu guru ini (bukan semua kelas)
        $kelasIds = $user->mengampu_mapel->pluck('kelas_id')->unique()->filter();

        // Hanya tampilkan latihan biasa — ujian ada di halaman Kelola Ujian
        $materis = Materi::with('mapel')
                         ->where('user_id', $user->id)
                         ->where('jenis', 'latihan')
                         ->latest()
                         ->get();

        // Statistik siswa yang dibimbing (hanya dari kelas yang diampu)
        $totalSiswa = User::where('role', 'siswa')->whereIn('kelas_id', $kelasIds)->count();

        return view('guru.dashboard', compact('materis', 'totalSiswa'));
    }

    public function create(Request $request)
    {
        $user = Auth::user();
        $mapels = $user->mengampu_mapel()->with('kelas')->get();

        if ($mapels->isEmpty()) {
            return redirect()->route('guru.dashboard')->with('error', '⚠️ Anda belum mengampu mata pelajaran apapun!');
        }

        // mode = 'ujian' jika masuk dari halaman Kelola Ujian
        $mode = $request->query('mode', 'latihan'); // 'latihan' | 'ujian'

        return view('guru.create_materi', compact('mapels', 'mode'));
    }

    public function ujianIndex()
    {
        $user   = Auth::user();
        $mapels = $user->mengampu_mapel()->with('kelas')->get();

        // Ambil semua ujian milik guru ini
        $ujians = Materi::with('mapel')
            ->where('user_id', $user->id)
            ->whereIn('jenis', ['ujian_ganjil', 'ujian_genap'])
            ->latest()
            ->get();

        return view('guru.ujian.index', compact('ujians', 'mapels'));
    }

    // --- KELOLA SOAL ---
    public function kelolaSoal($materi_id)
    {
        $materi = Materi::with('mapel')->findOrFail($materi_id);
        $soals  = Soal::where('materi_id', $materi_id)->with('opsi_jawaban')->get();
        return view('guru.kelola_soal', compact('materi', 'soals'));
    }

    public function storeSoal(Request $request, $materi_id)
    {
        $request->validate([
            'pertanyaan'    => 'required|string',
            'gambar'        => 'nullable|image|mimes:jpg,png,jpeg|max:2048',
            'opsi_a'        => 'required|string',
            'opsi_b'        => 'required|string',
            'opsi_c'        => 'required|string',
            'opsi_d'        => 'required|string',   // [FIX] opsi D ditambahkan
            'jawaban_benar' => 'required|in:a,b,c,d', // [FIX] include d
            'xp_reward'     => 'required|integer',
        ]);

        $materi     = Materi::findOrFail($materi_id);
        $pathGambar = null;

        if ($request->hasFile('gambar')) {
            $pathGambar = $request->file('gambar')->store('soal_images', 'public');
        }

        $soal = Soal::create([
            'materi_id'  => $materi->id,
            'mapel_id'   => $materi->mapel_id,
            'pertanyaan' => $request->pertanyaan,
            'gambar'     => $pathGambar,
            'tipe'       => 'pilihan_ganda',
            'xp_reward'  => $request->xp_reward,
        ]);

        $opsi = [
            ['teks_opsi' => $request->opsi_a, 'is_benar' => $request->jawaban_benar == 'a'],
            ['teks_opsi' => $request->opsi_b, 'is_benar' => $request->jawaban_benar == 'b'],
            ['teks_opsi' => $request->opsi_c, 'is_benar' => $request->jawaban_benar == 'c'],
            ['teks_opsi' => $request->opsi_d, 'is_benar' => $request->jawaban_benar == 'd'], // [FIX]
        ];

        foreach ($opsi as $o) {
            OpsiJawaban::create([
                'soal_id'  => $soal->id,
                'teks_opsi'=> $o['teks_opsi'],
                'is_benar' => $o['is_benar'],
            ]);
        }

        return redirect()->back()->with('success', 'Soal berhasil disimpan! ✅');
    }

    public function destroySoal($id)
    {
        $soal = Soal::findOrFail($id);

        if ($soal->gambar && Storage::disk('public')->exists($soal->gambar)) {
            Storage::disk('public')->delete($soal->gambar);
        }

        $soal->delete();
        return redirect()->back()->with('success', 'Soal berhasil dihapus! 🗑️');
    }

    public function destroy($id)
    {
        Materi::findOrFail($id)->delete();
        return redirect()->route('guru.dashboard')->with('success', 'Misi berhasil dihapus! 🗑️');
    }

    // --- FITUR KELULUSAN ---
    public function kelulusanIndex()
    {
        // [FIX] Rename $mapelIds → $kelasIds agar tidak menyesatkan
        $kelasIds = Auth::user()->mengampu_mapel->pluck('kelas_id')->unique()->filter();
        $siswas   = User::where('role', 'siswa')->whereIn('kelas_id', $kelasIds)->get();
        return view('guru.kelulusan.index', compact('siswas'));
    }

    public function tandaiLulus(Request $request, $id)
    {
        $siswa = User::findOrFail($id);
        $siswa->update(['siap_lulus' => $request->status]);
        return back()->with('success', 'Rekomendasi kelulusan diperbarui!');
    }

    // --- FITUR LAPORAN ---
    public function laporanSiswa()
    {
        // [FIX] Rename $mapelIds → $kelasIds
        $kelasIds = Auth::user()->mengampu_mapel->pluck('kelas_id')->unique()->filter();

        $siswas = User::where('role', 'siswa')
            ->whereIn('kelas_id', $kelasIds)
            ->with([
                'riwayat_belajar' => fn($q) => $q->latest(),
                'nilais.materi',
            ])
            ->orderBy('xp', 'desc')
            ->get();

        $aktif = $siswas->filter(fn($s) => $s->riwayat_belajar->first() &&
            $s->riwayat_belajar->first()->created_at > now()->subDays(3));
        $pasif = $siswas->diff($aktif);

        $semua_nilai = \App\Models\Nilai::with(['user', 'materi'])
            ->whereIn('user_id', $siswas->pluck('id'))
            ->latest()
            ->take(30)
            ->get();

        return view('guru.laporan.index', compact('siswas', 'aktif', 'pasif', 'semua_nilai'));
    }

    // --- FITUR GAME ---
    public function gameIndex()
    {
        $user  = Auth::user();
        $mapels = $user->mengampu_mapel()->with('kelas')->get();

        $games = DB::table('games')
            ->join('mapels', 'games.mapel_id', '=', 'mapels.id')
            ->whereIn('games.mapel_id', $mapels->pluck('id'))
            ->select('games.*', 'mapels.nama_mapel', 'mapels.warna_tema')
            ->orderBy('games.id', 'desc')
            ->get();

        return view('guru.game.index', compact('mapels', 'games'));
    }

    public function gameStore(Request $request)
    {
        $request->validate([
            'judul'     => 'required|string',
            'link_game' => 'required|url',
            'mapel_id'  => 'required|exists:mapels,id',
        ]);

        DB::table('games')->insert([
            'judul'      => $request->judul,
            'link_game'  => $request->link_game,
            'mapel_id'   => $request->mapel_id,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return back()->with('success', 'Game edukasi berhasil dipublikasikan! 🎮');
    }

    public function gameDestroy($id)
    {
        $game = DB::table('games')->where('id', $id)->first();

        if ($game) {
            DB::table('games')->where('id', $id)->delete();
            return redirect()->back()->with('success', 'Game berhasil dihapus! 🗑️');
        }

        return redirect()->back()->with('error', 'Game tidak ditemukan.');
    }

    // --- FITUR CHAT ---
    public function chatIndex(Request $request)
    {
        // [FIX] Rename $mapelIds → $kelasIds
        $kelasIds = Auth::user()->mengampu_mapel->pluck('kelas_id')->unique()->filter();
        $siswas   = User::where('role', 'siswa')->whereIn('kelas_id', $kelasIds)->get();
        $chats    = collect();

        if ($request->has('siswa_id')) {
            $siswa_id = $request->siswa_id;

            $chats = Chat::where(function ($q) use ($siswa_id) {
                    $q->where('sender_id', Auth::id())->where('receiver_id', $siswa_id);
                })
                ->orWhere(function ($q) use ($siswa_id) {
                    $q->where('sender_id', $siswa_id)->where('receiver_id', Auth::id());
                })
                ->latest()
                ->take(50)
                ->get()
                ->reverse()
                ->values();
        }

        return view('guru.chat.index', compact('siswas', 'chats'));
    }

    public function chatSend(Request $request)
    {
        $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'pesan'       => 'required|string|max:1000',
        ], [
            'receiver_id.required' => 'Pilih siswa penerima pesan terlebih dahulu!',
        ]);

        $chat = Chat::create([
            'sender_id'   => Auth::id(),
            'receiver_id' => $request->receiver_id,
            'pesan'       => $request->pesan,
        ]);

        // Notif in-app ke siswa penerima
        try {
            $siswa = User::find($request->receiver_id);
            $guru  = Auth::user();
            if ($siswa) {
                NotificationService::kirim(
                    $siswa,
                    '💬 Pesan dari Guru',
                    "{$guru->name}: " . \Illuminate\Support\Str::limit($request->pesan, 80),
                    'pesan',
                    '💬',
                    route('siswa.dashboard')
                );
            }
        } catch (\Throwable $e) {}

        // [FIX] Dukung AJAX response agar tab tidak terbuka baru & chat tidak tertutup
        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success'   => true,
                'pesan'     => $chat->pesan,
                'waktu'     => $chat->created_at->format('H:i'),
                'sender_id' => $chat->sender_id,
            ]);
        }

        return back()->with('success', 'Pesan bimbingan berhasil dikirim! ✉️');
    }

    // --- KELOLA MATERI ---
    public function edit($id)
    {
        $materi = Materi::findOrFail($id);
        $user   = Auth::user();

        if ($materi->user_id !== $user->id) {
            abort(403, 'Akses ditolak. Ini bukan materi Anda.');
        }

        $mapels = $user->mengampu_mapel()->with('kelas')->get();
        return view('guru.materi.edit', compact('materi', 'mapels'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'mapel_id'     => 'required|exists:mapels,id',
            'judul'        => 'required|string|max:255',
            'konten'       => 'required',
            'tipe'         => 'required',
            'xp_reward'    => 'required|integer',
            'jenis'        => 'nullable|in:latihan,ujian_ganjil,ujian_genap', // [FIX] ujian semester
            'jadwal_mulai' => 'nullable|date',
            'jadwal_selesai'=> 'nullable|date|after_or_equal:jadwal_mulai',
            'file_video'   => 'nullable|mimetypes:video/mp4,video/mpeg,video/quicktime|max:20000',
            'file_dokumen' => 'nullable|mimes:pdf,doc,docx,ppt,pptx|max:10000',
        ]);

        $materi = Materi::findOrFail($id);

        if ($materi->user_id !== Auth::id()) {
            abort(403);
        }

        $materi->mapel_id      = $request->mapel_id;
        $materi->judul         = $request->judul;
        $materi->konten        = $request->konten;
        $materi->tipe          = $request->tipe;
        $materi->xp_reward     = $request->xp_reward;
        $materi->jenis         = $request->jenis ?? 'latihan';          // [FIX]
        $materi->jadwal_mulai  = $request->jadwal_mulai;                // [FIX]
        $materi->jadwal_selesai= $request->jadwal_selesai;              // [FIX]

        if ($request->tipe === 'youtube') {
            $materi->youtube_link = $request->youtube_link;
        } elseif ($request->tipe === 'video_lokal' && $request->hasFile('file_video')) {
            if ($materi->file_path && Storage::disk('public')->exists($materi->file_path)) {
                Storage::disk('public')->delete($materi->file_path);
            }
            $materi->file_path = $request->file('file_video')->store('materi_video', 'public');
        } elseif ($request->tipe === 'dokumen' && $request->hasFile('file_dokumen')) {
            if ($materi->file_path && Storage::disk('public')->exists($materi->file_path)) {
                Storage::disk('public')->delete($materi->file_path);
            }
            $materi->file_path = $request->file('file_dokumen')->store('materi_dokumen', 'public');
        }

        $materi->save();

        // === PWA Push Notification (update materi) ===
        $this->kirimPushMateri($materi, 'update');

        // Redirect ke halaman yang sesuai
        $isUjian = in_array($materi->jenis, ['ujian_ganjil', 'ujian_genap']);
        return redirect()
            ->route($isUjian ? 'guru.ujian.index' : 'guru.dashboard')
            ->with('success', $isUjian ? 'Ujian berhasil diperbarui! ✏️✅' : 'Misi berhasil diperbarui! ✏️✅');
    }

    public function materiStore(Request $request)
    {
        $request->validate([
            'mapel_id'      => 'required|exists:mapels,id',
            'judul'         => 'required|string|max:255',
            'tipe'          => 'required|in:teks,youtube,video_lokal,dokumen',
            'konten'        => 'required|string',
            'youtube_link'  => 'nullable|url',
            'file_video'    => 'nullable|file|mimetypes:video/mp4,video/x-m4v,video/*|max:51200',
            'file_dokumen'  => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,ppt,pptx|max:10240',
            'xp_reward'     => 'required|numeric|min:0',
            'jenis'         => 'nullable|in:latihan,ujian_ganjil,ujian_genap', // [FIX]
            'jadwal_mulai'  => 'nullable|date',
            'jadwal_selesai'=> 'nullable|date|after_or_equal:jadwal_mulai',
        ]);

        $pathFile = null;

        if ($request->tipe === 'video_lokal' && $request->hasFile('file_video')) {
            $pathFile = $request->file('file_video')->store('materi_video', 'public');
        } elseif ($request->tipe === 'dokumen' && $request->hasFile('file_dokumen')) {
            $pathFile = $request->file('file_dokumen')->store('materi_dokumen', 'public');
        }

        $materiBaru = Materi::create([
            'user_id'        => Auth::id(),
            'mapel_id'       => $request->mapel_id,
            'judul'          => $request->judul,
            'tipe'           => $request->tipe,
            'konten'         => $request->konten,
            'youtube_link'   => ($request->tipe === 'youtube') ? $request->youtube_link : null,
            'file_path'      => $pathFile,
            'xp_reward'      => $request->xp_reward,
            'jenis'          => $request->jenis ?? 'latihan',           // [FIX]
            'jadwal_mulai'   => $request->jadwal_mulai,                 // [FIX]
            'jadwal_selesai' => $request->jadwal_selesai,               // [FIX]
        ]);

        $this->kirimNotifTugasBaru($materiBaru);

        // === PWA Push Notification ke siswa & ortu ===
        $this->kirimPushMateri($materiBaru, 'baru');

        // === Notif in-app ke semua Admin ===
        try {
            $guru    = Auth::user();
            $isUjian = in_array($materiBaru->jenis, ['ujian_ganjil', 'ujian_genap']);
            $label   = $isUjian ? 'Ujian Semester' : 'Materi Baru';
            $admins  = User::where('role', 'admin')->get();
            NotificationService::kirim(
                $admins,
                "📚 {$label} Ditambahkan",
                "{$guru->name} menambahkan \"{$materiBaru->judul}\"",
                'materi',
                '📚',
                null
            );
        } catch (\Throwable $e) {}

        $isUjian = in_array($materiBaru->jenis, ['ujian_ganjil', 'ujian_genap']);
        return redirect()
            ->route($isUjian ? 'guru.ujian.index' : 'guru.dashboard')
            ->with('success', $isUjian ? 'Ujian berhasil dipublikasikan! 📋✅' : 'Misi Belajar berhasil dipublikasikan! 🚀📱');
    }

    private function kirimNotifTugasBaru($materi)
    {
        $token = env('FONNTE_TOKEN');
        if (!$token) return false;

        $mapel = Mapel::find($materi->mapel_id);
        if (!$mapel) return false;

        // [FIX] Gunakan no_telp siswa langsung (nomor anak = nomor wali murid)
        $nomorWa = User::where('role', 'siswa')
            ->where('kelas_id', $mapel->kelas_id)
            ->whereNotNull('no_telp')
            ->where('no_telp', '!=', '')
            ->pluck('no_telp')
            ->unique()
            ->toArray();

        if (empty($nomorWa)) return false;

        $jenisLabel = match($materi->jenis ?? 'latihan') {
            'ujian_ganjil' => '📋 UJIAN SEMESTER GANJIL',
            'ujian_genap'  => '📋 UJIAN SEMESTER GENAP',
            default        => '📚 Materi/Misi Belajar Baru',
        };

        $jadwalInfo = '';
        if ($materi->jadwal_mulai) {
            $jadwalInfo = "\n⏰ *Jadwal:* " . \Carbon\Carbon::parse($materi->jadwal_mulai)->format('d M Y H:i') . ' WIB';
        }

        $pesan = "🔔 *INFO TUGAS BARU* 🔔\n\n"
               . "Halo Ayah/Bunda!\n"
               . "Ada {$jenisLabel} baru di MIFDA (MI Miftahul Huda).\n\n"
               . "📚 *Mata Pelajaran:* {$mapel->nama_mapel}\n"
               . "🎯 *Misi:* {$materi->judul}"
               . $jadwalInfo . "\n\n"
               . "Yuk, dampingi ananda untuk segera menyelesaikannya! 🚀";

        try {
            \Illuminate\Support\Facades\Http::withHeaders(['Authorization' => $token])
                ->post('https://api.fonnte.com/send', [
                    'target'      => implode(',', $nomorWa),
                    'message'     => $pesan,
                    'countryCode' => '62',
                ]);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Gagal broadcast WA: ' . $e->getMessage());
        }
    }

    /**
     * Kirim PWA push notification ke siswa & ortu saat materi dibuat/diupdate.
     */
    private function kirimPushMateri($materi, string $aksi = 'baru'): void
    {
        $mapel = Mapel::find($materi->mapel_id);
        if (!$mapel || !$mapel->kelas_id) return;

        $isBaru  = $aksi === 'baru';
        $isUjian = in_array($materi->jenis ?? 'latihan', ['ujian_ganjil', 'ujian_genap']);

        $jenisLabel = match($materi->jenis ?? 'latihan') {
            'ujian_ganjil' => 'Ujian Semester Ganjil',
            'ujian_genap'  => 'Ujian Semester Genap',
            default        => 'Materi Belajar',
        };

        $icon  = $isUjian ? '📋' : ($isBaru ? '📚' : '✏️');
        $judul = $isBaru
            ? "{$icon} {$jenisLabel} Baru: {$mapel->nama_mapel}"
            : "{$icon} {$jenisLabel} Diperbarui: {$mapel->nama_mapel}";

        $pesan = $isBaru
            ? "Guru menambahkan \"{$materi->judul}\". Yuk segera cek dan kerjakan! 🚀"
            : "Materi \"{$materi->judul}\" telah diperbarui. Cek konten terbaru sekarang!";

        $url = url('/siswa/belajar');

        // Kirim ke siswa sekelas
        NotificationService::keSiswaKelas($mapel->kelas_id, $judul, $pesan, $isUjian ? 'ujian' : 'materi', $icon, $url);

        // Kirim ke ortu siswa sekelas (jika ada relasi)
        try {
            NotificationService::keOrtuKelas($mapel->kelas_id, $judul, $pesan, $isUjian ? 'ujian' : 'materi', $icon, $url);
        } catch (\Throwable $e) {
            \Illuminate\Support\Facades\Log::info('[Push] keOrtuKelas skip: ' . $e->getMessage());
        }
    }
}
