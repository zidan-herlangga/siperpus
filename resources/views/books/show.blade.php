@extends('layouts.app')

@section('title', $book->title . ' - ' . config('app.name'))

@section('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/books-show.css') }}" media="print" onload="this.media='all'"
        fetchpriority="low">
    <noscript>
        <link rel="stylesheet" href="{{ asset('assets/css/books-show.css') }}">
    </noscript>
    <style>
        /* CSS UTAMA (LUAR SHADOW DOM) */
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

        .book-cover-wrapper {
            perspective: 1000px;
        }

        .book-cover {
            box-shadow: -8px 8px 20px rgba(0, 0, 0, 0.15), 0 0 0 1px rgba(0, 0, 0, 0.05);
            transition: transform 0.4s cubic-bezier(0.22, 1, 0.36, 1);
        }

        .book-cover-wrapper:hover .book-cover {
            transform: rotateY(-5deg) scale(1.02);
        }

        .tab-btn {
            position: relative;
            padding-bottom: 0.75rem;
            color: #6b7280;
            transition: color 0.3s ease;
            cursor: pointer;
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

        .tab-btn:hover {
            color: #374151;
        }

        .tab-btn.active {
            color: #059669;
            font-weight: 600;
        }

        .tab-btn.active::after {
            width: 100%;
        }

        .meta-card {
            background: white;
            border: 1px solid #f3f4f6;
            transition: all 0.3s ease;
        }

        .meta-card:hover {
            border-color: #e5e7eb;
            box-shadow: 0 4px 12px -2px rgba(0, 0, 0, 0.05);
        }

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

            {{-- HOST SHADOW DOM --}}
            <div id="book-show-shadow-host" style="display: contents;"></div>

            {{-- KONTEN UTAMA --}}
            <a href="{{ route('books.index') }}" wire:navigate.prefetch="false"
                class="inline-flex items-center gap-2 text-gray-500 hover:text-emerald-600 transition-colors text-sm font-medium mb-6 group">
                <i class="fas fa-arrow-left text-xs transition-transform group-hover:-translate-x-1"></i>
                Kembali ke Katalog
            </a>

            <div class="card-glass rounded-2xl overflow-hidden shadow-sm reveal">
                {{-- Header Buku --}}
                <div
                    class="bg-gradient-to-br from-emerald-600 to-emerald-700 text-white p-8 md:p-10 relative overflow-hidden">
                    <div class="absolute inset-0 bg-black/5"></div>
                    <div class="absolute top-0 right-0 w-64 h-64 bg-white/5 rounded-full -mr-32 -mt-32"></div>
                    <div class="relative z-10 flex flex-col md:flex-row md:items-start gap-8">
                        <div class="book-cover-wrapper flex-shrink-0 mx-auto md:mx-0">
                            <div class="book-cover w-36 h-52 md:w-44 md:h-64 bg-white rounded-xl overflow-hidden">
                                @if (filter_var($book->cover_image, FILTER_VALIDATE_URL))
                                    <img src="{{ $book->cover_image }}" class="w-full h-full object-cover"
                                        alt="{{ $book->title }}" loading="lazy">
                                @elseif ($book->cover_image)
                                    <img src="{{ asset('storage/' . $book->cover_image) }}" alt="{{ $book->title }}"
                                        class="w-full h-full object-cover" loading="lazy">
                                @else
                                    <div class="w-full h-full bg-gray-100 flex items-center justify-center">
                                        <i class="fas fa-book-open text-4xl text-gray-300"></i>
                                    </div>
                                @endif
                            </div>
                        </div>
                        <div class="flex-1 text-center md:text-left">
                            <h1 class="text-3xl md:text-4xl font-extrabold tracking-tight mb-2">{{ $book->title }}</h1>
                            <p class="text-emerald-100 mb-6 text-sm">oleh <span
                                    class="font-semibold text-white">{{ $book->author }}</span></p>
                            <div class="flex flex-wrap gap-2 justify-center md:justify-start">
                                @if ($book->stock > 0)
                                    <span
                                        class="bg-white/15 backdrop-blur-sm text-white px-3 py-1.5 rounded-lg text-xs font-semibold border border-white/10 flex items-center gap-1.5"><i
                                            class="fas fa-circle text-[6px] text-emerald-300"></i>Tersedia</span>
                                @else
                                    <span
                                        class="bg-red-500/20 backdrop-blur-sm text-red-200 px-3 py-1.5 rounded-lg text-xs font-semibold border border-red-400/20 flex items-center gap-1.5"><i
                                            class="fas fa-circle text-[6px] text-red-300"></i>Stok Habis</span>
                                @endif
                                <span
                                    class="bg-white/15 backdrop-blur-sm text-white px-3 py-1.5 rounded-lg text-xs font-semibold border border-white/10 flex items-center gap-1.5"><i
                                        class="fas fa-layer-group text-[10px]"></i>{{ $book->category->name ?? '-' }}</span>
                                <span
                                    class="bg-white/15 backdrop-blur-sm text-white px-3 py-1.5 rounded-lg text-xs font-semibold border border-white/10 flex items-center gap-1.5"><i
                                        class="fas fa-location-dot text-[10px]"></i>Rak {{ $book->shelf_code }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Konten Tabs --}}
                <div class="p-6 md:p-8">
                    <div class="border-b border-gray-100 mb-8">
                        <nav class="flex gap-6">
                            <button class="tab-btn active" data-tab="details">Detail Buku</button>
                            <button class="tab-btn" data-tab="synopsis">Sinopsis</button>
                            <button class="tab-btn" data-tab="reviews">Ulasan Pembaca</button>
                        </nav>
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                        <div class="lg:col-span-2">
                            {{-- TAB DETAILS --}}
                            <div id="details" class="tab-content">
                                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                                    <div class="meta-card p-4 rounded-xl">
                                        <p
                                            class="text-xs text-gray-400 font-medium uppercase tracking-wider mb-1 flex items-center gap-1.5">
                                            <i class="fas fa-building text-purple-400"></i> Penerbit
                                        </p>
                                        <p class="text-sm font-bold text-gray-800">{{ $book->publisher }}</p>
                                    </div>
                                    <div class="meta-card p-4 rounded-xl">
                                        <p
                                            class="text-xs text-gray-400 font-medium uppercase tracking-wider mb-1 flex items-center gap-1.5">
                                            <i class="fas fa-calendar text-blue-400"></i> Tahun
                                        </p>
                                        <p class="text-sm font-bold text-gray-800">{{ $book->year }}</p>
                                    </div>
                                    <div class="meta-card p-4 rounded-xl">
                                        <p
                                            class="text-xs text-gray-400 font-medium uppercase tracking-wider mb-1 flex items-center gap-1.5">
                                            <i class="fas fa-barcode text-emerald-400"></i> ISBN
                                        </p>
                                        <p class="text-sm font-bold text-gray-800">{{ $book->isbn ?? '-' }}</p>
                                    </div>
                                </div>
                            </div>

                            {{-- TAB SINOPSIS --}}
                            <div id="synopsis" class="tab-content hidden">
                                <div class="bg-gray-50/50 rounded-xl p-6 border border-gray-100">
                                    <p class="text-gray-600 leading-relaxed text-sm whitespace-pre-line">
                                        {{ $book->synopsis ?? 'Sinopsis untuk buku ini belum tersedia.' }}</p>
                                </div>
                            </div>

                            {{-- TAB REVIEWS --}}
                            <div id="reviews" class="tab-content hidden">
                                <div class="bg-gray-50/50 rounded-xl p-6 border border-gray-100 mb-6">
                                    <h2 class="text-lg font-bold text-gray-800 mb-6 flex items-center gap-2">
                                        <div
                                            class="w-8 h-8 rounded-lg bg-emerald-100 text-emerald-600 flex items-center justify-center text-sm">
                                            <i class="fas fa-comments"></i>
                                        </div>Apa Kata Mereka?
                                    </h2>

                                    @if (session('success_comment'))
                                        <div
                                            class="bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm p-4 rounded-xl mb-6 flex items-start gap-3">
                                            <i
                                                class="fas fa-check-circle mt-0.5 text-emerald-500"></i><span>{{ session('success_comment') }}</span>
                                        </div>
                                    @endif
                                    @if (session('error_comment'))
                                        <div
                                            class="bg-red-50 border border-red-200 text-red-600 text-sm p-4 rounded-xl mb-6 flex items-start gap-3">
                                            <i
                                                class="fas fa-exclamation-triangle mt-0.5 text-red-500"></i><span>{{ session('error_comment') }}</span>
                                        </div>
                                    @endif
                                    @if ($errors->any())
                                        <div
                                            class="bg-red-50 border border-red-200 text-red-600 text-sm p-4 rounded-xl mb-6">
                                            @foreach ($errors->all() as $error)
                                                <div class="flex items-center gap-2 mb-1"><i class="fas fa-times"></i>
                                                    {{ $error }}</div>
                                            @endforeach
                                        </div>
                                    @endif

                                    <div class="space-y-4 max-h-[500px] overflow-y-auto pr-2 mb-8" id="commentsContainer">
                                        @forelse ($comments as $comment)
                                            <div class="bg-white p-4 rounded-xl border border-gray-100 shadow-sm">
                                                <div class="flex gap-3">
                                                    <div
                                                        class="flex-shrink-0 w-10 h-10 rounded-xl overflow-hidden flex items-center justify-center bg-emerald-50 border border-emerald-100">
                                                        @if ($comment->student->avatar)
                                                            <img src="{{ asset('storage/' . $comment->student->avatar) }}"
                                                            class="w-full h-full object-cover" loading="lazy">@else<span
                                                                class="text-emerald-600 font-bold text-sm">{{ strtoupper(substr($comment->student->name, 0, 1)) }}</span>
                                                        @endif
                                                    </div>
                                                    <div class="flex-1 min-w-0">
                                                        <div class="flex items-center justify-between mb-1">
                                                            <h4 class="text-sm font-bold text-gray-800 truncate">
                                                                {{ $comment->student->name }}</h4>
                                                            <span
                                                                class="text-[11px] text-gray-400 flex-shrink-0 ml-2">{{ $comment->created_at->diffForHumans() }}</span>
                                                        </div>
                                                        <p class="text-sm text-gray-600 leading-relaxed">
                                                            {{ $comment->content }}</p>
                                                    </div>
                                                </div>
                                            </div>
                                        @empty
                                            <div class="text-center py-8 text-gray-400"><i
                                                    class="fas fa-comment-slash text-3xl mb-2"></i>
                                                <p class="text-sm font-medium">Belum ada ulasan untuk buku ini.</p>
                                            </div>
                                        @endforelse
                                        @if ($comments->hasPages())
                                            <div
                                                class="flex justify-center items-center gap-2 pt-4 border-t border-gray-100 mt-4">
                                                {{ $comments->links('vendor.pagination.custom-pagination') }}</div>
                                        @endif
                                    </div>

                                </div>
                                <div class="bg-white rounded-xl border border-gray-100 p-6 shadow-sm">
                                    <h3 class="text-sm font-bold text-gray-700 mb-4">Tulis Ulasan Anda</h3>
                                    @auth('student')
                                        @if (Auth::guard('student')->user()->is_active_flag)
                                            <form action="{{ route('books.comment.store', $book) }}" method="POST"
                                                class="space-y-4">@csrf<input type="hidden" name="is_livewire_navigate"
                                                    value="1">
                                                <textarea name="content" rows="4" required
                                                    class="w-full text-sm bg-gray-50 border border-gray-200 rounded-xl p-4 outline-none focus:border-emerald-400 focus:ring-2 focus:ring-emerald-100 transition-all placeholder-gray-400 resize-none"
                                                    placeholder="Bagaimana pendapat Anda tentang buku ini?"></textarea>
                                                <div class="flex justify-end"><button type="submit"
                                                        class="bg-emerald-500 hover:bg-emerald-600 block w-full text-center text-white font-semibold py-2.5 rounded-xl text-sm"><i
                                                            class="fas fa-paper-plane text-xs"></i> Kirim Ulasan</button>
                                                </div>
                                            </form>
                                        @else
                                            <p class="text-xs text-red-400 text-center bg-red-50 p-4 rounded-lg">Akun Anda
                                                nonaktif.</p>
                                        @endif
                                    @else
                                        <div class="text-center py-2">
                                            <p class="text-sm text-gray-500 mb-3">Silakan login terlebih dahulu.</p>
                                        </div>
                                    @endauth
                                </div>
                            </div>
                        </div>

                        {{-- Kolom Kanan (Action) --}}
                        <div class="lg:col-span-1">
                            <div class="sticky top-24 space-y-4">
                                @php
                                    $activeBorrowed = $book
                                        ->borrowings()
                                        ->whereIn('status', ['Dipinjam', 'Pending'])
                                        ->count();
                                    $userHasPending = Auth::guard('student')->check()
                                        ? $book
                                            ->borrowings()
                                            ->where('student_id', Auth::guard('student')->id())
                                            ->whereIn('status', ['Pending', 'Dipinjam'])
                                            ->exists()
                                        : false;
                                    $isOutOfStock = $book->stock < 1;
                                @endphp
                                <div class="bg-emerald-50/50 border border-emerald-100 rounded-xl p-5">
                                    <div class="flex items-center justify-between mb-3">
                                        <h3 class="text-sm font-bold text-gray-800 flex items-center gap-2"><i
                                                class="fas fa-cubes text-emerald-500"></i>Ketersediaan</h3><span
                                            class="text-xs {{ !$isOutOfStock ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700' }} px-2 py-0.5 rounded-md font-bold">{{ !$isOutOfStock ? ($book->stock > 5 ? 'Aman' : 'Terbatas') : 'Habis' }}</span>
                                    </div>
                                    <div class="flex items-baseline gap-2 mb-3"><span
                                            class="text-4xl font-extrabold {{ !$isOutOfStock ? 'text-emerald-600' : 'text-amber-500' }}">{{ $book->stock }}</span><span
                                            class="text-sm text-gray-500 font-medium">eksemplar tersisa</span></div>
                                    <div class="w-full bg-emerald-200/50 rounded-full h-1.5 mb-4">
                                        <div class="{{ !$isOutOfStock ? 'bg-emerald-500' : 'bg-amber-400' }} h-1.5 rounded-full"
                                            style="width: {{ min(100, $book->stock * 10) }}%"></div>
                                    </div>

                                    @if ($activeBorrowed > 0)
                                        <div class="text-xs text-gray-500 mb-4"><span
                                                class="font-semibold text-gray-700">{{ $activeBorrowed }}</span> buku
                                            sedang dipinjam</div>
                                    @endif

                                    @php use App\Providers\AppServiceProvider; @endphp
                                    @auth('student')
                                        @if (!Auth::guard('student')->user()->is_active_flag)
                                            <button disabled
                                                class="w-full bg-gray-300 text-gray-500 py-3 rounded-xl font-semibold text-sm cursor-not-allowed"><i
                                                    class="fas fa-user-slash text-xs mr-1"></i>Akun Nonaktif</button>
                                        @elseif ($userHasPending)
                                            <button disabled
                                                class="w-full bg-gray-200 text-gray-400 py-3 rounded-xl font-semibold text-sm cursor-not-allowed"><i
                                                    class="fas fa-check-circle text-xs mr-1"></i>Sudah Diajukan</button>
                                        @elseif (AppServiceProvider::isLibraryOpen())
                                            @if ($isOutOfStock)
                                                <button disabled
                                                    class="w-full bg-gray-300 text-gray-500 py-3 rounded-xl font-semibold text-sm cursor-not-allowed"><i
                                                        class="fas fa-circle-exclamation text-xs mr-1"></i>Stok Habis</button>
                                            @else
                                                <button onclick="showBorrowModal()"
                                                    class="btn-borrow w-full text-white py-3 rounded-xl font-semibold text-sm flex items-center justify-center gap-2 shadow-md"><i
                                                        class="fas fa-hand-holding-heart text-xs"></i>Pinjam Buku</button>
                                            @endif
                                        @else
                                            <button disabled
                                                class="w-full bg-gray-300 text-gray-500 py-3 rounded-xl font-semibold text-sm cursor-not-allowed"><i
                                                    class="fas fa-clock text-xs mr-1"></i>Di Luar Jam Operasional</button>
                                        @endif
                                    @else
                                        <a href="{{ route('student.login.form') }}" wire:navigate.prefetch="false"
                                            class="btn-borrow block w-full text-white py-3 rounded-xl font-semibold text-sm text-center shadow-md"><i
                                                class="fas fa-right-to-bracket mr-2 text-xs"></i>Login untuk Meminjam</a>
                                    @endauth
                                </div>

                                <div class="bg-white border border-gray-100 rounded-xl p-5">
                                    <h3 class="text-sm font-bold text-gray-800 mb-3 flex items-center gap-2"><i
                                            class="fas fa-shield-halved text-gray-400"></i>Ketentuan</h3>
                                    <ul class="space-y-2.5 text-xs text-gray-500">
                                        <li class="flex gap-2.5 bg-gray-50 p-2.5 rounded-lg"><i
                                                class="fas fa-calendar-check text-emerald-400 mt-0.5 w-3 text-center"></i><span>Maks.
                                                peminjaman <strong
                                                    class="text-gray-700">{{ config('library.borrow_duration_days', 7) }}
                                                    hari</strong></span></li>
                                        <li class="flex gap-2.5 bg-gray-50 p-2.5 rounded-lg"><i
                                                class="fas fa-coins text-amber-400 mt-0.5 w-3 text-center"></i><span>Denda
                                                <strong class="text-gray-700">Rp
                                                    {{ number_format(config('library.fine_per_day', 1000), 0, ',', '.') }}/hari</strong></span>
                                        </li>
                                        <li class="flex gap-2.5 bg-gray-50 p-2.5 rounded-lg"><i
                                                class="fas fa-user-check text-blue-400 mt-0.5 w-3 text-center"></i><span>Khusus
                                                siswa <strong class="text-gray-700">terverifikasi</strong></span></li>
                                    </ul>
                                </div>
                                @if (isset($relatedBooks) && $relatedBooks->count() > 0)
                                    <div class="bg-white border border-gray-100 rounded-xl p-5">
                                        <h3 class="text-sm font-bold text-gray-800 mb-3 flex items-center gap-2"><i
                                                class="fas fa-bookmark text-gray-400"></i>Buku Terkait</h3>
                                        <div class="space-y-2.5">
                                            @foreach ($relatedBooks->take(3) as $relatedBook)
                                                <a href="{{ route('books.show', $relatedBook) }}"
                                                    wire:navigate.prefetch="false"
                                                    class="flex items-center gap-3 p-2 rounded-lg hover:bg-gray-50 transition-colors group">
                                                    <div
                                                        class="w-10 h-14 bg-gray-100 rounded-md overflow-hidden flex-shrink-0 shadow-sm">
                                                        @if ($relatedBook->cover_image)
                                                            <img src="{{ filter_var($relatedBook->cover_image, FILTER_VALIDATE_URL) ? $relatedBook->cover_image : asset('storage/' . $relatedBook->cover_image) }}"
                                                                class="w-full h-full object-cover" alt=""
                                                                loading="lazy">
                                                        @else
                                                            <div class="w-full h-full flex items-center justify-center"><i
                                                                    class="fas fa-book text-gray-300 text-xs"></i></div>
                                                        @endif
                                                    </div>
                                                    <div class="min-w-0 flex-1">
                                                        <h4
                                                            class="text-xs font-semibold text-gray-700 truncate group-hover:text-emerald-600 transition-colors">
                                                            {{ $relatedBook->title }}</h4>
                                                        <p class="text-[10px] text-gray-400">{{ $relatedBook->author }}
                                                        </p>
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
@endsection

