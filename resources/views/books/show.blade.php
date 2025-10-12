@extends('layouts.app')

@section('title', $book->title . ' - Peminjaman Buku Online')

@section('content')
    <div class="container mx-auto px-4 py-8 max-w-6xl">
        <a href="{{ route('books.index') }}" class="inline-flex items-center text-gray-700 mb-6 font-semibold hover:text-green-600 transition">
            <i class="fas fa-arrow-left mr-2"></i>Kembali ke Katalog
        </a>

        <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
            <div class="relative p-8 text-white" style="background: url('{{ asset('assets/image/bg01-scaled.jpg') }}') no-repeat center center; background-size: cover;">
                <div class="absolute inset-0 bg-gradient-to-br from-green-800/80 to-teal-700/70 backdrop-blur-sm"></div>
                <div class="relative">
                    <h1 class="text-3xl md:text-4xl font-bold mb-2 text-shadow-dark">{{ $book->title }}</h1>
                    <p class="text-xl text-green-100 mb-6">oleh {{ $book->author }}</p>
                    <div class="flex flex-wrap gap-3">
                        @if ($book->stock > 0)
                            <span class="inline-flex items-center px-3 py-1 bg-white/90 text-green-800 rounded-full text-sm font-semibold shadow">
                                <i class="fas fa-check-circle mr-2"></i>Tersedia
                            </span>
                        @else
                            <span class="inline-flex items-center px-3 py-1 bg-white/90 text-red-800 rounded-full text-sm font-semibold shadow">
                                <i class="fas fa-times-circle mr-2"></i>Stok Habis
                            </span>
                        @endif
                        <span class="inline-flex items-center px-3 py-1 bg-white/20 border border-white/30 backdrop-blur-sm text-white rounded-full text-sm font-semibold">
                            <i class="fas fa-tag mr-2"></i>{{ $book->category }}
                        </span>
                        <span class="inline-flex items-center px-3 py-1 bg-white/20 border border-white/30 backdrop-blur-sm text-white rounded-full text-sm font-semibold">
                            <i class="fas fa-map-marker-alt mr-2"></i>Rak: {{ $book->shelf_code }}
                        </span>
                    </div>
                </div>
            </div>

            <div class="p-8">
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    {{-- Kolom Kiri: Detail & Deskripsi --}}
                    <div class="lg:col-span-2">
                        <div class="mb-8">
                            <h2 class="text-2xl font-bold text-gray-800 mb-4">Detail Buku</h2>
                            <dl class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-4">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Penerbit</dt>
                                    <dd class="mt-1 text-lg font-semibold text-gray-800">{{ $book->publisher }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Tahun Terbit</dt>
                                    <dd class="mt-1 text-lg font-semibold text-gray-800">{{ $book->year }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">ISBN</dt>
                                    <dd class="mt-1 text-lg font-semibold text-gray-800">{{ $book->isbn ?? '-' }}</dd>
                                </div>
                            </dl>
                        </div>
                        <div>
                            <h2 class="text-2xl font-bold text-gray-800 mb-4">Deskripsi</h2>
                            <p class="text-gray-600 leading-relaxed">
                                Buku "{{ $book->title }}" karya {{ $book->author }} merupakan koleksi berharga perpustakaan kami. 
                                Diterbitkan oleh {{ $book->publisher }} pada tahun {{ $book->year }}, buku ini termasuk dalam kategori 
                                {{ $book->category }} dan dapat ditemukan di rak {{ $book->shelf_code }}.
                            </p>
                        </div>
                    </div>

                    {{-- Kolom Kanan: Aksi Peminjaman (Sticky) --}}
                    <div class="lg:col-span-1">
                        <div class="sticky top-28 space-y-6">
                            <div class="bg-gray-50 rounded-xl p-6 border">
                                <h3 class="text-lg font-bold text-gray-800">Stok Tersedia</h3>
                                <p class="text-3xl font-bold text-green-600">{{ $book->stock }} <span class="text-base font-medium text-gray-600">Eksemplar</span></p>
                                <div class="mt-4">
                                    @if ($book->stock > 0)
                                        <button onclick="showBorrowModal()"
                                            class="w-full text-white py-3 px-6 rounded-lg font-semibold bg-green-600 hover:bg-green-700 transition duration-300 transform hover:scale-105 shadow-md flex items-center justify-center">
                                            <i class="fas fa-hand-holding mr-2"></i>Pinjam Buku
                                        </button>
                                    @else
                                        <button disabled class="w-full bg-gray-400 text-white py-3 px-6 rounded-lg font-semibold cursor-not-allowed flex items-center justify-center">
                                            <i class="fas fa-times-circle mr-2"></i>Stok Habis
                                        </button>
                                    @endif
                                </div>
                            </div>
                             <div class="bg-white rounded-xl shadow-md p-6 border">
                                <h3 class="text-lg font-bold text-gray-800 mb-3">Informasi Peminjaman</h3>
                                <ul class="space-y-3 text-sm text-gray-600">
                                    <li class="flex items-start"><i class="fas fa-clock fa-fw mr-3 mt-1 text-green-500"></i><span>Maksimal peminjaman: <strong>7 hari</strong></span></li>
                                    <li class="flex items-start"><i class="fas fa-exclamation-triangle fa-fw mr-3 mt-1 text-orange-500"></i><span>Denda keterlambatan: <strong>Rp1.000/hari</strong></span></li>
                                    <li class="flex items-start"><i class="fas fa-user-check fa-fw mr-3 mt-1 text-teal-500"></i><span>Hanya untuk siswa terverifikasi</span></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="borrowModal" class="fixed inset-0 bg-black bg-opacity-70 flex items-center justify-center p-4 z-50 hidden opacity-0 transition-opacity duration-300">
    <div class="bg-white rounded-2xl max-w-md w-full modal-content transform scale-95 opacity-0 transition-all duration-300">
        <div class="p-6">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-xl font-bold text-gray-800">Pinjam Buku</h3>
                <button onclick="closeBorrowModal()" class="text-gray-400 hover:text-gray-700">&times;</button>
            </div>

            <div class="text-center py-4 border-b border-gray-200">
                <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-book text-green-600 text-2xl"></i>
                </div>
                <h4 class="text-lg font-semibold mb-2">{{ $book->title }}</h4>
                <p class="text-gray-600 text-sm mb-4">oleh {{ $book->author }}</p>
            </div>

            <div class="pt-6">
                @auth('student')
                    <form id="borrowForm" action="{{ route('books.borrow', $book) }}" method="POST">
                        @csrf
                        @error('borrow')
                            <div class="bg-red-100 border border-red-300 text-red-700 px-4 py-3 rounded-lg mb-4 text-sm" role="alert">
                                {{ $message }}
                            </div>
                        @enderror
                        <div class="space-y-4">
                            <div class="bg-gray-50 rounded-lg p-4 text-sm space-y-2">
                                <div class="flex justify-between">
                                    <span class="font-semibold text-gray-500">Peminjam:</span>
                                    <span>{{ Auth::guard('student')->user()->name }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="font-semibold text-gray-500">Tanggal Pinjam:</span>
                                    <span>{{ now()->format('d M Y') }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="font-semibold text-gray-500">Jatuh Tempo:</span>
                                    <span class="font-bold">{{ now()->addDays(7)->format('d M Y') }}</span>
                                </div>
                            </div>

                            {{-- Syarat & Ketentuan --}}
                            <div class="flex items-start">
                                <input type="checkbox" name="terms" id="terms" required class="mt-1 mr-3 h-5 w-5 text-green-600 border-gray-300 rounded focus:ring-green-500">
                                <label for="terms" class="text-xs text-gray-600">
                                    Saya setuju untuk mengembalikan buku sesuai tanggal jatuh tempo dan menjaga kondisi buku dengan baik.
                                </label>
                            </div>

                            <button type="submit" id="confirmBorrowBtn" class="w-full bg-green-600 text-white py-3 px-4 rounded-lg font-semibold hover:bg-green-700 transition duration-300 flex items-center justify-center">
                                <i class="fas fa-check-circle mr-2"></i>Konfirmasi Peminjaman
                            </button>
                        </div>
                    </form>
                @else
                    <div class="space-y-4">
                        <p class="text-gray-600 text-center">
                            Untuk meminjam buku ini, silakan login sebagai siswa terdaftar atau daftar terlebih dahulu.
                        </p>
                        <div class="flex space-x-3">
                            <a href="{{ route('student.login.form') }}" class="flex-1 bg-green-600 text-white py-3 px-4 rounded-lg font-semibold hover:bg-green-700 transition duration-300 text-center">
                                <i class="fas fa-sign-in-alt mr-2"></i>Login
                            </a>
                            <a href="{{ route('student.register.form') }}" class="flex-1 bg-teal-600 text-white py-3 px-4 rounded-lg font-semibold hover:bg-teal-700 transition duration-300 text-center">
                                <i class="fas fa-user-plus mr-2"></i>Daftar
                            </a>
                        </div>
                    </div>
                @endauth
            </div>
        </div>
    </div>
</div>
@endsection