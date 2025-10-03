@extends('layouts.app')

@section('title', $book->title . ' - Perpustakaan Sekolah')

@section('content')
<div class="container mx-auto px-4 py-8 max-w-6xl">
    <!-- Back Button -->
    <a href="{{ route('books.index') }}" 
       class="inline-flex items-center text-gray-800 mb-6 font-semibold">
        <i class="fas fa-arrow-left mr-2"></i>Kembali ke Katalog
    </a>

    <!-- Book Detail Card -->
    <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
        <div class="md:flex">
            <!-- Book Cover Section -->
            <div class="md:w-2/5 bg-gradient-to-br from-purple-50 to-blue-50 p-8 flex items-center justify-center">
                <div class="text-center">
                    <div class="w-32 h-40 bg-gradient-to-br from-purple-500 to-blue-500 rounded-lg shadow-lg mx-auto mb-4 flex items-center justify-center">
                        <i class="fas fa-book text-white text-4xl"></i>
                    </div>
                    <div class="space-y-3">
                        @if($book->stock > 0)
                            <span class="inline-flex items-center px-4 py-2 bg-green-100 text-green-800 rounded-full text-sm font-semibold">
                                <i class="fas fa-check-circle mr-2"></i>Tersedia
                            </span>
                        @else
                            <span class="inline-flex items-center px-4 py-2 bg-red-100 text-red-800 rounded-full text-sm font-semibold">
                                <i class="fas fa-clock mr-2"></i>Sedang Dipinjam
                            </span>
                        @endif
                        <div class="text-center">
                            <span class="inline-flex items-center px-4 py-2 bg-blue-100 text-blue-800 rounded-full text-sm font-semibold">
                                <i class="fas fa-map-marker-alt mr-2"></i>{{ $book->shelf_code }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Book Details Section -->
            <div class="md:w-3/5 p-8">
                <h1 class="text-3xl md:text-4xl font-bold text-gray-800 mb-3">{{ $book->title }}</h1>
                <p class="text-xl text-gray-600 mb-6 flex items-center">
                    <i class="fas fa-user-edit mr-3 text-purple-500"></i>
                    {{ $book->author }}
                </p>

                <div class="grid md:grid-cols-2 gap-6 mb-8">
                    <!-- Left Column -->
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Penerbit</label>
                            <p class="text-lg font-semibold text-gray-800 flex items-center">
                                <i class="fas fa-building mr-3 text-blue-500"></i>
                                {{ $book->publisher }}
                            </p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Tahun Terbit</label>
                            <p class="text-lg font-semibold text-gray-800 flex items-center">
                                <i class="fas fa-calendar mr-3 text-green-500"></i>
                                {{ $book->year }}
                            </p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Kategori</label>
                            <p class="text-lg font-semibold text-gray-800 flex items-center">
                                <i class="fas fa-tag mr-3 text-orange-500"></i>
                                {{ $book->category }}
                            </p>
                        </div>
                    </div>

                    <!-- Right Column -->
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">ISBN</label>
                            <p class="text-lg font-semibold text-gray-800 flex items-center">
                                <i class="fas fa-barcode mr-3 text-purple-500"></i>
                                {{ $book->isbn ?? '-' }}
                            </p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Stok Tersedia</label>
                            <p class="text-2xl font-bold text-gray-800">
                                {{ $book->stock }} <span class="text-sm font-normal text-gray-600">eksemplar</span>
                            </p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-500 mb-1">Lokasi Rak</label>
                            <p class="text-xl font-bold text-blue-600 flex items-center">
                                <i class="fas fa-map-pin mr-3"></i>
                                {{ $book->shelf_code }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Action Buttons -->
                <div class="border-t border-gray-200 pt-6">
                    <div class="flex flex-col sm:flex-row space-y-3 sm:space-y-0 sm:space-x-4">
                        @if($book->stock > 0)
                            <button onclick="showBorrowModal()" 
                                    class="flex-1 bg-gradient-to-r from-green-500 to-teal-500 text-white py-3 px-6 rounded-lg font-semibold hover:from-green-600 hover:to-teal-600 transition duration-300 transform hover:scale-105 shadow-md flex items-center justify-center">
                                <i class="fas fa-hand-holding mr-2"></i>Pinjam Buku
                            </button>
                        @else
                            <button disabled
                                    class="flex-1 bg-gray-400 text-white py-3 px-6 rounded-lg font-semibold cursor-not-allowed flex items-center justify-center">
                                <i class="fas fa-clock mr-2"></i>Sedang Dipinjam
                            </button>
                        @endif
                        <a href="{{ route('books.index') }}" 
                           class="flex-1 bg-gradient-to-r from-purple-500 to-blue-500 text-white py-3 px-6 rounded-lg font-semibold hover:from-purple-600 hover:to-blue-600 transition duration-300 transform hover:scale-105 shadow-md flex items-center justify-center">
                            <i class="fas fa-search mr-2"></i>Cari Buku Lain
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Additional Information -->
    <div class="mt-8 grid md:grid-cols-2 gap-8">
        <!-- Book Description -->
        <div class="bg-white rounded-2xl shadow-lg p-6">
            <h3 class="text-xl font-semibold text-gray-800 mb-4 flex items-center">
                <i class="fas fa-info-circle mr-2 text-purple-500"></i>
                Deskripsi Buku
            </h3>
            <p class="text-gray-600 leading-relaxed">
                Buku "{{ $book->title }}" karya {{ $book->author }} merupakan koleksi berharga perpustakaan kami. 
                Diterbitkan oleh {{ $book->publisher }} pada tahun {{ $book->year }}, buku ini termasuk dalam kategori 
                {{ $book->category }} dan dapat ditemukan di rak {{ $book->shelf_code }}.
            </p>
        </div>

        <!-- Borrowing Information -->
        <div class="bg-white rounded-2xl shadow-lg p-6">
            <h3 class="text-xl font-semibold text-gray-800 mb-4 flex items-center">
                <i class="fas fa-clipboard-list mr-2 text-blue-500"></i>
                Informasi Peminjaman
            </h3>
            <ul class="space-y-3 text-gray-600">
                <li class="flex items-center">
                    <i class="fas fa-clock mr-3 text-green-500"></i>
                    <span>Maksimal peminjaman: <strong>7 hari</strong></span>
                </li>
                <li class="flex items-center">
                    <i class="fas fa-exclamation-triangle mr-3 text-orange-500"></i>
                    <span>Denda keterlambatan: <strong>Rp5.000/hari</strong></span>
                </li>
                <li class="flex items-center">
                    <i class="fas fa-user-check mr-3 text-purple-500"></i>
                    <span>Hanya untuk siswa terdaftar</span>
                </li>
            </ul>
        </div>
    </div>
</div>

<!-- Borrow Modal -->
<div id="borrowModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 hidden z-50">
    <div class="bg-white rounded-2xl max-w-md w-full max-h-[90vh] overflow-y-auto">
        <div class="p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-xl font-bold text-gray-800">Pinjam Buku</h3>
                <button onclick="closeBorrowModal()" class="text-gray-500 hover:text-gray-700">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            
            <div class="text-center py-4">
                <div class="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-book text-purple-600 text-2xl"></i>
                </div>
                <h4 class="text-lg font-semibold mb-2">{{ $book->title }}</h4>
                <p class="text-gray-600 mb-4">oleh {{ $book->author }}</p>
            </div>

            <div class="space-y-4">
                <p class="text-gray-600 text-center">
                    Untuk meminjam buku ini, silakan login sebagai siswa terdaftar atau daftar terlebih dahulu.
                </p>
                
                <div class="flex space-x-3">
                    <a href="/login" 
                       class="flex-1 bg-purple-600 text-white py-3 px-4 rounded-lg font-semibold hover:bg-purple-700 transition duration-300 text-center">
                        <i class="fas fa-sign-in-alt mr-2"></i>Login
                    </a>
                    <a href="{{ route('student.register.form') }}" 
                       class="flex-1 bg-blue-600 text-white py-3 px-4 rounded-lg font-semibold hover:bg-blue-700 transition duration-300 text-center">
                        <i class="fas fa-user-plus mr-2"></i>Daftar
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
    function showBorrowModal() {
        document.getElementById('borrowModal').classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeBorrowModal() {
        document.getElementById('borrowModal').classList.add('hidden');
        document.body.style.overflow = 'auto';
    }

    // Close modal when clicking outside
    document.getElementById('borrowModal').addEventListener('click', function(e) {
        if (e.target === this) {
            closeBorrowModal();
        }
    });

    // Close modal with Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            closeBorrowModal();
        }
    });

    // Add fade-in animation
    document.addEventListener('DOMContentLoaded', function() {
        const elements = document.querySelectorAll('.bg-white');
        elements.forEach((element, index) => {
            element.style.animationDelay = `${index * 100}ms`;
            element.classList.add('animate-fade-in-up');
        });
    });
</script>

<style>
    .animate-fade-in-up {
        animation: fadeInUp 0.6s ease-out;
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