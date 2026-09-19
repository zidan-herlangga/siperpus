<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#006739">
    <meta name="description" content="Aplikasi Peminjaman Buku Online Perpustakaan SMK Karya Guna 2 Bekasi">

    <!-- Facebook Meta Tags -->
    <meta property="og:url" content="">
    <meta property="og:type" content="website">
    <meta property="og:title" content="ELibrary SMK Karya Guna 2">
    <meta property="og:description" content="Aplikasi Peminjaman Buku Online Perpustakaan SMK Karya Guna 2 Bekasi">
    <meta property="og:image" content="">

    <!-- Twitter Meta Tags -->
    <meta name="twitter:card" content="summary_large_image">
    <meta property="twitter:domain" content="">
    <meta property="twitter:url" content="">
    <meta name="twitter:title" content="ELibrary SMK Karya Guna 2">
    <meta name="twitter:description" content="Aplikasi Peminjaman Buku Online Perpustakaan SMK Karya Guna 2 Bekasi">
    <meta name="twitter:image" content="">

    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">

    <!-- PWA  -->
    <link rel="apple-touch-icon" href="{{ asset('assets/image/favicon.png') }}">
    <link rel="manifest" href="{{ asset('/manifest.json') }}">

    {{-- ENV --}}
    <title>@yield('title', config('app.name'))</title>

    <link rel="icon" href="https://smkkg2.sch.id/wp-content/uploads/2026/02/logo-kg-transparan-2-150x150.png" type="image/png">
    <link rel="stylesheet" href="{{ asset('assets/css/layout.css') }}">
    @yield('styles')

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
        media="print" onload="this.media='all'">

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Tailwind CSS CDN & JS -->
    {{-- <script src="https://cdn.tailwindcss.com/3.4.17" defer></script> --}}

    <!-- DITAMBAHKAN LIVEWIRE 1: Styles Livewire v3 -->
    <livewire:styles />
</head>