@section('scripts')
    {{-- Elemen Loading --}}
    <div id="loadingState"
        class="hidden fixed inset-0 bg-black/40 backdrop-blur-sm z-[100] flex items-center justify-center">
        <div class="bg-white p-6 rounded-2xl shadow-2xl flex flex-col items-center gap-3">
            <div class="w-10 h-10 border-4 border-emerald-200 border-t-emerald-600 rounded-full animate-spin"></div>
            <p class="text-sm font-semibold text-gray-700">Memproses Peminjaman...</p>
        </div>
    </div>

    {{-- Library html2canvas --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>

    <script>
        // PREPARE DATA USER (Sanitasi Output Blade untuk mencegah error JS kutip)
        window.studentData = {
            name: "{{ auth('student')->check() ? e(auth('student')->user()->name) : 'Tamu' }}",
            class: "{{ auth('student')->check() ? e(auth('student')->user()->class ?? '-') : '-' }}",
            nis: "{{ auth('student')->check() ? e(auth('student')->user()->nis ?? '-') : '-' }}"
        };

        // INISIALISASI SHADOW DOM MODAL
        (function() {
            const host = document.getElementById('book-show-shadow-host');
            if (!host) return;

            const shadow = host.attachShadow({
                mode: 'open'
            });

            // CSS Internal Shadow DOM
            const style = `
        <style>
            .modal-backdrop { opacity: 0; visibility: hidden; transition: all 0.3s ease; position: fixed; inset: 0; background: rgba(0,0,0,0.5); backdrop-filter: blur(4px); display: flex; align-items: center; justify-content: center; padding: 1rem; z-index: 9999; }
            .modal-backdrop.show { opacity: 1; visibility: visible; }
            .modal-box { transform: scale(0.95) translateY(10px); transition: all 0.3s cubic-bezier(0.22, 1, 0.36, 1); background: white; border-radius: 1rem; max-width: 28rem; width: 100%; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.25); border: 1px solid #f3f4f6; overflow: hidden; }
            .modal-backdrop.show .modal-box { transform: scale(1) translateY(0); }

            .ticket-backdrop { opacity: 0; visibility: hidden; transition: all 0.4s ease; position: fixed; inset: 0; background: rgba(0,0,0,0.8); display: flex; align-items: center; justify-content: center; padding: 1rem; z-index: 10000; }
            .ticket-backdrop.show { opacity: 1; visibility: visible; }
            
            .ticket-card {
                background: #ffffff; width: 100%; max-width: 380px; border-radius: 16px; 
                box-shadow: 0 20px 60px rgba(0,0,0,0.3); overflow: visible;
                transform: scale(0.8) translateY(30px); transition: all 0.5s cubic-bezier(0.22, 1, 0.36, 1);
                font-family: system-ui, -apple-system, sans-serif;
                position: relative;
            }
            .ticket-card::before, .ticket-card::after {
                content: ''; position: absolute; left: 50%; width: 28px; height: 28px;
                background: #1a1a2e; border-radius: 50%; transform: translateX(-50%); z-index: 2;
            }
            .ticket-card::before { top: -14px; }
            .ticket-card::after { bottom: -14px; }
            .ticket-backdrop.show .ticket-card { transform: scale(1) translateY(0); }
            .ticket-header {
                background: linear-gradient(135deg, #059669 0%, #047857 50%, #065f46 100%);
                padding: 1.75rem 1.5rem 1.25rem; text-align: center; color: white;
                position: relative; border-radius: 16px 16px 0 0;
            }
            .ticket-header::after {
                content: ''; position: absolute; bottom: 0; left: 0; right: 0;
                height: 20px;
                background: radial-gradient(circle at 0 100%, #1a1a2e 0, #1a1a2e 4px, transparent 4px, transparent) repeat-x;
                background-size: 16px 20px;
            }
            .ticket-body { padding: 1.5rem 1.5rem 1rem; background: #ffffff; }
            .ticket-body::before {
                content: ''; display: block; height: 14px; margin: -1.5rem -1.5rem 1rem;
                background: radial-gradient(circle at 0 0, #1a1a2e 0, #1a1a2e 4px, transparent 4px, transparent) repeat-x;
                background-size: 16px 14px;
            }
            .ticket-divider { border: none; border-top: 2px dashed #d1d5db; margin: 1rem 0; position: relative; }
            .info-row { display: flex; justify-content: space-between; margin-bottom: 0.625rem; font-size: 0.8125rem; }
            .info-label { color: #6b7280; }
            .info-value { color: #111827; font-weight: 700; text-align: right; max-width: 60%; }
            .ticket-footer {
                background: #f9fafb; padding: 1rem 1.5rem; text-align: center;
                border-top: 2px dashed #d1d5db; position: relative;
            }
            .ticket-footer::before {
                content: ''; position: absolute; top: -16px; left: 50%; transform: translateX(-50%);
                width: 28px; height: 28px; background: #f9fafb; border-radius: 50%; z-index: 1;
            }
            .barcode-wrap { display: flex; justify-content: center; gap: 3px; margin-bottom: 0.5rem; }
            .barcode-bar { width: 2px; background: #111827; }
            
            .toast-container { position: fixed; top: 1.25rem; right: 1.25rem; z-index: 99999; display: flex; flex-direction: column; gap: 0.5rem; pointer-events: none; }
            .toast { pointer-events: auto; display: flex; align-items: flex-start; gap: 0.75rem; padding: 1rem 1.25rem; border-radius: 12px; box-shadow: 0 10px 30px rgba(0,0,0,0.15); min-width: 280px; max-width: 400px; transform: translateX(120%); opacity: 0; transition: all 0.4s cubic-bezier(0.22, 1, 0.36, 1); font-family: system-ui, -apple-system, sans-serif; }
            .toast.show { transform: translateX(0); opacity: 1; }
            .toast-error { background: #fef2f2; border: 1px solid #fecaca; }
            .toast-success { background: #f0fdf4; border: 1px solid #bbf7d0; }
            .toast-icon { width: 24px; height: 24px; border-radius: 8px; display: flex; align-items: center; justify-content: center; flex-shrink: 0; font-size: 0.75rem; margin-top: 1px; }
            .toast-error .toast-icon { background: #fee2e2; color: #dc2626; }
            .toast-success .toast-icon { background: #dcfce7; color: #16a34a; }
            .toast-body { flex: 1; min-width: 0; }
            .toast-title { font-weight: 700; font-size: 0.8125rem; color: #1f2937; margin-bottom: 0.125rem; }
            .toast-message { font-size: 0.75rem; color: #6b7280; line-height: 1.4; }
            .toast-close { background: none; border: none; cursor: pointer; color: #9ca3af; padding: 0; font-size: 1rem; line-height: 1; flex-shrink: 0; margin-left: auto; }
            .toast-close:hover { color: #6b7280; }

            .ticket-actions { padding: 1rem 1.5rem 1.5rem; display: flex; flex-direction: column; gap: 0.625rem; }
            .btn-save { background: #059669; color: white; padding: 0.875rem; border-radius: 0.75rem; font-weight: 700; font-size: 0.875rem; border: none; cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 0.5rem; transition: all 0.2s; box-shadow: 0 4px 12px rgba(5,150,105,0.3); }
            .btn-save:hover { background: #047857; transform: translateY(-1px); box-shadow: 0 6px 16px rgba(5,150,105,0.4); }
            .btn-close-ticket { background: transparent; color: #6b7280; padding: 0.75rem; border-radius: 0.75rem; font-weight: 600; font-size: 0.875rem; border: 1px solid #e5e7eb; cursor: pointer; transition: all 0.2s; }
            .btn-close-ticket:hover { background: #f9fafb; color: #374151; border-color: #d1d5db; }
        </style>
    `;

            // HTML Internal Shadow DOM
            const html = `
        <!-- MODAL KONFIRMASI -->
        <div id="borrowModalShadow" class="modal-backdrop">
            <div class="modal-box">
                <div style="background:linear-gradient(135deg,#059669,#047857);padding:1rem;color:white;">
                    <div style="display:flex;align-items:center;gap:0.75rem;">
                        <div><h3 style="font-weight:700;font-size:1.125rem;">Pinjam Buku</h3><p style="font-size:0.75rem;color:#a7f3d0;">Konfirmasi peminjaman Anda</p></div>
                    </div>
                </div>
                <div style="padding:1.5rem;">
                    <div style="display:flex;align-items:center;gap:1rem;padding:1rem;background:#f9fafb;border-radius:0.75rem;margin-bottom:1.25rem;">
                        <div style="width:3.5rem;height:5rem;background:white;border-radius:0.375rem;overflow:hidden;flex-shrink:0;border:1px solid #f3f4f6;">
                            @if (filter_var($book->cover_image, FILTER_VALIDATE_URL))
                                <img src="{{ $book->cover_image }}" style="width:100%;height:100%;object-fit:cover;" crossorigin="anonymous" loading="lazy">
                            @elseif ($book->cover_image)
                                <img src="{{ asset('storage/' . $book->cover_image) }}" style="width:100%;height:100%;object-fit:cover;" loading="lazy">
                            @else
                                <div style="width:100%;height:100%;display:flex;align-items:center;justify-content:center;background:#f3f4f6;"><i class="fas fa-book" style="color:#d1d5db;"></i></div>
                            @endif
                        </div>
                        <div style="min-width:0;">
                            <h4 style="font-weight:700;font-size:0.875rem;color:#1f2937;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">{{ $book->title }}</h4>
                            <p style="font-size:0.75rem;color:#6b7280;">oleh {{ $book->author }}</p>
                        </div>
                    </div>
                    @auth('student')
                        @if (Auth::guard('student')->user()->is_active_flag)
                            <form id="borrowFormShadow" action="{{ route('books.borrow', $book) }}" method="POST">
                                @csrf
                                <div style="margin-bottom:1rem;background:#ecfdf5;border:1px solid #d1fae5;border-radius:0.75rem;padding:1rem;font-size:0.875rem;">
                                    <div style="display:flex;justify-content:space-between;margin-bottom:0.625rem;"><span style="color:#6b7280;">Peminjam</span><span style="font-weight:600;color:#1f2937;">{{ auth('student')->user()->name }}</span></div>
                                    <div style="display:flex;justify-content:space-between;margin-bottom:0.625rem;"><span style="color:#6b7280;">Tgl Pinjam</span><span style="font-weight:600;color:#1f2937;">{{ now()->format('d M Y') }}</span></div>
                                    <div style="display:flex;justify-content:space-between;padding-top:0.625rem;border-top:1px solid #a7f3d0;"><span style="color:#6b7280;">Jatuh Tempo</span><span style="font-weight:700;color:#059669;">{{ now()->addDays((int) config('library.borrow_duration_days', 7))->format('d M Y') }}</span></div>
                                </div>
                                <label style="display:flex;align-items:flex-start;gap:0.75rem;cursor:pointer;padding:0.75rem;background:#ecfdf5;border-radius:0.75rem;border:1px solid #d1fae5;margin-bottom:1rem;">
                                    <input type="checkbox" name="terms" required style="margin-top:2px; width:18px; height:18px; accent-color:#059669;">
                                    <span style="font-size:0.75rem;color:#047857;line-height:1.5;">Saya setuju mengembalikan buku tepat waktu dan menjaga kondisinya dengan baik.</span>
                                </label>
                                <button type="submit" style="background:linear-gradient(135deg,#059669,#047857);width:100%;color:white;padding:0.75rem;border-radius:0.75rem;font-weight:600;font-size:0.875rem;border:none;cursor:pointer;display:flex;align-items:center;justify-content:center;gap:0.5rem;box-shadow:0 4px 6px -1px rgba(0,0,0,0.1);"> <i class="fas fa-check-circle" style="font-size:0.75rem;"></i> Konfirmasi Peminjaman </button>
                            </form>
                        @else
                            <div style="text-align:center;padding:1rem;"><div style="width:4rem;height:4rem;background:#fee2e2;border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 0.75rem;"><i class="fas fa-user-slash" style="color:#ef4444;font-size:1.25rem;"></i></div><p style="color:#374151;font-size:0.875rem;font-weight:700;margin-bottom:0.25rem;">Akun Nonaktif</p><p style="color:#9ca3af;font-size:0.75rem;">Hubungi administrator.</p></div>
                        @endif
                    @else
                        <div style="text-align:center;padding:0.5rem;"><p style="color:#6b7280;font-size:0.875rem;margin-bottom:1rem;">Silakan login atau daftar untuk meminjam buku ini.</p><div style="display:flex;gap:0.5rem;"><a href="{{ route('student.login.form') }}" style="flex:1;background:#059669;color:white;padding:0.625rem;border-radius:0.75rem;font-weight:600;font-size:0.875rem;text-align:center;text-decoration:none;">Login</a><a href="{{ route('student.register.form') }}" style="flex:1;background:white;border:1px solid #e5e7eb;color:#374151;padding:0.625rem;border-radius:0.75rem;font-weight:600;font-size:0.875rem;text-align:center;text-decoration:none;">Daftar</a></div></div>
                    @endauth
                </div>
            </div>
        </div>

        <!-- MODAL TIKET SUKSES -->
        <div id="ticketModalShadow" class="ticket-backdrop">
            <div class="ticket-card" id="ticketCaptureArea">
                <div class="ticket-header">
                    <div style="display:flex;align-items:center;justify-content:center;gap:0.5rem;margin-bottom:0.5rem;">
                        <i class="fas fa-book-open-reader" style="font-size:1.25rem;opacity:0.6;"></i>
                        <span style="font-size:0.75rem;font-weight:700;letter-spacing:3px;opacity:0.7;">{{ strtoupper(config('app.name', 'PERPUSTAKAAN')) }}</span>
                    </div>
                    <h2 style="font-size:1.5rem;font-weight:900;margin:0;letter-spacing:-0.5px;text-shadow:0 2px 4px rgba(0,0,0,0.1);">BUKTI PEMINJAMAN</h2>
                    <div style="display:flex;align-items:center;justify-content:center;gap:0.5rem;margin-top:0.5rem;">
                        <span style="font-size:0.7rem;opacity:0.75;">#{{ strtoupper(config('app.name', 'PUS')) }}</span>
                        <span style="width:4px;height:4px;background:rgba(255,255,255,0.4);border-radius:50%;display:inline-block;"></span>
                        <span style="font-size:0.7rem;opacity:0.75;">{{ now()->format('d/m/Y') }}</span>
                    </div>
                </div>
                <div class="ticket-body">
                    <div style="display:flex;align-items:center;gap:1rem;margin-bottom:1rem;background:#f0fdf4;border:1px solid #d1fae5;border-radius:12px;padding:0.875rem;">
                        <div style="width:56px;height:80px;background:#f3f4f6;border-radius:6px;overflow:hidden;flex-shrink:0;border:1px solid #e5e7eb;box-shadow:0 2px 6px rgba(0,0,0,0.06);">
                            @if (filter_var($book->cover_image, FILTER_VALIDATE_URL))
                                <img src="{{ $book->cover_image }}" style="width:100%;height:100%;object-fit:cover;" crossorigin="anonymous" loading="lazy">
                            @elseif ($book->cover_image)
                                <img src="{{ asset('storage/' . $book->cover_image) }}" style="width:100%;height:100%;object-fit:cover;" loading="lazy">
                            @else
                                <div style="width:100%;height:100%;display:flex;align-items:center;justify-content:center;background:#f0fdf4;"><i class="fas fa-book" style="color:#6ee7b7;font-size:20px;"></i></div>
                            @endif
                        </div>
                        <div style="flex:1;min-width:0;">
                            <div style="font-size:0.65rem;color:#059669;font-weight:700;text-transform:uppercase;letter-spacing:0.5px;margin-bottom:0.125rem;">Judul Buku</div>
                            <h3 style="font-weight:800;color:#111827;font-size:0.95rem;margin:0 0 0.125rem 0;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;">{{ $book->title }}</h3>
                            <p style="color:#6b7280;font-size:0.75rem;margin:0;">{{ $book->author }}</p>
                        </div>
                    </div>

                    <hr class="ticket-divider">

                    <div style="display:flex;flex-direction:column;gap:0.5rem;">
                        <div style="display:flex;align-items:center;gap:0.625rem;">
                            <div style="flex:1;display:flex;justify-content:space-between;align-items:center;">
                                <span style="font-size:0.75rem;color:#6b7280;">ID Peminjaman</span>
                                <span class="info-value" id="ticketBorrowId" style="font-size:0.8125rem;color:#059669;font-weight:800;">Menunggu...</span>
                            </div>
                        </div>
                        <div style="display:flex;align-items:center;gap:0.625rem;">
                            <div style="flex:1;display:flex;justify-content:space-between;align-items:center;">
                                <span style="font-size:0.75rem;color:#6b7280;">Nama Peminjam</span>
                                <span class="info-value" id="ticketStudentName" style="font-size:0.8125rem;color:#111827;font-weight:700;">-</span>
                            </div>
                        </div>
                        <div style="display:flex;align-items:center;gap:0.625rem;">
                            <div style="flex:1;display:flex;justify-content:space-between;align-items:center;">
                                <span style="font-size:0.75rem;color:#6b7280;">Kelas / NIS</span>
                                <span class="info-value" id="ticketStudentNis" style="font-size:0.8125rem;color:#111827;font-weight:700;">-</span>
                            </div>
                        </div>
                    </div>
                    <hr class="ticket-divider">
                </div>

                <div class="ticket-actions">
                    <button class="btn-save" id="btnSaveTicket"><i class="fas fa-download"></i> Simpan Sebagai Gambar</button>
                    <button class="btn-close-ticket" id="btnCloseTicket">Kembali ke Katalog</button>
                </div>
            </div>
        </div>
            <div class="toast-container" id="toastContainer"></div>
        `;

            shadow.innerHTML = style + html;

            // TOAST HELPER
            const toastContainer = shadow.getElementById('toastContainer');

            function showToast(type, title, message, duration = 5000) {
                const icons = {
                    error: '<i class="fas fa-xmark"></i>',
                    success: '<i class="fas fa-check"></i>'
                };
                const el = document.createElement('div');
                el.className = 'toast toast-' + type;
                el.innerHTML =
                    '<div class="toast-icon">' + (icons[type] || '') + '</div>' +
                    '<div class="toast-body"><div class="toast-title">' + title + '</div><div class="toast-message">' +
                    message + '</div></div>' +
                    '<button class="toast-close">&times;</button>';
                toastContainer.appendChild(el);
                requestAnimationFrame(() => el.classList.add('show'));
                el.querySelector('.toast-close').onclick = function() {
                    el.classList.remove('show');
                    setTimeout(() => el.remove(), 400);
                };
                setTimeout(() => {
                    if (el.parentNode) {
                        el.classList.remove('show');
                        setTimeout(() => el.remove(), 400);
                    }
                }, duration);
            }

            // ELEMENT ACCESS
            const modal = shadow.getElementById('borrowModalShadow');
            const ticketModal = shadow.getElementById('ticketModalShadow');
            const form = shadow.getElementById('borrowFormShadow');
            const btnSaveTicket = shadow.getElementById('btnSaveTicket');
            const btnCloseTicket = shadow.getElementById('btnCloseTicket');

            // FILL USER DATA
            if (window.studentData) {
                const nameEl = shadow.getElementById('ticketStudentName');
                const nisEl = shadow.getElementById('ticketStudentNis');
                if (nameEl) nameEl.textContent = window.studentData.name;
                if (nisEl) nisEl.textContent = window.studentData.class + ' / ' + window.studentData.nis;
            }

            // FUNGSI BUKA MODAL
            window.showBorrowModal = function() {
                modal.classList.add('show');
                document.body.style.overflow = 'hidden';
            };

            function closeBorrowModal() {
                modal.classList.remove('show');
                document.body.style.overflow = '';
            }

            modal.addEventListener('click', (e) => {
                if (e.target === modal) closeBorrowModal();
            });

            // SUBMIT FORM
            if (form) {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();
                    closeBorrowModal();

                    const loading = document.getElementById('loadingState');
                    if (loading) loading.classList.remove('hidden');
                    document.body.style.overflow = 'hidden';

                    fetch(this.action, {
                            method: 'POST',
                            body: new FormData(this),
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (loading) loading.classList.add('hidden');

                            if (data.success) {
                                if (data.borrow_id) {
                                    shadow.getElementById('ticketBorrowId').innerText = "#PMB-" + data
                                        .borrow_id;
                                } else {
                                    shadow.getElementById('ticketBorrowId').innerText = "#VERIFIED";
                                }
                                ticketModal.classList.add('show');
                                document.body.style.overflow = 'hidden';
                            } else {
                                document.body.style.overflow = '';
                                showToast('error', 'Gagal!', data.message);
                            }
                        })
                        .catch(() => {
                            if (loading) loading.classList.add('hidden');
                            document.body.style.overflow = '';
                            showToast('error', 'Kesalahan!',
                                'Terjadi kesalahan jaringan. Silakan coba lagi.');
                        });
                });
            }

            // TUTUP TIKET & REDIRECT
            if (btnCloseTicket) {
                btnCloseTicket.addEventListener('click', () => {
                    window.location.href = '{{ route('books.index') }}';
                });
            }

            // DOWNLOAD TIKET (HTML2CANVAS)
            if (btnSaveTicket) {
                btnSaveTicket.addEventListener('click', async () => {
                    btnSaveTicket.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Membuat Gambar...';
                    btnSaveTicket.disabled = true;

                    try {
                        const actionsDiv = shadow.querySelector('.ticket-actions');
                        actionsDiv.style.display = 'none';

                        const ticketEl = shadow.getElementById('ticketCaptureArea');

                        const canvas = await html2canvas(ticketEl, {
                            backgroundColor: "#ffffff",
                            scale: 2,
                            useCORS: true,
                            logging: false,
                            allowTaint: true,
                            removeContainer: true
                        });

                        actionsDiv.style.display = 'flex';

                        const link = document.createElement('a');
                        link.download = 'Tiket_Peminjaman_{{ Str::slug($book->title) }}.jpg';
                        link.href = canvas.toDataURL('image/jpeg', 0.98);
                        link.click();

                        btnSaveTicket.innerHTML = '<i class="fas fa-check"></i> Tersimpan!';
                        setTimeout(() => {
                            btnSaveTicket.innerHTML =
                                '<i class="fas fa-download"></i> Simpan Sebagai Gambar';
                            btnSaveTicket.disabled = false;
                        }, 2000);

                    } catch (err) {
                        console.error(err);
                        const actionsDiv = shadow.querySelector('.ticket-actions');
                        actionsDiv.style.display = 'flex';
                        alert('Gagal membuat gambar. Pastikan koneksi stabil.');
                        btnSaveTicket.innerHTML = '<i class="fas fa-download"></i> Simpan Sebagai Gambar';
                        btnSaveTicket.disabled = false;
                    }
                });
            }
        })();

        // TABS LOGIC
        document.addEventListener('DOMContentLoaded', function() {
            const tabBtns = document.querySelectorAll('.tab-btn');
            tabBtns.forEach(btn => {
                btn.addEventListener('click', () => {
                    document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove(
                        'active'));
                    document.querySelectorAll('.tab-content').forEach(c => c.classList.add(
                        'hidden'));
                    btn.classList.add('active');
                    const target = document.getElementById(btn.dataset.tab);
                    if (target) target.classList.remove('hidden');
                });
            });

            // REVEAL ANIMATION
            const revealEl = document.querySelector('.reveal:not(.visible)');
            if (revealEl) {
                const observer = new IntersectionObserver((entries) => {
                    entries.forEach(entry => {
                        if (entry.isIntersecting) {
                            entry.target.classList.add('visible');
                            observer.unobserve(entry.target);
                        }
                    });
                }, {
                    threshold: 0.1
                });
                observer.observe(revealEl);
            }
        });
    </script>
@endsection
