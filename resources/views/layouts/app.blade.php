<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Perpustakaan Sekolah')</title>

    <link rel="icon" href="{{ asset('assets/image/favicon.png') }}" type="image/png">

    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>

    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
</head>

<body class="bg-gray-50 min-h-screen flex flex-col">
    <header class="gradient-bg text-white shadow-lg sticky top-0 z-50">
        <div class="container mx-auto px-4 py-4">
            <div class="flex justify-between items-center">
                <a href="/">
                    <img src="{{ asset('assets/image/logo-light-smkg2.png') }}" alt="Logo SMK Karya Guna 2 Bekasi">
                </a>

                <nav class="hidden md:flex items-center space-x-6">
                    @auth('student')
                        {{-- Tampil jika siswa SUDAH LOGIN --}}
                        <a href="{{ route('student.dashboard') }}"
                            class="nav-link text-white font-medium py-2 hover:text-yellow-500 transition duration-300 {{ request()->routeIs('student.dashboard') ? 'text-yellow-500' : '' }}">
                            Dashboard
                        </a>
                        {{-- Link ini selalu tampil --}}
                        <a href="{{ route('books.index') }}"
                            class="nav-link text-white font-medium py-2 hover:text-yellow-500 transition duration-300 {{ request()->routeIs('books.index') ? 'text-yellow-500' : '' }}">
                            Katalog Buku
                        </a>
                        <form action="{{ route('student.logout') }}" method="POST">
                            @csrf
                            <button type="submit"
                                class="bg-yellow-500 text-white px-4 py-2 rounded-lg font-semibold hover:bg-white hover:text-yellow-500 transition duration-300">
                                Logout
                            </button>
                        </form>
                    @else
                        {{-- Tampil jika siswa BELUM LOGIN --}}
                        <a href="{{ route('student.register.form') }}"
                            class="nav-link text-white font-medium py-2 hover:text-yellow-500 transition duration-300 {{ request()->routeIs('student.register.form') ? 'text-yellow-500' : '' }}">
                            Daftar Siswa
                        </a>
                        <a href="{{ route('student.login.form') }}"
                            class="nav-link text-white font-medium py-2 hover:text-yellow-500 transition duration-300 {{ request()->routeIs('student.login.form') ? 'text-yellow-500' : '' }}">
                            Login Siswa
                        </a>
                    @endauth
                </nav>

                <button id="mobileMenuButton" class="md:hidden text-2xl">
                    <i class="fas fa-bars"></i>
                </button>
            </div>

            <div id="mobileMenu"
                class="hidden md:hidden mt-4 space-y-3 border-t border-gray-300 border-opacity-50 pt-4">
                @auth('student')
                    <div class="text-white pl-4 py-2">
                        Halo, <span class="font-bold">{{ Auth::guard('student')->user()->name }}</span>
                    </div>

                    {{-- Tampil jika siswa SUDAH LOGIN --}}
                    <a href="{{ route('student.dashboard') }}"
                        class="block text-white font-medium py-2 border-l-4 border-transparent pl-4 hover:border-white {{ request()->routeIs('student.dashboard') ? 'bg-green-700 !border-white' : '' }}">Dashboard</a>

                    <a href="{{ route('books.index') }}"
                        class="block text-white font-medium py-2 border-l-4 border-transparent pl-4 hover:border-white {{ request()->routeIs('books.*') ? 'bg-green-700 !border-white' : '' }}">Katalog
                        Buku</a>
                    <form action="{{ route('student.logout') }}" method="POST" class="px-4">
                        @csrf
                        <button type="submit"
                            class="w-full bg-yellow-500 text-white py-2 rounded-lg font-semibold text-center mt-2">
                            Logout
                        </button>
                    </form>
                @else
                    {{-- Tampil jika siswa BELUM LOGIN --}}
                    <a href="{{ route('student.register.form') }}"
                        class="block text-white font-medium py-2 border-l-4 border-transparent pl-4 hover:border-white {{ request()->routeIs('student.register.form') ? 'bg-green-700 !border-white' : '' }}">Daftar
                        Siswa</a>
                    <a href="{{ route('student.login.form') }}"
                        class="block text-white font-medium py-2 border-l-4 border-transparent pl-4 hover:border-white {{ request()->routeIs('student.login.form') ? 'bg-green-700 !border-white' : '' }}">Login
                        Siswa</a>
                @endauth
            </div>
        </div>
    </header>

    <main class="flex-grow">
        @yield('content')
    </main>

    <footer class="gradient-bg text-white py-12">
        <div class="container mx-auto px-4">
            <div class="grid md:grid-cols-4 gap-8">
                {{-- Kolom 1: Logo dan Deskripsi --}}
                <div>
                    <img src="{{ asset('assets/image/logo-light-smkg2.png') }}" alt="Logo SMK Karya Guna 2 Bekasi"
                        class="mb-4">
                    <p class="text-gray-300">Membangun generasi cerdas melalui literasi dan pengetahuan.</p>
                </div>

                {{-- Kolom 2: Tautan Cepat --}}
                <div>
                    <h4 class="text-lg font-semibold mb-4 border-b-2 border-yellow-500 pb-2 inline-block">Tautan Cepat
                    </h4>
                    <ul class="space-y-2">
                        <li><a href="{{ route('books.index') }}"
                                class="text-gray-300 hover:text-white transition">Katalog Buku</a></li>
                        @guest('student')
                            <li><a href="{{ route('student.register.form') }}"
                                    class="text-gray-300 hover:text-white transition">Pendaftaran Siswa</a></li>
                            <li><a href="{{ route('student.login.form') }}"
                                    class="text-gray-300 hover:text-white transition">Login Siswa</a></li>
                        @endguest
                        @auth('student')
                            <li><a href="{{ route('student.dashboard') }}"
                                    class="text-gray-300 hover:text-white transition">Dashboard Saya</a></li>
                        @endauth
                    </ul>
                </div>

                {{-- Kolom 3: Jam Operasional dengan Status Dinamis --}}
                <div>
                    <h4 class="text-lg font-semibold mb-4 border-b-2 border-yellow-500 pb-2 inline-block">Jam
                        Operasional</h4>
                    <div class="space-y-2 text-gray-300">
                        <p>Senin - Jumat: 07:00 - 16:00</p>
                        <p>Sabtu - Miggu: Tutup</p>
                    </div>
                    {{-- Indikator Buka/Tutup Dinamis --}}
                    @php
                        $now = now()->setTimezone('Asia/Jakarta');
                        $dayOfWeek = $now->dayOfWeek; // Senin = 1, Minggu = 0
                        $time = $now->format('H:i');
                        $isOpen = false;
                        if ($dayOfWeek >= 1 && $dayOfWeek <= 5 && $time >= '07:00' && $time < '16:00') {
                            $isOpen = true; // Hari kerja
                        } elseif ($dayOfWeek == 6 && $time >= '08:00' && $time < '14:00') {
                            $isOpen = true; // Sabtu
                        }
                    @endphp
                    <div class="mt-4">
                        @if ($isOpen)
                            <span class="bg-green-500 text-white text-xs font-bold px-3 py-1 rounded-full">
                                <i class="fas fa-check-circle mr-1"></i> Sedang Buka
                            </span>
                        @else
                            <span class="bg-red-500 text-white text-xs font-bold px-3 py-1 rounded-full">
                                <i class="fas fa-times-circle mr-1"></i> Sedang Tutup
                            </span>
                        @endif
                    </div>
                </div>

                {{-- Kolom 4: Kontak --}}
                <div>
                    <h4 class="text-lg font-semibold mb-4 border-b-2 border-yellow-500 pb-2 inline-block">Kontak</h4>
                    <div class="space-y-2 text-gray-300">
                        <p><i class="fas fa-map-marker-alt fa-fw mr-2"></i>
                            <a href="https://maps.app.goo.gl/ruoLZxPsTb8hB4Ep7">Jl. Karang Satria No.503, RT.010/RW.016,
                                Duren Jaya, Kec. Bekasi Tim., Kota Bks, Jawa Barat 17111</a>
                        </p>
                        <p><i class="fas fa-phone fa-fw mr-2"></i>
                            <a href="tel:(021) 8800523">(021) 8800523</a>
                        </p>
                        <p><i class="fas fa-envelope fa-fw mr-2"></i>
                            <a href="mailto:info@smkkaryaguna2bekasi.sch.id">info@smkkaryaguna2bekasi.sch.id</a>
                        </p>
                    </div>
                </div>
            </div>

            {{-- Copyright --}}
            <div class="border-t border-t-white mt-8 pt-8 text-center text-white">
                <p>&copy; {{ date('Y') }} Perpustakaan SMK Karya Guna 2 Bekasi. Hak Cipta Dilindungi.</p>
            </div>
        </div>
    </footer>

    @if (session('email_verified'))
        <div id="verificationModal"
            class="fixed inset-0 bg-black bg-opacity-60 flex items-center justify-center p-4 z-50">
            <div
                class="bg-white rounded-2xl shadow-2xl p-8 md:p-12 text-center max-w-md mx-auto transform transition-all duration-300 scale-95 opacity-0 modal-content">
                <div class="w-20 h-20 bg-green-100 rounded-full mx-auto flex items-center justify-center mb-6">
                    <svg class="w-12 h-12 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
                <h2 class="text-2xl md:text-3xl font-bold text-gray-800 mb-4">Verifikasi Berhasil!</h2>
                <p class="text-gray-600 mb-8">
                    Terima kasih! Akun Anda telah berhasil diverifikasi. Sekarang Anda dapat mengakses semua layanan
                    perpustakaan.
                </p>
                <button id="closeModalBtn"
                    class="w-full bg-green-600 text-white font-semibold py-3 px-6 rounded-lg hover:bg-green-700 focus:outline-none focus:ring-4 focus:ring-green-300 transition duration-300 ease-in-out">
                    Mulai Menjelajah Buku
                </button>
            </div>
        </div>
    @endif

    <script src="{{ asset('assets/js/main.js') }}"></script>
</body>

</html>