<body class="bg-[#f4f6f1] min-h-screen flex flex-col overflow-x-hidden">
    {{-- HEADER --}}
    <header id="mainHeader"
        class="gradient-bg text-white shadow-sm sticky top-0 z-50 transition-all duration-300">
        <div class="container mx-auto px-4 py-2.5 md:py-3 flex justify-between items-center gap-3">

            {{-- LOGO --}}
            <a href="{{ route('homepage') }}" wire:navigate.prefetch="false"
                class="flex items-center gap-3 min-w-0 smooth-transition hover:opacity-90">
                <span
                    class="bg-white rounded-xl p-1 shrink-0 inline-flex items-center justify-center shadow-sm">
                    <img src="{{ asset('assets/image/SMK-Karya-Guna-2-Bekasi.svg') }}" alt="Logo SMK Karya Guna 2 Bekasi"
                        class="h-8 w-auto md:h-10 object-contain">
                </span>
                <span class="flex flex-col leading-tight min-w-0">
                    <span class="font-bold text-base md:text-lg tracking-tight whitespace-nowrap">ELibrary</span>
                    <span class="text-white/70 text-[10px] md:text-xs font-medium -mt-0.5 whitespace-nowrap">Perpustakaan Digital</span>
                </span>
            </a>

            {{-- NAVIGASI DESKTOP (lg ke atas) --}}
            <nav class="hidden lg:flex items-center gap-1.5 font-medium">
                @auth('student')
                    <a href="{{ route('student.dashboard') }}" wire:navigate.prefetch="false"
                        class="flex items-center gap-2 px-4 py-2 rounded-lg smooth-transition {{ request()->routeIs('student.dashboard') ? 'bg-white/10 text-white font-semibold' : 'text-white/75 hover:text-white hover:bg-white/5' }}">
                        <i class="fas fa-tachometer-alt text-sm"></i>
                        Dashboard
                    </a>

                    <a href="{{ route('books.index') }}" wire:navigate.prefetch="false"
                        class="flex items-center gap-2 px-4 py-2 rounded-lg smooth-transition {{ request()->routeIs('books.*') ? 'bg-white/10 text-white font-semibold' : 'text-white/75 hover:text-white hover:bg-white/5' }}">
                        <i class="fas fa-book text-sm"></i>
                        Katalog
                    </a>

                    <a href="{{ route('student.history') }}" wire:navigate.prefetch="false"
                        class="flex items-center gap-2 px-4 py-2 rounded-lg smooth-transition {{ request()->routeIs('student.history') ? 'bg-white/10 text-white font-semibold' : 'text-white/75 hover:text-white hover:bg-white/5' }}">
                        <i class="fas fa-history text-sm"></i>
                        Riwayat
                    </a>

                    <button onclick="openBorrowGuideModal()"
                        class="flex items-center gap-2 px-4 py-2 rounded-lg smooth-transition text-white/75 hover:text-white hover:bg-white/5">
                        <i class="fas fa-circle-info text-sm"></i>
                        Petunjuk
                    </button>

                    <form action="{{ route('student.logout') }}" method="POST" class="inline ml-2">
                        @csrf
                        <button type="submit"
                            class="flex items-center gap-2 bg-red-600 text-white px-4 py-2 rounded-lg shadow-sm smooth-transition hover:bg-red-500">
                            <i class="fas fa-right-from-bracket text-sm"></i>
                            Keluar
                        </button>
                    </form>
                @else
                    <a href="{{ route('books.index') }}" wire:navigate.prefetch="false"
                        class="flex items-center gap-2 px-4 py-2 rounded-lg smooth-transition {{ request()->routeIs('books.*') ? 'bg-white/10 text-white font-semibold' : 'text-white/75 hover:text-white hover:bg-white/5' }}">
                        <i class="fas fa-book text-sm"></i>
                        Katalog
                    </a>

                    <button onclick="openBorrowGuideModal()"
                        class="flex items-center gap-2 px-4 py-2 rounded-lg smooth-transition text-white/75 hover:text-white hover:bg-white/5">
                        <i class="fas fa-circle-info text-sm"></i>
                        Petunjuk
                    </button>

                    <!-- PWA Install Button -->
                    <button id="pwa-install-btn"
                        class="flex items-center gap-2 border border-white/30 text-white px-4 py-2 rounded-lg smooth-transition hover:bg-white/10"
                        style="display: none;">
                        <i class="fas fa-download text-sm"></i>
                        Install App
                    </button>

                    {{-- Dropdown Akun --}}
                    <div class="relative ml-2">
                        <button id="dropdownToggle"
                            class="flex items-center gap-2 bg-cta text-[#16271d] font-semibold px-4 py-2 rounded-lg shadow-sm smooth-transition hover:bg-cta-dark focus:outline-none focus:ring-2 focus:ring-white/40">
                            <i class="fas fa-user text-sm"></i>
                            <span>Akun</span>
                            <i id="dropdownArrow" class="fas fa-chevron-down ml-1 text-xs smooth-transition"></i>
                        </button>

                        <div id="dropdownMenu"
                            class="absolute right-0 mt-2 w-48 bg-white rounded-xl shadow-xl opacity-0 invisible smooth-transition transform origin-top-right z-50 border border-gray-100">
                            <a href="{{ route('student.login.form') }}" wire:navigate.prefetch="false"
                                class="flex items-center gap-3 px-4 py-3 text-gray-700 smooth-transition hover:bg-gray-50 rounded-t-xl">
                                <i class="fas fa-right-to-bracket text-emerald-600"></i>
                                <span class="text-sm font-medium">Masuk</span>
                            </a>
                            <div class="border-t border-gray-100"></div>
                            <a href="{{ route('student.register.form') }}" wire:navigate.prefetch="false"
                                class="flex items-center gap-3 px-4 py-3 text-gray-700 smooth-transition hover:bg-gray-50 rounded-b-xl">
                                <i class="fas fa-user-plus text-emerald-600"></i>
                                <span class="text-sm font-medium">Daftar</span>
                            </a>
                        </div>
                    </div>
                @endauth
            </nav>

            {{-- AKSI MOBILE & TABLET (< lg) --}}
            <div class="flex items-center gap-2 lg:hidden">
                @auth('student')
                    <a href="{{ route('student.dashboard') }}" wire:navigate.prefetch="false"
                        class="w-10 h-10 flex items-center justify-center rounded-xl bg-white/10 text-white smooth-transition hover:bg-white/20">
                        <i class="fas fa-user text-sm"></i>
                    </a>
                @else
                    <a href="{{ route('student.login.form') }}" wire:navigate.prefetch="false"
                        class="flex items-center gap-2 bg-cta text-[#16271d] font-semibold px-4 py-2 rounded-xl shadow-sm smooth-transition hover:bg-cta-dark text-sm">
                        <i class="fas fa-right-to-bracket text-sm"></i>
                        
                    </a>
                @endauth
                <button id="menuToggle"
                    class="w-10 h-10 flex items-center justify-center rounded-xl bg-white/10 text-white hover:bg-white/20 smooth-transition text-lg"
                    aria-label="Buka menu">
                    <i class="fas fa-bars"></i>
                </button>
            </div>
        </div>
    </header>

    {{-- OFFCANVAS MENU (mobile & tablet) --}}
    <div id="offcanvasOverlay" aria-hidden="true"
        class="fixed inset-0 bg-black/50 backdrop-blur-sm z-[60] opacity-0 invisible smooth-transition"></div>
    <aside id="offcanvas" aria-label="Menu navigasi"
        class="fixed top-0 right-0 h-full w-[84vw] max-w-sm bg-white shadow-2xl z-[70] transform translate-x-full smooth-transition flex flex-col">
        <div class="flex items-center justify-between gap-3 p-4 border-b border-gray-100">
            <span class="flex items-center gap-2.5 min-w-0">
                <span class="w-9 h-9 rounded-lg overflow-hidden bg-white border border-gray-100 flex items-center justify-center shrink-0">
                    <img src="{{ asset('assets/image/SMK-Karya-Guna-2-Bekasi.svg') }}" alt="Logo SMK Karya Guna 2 Bekasi"
                        class="h-7 w-auto object-contain">
                </span>
                <span class="flex flex-col leading-tight min-w-0">
                    <span class="font-bold text-sm tracking-tight truncate">ELibrary</span>
                    <span class="text-[10px] text-gray-500 font-medium truncate">SMK Karya Guna 2 Bekasi</span>
                </span>
            </span>
            <button id="closeOffcanvas"
                class="w-9 h-9 flex items-center justify-center rounded-lg text-gray-400 hover:text-gray-700 hover:bg-gray-100 smooth-transition shrink-0"
                aria-label="Tutup menu">
                <i class="fas fa-xmark text-lg"></i>
            </button>
        </div>

        <nav class="flex-1 overflow-y-auto p-3 space-y-1">
            <a href="{{ route('homepage') }}" wire:navigate.prefetch="false" onclick="closeMenu()"
                class="offcanvas-link {{ request()->routeIs('homepage') ? 'bg-[#eaf3ed] text-[#006739] font-semibold' : '' }}">
                <i class="fas fa-house w-5 text-center"></i> Beranda
            </a>
            <a href="{{ route('books.index') }}" wire:navigate.prefetch="false" onclick="closeMenu()"
                class="offcanvas-link {{ request()->routeIs('books.*') ? 'bg-[#eaf3ed] text-[#006739] font-semibold' : '' }}">
                <i class="fas fa-book-open w-5 text-center"></i> Katalog Buku
            </a>
            <button onclick="openBorrowGuideModal()"
                class="offcanvas-link w-full text-left">
                <i class="fas fa-circle-info w-5 text-center"></i> Petunjuk Peminjaman
            </button>

            <div class="my-2 border-t border-gray-100"></div>

            @auth('student')
                <a href="{{ route('student.dashboard') }}" wire:navigate.prefetch="false" onclick="closeMenu()"
                    class="offcanvas-link {{ request()->routeIs('student.dashboard') ? 'bg-[#eaf3ed] text-[#006739] font-semibold' : '' }}">
                    <i class="fas fa-tachometer-alt w-5 text-center"></i> Dashboard
                </a>
                <a href="{{ route('student.history') }}" wire:navigate.prefetch="false" onclick="closeMenu()"
                    class="offcanvas-link {{ request()->routeIs('student.history') ? 'bg-[#eaf3ed] text-[#006739] font-semibold' : '' }}">
                    <i class="fas fa-history w-5 text-center"></i> Riwayat Peminjaman
                </a>
                <form action="{{ route('student.logout') }}" method="POST" class="mt-1">
                    @csrf
                    <button type="submit"
                        class="flex items-center gap-2 w-full bg-red-600 hover:bg-red-500 text-white px-4 py-2.5 rounded-xl font-semibold text-sm smooth-transition">
                        <i class="fas fa-right-from-bracket w-5 text-center"></i> Keluar
                    </button>
                </form>
            @else
                <a href="{{ route('student.login.form') }}" wire:navigate.prefetch="false" onclick="closeMenu()"
                    class="flex items-center justify-center gap-2 bg-cta hover:bg-cta-dark text-[#16271d] font-semibold px-4 py-2.5 rounded-xl text-sm smooth-transition">
                    <i class="fas fa-right-to-bracket w-5 text-center"></i> Masuk
                </a>
                <a href="{{ route('student.register.form') }}" wire:navigate.prefetch="false" onclick="closeMenu()"
                    class="flex items-center justify-center gap-2 mt-2 border border-gray-200 hover:border-[#006739] hover:text-[#006739] text-gray-700 font-semibold px-4 py-2.5 rounded-xl text-sm smooth-transition">
                    <i class="fas fa-user-plus w-5 text-center"></i> Daftar Akun
                </a>
            @endauth
        </nav>

        <div class="p-4 pt-3 border-t border-gray-100">
            <p class="text-center text-[11px] text-gray-400">&copy; {{ date('Y') }} SMK Karya Guna 2 Bekasi</p>
        </div>
    </aside>

    {{-- MAIN CONTENT --}}
    <main class="flex-grow">
        @yield('content')
    </main>

    {{-- BOTTOM NAVIGATION MOBILE --}}
    <nav id="bottomNav" class="fixed bottom-0 left-0 right-0 z-40 md:hidden">
        <div
            class="bg-white/95 backdrop-blur-xl border-t border-gray-200 shadow-[0_-4px_20px_rgba(0,0,0,0.05)] px-2 py-2 pb-[env(safe-area-inset-bottom)]">
            <div class="flex items-center justify-around">

                @auth('student')
                    {{-- Menu Dashboard --}}
                    <a href="{{ route('student.dashboard') }}" wire:navigate.prefetch="false"
                        class="flex flex-col items-center justify-center py-2 px-3 rounded-xl transition-all group {{ request()->routeIs('student.dashboard') ? 'text-emerald-600' : 'text-gray-400 active-nav-item' }}">
                        <div
                            class="w-7 h-7 flex items-center justify-center rounded-lg bg-gray-100 group-[.text-emerald-600]:bg-emerald-100 transition-colors mb-1">
                            <i class="fas fa-user text-xs"></i>
                        </div>
                        <span class="text-[10px] font-semibold">Dashboard</span>
                    </a>

                    <a href="{{ route('student.history') }}" wire:navigate.prefetch="false"
                        class="flex flex-col items-center justify-center py-2 px-3 rounded-xl transition-all group {{ request()->routeIs('student.history') ? 'text-emerald-600' : 'text-gray-400 active-nav-item' }}">
                        <div
                            class="w-7 h-7 flex items-center justify-center rounded-lg bg-gray-100 group-[.text-emerald-600]:bg-emerald-100 transition-colors mb-1">
                            <i class="fas fa-history text-xs"></i>
                        </div>
                        <span class="text-[10px] font-semibold">Riwayat</span>
                    </a>
                @else
                    {{-- Menu Beranda --}}
                    <a href="{{ route('homepage') }}" wire:navigate.prefetch="false"
                        class="flex flex-col items-center justify-center py-2 px-3 rounded-xl transition-all text-gray-400 active-nav-item group {{ request()->routeIs('homepage') ? 'text-emerald-600' : '' }}">
                        <div
                            class="w-7 h-7 flex items-center justify-center rounded-lg bg-gray-100 group-[.text-emerald-600]:bg-emerald-100 transition-colors mb-1">
                            <i class="fas fa-house text-xs"></i>
                        </div>
                        <span class="text-[10px] font-semibold">Beranda</span>
                    </a>
                @endauth

                {{-- Menu Katalog --}}
                <a href="{{ route('books.index') }}" wire:navigate.prefetch="false"
                    class="flex flex-col items-center justify-center py-2 px-3 rounded-xl transition-all text-gray-400 active-nav-item group {{ request()->routeIs('books.*') ? 'text-emerald-600' : '' }}">
                    <div
                        class="w-7 h-7 flex items-center justify-center rounded-lg bg-gray-100 group-[.text-emerald-600]:bg-emerald-100 transition-colors mb-1">
                        <i class="fas fa-book-open text-xs"></i>
                    </div>
                    <span class="text-[10px] font-semibold">Katalog</span>
                </a>

                {{-- Menu Petunjuk --}}
                <button onclick="openBorrowGuideModal()"
                    class="flex flex-col items-center justify-center py-2 px-3 rounded-xl transition-all text-gray-400 active-nav-item group">
                    <div
                        class="w-7 h-7 flex items-center justify-center rounded-lg bg-gray-100 group-hover:bg-emerald-100 transition-colors mb-1">
                        <i class="fas fa-circle-info text-xs"></i>
                    </div>
                    <span class="text-[10px] font-semibold">Info</span>
                </button>

                {{-- Menu Akun (Login/Daftar/Dashboard) --}}
                @auth('student')
                    {{-- Logout Form --}}
                    <form action="{{ route('student.logout') }}" method="POST">
                        @csrf
                        <button type="submit"
                            class="flex flex-col items-center justify-center py-2 px-3 rounded-xl transition-all text-red-500 group w-full">
                            <div
                                class="w-7 h-7 flex items-center justify-center rounded-lg bg-red-100 group-hover:bg-red-200 transition-colors mb-1">
                                <i class="fas fa-right-from-bracket text-xs"></i>
                            </div>
                            <span class="text-[10px] font-semibold">Keluar</span>
                        </button>
                    </form>
                @else
                    <a href="{{ route('student.login.form') }}" wire:navigate.prefetch="false"
                        class="flex flex-col items-center justify-center py-2 px-3 rounded-xl transition-all text-gray-400 active-nav-item group">
                        <div
                            class="w-7 h-7 flex items-center justify-center rounded-lg bg-gray-100 group-hover:bg-emerald-100 transition-colors mb-1">
                            <i class="fas fa-right-to-bracket text-xs"></i>
                        </div>
                        <span class="text-[10px] font-semibold">Masuk</span>
                    </a>
                @endauth

            </div>
        </div>
    </nav>

    {{-- FOOTER --}}
    <footer class="footer text-white mt-14 md:mt-16 mb-20 md:mb-0">
        <div class="container mx-auto px-4 pt-12 pb-8 md:pb-10 grid grid-cols-1 md:grid-cols-3 gap-10 md:gap-12">

            {{-- Brand --}}
            <div>
                <a href="{{ route('homepage') }}" wire:navigate.prefetch="false"
                    class="flex items-center gap-3 mb-5 smooth-transition hover:opacity-90">
                    <span class="bg-white rounded-xl p-1.5 inline-flex items-center justify-center shadow-sm">
                        <img src="{{ asset('assets/image/SMK-Karya-Guna-2-Bekasi.svg') }}" alt="Logo SMK Karya Guna 2 Bekasi"
                            class="h-8 w-auto md:h-10 object-contain">
                    </span>
                    <span class="flex flex-col leading-tight">
                        <span class="font-bold text-base md:text-lg tracking-tight">ELibrary</span>
                        <span class="text-white/60 text-[10px] md:text-xs font-medium -mt-0.5">Perpustakaan Digital</span>
                    </span>
                </a>
                <p class="text-sm text-white/70 leading-relaxed">Katalog digital SMK Karya Guna 2 Bekasi. Temukan, pinjam, dan baca – semua dalam satu alur yang jujur.</p>

                <div class="flex items-center gap-2.5 mt-6">
                    <a href="https://www.facebook.com/smkkaryaguna2bekasi/" target="_blank" rel="noopener"
                        class="w-9 h-9 flex items-center justify-center rounded-full bg-white/10 text-white/70 hover:bg-white/20 hover:text-white smooth-transition"
                        aria-label="Facebook SMK Karya Guna 2 Bekasi">
                        <i class="fa-brands fa-facebook-f text-sm"></i>
                    </a>
                    <a href="https://www.instagram.com/smkkaryaguna2bekasi/" target="_blank" rel="noopener"
                        class="w-9 h-9 flex items-center justify-center rounded-full bg-white/10 text-white/70 hover:bg-white/20 hover:text-white smooth-transition"
                        aria-label="Instagram SMK Karya Guna 2 Bekasi">
                        <i class="fa-brands fa-instagram text-sm"></i>
                    </a>
                    <a href="https://wa.me/+6285772224344" target="_blank" rel="noopener"
                        class="w-9 h-9 flex items-center justify-center rounded-full bg-white/10 text-white/70 hover:bg-white/20 hover:text-white smooth-transition"
                        aria-label="WhatsApp SMK Karya Guna 2 Bekasi">
                        <i class="fa-brands fa-whatsapp text-sm"></i>
                    </a>
                </div>
            </div>

            {{-- Navigasi --}}
            <div>
                <h4 class="text-white font-semibold text-sm tracking-wide mb-4">Navigasi</h4>
                <ul class="space-y-2.5 text-sm text-white/70">
                    <li><a href="{{ route('homepage') }}" wire:navigate.prefetch="false" class="smooth-transition hover:text-white">Beranda</a></li>
                    <li><a href="{{ route('books.index') }}" wire:navigate.prefetch="false" class="smooth-transition hover:text-white">Katalog Buku</a></li>
                    <li><button onclick="openBorrowGuideModal()" class="smooth-transition hover:text-white text-left">Petunjuk Peminjaman</button></li>
                    @auth('student')
                        <li><a href="{{ route('student.dashboard') }}" wire:navigate.prefetch="false" class="smooth-transition hover:text-white">Dashboard</a></li>
                        <li><a href="{{ route('student.history') }}" wire:navigate.prefetch="false" class="smooth-transition hover:text-white">Riwayat Peminjaman</a></li>
                    @else
                        <li><a href="{{ route('student.login.form') }}" wire:navigate.prefetch="false" class="smooth-transition hover:text-white">Masuk</a></li>
                        <li><a href="{{ route('student.register.form') }}" wire:navigate.prefetch="false" class="smooth-transition hover:text-white">Daftar Akun</a></li>
                    @endauth
                </ul>
            </div>

            {{-- Kontak --}}
            <div>
                <h4 class="text-white font-semibold text-sm tracking-wide mb-4">Kontak</h4>
                <ul class="space-y-3.5 text-sm text-white/70">
                    <li class="flex gap-3">
                        <i class="fas fa-location-dot text-white/40 mt-0.5"></i>
                        <span>Jl. Karang Satria No.503, RT.010/RW.016, Duren Jaya, Kec. Bekasi Timur, Kota Bekasi, Jawa Barat 17111</span>
                    </li>
                    <li class="flex gap-3 items-start">
                        <i class="fas fa-phone text-white/40 mt-0.5"></i>
                        <a href="tel:+6285772224344" class="smooth-transition hover:text-white">+62 8577 2224 344</a>
                    </li>
                    <li class="flex gap-3 items-start">
                        <i class="fas fa-envelope text-white/40 mt-0.5"></i>
                        <a href="mailto:info@smkkg2.sch.id" class="smooth-transition hover:text-white">info@smkkg2.sch.id</a>
                    </li>
                    <li class="flex gap-3 items-start">
                        <i class="fas fa-globe text-white/40 mt-0.5"></i>
                        <a href="https://smkkg2.sch.id" target="_blank" rel="noopener" class="smooth-transition hover:text-white">smkkg2.sch.id</a>
                    </li>
                </ul>
            </div>
        </div>

        <div class="border-t border-white/10">
            <div class="container mx-auto px-4 py-5 flex flex-col md:flex-row items-center justify-between gap-2 text-sm text-white/60">
                <p>&copy; {{ date('Y') }} SMK Karya Guna 2 Bekasi. Hak Cipta Dilindungi.</p>
                <span class="flex items-center gap-2"><i class="fas fa-book-open text-xs"></i> Perpustakaan Digital ELibrary</span>
            </div>
        </div>
    </footer>

    {{-- MODAL PETUNJUK --}}
    <div id="borrowGuideModal"
        class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center p-4 z-[60] opacity-0 invisible smooth-transition">
        <div class="bg-white rounded-2xl shadow-2xl p-6 max-w-md w-full transform scale-95 smooth-transition border border-gray-100"
            id="modalContent">
            <div class="flex justify-between items-center mb-5">
                <h2 class="text-lg font-bold text-green flex items-center gap-2">
                    <i class="fas fa-book-open-reader text-emerald-500"></i>
                    Petunjuk Peminjaman
                </h2>
                <button onclick="closeBorrowGuideModal()"
                    class="text-gray-300 hover:text-red-500 smooth-transition text-lg w-8 h-8 flex items-center justify-center rounded-lg hover:bg-red-50">
                    <i class="fas fa-xmark"></i>
                </button>
            </div>
            <ol class="list-decimal list-inside space-y-3 text-gray-600 text-sm leading-relaxed">
                <li><strong class="text-gray-800">Daftar/Masuk:</strong> Pastikan Anda sudah memiliki akun dan dalam
                    keadaan login.</li>
                <li><strong class="text-gray-800">Cari Buku:</strong> Jelajahi katalog untuk menemukan buku yang ingin
                    Anda pinjam.</li>
                <li><strong class="text-gray-800">Klik Pinjam:</strong> Pada halaman detail buku, klik tombol "Pinjam
                    Buku".</li>
                <li><strong class="text-gray-800">Konfirmasi:</strong> Setujui syarat dan ketentuan pada modal
                    konfirmasi.</li>
                <li><strong class="text-gray-800">Selesai:</strong> Buku berhasil dipinjam! Cek Dashboard untuk
                    detailnya.</li>
            </ol>
            <div class="mt-6 flex justify-end">
                <button onclick="closeBorrowGuideModal()"
                    class="bg-emerald-600 hover:bg-emerald-700 text-white px-5 py-2.5 rounded-lg smooth-transition btn-hover-effect font-medium text-sm shadow-md">
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
                if (mainContent) mainContent.style.paddingBottom = '5rem';
            } else {
                bottomNav.classList.remove('show-nav');
                if (mainContent) mainContent.style.paddingBottom = '0';
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
                    if (isOpen) {
                        closeDropdown();
                    } else {
                        dropdownMenu.classList.remove('invisible', 'opacity-0');
                        dropdownMenu.classList.add('opacity-100', 'visible');
                        dropdownArrow.style.transform = 'rotate(180deg)';
                    }
                });

                document.addEventListener('click', (e) => {
                    if (!dropdownToggle.contains(e.target) && !dropdownMenu.contains(e.target)) {
                        closeDropdown();
                    }
                });
            }

            function closeDropdown() {
                if (dropdownMenu) {
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
                if (!offcanvas) return;
                offcanvas.classList.remove('translate-x-full');
                offcanvasOverlay.classList.remove('opacity-0', 'invisible');
                offcanvasOverlay.classList.add('opacity-100', 'visible');
                document.body.style.overflow = 'hidden';
            }

            function closeMenu() {
                if (!offcanvas) return;
                offcanvas.classList.add('translate-x-full');
                offcanvasOverlay.classList.add('opacity-0', 'invisible');
                offcanvasOverlay.classList.remove('opacity-100', 'visible');
                document.body.style.overflow = '';
            }

            document.addEventListener('keydown', (e) => {
                if (e.key === 'Escape' && offcanvas && !offcanvas.classList.contains('translate-x-full')) {
                    closeMenu();
                }
            });

            // Expose ke global agar bisa dipanggil dari tombol di file Blade manapun
            window.closeMenu = closeMenu;
            window.openMenu = openMenu;

            // === MODAL LOGIC (Layout Utama) ===
            const borrowGuideModal = document.getElementById('borrowGuideModal');
            const modalContent = document.getElementById('modalContent');

            window.openBorrowGuideModal = function() {
                if (!borrowGuideModal) return;
                borrowGuideModal.classList.remove('opacity-0', 'invisible');
                borrowGuideModal.classList.add('opacity-100', 'visible');
                if (modalContent) modalContent.style.transform = 'scale(1)';
                document.body.style.overflow = 'hidden';
                closeMenu();
            }

            window.closeBorrowGuideModal = function() {
                if (!borrowGuideModal) return;
                borrowGuideModal.classList.add('opacity-0', 'invisible');
                borrowGuideModal.classList.remove('opacity-100', 'visible');
                if (modalContent) modalContent.style.transform = 'scale(0.95)';
                document.body.style.overflow = '';
            }

            if (borrowGuideModal) {
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
                    const {
                        outcome
                    } = await deferredPrompt.userChoice;
                    deferredPrompt = null;
                    pwaInstallBtn.style.display = 'none';
                });
            }
        });

        // === SERVICE WORKER ===
        if ("serviceWorker" in navigator) {
            // Saat service worker baru mengambil alih (versi cache berubah),
            // muat ulang sekali agar halaman langsung bebas dari cache lama.
            navigator.serviceWorker.addEventListener('controllerchange', () => {
                window.location.reload();
            });

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
