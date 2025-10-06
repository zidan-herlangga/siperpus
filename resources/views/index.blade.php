@extends('layouts.app')

@section('title', 'Perpustakaan Sekolah - Beranda')

@section('content')
    <section class="gradient-bg text-white py-20">
        <div class="container mx-auto px-4 text-center">
            <h2 class="text-4xl md:text-5xl font-bold mb-6 animate-pulse">Selamat Datang di Perpustakaan SMK KG 2 </h2>
            <p class="text-lg md:text-xl mb-8 text-green-100 max-w-2xl mx-auto leading-relaxed">
                Temukan berbagai koleksi buku menarik untuk mendukung proses belajar mengajar di sekolah kami.
            </p>
            <div class="flex flex-col sm:flex-row justify-center space-y-4 sm:space-y-0 sm:space-x-4">
                <a href="{{ route('books.index') }}" class="bg-white text-green-700 px-8 py-3 rounded-lg font-semibold hover:bg-gray-100 transition duration-300 transform hover:scale-105 shadow-lg">
                    <i class="fas fa-search mr-2"></i>Jelajahi Koleksi
                </a>
                <a href="{{ route('student.register.form') }}" class="border-2 border-white text-white px-8 py-3 rounded-lg font-semibold hover:bg-white hover:text-green-700 transition duration-300 transform hover:scale-105">
                    <i class="fas fa-user-plus mr-2"></i>Daftar Sekarang
                </a>
            </div>
        </div>
    </section>

    <section class="py-16 bg-white">
        <div class="container mx-auto px-4">
            <h3 class="text-3xl font-bold text-center text-gray-800 mb-12">Mengapa Memilih Perpustakaan Kami?</h3>
            <div class="grid md:grid-cols-3 gap-8">
                <div class="text-center p-6 hover:bg-green-50 rounded-lg transition duration-300">
                    <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-book text-green-600 text-2xl"></i>
                    </div>
                    <h4 class="text-xl font-semibold mb-3">Koleksi Lengkap</h4>
                    <p class="text-gray-600">Ribuan buku dari berbagai kategori dan penerbit terkemuka</p>
                </div>
                <div class="text-center p-6 hover:bg-green-50 rounded-lg transition duration-300">
                    <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-clock text-green-600 text-2xl"></i>
                    </div>
                    <h4 class="text-xl font-semibold mb-3">Akses 24/7</h4>
                    <p class="text-gray-600">Sistem online yang dapat diakses kapan saja dan di mana saja</p>
                </div>
                <div class="text-center p-6 hover:bg-green-50 rounded-lg transition duration-300">
                    <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-bell text-green-600 text-2xl"></i>
                    </div>
                    <h4 class="text-xl font-semibold mb-3">Notifikasi Otomatis</h4>
                    <p class="text-gray-600">Pengingat pengembalian buku dan informasi terbaru</p>
                </div>
            </div>
        </div>
    </section>

    <section class="py-16 bg-gray-100">
        <div class="container mx-auto px-4">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-8 text-center">
                <div class="bg-white p-6 rounded-lg shadow-sm hover:shadow-md transition duration-300">
                    <div class="text-3xl md:text-4xl font-bold text-green-600 mb-2" id="bookCount" data-target="{{ $bookCount ?? 0 }}">0</div>
                    <p class="text-gray-600 font-medium">Judul Buku</p>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-sm hover:shadow-md transition duration-300">
                    <div class="text-3xl md:text-4xl font-bold text-teal-600 mb-2" id="studentCount" data-target="{{ $studentCount ?? 0 }}">0</div>
                    <p class="text-gray-600 font-medium">Siswa Terdaftar</p>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-sm hover:shadow-md transition duration-300">
                    <div class="text-3xl md:text-4xl font-bold text-emerald-600 mb-2" id="borrowCount" data-target="{{ $borrowCount ?? 0 }}">0</div>
                    <p class="text-gray-600 font-medium">Peminjaman/Bulan</p>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-sm hover:shadow-md transition duration-300">
                    <div class="text-3xl md:text-4xl font-bold text-lime-600 mb-2" id="categoryCount" data-target="{{ $categoryCount ?? 0 }}">0</div>
                    <p class="text-gray-600 font-medium">Kategori Buku</p>
                </div>
            </div>
        </div>
    </section>

    <section class="py-16 bg-white">
        <div class="container mx-auto px-4 text-center">
            <h3 class="text-3xl font-bold text-gray-800 mb-8">Mulai Jelajahi Sekarang</h3>
            <div class="grid md:grid-cols-2 gap-8 max-w-4xl mx-auto">
                <a href="{{ route('books.index') }}" class="bg-gradient-to-r from-green-500 to-teal-600 text-white p-8 rounded-lg shadow-lg hover:shadow-xl transition duration-300 transform hover:scale-105">
                    <i class="fas fa-book-open text-4xl mb-4"></i>
                    <h4 class="text-xl font-semibold mb-2">Lihat Katalog Buku</h4>
                    <p class="text-green-100">Jelajahi koleksi buku kami yang lengkap</p>
                </a>
                <a href="{{ route('student.register.form') }}" class="bg-gradient-to-r from-emerald-500 to-green-600 text-white p-8 rounded-lg shadow-lg hover:shadow-xl transition duration-300 transform hover:scale-105">
                    <i class="fas fa-user-graduate text-4xl mb-4"></i>
                    <h4 class="text-xl font-semibold mb-2">Daftar sebagai Siswa</h4>
                    <p class="text-emerald-100">Bergabung dan nikmati layanan perpustakaan</p>
                </a>
            </div>
        </div>
    </section>
@endsection

@push('scripts')
<script>
    // Animated counter
    function animateCounter(element, target, duration = 2000) {
        let start = 0;
        const increment = target / (duration / 16);
        const timer = setInterval(() => {
            start += increment;
            if (start >= target) {
                element.textContent = target + '+';
                clearInterval(timer);
            } else {
                element.textContent = Math.floor(start) + '+';
            }
        }, 16);
    }

    // Start animation when page loads
    document.addEventListener('DOMContentLoaded', function() {
        // Simulate data (in real app, you would fetch this from your backend)
        setTimeout(() => {
            animateCounter(document.getElementById('bookCount'), 5000);
            animateCounter(document.getElementById('studentCount'), 1200);
            animateCounter(document.getElementById('borrowCount'), 350);
            animateCounter(document.getElementById('categoryCount'), 50);
        }, 500);

        // Add scroll animations
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('animate-fade-in-up');
                }
            });
        }, observerOptions);

        // Observe feature cards
        document.querySelectorAll('.grid > div').forEach(card => {
            observer.observe(card);
        });
    });
</script>

<style>
    /* .animate-fade-in-up {
        animation: fadeInUp 0.6s ease-out;
    } */

    .gradient-bg {
        background: linear-gradient(135deg, #047857, #065f46);
    }
    .animate-fade-in-up {
        opacity: 0; /* Mulai dari transparan */
        animation: fadeInUp 0.8s ease-out forwards;
    }
    
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
</style>
@endpush