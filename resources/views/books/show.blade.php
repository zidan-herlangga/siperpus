@extends('layouts.app')

@section('title', $book->title . ' - ' . config('app.name'))

@section('content')
    <div class="container mx-auto px-4 py-6 max-w-6xl space-y-6">
        {{-- Tombol Kembali --}}
        <a href="{{ route('books.index') }}"
            class="inline-flex items-center text-gray-700 hover:text-green-600 transition-colors">
            <i class="fas fa-arrow-left mr-2"></i>Kembali ke Katalog
        </a>

        {{-- Kartu Utama Buku --}}
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
            {{-- Bagian Header Buku --}}
            <div class="bg-green-600 text-white p-6">
                <h1 class="text-2xl md:text-3xl font-bold mb-2">{{ $book->title }}</h1>
                <p class="text-green-100 mb-4">oleh {{ $book->author }}</p>

                <div class="flex flex-wrap gap-2">
                    @if ($book->stock > 0)
                        <span class="bg-white text-green-800 px-3 py-1 rounded-full text-sm font-medium flex items-center">
                            <i class="fas fa-check-circle text-green-600 mr-1"></i>Tersedia
                        </span>
                    @else
                        <span class="bg-white text-red-800 px-3 py-1 rounded-full text-sm font-medium flex items-center">
                            <i class="fas fa-times-circle text-red-600 mr-1"></i>Stok Habis
                        </span>
                    @endif

                    <span class="bg-green-700 text-white px-3 py-1 rounded-full text-sm font-medium">
                        <i class="fas fa-tag mr-1"></i>{{ $book->category }}
                    </span>
                    <span class="bg-green-700 text-white px-3 py-1 rounded-full text-sm font-medium">
                        <i class="fas fa-map-marker-alt mr-1"></i>Rak {{ $book->shelf_code }}
                    </span>
                </div>
            </div>

            {{-- Bagian Konten --}}
            <div class="p-6">
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    {{-- Kolom Kiri --}}
                    <div class="lg:col-span-2 space-y-6">
                        {{-- Detail Buku --}}
                        <section>
                            <h2 class="text-xl font-bold text-gray-800 mb-3">Detail Buku</h2>
                            <dl class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Penerbit</dt>
                                    <dd class="mt-1 font-semibold text-gray-800">{{ $book->publisher }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Tahun Terbit</dt>
                                    <dd class="mt-1 font-semibold text-gray-800">{{ $book->year }}</dd>
                                </div>
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">ISBN</dt>
                                    <dd class="mt-1 font-semibold text-gray-800">{{ $book->isbn ?? '-' }}</dd>
                                </div>
                            </dl>
                        </section>

                        {{-- Deskripsi --}}
                        <section>
                            {{-- <h2 class="text-xl font-bold text-gray-800 mb-3">Deskripsi</h2>
                            <p class="text-gray-600 leading-relaxed">
                                Buku <strong>"{{ $book->title }}"</strong> karya {{ $book->author }} merupakan koleksi
                                berharga perpustakaan kami. Diterbitkan oleh {{ $book->publisher }} pada tahun {{ $book->year }},
                                buku ini termasuk dalam kategori <strong>{{ $book->category }}</strong> dan dapat ditemukan
                                di rak <strong>{{ $book->shelf_code }}</strong>.
                            </p> --}}

                            {{-- Sinopsis --}}
                            <h2 class="text-xl font-bold text-gray-800 mb-3">Sinopsis</h2>
                            <p class="text-gray-600 leading-relaxed whitespace-pre-line">
                                {{ $book->synopsis ?? 'Sinopsis untuk buku ini belum tersedia.' }}
                            </p>
                        </section>
                    </div>

                    {{-- Kolom Kanan --}}
                    <div class="lg:col-span-1">
                        <div class="space-y-4">
                            {{-- Kartu Info Stok --}}
                            <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                                <h3 class="text-lg font-bold text-gray-800 mb-2">Stok Tersedia</h3>
                                <p class="text-2xl font-bold text-green-600">
                                    {{ $book->stock }}
                                    <span class="text-base font-medium text-gray-600">Eksemplar</span>
                                </p>

                                <div class="mt-4">
                                    @php
                                        use App\Providers\AppServiceProvider;
                                    @endphp

                                    @if (AppServiceProvider::isLibraryOpen() && $book->stock > 0)
                                        <button onclick="showBorrowModal()"
                                            class="w-full bg-green-600 text-white py-2 px-4 rounded font-medium hover:bg-green-700 transition-colors flex items-center justify-center">
                                            <i class="fas fa-hand-holding mr-2"></i>Pinjam Buku
                                        </button>
                                    @else
                                        <button disabled title="Peminjaman hanya dapat dilakukan pada jam operasional (Senin-Jumat, 07:00-16:00 WIB)"
                                            class="w-full bg-gray-400 text-white py-2 px-4 rounded font-medium cursor-not-allowed flex items-center justify-center">
                                            <i class="fas fa-clock mr-2"></i>
                                            {{ $book->stock > 0 ? 'Di Luar Jam Operasional' : 'Stok Habis' }}
                                        </button>
                                    @endif
                                </div>
                            </div>

                            {{-- Info Peminjaman --}}
                            <div class="bg-white border border-gray-200 rounded-lg p-4">
                                <h3 class="text-lg font-bold text-gray-800 mb-3">Informasi Peminjaman</h3>
                                <ul class="space-y-2 text-sm text-gray-600">
                                    <li class="flex items-start">
                                        <i class="fas fa-clock mr-2 mt-1 text-green-500"></i>
                                        <span>Maksimal peminjaman: <strong>7 hari</strong></span>
                                    </li>
                                    <li class="flex items-start">
                                        <i class="fas fa-exclamation-triangle mr-2 mt-1 text-orange-500"></i>
                                        <span>Denda keterlambatan: <strong>Rp1.000/hari</strong></span>
                                    </li>
                                    <li class="flex items-start">
                                        <i class="fas fa-user-check mr-2 mt-1 text-teal-500"></i>
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
        <div id="borrowModal" class="fixed inset-0 bg-black/50 flex items-center justify-center p-4 z-50 hidden">
            <div class="bg-white rounded-lg max-w-md w-full">
                <div class="p-5">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-bold text-gray-800">Pinjam Buku</h3>
                        <button onclick="closeBorrowModal()" class="text-gray-500 hover:text-gray-700">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>

                    <div class="text-center py-3 border-b border-gray-200">
                        <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-3">
                            <i class="fas fa-book text-green-600"></i>
                        </div>
                        <h4 class="font-semibold mb-1">{{ $book->title }}</h4>
                        <p class="text-gray-600 text-sm">oleh {{ $book->author }}</p>
                    </div>

                    <div class="pt-4">
                        @auth('student')
                            <form id="borrowForm" action="{{ route('books.borrow', $book) }}" method="POST">
                                @csrf
                                <div class="space-y-3">
                                    <div class="bg-gray-50 rounded p-3 text-sm space-y-1">
                                        <div class="flex justify-between">
                                            <span class="font-medium text-gray-500">Peminjam:</span>
                                            <span>{{ Auth::guard('student')->user()->name }}</span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span class="font-medium text-gray-500">Tanggal Pinjam:</span>
                                            <span>{{ now()->format('d M Y') }}</span>
                                        </div>
                                        <div class="flex justify-between">
                                            <span class="font-medium text-gray-500">Jatuh Tempo:</span>
                                            <span class="font-bold">{{ now()->addDays(7)->format('d M Y') }}</span>
                                        </div>
                                    </div>

                                    <div class="flex items-start">
                                        <input type="checkbox" name="terms" id="terms" required
                                            class="mt-1 mr-2 text-green-600 border-gray-300 rounded focus:ring-green-500">
                                        <label for="terms" class="text-xs text-gray-600">
                                            Saya setuju untuk mengembalikan buku sesuai tanggal jatuh tempo dan menjaga
                                            kondisi buku dengan baik.
                                        </label>
                                    </div>

                                    <button type="submit"
                                        class="w-full bg-green-600 text-white py-2 px-4 rounded font-medium hover:bg-green-700 transition-colors flex items-center justify-center">
                                        <i class="fas fa-check-circle mr-2"></i>Konfirmasi Peminjaman
                                    </button>
                                </div>
                            </form>
                        @else
                            <div class="space-y-3">
                                <p class="text-gray-600 text-center text-sm">
                                    Untuk meminjam buku ini, silakan login sebagai siswa terdaftar atau daftar terlebih dahulu.
                                </p>
                                <div class="flex gap-2">
                                    <a href="{{ route('student.login.form') }}"
                                        class="flex-1 bg-green-600 text-white py-2 px-3 rounded font-medium hover:bg-green-700 transition-colors text-center text-sm">
                                        <i class="fas fa-sign-in-alt mr-1"></i>Login
                                    </a>
                                    <a href="{{ route('student.register.form') }}"
                                        class="flex-1 bg-teal-600 text-white py-2 px-3 rounded font-medium hover:bg-teal-700 transition-colors text-center text-sm">
                                        <i class="fas fa-user-plus mr-1"></i>Daftar
                                    </a>
                                </div>
                            </div>
                        @endauth
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        function showBorrowModal() {
            document.getElementById('borrowModal').classList.remove('hidden');
        }

        function closeBorrowModal() {
            document.getElementById('borrowModal').classList.add('hidden');
        }

        // Close modal when clicking outside
        document.getElementById('borrowModal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeBorrowModal();
            }
        });
    </script>
@endsection