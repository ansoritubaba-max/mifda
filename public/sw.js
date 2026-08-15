// ============================================================
// MIFDA - Service Worker
// Fix: chrome-extension scheme, cross-origin cache, opaque response
// ============================================================

const CACHE_NAME    = "mifda-pwa-v2";
const OFFLINE_URL   = "/offline.html";

// ── INSTALL ─────────────────────────────────────────────────
self.addEventListener("install", (event) => {
    event.waitUntil(
        caches.open(CACHE_NAME)
            .then(cache => cache.add(OFFLINE_URL).catch(() => {}))
            .then(() => self.skipWaiting())
    );
});

// ── ACTIVATE ────────────────────────────────────────────────
self.addEventListener("activate", (event) => {
    event.waitUntil(
        caches.keys()
            .then(keys => Promise.all(
                keys.filter(k => k !== CACHE_NAME).map(k => caches.delete(k))
            ))
            .then(() => self.clients.claim())
    );
});

// ── SKIP WAITING ────────────────────────────────────────────
self.addEventListener('message', (event) => {
    if (event.data?.type === 'SKIP_WAITING') self.skipWaiting();
});

// ── FETCH ───────────────────────────────────────────────────
self.addEventListener("fetch", (event) => {
    const req = event.request;
    const url = req.url;

    // 1. Skip non-http(s) — chrome-extension://, data:, blob:, etc.
    if (!url.startsWith('http://') && !url.startsWith('https://')) return;

    // 2. Skip non-GET (POST, PUT, DELETE, PATCH)
    if (req.method !== 'GET') return;

    // 3. Skip API / notifikasi polling — jangan di-cache
    if (url.includes('/notifikasi/') || url.includes('/api/')) return;

    // 4. Navigasi halaman → network-first, fallback ke offline.html
    if (req.mode === 'navigate') {
        event.respondWith(
            fetch(req).catch(() => caches.match(OFFLINE_URL))
        );
        return;
    }

    // 5. Asset statis (CSS, JS, font, gambar) → cache-first
    if (['style','script','font','image'].includes(req.destination)) {
        event.respondWith(
            caches.match(req).then(cached => {
                if (cached) return cached;
                return fetch(req).then(res => {
                    // Hanya cache response yang valid & same-origin (bukan opaque)
                    if (res && res.status === 200 && res.type === 'basic') {
                        const clone = res.clone();
                        caches.open(CACHE_NAME).then(c => c.put(req, clone));
                    }
                    return res;
                }).catch(() => caches.match(OFFLINE_URL));
            })
        );
        return;
    }

    // 6. Default: network-first, tanpa paksa cache
    event.respondWith(fetch(req).catch(() => caches.match(req)));
});

// ── PUSH NOTIFICATION ───────────────────────────────────────
self.addEventListener('push', (event) => {
    let data = {
        title: 'MIFDA – MI Miftahul Huda',
        body:  'Ada notifikasi baru untukmu!',
        icon:  '/icon-192x192.png',
        badge: '/icon-72x72.png',
        tag:   'default',
        url:   '/',
    };

    if (event.data) {
        try   { data = { ...data, ...event.data.json() }; }
        catch { data.body = event.data.text(); }
    }

    event.waitUntil(
        self.registration.showNotification(data.title, {
            body:               data.body,
            icon:               data.icon,
            badge:              data.badge,
            tag:                data.tag,
            renotify:           true,
            requireInteraction: false,
            vibrate:            [200, 100, 200],
            data:               { url: data.url },
            actions: [
                { action: 'buka',  title: '📖 Buka Sekarang' },
                { action: 'tutup', title: '✖ Tutup' },
            ],
        })
    );
});

// ── NOTIFICATION CLICK ──────────────────────────────────────
self.addEventListener('notificationclick', (event) => {
    event.notification.close();
    if (event.action === 'tutup') return;

    const target = event.notification.data?.url ?? '/';

    event.waitUntil(
        clients.matchAll({ type: 'window', includeUncontrolled: true })
            .then(list => {
                for (const c of list) {
                    if ('focus' in c) { c.focus(); c.navigate(target); return; }
                }
                if (clients.openWindow) return clients.openWindow(target);
            })
    );
});
