@extends('layouts.app')

@section('title', 'Katalog Buku - ' . config('app.name'))

@section('styles')
<link rel="stylesheet" href="{{ asset('assets/css/books.css') }}">
<style>
    /* Custom style spesifik halaman ini */
    .bg-mesh {
        background-color: #f9fafb;
        background-image: 
            radial-gradient(at 20% 20%, rgba(52, 211, 153, 0.08) 0px, transparent 50%),
            radial-gradient(at 80% 0%, rgba(16, 185, 129, 0.06) 0px, transparent 50%),
            radial-gradient(at 0% 100%, rgba(167, 243, 208, 0.08) 0px, transparent 50%);
    }
    .card-glass {
        background: rgba(255, 255, 255, 0.8);
        backdrop-filter: blur(16px) saturate(180%);
        -webkit-backdrop-filter: blur(16px) saturate(180%);
        border: 1px solid rgba(255, 255, 255, 0.9);
        box-shadow: 0 4px 24px -4px rgba(0, 0, 0, 0.05);
    }
    
    /* Input & Select Modern */
    .input-modern {
        border: 1.5px solid #e5e7eb;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .input-modern:hover { border-color: #d1d5db; }
    .input-modern:focus {
        border-color: #10b981;
        box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1);
    }

    /* Card Buku */
    .book-card {
        background: white;
        border: 1px solid #f3f4f6;
        border-radius: 16px;
        overflow: hidden;
        transition: all 0.4s cubic-bezier(0.22, 1, 0.36, 1);
    }
    .book-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 20px 40px -12px rgba(0,0,0,0.1);
        border-color: #e5e7eb;
    }
    .book-card:hover .book-cover-icon {
        transform: scale(1.1) rotate(-3deg);
    }
    .book-cover-icon {
        transition: all 0.4s cubic-bezier(0.22, 1, 0.36, 1);
    }
    
    .btn-detail {
        background: linear-gradient(135deg, #059669, #047857);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .btn-detail:hover {
        background: linear-gradient(135deg, #047857, #065f46);
        box-shadow: 0 8px 20px -4px rgba(5, 150, 105, 0.4);
    }
    
    /* Modal Styles */
    .modal-backdrop {
        opacity: 0;
        visibility: hidden;
        transition: all 0.3s ease;
    }
    .modal-backdrop.show {
        opacity: 1;
        visibility: visible;
    }
    .modal-box {
        transform: scale(0.95) translateY(10px);
        transition: all 0.3s cubic-bezier(0.22, 1, 0.36, 1);
    }
    .modal-backdrop.show .modal-box {
        transform: scale(1) translateY(0);
    }
</style>
@stop

@section('content')
<div class="min-h-screen bg-mesh py-8">
    <div class="container mx-auto px-4">

        {{-- Header --}}
        <div class="text-center mb-8 reveal">
            <h1 class="text-3xl md:text-4xl font-extrabold text-gray-800 tracking-tight mb-2">
                Katalog Buku
            </h1>
            <p class="text-gray-500 max-w-2xl mx-auto text-sm">
                Jelajahi koleksi perpustakaan digital kami — temukan dan pinjam buku favoritmu dengan mudah.
            </p>
        </div>

        {{-- Search & Filter Bar (Sticky) --}}
        <div class="bg-white/90 backdrop-blur-md rounded-2xl p-5 mb-8 border border-gray-100 shadow-sm reveal reveal-delay-1 transition-all duration-300" id="filterBar">
            <form action="{{ route('books.index') }}" method="GET" class="grid md:grid-cols-5 gap-4 items-end">
                {{-- Search --}}
                <div class="md:col-span-2">
                    <label for="search" class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">Cari Buku</label>
                    <div class="relative">
                        <i class="fas fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                        <input type="text" name="search" id="search" placeholder="Judul, pengarang, ISBN..." 
                            value="{{ request('search') }}"
                            class="input-modern w-full pl-10 pr-4 py-2.5 rounded-xl bg-white outline-none text-gray-800 placeholder-gray-400 text-sm">
                    </div>
                </div>

                {{-- Category --}}
                <div>
                    <label for="category" class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">Kategori</label>
                    <select name="category" id="category"
                        class="input-modern w-full px-4 py-2.5 rounded-xl bg-white outline-none text-gray-800 text-sm appearance-none cursor-pointer">
                        <option value="">Semua</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category }}" {{ request('category') == $category ? 'selected' : '' }}>{{ $category }}</option>
                        @endforeach
                    </select>
                </div>

                {{-- Sort --}}
                <div>
                    <label for="sort" class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">Urutkan</label>
                    <select name="sort" id="sort"
                        class="input-modern w-full px-4 py-2.5 rounded-xl bg-white outline-none text-gray-800 text-sm appearance-none cursor-pointer">
                        <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Terbaru</option>
                        <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Terlama</option>
                        <option value="title_asc" {{ request('sort') == 'title_asc' ? 'selected' : '' }}>Judul A-Z</option>
                        <option value="title_desc" {{ request('sort') == 'title_desc' ? 'selected' : '' }}>Judul Z-A</option>
                    </select>
                </div>

                {{-- Buttons --}}
                <div class="flex gap-2">
                    <button type="submit"
                        class="btn-detail flex-1 text-white px-4 py-2.5 rounded-xl font-semibold text-sm flex items-center justify-center gap-2">
                        <i class="fas fa-magnifying-glass text-xs"></i> Cari
                    </button>
                    <a href="{{ route('books.index') }}"
                        class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-600 px-4 py-2.5 rounded-xl font-semibold transition-all text-center text-sm flex items-center justify-center gap-2 border border-gray-200">
                        <i class="fas fa-arrow-rotate-left text-xs"></i> Reset
                    </a>
                </div>
            </form>
        </div>

        {{-- Filter Summary --}}
        @if (request('search') || request('category'))
            <div class="mb-6 text-center text-sm text-gray-500 reveal">
                Menampilkan <span class="font-bold text-gray-800">{{ $books->total() }}</span> hasil
                @if (request('search'))
                    untuk "<span class="font-semibold text-emerald-600">{{ request('search') }}</span>"
                @endif
                @if (request('category'))
                    di kategori 
                    <span class="bg-emerald-50 text-emerald-700 px-3 py-1 rounded-full text-xs font-bold inline-flex items-center gap-1 border border-emerald-100">
                        <i class="fas fa-tag text-[10px]"></i>{{ request('category') }}
                    </span>
                @endif
            </div>
        @endif

        {{-- Book Grid --}}
        @if ($books->count() > 0)
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @foreach ($books as $book)
                    <div class="book-card reveal">
                        {{-- Cover Area --}}
                        <div class="relative bg-gradient-to-br from-gray-50 to-emerald-50/50 h-44 flex items-center justify-center p-4">
                            <i class="fas fa-book-open book-cover-icon text-5xl text-emerald-300"></i>
                            
                            {{-- Badges --}}
                            <div class="absolute top-3 left-3">
                                <span class="bg-white/90 backdrop-blur-sm text-gray-600 text-[10px] font-bold px-2 py-1 rounded-md shadow-sm border border-gray-100">
                                    {{ $book->shelf_code }}
                                </span>
                            </div>
                            <div class="absolute top-3 right-3">
                                @if ($book->stock > 0)
                                    <span class="bg-emerald-500 text-white text-[10px] font-bold px-2.5 py-1 rounded-md shadow-sm">
                                        Stok: {{ $book->stock }}
                                    </span>
                                @else
                                    <span class="bg-red-500 text-white text-[10px] font-bold px-2.5 py-1 rounded-md shadow-sm">
                                        Habis
                                    </span>
                                @endif
                            </div>
                        </div>

                        {{-- Info Area --}}
                        <div class="p-4">
                            <h3 class="font-bold text-gray-800 text-sm leading-snug line-clamp-2 mb-3 min-h-[2.5rem] hover:text-emerald-600 transition-colors">
                                {{ $book->title }}
                            </h3>
                            
                            <div class="space-y-1.5 mb-4 text-xs text-gray-500">
                                <div class="flex items-center gap-2">
                                    <i class="fas fa-pen-fancy w-3 text-center text-purple-400"></i>
                                    <span class="truncate">{{ $book->author }}</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <i class="fas fa-layer-group w-3 text-center text-blue-400"></i>
                                    <span class="truncate">{{ $book->category }}</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <i class="fas fa-calendar w-3 text-center text-orange-400"></i>
                                    <span>{{ $book->year }}</span>
                                </div>
                            </div>

                            <a href="{{ route('books.show', $book) }}"
                                class="btn-detail block w-full text-center text-white font-semibold py-2.5 rounded-xl text-sm">
                                Lihat Detail
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            {{-- Empty State --}}
            <div class="py-16 reveal">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-12 max-w-md mx-auto text-center">
                    <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-5 border border-gray-100">
                        <i class="fas fa-magnifying-glass text-3xl text-gray-300"></i>
                    </div>
                    <h3 class="text-lg font-bold text-gray-800 mb-2">Buku Tidak Ditemukan</h3>
                    <p class="text-gray-400 text-sm mb-6 leading-relaxed">Maaf, tidak ada buku yang cocok dengan pencarian Anda. Coba ganti kata kunci atau filter.</p>
                    <a href="{{ route('books.index') }}"
                        class="btn-detail inline-flex items-center gap-2 text-white px-6 py-2.5 rounded-xl font-semibold text-sm">
                        <i class="fas fa-arrow-rotate-left text-xs"></i> Reset Pencarian
                    </a>
                </div>
            </div>
        @endif

        {{-- Pagination --}}
        @if ($books->hasPages())
            <div class="mt-10 reveal">
                {{ $books->links('vendor.pagination.custom-pagination') }}
            </div>
        @endif
    </div>
