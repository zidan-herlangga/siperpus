<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#065f46">
    <meta name="description" content="Aplikasi Peminjaman Buku Online Perpustakaan SMK Karya Guna 2 Bekasi">
    
    <!-- Facebook Meta Tags -->
    <meta property="og:url" content="https://testing.zidanherlangga.my.id/">
    <meta property="og:type" content="website">
    <meta property="og:title" content="ELibrary SMK Karya Guna 2">
    <meta property="og:description" content="Aplikasi Peminjaman Buku Online Perpustakaan SMK Karya Guna 2 Bekasi">
    <meta property="og:image" content="https://ogcdn.net/ad84de55-98c4-4cbb-9d40-76165cdc1c86/v1/https%3A%2F%2Ftesting.zidanherlangga.my.id%2Fassets%2Fimage%2FSMK-Karya-Guna-2-Bekasi.svg/Perpustakaan%20Digital%20Aktif/ELibrary%20SMK%0AKarya%20Guna%202/Aplikasi%20Peminjaman%20Buku%20Online%20Perpustakaan%20SMK%20Karya%20Guna%202%20Bekasi/Jelajahi%20Koleksi/https%3A%2F%2Ftesting.zidanherlangga.my.id%2Fassets%2Fimage%2Flogo-smk.png/25%2B%20Judul%20Buku%20Tersedia/og.png">
    
    <!-- Twitter Meta Tags -->
    <meta name="twitter:card" content="summary_large_image">
    <meta property="twitter:domain" content="testing.zidanherlangga.my.id">
    <meta property="twitter:url" content="https://testing.zidanherlangga.my.id/">
    <meta name="twitter:title" content="ELibrary SMK Karya Guna 2">
    <meta name="twitter:description" content="Aplikasi Peminjaman Buku Online Perpustakaan SMK Karya Guna 2 Bekasi">
    <meta name="twitter:image" content="https://ogcdn.net/ad84de55-98c4-4cbb-9d40-76165cdc1c86/v1/https%3A%2F%2Ftesting.zidanherlangga.my.id%2Fassets%2Fimage%2FSMK-Karya-Guna-2-Bekasi.svg/Perpustakaan%20Digital%20Aktif/ELibrary%20SMK%0AKarya%20Guna%202/Aplikasi%20Peminjaman%20Buku%20Online%20Perpustakaan%20SMK%20Karya%20Guna%202%20Bekasi/Jelajahi%20Koleksi/https%3A%2F%2Ftesting.zidanherlangga.my.id%2Fassets%2Fimage%2Flogo-smk.png/25%2B%20Judul%20Buku%20Tersedia/og.png">

    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">

    <!-- PWA  -->
    <link rel="apple-touch-icon" href="{{ asset('assets/image/favicon.png') }}">
    <link rel="manifest" href="{{ asset('/manifest.json') }}">

    {{-- ENV --}}
    <title>@yield('title', config('app.name'))</title>

    <link rel="icon" href="{{ asset('assets/image/favicon.png') }}" type="image/png">
    <link rel="stylesheet" href="{{ asset('assets/css/layout.css') }}">
    @yield('styles')
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" media="print" onload="this.media='all'">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Tailwind CSS CDN & JS -->
    <script src="https://cdn.tailwindcss.com/3.4.17" defer></script>

    <!-- DITAMBAHKAN LIVEWIRE 1: Styles Livewire v3 -->
    <livewire:styles />
</head>

