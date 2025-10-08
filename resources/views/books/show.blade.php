@extends('layouts.app')

@section('title', $book->title . ' - Perpustakaan Sekolah')

@section('content')
    <div class="container mx-auto px-4 py-8 max-w-6xl">
        <a href="{{ route('books.index') }}"
            class="inline-flex items-center text-gray-800 mb-6 font-semibold hover:text-green-600 transition">
            <i class="fas fa-arrow-left mr-2"></i>Kembali ke Katalog
        </a>

        <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
            <div class="md:flex">
                <div class="md:w-2/5 bg-gradient-to-br from-green-50 to-teal-50 p-8 flex items-center justify-center"
                    style="background: url('{{ asset('assets/image/bg01-scaled.jpg') }}') no-repeat center center; background-size: cover;">
                    <div class="text-center">
                        <div class="w-32 h-40 bg-white rounded-lg shadow-lg mx-auto mb-4 flex items-center justify-center">
                            <i class="fas fa-book text-gray-700 text-4xl"></i>
                        </div>
                        <div class="space-y-3">
                            @auth('student')
                                @if ($book->stock > 0)
                                    <span
                                        class="inline-flex items-center px-4 py-2 bg-green-100 text-green-800 rounded-full text-sm font-semibold">
                                        <i class="fas fa-check-circle mr-2"></i>Tersedia
                                    </span>
                                @else
                                    <span
                                        class="inline-flex items-center px-4 py-2 bg-red-100 text-red-800 rounded-full text-sm font-semibold">
                                        <i class="fas fa-clock mr-2"></i>Habis
                                    </span>
                                @endif
                            @endauth
                            <div class="text-center">
                                <span
                                    class="inline-flex items-center px-4 py-2 bg-teal-100 text-teal-800 rounded-full text-sm font-semibold">
                                    <i class="fas fa-map-marker-alt mr-2"></i>{{ $book->shelf_code }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="md:w-3/5 p-8">
                    <h1 class="text-3xl md:text-4xl font-bold text-gray-800 mb-3">{{ $book->title }}</h1>
                    <p class="text-xl text-gray-600 mb-6 flex items-center">
                        <i class="fas fa-user-edit mr-3 text-green-500"></i>
                        {{ $book->author }}
                    </p>

                    <div class="grid md:grid-cols-2 gap-6 mb-8">
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-500 mb-1">Penerbit</label>
                                <p class="text-lg font-semibold text-gray-800 flex items-center">
                                    <i class="fas fa-building mr-3 text-teal-500"></i>
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

                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-500 mb-1">ISBN</label>
                                <p class="text-lg font-semibold text-gray-800 flex items-center">
                                    <i class="fas fa-barcode mr-3 text-gray-500"></i>
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
                                <p class="text-xl font-bold text-teal-600 flex items-center">
                                    <i class="fas fa-map-pin mr-3"></i>
                                    {{ $book->shelf_code }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="border-t border-gray-200 pt-6">
                        <div class="flex flex-col sm:flex-row space-y-3 sm:space-y-0 sm:space-x-4">
                            @if ($book->stock > 0)
                                <button onclick="showBorrowModal()"
                                    class="flex-1 text-white py-3 px-6 rounded-lg font-semibold bg-green-700 transition duration-300 transform hover:scale-105 shadow-md flex items-center justify-center">
                                    <i class="fas fa-hand-holding mr-2"></i>Pinjam Buku
                                </button>
                            @else
                                <button disabled
                                    class="flex-1 bg-gray-400 text-white py-3 px-6 rounded-lg font-semibold cursor-not-allowed flex items-center justify-center">
                                    <i class="fas fa-clock mr-2"></i>Stok Habis
                                </button>
                            @endif
                            <a href="{{ route('books.index') }}"
                                class="flex-1 bg-gray-600 text-white py-3 px-6 rounded-lg font-semibold hover:bg-gray-700 transition duration-300 shadow-md flex items-center justify-center">
                                <i class="fas fa-search mr-2"></i>Cari Buku Lain
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="borrowModal" class="fixed inset-0 bg-black bg-opacity-50 flex  items-center justify-center p-4 hidden z-50">
        <div class="bg-white rounded-2xl max-w-md w-full">
            <div class="p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-xl font-bold text-gray-800">Pinjam Buku</h3>
                    <button onclick="closeBorrowModal()" class="text-gray-500 hover:text-gray-700">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>

                <div class="text-center py-4">
                    <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-book text-green-600 text-2xl"></i>
                    </div>
                    <h4 class="text-lg font-semibold mb-2">{{ $book->title }}</h4>
                    <p class="text-gray-600 mb-4">oleh {{ $book->author }}</p>
                </div>

                @auth('student')
                    <form action="{{ route('books.borrow', $book) }}" method="POST">
                        @csrf
                        @error('borrow')
                            <div class="bg-red-100 border border-red-300 text-red-700 px-4 py-3 rounded-lg mb-4 text-sm"
                                role="alert">
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
                            <button type="submit"
                                class="w-full bg-green-600 text-white py-3 px-4 rounded-lg font-semibold hover:bg-green-700 transition duration-300">
                                <i class="fas fa-check-circle mr-2"></i>Konfirmasi Peminjaman
                            </button>
                        </div>
                    </form>
                @else
                    <div class="space-y-4">
                        <p class="text-gray-600 text-center">
                            Untuk meminjam buku ini, silakan login atau daftar terlebih dahulu.
                        </p>
                        <div class="flex space-x-3">
                            <a href="{{ route('student.login.form') }}"
                                class="flex-1 bg-green-600 text-white py-3 px-4 rounded-lg font-semibold hover:bg-green-700 transition duration-300 text-center">
                                <i class="fas fa-sign-in-alt mr-2"></i>Login
                            </a>
                            <a href="{{ route('student.register.form') }}"
                                class="flex-1 bg-teal-600 text-white py-3 px-4 rounded-lg font-semibold hover:bg-teal-700 transition duration-300 text-center">
                                <i class="fas fa-user-plus mr-2"></i>Daftar
                            </a>
                        </div>
                    </div>
                @endauth
            </div>
        </div>
    </div>
@endsection