</div>

{{-- Modal Petunjuk --}}
<div id="borrowGuideModal" class="modal-backdrop fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center z-[60] p-4">
    <div class="modal-box bg-white rounded-2xl shadow-2xl max-w-md w-full border border-gray-100 overflow-hidden">
        <div class="bg-emerald-600 p-5 text-white">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-white/20 rounded-lg flex items-center justify-center">
                    <i class="fas fa-book-open-reader"></i>
                </div>
                <div>
                    <h3 class="font-bold">Petunjuk Peminjaman</h3>
                    <p class="text-emerald-200 text-xs">Panduan singkat untuk meminjam buku</p>
                </div>
            </div>
        </div>
        <div class="p-6">
            <ol class="space-y-4">
                <li class="flex gap-3">
                    <div class="w-7 h-7 bg-emerald-100 text-emerald-600 rounded-full flex items-center justify-center text-xs font-bold flex-shrink-0 mt-0.5">1</div>
                    <div>
                        <p class="text-sm font-medium text-gray-800">Login / Daftar</p>
                        <p class="text-xs text-gray-500 mt-0.5">Pastikan Anda sudah memiliki akun.</p>
                    </div>
                </li>
                <li class="flex gap-3">
                    <div class="w-7 h-7 bg-emerald-100 text-emerald-600 rounded-full flex items-center justify-center text-xs font-bold flex-shrink-0 mt-0.5">2</div>
                    <div>
                        <p class="text-sm font-medium text-gray-800">Cari & Pinjam Buku</p>
                        <p class="text-xs text-gray-500 mt-0.5">Temukan buku lalu klik tombol "Pinjam Buku".</p>
                    </div>
                </li>
                <li class="flex gap-3">
                    <div class="w-7 h-7 bg-emerald-100 text-emerald-600 rounded-full flex items-center justify-center text-xs font-bold flex-shrink-0 mt-0.5">3</div>
                    <div>
                        <p class="text-sm font-medium text-gray-800">Pengembalian</p>
                        <p class="text-xs text-gray-500 mt-0.5">Kembalikan buku sebelum jatuh tempo.</p>
                    </div>
                </li>
            </ol>
            <div class="mt-6 flex justify-end">
                <button onclick="closeModal()" class="bg-emerald-600 hover:bg-emerald-700 text-white px-5 py-2.5 rounded-xl text-sm font-semibold transition-colors">
                    Mengerti
                </button>
            </div>
        </div>
    </div>
</div>

@section('scripts')
<script>
    // --- Scroll Reveal ---
    const revealElements = document.querySelectorAll('.reveal');
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('visible');
                observer.unobserve(entry.target);
            }
        });
    }, { threshold: 0.1 });
    revealElements.forEach(el => observer.observe(el));

    

    // --- Modal Logic (Standalone) ---
    const modal = document.getElementById('borrowGuideModal');
    
    // Fungsi global agar bisa dipanggil dari layout/app jika ada tombol yang memakainya
    window.openBorrowGuideModal = function() {
        modal.classList.add('show');
        document.body.style.overflow = 'hidden';
    }
    
    window.closeBorrowGuideModal = function() {
        modal.classList.remove('show');
        document.body.style.overflow = '';
    }

    // Alias untuk modal di halaman ini
    function openModal() {
        modal.classList.add('show');
        document.body.style.overflow = 'hidden';
    }
    function closeModal() {
        modal.classList.remove('show');
        document.body.style.overflow = '';
    }

    // Tutup modal jika klik luar
    modal.addEventListener('click', (e) => {
        if (e.target === modal) closeModal();
    });
</script>
@stop

@endsection