<?php

namespace App\Http\Controllers;

use App\Models\Notifikasi;
use App\Models\PushSubscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /**
     * Simpan push subscription dari browser.
     * POST /notifikasi/subscribe
     */
    public function subscribe(Request $request)
    {
        $request->validate([
            'endpoint'         => 'required|string',
            'public_key'       => 'nullable|string',
            'auth_token'       => 'nullable|string',
            'content_encoding' => 'nullable|string',
        ]);

        try {
            // Upsert berdasarkan endpoint (satu browser = satu endpoint)
            PushSubscription::updateOrCreate(
                ['endpoint' => $request->endpoint],
                [
                    'user_id'          => Auth::id(),
                    'public_key'       => $request->public_key,
                    'auth_token'       => $request->auth_token,
                    'content_encoding' => $request->content_encoding ?? 'aesgcm',
                ]
            );
        } catch (\Throwable $e) {
            // Table may not exist yet — migration pending
        }

        return response()->json(['success' => true]);
    }

    /**
     * Hapus subscription (saat user logout / unsubscribe).
     * POST /notifikasi/unsubscribe
     */
    public function unsubscribe(Request $request)
    {
        $request->validate(['endpoint' => 'required|string']);

        try {
            PushSubscription::where('endpoint', $request->endpoint)
                            ->where('user_id', Auth::id())
                            ->delete();
        } catch (\Throwable $e) {
            // Table may not exist yet
        }

        return response()->json(['success' => true]);
    }

    /**
     * Ambil notifikasi milik user (untuk dropdown bell).
     * GET /notifikasi
     */
    public function index()
    {
        try {
            $notifikasis = Notifikasi::where('user_id', Auth::id())
                ->latest()
                ->take(20)
                ->get()
                ->map(fn($n) => [
                    'id'     => $n->id,
                    'judul'  => $n->judul,
                    'pesan'  => $n->pesan,
                    'tipe'   => $n->tipe,
                    'icon'   => $n->icon,
                    'url'    => $n->url,
                    'dibaca' => !is_null($n->dibaca_at),
                    'waktu'  => $n->created_at->diffForHumans(),
                ]);

            $belumDibaca = Notifikasi::where('user_id', Auth::id())
                ->whereNull('dibaca_at')
                ->count();
        } catch (\Throwable $e) {
            return response()->json(['notifikasis' => [], 'belum_dibaca' => 0]);
        }

        return response()->json([
            'notifikasis'  => $notifikasis,
            'belum_dibaca' => $belumDibaca,
        ]);
    }

    /**
     * Tandai satu notifikasi sebagai dibaca.
     * POST /notifikasi/{id}/read
     */
    public function markRead($id)
    {
        try {
            $notif = Notifikasi::where('user_id', Auth::id())->findOrFail($id);
            $notif->update(['dibaca_at' => now()]);
        } catch (\Throwable $e) {
            // Table may not exist yet
        }

        return response()->json(['success' => true]);
    }

    /**
     * Tandai semua notifikasi sebagai dibaca.
     * POST /notifikasi/read-all
     */
    public function markAllRead()
    {
        try {
            Notifikasi::where('user_id', Auth::id())
                ->whereNull('dibaca_at')
                ->update(['dibaca_at' => now()]);
        } catch (\Throwable $e) {
            // Table may not exist yet
        }

        return response()->json(['success' => true]);
    }

    /**
     * Jumlah notifikasi belum dibaca (untuk polling ringan).
     * GET /notifikasi/count
     */
    public function count()
    {
        try {
            $count = Notifikasi::where('user_id', Auth::id())
                ->whereNull('dibaca_at')
                ->count();
        } catch (\Throwable $e) {
            return response()->json(['count' => 0]);
        }

        return response()->json(['count' => $count]);
    }

    /**
     * TAMBAHAN (upgrade #2): hapus satu notifikasi beneran (bukan cuma
     * ditandai dibaca) — supaya gak numpuk terus di database.
     * POST /notifikasi/{id}/hapus
     */
    public function destroy($id)
    {
        try {
            Notifikasi::where('user_id', Auth::id())->where('id', $id)->delete();
        } catch (\Throwable $e) {
            // Table may not exist yet
        }

        return response()->json(['success' => true]);
    }

    /**
     * TAMBAHAN (upgrade #2): hapus SEMUA notifikasi milik user yang login.
     * POST /notifikasi/hapus-semua
     */
    public function destroyAll()
    {
        try {
            Notifikasi::where('user_id', Auth::id())->delete();
        } catch (\Throwable $e) {
            // Table may not exist yet
        }

        return response()->json(['success' => true]);
    }
}
