<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#065f46">
    <meta name="description" content="Aplikasi Peminjaman Buku Online Perpustakaan SMK Karya Guna 2 Bekasi">
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">

    <!-- PWA  -->
    <meta name="theme-color" content="#065f46" />
    <link rel="apple-touch-icon" href="{{ asset('assets/image/favicon.png') }}">
    <link rel="manifest" href="{{ asset('/manifest.json') }}">

    {{-- ENV --}}
    <title>@yield('title', config('app.name'))</title>

    <link rel="icon" href="{{ asset('assets/image/favicon.png') }}" type="image/png">
    <link rel="stylesheet" href="{{ asset('assets/css/layout.css') }}">
    @yield('styles')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" media="print" onload="this.media='all'">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Preload Tailwind CSS untuk loading lebih cepat -->
    <link rel="preload" href="https://cdn.tailwindcss.com/3.4.17" as="script">
    <script src="https://cdn.tailwindcss.com/3.4.17"></script>
</head>

<body class="bg-gray-50 min-h-screen flex flex-col overflow-x-hidden">
    <!-- Loading Screen -->
    <div id="loading-screen" class="fixed inset-0 bg-white z-50 flex items-center justify-center">
        <div class="text-center">
            <div class="loader border-4 border-gray-200 rounded-full w-12 h-12 mx-auto mb-4"></div>
            <p class="text-green">Memuat...</p>
        </div>
    </div>

    {{-- HEADER --}}
    <header class="gradient-bg text-white shadow-lg sticky top-0 z-50">
        <div class="container mx-auto px-4 py-4 flex justify-between items-center">

            {{-- LOGO --}}
            <a href="{{ route('homepage') }}" class="flex items-center space-x-2 smooth-transition hover:scale-105">
                <img src="{{ asset('assets/image/logo-light-smkg2.png') }}" alt="Logo SMK Karya Guna 2 Bekasi"
                    class="w-auto h-10 md:h-12">
            </a>

            {{-- NAVIGASI DESKTOP --}}
            <nav class="hidden md:flex items-center gap-6 font-medium">
                @auth('student')
                    <a href="{{ route('student.dashboard') }}"
                        class="flex items-center gap-2 px-3 py-2 rounded-lg smooth-transition {{ request()->routeIs('student.dashboard') ? 'bg-white/20 text-yellow-300' : 'text-white/90 hover:bg-white/10' }}">
                        <i class="fas fa-tachometer-alt"></i>
                        Dashboard
                    </a>

                    <a href="{{ route('books.index') }}"
                        class="flex items-center gap-2 px-3 py-2 rounded-lg smooth-transition {{ request()->routeIs('books.*') ? 'bg-white/20 text-yellow-300' : 'text-white/90 hover:bg-white/10' }}">
                        <i class="fas fa-book"></i>
                        Katalog
                    </a>

                    <a href="#guide" onclick="openBorrowGuideModal()"
                        class="flex items-center gap-2 px-3 py-2 rounded-lg smooth-transition text-white/90 hover:bg-white/10">
                        <i class="fas fa-info-circle"></i>
                        Petunjuk
                    </a>

                    <form action="{{ route('student.logout') }}" method="POST" class="inline">
                        @csrf
                        <button type="submit"
                            class="flex items-center gap-2 bg-red-600 text-white px-4 py-2 rounded-lg shadow smooth-transition hover:bg-red-700 btn-hover-effect">
                            <i class="fas fa-sign-out-alt"></i>
                            Keluar
                        </button>
                    </form>
                @else
                    <!-- PWA Install Button -->
                    <button id="pwa-install-btn"
                        class="flex items-center gap-2 bg-green text-white px-4 py-2 rounded-lg shadow smooth-transition hover:bg-dark-green btn-hover-effect" style="display:none;">
                        <i class="fas fa-download"></i>
                        Install App
                    </button>
                    
                    <a href="{{ route('books.index') }}"
                        class="flex items-center gap-2 px-3 py-2 rounded-lg smooth-transition {{ request()->routeIs('books.*') ? 'bg-white/20 text-yellow-300' : 'text-white/90 hover:bg-white/10' }}">
                        <i class="fas fa-book"></i>
                        Katalog
                    </a>

                    <a href="#" onclick="openBorrowGuideModal()"
                        class="flex items-center gap-2 px-3 py-2 rounded-lg smooth-transition text-white/90 hover:bg-white/10">
                        <i class="fas fa-info-circle"></i>
                        Petunjuk
                    </a>

                    {{-- Dropdown untuk Login/Daftar --}}
                    <div class="relative dropdown group">
                        <button
                            class="flex items-center gap-2 bg-yellow-500 text-gray-900 font-semibold px-4 py-2 rounded-lg shadow-md smooth-transition hover:bg-yellow-400 focus:outline-none focus:ring-2 focus:ring-yellow-300 btn-hover-effect">
                            <i class="fas fa-user"></i>
                            <span>Akun</span>
                            <i
                                class="fas fa-chevron-down ml-1 text-xs smooth-transition group-hover:rotate-180"></i>
                        </button>

                        <div
                            class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg opacity-0 scale-95 smooth-transition group-hover:opacity-100 group-hover:scale-100 transform origin-top-right z-50">
                            <a href="{{ route('student.login.form') }}"
                                class="flex items-center gap-2 px-4 py-2 text-gray-700 smooth-transition hover:bg-yellow-100 rounded-t-lg">
                                <i class="fas fa-sign-in-alt"></i>
                                Login
                            </a>
                            <a href="{{ route('student.register.form') }}"
                                class="flex items-center gap-2 px-4 py-2 text-gray-700 smooth-transition hover:bg-yellow-100 rounded-b-lg">
                                <i class="fas fa-user-plus"></i>
                                Daftar
                            </a>
                        </div>
                    </div>
                @endauth
            </nav>

            {{-- TOMBOL MENU MOBILE --}}
            <button id="menuToggle" class="md:hidden text-white text-2xl focus:outline-none smooth-transition hover:scale-110">
                <i class="fas fa-bars"></i>
            </button>
        </div>
    </header>

    {{-- OFFCANVAS MODERN --}}
    <div id="offcanvas" class="offcanvas fixed top-0 left-0 w-72 h-full z-40 transform -translate-x-full smooth-transition">
        <div class="flex justify-between items-center mb-6 bg-green/10 p-2 rounded-lg">
            <h2 class="text-lg font-bold text-green">Menu</h2>
            <button id="closeOffcanvas" class="text-gray-700 text-xl smooth-transition hover:text-green">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <hr class="border-green/20">
        <div class="flex flex-col flex-grow space-y-4 p-4">
            @auth('student')
                <a href="{{ route('student.dashboard') }}"
                    class="flex items-center gap-3 py-2 text-gray-700 smooth-transition hover:text-green hover:translate-x-2">
                    <i class="fas fa-tachometer-alt w-5"></i>
                    Dashboard
                </a>
                <a href="{{ route('books.index') }}"
                    class="flex items-center gap-3 py-2 text-gray-700 smooth-transition hover:text-green hover:translate-x-2">
                    <i class="fas fa-book w-5"></i>
                    Katalog Buku
                </a>
                <a href="#" onclick="openBorrowGuideModal()"
                    class="flex items-center gap-3 py-2 text-gray-700 smooth-transition hover:text-green hover:translate-x-2">
                    <i class="fas fa-info-circle w-5"></i>
                    Petunjuk
                </a>
                <form action="{{ route('student.logout') }}" method="POST" class="mt-auto">
                    @csrf
                    <button type="submit" class="flex items-center gap-3 w-full text-left text-red-600 font-semibold py-2 smooth-transition hover:bg-red-50 rounded-lg">
                        <i class="fas fa-sign-out-alt w-5"></i>
                        Keluar
                    </button>
                </form>
            @else
                <a href="{{ route('homepage') }}" class="flex items-center gap-3 py-2 text-gray-700 smooth-transition hover:text-green hover:translate-x-2">
                    <i class="fas fa-home w-5"></i>
                    Beranda
                </a>
                <a href="{{ route('books.index') }}"
                    class="flex items-center gap-3 py-2 text-gray-700 smooth-transition hover:text-green hover:translate-x-2">
                    <i class="fas fa-book w-5"></i>
                    Katalog Buku
                </a>
                <a href="#" onclick="openBorrowGuideModal()"
                    class="flex items-center gap-3 py-2 text-gray-700 smooth-transition hover:text-green hover:translate-x-2">
                    <i class="fas fa-info-circle w-5"></i>
                    Petunjuk
                </a>
                <a href="{{ route('student.register.form') }}"
                    class="flex items-center gap-3 py-2 text-gray-700 smooth-transition hover:text-green hover:translate-x-2">
                    <i class="fas fa-user-plus w-5"></i>
                    Daftar
                </a>
                <a href="{{ route('student.login.form') }}"
                    class="flex items-center gap-3 py-2 text-gray-700 smooth-transition hover:text-green hover:translate-x-2">
                    <i class="fas fa-sign-in-alt w-5"></i>
                    Login
                </a>
            @endauth
        </div>
    </div>

    <div id="offcanvasOverlay" class="fixed inset-0 bg-black bg-opacity-50 z-30 hidden smooth-transition"></div>

    {{-- MAIN CONTENT --}}
    <main class="flex-grow">
        @yield('content')
    </main>

    {{-- FOOTER --}}
    <footer class="gradient-bg text-white py-8 mt-12">
        <div class="container mx-auto px-4 text-center">
            <p class="text-sm text-gray-200">© {{ date('Y') }} Perpustakaan SMK Karya Guna 2 Bekasi. Hak Cipta Dilindungi.</p>
        </div>
    </footer>

    {{-- MODAL PETUNJUK --}}
    <div id="borrowGuideModal"
        class="fixed inset-0 bg-black bg-opacity-60 flex items-center justify-center p-4 z-50 hidden">
        <div class="bg-white rounded-lg shadow-lg p-6 max-w-md w-full modal-fade card-hover">
            <div class="flex justify-between items-center mb-4 border-b pb-2">
                <h2 class="text-xl font-bold text-green">Petunjuk Peminjaman Buku</h2>
                <button id="closeGuideBtn" class="text-gray-400 smooth-transition hover:text-gray-700 text-2xl">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <ol class="list-decimal list-inside space-y-3 text-gray-600">
                <li><strong>Daftar/Login:</strong> Pastikan Anda sudah memiliki akun dan dalam keadaan login.</li>
                <li><strong>Cari Buku:</strong> Jelajahi katalog untuk menemukan buku yang ingin Anda pinjam.</li>
                <li><strong>Klik Pinjam:</strong> Pada halaman detail buku, klik tombol "Pinjam Buku".</li>
                <li><strong>Konfirmasi:</strong> Setujui syarat dan ketentuan pada modal konfirmasi peminjaman.</li>
                <li><strong>Selesai:</strong> Buku berhasil dipinjam! Cek halaman Dashboard untuk melihat detail
                    peminjaman Anda.</li>
            </ol>
            <div class="mt-6 flex justify-end">
                <button onclick="closeBorrowGuideModal()" class="bg-green text-white px-4 py-2 rounded-lg smooth-transition hover:bg-dark-green btn-hover-effect">
                    Mengerti
                </button>
            </div>
        </div>
    </div>

    @yield('scripts')

    <script>
        
        // Optimasi loading dengan menyembunyikan loading screen setelah halaman dimuat
        window.addEventListener('load', function() {
            setTimeout(function() {
                document.getElementById('loading-screen').style.display = 'none';
            }, 500);
        });

        // Fungsi untuk offcanvas menu
        const menuToggle = document.getElementById('menuToggle');
        const offcanvas = document.getElementById('offcanvas');
        const offcanvasOverlay = document.getElementById('offcanvasOverlay');
        const closeOffcanvas = document.getElementById('closeOffcanvas');

        menuToggle.addEventListener('click', () => {
            offcanvas.classList.remove('-translate-x-full');
            offcanvasOverlay.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        });

        closeOffcanvas.addEventListener('click', closeMenu);
        offcanvasOverlay.addEventListener('click', closeMenu);

        function closeMenu() {
            offcanvas.classList.add('-translate-x-full');
            offcanvasOverlay.classList.add('hidden');
            document.body.style.overflow = '';
        }

        // Fungsi untuk modal petunjuk
        function openBorrowGuideModal() {
            const modal = document.getElementById('borrowGuideModal');
            modal.classList.remove('hidden');
            document.body.style.overflow = 'hidden';
        }

        function closeBorrowGuideModal() {
            const modal = document.getElementById('borrowGuideModal');
            modal.classList.add('hidden');
            document.body.style.overflow = '';
        }

        document.getElementById('closeGuideBtn').addEventListener('click', closeBorrowGuideModal);

        // PWA Installation
        let deferredPrompt;
        const pwaInstallBtn = document.getElementById('pwa-install-btn');

        window.addEventListener('beforeinstallprompt', (e) => {
            // Prevent the mini-infobar from appearing on mobile
            e.preventDefault();
            // Stash the event so it can be triggered later
            deferredPrompt = e;
            // Show the install button
            if (pwaInstallBtn) {
                pwaInstallBtn.style.display = 'flex';
            }
        });

        if (pwaInstallBtn) {
            pwaInstallBtn.addEventListener('click', async () => {
                if (!deferredPrompt) {
                    return;
                }
                // Show the install prompt
                deferredPrompt.prompt();
                // Wait for the user to respond to the prompt
                const { outcome } = await deferredPrompt.userChoice;
                // We've used the prompt, and can't use it again, throw it away
                deferredPrompt = null;
                // Hide the install button
                pwaInstallBtn.style.display = 'none';
            });
        }

        // Service Worker Registration
        if ("serviceWorker" in navigator) {
            window.addEventListener('load', function() {
                navigator.serviceWorker.register("/sw.js").then(
                    (registration) => {
                        console.log("Service worker registration succeeded:", registration);
                    },
                    (error) => {
                        console.error(`Service worker registration failed: ${error}`);
                    },
                );
            });
        } else {
            console.error("Service workers are not supported.");
        }
    </script>
</body>

</html>