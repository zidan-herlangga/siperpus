@extends('layouts.app')

@section('title', config('app.name'))

@section('styles')
<link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
@stop

@section('content')
    {{-- Hero Section dengan Efek Parallax --}}
    <section class="relative text-white py-20 md:py-28 overflow-hidden hero-section">
        <div class="absolute inset-0 bg-gradient-to-br from-emerald-900/90 via-green-800/80 to-teal-700/80"></div>
        
        <!-- Animated Background Elements -->
        <div class="absolute top-10 left-10 w-20 h-20 bg-green-400/20 rounded-full animate-pulse"></div>
        <div class="absolute bottom-10 right-10 w-32 h-32 bg-emerald-400/20 rounded-full animate-pulse" style="animation-delay: 1s;"></div>
        <div class="absolute top-1/2 left-1/4 w-16 h-16 bg-teal-400/20 rounded-full animate-pulse" style="animation-delay: 2s;"></div>

        <div class="relative container mx-auto px-6 text-center">
            <h1 class="text-3xl md:text-4xl font-bold mb-6 leading-tight animate-fade-in-down">
                Selamat Datang di <br><span class="text-yellow-300">{{ config('app.name', 'Perpustakaan') }}</span>
            </h1>
            <p class="text-lg mb-8 text-green-100 max-w-2xl mx-auto animate-fade-in-up">
                Temukan ribuan koleksi buku dan jelajahi dunia literasi secara digital — cepat, mudah, dan menyenangkan.
            </p>
            <!-- Belum login -->
            <div class="flex flex-col sm:flex-row justify-center gap-4 animate-fade-in-up" style="animation-delay: 0.2s;">
                @if (Auth::guard('student')->user())
                <a href="{{ route('books.index') }}"
                    class="bg-yellow-400 text-green-900 px-6 py-3 rounded-lg font-semibold hover:bg-yellow-300 transition-all duration-300 shadow-md hover:shadow-lg transform hover:-translate-y-1 flex items-center justify-center">
                    <i class="fas fa-search mr-2"></i> Jelajahi Koleksi
                </a>
                @else
                <a href="{{ route('books.index') }}"
                    class="bg-yellow-400 text-green-900 px-6 py-3 rounded-lg font-semibold hover:bg-yellow-300 transition-all duration-300 shadow-md hover:shadow-lg transform hover:-translate-y-1 flex items-center justify-center">
                    <i class="fas fa-search mr-2"></i> Jelajahi Koleksi
                </a>
                <a href="{{ route('student.register.form') }}"
                    class="border border-white text-white px-6 py-3 rounded-lg font-semibold hover:bg-white hover:text-green-800 transition-all duration-300 shadow-md hover:shadow-lg transform hover:-translate-y-1 flex items-center justify-center">
                    <i class="fas fa-user-plus mr-2"></i> Daftar Sekarang
                </a>
                @endif
            </div>
        </div>
    </section>

    {{-- Features Section dengan Animasi --}}
    <section class="py-16 bg-white">
        <div class="container mx-auto px-6">
            <h2 class="text-2xl font-bold text-center text-gray-800 mb-10 animate-on-scroll">Kami Punya?</h2>
            <div class="grid md:grid-cols-3 gap-8">
                @php
                    $features = [
                        [
                            'icon' => 'fa-book',
                            'title' => 'Koleksi Lengkap',
                            'desc' => 'Ribuan buku dari berbagai kategori dan penerbit terkemuka.',
                            'delay' => 0.1,
                        ],
                        [
                            'icon' => 'fa-laptop',
                            'title' => 'Akses Digital',
                            'desc' => 'Sistem online yang bisa diakses kapan pun dan di mana pun.',
                            'delay' => 0.2,
                        ],
                        [
                            'icon' => 'fa-bell',
                            'title' => 'Notifikasi Otomatis',
                            'desc' => 'Dapatkan pengingat pengembalian buku dan informasi terbaru.',
                            'delay' => 0.3,
                        ],
                    ];
                @endphp
                @foreach ($features as $index => $feature)
                    <div class="text-center p-5 bg-green-50 rounded-lg transition-all duration-300 hover:bg-green-100 hover:shadow-lg transform hover:-translate-y-2 animate-on-scroll" 
                         style="animation-delay: {{ $feature['delay'] }}s;">
                        <div class="w-16 h-16 bg-gradient-to-br from-green-100 to-emerald-100 rounded-full flex items-center justify-center mx-auto mb-4 shadow-md">
                            <i class="fas {{ $feature['icon'] }} text-green-600 text-2xl"></i>
                        </div>
                        <h3 class="text-lg font-semibold mb-2">{{ $feature['title'] }}</h3>
                        <p class="text-gray-600 text-sm">{{ $feature['desc'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Statistics Section dengan Animasi Angka --}}
    <section class="py-16 bg-gradient-to-br from-gray-50 to-green-50">
        <div class="container mx-auto px-6 text-center">
            <h2 class="text-2xl font-bold text-gray-800 mb-10 animate-on-scroll">Statistik Perpustakaan</h2>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
                @php
                    $stats = [
                        [
                            'icon' => 'fa-book',
                            'value' => $bookCount ?? 0,
                            'label' => 'Judul Buku',
                            'color' => 'text-green-500',
                            'bgColor' => 'from-green-50 to-emerald-50',
                            'delay' => 0.1,
                        ],
                        [
                            'icon' => 'fa-users',
                            'value' => $studentCount ?? 0,
                            'label' => 'Siswa Terdaftar',
                            'color' => 'text-teal-500',
                            'bgColor' => 'from-teal-50 to-cyan-50',
                            'delay' => 0.2,
                        ],
                        [
                            'icon' => 'fa-exchange-alt',
                            'value' => $borrowCount ?? 0,
                            'label' => 'Peminjaman Bulan Ini',
                            'color' => 'text-emerald-500',
                            'bgColor' => 'from-emerald-50 to-green-50',
                            'delay' => 0.3,
                        ],
                        [
                            'icon' => 'fa-tags',
                            'value' => $categoryCount ?? 0,
                            'label' => 'Kategori Buku',
                            'color' => 'text-lime-500',
                            'bgColor' => 'from-lime-50 to-green-50',
                            'delay' => 0.4,
                        ],
                    ];
                @endphp
                @foreach ($stats as $stat)
                    <div class="bg-gradient-to-br {{ $stat['bgColor'] }} p-6 rounded-lg shadow-sm hover:shadow-md transition-all duration-300 transform hover:-translate-y-1 animate-on-scroll" 
                         style="animation-delay: {{ $stat['delay'] }}s;">
                        <div class="w-12 h-12 bg-white rounded-full flex items-center justify-center mx-auto mb-3 shadow-sm">
                            <i class="fas {{ $stat['icon'] }} text-xl {{ $stat['color'] }}"></i>
                        </div>
                        <div class="text-2xl font-bold text-gray-800 counter" data-target="{{ $stat['value'] }}">0</div>
                        <p class="text-gray-600 text-sm mt-1">{{ $stat['label'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- Call-to-Action Section dengan Efek Hover --}}
    <section class="py-16 bg-white">
        <div class="container mx-auto px-6 text-center">
            <h2 class="text-2xl font-bold text-gray-800 mb-10 animate-on-scroll">Mulai Perjalanan Literasi Anda</h2>
            <div class="grid md:grid-cols-2 gap-6 max-w-3xl mx-auto">
                <a href="{{ route('books.index') }}"
                    class="group bg-gradient-to-br from-green-600 to-emerald-600 text-white p-8 rounded-lg shadow-md hover:shadow-xl transition-all duration-500 transform hover:-translate-y-2 flex flex-col items-center justify-center animate-on-scroll"
                    style="animation-delay: 0.1s;">
                    <div class="w-16 h-16 bg-white/20 rounded-full flex items-center justify-center mb-4 group-hover:bg-white/30 transition-colors duration-300">
                        <i class="fas fa-search text-3xl"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-2">Lihat Katalog Buku</h3>
                    <p class="text-green-100 text-sm max-w-sm">Jelajahi dan temukan buku favorit Anda dari ribuan koleksi yang tersedia.</p>
                </a>
                <a href="{{ route('student.register.form') }}"
                    class="group bg-gradient-to-br from-emerald-600 to-teal-600 text-white p-8 rounded-lg shadow-md hover:shadow-xl transition-all duration-500 transform hover:-translate-y-2 flex flex-col items-center justify-center animate-on-scroll"
                    style="animation-delay: 0.2s;">
                    <div class="w-16 h-16 bg-white/20 rounded-full flex items-center justify-center mb-4 group-hover:bg-white/30 transition-colors duration-300">
                        <i class="fas fa-user-graduate text-3xl"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-2">Daftar sebagai Siswa</h3>
                    <p class="text-emerald-100 text-sm max-w-sm">Buat akun untuk mulai meminjam buku dan mengakses semua fitur perpustakaan.</p>
                </a>
            </div>
        </div>
    </section>

    <!-- Testimonial Section -->
    <section class="py-16 bg-gradient-to-br from-green-50 to-emerald-50">
        <div class="container mx-auto px-6">
            <h2 class="text-2xl font-bold text-center text-gray-800 mb-10 animate-on-scroll">Apa Kata Mereka?</h2>
            <div class="grid md:grid-cols-3 gap-6">
                @php
                    $testimonials = [
                        [
                            'name' => 'Ahmad Rizki',
                            'role' => 'Siswa Kelas XII',
                            'content' => 'Sistem perpustakaan online ini sangat membantu saya menemukan referensi untuk tugas sekolah. Mudah digunakan dan koleksinya lengkap!',
                            'avatar' => 'https://picsum.photos/seed/user1/200/200.jpg',
                            'rating' => 5,
                            'delay' => 0.1,
                        ],
                        [
                            'name' => 'Siti Nurhaliza',
                            'role' => 'Siswa Kelas XI',
                            'content' => 'Saya suka fitur notifikasi pengingat pengembalian buku. Tidak perlu khawatir lagi terlambat mengembalikan buku pinjaman.',
                            'avatar' => 'https://picsum.photos/seed/user2/200/200.jpg',
                            'rating' => 5,
                            'delay' => 0.2,
                        ],
                        [
                            'name' => 'Budi Santoso',
                            'role' => 'Siswa Kelas X',
                            'content' => 'Aplikasi perpustakaan ini sangat memudahkan saya untuk mencari dan meminjam buku kapan saja dan di mana saja.',
                            'avatar' => 'https://picsum.photos/seed/user3/200/200.jpg',
                            'rating' => 4,
                            'delay' => 0.3,
                        ],
                    ];
                @endphp
                @foreach ($testimonials as $testimonial)
                    <div class="bg-white p-6 rounded-lg shadow-md hover:shadow-lg transition-all duration-300 transform hover:-translate-y-2 animate-on-scroll" 
                         style="animation-delay: {{ $testimonial['delay'] }}s;">
                        <div class="flex items-center mb-4">
                            <img src="{{ $testimonial['avatar'] }}" alt="{{ $testimonial['name'] }}" 
                                 class="w-12 h-12 rounded-full mr-3 object-cover">
                            <div>
                                <h4 class="font-semibold text-gray-800">{{ $testimonial['name'] }}</h4>
                                <p class="text-sm text-gray-600">{{ $testimonial['role'] }}</p>
                            </div>
                        </div>
                        <div class="flex mb-3">
                            @for ($i = 1; $i <= 5; $i++)
                                <i class="fas fa-star {{ $i <= $testimonial['rating'] ? 'text-yellow-400' : 'text-gray-300' }}"></i>
                            @endfor
                        </div>
                        <p class="text-gray-600 text-sm italic">"{{ $testimonial['content'] }}"</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    <!-- Custom Styles and Scripts -->
    
    @section('scripts')
    <script src="{{ asset('assets/js/script.js') }}"></script>
    @stop

@endsection