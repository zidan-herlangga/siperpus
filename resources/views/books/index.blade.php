@extends('layouts.app')

@section('title', 'Katalog - ' . config('app.name'))

@section('styles')
<link rel="stylesheet" href="{{ asset('assets/css/books.css') }}">
@stop

@section('content')
<div class="min-h-screen bg-gradient-to-br from-gray-50 to-green-50 py-8">
    <div class="container mx-auto px-4">

        {{-- Header dengan Animasi --}}
        <div class="text-center mb-8 animate-fade-in">
            <h1 class="text-3xl md:text-4xl font-bold text-gray-800 mb-2">
                Katalog Buku
            </h1>
            <p class="text-gray-600 max-w-2xl mx-auto">
                Jelajahi koleksi perpustakaan digital kami — temukan dan pinjam buku favoritmu dengan mudah.
            </p>
        </div>

        {{-- Search + Filter dengan Desain Lebih Menarik --}}
        <div class="bg-white rounded-lg shadow-md p-4 mb-6 border border-gray-200 animate-slide-up">
            <form action="{{ route('books.index') }}" method="GET" class="grid md:grid-cols-5 gap-4 items-end">
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
                            class="w-full pl-9 pr-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-green-500 focus:ring-2 focus:ring-green-200 transition-all"
                        >
                        <i class="fas fa-search absolute left-3 top-3 text-gray-400 text-sm"></i>
                    </div>
                </div>

                {{-- Category Filter --}}
                <div>
                    <label for="category" class="block text-sm font-medium text-gray-700 mb-2">Kategori</label>
                    <select name="category" id="category"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-green-500 focus:ring-2 focus:ring-green-200 transition-all">
                        <option value="">Semua Kategori</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category }}" {{ request('category') == $category ? 'selected' : '' }}>
                                {{ $category }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Sort Filter --}}
                <div>
                    <label for="sort" class="block text-sm font-medium text-gray-700 mb-2">Urutkan</label>
                    <select name="sort" id="sort"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:border-green-500 focus:ring-2 focus:ring-green-200 transition-all">
                        <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Terbaru</option>
                        <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Terlama</option>
                        <option value="title_asc" {{ request('sort') == 'title_asc' ? 'selected' : '' }}>Judul A-Z</option>
                        <option value="title_desc" {{ request('sort') == 'title_desc' ? 'selected' : '' }}>Judul Z-A</option>
                    </select>
                </div>

                {{-- Buttons --}}
                <div class="flex gap-2">
                    <button type="submit"
                        class="flex-1 bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 text-white px-4 py-2 rounded-lg font-medium transition-all transform hover:-translate-y-0.5 shadow-md">
                        <i class="fas fa-search mr-2"></i>Cari
                    </button>
                    <a href="{{ route('books.index') }}"
                        class="flex-1 bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded-lg font-medium transition-all text-center">
                        <i class="fas fa-rotate-right mr-2"></i>Reset
                    </a>
                </div>
            </form>
        </div>

        {{-- Filter Summary dengan Desain Lebih Menarik --}}
        @if (request('search') || request('category'))
            <div class="mb-4 text-gray-600 text-center animate-fade-in">
                Menampilkan <span class="font-medium text-green-600">{{ $books->total() }}</span> hasil
                @if (request('search'))
                    untuk "<strong class="text-green-700">{{ request('search') }}</strong>"
                @endif
                @if (request('category'))
                    dalam kategori 
                    <span class="bg-gradient-to-r from-green-100 to-emerald-100 text-green-800 px-3 py-1 rounded-full text-sm font-medium inline-flex items-center">
                        <i class="fas fa-tag mr-1"></i>{{ request('category') }}
                    </span>
                @endif
            </div>
        @endif

        {{-- Loading Indicator --}}
        <div id="loadingIndicator" class="hidden fixed top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2 z-50">
            <div class="bg-white rounded-lg shadow-lg p-4 flex items-center">
                <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-green-600 mr-3"></div>
                <span class="text-gray-700">Memuat data...</span>
            </div>
        </div>

        {{-- Book Grid dengan Animasi --}}
        @if ($books->count() > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @foreach ($books as $index => $book)
                    <div class="book-card bg-white rounded-lg shadow-sm border border-gray-200 hover:shadow-xl transition-all duration-300 transform hover:-translate-y-2 animate-on-scroll" 
                         style="animation-delay: {{ $index * 0.05 }}s;">
                        <div class="relative overflow-hidden rounded-t-lg bg-gradient-to-br from-green-50 to-emerald-50 h-32 flex items-center justify-center">
                            {{-- Book Cover Placeholder dengan Animasi --}}
                            <div class="book-cover-container">
                                <i class="fas fa-book text-4xl text-green-600"></i>
                            </div>
                            
                            {{-- Status Badge --}}
                            <div class="absolute top-2 right-2">
                                @if ($book->stock > 0)
                                    <span class="bg-green-500 text-white text-xs font-medium px-2 py-1 rounded-full flex items-center">
                                        <i class="fas fa-check-circle mr-1"></i> Tersedia
                                    </span>
                                @else
                                    <span class="bg-red-500 text-white text-xs font-medium px-2 py-1 rounded-full flex items-center">
                                        <i class="fas fa-clock mr-1"></i> Habis
                                    </span>
                                @endif
                            </div>
                            
                            {{-- Shelf Code Badge --}}
                            <div class="absolute top-2 left-2">
                                <span class="bg-white/80 backdrop-blur text-gray-700 text-xs px-2 py-1 rounded-full">
                                    <i class="fas fa-map-marker-alt mr-1"></i> {{ $book->shelf_code }}
                                </span>
                            </div>
                        </div>

                        <div class="p-4">
                            {{-- Book Info --}}
                            <h3 class="font-bold text-gray-800 mb-2 line-clamp-2 leading-tight text-sm hover:text-green-600 transition-colors">
                                {{ $book->title }}
                            </h3>
                            <p class="text-xs text-gray-500 mb-2 flex items-center">
                                <i class="fas fa-user-edit mr-1 text-purple-500"></i>{{ $book->author }}
                            </p>
                            <p class="text-xs text-gray-500 mb-3 flex items-center">
                                <i class="fas fa-tag mr-1 text-blue-500"></i>{{ $book->category }}
                            </p>

                            {{-- Stock + Year --}}
                            <div class="flex justify-between items-center text-xs text-gray-500 mb-4">
                                <span class="flex items-center">
                                    <i class="fas fa-copy mr-1 text-green-500"></i> Stok: 
                                    <span class="font-medium {{ $book->stock > 0 ? 'text-green-600' : 'text-red-600' }}">
                                        {{ $book->stock }}
                                    </span>
                                </span>
                                <span class="flex items-center">
                                    <i class="fas fa-calendar mr-1 text-orange-500"></i>{{ $book->year }}
                                </span>
                            </div>

                            {{-- Button dengan Efek Hover --}}
                            <a href="{{ route('books.show', $book) }}"
                                class="block w-full text-center bg-gradient-to-r from-green-600 to-emerald-600 text-white font-medium py-2 rounded-lg hover:from-green-700 hover:to-emerald-700 transition-all duration-300 transform hover:-translate-y-0.5 text-sm">
                                <i class="fas fa-eye mr-1"></i> Lihat Detail
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            {{-- Empty State dengan Desain Lebih Menarik --}}
            <div class="text-center py-12 animate-fade-in">
                <div class="bg-white rounded-lg shadow-md p-8 max-w-md mx-auto border border-gray-200">
                    <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-book-open text-3xl text-gray-400"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-700 mb-2">Tidak ada buku ditemukan</h3>
                    <p class="text-gray-500 mb-4">Coba ubah kata kunci pencarian atau kategori.</p>
                    <a href="{{ route('books.index') }}"
                        class="inline-flex items-center bg-gradient-to-r from-green-600 to-emerald-600 text-white px-4 py-2 rounded-lg hover:from-green-700 hover:to-emerald-700 transition-all">
                        <i class="fas fa-rotate-right mr-2"></i> Reset Pencarian
                    </a>
                </div>
            </div>
        @endif

        {{-- Pagination dengan Desain Lebih Menarik --}}
        @if ($books->hasPages())
            <div class="mt-8 flex justify-center animate-fade-in">
                {{ $books->links('vendor.pagination.custom-pagination') }}
            </div>
        @endif
    </div>
</div>

{{-- Modal Petunjuk dengan Desain Lebih Menarik --}}
<div id="borrowGuideModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm flex items-center justify-center hidden z-50 p-4">
    <div class="bg-white rounded-lg max-w-md w-full shadow-xl p-5 transform transition-all scale-95 modal-content">
        <div class="flex justify-between items-center mb-4 border-b pb-2">
            <h3 class="text-lg font-bold text-green-700 flex items-center">
                <i class="fas fa-info-circle mr-2"></i> Petunjuk Peminjaman Buku
            </h3>
            <button onclick="closeBorrowGuideModal()" class="text-gray-500 hover:text-gray-700 transition-colors">
                <i class="fas fa-times"></i>
            </button>
        </div>
        <ol class="list-decimal list-inside text-gray-700 space-y-3 text-sm">
            <li class="flex items-start">
                <span class="bg-green-100 text-green-700 rounded-full w-6 h-6 flex items-center justify-center text-xs font-bold mr-2 flex-shrink-0">1</span>
                <div>Login atau daftar terlebih dahulu sebelum meminjam buku.</div>
            </li>
            <li class="flex items-start">
                <span class="bg-green-100 text-green-700 rounded-full w-6 h-6 flex items-center justify-center text-xs font-bold mr-2 flex-shrink-0">2</span>
                <div>Pilih buku dari katalog, lalu klik tombol "Pinjam".</div>
            </li>
            <li class="flex items-start">
                <span class="bg-green-100 text-green-700 rounded-full w-6 h-6 flex items-center justify-center text-xs font-bold mr-2 flex-shrink-0">3</span>
                <div>Konfirmasi peminjaman dan ambil buku di perpustakaan.</div>
            </li>
            <li class="flex items-start">
                <span class="bg-green-100 text-green-700 rounded-full w-6 h-6 flex items-center justify-center text-xs font-bold mr-2 flex-shrink-0">4</span>
                <div>Kembalikan buku sebelum jatuh tempo agar tidak terkena denda.</div>
            </li>
        </ol>
        <div class="mt-6 flex justify-end">
            <button onclick="closeBorrowGuideModal()" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition-colors">
                Mengerti
            </button>
        </div>
    </div>
</div>

@section('scripts')
<script src="{{ asset('assets/js/books.js') }}"></script>
@stop

@endsection