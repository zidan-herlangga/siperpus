const preLoad = function () {
    return caches.open("offline").then(function (cache) {
        // Hanya cache root dan halaman penting lainnya.
        // offline.html sudah tidak diperlukan lagi.
        return cache.addAll(filesToCache);
    });
};

self.addEventListener("install", function (event) {
    event.waitUntil(preLoad());
});

const filesToCache = [
    '/',
    // '/offline.html' // DIHAPUS
];

// Fungsi ini tidak lagi diperlukan
// const checkResponse = function (request) { ... };

// Fungsi ini masih berguna untuk menambahkan cache saat permintaan berhasil
const addToCache = function (request) {
    // Hanya cache permintaan http(s)
    if (!request.url.startsWith('http')) {
        return Promise.resolve();
    }
    return caches.open("offline").then(function (cache) {
        return fetch(request).then(function (response) {
            // Kloning respons karena respons adalah stream dan hanya bisa dibaca sekali
            const responseToCache = response.clone();
            cache.put(request, responseToCache);
        });
    });
};

// Fungsi ini tidak lagi diperlukan
// const returnFromCache = function (request) { ... };

self.addEventListener("fetch", function (event) {
    // Kita hanya ingin menangani permintaan GET. Cache permintaan POST biasanya bukan ide yang baik.
    if (event.request.method !== 'GET') {
        return;
    }

    // Kita hanya ingin cache permintaan http/https.
    if (!event.request.url.startsWith('http')) {
        return;
    }

    event.respondWith(
        fetch(event.request)
            .then(function (response) {
                // Jika permintaan jaringan berhasil, kita kembalikan responsnya
                // dan juga menambahkannya ke cache untuk kunjungan berikutnya.
                // Proses penambahan ke cache berjalan di latar belakang.
                event.waitUntil(addToCache(event.request));
                return response;
            })
        // Jika fetch (permintaan jaringan) gagal, kita tidak melakukan apa-apa.
        // Tidak ada .catch() yang menangkap error, sehingga error akan diteruskan ke browser.
        // Browser kemudian akan menampilkan halaman error defaultnya (misalnya "Tidak ada koneksi internet").
        // Dengan ini, kita telah "menghilangkan" halaman offline.html.
    );
});