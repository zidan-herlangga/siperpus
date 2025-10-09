@extends('layouts.app')

@section('title', 'Katalog Buku - Peminjaman Buku Online')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <!-- Header Section -->
        <div class="text-center mb-12">
            <h1 class="text-4xl md:text-5xl font-bold text-gray-800 mb-4">Katalog Buku</h1>
            <p class="text-lg text-gray-600 max-w-2xl mx-auto">
                Temukan buku yang Anda butuhkan dari koleksi kami yang lengkap
            </p>
        </div>

        <!-- Search and Filter Section -->
        <div class="bg-white rounded-2xl shadow-lg p-6 mb-8">
            <form action="{{ route('books.index') }}" method="GET"
                class="space-y-4 md:space-y-0 md:flex md:space-x-4 md:items-end">
                <div class="flex-1">
                    <label for="search" class="block text-sm font-medium text-gray-700 mb-2">Cari Buku</label>
                    <div class="search-box relative">
                        <input type="text" name="search" id="search"
                            placeholder="Cari berdasarkan judul, pengarang, atau kategori..."
                            class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg form-input focus:outline-none focus:border-green-500 transition"
                            value="{{ request('search') }}">
                        <i class="fas fa-search absolute left-3 top-3.5 text-gray-400"></i>
                    </div>
                </div>

                <div class="flex-1">
                    <label for="category" class="block text-sm font-medium text-gray-700 mb-2">Kategori</label>
                    <select name="category" id="category"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-green-500 transition">
                        <option value="">Semua Kategori</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category }}" {{ request('category') == $category ? 'selected' : '' }}>
                                {{ $category }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="flex space-x-3">
                    <button type="submit"
                        class="flex items-center justify-center gradient-bg  text-white px-6 py-3 rounded-lg font-semibold">
                        <i class="fas fa-search mr-2"></i>Cari
                    </button>
                    <a href="{{ route('books.index') }}"
                        class="flex items-center justify-center bg-gray-500 text-white px-6 py-3 rounded-lg hover:bg-gray-600 transition duration-300 font-semibold">
                        <i class="fas fa-refresh mr-2"></i>Reset
                    </a>
                </div>
            </form>
        </div>

        <!-- Results Count -->
        @if (request()->has('search') || request()->has('category'))
            <div class="mb-6">
                <p class="text-gray-600">
                    Menampilkan {{ $books->total() }} hasil
                    @if (request('search'))
                        untuk "<strong>{{ request('search') }}</strong>"
                    @endif
                    @if (request('category'))
                        dalam kategori <span
                            class="bg-purple-100 text-purple-800 px-2 py-1 rounded-full text-sm font-medium">{{ request('category') }}</span>
                    @endif
                </p>
            </div>
        @endif

        <!-- Books Grid -->
        <div id="booksContainer">
            @if ($books->count() > 0)
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6 mb-8">
                    @foreach ($books as $book)
                        <div
                            class="book-card bg-white rounded-xl shadow-md overflow-hidden border border-gray-200 hover:border-green-300">
                            <div class="p-6">
                                <!-- Status Badge -->
                                <div class="flex justify-between items-start mb-3">
                                    @if ($book->stock > 0)
                                        <span
                                            class="px-3 py-1 bg-green-100 text-green-800 text-xs font-semibold rounded-full flex items-center">
                                            <i class="fas fa-check-circle mr-1"></i>Tersedia
                                        </span>
                                    @else
                                        <span
                                            class="px-3 py-1 bg-red-100 text-red-800 text-xs font-semibold rounded-full flex items-center">
                                            <i class="fas fa-clock mr-1"></i>Habis
                                        </span>
                                    @endif
                                    <span
                                        class="text-xs text-gray-500 bg-gray-100 px-2 py-1 rounded">{{ $book->shelf_code }}</span>
                                </div>

                                <!-- Book Info -->
                                <h3 class="font-bold text-lg text-gray-800 mb-2 line-clamp-2 leading-tight">
                                    {{ $book->title }}</h3>
                                <p class="text-gray-600 mb-2 flex items-center">
                                    <i class="fas fa-user-edit mr-2 text-purple-500"></i>
                                    <span class="line-clamp-1">{{ $book->author }}</span>
                                </p>
                                <p class="text-gray-600 mb-4 flex items-center">
                                    <i class="fas fa-tag mr-2 text-blue-500"></i>
                                    {{ $book->category }}
                                </p>

                                <!-- Stock Info -->
                                <div class="flex justify-between items-center text-sm text-gray-500 mb-4">
                                    <span class="flex items-center">
                                        <i class="fas fa-copy mr-1"></i>
                                        Stok: {{ $book->stock }}
                                    </span>
                                    <span class="flex items-center">
                                        <i class="fas fa-calendar mr-1"></i>
                                        {{ $book->year }}
                                    </span>
                                </div>

                                <!-- Action Button -->
                                <a href="{{ route('books.show', $book) }}"
                                    class="block w-full text-center gradient-bg text-white font-semibold py-3 px-4 rounded-lg hover:from-purple-600 hover:to-blue-600 transition duration-300 transform hover:scale-105 shadow-md">
                                    <i class="fas fa-eye mr-2"></i>Lihat Detail
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <!-- Empty State -->
                <div class="text-center py-12">
                    <div class="bg-white rounded-2xl shadow-sm p-8 max-w-md mx-auto">
                        <i class="fas fa-book-open text-6xl text-gray-300 mb-4"></i>
                        <h3 class="text-xl font-semibold text-gray-600 mb-2">Tidak ada buku ditemukan</h3>
                        <p class="text-gray-500 mb-6">Coba ubah kata kunci pencarian atau filter kategori</p>
                        <a href="{{ route('books.index') }}"
                            class="bg-purple-600 text-white px-6 py-2 rounded-lg hover:bg-purple-700 transition duration-300 inline-flex items-center">
                            <i class="fas fa-refresh mr-2"></i>Reset Pencarian
                        </a>
                    </div>
                </div>
            @endif
        </div>

        <!-- Pagination -->
        @if ($books->hasPages())
            <div class="mt-8 p-3 rounded-lg">
                {{-- pagination no dark --}}
                {{ $books->links('vendor.pagination.custom-pagination') }}

            </div>
        @endif
    </div>

    <!-- Modal Petunjuk Peminjaman Buku -->
    <div id="borrowGuideModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center p-4 hidden z-50">
        <div class="bg-white rounded-2xl max-w-lg w-full shadow-xl">
            <div class="p-6">
                <div class="flex justify-between items-center mb-5">
                    <h3 class="text-xl font-bold text-gray-800">Petunjuk Peminjaman Buku</h3>
                    <button onclick="closeBorrowGuideModal()" class="text-gray-600 hover:text-gray-700">
                        <i class="fas fa-times text-xl"></i>
                    </button>
                </div>

                <div class="space-y-4 text-gray-700 text-sm leading-relaxed">
                    <p>Berikut adalah langkah-langkah untuk meminjam buku di perpustakaan kami:</p>
                    <ol class="list-decimal list-inside space-y-2">
                        <li><strong>Login atau Daftar:</strong> Pastikan kamu sudah masuk ke akun siswa. Jika belum, gunakan tombol Login atau Daftar di modal peminjaman.</li>
                        <li><strong>Pilih Buku:</strong> Telusuri daftar buku yang tersedia dan klik tombol "Pinjam" pada buku yang kamu inginkan.</li>
                        <li><strong>Konfirmasi Peminjaman:</strong> Pada modal peminjaman, periksa detail tanggal peminjaman dan jatuh tempo, lalu klik "Konfirmasi Peminjaman".</li>
                        <li><strong>Ambil Buku:</strong> Setelah konfirmasi, kamu bisa mengambil buku tersebut di perpustakaan sesuai aturan yang berlaku.</li>
                        <li><strong>Kembalikan Tepat Waktu:</strong> Pastikan mengembalikan buku sebelum tanggal jatuh tempo untuk menghindari denda keterlambatan.</li>
                    </ol>
                    <p class="font-semibold">Jika ada pertanyaan, silakan hubungi petugas perpustakaan atau admin.</p>
                </div>
            </div>
        </div>
    </div>

    <script>
        function openBorrowGuideModal() {
            document.getElementById('borrowGuideModal').classList.remove('hidden');
        }
        function closeBorrowGuideModal() {
            document.getElementById('borrowGuideModal').classList.add('hidden');
        }
    </script>
@endsection
