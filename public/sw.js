const CACHE_NAME = 'nagari-sastra-pwa-v1';
const OFFLINE_URL = '/offline';

const PRECACHE_ASSETS = [
    '/',
    OFFLINE_URL,
    '/manifest.json',
    '/pwa-icons/icon-72x72.png',
    '/pwa-icons/icon-96x96.png',
    '/pwa-icons/icon-128x128.png',
    '/pwa-icons/icon-144x144.png',
    '/pwa-icons/icon-152x152.png',
    '/pwa-icons/icon-192x192.png',
    '/pwa-icons/icon-384x384.png',
    '/pwa-icons/icon-512x512.png',
    '/pwa-icons/maskable-icon-512x512.png',
    '/front/css/bootstrap.min.css',
    '/front/css/flaticon.css',
    '/front/css/menu.css',
    '/front/css/blue-theme.css',
    '/front/css/responsive.css',
    '/front/js/jquery-3.5.1.min.js',
    '/front/js/bootstrap.min.js'
];

// INSTALL EVENT: Pre-cache core assets
self.addEventListener('install', (event) => {
    event.waitUntil(
        caches.open(CACHE_NAME).then((cache) => {
            return cache.addAll(PRECACHE_ASSETS).catch((err) => {
                console.warn('[PWA ServiceWorker] Pre-cache partial warning:', err);
            });
        }).then(() => self.skipWaiting())
    );
});

// ACTIVATE EVENT: Clean old caches & take control
self.addEventListener('activate', (event) => {
    event.waitUntil(
        caches.keys().then((cacheNames) => {
            return Promise.all(
                cacheNames.map((cache) => {
                    if (cache !== CACHE_NAME) {
                        return caches.delete(cache);
                    }
                })
            );
        }).then(() => self.clients.claim())
    );
});

// FETCH EVENT: Network-first for pages, Cache-first for assets, with offline fallback
self.addEventListener('fetch', (event) => {
    const request = event.request;
    const url = new URL(request.url);

    // Only handle GET requests
    if (request.method !== 'GET') {
        return;
    }

    // Skip admin panel, livewire internal calls, and midtrans / payment gateways
    if (url.pathname.startsWith('/admin') ||
        url.pathname.startsWith('/livewire') ||
        url.pathname.startsWith('/payment') ||
        url.pathname.startsWith('/sanctum') ||
        url.pathname.startsWith('/api')) {
        return;
    }

    // 1. Navigation (HTML Pages) -> Network first, fallback to Cache or Offline Page
    if (request.mode === 'navigate') {
        event.respondWith(
            fetch(request)
                .then((response) => {
                    if (response && response.status === 200) {
                        const copy = response.clone();
                        caches.open(CACHE_NAME).then((cache) => cache.put(request, copy));
                    }
                    return response;
                })
                .catch(async () => {
                    const cachedResponse = await caches.match(request);
                    if (cachedResponse) {
                        return cachedResponse;
                    }
                    return caches.match(OFFLINE_URL);
                })
        );
        return;
    }

    // 2. Static Assets (CSS, JS, Fonts, Images, Icons) -> Cache first with Network fallback
    if (
        url.pathname.startsWith('/front/') ||
        url.pathname.startsWith('/pwa-icons/') ||
        url.pathname.startsWith('/storage/') ||
        url.pathname.endsWith('.css') ||
        url.pathname.endsWith('.js') ||
        url.pathname.endsWith('.woff') ||
        url.pathname.endsWith('.woff2') ||
        url.pathname.endsWith('.ttf') ||
        url.pathname.endsWith('.png') ||
        url.pathname.endsWith('.jpg') ||
        url.pathname.endsWith('.jpeg') ||
        url.pathname.endsWith('.svg') ||
        url.pathname.endsWith('.webp')
    ) {
        event.respondWith(
            caches.match(request).then((cachedResponse) => {
                if (cachedResponse) {
                    // Update cache in background
                    fetch(request).then((networkResponse) => {
                        if (networkResponse && networkResponse.status === 200) {
                            caches.open(CACHE_NAME).then((cache) => cache.put(request, networkResponse));
                        }
                    }).catch(() => {});
                    return cachedResponse;
                }
                return fetch(request).then((networkResponse) => {
                    if (networkResponse && networkResponse.status === 200) {
                        const copy = networkResponse.clone();
                        caches.open(CACHE_NAME).then((cache) => cache.put(request, copy));
                    }
                    return networkResponse;
                });
            })
        );
        return;
    }

    // 3. Default -> Network with Cache fallback
    event.respondWith(
        fetch(request)
            .then((response) => {
                if (response && response.status === 200) {
                    const copy = response.clone();
                    caches.open(CACHE_NAME).then((cache) => cache.put(request, copy));
                }
                return response;
            })
            .catch(() => caches.match(request))
    );
});
