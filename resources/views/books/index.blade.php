@extends('layouts.app')

@section('title', 'Katalog - ' . config('app.name'))

@section('content')
<div class="min-h-screen bg-gray-50 py-8">
    <div class="container mx-auto px-4">

        {{-- Header --}}
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-gray-800 mb-2">
                Katalog Buku
            </h1>
            <p class="text-gray-600 max-w-2xl mx-auto">
                Jelajahi koleksi perpustakaan digital kami — temukan dan pinjam buku favoritmu dengan mudah.
            </p>
        </div>

        {{-- Search + Filter --}}
        <div class="bg-white rounded-lg shadow-sm p-4 mb-6 border border-gray-200">
            <form action="{{ route('books.index') }}" method="GET" class="grid md:grid-cols-4 gap-4 items-end">
                {{-- Search Input --}}
                <div class="md:col-span-2">
                    <label for="search" class="block text-sm font-medium text-gray-700 mb-2">Cari Buku</label>
                    <div class="relative">
                        <input 
                            type="text" 
                            name="search" 
                            id="search" 
                            placeholder="Cari judul, pengarang, atau kategori..." 
                            value="{{ request('search') }}"
                            class="w-full pl-9 pr-3 py-2 border border-gray-300 rounded focus:outline-none focus:border-green-500 focus:ring-1 focus:ring-green-500"
                        >
                        <i class="fas fa-search absolute left-3 top-3 text-gray-400 text-sm"></i>
                    </div>
                </div>

                {{-- Category Filter --}}
                <div>
                    <label for="category" class="block text-sm font-medium text-gray-700 mb-2">Kategori</label>
                    <select name="category" id="category"
                        class="w-full px-3 py-2 border border-gray-300 rounded focus:outline-none focus:border-green-500 focus:ring-1 focus:ring-green-500">
                        <option value="">Semua Kategori</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category }}" {{ request('category') == $category ? 'selected' : '' }}>
                                {{ $category }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Buttons --}}
                <div class="flex gap-2">
                    <button type="submit"
                        class="flex-1 bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded font-medium transition-colors">
                        <i class="fas fa-search mr-2"></i>Cari
                    </button>
                    <a href="{{ route('books.index') }}"
                        class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded font-medium transition-colors text-center">
                        <i class="fas fa-rotate-right mr-2"></i>Reset
                    </a>
                </div>
            </form>
        </div>

        {{-- Filter Summary --}}
        @if (request('search') || request('category'))
            <div class="mb-4 text-gray-600 text-center">
                Menampilkan <span class="font-medium">{{ $books->total() }}</span> hasil
                @if (request('search'))
                    untuk "<strong>{{ request('search') }}</strong>"
                @endif
                @if (request('category'))
                    dalam kategori 
                    <span class="bg-green-100 text-green-800 px-2 py-1 rounded text-sm font-medium">
                        {{ request('category') }}
                    </span>
                @endif
            </div>
        @endif

        {{-- Book Grid --}}
        @if ($books->count() > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @foreach ($books as $book)
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 hover:shadow-md transition-shadow">
                        <div class="p-4">
                            {{-- Status Badge --}}
                            <div class="flex justify-between items-start mb-3">
                                @if ($book->stock > 0)
                                    <span class="bg-green-100 text-green-800 text-xs font-medium px-2 py-1 rounded">
                                        <i class="fas fa-check-circle mr-1"></i> Tersedia
                                    </span>
                                @else
                                    <span class="bg-red-100 text-red-800 text-xs font-medium px-2 py-1 rounded">
                                        <i class="fas fa-clock mr-1"></i> Habis
                                    </span>
                                @endif
                                <span class="bg-gray-100 text-gray-600 text-xs px-2 py-1 rounded">
                                    {{ $book->shelf_code }}
                                </span>
                            </div>

                            {{-- Book Info --}}
                            <h3 class="font-bold text-gray-800 mb-2 line-clamp-2 leading-tight text-sm">
                                {{ $book->title }}
                            </h3>
                            <p class="text-xs text-gray-500 mb-2">
                                <i class="fas fa-user-edit mr-1 text-purple-500"></i>{{ $book->author }}
                            </p>
                            <p class="text-xs text-gray-500 mb-3">
                                <i class="fas fa-tag mr-1 text-blue-500"></i>{{ $book->category }}
                            </p>

                            {{-- Stock + Year --}}
                            <div class="flex justify-between items-center text-xs text-gray-500 mb-4">
                                <span><i class="fas fa-copy mr-1"></i> Stok: {{ $book->stock }}</span>
                                <span><i class="fas fa-calendar mr-1"></i>{{ $book->year }}</span>
                            </div>

                            {{-- Button --}}
                            <a href="{{ route('books.show', $book) }}"
                                class="block w-full text-center bg-green-600 text-white font-medium py-2 rounded hover:bg-green-700 transition-colors text-sm">
                                <i class="fas fa-eye mr-1"></i> Lihat Detail
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            {{-- Empty State --}}
            <div class="text-center py-12">
                <div class="bg-white rounded-lg shadow-sm p-8 max-w-md mx-auto border border-gray-200">
                    <i class="fas fa-book-open text-4xl text-gray-300 mb-3"></i>
                    <h3 class="text-xl font-semibold text-gray-700 mb-2">Tidak ada buku ditemukan</h3>
                    <p class="text-gray-500 mb-4">Coba ubah kata kunci pencarian atau kategori.</p>
                    <a href="{{ route('books.index') }}"
                        class="inline-flex items-center bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700 transition-colors">
                        <i class="fas fa-rotate-right mr-2"></i> Reset Pencarian
                    </a>
                </div>
            </div>
        @endif

        {{-- Pagination --}}
        @if ($books->hasPages())
            <div class="mt-8 flex justify-center">
                {{ $books->links('vendor.pagination.custom-pagination') }}
            </div>
        @endif
    </div>
</div>

{{-- Modal Petunjuk --}}
<div id="borrowGuideModal" class="fixed inset-0 bg-black/50 flex items-center justify-center hidden z-50 p-4">
    <div class="bg-white rounded-lg max-w-md w-full shadow-lg p-5">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-bold text-gray-800">Petunjuk Peminjaman Buku</h3>
            <button onclick="closeBorrowGuideModal()" class="text-gray-500 hover:text-gray-700">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <ol class="list-decimal list-inside text-gray-700 space-y-2 text-sm">
            <li>Login atau daftar terlebih dahulu sebelum meminjam buku.</li>
            <li>Pilih buku dari katalog, lalu klik tombol "Pinjam".</li>
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