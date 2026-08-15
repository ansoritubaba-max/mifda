<?php

namespace App\Services;

use App\Models\Notifikasi;
use App\Models\PushSubscription;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class NotificationService
{
    /**
     * Kirim notifikasi ke satu user atau koleksi user.
     *
     * @param User|Collection|array $targets
     */
    public static function kirim(
        $targets,
        string $judul,
        string $pesan,
        string $tipe   = 'info',
        string $icon   = '📢',
        ?string $url   = null
    ): void {
        // Normalisasi ke Collection of User
        if ($targets instanceof User) {
            $targets = collect([$targets]);
        } elseif (is_array($targets)) {
            $targets = collect($targets);
        }

        foreach ($targets as $user) {
            // 1. Simpan ke database (in-app notification)
            Notifikasi::create([
                'user_id' => $user->id,
                'judul'   => $judul,
                'pesan'   => $pesan,
                'tipe'    => $tipe,
                'icon'    => $icon,
                'url'     => $url,
            ]);

            // 2. Kirim Web Push ke semua subscription device user
            self::kirimPush($user, $judul, $pesan, $icon, $url);
        }
    }

    /**
     * Kirim Web Push Notification ke semua device terdaftar milik user.
     */
    private static function kirimPush(User $user, string $judul, string $pesan, string $icon, ?string $url): void
    {
        $subscriptions = PushSubscription::where('user_id', $user->id)->get();
        if ($subscriptions->isEmpty()) return;

        $vapidPublic  = config('app.vapid_public_key');
        $vapidPrivate = config('app.vapid_private_key');
        $vapidSubject = config('app.url');

        // Jika paket minishlink/web-push belum diinstall, skip push silently
        if (!class_exists(\Minishlink\WebPush\WebPush::class)) {
            return;
        }

        try {
            $webPush = new \Minishlink\WebPush\WebPush([
                'VAPID' => [
                    'subject'    => $vapidSubject,
                    'publicKey'  => $vapidPublic,
                    'privateKey' => $vapidPrivate,
                ],
            ]);

            $payload = json_encode([
                'title' => $judul,
                'body'  => $pesan,
                'icon'  => '/icon-192x192.png',
                'badge' => '/icon-72x72.png',
                'tag'   => $tipe,
                'url'   => $url ?? '/',
            ]);

            foreach ($subscriptions as $sub) {
                $subscription = \Minishlink\WebPush\Subscription::create([
                    'endpoint'        => $sub->endpoint,
                    'keys'            => [
                        'p256dh' => $sub->public_key,
                        'auth'   => $sub->auth_token,
                    ],
                    'contentEncoding' => $sub->content_encoding,
                ]);

                $webPush->queueNotification($subscription, $payload);
            }

            foreach ($webPush->flush() as $report) {
                if (!$report->isSuccess()) {
                    // Endpoint expired/invalid — hapus dari DB
                    if ($report->isSubscriptionExpired()) {
                        PushSubscription::where('endpoint', $report->getEndpoint())->delete();
                    }
                    Log::warning('[Push] Gagal kirim ke: ' . $report->getEndpoint() . ' — ' . $report->getReason());
                }
            }
        } catch (\Throwable $e) {
            Log::error('[Push] Error: ' . $e->getMessage());
        }
    }

    /**
     * Shortcut: kirim ke semua siswa dalam satu kelas.
     */
    public static function keSiswaKelas(int $kelasId, string $judul, string $pesan, string $tipe = 'info', string $icon = '📢', ?string $url = null): void
    {
        $siswa = User::where('role', 'siswa')
                     ->where('kelas_id', $kelasId)
                     ->get();

        self::kirim($siswa, $judul, $pesan, $tipe, $icon, $url);
    }

    /**
     * Shortcut: kirim ke semua ortu dari siswa dalam satu kelas.
     */
    public static function keOrtuKelas(int $kelasId, string $judul, string $pesan, string $tipe = 'info', string $icon = '📢', ?string $url = null): void
    {
        // Ambil user_id siswa di kelas
        $siswaIds = User::where('role', 'siswa')->where('kelas_id', $kelasId)->pluck('id');

        // Ambil ortu yang anaknya ada di kelas tsb via relasi anak()
        $ortus = User::where('role', 'ortu')
                     ->whereHas('anak', fn($q) => $q->whereIn('id', $siswaIds))
                     ->get();

        self::kirim($ortus, $judul, $pesan, $tipe, $icon, $url);
    }
}
