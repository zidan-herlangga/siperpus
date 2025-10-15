@extends('layouts.app')

@section('title', 'Peminjaman Buku Online - SMK Karya Guna 2 Bekasi')

@section('content')
    {{-- Hero Section --}}
    <section class="relative text-white py-24 md:py-32 overflow-hidden">
        {{-- <picture>
            <source srcset="{{ asset('assets/image/bg01-scaled.webp') }}" type="image/webp">
            <img src="{{ asset('assets/image/bg01-scaled.jpg') }}" alt="Perpustakaan SMK Karya Guna 2 Bekasi"
                class="absolute inset-0 w-full h-full object-cover" width="1920" height="1080" loading="eager"
                fetchpriority="high">
        </picture> --}}
        <div class="absolute inset-0 bg-gradient-to-br from-emerald-900/90 via-green-800/70 to-teal-600/70 backdrop-blur-sm">
        </div>

        <div class="relative container mx-auto px-6 text-center animate-fadeIn">
            <h1 class="text-4xl md:text-5xl font-extrabold mb-6 leading-tight drop-shadow-lg">
                Selamat Datang di <br><span class="text-yellow-300">Perpustakaan <span id="simpleUsage"></span></span>
            </h1>
            <p class="text-lg md:text-xl mb-10 text-green-100 max-w-2xl mx-auto leading-relaxed">
                Temukan ribuan koleksi buku dan jelajahi dunia literasi secara digital — cepat, mudah, dan menyenangkan.
            </p>
            <div class="flex flex-col sm:flex-row justify-center gap-4">
                <a href="{{ route('books.index') }}"
                    class="bg-yellow-400 text-green-900 px-8 py-3 rounded-full font-semibold hover:bg-yellow-300 transition-all transform hover:scale-105 shadow-lg focus:ring-4 focus:ring-yellow-300 flex items-center justify-center text-lg">
                    <i class="fas fa-search mr-2"></i> Jelajahi Koleksi
                </a>
                <a href="{{ route('student.register.form') }}"
                    class="border-2 border-white text-white px-8 py-3 rounded-full font-semibold hover:bg-white hover:text-green-800 transition-all transform hover:scale-105 shadow-lg focus:ring-4 focus:ring-green-300 flex items-center justify-center text-lg">
                    <i class="fas fa-user-plus mr-2"></i> Daftar Sekarang
                </a>
            </div>
        </div>
    </section>

    {{-- Features Section --}}
    <section class="py-20 bg-white">
        <div class="container mx-auto px-6">
            <h2 class="text-3xl font-bold text-center text-gray-800 mb-12">Mengapa Memilih Kami?</h2>
            <div class="grid md:grid-cols-3 gap-10">
                @php
                    $features = [
                        [
                            'icon' => 'fa-book-reader',
                            'title' => 'Koleksi Lengkap',
                            'desc' => 'Ribuan buku dari berbagai kategori dan penerbit terkemuka.',
                        ],
                        [
                            'icon' => 'fa-globe',
                            'title' => 'Akses Digital',
                            'desc' => 'Sistem online yang bisa diakses kapan pun dan di mana pun.',
                        ],
                        [
                            'icon' => 'fa-bell',
                            'title' => 'Notifikasi Otomatis',
                            'desc' => 'Dapatkan pengingat pengembalian buku dan informasi terbaru.',
                        ],
                    ];
                @endphp
                @foreach ($features as $feature)
                    <div
                        class="text-center p-6 hover:bg-green-50 rounded-2xl transition duration-300 shadow-sm hover:shadow-lg">
                        <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-5">
                            <i class="fas {{ $feature['icon'] }} text-green-600 text-3xl"></i>
                        </div>
                        <h3 class="text-xl font-semibold mb-3">{{ $feature['title'] }}</h3>
                        <p class="text-gray-600">{{ $feature['desc'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Statistics Section --}}
    <section class="py-20 bg-gradient-to-br from-emerald-50 via-green-50 to-teal-50">
        <div class="container mx-auto px-6 text-center">
            <h2 class="text-3xl font-bold text-gray-800 mb-12">Perpustakaan dalam Angka</h2>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-8">
                @php
                    $stats = [
                        [
                            'icon' => 'fa-book-open',
                            'value' => $bookCount ?? 0,
                            'label' => 'Judul Buku',
                            'color' => 'text-green-500',
                        ],
                        [
                            'icon' => 'fa-users',
                            'value' => $studentCount ?? 0,
                            'label' => 'Siswa Terdaftar',
                            'color' => 'text-teal-500',
                        ],
                        [
                            'icon' => 'fa-exchange-alt',
                            'value' => $borrowCount ?? 0,
                            'label' => 'Peminjaman Bulan Ini',
                            'color' => 'text-emerald-500',
                        ],
                        [
                            'icon' => 'fa-tags',
                            'value' => $categoryCount ?? 0,
                            'label' => 'Kategori Buku',
                            'color' => 'text-lime-500',
                        ],
                    ];
                @endphp
                @foreach ($stats as $stat)
                    <div class="bg-white p-8 rounded-2xl shadow-md hover:shadow-lg transition duration-300">
                        <i class="fas {{ $stat['icon'] }} text-3xl {{ $stat['color'] }} mb-3"></i>
                        <div class="text-4xl font-extrabold text-gray-800">{{ $stat['value'] }}</div>
                        <p class="text-gray-600 mt-2">{{ $stat['label'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Call-to-Action Section --}}
    <section class="py-20 bg-white">
        <div class="container mx-auto px-6 text-center">
            <h2 class="text-3xl font-bold text-gray-800 mb-12">Mulai Perjalanan Literasi Anda</h2>
            <div class="grid md:grid-cols-2 gap-8 max-w-4xl mx-auto">
                <a href="{{ route('books.index') }}"
                    class="bg-gradient-to-br from-green-600 to-teal-700 text-white p-10 rounded-2xl shadow-lg hover:shadow-2xl transition duration-300 transform hover:-translate-y-1 flex flex-col items-center justify-center">
                    <i class="fas fa-search text-4xl mb-4"></i>
                    <h3 class="text-2xl font-bold mb-2">Lihat Katalog Buku</h3>
                    <p class="text-green-100 max-w-sm">Jelajahi dan temukan buku favorit Anda dari ribuan koleksi yang
                        tersedia.</p>
                </a>
                <a href="{{ route('student.register.form') }}"
                    class="bg-gradient-to-br from-emerald-600 to-green-700 text-white p-10 rounded-2xl shadow-lg hover:shadow-2xl transition duration-300 transform hover:-translate-y-1 flex flex-col items-center justify-center">
                    <i class="fas fa-user-graduate text-4xl mb-4"></i>
                    <h3 class="text-2xl font-bold mb-2">Daftar sebagai Siswa</h3>
                    <p class="text-emerald-100 max-w-sm">Buat akun untuk mulai meminjam buku dan mengakses semua fitur
                        perpustakaan.</p>
                </a>
            </div>
        </div>
    </section>

    <script src="https://unpkg.com/typeit@8.8.7/dist/index.umd.js"></script>
    <script>
        new TypeIt("#simpleUsage", {
            strings: "SMK Karya Guna 2 Bekasi",
            speed: 640,
            waitUntilVisible: true,
        }).go();
    </script>
@endsection
