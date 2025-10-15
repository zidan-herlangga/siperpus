<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#065f46">
    <meta name="description" content="Aplikasi Peminjaman Buku Online Perpustakaan SMK Karya Guna 2 Bekasi">
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">

    <title>@yield('title', 'Perpustakaan Sekolah')</title>

    <link rel="icon" href="{{ asset('assets/image/favicon.png') }}" type="image/png">
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- <script src="https://cdn.tailwindcss.com/3.4.17" async></script> --}}

</head>

<body class="bg-gray-50 min-h-screen flex flex-col overflow-x-hidden">

    {{-- HEADER --}}
    <header class="gradient-bg text-white shadow-lg sticky top-0 z-50">
        <div class="container mx-auto px-4 py-4 flex justify-between items-center">

            {{-- LOGO --}}
            <a href="{{ route('homepage') }}" class="flex items-center space-x-2">
                <img src="{{ asset('assets/image/logo-light-smkg2.png') }}" alt="Logo SMK Karya Guna 2 Bekasi"
                    class="w-auto h-10 md:h-12">
            </a>

            {{-- NAVIGASI DESKTOP --}}
            <nav
                class="hidden md:flex items-center gap-6 font-medium backdrop-blur-md bg-white/10 px-6 py-3 rounded-full shadow-lg border border-white/20">
                @auth('student')
                    <a href="{{ route('student.dashboard') }}"
                        class="relative px-3 py-2 transition-all duration-300
                {{ request()->routeIs('student.dashboard')
                    ? 'text-yellow-400 after:absolute after:bottom-0 after:left-0 after:h-[2px] after:w-full after:bg-yellow-400'
                    : 'text-white/90 hover:text-yellow-300 hover:after:absolute hover:after:bottom-0 hover:after:left-0 hover:after:h-[2px] hover:after:w-full hover:after:bg-yellow-300 hover:after:transition-all hover:after:duration-300' }}">
                        Dashboard
                    </a>

                    <a href="{{ route('books.index') }}"
                        class="relative px-3 py-2 transition-all duration-300
                {{ request()->routeIs('books.*')
                    ? 'text-yellow-400 after:absolute after:bottom-0 after:left-0 after:h-[2px] after:w-full after:bg-yellow-400'
                    : 'text-white/90 hover:text-yellow-300 hover:after:absolute hover:after:bottom-0 hover:after:left-0 hover:after:h-[2px] hover:after:w-full hover:after:bg-yellow-300 hover:after:transition-all hover:after:duration-300' }}">
                        Katalog
                    </a>

                    <a href="#" onclick="openBorrowGuideModal()"
                        class="text-white/90 hover:text-yellow-300 transition-colors duration-300">
                        Petunjuk
                    </a>

                    <form action="{{ route('student.logout') }}" method="POST" class="inline">
                        @csrf
                        <button type="submit"
                            class="ml-3 bg-gradient-to-r from-red-500 to-red-600 text-white px-5 py-2 rounded-full shadow-md hover:shadow-lg hover:scale-105 transition-all duration-300">
                            Logout
                        </button>
                    </form>
                @else
                    <a href="{{ route('books.index') }}"
                        class="relative px-3 py-2 transition-all duration-300
                {{ request()->routeIs('books.*')
                    ? 'text-yellow-400 after:absolute after:bottom-0 after:left-0 after:h-[2px] after:w-full after:bg-yellow-400'
                    : 'text-white/90 hover:text-yellow-300 hover:after:absolute hover:after:bottom-0 hover:after:left-0 hover:after:h-[2px] hover:after:w-full hover:after:bg-yellow-300 hover:after:transition-all hover:after:duration-300' }}">
                        Katalog
                    </a>

                    <a href="#" onclick="openBorrowGuideModal()"
                        class="text-white/90 hover:text-yellow-300 transition-colors duration-300">
                        Petunjuk
                    </a>

                    <a href="{{ route('student.register.form') }}"
                        class="relative px-3 py-2 transition-all duration-300
                {{ request()->routeIs('student.register.form')
                    ? 'text-yellow-400 after:absolute after:bottom-0 after:left-0 after:h-[2px] after:w-full after:bg-yellow-400'
                    : 'text-white/90 hover:text-yellow-300 hover:after:absolute hover:after:bottom-0 hover:after:left-0 hover:after:h-[2px] hover:after:w-full hover:after:bg-yellow-300 hover:after:transition-all hover:after:duration-300' }}">
                        Daftar
                    </a>

                    <a href="{{ route('student.login.form') }}"
                        class="ml-3 bg-gradient-to-r from-yellow-500 to-yellow-400 text-gray-900 font-semibold px-6 py-2 rounded-full shadow-md hover:shadow-lg hover:scale-105 transition-all duration-300">
                        Login
                    </a>
                @endauth
            </nav>


            {{-- TOMBOL MENU MOBILE --}}
            <button id="menuToggle" class="md:hidden text-white text-2xl focus:outline-none">
                <i class="fas fa-bars"></i>
            </button>
        </div>
    </header>

    {{-- OFFCANVAS MODERN --}}
    <div id="offcanvas" class="offcanvas">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-lg font-bold text-green-700">Menu</h2>
            <button id="closeOffcanvas"><i class="fas fa-times text-green-700 text-xl"></i></button>
        </div>
        <div class="flex flex-col flex-grow">
            @auth('student')
                <a href="{{ route('student.dashboard') }}">Dashboard</a>
                <a href="{{ route('books.index') }}">Katalog Buku</a>
                <a href="#" onclick="openBorrowGuideModal()">Petunjuk</a>
                <form action="{{ route('student.logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="text-left w-full text-red-600 font-semibold">Logout</button>
                </form>
            @else
                <a href="{{ route('homepage') }}">Beranda</a>
                <a href="{{ route('books.index') }}">Katalog Buku</a>
                <a href="#" onclick="openBorrowGuideModal()">Petunjuk</a>
                <a href="{{ route('student.register.form') }}">Daftar</a>
                <a href="{{ route('student.login.form') }}">Login</a>
            @endauth
        </div>
    </div>

    <div id="offcanvasOverlay" class="offcanvas-overlay"></div>

    {{-- MAIN CONTENT --}}
    <main class="flex-grow">
        @yield('content')
    </main>

    {{-- FOOTER --}}
    <footer class="gradient-bg text-white py-12 mt-12 hidden md:block">
        <div class="container mx-auto px-4 text-center">
            <p class="text-sm text-gray-200">© {{ date('Y') }} Perpustakaan SMK Karya Guna 2 Bekasi. Hak Cipta
                Dilindungi.</p>
        </div>
    </footer>

    {{-- MODAL PETUNJUK --}}
    <div id="borrowGuideModal"
        class="fixed inset-0 bg-black bg-opacity-60 flex items-center justify-center p-4 z-50 hidden opacity-0 transition-opacity duration-300">
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
                <li><strong>Selesai:</strong> Buku berhasil dipinjam! Cek halaman Dashboard untuk melihat detail
                    peminjaman Anda.</li>
            </ol>
        </div>
    </div>

    <script src="{{ asset('assets/js/main.js') }}"></script>
    <script>
        const menuToggle = document.getElementById('menuToggle');
        const offcanvas = document.getElementById('offcanvas');
        const offcanvasOverlay = document.getElementById('offcanvasOverlay');
        const closeOffcanvas = document.getElementById('closeOffcanvas');

        menuToggle.addEventListener('click', () => {
            offcanvas.classList.add('active');
            offcanvasOverlay.classList.add('active');
            document.body.style.overflow = 'hidden'; // prevent scroll
        });

        closeOffcanvas.addEventListener('click', closeMenu);
        offcanvasOverlay.addEventListener('click', closeMenu);

        function closeMenu() {
            offcanvas.classList.remove('active');
            offcanvasOverlay.classList.remove('active');
            document.body.style.overflow = '';
        }
    </script>
</body>

</html>
