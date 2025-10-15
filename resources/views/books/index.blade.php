@extends('layouts.app')

@section('title', 'Katalog Buku - Peminjaman Buku Online')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-green-50 to-yellow-50 py-12">
    <div class="container mx-auto px-6">

        {{-- Header --}}
        <div class="text-center mb-12">
            <h1 class="text-4xl md:text-5xl font-extrabold text-gray-800 mb-3 tracking-tight">
                📚 Katalog Buku
            </h1>
            <p class="text-lg text-gray-600 max-w-2xl mx-auto leading-relaxed">
                Jelajahi koleksi perpustakaan digital kami — temukan, baca, dan pinjam buku favoritmu dengan mudah.
            </p>
        </div>

        {{-- Search + Filter --}}
        <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-lg p-6 mb-10 border border-gray-200">
            <form action="{{ route('books.index') }}" method="GET" class="grid md:grid-cols-3 gap-4 items-end">
                <div class="md:col-span-2">
                    <label for="search" class="block text-sm font-semibold text-gray-700 mb-2">Cari Buku</label>
                    <div class="relative">
                        <input 
                            type="text" 
                            name="search" 
                            id="search" 
                            placeholder="Cari judul, pengarang, atau kategori..." 
                            value="{{ request('search') }}"
                            class="w-full pl-10 pr-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-green-500 focus:border-transparent transition duration-200 outline-none"
                        >
                        <i class="fas fa-search absolute left-3 top-3.5 text-gray-400"></i>
                    </div>
                </div>

                <div>
                    <label for="category" class="block text-sm font-semibold text-gray-700 mb-2">Kategori</label>
                    <select name="category" id="category"
                        class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:ring-2 focus:ring-green-500 focus:border-transparent transition duration-200">
                        <option value="">Semua Kategori</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category }}" {{ request('category') == $category ? 'selected' : '' }}>
                                {{ $category }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="flex md:col-span-3 gap-3">
                    <button type="submit"
                        class="flex-1 flex items-center justify-center bg-green-600 hover:bg-green-500 text-white px-6 py-3 rounded-lg font-semibold transition duration-300 transform hover:scale-105">
                        <i class="fas fa-search mr-2"></i>Cari
                    </button>
                    <a href="{{ route('books.index') }}"
                        class="flex-1 flex items-center justify-center bg-gray-200 hover:bg-gray-300 text-gray-800 px-6 py-3 rounded-lg font-semibold transition duration-300">
                        <i class="fas fa-rotate-right mr-2"></i>Reset
                    </a>
                </div>
            </form>
        </div>

        {{-- Filter Summary --}}
        @if (request('search') || request('category'))
            <div class="mb-6 text-gray-600 text-center">
                Menampilkan <span class="font-semibold">{{ $books->total() }}</span> hasil
                @if (request('search'))
                    untuk “<strong>{{ request('search') }}</strong>”
                @endif
                @if (request('category'))
                    dalam kategori 
                    <span class="bg-purple-100 text-purple-800 px-2 py-1 rounded-full text-sm font-medium">
                        {{ request('category') }}
                    </span>
                @endif
            </div>
        @endif

        {{-- Book Grid --}}
        @if ($books->count() > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
                @foreach ($books as $book)
                    <div class="relative group bg-white/80 backdrop-blur-xl rounded-2xl shadow-md border border-gray-200 hover:shadow-2xl transition-all duration-300 overflow-hidden">
                        <div class="p-6">
                            {{-- Status Badge --}}
                            <div class="flex justify-between items-start mb-4">
                                @if ($book->stock > 0)
                                    <span class="bg-green-100 text-green-800 text-xs font-semibold px-3 py-1 rounded-full">
                                        <i class="fas fa-check-circle mr-1"></i> Tersedia
                                    </span>
                                @else
                                    <span class="bg-red-100 text-red-800 text-xs font-semibold px-3 py-1 rounded-full">
                                        <i class="fas fa-clock mr-1"></i> Habis
                                    </span>
                                @endif
                                <span class="bg-gray-100 text-gray-600 text-xs px-2 py-1 rounded font-medium">
                                    {{ $book->shelf_code }}
                                </span>
                            </div>

                            {{-- Info --}}
                            <h3 class="font-bold text-lg text-gray-800 mb-1 line-clamp-2 leading-tight">
                                {{ $book->title }}
                            </h3>
                            <p class="text-sm text-gray-500 mb-2">
                                <i class="fas fa-user-edit mr-2 text-purple-500"></i>{{ $book->author }}
                            </p>
                            <p class="text-sm text-gray-500 mb-4">
                                <i class="fas fa-tag mr-2 text-blue-500"></i>{{ $book->category }}
                            </p>

                            {{-- Stock + Year --}}
                            <div class="flex justify-between items-center text-xs text-gray-500 mb-5">
                                <span><i class="fas fa-copy mr-1"></i> Stok: {{ $book->stock }}</span>
                                <span><i class="fas fa-calendar mr-1"></i>{{ $book->year }}</span>
                            </div>

                            {{-- Button --}}
                            <a href="{{ route('books.show', $book) }}"
                                class="block w-full text-center bg-gradient-to-r from-green-600 to-yellow-500 text-white font-semibold py-3 rounded-lg hover:from-green-500 hover:to-yellow-400 transition duration-300 transform hover:scale-105 shadow-md">
                                <i class="fas fa-eye mr-2"></i> Lihat Detail
                            </a>
                        </div>
                        {{-- Accent bar --}}
                        <div class="absolute left-0 top-0 h-full w-1 bg-gradient-to-b from-green-500 to-yellow-400"></div>
                    </div>
                @endforeach
            </div>
        @else
            {{-- Empty State --}}
            <div class="text-center py-20">
                <div class="bg-white/70 backdrop-blur-xl rounded-2xl shadow p-10 max-w-md mx-auto border border-gray-200">
                    <i class="fas fa-book-open text-6xl text-gray-300 mb-4"></i>
                    <h3 class="text-2xl font-semibold text-gray-700 mb-2">Tidak ada buku ditemukan</h3>
                    <p class="text-gray-500 mb-6">Coba ubah kata kunci pencarian atau kategori.</p>
                    <a href="{{ route('books.index') }}"
                        class="inline-flex items-center bg-green-600 text-white px-6 py-3 rounded-lg hover:bg-green-500 transition duration-300">
                        <i class="fas fa-rotate-right mr-2"></i> Reset Pencarian
                    </a>
                </div>
            </div>
        @endif

        {{-- Pagination --}}
        @if ($books->hasPages())
            <div class="mt-10 flex justify-center">
                {{ $books->links('vendor.pagination.custom-pagination') }}
            </div>
        @endif
    </div>
</div>

{{-- Modal Petunjuk --}}
<div id="borrowGuideModal" class="fixed inset-0 bg-black/50 flex items-center justify-center hidden z-50">
    <div class="bg-white rounded-2xl max-w-lg w-full shadow-xl p-6 animate-fade-in">
        <div class="flex justify-between items-center mb-5">
            <h3 class="text-xl font-bold text-gray-800">Petunjuk Peminjaman Buku</h3>
            <button onclick="closeBorrowGuideModal()" class="text-gray-600 hover:text-gray-800">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <ol class="list-decimal list-inside text-gray-700 space-y-2 text-sm leading-relaxed">
            <li>Login atau daftar terlebih dahulu sebelum meminjam buku.</li>
            <li>Pilih buku dari katalog, lalu klik tombol “Pinjam”.</li>
            <li>Konfirmasi peminjaman dan ambil buku di perpustakaan.</li>
            <li>Kembalikan buku sebelum jatuh tempo agar tidak terkena denda.</li>
        </ol>
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
