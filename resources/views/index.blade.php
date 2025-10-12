@extends('layouts.app')

@section('title', 'Peminjaman Buku Online - SMK Karya Guna 2 Bekasi')

@section('content')
    {{-- Hero Section --}}
    <section class="relative text-white py-24 md:py-32 overflow-hidden">
        {{-- Background Image --}}
        <picture>
            <source srcset="{{ asset('assets/image/bg01-scaled.webp') }}" type="image/webp">
            <img src="{{ asset('assets/image/bg01-scaled.jpg') }}" {{-- Fallback JPG --}}
                 alt="Perpustakaan SMK Karya Guna 2 Bekasi"
                 class="absolute inset-0 w-full h-full object-cover"
                 width="1920" height="1080"
                 loading="eager"
                 fetchpriority="high">
        </picture>
        <div class="absolute inset-0 bg-gradient-to-r from-green-800/80 to-green-600/60"></div>

        {{-- Content --}}
        <div class="relative container mx-auto px-4 text-center">
            <h1 class="text-4xl md:text-5xl font-bold mb-6 text-shadow-dark">
                Selamat Datang di Perpustakaan <br>SMK Karya Guna 2 Bekasi
            </h1>
            <p class="text-lg md:text-xl mb-10 text-green-100 max-w-3xl mx-auto leading-relaxed">
                Temukan ribuan koleksi buku untuk mendukung proses belajar dan memperluas wawasan Anda.
            </p>
            <div class="flex flex-col sm:flex-row justify-center space-y-4 sm:space-y-0 sm:space-x-4">
                <a href="{{ route('books.index') }}" class="bg-white text-green-700 px-8 py-3 rounded-full font-semibold hover:bg-gray-100 transition duration-300 transform hover:scale-105 shadow-lg flex items-center justify-center text-lg">
                    <i class="fas fa-search mr-2"></i>Jelajahi Koleksi
                </a>
                <a href="{{ route('student.register.form') }}" class="border-2 border-white text-white px-8 py-3 rounded-full font-semibold hover:bg-white hover:text-green-700 transition duration-300 transform hover:scale-105 flex items-center justify-center text-lg">
                    <i class="fas fa-user-plus mr-2"></i>Daftar Sekarang
                </a>
            </div>
        </div>
    </section>

    {{-- Features Section --}}
    <section class="py-20 bg-white">
        <div class="container mx-auto px-4">
            <h2 class="text-3xl font-bold text-center text-gray-800 mb-12">Mengapa Memilih Perpustakaan Kami?</h2>
            <div class="grid md:grid-cols-3 gap-10">
                <div class="text-center p-6">
                    <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-5">
                        <i class="fas fa-book-reader text-green-600 text-3xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold mb-3">Koleksi Lengkap</h3>
                    <p class="text-gray-600">Ribuan buku dari berbagai kategori dan penerbit terkemuka siap menanti Anda.</p>
                </div>
                <div class="text-center p-6">
                    <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-5">
                        <i class="fas fa-globe text-green-600 text-3xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold mb-3">Akses Digital</h3>
                    <p class="text-gray-600">Sistem online yang dapat diakses kapan saja dan di mana saja melalui perangkat Anda.</p>
                </div>
                <div class="text-center p-6">
                    <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-5">
                        <i class="fas fa-bell text-green-600 text-3xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold mb-3">Notifikasi Otomatis</h3>
                    <p class="text-gray-600">Dapatkan pengingat pengembalian buku dan informasi terbaru langsung ke email Anda.</p>
                </div>
            </div>
        </div>
    </section>

    {{-- Statistics Section --}}
    <section class="py-20 bg-gray-50">
        <div class="container mx-auto px-4">
            <h2 class="text-3xl font-bold text-center text-gray-800 mb-12">Perpustakaan dalam Angka</h2>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-8 text-center">
                <div class="bg-white p-8 rounded-xl shadow-md">
                    <i class="fas fa-book-open text-3xl text-green-500 mb-3"></i>
                    <div class="text-4xl font-bold text-gray-800">{{ $bookCount ?? 0 }}</div>
                    <p class="text-gray-600 font-medium mt-1">Judul Buku</p>
                </div>
                <div class="bg-white p-8 rounded-xl shadow-md">
                    <i class="fas fa-users text-3xl text-teal-500 mb-3"></i>
                    <div class="text-4xl font-bold text-gray-800">{{ $studentCount ?? 0 }}</div>
                    <p class="text-gray-600 font-medium mt-1">Siswa Terdaftar</p>
                </div>
                <div class="bg-white p-8 rounded-xl shadow-md">
                    <i class="fas fa-exchange-alt text-3xl text-emerald-500 mb-3"></i>
                    <div class="text-4xl font-bold text-gray-800">{{ $borrowCount ?? 0 }}</div>
                    <p class="text-gray-600 font-medium mt-1">Peminjaman Bulan Ini</p>
                </div>
                <div class="bg-white p-8 rounded-xl shadow-md">
                    <i class="fas fa-tags text-3xl text-lime-500 mb-3"></i>
                    <div class="text-4xl font-bold text-gray-800">{{ $categoryCount ?? 0 }}</div>
                    <p class="text-gray-600 font-medium mt-1">Kategori Buku</p>
                </div>
            </div>
        </div>
    </section>

    {{-- Quick Actions Section --}}
    <section class="py-20 bg-white">
        <div class="container mx-auto px-4 text-center">
            <h2 class="text-3xl font-bold text-gray-800 mb-12">Mulai Perjalanan Literasi Anda</h2>
            <div class="grid md:grid-cols-2 gap-8 max-w-4xl mx-auto">
                <a href="{{ route('books.index') }}" class="bg-pattern bg-gradient-to-br from-green-600 to-teal-700 text-white p-10 rounded-2xl shadow-lg hover:shadow-xl transition duration-300 transform hover:scale-105 flex flex-col items-center justify-center">
                    <i class="fas fa-search text-4xl mb-4"></i>
                    <h3 class="text-2xl font-bold mb-2">Lihat Katalog Buku</h3>
                    <p class="text-green-100">Jelajahi dan temukan buku favorit Anda dari ribuan koleksi yang tersedia.</p>
                </a>
                <a href="{{ route('student.register.form') }}" class="bg-pattern bg-gradient-to-br from-emerald-600 to-green-700 text-white p-10 rounded-2xl shadow-lg hover:shadow-xl transition duration-300 transform hover:scale-105 flex flex-col items-center justify-center">
                    <i class="fas fa-user-graduate text-4xl mb-4"></i>
                    <h3 class="text-2xl font-bold mb-2">Daftar sebagai Siswa</h3>
                    <p class="text-emerald-100">Buat akun untuk mulai meminjam buku dan mengakses semua fitur perpustakaan.</p>
                </a>
            </div>
        </div>
    </section>
@endsection