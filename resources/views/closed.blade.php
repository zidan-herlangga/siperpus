<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perpustakaan Tutup</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link rel="icon" href="{{ asset('assets/image/favicon.png') }}" type="image/png">
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
</head>

<body class="gradient-bg flex items-center justify-center min-h-screen">
    <div class="text-center bg-white p-12 rounded-2xl shadow-2xl max-w-lg mx-auto">
        <div class="w-20 h-20 bg-red-100 rounded-full mx-auto flex items-center justify-center mb-6">
            <i class="fas fa-store-slash text-red-500 text-4xl"></i>
        </div>
        <h1 class="text-3xl font-bold text-gray-800 mb-4">Perpustakaan Sedang Tutup</h1>
        <p class="text-gray-600 mb-8">
            Maaf, perpustakaan digital kami hanya dapat diakses pada hari kerja. Silakan kembali lagi nanti.
        </p>
        <div class="bg-gray-50 rounded-lg p-4 text-left">
            <h3 class="font-semibold text-gray-700 mb-2">Jam Operasional:</h3>
            <ul class="text-gray-600 text-sm space-y-1">
                <li><strong>Senin - Jumat:</strong> 07:00 - 16:00 WIB</li>
                <li><strong>Sabtu - Minggu:</strong> Tutup</li>
            </ul>
        </div>
    </div>
</body>

</html>
