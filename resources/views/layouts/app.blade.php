<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#065f46">
    <meta name="description" content="Aplikasi Peminjaman Buku Online Perpustakaan SMK Karya Guna 2 Bekasi">
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">

    <!-- PWA  -->
    <meta name="theme-color" content="#6777ef" />
    <link rel="apple-touch-icon" href="{{ asset('assets/image/favicon.png') }}">
    <link rel="manifest" href="{{ asset('/manifest.json') }}">

    {{-- ENV --}}
    <title>@yield('title', config('app.name'))</title>

    <link rel="icon" href="{{ asset('assets/image/favicon.png') }}" type="image/png">
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <script src="https://cdn.tailwindcss.com/3.4.17" async></script>
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
            <nav class="hidden md:flex items-center gap-6 font-medium">
                @auth('student')
                    <a href="{{ route('student.dashboard') }}"
                        class="flex items-center gap-2 px-3 py-2 rounded-lg transition-colors duration-200 {{ request()->routeIs('student.dashboard') ? 'bg-white/20 text-yellow-300' : 'text-white/90 hover:bg-white/10' }}">
                        <i class="fas fa-tachometer-alt"></i>
                        Dashboard
                    </a>

                    <a href="{{ route('books.index') }}"
                        class="flex items-center gap-2 px-3 py-2 rounded-lg transition-colors duration-200 {{ request()->routeIs('books.*') ? 'bg-white/20 text-yellow-300' : 'text-white/90 hover:bg-white/10' }}">
                        <i class="fas fa-book"></i>
                        Katalog
                    </a>

                    <a href="#" onclick="openBorrowGuideModal()"
                        class="flex items-center gap-2 px-3 py-2 rounded-lg transition-colors duration-200 text-white/90 hover:bg-white/10">
                        <i class="fas fa-info-circle"></i>
                        Petunjuk
                    </a>

                    <form action="{{ route('student.logout') }}" method="POST" class="inline">
                        @csrf
                        <button type="submit"
                            class="flex items-center gap-2 bg-red-600 text-white px-4 py-2 rounded-lg shadow hover:bg-red-700 transition-colors duration-200">
                            <i class="fas fa-sign-out-alt"></i>
                            Logout
                        </button>
                    </form>
                @else
                    <!-- Add this inside <body> -->
                    <button id="pwa-install-btn"
                        style="display:none; position: fixed; bottom: 20px; right: 20px; padding: 10px 20px; background-color: #007bff; color: white; border: none; border-radius: 8px; z-index: 1000;">
                        Install App
                    </button>
                    <a href="{{ route('books.index') }}"
                        class="flex items-center gap-2 px-3 py-2 rounded-lg transition-colors duration-200 {{ request()->routeIs('books.*') ? 'bg-white/20 text-yellow-300' : 'text-white/90 hover:bg-white/10' }}">
                        <i class="fas fa-book"></i>
                        Katalog
                    </a>

                    <a href="#" onclick="openBorrowGuideModal()"
                        class="flex items-center gap-2 px-3 py-2 rounded-lg transition-colors duration-200 text-white/90 hover:bg-white/10">
                        <i class="fas fa-info-circle"></i>
                        Petunjuk
                    </a>

                    {{-- Dropdown untuk Login/Daftar --}}
                    <div class="relative dropdown group">
                        <button
                            class="flex items-center gap-2 bg-yellow-500 text-gray-900 font-semibold px-4 py-2 rounded-lg shadow-md hover:bg-yellow-400 transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-yellow-300">
                            <i class="fas fa-user"></i>
                            <span>Akun</span>
                            <i
                                class="fas fa-chevron-down ml-1 text-xs transition-transform duration-200 group-hover:rotate-180"></i>
                        </button>

                        <div
                            class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg opacity-0 scale-95 group-hover:opacity-100 group-hover:scale-100 transform transition-all duration-200 origin-top-right z-50">
                            <a href="{{ route('student.login.form') }}"
                                class="flex items-center gap-2 px-4 py-2 text-gray-700 hover:bg-yellow-100 transition-colors duration-150 rounded-t-lg">
                                <i class="fas fa-sign-in-alt"></i>
                                Login
                            </a>
                            <a href="{{ route('student.register.form') }}"
                                class="flex items-center gap-2 px-4 py-2 text-gray-700 hover:bg-yellow-100 transition-colors duration-150 rounded-b-lg">
                                <i class="fas fa-user-plus"></i>
                                Daftar
                            </a>
                        </div>
                    </div>
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
        <div class="flex justify-between items-center mb-6 bg-black/10 p-2 rounded-lg">
            <h2 class="text-lg font-bold text-green-700">Menu</h2>
            {{-- <img src="{{ asset('assets/image/logo-light-smkg2.png') }}" alt="" class="w-auto h-10"> --}}
            <button id="closeOffcanvas"><i class="fas fa-times text-gray-700 text-xl"></i></button>
        </div>
        <hr>
        <div class="flex flex-col flex-grow space-y-4">
            @auth('student')
                <a href="{{ route('student.dashboard') }}"
                    class="flex items-center gap-3 py-2 text-gray-700 hover:text-green-700">
                    <i class="fas fa-tachometer-alt w-5"></i>
                    Dashboard
                </a>
                <a href="{{ route('books.index') }}"
                    class="flex items-center gap-3 py-2 text-gray-700 hover:text-green-700">
                    <i class="fas fa-book w-5"></i>
                    Katalog Buku
                </a>
                <a href="#" onclick="openBorrowGuideModal()"
                    class="flex items-center gap-3 py-2 text-gray-700 hover:text-green-700">
                    <i class="fas fa-info-circle w-5"></i>
                    Petunjuk
                </a>
                <form action="{{ route('student.logout') }}" method="POST" class="mt-auto">
                    @csrf
                    <button type="submit" class="flex items-center gap-3 w-full text-left text-red-600 font-semibold py-2">
                        <i class="fas fa-sign-out-alt w-5"></i>
                        Logout
                    </button>
                </form>
            @else
                <a href="{{ route('homepage') }}" class="flex items-center gap-3 py-2 text-gray-700 hover:text-green-700">
                    <i class="fas fa-home w-5"></i>
                    Beranda
                </a>
                <a href="{{ route('books.index') }}"
                    class="flex items-center gap-3 py-2 text-gray-700 hover:text-green-700">
                    <i class="fas fa-book w-5"></i>
                    Katalog Buku
                </a>
                <a href="#" onclick="openBorrowGuideModal()"
                    class="flex items-center gap-3 py-2 text-gray-700 hover:text-green-700">
                    <i class="fas fa-info-circle w-5"></i>
                    Petunjuk
                </a>
                <a href="{{ route('student.register.form') }}"
                    class="flex items-center gap-3 py-2 text-gray-700 hover:text-green-700">
                    <i class="fas fa-user-plus w-5"></i>
                    Daftar
                </a>
                <a href="{{ route('student.login.form') }}"
                    class="flex items-center gap-3 py-2 text-gray-700 hover:text-green-700">
                    <i class="fas fa-sign-in-alt w-5"></i>
                    Login
                </a>
            @endauth
        </div>
    </div>

    <div id="offcanvasOverlay" class="offcanvas-overlay"></div>

    {{-- MAIN CONTENT --}}
    <main class="flex-grow">
        @yield('content')
    </main>

    {{-- FOOTER --}}
    {{-- <footer class="gradient-bg text-white py-12 mt-12 hidden md:block">
        <div class="container mx-auto px-4 text-center">
            <p class="text-sm text-gray-200">© {{ date('Y') }} Perpustakaan SMK Karya Guna 2 Bekasi. Hak Cipta
                Dilindungi.</p>
        </div>
    </footer> --}}

    {{-- MODAL PETUNJUK --}}
    <div id="borrowGuideModal"
        class="fixed inset-0 bg-black bg-opacity-60 flex items-center justify-center p-4 z-50 hidden modal-fade">
        <div class="bg-white rounded-lg shadow-lg p-6 max-w-md w-full">
            <div class="flex justify-between items-center mb-4">
                <h2 class="text-xl font-bold text-gray-800">Petunjuk Peminjaman Buku</h2>
                <button id="closeGuideBtn" class="text-gray-400 hover:text-gray-700">&times;</button>
            </div>
            <ol class="list-decimal list-inside space-y-3 text-gray-600">
                <li><strong>Daftar/Login:</strong> Pastikan Anda sudah memiliki akun dan dalam keadaan login.</li>
                <li><strong>Cari Buku:</strong> Jelajahi katalog untuk menemukan buku yang ingin Anda pinjam.</li>
                <li><strong>Klik Pinjam:</strong> Pada halaman detail buku, klik tombol "Pinjam Buku".</li>
                <li><strong>Konfirmasi:</strong> Setujui syarat dan ketentuan pada modal konfirmasi peminjaman.</li>
                <li><strong>Selesai:</strong> Buku berhasil dipinjam! Cek halaman Dashboard untuk melihat detail
                    peminjaman Anda.</li>
            </ol>
        </div>
    </div>

    <script>
        // Fungsi untuk offcanvas menu
        const menuToggle = document.getElementById('menuToggle');
        const offcanvas = document.getElementById('offcanvas');
        const offcanvasOverlay = document.getElementById('offcanvasOverlay');
        const closeOffcanvas = document.getElementById('closeOffcanvas');

        menuToggle.addEventListener('click', () => {
            offcanvas.classList.add('active');
            offcanvasOverlay.classList.add('active');
            document.body.style.overflow = 'hidden';
        });

        closeOffcanvas.addEventListener('click', closeMenu);
        offcanvasOverlay.addEventListener('click', closeMenu);

        function closeMenu() {
            offcanvas.classList.remove('active');
            offcanvasOverlay.classList.remove('active');
            document.body.style.overflow = '';
        }

        // Fungsi untuk modal petunjuk
        function openBorrowGuideModal() {
            const modal = document.getElementById('borrowGuideModal');
            modal.classList.remove('hidden');
            setTimeout(() => {
                modal.style.opacity = '1';
            }, 10);
        }

        document.getElementById('closeGuideBtn').addEventListener('click', function() {
            const modal = document.getElementById('borrowGuideModal');
            modal.style.opacity = '0';
            setTimeout(() => {
                modal.classList.add('hidden');
            }, 200);
        });
    </script>

    <script src="{{ asset('/sw.js') }}"></script>
    <script>
        if ("serviceWorker" in navigator) {
            // Register a service worker hosted at the root of the
            // site using the default scope.
            navigator.serviceWorker.register("/sw.js").then(
                (registration) => {
                    console.log("Service worker registration succeeded:", registration);
                },
                (error) => {
                    console.error(`Service worker registration failed: ${error}`);
                },
            );
        } else {
            console.error("Service workers are not supported.");
        }
    </script>
</body>

</html>