<body class="bg-gray-50 min-h-screen flex flex-col overflow-x-hidden">
    {{-- HEADER --}}
    <header id="mainHeader" class="hidden lg:flex md:flex gradient-bg text-white shadow-lg sticky top-0 z-50 transition-all duration-300">
        <div class="container mx-auto px-4 py-4 flex justify-between items-center">

            {{-- LOGO --}}
            <a href="{{ route('homepage') }}" wire:navigate.prefetch="false" class="flex items-center space-x-2 smooth-transition hover:scale-105">
                <img src="{{ asset('assets/image/SMK-Karya-Guna-2-Bekasi.svg') }}" alt="Logo SMK Karya Guna 2 Bekasi"
                    class="w-auto h-10 md:h-12">
            </a>

            {{-- NAVIGASI DESKTOP --}}
            <nav class="hidden md:flex items-center gap-2 font-medium">
                @auth('student')
                    <a href="{{ route('student.dashboard') }}" wire:navigate.prefetch="false"
                        class="flex items-center gap-2 px-4 py-2 rounded-lg smooth-transition {{ request()->routeIs('student.dashboard') ? 'bg-white/20 text-yellow-300' : 'text-white/90 hover:bg-white/10' }}" style="color: #006638">
                        <i class="fas fa-tachometer-alt text-sm"></i>
                        Dashboard
                    </a>

                    <a href="{{ route('books.index') }}" wire:navigate.prefetch="false"
                        class="flex items-center gap-2 px-4 py-2 rounded-lg smooth-transition {{ request()->routeIs('books.*') ? 'bg-white/20 text-yellow-300' : 'text-white/90 hover:bg-white/10' }}" style="color: #006638">
                        <i class="fas fa-book text-sm" ></i>
                        Katalog
                    </a>

                    <a href="{{ route('student.history') }}" wire:navigate.prefetch="false"
                        class="flex items-center gap-2 px-4 py-2 rounded-lg smooth-transition {{ request()->routeIs('student.history') ? 'bg-white/20 text-yellow-300' : 'text-white/90 hover:bg-white/10' }}" style="color: #006638">
                        <i class="fas fa-history text-sm" ></i>
                        Riwayat
                    </a>

                    <button onclick="openBorrowGuideModal()"
                        class="flex items-center gap-2 px-4 py-2 rounded-lg smooth-transition text-white/90 hover:bg-white/10" style="color: #006638">
                        <i class="fas fa-circle-info text-sm"></i>
                        Petunjuk
                    </button>

                    <form action="{{ route('student.logout') }}" method="POST" class="inline ml-2">
                        @csrf
                        <button type="submit"
                            class="flex items-center gap-2 bg-red-500/80 backdrop-blur-sm text-white px-4 py-2 rounded-lg shadow smooth-transition hover:bg-red-600 btn-hover-effect">
                            <i class="fas fa-right-from-bracket text-sm"></i>
                            Keluar
                        </button>
                    </form>
                @else
                    <a href="{{ route('books.index') }}" wire:navigate.prefetch="false" 
                        class="flex items-center gap-2 px-4 py-2 rounded-lg smooth-transition {{ request()->routeIs('books.*') ? 'bg-white/20 text-yellow-300' : 'text-white/90 hover:bg-white/10' }}" style="color: #006638">
                        <i class="fas fa-book text-sm"></i>
                        Katalog
                    </a>

                    <button onclick="openBorrowGuideModal()"
                        class="flex items-center gap-2 px-4 py-2 rounded-lg smooth-transition text-white/90 hover:bg-white/10" style="color: #006638">
                        <i class="fas fa-circle-info text-sm"></i>
                        Petunjuk
                    </button>

                    <!-- PWA Install Button -->
                    <button id="pwa-install-btn"
                        class="flex items-center gap-2 backdrop-blur-sm border border-white/20 text-white px-4 py-2 rounded-lg shadow smooth-transition btn-hover-effect" style="display: none; background: #006638 !important;">
                        <i class="fas fa-download text-sm"></i>
                        Install App
                    </button>

                    {{-- Dropdown Akun --}}
                    <div class="relative ml-2">
                        <button id="dropdownToggle"
                            class="flex items-center gap-2 bg-yellow-400 text-gray-900 font-semibold px-4 py-2 rounded-lg shadow-md smooth-transition hover:bg-yellow-300 focus:outline-none focus:ring-2 focus:ring-yellow-200 btn-hover-effect">
                            <i class="fas fa-user text-sm"></i>
                            <span>Akun</span>
                            <i id="dropdownArrow" class="fas fa-chevron-down ml-1 text-xs smooth-transition"></i>
                        </button>

                        <div id="dropdownMenu"
                            class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-xl opacity-0 invisible smooth-transition transform origin-top-right z-50 border border-gray-100">
                            <a href="{{ route('student.login.form') }}" wire:navigate.prefetch="false"
                                class="flex items-center gap-3 px-4 py-3 text-gray-700 smooth-transition hover:bg-gray-50 rounded-t-xl">
                                <i class="fas fa-right-to-bracket text-green-600"></i>
                                <span class="text-sm font-medium">Login</span>
                            </a>
                            <div class="border-t border-gray-100"></div>
                            <a href="{{ route('student.register.form') }}" wire:navigate.prefetch="false"
                                class="flex items-center gap-3 px-4 py-3 text-gray-700 smooth-transition hover:bg-gray-50 rounded-b-xl">
                                <i class="fas fa-user-plus text-green-600"></i>
                                <span class="text-sm font-medium">Daftar</span>
                            </a>
                        </div>
                    </div>
                @endauth
            </nav>

            {{-- TOMBOL MENU MOBILE --}}
            <button id="menuToggle" class="md:hidden text-white text-xl focus:outline-none smooth-transition hover:scale-110 w-10 h-10 flex items-center justify-center rounded-lg bg-white/10">
                <i class="fas fa-bars"></i>
            </button>
        </div>
    </header>

    {{-- OFFCANVAS MODERN --}}
    {{-- <div id="offcanvasOverlay" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-30 opacity-0 invisible smooth-transition cursor-pointer"></div>
    
    <div id="offcanvas" class="offcanvas fixed top-0 left-0 w-72 h-full z-40 transform -translate-x-full smooth-transition shadow-2xl">
        <div class="flex justify-between items-center p-5 border-b border-green-100">
            <h2 class="text-lg font-bold text-green">Menu Utama</h2>
            <button id="closeOffcanvas" class="text-gray-400 hover:text-red-500 smooth-transition text-lg w-8 h-8 flex items-center justify-center rounded-lg hover:bg-red-50">
                <i class="fas fa-xmark"></i>
            </button>
        </div>
        <div class="flex flex-col flex-grow p-4 space-y-1">
            @auth('student')
                <a href="{{ route('student.dashboard') }}" wire:navigate.prefetch="false" class="offcanvas-link">
                    <i class="fas fa-tachometer-alt w-5 text-center"></i>
                    <span>Dashboard</span>
                </a>
                <a href="{{ route('books.index') }}" wire:navigate.prefetch="false" class="offcanvas-link">
                    <i class="fas fa-book w-5 text-center"></i>
                    <span>Katalog Buku</span>
                </a>
                <button onclick="openBorrowGuideModal(); closeMenu();" class="offcanvas-link text-left w-full">
                    <i class="fas fa-circle-info w-5 text-center"></i>
                    <span>Petunjuk</span>
                </button>
                
                <div class="flex-grow"></div>
                
                <div class="border-t border-gray-100 pt-4 mt-4">
                    <form action="{{ route('student.logout') }}" method="POST" class="w-full">
                        @csrf
                        <button type="submit" class="flex items-center gap-3 w-full text-left text-red-500 font-semibold py-3 px-3 smooth-transition hover:bg-red-50 rounded-lg">
                            <i class="fas fa-right-from-bracket w-5 text-center"></i>
                            <span>Keluar</span>
                        </button>
                    </form>
                </div>
            @else
                <a href="{{ route('homepage') }}" wire:navigate.prefetch="false" class="offcanvas-link">
                    <i class="fas fa-house w-5 text-center"></i>
                    <span>Beranda</span>
                </a>
                <a href="{{ route('books.index') }}" wire:navigate.prefetch="false" class="offcanvas-link">
                    <i class="fas fa-book w-5 text-center"></i>
                    <span>Katalog Buku</span>
                </a>
                <button onclick="openBorrowGuideModal(); closeMenu();" class="offcanvas-link text-left w-full">
                    <i class="fas fa-circle-info w-5 text-center"></i>
                    <span>Petunjuk</span>
                </button>
                
                <div class="flex-grow"></div>
                
                <div class="border-t border-gray-100 pt-4 mt-4 space-y-1">
                    <a href="{{ route('student.register.form') }}" class="offcanvas-link font-medium text-green-700" wire:navigate.prefetch="false">
                        <i class="fas fa-user-plus w-5 text-center"></i>
                        <span>Daftar Akun</span>
                    </a>
                    <a href="{{ route('student.login.form') }}" class="offcanvas-link font-medium" wire:navigate.prefetch="false">
                        <i class="fas fa-right-to-bracket w-5 text-center"></i>
                        <span>Login</span>
                    </a>
                </div>
            @endauth
        </div>
    </div> --}}

    <!-- DITAMBAHKAN LIVEWIRE 2: Wadah Notifikasi Realtime -->
    <div id="livewire-notification-container" class="fixed top-20 right-4 z-[90] space-y-3 w-80 pointer-events-none">
        {{-- Disinilah popup notifikasi akan muncul nanti --}}
    </div>

    {{-- MAIN CONTENT --}}
    <main class="flex-grow">
        @yield('content')
    </main>

        {{-- BOTTOM NAVIGATION MOBILE --}}
    <nav id="bottomNav" class="fixed bottom-0 left-0 right-0 z-40 md:hidden">
        <div class="bg-white/95 backdrop-blur-xl border-t border-gray-200 shadow-[0_-4px_20px_rgba(0,0,0,0.05)] px-2 py-2 pb-[env(safe-area-inset-bottom)]">
            <div class="flex items-center justify-around">
                
                @auth('student')
                    {{-- <a href="{{ route('homepage') }}" wire:navigate.prefetch="false" class="flex flex-col items-center justify-center py-2 px-3 rounded-xl transition-all text-gray-400 active-nav-item group {{ request()->routeIs('homepage') ? 'text-emerald-600' : '' }}">
                        <div class="w-7 h-7 flex items-center justify-center rounded-lg bg-gray-100 group-[.text-emerald-600]:bg-emerald-100 transition-colors mb-1">
                            <i class="fas fa-house text-xs"></i>
                        </div>
                        <span class="text-[10px] font-semibold">Beranda</span>
                    </a> --}}

                    {{-- Menu Dashboard --}}
                    <a href="{{ route('student.dashboard') }}" wire:navigate.prefetch="false" class="flex flex-col items-center justify-center py-2 px-3 rounded-xl transition-all group {{ request()->routeIs('student.dashboard') ? 'text-emerald-600' : 'text-gray-400 active-nav-item' }}">
                        <div class="w-7 h-7 flex items-center justify-center rounded-lg bg-gray-100 group-[.text-emerald-600]:bg-emerald-100 transition-colors mb-1">
                            <i class="fas fa-user text-xs"></i>
                        </div>
                        <span class="text-[10px] font-semibold">Dashboard</span>
                    </a>

                    <a href="{{ route('student.history') }}" wire:navigate.prefetch="false" class="flex flex-col items-center justify-center py-2 px-3 rounded-xl transition-all group {{ request()->routeIs('student.history') ? 'text-emerald-600' : 'text-gray-400 active-nav-item' }}">
                        <div class="w-7 h-7 flex items-center justify-center rounded-lg bg-gray-100 group-[.text-emerald-600]:bg-emerald-100 transition-colors mb-1">
                            <i class="fas fa-history text-xs"></i>
                        </div>
                        <span class="text-[10px] font-semibold">Riwayat</span>
                    </a>  
                @else
                    {{-- Menu Beranda --}}
                    <a href="{{ route('homepage') }}" wire:navigate.prefetch="false" class="flex flex-col items-center justify-center py-2 px-3 rounded-xl transition-all text-gray-400 active-nav-item group {{ request()->routeIs('homepage') ? 'text-emerald-600' : '' }}">
                        <div class="w-7 h-7 flex items-center justify-center rounded-lg bg-gray-100 group-[.text-emerald-600]:bg-emerald-100 transition-colors mb-1">
                            <i class="fas fa-house text-xs"></i>
                        </div>
                        <span class="text-[10px] font-semibold">Beranda</span>
                    </a>
                @endauth

                {{-- Menu Katalog --}}
                <a href="{{ route('books.index') }}" wire:navigate.prefetch="false" class="flex flex-col items-center justify-center py-2 px-3 rounded-xl transition-all text-gray-400 active-nav-item group {{ request()->routeIs('books.*') ? 'text-emerald-600' : '' }}">
                    <div class="w-7 h-7 flex items-center justify-center rounded-lg bg-gray-100 group-[.text-emerald-600]:bg-emerald-100 transition-colors mb-1">
                        <i class="fas fa-book-open text-xs"></i>
                    </div>
                    <span class="text-[10px] font-semibold">Katalog</span>
                </a>

                {{-- Menu Petunjuk --}}
                <button onclick="openBorrowGuideModal()" class="flex flex-col items-center justify-center py-2 px-3 rounded-xl transition-all text-gray-400 active-nav-item group">
                    <div class="w-7 h-7 flex items-center justify-center rounded-lg bg-gray-100 group-hover:bg-blue-100 transition-colors mb-1">
                        <i class="fas fa-circle-info text-xs"></i>
                    </div>
                    <span class="text-[10px] font-semibold">Info</span>
                </button>

                {{-- Menu Akun (Login/Daftar/Dashboard) --}}
                @auth('student')
                    {{-- <a href="{{ route('student.edit') }}" wire:navigate.prefetch="false" class="flex flex-col items-center justify-center py-2 px-3 rounded-xl transition-all text-gray-400 active-nav-item group">
                        <div class="w-7 h-7 flex items-center justify-center rounded-lg bg-gray-100 group-hover:bg-purple-100 transition-colors mb-1">
                            <i class="fas fa-user text-xs"></i>
                        </div>
                        <span class="text-[10px] font-semibold">Profil</span>
                    </a> --}}
                    
                    {{-- Logout Form --}}
                    <form action="{{ route('student.logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="flex flex-col items-center justify-center py-2 px-3 rounded-xl transition-all text-red-500 group w-full">
                            <div class="w-7 h-7 flex items-center justify-center rounded-lg bg-red-100 group-hover:bg-red-200 transition-colors mb-1">
                                <i class="fas fa-right-from-bracket text-xs"></i>
                            </div>
                            <span class="text-[10px] font-semibold">Keluar</span>
                        </button>
                    </form>
                @else
                    <a href="{{ route('student.login.form') }}" wire:navigate.prefetch="false" class="flex flex-col items-center justify-center py-2 px-3 rounded-xl transition-all text-gray-400 active-nav-item group">
                        <div class="w-7 h-7 flex items-center justify-center rounded-lg bg-gray-100 group-hover:bg-amber-100 transition-colors mb-1">
                            <i class="fas fa-right-to-bracket text-xs"></i>
                        </div>
                        <span class="text-[10px] font-semibold">Masuk</span>
                    </a>
                @endauth

            </div>
        </div>
    </nav>

    {{-- FOOTER --}}
    <footer class="hidden lg:flex md:flex footer text-white py-8 mt-12 md:mt-12 mb-20 md:mb-0">
        <div class="container mx-auto px-4 text-center">
            <div class="flex items-center justify-center gap-2 mb-3 opacity-70">
                <i class="fas fa-book-open text-sm"></i>
                <span class="text-sm font-medium">Perpustakaan Digital</span>
            </div>
            <p class="text-sm text-white/60">© {{ date('Y') }} SMK Karya Guna 2 Bekasi. Hak Cipta Dilindungi.</p>
        </div>
    </footer>

    {{-- MODAL PETUNJUK --}}
    <div id="borrowGuideModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center p-4 z-[60] opacity-0 invisible smooth-transition">
        <div class="bg-white rounded-2xl shadow-2xl p-6 max-w-md w-full transform scale-95 smooth-transition border border-gray-100" id="modalContent">
            <div class="flex justify-between items-center mb-5">
                <h2 class="text-lg font-bold text-green flex items-center gap-2">
                    <i class="fas fa-book-open-reader text-emerald-500"></i>
                    Petunjuk Peminjaman
                </h2>
                <button onclick="closeBorrowGuideModal()" class="text-gray-300 hover:text-red-500 smooth-transition text-lg w-8 h-8 flex items-center justify-center rounded-lg hover:bg-red-50">
                    <i class="fas fa-xmark"></i>
                </button>
            </div>
            <ol class="list-decimal list-inside space-y-3 text-gray-600 text-sm leading-relaxed">
                <li><strong class="text-gray-800">Daftar/Login:</strong> Pastikan Anda sudah memiliki akun dan dalam keadaan login.</li>
                <li><strong class="text-gray-800">Cari Buku:</strong> Jelajahi katalog untuk menemukan buku yang ingin Anda pinjam.</li>
                <li><strong class="text-gray-800">Klik Pinjam:</strong> Pada halaman detail buku, klik tombol "Pinjam Buku".</li>
                <li><strong class="text-gray-800">Konfirmasi:</strong> Setujui syarat dan ketentuan pada modal konfirmasi.</li>
                <li><strong class="text-gray-800">Selesai:</strong> Buku berhasil dipinjam! Cek Dashboard untuk detailnya.</li>
            </ol>
            <div class="mt-6 flex justify-end">
                <button onclick="closeBorrowGuideModal()" class="bg-green text-white px-5 py-2.5 rounded-lg smooth-transition hover:bg-dark-green btn-hover-effect font-medium text-sm shadow-md">
                    Mengerti
                </button>
            </div>
        </div>
    </div>

    @yield('scripts')

    <script>
        // === BOTTOM NAVIGATION MOBILE LOGIC ===
        function handleBottomNavVisibility() {
            const bottomNav = document.getElementById('bottomNav');
            const mainContent = document.querySelector('main');
            
            if (!bottomNav) return;

            if (window.innerWidth < 768) {
                bottomNav.classList.add('show-nav');
                if(mainContent) mainContent.style.paddingBottom = '5rem';
            } else {
                bottomNav.classList.remove('show-nav');
                if(mainContent) mainContent.style.paddingBottom = '0';
            }
        }

        window.addEventListener('DOMContentLoaded', handleBottomNavVisibility);
        window.addEventListener('resize', handleBottomNavVisibility);

        // === HEADER SCROLL EFFECT ===
        window.addEventListener('scroll', () => {
            const mainHeader = document.getElementById('mainHeader');
            if (!mainHeader) return;
            if (window.scrollY > 10) {
                mainHeader.classList.add('header-scrolled');
            } else {
                mainHeader.classList.remove('header-scrolled');
            }
        });

        // === DROPDOWN DESKTOP LOGIC ===
        document.addEventListener('DOMContentLoaded', function() {
            const dropdownToggle = document.getElementById('dropdownToggle');
            const dropdownMenu = document.getElementById('dropdownMenu');
            const dropdownArrow = document.getElementById('dropdownArrow');

            if (dropdownToggle) {
                dropdownToggle.addEventListener('click', (e) => {
                    e.stopPropagation();
                    const isOpen = !dropdownMenu.classList.contains('invisible');
                    if (isOpen) { closeDropdown(); } 
                    else {
                        dropdownMenu.classList.remove('invisible', 'opacity-0');
                        dropdownMenu.classList.add('opacity-100', 'visible');
                        dropdownArrow.style.transform = 'rotate(180deg)';
                    }
                });

                document.addEventListener('click', (e) => {
                    if (!dropdownToggle.contains(e.target) && !dropdownMenu.contains(e.target)) { closeDropdown(); }
                });
            }

            function closeDropdown() {
                if(dropdownMenu) {
                    dropdownMenu.classList.add('invisible', 'opacity-0');
                    dropdownMenu.classList.remove('opacity-100', 'visible');
                    dropdownArrow.style.transform = 'rotate(0deg)';
                }
            }

            // === OFFCANVAS LOGIC ===
            const menuToggle = document.getElementById('menuToggle');
            const offcanvas = document.getElementById('offcanvas');
            const offcanvasOverlay = document.getElementById('offcanvasOverlay');
            const closeOffcanvas = document.getElementById('closeOffcanvas');

            if (menuToggle) menuToggle.addEventListener('click', openMenu);
            if (closeOffcanvas) closeOffcanvas.addEventListener('click', closeMenu);
            if (offcanvasOverlay) offcanvasOverlay.addEventListener('click', closeMenu);

            function openMenu() {
                if(!offcanvas) return;
                offcanvas.classList.remove('-translate-x-full');
                offcanvasOverlay.classList.remove('opacity-0', 'invisible');
                offcanvasOverlay.classList.add('opacity-100', 'visible');
                document.body.style.overflow = 'hidden';
            }

            function closeMenu() {
                if(!offcanvas) return;
                offcanvas.classList.add('-translate-x-full');
                offcanvasOverlay.classList.add('opacity-0', 'invisible');
                offcanvasOverlay.classList.remove('opacity-100', 'visible');
                document.body.style.overflow = '';
            }

            // Expose ke global agar bisa dipanggil dari tombol di file Blade manapun
            window.closeMenu = closeMenu;
            window.openMenu = openMenu;

            // === MODAL LOGIC (Layout Utama) ===
            const borrowGuideModal = document.getElementById('borrowGuideModal');
            const modalContent = document.getElementById('modalContent');

            window.openBorrowGuideModal = function() {
                if(!borrowGuideModal) return;
                borrowGuideModal.classList.remove('opacity-0', 'invisible');
                borrowGuideModal.classList.add('opacity-100', 'visible');
                if(modalContent) modalContent.style.transform = 'scale(1)';
                document.body.style.overflow = 'hidden';
                closeMenu(); 
            }

            window.closeBorrowGuideModal = function() {
                if(!borrowGuideModal) return;
                borrowGuideModal.classList.add('opacity-0', 'invisible');
                borrowGuideModal.classList.remove('opacity-100', 'visible');
                if(modalContent) modalContent.style.transform = 'scale(0.95)';
                document.body.style.overflow = '';
            }

            if(borrowGuideModal) {
                borrowGuideModal.addEventListener('click', (e) => {
                    if (e.target === borrowGuideModal) window.closeBorrowGuideModal();
                });
            }

            // === PWA INSTALLATION ===
            let deferredPrompt;
            const pwaInstallBtn = document.getElementById('pwa-install-btn');

            window.addEventListener('beforeinstallprompt', (e) => {
                e.preventDefault();
                deferredPrompt = e;
                if (pwaInstallBtn) pwaInstallBtn.style.display = 'flex';
            });

            if (pwaInstallBtn) {
                pwaInstallBtn.addEventListener('click', async () => {
                    if (!deferredPrompt) return;
                    deferredPrompt.prompt();
                    const { outcome } = await deferredPrompt.userChoice;
                    deferredPrompt = null;
                    pwaInstallBtn.style.display = 'none';
                });
            }
        });

        // === SERVICE WORKER ===
        if ("serviceWorker" in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register("/sw.js")
                    .then(reg => console.log("SW registered:", reg.scope))
                    .catch(err => console.error("SW failed:", err));
            });
        }
    </script>

    <!-- DITAMBAHKAN LIVEWIRE 3: Script Inti Livewire v3 -->
    <livewire:scripts />
</body>
</html>