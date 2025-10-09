const CACHE_NAME = 'siperpus-cache-v2'; // Ganti versi cache
const assetsToCache = [
    '/',
    '/books',
    '/offline.html', // Halaman fallback
    '/assets/image/logo-light-smkg2.png',
    // Path ke ikon PWA Anda
    '/assets/image/favicon.png' 
];

// Event 'install': Cache aset-aset penting
self.addEventListener('install', event => {
    event.waitUntil(
        caches.open(CACHE_NAME)
            .then(cache => {
                console.log('Opened cache');
                return cache.addAll(assetsToCache);
            })
    );
});

// Event 'fetch': Menyajikan konten dari cache jika tersedia
self.addEventListener('fetch', event => {
    event.respondWith(
        caches.match(event.request)
            .then(response => {
                // Jika request ada di cache, sajikan dari cache
                if (response) {
                    return response;
                }
                // Jika tidak, coba ambil dari network
                return fetch(event.request).catch(() => {
                    // Jika network gagal (offline), tampilkan halaman fallback
                    return caches.match('/offline.html');
                });
            })
    );
});