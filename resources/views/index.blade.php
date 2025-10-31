@extends('layouts.app')

@section('title', config('app.name'))

@section('content')
    {{-- Hero Section --}}
    <section class="relative text-white py-20 md:py-28 overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-br from-emerald-900/90 via-green-800/80 to-teal-700/80"></div>

        <div class="relative container mx-auto px-6 text-center">
            <h1 class="text-3xl md:text-4xl font-bold mb-6 leading-tight">
                Selamat Datang di <br><span class="text-yellow-300">{{ config('app.name', 'Perpustakaan') }}</span>
            </h1>
            <p class="text-lg mb-8 text-green-100 max-w-2xl mx-auto">
                Temukan ribuan koleksi buku dan jelajahi dunia literasi secara digital — cepat, mudah, dan menyenangkan.
            </p>
            <div class="flex flex-col sm:flex-row justify-center gap-4">
                <a href="{{ route('books.index') }}"
                    class="bg-yellow-400 text-green-900 px-6 py-3 rounded-lg font-semibold hover:bg-yellow-300 transition-colors shadow-md flex items-center justify-center">
                    <i class="fas fa-search mr-2"></i> Jelajahi Koleksi
                </a>
                <a href="{{ route('student.register.form') }}"
                    class="border border-white text-white px-6 py-3 rounded-lg font-semibold hover:bg-white hover:text-green-800 transition-colors shadow-md flex items-center justify-center">
                    <i class="fas fa-user-plus mr-2"></i> Daftar Sekarang
                </a>
            </div>
        </div>
    </section>

    {{-- Features Section --}}
    <section class="py-16 bg-white">
        <div class="container mx-auto px-6">
            <h2 class="text-2xl font-bold text-center text-gray-800 mb-10">Mengapa Memilih Kami?</h2>
            <div class="grid md:grid-cols-3 gap-8">
                @php
                    $features = [
                        [
                            'icon' => 'fa-book',
                            'title' => 'Koleksi Lengkap',
                            'desc' => 'Ribuan buku dari berbagai kategori dan penerbit terkemuka.',
                        ],
                        [
                            'icon' => 'fa-laptop',
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
                    <div class="text-center p-5 bg-green-50 rounded-lg transition-colors">
                        <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas {{ $feature['icon'] }} text-green-600 text-2xl"></i>
                        </div>
                        <h3 class="text-lg font-semibold mb-2">{{ $feature['title'] }}</h3>
                        <p class="text-gray-600 text-sm">{{ $feature['desc'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Statistics Section --}}
    <section class="py-16 bg-gray-50">
        <div class="container mx-auto px-6 text-center">
            <h2 class="text-2xl font-bold text-gray-800 mb-10">Perpustakaan dalam Angka</h2>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                @php
                    $stats = [
                        [
                            'icon' => 'fa-book',
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
                    <div class="bg-white p-6 rounded-lg shadow-sm">
                        <i class="fas {{ $stat['icon'] }} text-2xl {{ $stat['color'] }} mb-2"></i>
                        <div class="text-2xl font-bold text-gray-800">{{ $stat['value'] }}</div>
                        <p class="text-gray-600 text-sm mt-1">{{ $stat['label'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Call-to-Action Section --}}
    <section class="py-16 bg-white">
        <div class="container mx-auto px-6 text-center">
            <h2 class="text-2xl font-bold text-gray-800 mb-10">Mulai Perjalanan Literasi Anda</h2>
            <div class="grid md:grid-cols-2 gap-6 max-w-3xl mx-auto">
                <a href="{{ route('books.index') }}"
                    class="bg-green-600 text-white p-8 rounded-lg shadow-md hover:bg-green-700 transition-colors flex flex-col items-center justify-center">
                    <i class="fas fa-search text-3xl mb-3"></i>
                    <h3 class="text-xl font-bold mb-2">Lihat Katalog Buku</h3>
                    <p class="text-green-100 text-sm max-w-sm">Jelajahi dan temukan buku favorit Anda dari ribuan koleksi yang tersedia.</p>
                </a>
                <a href="{{ route('student.register.form') }}"
                    class="bg-emerald-600 text-white p-8 rounded-lg shadow-md hover:bg-emerald-700 transition-colors flex flex-col items-center justify-center">
                    <i class="fas fa-user-graduate text-3xl mb-3"></i>
                    <h3 class="text-xl font-bold mb-2">Daftar sebagai Siswa</h3>
                    <p class="text-emerald-100 text-sm max-w-sm">Buat akun untuk mulai meminjam buku dan mengakses semua fitur perpustakaan.</p>
                </a>
            </div>
        </div>
    </section>
@endsection