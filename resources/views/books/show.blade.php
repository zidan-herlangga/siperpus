@extends('layouts.app')

@section('title', $book->title . ' - Peminjaman Buku Online')

@section('content')
    <div class="container mx-auto px-4 py-8 max-w-6xl space-y-8 animate-fade">
        {{-- Tombol Kembali --}}
        <a href="{{ route('books.index') }}"
            class="inline-flex items-center text-gray-700 mb-6 font-semibold hover:text-green-600 transition">
            <i class="fas fa-arrow-left mr-2"></i>Kembali ke Katalog
        </a>

        {{-- Kartu Utama Buku --}}
        <div class="bg-white rounded-3xl shadow-xl overflow-hidden">
            {{-- Bagian Hero Buku --}}
            <div class="relative p-10 md:p-12 text-white rounded-b-3xl overflow-hidden animate-fade"
                style="background: linear-gradient(135deg, rgba(16,185,129,0.9), rgba(20,184,166,0.85)), url('{{ asset('assets/image/bg01-scaled.jpg') }}') center/cover no-repeat;">
                <div class="relative z-10">
                    <h1 class="text-4xl md:text-5xl font-extrabold mb-3 drop-shadow-lg">{{ $book->title }}</h1>
                    <p class="text-lg md:text-xl text-green-100 mb-5">oleh {{ $book->author }}</p>

                    <div class="flex flex-wrap gap-3">
                        @if ($book->stock > 0)
                            <span
                                class="px-4 py-1.5 bg-white/90 text-green-800 rounded-full text-sm font-semibold flex items-center shadow-sm">
                                <i class="fas fa-check-circle text-green-600 mr-2"></i>Tersedia
                            </span>
                        @else
                            <span
                                class="px-4 py-1.5 bg-white/90 text-red-800 rounded-full text-sm font-semibold flex items-center shadow-sm">
                                <i class="fas fa-times-circle text-red-600 mr-2"></i>Stok Habis
                            </span>
                        @endif

                        <span
                            class="px-4 py-1.5 bg-white/20 backdrop-blur-md text-white rounded-full text-sm font-semibold border border-white/30">
                            <i class="fas fa-tag mr-2"></i>{{ $book->category }}
                        </span>
                        <span
                            class="px-4 py-1.5 bg-white/20 backdrop-blur-md text-white rounded-full text-sm font-semibold border border-white/30">
                            <i class="fas fa-map-marker-alt mr-2"></i>Rak {{ $book->shelf_code }}
                        </span>
                    </div>
                </div>
            </div>

            {{-- Bagian Konten --}}
            <div class="p-8">
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-10">
                    {{-- Kolom Kiri --}}
                    <div class="lg:col-span-2 space-y-10">
                        {{-- Detail Buku --}}
                        <section>
                            <h2 class="text-2xl font-bold text-gray-800 mb-4">📖 Detail Buku</h2>
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
                        </section>

                        {{-- Deskripsi --}}
                        <section>
                            <h2 class="text-2xl font-bold text-gray-800 mb-4">📝 Deskripsi</h2>
                            <p class="text-gray-600 leading-relaxed">
                                Buku <strong>"{{ $book->title }}"</strong> karya {{ $book->author }} merupakan koleksi
                                berharga
                                perpustakaan kami. Diterbitkan oleh {{ $book->publisher }} pada tahun {{ $book->year }},
                                buku ini termasuk dalam kategori <strong>{{ $book->category }}</strong> dan dapat
                                ditemukan
                                di rak <strong>{{ $book->shelf_code }}</strong>.
                            </p>
                        </section>
                    </div>

                    {{-- Kolom Kanan --}}
                    <div class="lg:col-span-1">
                        <div class="sticky top-28 space-y-6">
                            {{-- Kartu Info Stok --}}
                            <div
                                class="bg-gradient-to-br from-green-50 to-teal-50 border border-green-100 rounded-2xl shadow-md p-6 transition-transform hover:scale-[1.02] hover:shadow-lg duration-300">
                                <h3 class="text-lg font-bold text-gray-800 mb-2">Stok Tersedia</h3>
                                <p class="text-3xl font-bold text-green-600">
                                    {{ $book->stock }}
                                    <span class="text-base font-medium text-gray-600">Eksemplar</span>
                                </p>

                                <div class="mt-5">
                                    {{-- @if ($book->stock > 0)
                                        <button onclick="showBorrowModal()"
                                            class="w-full flex items-center justify-center bg-gradient-to-r from-green-600 to-teal-600 text-white font-semibold py-3 rounded-xl hover:from-green-700 hover:to-teal-700 transition duration-300 transform hover:scale-105 shadow-lg">
                                            <i class="fas fa-hand-holding mr-2"></i>Pinjam Buku
                                        </button>
                                    @else
                                        <button disabled
                                            class="w-full bg-gray-400 text-white py-3 px-6 rounded-xl font-semibold cursor-not-allowed flex items-center justify-center">
                                            <i class="fas fa-times-circle mr-2"></i>Stok Habis
                                        </button>
                                    @endif --}}
                                    {{-- Panggil AppServiceProvider di atas agar bisa digunakan --}}
                                    @php
                                        use App\Providers\AppServiceProvider;
                                    @endphp

                                    @if (AppServiceProvider::isLibraryOpen() && $book->stock > 0)
                                        {{-- Tombol aktif jika PERPUSTAKAAN BUKA dan STOK ADA --}}
                                        <button onclick="showBorrowModal()"
                                            class="flex-1 text-white py-3 px-6 rounded-lg font-semibold bg-green-600 hover:bg-green-700 transition duration-300 transform hover:scale-105 shadow-md flex items-center justify-center">
                                            <i class="fas fa-hand-holding mr-2"></i>Pinjam Buku
                                        </button>
                                    @else
                                        {{-- Tombol nonaktif jika PERPUSTAKAAN TUTUP atau STOK HABIS --}}
                                        <button disabled title="Peminjaman hanya dapat dilakukan pada jam operasional (Senin-Jumat, 07:00-16:00 WIB)"
                                            class="flex-1 bg-gray-400 text-white py-3 px-6 rounded-lg font-semibold cursor-not-allowed flex items-center justify-center">
                                            <i class="fas fa-clock mr-2"></i>
                                            {{-- Tampilkan pesan yang sesuai --}}
                                            {{ $book->stock > 0 ? 'Di Luar Jam Operasional' : 'Stok Habis' }}
                                        </button>
                                    @endif
                                </div>
                            </div>

                            {{-- Info Peminjaman --}}
                            <div class="bg-white rounded-2xl shadow-md p-6 border border-gray-100">
                                <h3 class="text-lg font-bold text-gray-800 mb-3">Informasi Peminjaman</h3>
                                <ul class="space-y-3 text-sm text-gray-600">
                                    <li class="flex items-start">
                                        <i class="fas fa-clock fa-fw mr-3 mt-1 text-green-500"></i>
                                        <span>Maksimal peminjaman: <strong>7 hari</strong></span>
                                    </li>
                                    <li class="flex items-start">
                                        <i class="fas fa-exclamation-triangle fa-fw mr-3 mt-1 text-orange-500"></i>
                                        <span>Denda keterlambatan: <strong>Rp1.000/hari</strong></span>
                                    </li>
                                    <li class="flex items-start">
                                        <i class="fas fa-user-check fa-fw mr-3 mt-1 text-teal-500"></i>
                                        <span>Hanya untuk siswa terverifikasi</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- MODAL PINJAM BUKU --}}
        <div id="borrowModal"
            class="fixed inset-0 bg-black bg-opacity-70 flex items-center justify-center p-4 z-50 hidden opacity-0 transition-opacity duration-300">
            <div
                class="bg-white rounded-2xl max-w-md w-full modal-content transform scale-95 opacity-0 transition-all duration-300">
                <div class="p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-xl font-bold text-gray-800">Pinjam Buku</h3>
                        <button onclick="closeBorrowModal()"
                            class="text-gray-400 hover:text-gray-700 text-2xl leading-none">&times;</button>
                    </div>

                    <div class="text-center py-4 border-b border-gray-200">
                        <div
                            class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4 shadow-inner">
                            <i class="fas fa-book text-green-600 text-2xl"></i>
                        </div>
                        <h4 class="text-lg font-semibold mb-2">{{ $book->title }}</h4>
                        <p class="text-gray-600 text-sm mb-4">oleh {{ $book->author }}</p>
                    </div>

                    <div class="pt-6">
                        @auth('student')
                            <form id="borrowForm" action="{{ route('books.borrow', $book) }}" method="POST">
                                @csrf
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

                                    <div class="flex items-start">
                                        <input type="checkbox" name="terms" id="terms" required
                                            class="mt-1 mr-3 h-5 w-5 text-green-600 border-gray-300 rounded focus:ring-green-500">
                                        <label for="terms" class="text-xs text-gray-600">
                                            Saya setuju untuk mengembalikan buku sesuai tanggal jatuh tempo dan menjaga
                                            kondisi buku dengan baik.
                                        </label>
                                    </div>

                                    <button type="submit"
                                        class="w-full bg-gradient-to-r from-green-600 to-teal-600 text-white py-3 px-4 rounded-lg font-semibold hover:from-green-700 hover:to-teal-700 transition duration-300 flex items-center justify-center shadow-md">
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
        </div>
    </div>
@endsection
