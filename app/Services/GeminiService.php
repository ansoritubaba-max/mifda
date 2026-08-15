<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * FITUR BARU (upgrade #5): Model AI dinamis + auto-fallback.
 *
 * Sebelumnya model Gemini di-hardcode 1 biji ("gemini-1.5-flash-8b") —
 * begitu limit gratis API key itu habis (Google balas error kuota), chat
 * AI mati total sampai ada yang ganti model manual di kode. Sekarang:
 *
 * 1. Daftar model diambil LANGSUNG dari API Google (bukan hardcode di
 *    kode), jadi otomatis ikut update kalau Google nambah/ganti model
 *    yang tersedia buat API key ini.
 * 2. Kalau model yang lagi dicoba kena limit/gagal, otomatis lanjut coba
 *    model berikutnya DALAM request yang sama — siswa/guru gak akan
 *    sadar ada masalah, chat tetap kejawab.
 * 3. Model yang barusan sukses "diinget" 10 menit, jadi request
 *    berikutnya langsung mulai dari situ (gak perlu nyoba dari awal lagi
 *    tiap kali).
 */
class GeminiService
{
    private const CACHE_MODEL_LIST = 'gemini_model_list';
    private const CACHE_WORKING_MODEL = 'gemini_working_model';

    /**
     * Generate teks dari prompt. Otomatis coba beberapa model kalau yang
     * pertama kena limit/gagal. Balikin null kalau SEMUA model gagal
     * (misal API key-nya sendiri yang bermasalah, atau semua model lagi
     * kena limit).
     */
    public function generateContent(string $prompt, array $generationConfig = []): ?string
    {
        $apiKey = env('GEMINI_API_KEY');

        if (empty($apiKey)) {
            Log::error('[Gemini] GEMINI_API_KEY kosong di .env.');
            return null;
        }

        $models = $this->getModelPriorityList($apiKey);

        foreach ($models as $model) {
            $url = "https://generativelanguage.googleapis.com/v1beta/models/{$model}:generateContent?key={$apiKey}";

            try {
                $response = Http::connectTimeout(15)->timeout(45)->post($url, [
                    'contents' => [
                        ['parts' => [['text' => $prompt]]],
                    ],
                    'generationConfig' => $generationConfig,
                ]);
            } catch (\Throwable $e) {
                Log::warning("[Gemini] Model {$model} gagal konek: " . $e->getMessage());
                continue;
            }

            if ($response->successful()) {
                $teks = $response->json('candidates.0.content.parts.0.text');

                if (!empty($teks)) {
                    // Inget model yang barusan berhasil, biar request
                    // berikutnya langsung coba ini duluan (skip yang lagi
                    // limit tanpa perlu ngetes ulang dari model pertama).
                    Cache::put(self::CACHE_WORKING_MODEL, $model, now()->addMinutes(10));

                    return $teks;
                }
            }

            $isQuotaError = $response->status() === 429
                || str_contains(strtolower($response->body()), 'quota')
                || str_contains(strtolower($response->body()), 'resource_exhausted');

            Log::warning("[Gemini] Model {$model} gagal (status {$response->status()}"
                . ($isQuotaError ? ', kena limit kuota' : '')
                . "), coba model berikutnya... Respons: " . substr($response->body(), 0, 300));

            // Lanjut ke model berikutnya di loop ini, apapun sebab
            // gagalnya (quota habis ATAU sebab lain) — siapa tau model
            // lain yang dicoba beres. Kalau semua di daftar habis dicoba,
            // function ini balikin null di baris paling akhir.
        }

        Log::error('[Gemini] Semua model di daftar gagal dicoba untuk 1 request ini.');

        return null;
    }

    /**
     * Ambil daftar model yang support generateContent, urut dari yang
     * paling ringan/hemat kuota duluan. Diambil LANGSUNG dari API Google
     * (endpoint yang sama seperti dipakai route /cek-model-ai), di-cache 1
     * jam biar gak nge-hit endpoint list itu tiap kali ada 1 chat masuk.
     */
    private function getModelPriorityList(string $apiKey): array
    {
        $list = Cache::remember(self::CACHE_MODEL_LIST, now()->addHour(), function () use ($apiKey) {
            try {
                $response = Http::timeout(15)->get(
                    "https://generativelanguage.googleapis.com/v1beta/models?key={$apiKey}"
                );

                if (!$response->successful()) {
                    // BUGFIX: sebelumnya cuma dicatat status code-nya
                    // doang — gak kelihatan PESAN aslinya dari Google, jadi
                    // susah didiagnosis. Sekarang body lengkap ikut dicatat.
                    throw new \RuntimeException('Status ' . $response->status() . ' — ' . $response->body());
                }

                $models = collect($response->json('models', []))
                    // Cuma model yang beneran support generateContent
                    // (bukan model embedding dkk yang gak relevan di sini).
                    ->filter(fn ($m) => in_array('generateContent', $m['supportedGenerationMethods'] ?? []))
                    ->pluck('name')
                    // API Google balikin "models/gemini-2.5-flash", yang
                    // dibutuhin cuma nama belakangnya.
                    ->map(fn ($name) => str_replace('models/', '', $name))
                    // Prioritaskan varian paling hemat kuota duluan:
                    // flash-lite > flash-8b (kalau masih ada) > flash biasa
                    // > pro (paling berat). Model preview/experimental
                    // ditaruh paling belakang (kurang stabil buat dipakai
                    // harian).
                    ->sortBy(function ($name) {
                        if (str_contains($name, 'exp') || str_contains($name, 'preview')) return 5;
                        if (str_contains($name, 'flash-lite')) return 0;
                        if (str_contains($name, 'flash-8b')) return 1;
                        if (str_contains($name, 'flash')) return 2;
                        if (str_contains($name, 'pro')) return 3;
                        return 4;
                    })
                    ->values()
                    ->all();

                return empty($models) ? $this->daftarCadangan() : $models;
            } catch (\Throwable $e) {
                Log::error('[Gemini] Gagal ambil daftar model dari API, pakai daftar cadangan: ' . $e->getMessage());

                // Kalau endpoint daftar model-nya sendiri lagi bermasalah,
                // tetap kasih beberapa nama model buat dicoba, jangan
                // sampai chat langsung mati total.
                return $this->daftarCadangan();
            }
        });

        // Kalau ada model yang kebukti masih berhasil dalam 10 menit
        // terakhir, taruh paling depan biar dicoba duluan.
        $working = Cache::get(self::CACHE_WORKING_MODEL);
        if ($working) {
            $list = array_values(array_unique(array_merge([$working], $list)));
        }

        return $list;
    }

    /**
     * BUGFIX: sebelumnya daftar cadangan ini isinya "gemini-1.5-flash-8b"
     * dan "gemini-1.5-flash" — ternyata KEDUANYA sudah resmi disetop total
     * oleh Google per 24 September 2025 (bukan cuma dikurangi kuotanya,
     * tapi beneran dihapus — makanya errornya 404 "model not found").
     * Diganti ke model generasi 2.5 yang jadi rekomendasi resmi Google
     * saat ini. Kalau di masa depan Google pensiunin model-model ini
     * juga, jalur utama (ambil daftar live dari API) yang seharusnya
     * otomatis menyesuaikan — ini cuma jaring pengaman kalau jalur utama
     * itu gagal diakses.
     */
    private function daftarCadangan(): array
    {
        return ['gemini-2.5-flash-lite', 'gemini-2.5-flash', 'gemini-2.5-pro'];
    }
}
