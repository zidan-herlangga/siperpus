<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#065f46">
    <meta name="description" content="Aplikasi Peminjaman Buku Online Perpustakaan SMK Karya Guna 2 Bekasi">
    <title>@yield('title', 'Perpustakaan Sekolah')</title>
    <link rel="manifest" href="{{ asset('manifest.json') }}">
    <link rel="icon" href="{{ asset('assets/image/favicon.png') }}" type="image/png">
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <link rel="stylesheet" href="https://oyb1uxkjg.localto.net/assets/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    {{-- Aset disatukan melalui Vite --}}
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        /* Bottom navigation modern look */
        .mobile-nav {
            border-radius: 13px 13px 0 0;
        }
        /* Modal animasi */
        .modal-content {
            animation: popUp 0.3s ease-out forwards;
        }

        @keyframes popUp {
            from {
                transform: scale(0.9);
                opacity: 0;
            }

            to {
                transform: scale(1);
                opacity: 1;
            }
        }
    </style>
</head>
<body class="bg-gray-50 min-h-screen flex flex-col overflow-x-hidden">

    {{-- HEADER --}}
    <header class="gradient-bg text-white shadow-lg sticky top-0 z-40">
        <div class="container mx-auto px-4 py-4">
            <div class="flex justify-between items-center">
                <a href="/">
                    <img src="{{ asset('assets/image/logo-light-smkg2.png') }}" alt="Logo SMK Karya Guna 2 Bekasi" class="w-auto h-10 md:h-12">
                </a>

                {{-- NAVIGASI DESKTOP --}}
                <nav class="hidden md:flex items-center space-x-8">
                    @auth('student')
                        <a href="{{ route('student.dashboard') }}" class="font-medium py-2 transition duration-300 {{ request()->routeIs('student.dashboard') ? 'text-yellow-400 border-b-2 border-yellow-400' : 'hover:text-yellow-300' }}">Dashboard</a>
                        <a href="{{ route('books.index') }}" class="font-medium py-2 transition duration-300 {{ request()->routeIs('books.*') ? 'text-yellow-400 border-b-2 border-yellow-400' : 'hover:text-yellow-300' }}">Katalog Buku</a>
                        <form action="{{ route('student.logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="bg-yellow-500 text-white px-5 py-2 rounded-full font-semibold hover:bg-white hover:text-green-700 transition duration-300">Logout</button>
                        </form>
                    @else
                        <a href="{{ route('books.index') }}" class="font-medium py-2 transition duration-300 {{ request()->routeIs('books.*') ? 'text-yellow-400 border-b-2 border-yellow-400' : 'hover:text-yellow-300' }}">Katalog Buku</a>
                        <a href="{{ route('student.register.form') }}" class="font-medium py-2 transition duration-300 {{ request()->routeIs('student.register.form') ? 'text-yellow-400 border-b-2 border-yellow-400' : 'hover:text-yellow-300' }}">Daftar</a>
                        <a href="{{ route('student.login.form') }}" class="bg-white text-green-700 px-5 py-2 rounded-full font-semibold hover:bg-gray-200 transition duration-300">Login</a>
                    @endauth
                </nav>

                {{-- Tombol hamburger (jika diperlukan di masa depan) --}}
                <div class="md:hidden"></div>
            </div>
        </div>
    </header>

    {{-- MAIN CONTENT --}}
    <main class="flex-grow">
        @yield('content')
    </main>

    {{-- FOOTER (Hanya tampil di desktop) --}}
    <footer class="gradient-bg text-white py-12 mt-12 hidden md:block">
        <div class="container mx-auto px-4">
            <div class="grid md:grid-cols-4 gap-8">

                {{-- Logo dan Deskripsi --}}
                <div>
                    <img src="{{ asset('assets/image/logo-light-smkg2.png') }}" alt="Logo SMK Karya Guna 2 Bekasi"
                        class="mb-4 w-40">
                    <p class="text-gray-300 text-sm">Membangun generasi cerdas melalui literasi dan pengetahuan.</p>
                </div>

                {{-- Tautan Cepat --}}
                <div>
                    <h4 class="text-lg font-semibold mb-4 border-b-2 border-yellow-500 pb-2 inline-block">Tautan Cepat</h4>
                    <ul class="space-y-2 text-sm">
                        <li><a href="{{ route('books.index') }}" class="hover:text-yellow-400">Katalog Buku</a></li>
                        @guest('student')
                            <li><a href="{{ route('student.register.form') }}" class="hover:text-yellow-400">Daftar
                                    Siswa</a></li>
                            <li><a href="{{ route('student.login.form') }}" class="hover:text-yellow-400">Login
                                    Siswa</a></li>
                        @endguest
                        @auth('student')
                            <li><a href="{{ route('student.dashboard') }}" class="hover:text-yellow-400">Dashboard
                                    Saya</a></li>
                        @endauth
                    </ul>
                </div>

                {{-- Jam Operasional --}}
                <div>
                    <h4 class="text-lg font-semibold mb-4 border-b-2 border-yellow-500 pb-2 inline-block">Jam Operasional
                    </h4>
                    <p class="text-gray-300 text-sm">Senin - Jumat: 07:00 - 16:00</p>
                    <p class="text-gray-300 text-sm">Sabtu - Minggu: Tutup</p>

                    @php
                        $now = now()->setTimezone('Asia/Jakarta');
                        $day = $now->dayOfWeek;
                        $time = $now->format('H:i');
                        $isOpen = ($day >= 1 && $day <= 5 && $time >= '07:00' && $time < '16:00');
                    @endphp

                    <div class="mt-3">
                        @if ($isOpen)
                            <span class="bg-green-500 text-white text-xs font-bold px-3 py-1 rounded-full">
                                <i class="fas fa-check-circle mr-1"></i> Sedang Buka
                            </span>
                        @else
                            <span class="bg-red-500 text-white text-xs font-bold px-3 py-1 rounded-full">
                                <i class="fas fa-times-circle mr-1"></i> Tutup
                            </span>
                        @endif
                    </div>
                </div>

                {{-- Kontak --}}
                <div>
                    <h4 class="text-lg font-semibold mb-4 border-b-2 border-yellow-500 pb-2 inline-block">Kontak</h4>
                    <div class="space-y-2 text-gray-300 text-sm">
                        <p><i class="fas fa-map-marker-alt mr-2"></i><a href="https://maps.app.goo.gl/ruoLZxPsTb8hB4Ep7"
                                target="_blank" class="hover:text-yellow-400">Jl. Karang Satria No.503, Bekasi</a></p>
                        <p><i class="fas fa-phone mr-2"></i><a href="tel:(021)8800523"
                                class="hover:text-yellow-400">(021) 8800523</a></p>
                        <p><i class="fas fa-envelope mr-2"></i><a
                                href="mailto:info@smkkaryaguna2bekasi.sch.id"
                                class="hover:text-yellow-400">info@smkkaryaguna2bekasi.sch.id</a></p>
                    </div>
                </div>
            </div>

            <div class="border-t border-white mt-8 pt-6 text-center text-sm text-gray-200">
                © {{ date('Y') }} Perpustakaan SMK Karya Guna 2 Bekasi. Hak Cipta Dilindungi.
            </div>
        </div>
    </footer>

    {{-- NAVIGASI BAWAH UNTUK MOBILE --}}
    <nav class="mobile-nav fixed bottom-0 left-0 right-0 text-white shadow-lg md:hidden z-50 gradient-bg">
        <div class="flex justify-around items-center h-16">
            @auth('student')
                <a href="{{ route('student.dashboard') }}" class="flex flex-col items-center text-xs font-medium transition-all duration-200 {{ request()->routeIs('student.dashboard') ? 'text-yellow-400 scale-110' : 'text-gray-300 hover:text-white' }}">
                    <i class="fas fa-home text-xl mb-1"></i><span>Dashboard</span>
                </a>
                <a href="{{ route('books.index') }}" class="flex flex-col items-center text-xs font-medium transition-all duration-200 {{ request()->routeIs('books.*') ? 'text-yellow-400 scale-110' : 'text-gray-300 hover:text-white' }}">
                    <i class="fas fa-book text-xl mb-1"></i><span>Katalog</span>
                </a>
                <button onclick="openBorrowGuideModal()" class="flex flex-col items-center text-xs font-medium text-gray-300 hover:text-white">
                    <i class="fas fa-info-circle text-xl mb-1"></i><span>Petunjuk</span>
                </button>
                <form action="{{ route('student.logout') }}" method="POST" class="flex flex-col items-center text-xs font-medium">
                    @csrf
                    <button type="submit" class="text-gray-300 hover:text-white"><i class="fas fa-sign-out-alt text-xl mb-1"></i><span>Logout</span></button>
                </form>
            @else
                <a href="/" class="flex flex-col items-center text-xs font-medium transition-all duration-200 {{ request()->routeIs('homepage') ? 'text-yellow-400 scale-110' : 'text-gray-300 hover:text-white' }}">
                    <i class="fas fa-home text-xl mb-1"></i><span>Beranda</span>
                </a>
                <a href="{{ route('books.index') }}" class="flex flex-col items-center text-xs font-medium transition-all duration-200 {{ request()->routeIs('books.*') ? 'text-yellow-400 scale-110' : 'text-gray-300 hover:text-white' }}">
                    <i class="fas fa-book text-xl mb-1"></i><span>Katalog</span>
                </a>
                <a href="{{ route('student.register.form') }}" class="flex flex-col items-center text-xs font-medium text-gray-300 hover:text-white">
                    <i class="fas fa-user-plus text-xl mb-1"></i><span>Daftar</span>
                </a>
                <a href="{{ route('student.login.form') }}" class="flex flex-col items-center text-xs font-medium text-gray-300 hover:text-white">
                    <i class="fas fa-sign-in-alt text-xl mb-1"></i><span>Login</span>
                </a>
            @endauth
        </div>
    </nav>
    
    {{-- MODAL PETUNJUK PEMINJAMAN --}}
    <div id="borrowGuideModal" class="fixed inset-0 bg-black bg-opacity-60 flex items-center justify-center p-4 z-50 hidden opacity-0 transition-opacity duration-300">
        <div class="bg-white rounded-2xl shadow-2xl p-8 max-w-md w-full modal-content">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-xl font-bold text-gray-800">Petunjuk Peminjaman Buku</h2>
                <button id="closeGuideBtn" class="text-gray-400 hover:text-gray-700">&times;</button>
            </div>
            <ol class="list-decimal list-inside space-y-4 text-gray-600">
                <li><strong>Daftar/Login:</strong> Pastikan Anda sudah memiliki akun dan dalam keadaan login.</li>
                <li><strong>Cari Buku:</strong> Jelajahi katalog untuk menemukan buku yang ingin Anda pinjam.</li>
                <li><strong>Klik Pinjam:</strong> Pada halaman detail buku, klik tombol "Pinjam Buku".</li>
                <li><strong>Konfirmasi:</strong> Setujui syarat dan ketentuan pada modal konfirmasi peminjaman.</li>
                <li><strong>Selesai:</strong> Buku berhasil dipinjam! Cek halaman Dashboard untuk melihat detail peminjaman Anda.</li>
            </ol>
        </div>
    </div>
    
    {{-- MODAL VERIFIKASI (TETAP SAMA) --}}
    @if (session('email_verified'))
        <div id="verificationModal" class="fixed inset-0 bg-black bg-opacity-60 flex items-center justify-center p-4 z-50 transition-opacity duration-300">
            <div class="bg-white rounded-2xl shadow-2xl p-8 md:p-10 text-center max-w-md w-full modal-content">
                <div class="w-20 h-20 bg-green-100 rounded-full mx-auto flex items-center justify-center mb-6">
                    <i class="fas fa-check text-green-600 text-4xl"></i>
                </div>
                <h2 class="text-2xl font-bold text-gray-800 mb-4">Verifikasi Berhasil!</h2>
                <p class="text-gray-600 mb-8">
                    Terima kasih! Akun Anda telah berhasil diverifikasi. Sekarang Anda dapat mengakses semua layanan
                    perpustakaan.
                </p>
                <button id="closeModalBtn"
                    class="w-full bg-green-600 text-white font-semibold py-3 rounded-lg hover:bg-green-700 transition">
                    Mulai Menjelajah Buku
                </button>
            </div>
        </div>
    @endif
    
    <script src="{{ asset('assets/js/main.js') }}"></script>
    <script>
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register('/sw.js').then(registration => {
                        console.log('ServiceWorker registration successful with scope: ', registration.scope);
                    }).catch(error => {
                    console.log('ServiceWorker registration failed: ', error);
                });
            });
        }
    </script>
</body>
</html>