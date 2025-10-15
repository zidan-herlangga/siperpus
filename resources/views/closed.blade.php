<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perpustakaan Tutup</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link rel="icon" href="{{ asset('assets/image/favicon.png') }}" type="image/png">
</head>

<body
    class="bg-gradient-to-br from-red-50 via-rose-50 to-orange-50 dark:from-gray-900 dark:via-gray-800 dark:to-gray-900 transition-colors duration-500 flex items-center justify-center min-h-screen">

    <div
        class="text-center bg-white/90 dark:bg-gray-800/90 backdrop-blur-lg p-10 md:p-12 rounded-3xl shadow-2xl border border-red-100 dark:border-gray-700 max-w-lg mx-auto transform transition-all duration-500 hover:scale-[1.02]">

        <div
            class="w-24 h-24 bg-red-100 dark:bg-red-900/30 rounded-full mx-auto flex items-center justify-center mb-6 shadow-inner">
            <i class="fas fa-store-slash text-red-500 dark:text-red-400 text-5xl"></i>
        </div>

        <h1 class="text-3xl font-bold text-gray-800 dark:text-gray-100 mb-3 tracking-tight">
            Perpustakaan Sedang Tutup
        </h1>

        <p class="text-gray-600 dark:text-gray-300 mb-8 leading-relaxed">
            Maaf, perpustakaan digital kami hanya dapat diakses pada jam operasional.
            Silakan kembali lagi nanti.
        </p>

        <div class="bg-gray-50 dark:bg-gray-700/50 rounded-xl p-5 text-left shadow-sm">
            <h3 class="font-semibold text-gray-800 dark:text-gray-200 mb-3 flex items-center gap-2">
                <i class="fas fa-clock text-red-500 dark:text-red-400"></i> Jam Operasional
            </h3>
            <ul class="text-gray-600 dark:text-gray-300 text-sm space-y-1">
                <li><strong>Senin - Jumat:</strong> 07:00 – 16:00 WIB</li>
                <li><strong>Sabtu - Minggu:</strong> Tutup</li>
            </ul>
        </div>

        <div class="mt-10">
            <a href="https://www.youtube.com/@smkkaryaguna2bekasi"
                class="inline-flex items-center gap-2 bg-red-500 hover:bg-red-600 text-white px-6 py-3 rounded-full font-semibold shadow-md transition-transform duration-300 hover:scale-105">
                <i class="fas fa-arrow-left"></i> Kembali ke Beranda
            </a>
        </div>
    </div>

</body>

</html>
