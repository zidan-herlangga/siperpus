@extends('layouts.app')

@section('title', $book->title . ' - ' . config('app.name'))

@section('styles')
<link rel="stylesheet" href="{{ asset('assets/css/books-show.css') }}">
<style>
    .bg-mesh {
        background-color: #f9fafb;
        background-image: 
            radial-gradient(at 20% 20%, rgba(52, 211, 153, 0.08) 0px, transparent 50%),
            radial-gradient(at 80% 0%, rgba(16, 185, 129, 0.06) 0px, transparent 50%),
            radial-gradient(at 0% 100%, rgba(167, 243, 208, 0.08) 0px, transparent 50%);
    }
    .card-glass {
        background: rgba(255, 255, 255, 0.9);
        backdrop-filter: blur(16px) saturate(180%);
        -webkit-backdrop-filter: blur(16px) saturate(180%);
        border: 1px solid rgba(255, 255, 255, 0.9);
        box-shadow: 0 4px 24px -4px rgba(0, 0, 0, 0.05);
    }
    
    /* Cover Book Styling */
    .book-cover-wrapper {
        perspective: 1000px;
    }
    .book-cover {
        box-shadow: -8px 8px 20px rgba(0,0,0,0.15), 0 0 0 1px rgba(0,0,0,0.05);
        transition: transform 0.4s cubic-bezier(0.22, 1, 0.36, 1);
    }
    .book-cover-wrapper:hover .book-cover {
        transform: rotateY(-5deg) scale(1.02);
    }

    /* Tabs Styling */
    .tab-btn {
        position: relative;
        padding-bottom: 0.75rem;
        color: #6b7280;
        transition: color 0.3s ease;
    }
    .tab-btn::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 0;
        width: 0;
        height: 2px;
        background-color: #059669;
        transition: width 0.3s ease;
        border-radius: 2px;
    }
    .tab-btn:hover { color: #374151; }
    .tab-btn.active { color: #059669; font-weight: 600; }
    .tab-btn.active::after { width: 100%; }

    /* Info Meta Cards */
    .meta-card {
        background: white;
        border: 1px solid #f3f4f6;
        transition: all 0.3s ease;
    }
    .meta-card:hover {
        border-color: #e5e7eb;
        box-shadow: 0 4px 12px -2px rgba(0,0,0,0.05);
    }

    /* Sidebar Stock */
    .btn-borrow {
        background: linear-gradient(135deg, #059669, #047857);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .btn-borrow:hover:not(:disabled) {
        background: linear-gradient(135deg, #047857, #065f46);
        transform: translateY(-2px);
        box-shadow: 0 10px 25px -5px rgba(5, 150, 105, 0.4);
    }
    .btn-borrow:disabled {
        background: #d1d5db;
        cursor: not-allowed;
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

    /* Custom Checkbox */
    input[type="checkbox"] {
        appearance: none;
        -webkit-appearance: none;
        width: 18px;
        height: 18px;
        border: 2px solid #d1d5db;
        border-radius: 4px;
        cursor: pointer;
        transition: all 0.2s ease;
        position: relative;
        flex-shrink: 0;
    }
    input[type="checkbox"]:checked {
        background-color: #059669;
        border-color: #059669;
    }
    input[type="checkbox"]:checked::after {
        content: '';
        position: absolute;
        left: 4px;
        top: 1px;
        width: 6px;
        height: 10px;
        border: solid white;
        border-width: 0 2px 2px 0;
        transform: rotate(45deg);
    }
</style>
@stop

@section('content')
    <div class="min-h-screen bg-mesh py-8">
        <div class="container mx-auto px-4 max-w-6xl">
            
            <!-- Loading Overlay -->
            <div id="loadingState" class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center z-[70] hidden">
                <div class="bg-white rounded-2xl p-8 flex flex-col items-center shadow-2xl border border-gray-100">
                    <div class="animate-spin rounded-full h-12 w-12 border-4 border-gray-100 border-t-emerald-600 mb-4"></div>
                    <p class="text-gray-700 font-medium">Memproses peminjaman...</p>
                </div>
            </div>

            <!-- Success Toast -->
            <div id="successNotification" class="fixed top-6 right-6 bg-white border border-emerald-200 text-emerald-700 px-5 py-4 rounded-xl shadow-2xl z-[70] hidden transform translate-x-[120%] transition-transform duration-500 flex items-center gap-3">
                <div class="w-8 h-8 bg-emerald-100 rounded-full flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-check text-emerald-600"></i>
                </div>
                <span id="successMessage" class="font-semibold text-sm">Peminjaman berhasil!</span>
            </div>

            {{-- Tombol Kembali --}}
            <a href="{{ route('books.index') }}" class="inline-flex items-center gap-2 text-gray-500 hover:text-emerald-600 transition-colors text-sm font-medium mb-6 group">
                <i class="fas fa-arrow-left text-xs transition-transform group-hover:-translate-x-1"></i>
                Kembali ke Katalog
            </a>

            {{-- Kartu Utama Buku --}}
            <div class="card-glass rounded-2xl overflow-hidden shadow-sm reveal">
                {{-- Header Buku --}}
                <div class="bg-gradient-to-br from-emerald-600 to-green-700 text-white p-8 md:p-10 relative overflow-hidden">
                    <div class="absolute inset-0 bg-black/5"></div>
                    <div class="absolute top-0 right-0 w-64 h-64 bg-white/5 rounded-full -mr-32 -mt-32"></div>
                    
                    <div class="relative z-10 flex flex-col md:flex-row md:items-start gap-8">
                        {{-- COVER --}}
                        <div class="book-cover-wrapper flex-shrink-0 mx-auto md:mx-0">
                            <div class="book-cover w-36 h-52 md:w-44 md:h-64 bg-white rounded-xl overflow-hidden">
                                @if (filter_var($book->cover_image, FILTER_VALIDATE_URL))
                                    <img src="{{ $book->cover_image }}" class="w-full h-full object-cover" alt="{{ $book->title }}">
                                @elseif ($book->cover_image)
                                    <img src="{{ asset('storage/' . $book->cover_image) }}" alt="{{ $book->title }}" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full bg-gray-100 flex items-center justify-center">
                                        <i class="fas fa-book-open text-4xl text-gray-300"></i>
                                    </div>
                                @endif
                            </div>
                        </div>

                        {{-- INFO --}}
                        <div class="flex-1 text-center md:text-left">
                            <h1 class="text-3xl md:text-4xl font-extrabold tracking-tight mb-2">{{ $book->title }}</h1>
                            <p class="text-emerald-100 mb-6 text-sm">oleh <span class="font-semibold text-white">{{ $book->author }}</span></p>
                            
                            <div class="flex flex-wrap gap-2 justify-center md:justify-start">
                                @if ($book->stock > 0)
                                    <span class="bg-white/15 backdrop-blur-sm text-white px-3 py-1.5 rounded-lg text-xs font-semibold border border-white/10 flex items-center gap-1.5">
                                        <i class="fas fa-circle text-[6px] text-emerald-300"></i>Tersedia
                                    </span>
                                @else
                                    <span class="bg-red-500/20 backdrop-blur-sm text-red-200 px-3 py-1.5 rounded-lg text-xs font-semibold border border-red-400/20 flex items-center gap-1.5">
                                        <i class="fas fa-circle text-[6px] text-red-300"></i>Stok Habis
                                    </span>
                                @endif
                                <span class="bg-white/15 backdrop-blur-sm text-white px-3 py-1.5 rounded-lg text-xs font-semibold border border-white/10 flex items-center gap-1.5">
                                    <i class="fas fa-layer-group text-[10px]"></i>{{ $book->category }}
                                </span>
                                <span class="bg-white/15 backdrop-blur-sm text-white px-3 py-1.5 rounded-lg text-xs font-semibold border border-white/10 flex items-center gap-1.5">
                                    <i class="fas fa-location-dot text-[10px]"></i>Rak {{ $book->shelf_code }}
                                </span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Konten Tabs --}}
                <div class="p-6 md:p-8">
                    {{-- Tab Navigation --}}
                    <div class="border-b border-gray-100 mb-8">
                        <nav class="flex gap-6">
                            <button class="tab-btn active" data-tab="details">Detail Buku</button>
                            <button class="tab-btn" data-tab="synopsis">Sinopsis</button>
                            <button class="tab-btn" data-tab="reviews">Ulasan Pembaca</button>
                        </nav>
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                        {{-- Kolom Kiri (Tab Content) --}}
                        <div class="lg:col-span-2">
                            <div id="details" class="tab-content">
                                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                    <div class="meta-card p-4 rounded-xl">
                                        <p class="text-xs text-gray-400 font-medium uppercase tracking-wider mb-1 flex items-center gap-1.5">
                                            <i class="fas fa-building text-purple-400"></i> Penerbit
                                        </p>
                                        <p class="text-sm font-bold text-gray-800">{{ $book->publisher }}</p>
                                    </div>
                                    <div class="meta-card p-4 rounded-xl">
                                        <p class="text-xs text-gray-400 font-medium uppercase tracking-wider mb-1 flex items-center gap-1.5">
                                            <i class="fas fa-calendar text-blue-400"></i> Tahun
                                        </p>
                                        <p class="text-sm font-bold text-gray-800">{{ $book->year }}</p>
                                    </div>
                                    <div class="meta-card p-4 rounded-xl">
                                        <p class="text-xs text-gray-400 font-medium uppercase tracking-wider mb-1 flex items-center gap-1.5">
                                            <i class="fas fa-barcode text-emerald-400"></i> ISBN
                                        </p>
                                        <p class="text-sm font-bold text-gray-800">{{ $book->isbn ?? '-' }}</p>
                                    </div>
                                </div>
                            </div>
                            
                            <div id="synopsis" class="tab-content hidden">
                                <div class="bg-gray-50/50 rounded-xl p-6 border border-gray-100">
                                    <p class="text-gray-600 leading-relaxed text-sm whitespace-pre-line">{!! $book->synopsis ?? 'Sinopsis untuk buku ini belum tersedia.' !!}</p>
                                </div>
                            </div>
                            
                            {{-- ========================================== --}}
                            {{-- TAB 3: ULASAN PEMBACA --}}
                            {{-- ========================================== --}}
                            <div id="reviews" class="tab-content hidden">
                                <div class="bg-gray-50/50 rounded-xl p-6 border border-gray-100 mb-6">
                                    <h2 class="text-lg font-bold text-gray-800 mb-6 flex items-center gap-2">
                                        <div class="w-8 h-8 rounded-lg bg-emerald-100 text-emerald-600 flex items-center justify-center text-sm">
                                            <i class="fas fa-comments"></i>
                                        </div>
                                        Apa Kata Mereka?
                                    </h2>

                                    {{-- Pesan Sukses/Error --}}
                                    @if(session('success_comment'))
                                        <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm p-4 rounded-xl mb-6 flex items-start gap-3 animate-slide-down">
                                            <i class="fas fa-check-circle mt-0.5 text-emerald-500"></i> 
                                            <span>{{ session('success_comment') }}</span>
                                        </div>
                                    @endif
                                    @if(session('error_comment'))
                                        <div class="bg-red-50 border border-red-200 text-red-600 text-sm p-4 rounded-xl mb-6 flex items-start gap-3 animate-slide-down">
                                            <i class="fas fa-exclamation-triangle mt-0.5 text-red-500"></i> 
                                            <span>{{ session('error_comment') }}</span>
                                        </div>
                                    @endif
                                    @if ($errors->any())
                                        <div class="bg-red-50 border border-red-200 text-red-600 text-sm p-4 rounded-xl mb-6 animate-slide-down">
                                            @foreach ($errors->all() as $error)<div class="flex items-center gap-2 mb-1"><i class="fas fa-times"></i> {{ $error }}</div>@endforeach
                                        </div>
                                    @endif

                                    {{-- Daftar Komentar --}}
                                    <div class="space-y-4 max-h-[500px] overflow-y-auto pr-2 mb-8" id="commentsContainer">
                                        @php
                                            $comments = \App\Models\BookComment::where('book_id', $book->id)
                                                ->approved()
                                                ->latest()
                                                ->paginate(10, ['*'], 'commentPage');
                                        @endphp
                                        @forelse ($comments as $comment)
                                            <div class="bg-white p-4 rounded-xl border border-gray-100 shadow-sm">
                                                <div class="flex gap-3">
                                                    {{-- Avatar/Inisial --}}
                                                    <div class="flex-shrink-0 w-10 h-10 rounded-xl overflow-hidden flex items-center justify-center bg-emerald-50 border border-emerald-100">
                                                        @if($comment->student->avatar)
                                                            <img src="{{ asset('storage/' . $comment->student->avatar) }}" class="w-full h-full object-cover">
                                                        @else
                                                            <span class="text-emerald-600 font-bold text-sm">{{ strtoupper(substr($comment->student->name, 0, 1)) }}</span>
                                                        @endif
                                                    </div>
                                                    <div class="flex-1 min-w-0">
                                                        <div class="flex items-center justify-between mb-1">
                                                            <h4 class="text-sm font-bold text-gray-800 truncate">{{ $comment->student->name }}</h4>
                                                            <span class="text-[11px] text-gray-400 flex-shrink-0 ml-2">{{ $comment->created_at->diffForHumans() }}</span>
                                                        </div>
                                                        <p class="text-sm text-gray-600 leading-relaxed">{{ $comment->content }}</p>
                                                    </div>
                                                </div>
                                            </div>
                                        @empty
                                            <div class="text-center py-8 text-gray-400">
                                                <i class="fas fa-comment-slash text-3xl mb-2"></i>
                                                <p class="text-sm font-medium">Belum ada ulasan untuk buku ini.</p>
                                                <p class="text-xs">Jadilah yang pertama memberikan pendapat!</p>
                                            </div>
                                        @endforelse

                                        {{-- Pagination Komentar --}}
                                        @if($comments->hasPages())
                                            <div class="flex justify-center items-center gap-2 pt-4 border-t border-gray-100 mt-4">
                                                {{ $comments->links('vendor.pagination.custom-pagination') }}
                                            </div>
                                        @endif
                                    </div>

                                    {{-- Form Kirim Komentar --}}
                                    <div class="bg-white rounded-xl border border-gray-100 p-6 shadow-sm">
                                        <h3 class="text-sm font-bold text-gray-700 mb-4">Tulis Ulasan Anda</h3>
                                        @auth('student')
                                            @if (Auth::guard('student')->user()->is_active_flag)
                                                <form action="{{ route('books.comment.store', $book) }}" method="POST" class="space-y-4">
                                                    @csrf
                                                    <textarea name="content" rows="4" required
                                                        class="w-full text-sm bg-gray-50 border border-gray-200 rounded-xl p-4 outline-none focus:border-emerald-400 focus:ring-2 focus:ring-emerald-100 transition-all placeholder-gray-400 resize-none"
                                                        placeholder="Bagaimana pendapat Anda tentang buku ini? (Membantu pembaca lain dalam memilih buku)..."></textarea>
                                                    <div class="flex justify-end">
                                                        <button type="submit" class="bg-emerald-500 hover:bg-emerald-600 block w-full text-center text-white font-semibold py-2.5 rounded-xl text-sm">
                                                            <i class="fas fa-paper-plane text-xs"></i> Kirim Ulasan
                                                        </button>
                                                    </div>
                                                </form>
                                            @else
                                                <p class="text-xs text-red-400 text-center bg-red-50 p-4 rounded-lg">Akun Anda nonaktif, sehingga tidak bisa memberikan ulasan pada buku ini.</p>
                                            @endif
                                        @else
                                            <div class="text-center py-2">
                                                <p class="text-sm text-gray-500 mb-3">Silakan login terlebih dahulu untuk memberikan ulasan.</p>
                                                <a href="{{ route('student.login.form') }}" class="btn-detail text-white px-6 py-2.5 rounded-xl text-sm font-semibold inline-flex items-center gap-2">
                                                    <i class="fas fa-right-to-bracket text-xs"></i> Login
                                                </a>
                                            </div>
                                        @endauth
                                    </div>
                                </div>
                            </div>
                        </div>


                        {{-- Kolom Kanan (Stok & Info) --}}
                        <div class="lg:col-span-1">
                            <div class="sticky top-24 space-y-4">
                                {{-- Kartu Stok --}}
                                <div class="bg-emerald-50/50 border border-emerald-100 rounded-xl p-5">
                                    <div class="flex items-center justify-between mb-3">
                                        <h3 class="text-sm font-bold text-gray-800 flex items-center gap-2">
                                            <i class="fas fa-cubes text-emerald-500"></i>Ketersediaan
                                        </h3>
                                        <span class="text-xs bg-emerald-100 text-emerald-700 px-2 py-0.5 rounded-md font-bold">
                                            {{ $book->stock > 5 ? 'Aman' : 'Terbatas' }}
                                        </span>
                                    </div>
                                    
                                    <div class="flex items-baseline gap-2 mb-3">
                                        <span class="text-4xl font-extrabold text-emerald-600">{{ $book->stock }}</span>
                                        <span class="text-sm text-gray-500 font-medium">eksemplar</span>
                                    </div>
                                    
                                    <div class="w-full bg-emerald-200/50 rounded-full h-1.5 mb-5">
                                        <div class="bg-emerald-500 h-1.5 rounded-full transition-all duration-700" style="width: {{ min(100, $book->stock * 10) }}%"></div>
                                    </div>

                                    @php use App\Providers\AppServiceProvider; @endphp
                                    @if (AppServiceProvider::isLibraryOpen() && $book->stock > 0)
                                        @auth('student')
                                            @if (Auth::guard('student')->user()->is_active)
                                                <button onclick="showBorrowModal()" class="btn-borrow w-full text-white py-3 rounded-xl font-semibold text-sm flex items-center justify-center gap-2 shadow-md">
                                                    <i class="fas fa-hand-holding-heart text-xs"></i>Pinjam Buku
                                                </button>
                                            @else
                                                <button disabled class="w-full bg-gray-300 text-gray-500 py-3 rounded-xl font-semibold text-sm flex items-center justify-center gap-2 cursor-not-allowed">
                                                    <i class="fas fa-user-slash text-xs"></i>Akun Nonaktif
                                                </button>
                                            @endif
                                        @else
                                            <a href="{{ route('student.login.form') }}" class="block w-full bg-blue-600 hover:bg-blue-700 text-white py-3 rounded-xl font-semibold text-sm text-center shadow-md transition-colors">
                                                <i class="fas fa-right-to-bracket mr-2 text-xs"></i>Login untuk Meminjam
                                            </a>
                                        @endauth
                                    @else
                                        <button disabled class="w-full bg-gray-300 text-gray-500 py-3 rounded-xl font-semibold text-sm flex items-center justify-center gap-2 cursor-not-allowed">
                                            <i class="fas fa-clock text-xs"></i>{{ $book->stock > 0 ? 'Di Luar Jam Operasional' : 'Stok Habis' }}
                                        </button>
                                    @endif
                                </div>

                                {{-- Info Peminjaman --}}
                                <div class="bg-white border border-gray-100 rounded-xl p-5">
                                    <h3 class="text-sm font-bold text-gray-800 mb-3 flex items-center gap-2">
                                        <i class="fas fa-shield-halved text-gray-400"></i>Ketentuan
                                    </h3>
                                    <ul class="space-y-2.5 text-xs text-gray-500">
                                        <li class="flex gap-2.5 bg-gray-50 p-2.5 rounded-lg">
                                            <i class="fas fa-calendar-check text-emerald-400 mt-0.5 w-3 text-center"></i>
                                            <span>Maks. peminjaman <strong class="text-gray-700">7 hari</strong></span>
                                        </li>
                                        <li class="flex gap-2.5 bg-gray-50 p-2.5 rounded-lg">
                                            <i class="fas fa-coins text-amber-400 mt-0.5 w-3 text-center"></i>
                                            <span>Denda <strong class="text-gray-700">Rp1.000/hari</strong></span>
                                        </li>
                                        <li class="flex gap-2.5 bg-gray-50 p-2.5 rounded-lg">
                                            <i class="fas fa-user-check text-blue-400 mt-0.5 w-3 text-center"></i>
                                            <span>Khusus siswa <strong class="text-gray-700">terverifikasi</strong></span>
                                        </li>
                                    </ul>
                                </div>    

                                {{-- Buku Terkait --}}
                                @if(isset($relatedBooks) && $relatedBooks->count() > 0)
                                    <div class="bg-white border border-gray-100 rounded-xl p-5">
                                        <h3 class="text-sm font-bold text-gray-800 mb-3 flex items-center gap-2">
                                            <i class="fas fa-bookmark text-gray-400"></i>Buku Terkait
                                        </h3>
                                        <div class="space-y-2.5">
                                            @foreach ($relatedBooks->take(3) as $relatedBook)
                                                <a href="{{ route('books.show', $relatedBook) }}" class="flex items-center gap-3 p-2 rounded-lg hover:bg-gray-50 transition-colors group">
                                                    <div class="w-10 h-14 bg-gray-100 rounded-md overflow-hidden flex-shrink-0 shadow-sm">
                                                        @if($relatedBook->cover_image)
                                                            <img src="{{ filter_var($relatedBook->cover_image, FILTER_VALIDATE_URL) ? $relatedBook->cover_image : asset('storage/' . $relatedBook->cover_image) }}" class="w-full h-full object-cover" alt="">
                                                        @else
                                                            <div class="w-full h-full flex items-center justify-center"><i class="fas fa-book text-gray-300 text-xs"></i></div>
                                                        @endif
                                                    </div>
                                                    <div class="min-w-0 flex-1">
                                                        <h4 class="text-xs font-semibold text-gray-700 truncate group-hover:text-emerald-600 transition-colors">{{ $relatedBook->title }}</h4>
                                                        <p class="text-[10px] text-gray-400">{{ $relatedBook->author }}</p>
                                                    </div>
                                                </a>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- MODAL PINJAM BUKU --}}
    <div id="borrowModal" class="modal-backdrop fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center p-4 z-[60]">
        <div class="modal-box bg-white rounded-2xl max-w-md w-full shadow-2xl border border-gray-100 overflow-hidden">
            <div class="bg-emerald-600 p-5 text-white">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-white/20 rounded-lg flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-hand-holding-heart"></i>
                    </div>
                    <div>
                        <h3 class="font-bold text-lg">Pinjam Buku</h3>
                        <p class="text-emerald-200 text-xs">Konfirmasi peminjaman Anda</p>
                    </div>
                </div>
            </div>
            <div class="p-6">
                {{-- Info Buku di Modal --}}
                <div class="flex items-center gap-4 p-4 bg-gray-50 rounded-xl mb-5">
                    <div class="w-14 h-20 bg-white rounded-md overflow-hidden shadow-sm flex-shrink-0 border border-gray-100">
                        @if (filter_var($book->cover_image, FILTER_VALIDATE_URL))
                            <img src="{{ $book->cover_image }}" class="w-full h-full object-cover" alt="">
                        @elseif ($book->cover_image)
                            <img src="{{ asset('storage/' . $book->cover_image) }}" class="w-full h-full object-cover" alt="">
                        @else
                            <div class="w-full h-full flex items-center justify-center"><i class="fas fa-book text-gray-300"></i></div>
                        @endif
                    </div>
                    <div class="min-w-0">
                        <h4 class="font-bold text-gray-800 text-sm truncate">{{ $book->title }}</h4>
                        <p class="text-xs text-gray-500">oleh {{ $book->author }}</p>
                    </div>
                </div>

                @auth('student')
                    @if (Auth::guard('student')->user()->is_active)
                        <form id="borrowForm" action="{{ route('books.borrow', $book) }}" method="POST">
                            @csrf
                            <div class="space-y-4">
                                <div class="bg-emerald-50 border border-emerald-100 rounded-xl p-4 space-y-2.5 text-sm">
                                    <div class="flex justify-between">
                                        <span class="text-gray-500 flex items-center gap-2"><i class="fas fa-user w-3 text-center text-emerald-500"></i>Peminjam</span>
                                        <span class="font-semibold text-gray-800">{{ Auth::guard('student')->user()->name }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-500 flex items-center gap-2"><i class="fas fa-calendar w-3 text-center text-emerald-500"></i>Tgl Pinjam</span>
                                        <span class="font-semibold text-gray-800">{{ now()->format('d M Y') }}</span>
                                    </div>
                                    <div class="flex justify-between pt-2 border-t border-emerald-200">
                                        <span class="text-gray-500 flex items-center gap-2"><i class="fas fa-calendar-check w-3 text-center text-emerald-500"></i>Jatuh Tempo</span>
                                        <span class="font-bold text-emerald-600">{{ now()->addDays(7)->format('d M Y') }}</span>
                                    </div>
                                </div>
                                
                                <label class="flex items-start gap-3 cursor-pointer p-3 bg-blue-50 rounded-xl border border-blue-100">
                                    <input type="checkbox" name="terms" id="terms" required class="mt-0.5">
                                    <span class="text-xs text-blue-700 leading-relaxed">Saya setuju mengembalikan buku tepat waktu dan menjaga kondisinya dengan baik.</span>
                                </label>

                                <button type="submit" class="btn-borrow w-full text-white py-3 rounded-xl font-semibold text-sm flex items-center justify-center gap-2 shadow-md">
                                    <i class="fas fa-check-circle text-xs"></i>Konfirmasi Peminjaman
                                </button>
                            </div>
                        </form>
                    @else
                        <div class="text-center py-4">
                            <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-3">
                                <i class="fas fa-user-slash text-red-500 text-xl"></i>
                            </div>
                            <p class="text-gray-700 text-sm font-bold mb-1">Akun Nonaktif</p>
                            <p class="text-gray-400 text-xs">Hubungi administrator untuk mengaktifkan akun Anda.</p>
                        </div>
                    @endif
                @else
                    <div class="text-center py-2">
                        <p class="text-gray-500 text-sm mb-4">Silakan login atau daftar untuk meminjam buku ini.</p>
                        <div class="flex gap-2">
                            <a href="{{ route('student.login.form') }}" class="flex-1 bg-emerald-600 hover:bg-emerald-700 text-white py-2.5 rounded-xl font-semibold text-sm text-center transition-colors">Login</a>
                            <a href="{{ route('student.register.form') }}" class="flex-1 bg-white border border-gray-200 text-gray-700 hover:bg-gray-50 py-2.5 rounded-xl font-semibold text-sm text-center transition-colors">Daftar</a>
                        </div>
                    </div>
                @endauth
            </div>
        </div>
    </div>

    @section('scripts')
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // --- Scroll Reveal ---
        const revealEl = document.querySelector('.reveal');
        if(revealEl) {
            const observer = new IntersectionObserver((entries) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        entry.target.classList.add('visible');
                        observer.unobserve(entry.target);
                    }
                });
            }, { threshold: 0.1 });
            observer.observe(revealEl);
        }

        // --- Tabs Logic ---
        const tabBtns = document.querySelectorAll('.tab-btn');
        const tabContents = document.querySelectorAll('.tab-content');

        tabBtns.forEach(btn => {
            btn.addEventListener('click', () => {
                tabBtns.forEach(b => b.classList.remove('active'));
                tabContents.forEach(c => c.classList.add('hidden'));
                
                btn.classList.add('active');
                const target = document.getElementById(btn.dataset.tab);
                if(target) target.classList.remove('hidden');
            });
        });

        // --- Modal Logic ---
        const borrowModal = document.getElementById('borrowModal');
        const borrowForm = document.getElementById('borrowForm');

        window.showBorrowModal = function() {
            borrowModal.classList.add('show');
            document.body.style.overflow = 'hidden';
        }

        window.closeBorrowModal = function() {
            borrowModal.classList.remove('show');
            document.body.style.overflow = '';
        }

        borrowModal.addEventListener('click', (e) => {
            if (e.target === borrowModal) closeBorrowModal();
        });

        // --- Form Submit & Loading State ---
        if(borrowForm) {
            borrowForm.addEventListener('submit', function(e) {
                e.preventDefault(); // Hentikan submit biasa
                
                closeBorrowModal();
                document.getElementById('loadingState').classList.remove('hidden');
                document.body.style.overflow = 'hidden';

                // Kirim data via Fetch API
                fetch(this.action, {
                    method: 'POST',
                    body: new FormData(this),
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    document.getElementById('loadingState').classList.add('hidden');
                    document.body.style.overflow = '';
                    
                    if(data.success) {
                        // Tampilkan Notifikasi Sukses
                        const notif = document.getElementById('successNotification');
                        notif.classList.remove('hidden');
                        setTimeout(() => notif.style.transform = 'translateX(0)', 10);
                        
                        // Redirect setelah 2 detik
                        setTimeout(() => {
                            window.location.href = data.redirect || '{{ route("student.dashboard") }}';
                        }, 2000);
                    } else {
                        alert(data.message || 'Gagal meminjam buku.');
                    }
                })
                .catch(() => {
                    document.getElementById('loadingState').classList.add('hidden');
                    document.body.style.overflow = '';
                    alert('Terjadi kesalahan jaringan.');
                });
            });
        }
    });
    </script>
    @stop 

@endsection