@extends('layouts.app')

@section('title', $book->title . ' - ' . config('app.name'))

@section('styles')
<link rel="stylesheet" href="{{ asset('assets/css/books-show.css') }}">
@stop

@section('content')
    <div class="container mx-auto px-4 py-6 max-w-6xl space-y-6">
        <!-- Loading State -->
        <div id="loadingState" class="hidden fixed inset-0 bg-black/50 flex items-center justify-center z-50">
            <div class="bg-white rounded-lg p-5 flex flex-col items-center">
                <div class="animate-spin rounded-full h-10 w-10 border-b-2 border-green-600 mb-3"></div>
                <p class="text-gray-700">Memproses peminjaman...</p>
            </div>
        </div>

        <!-- Success Notification -->
        <div id="successNotification" class="hidden fixed top-4 right-4 bg-green-500 text-white px-4 py-3 rounded-lg shadow-lg z-50 flex items-center">
            <i class="fas fa-check-circle mr-2"></i>
            <span id="successMessage">Peminjaman berhasil!</span>
        </div>

        {{-- Tombol Kembali dengan Efek Hover --}}
        <a href="{{ route('books.index') }}"
            class="inline-flex items-center text-gray-700 hover:text-green-600 transition-all duration-300 transform hover:-translate-x-1">
            <i class="fas fa-arrow-left mr-2"></i>Kembali ke Katalog
        </a>

        {{-- Kartu Utama Buku --}}
        <div class="bg-white rounded-lg shadow-lg border border-gray-200 overflow-hidden animate-fade-in">
            {{-- Header Buku --}}
            <div class="bg-gradient-to-r from-green-600 to-emerald-600 text-white p-6">
                <div class="flex flex-col md:flex-row md:items-start gap-6">
                    {{-- COVER BUKU --}}
                    <div class="relative group">
                        <div class="w-32 h-48 md:w-40 md:h-60 bg-white rounded-lg overflow-hidden shadow-xl border border-white/30 transform transition-all duration-300 group-hover:scale-105">
                            @if (filter_var($book->cover_image, FILTER_VALIDATE_URL))
                                <img src="{{ $book->cover_image }}" class="w-full h-full object-cover" alt="{{ $book->title }}">
                            @else
                                <img src="{{ asset('storage/' . $book->cover_image) }}" alt="{{ $book->title }}" class="w-full h-full object-cover">
                            @endif
                        </div>
                        <div class="absolute -top-2 -right-2">
                            @if ($book->stock > 0)
                                <div class="bg-green-500 text-white rounded-full w-10 h-10 flex items-center justify-center animate-pulse">
                                    <i class="fas fa-check"></i>
                                </div>
                            @else
                                <div class="bg-red-500 text-white rounded-full w-10 h-10 flex items-center justify-center">
                                    <i class="fas fa-times"></i>
                                </div>
                            @endif
                        </div>
                    </div>
                    {{-- INFO BUKU --}}
                    <div class="flex-1">
                        <h1 class="text-2xl md:text-3xl font-bold mb-2">{{ $book->title }}</h1>
                        <p class="text-green-100 mb-4 flex items-center">
                            <i class="fas fa-user-edit mr-2"></i>oleh {{ $book->author }}
                        </p>
                        <div class="flex flex-wrap gap-2 mb-4">
                            @if ($book->stock > 0)
                                <span class="bg-white/20 backdrop-blur text-white px-3 py-1 rounded-full text-sm font-medium flex items-center border border-white/30">
                                    <i class="fas fa-check-circle mr-1"></i>Tersedia
                                </span>
                            @else
                                <span class="bg-white/20 backdrop-blur text-white px-3 py-1 rounded-full text-sm font-medium flex items-center border border-white/30">
                                    <i class="fas fa-times-circle mr-1"></i>Stok Habis
                                </span>
                            @endif
                            <span class="bg-white/20 backdrop-blur text-white px-3 py-1 rounded-full text-sm font-medium border border-white/30">
                                <i class="fas fa-tag mr-1"></i>{{ $book->category }}
                            </span>
                            <span class="bg-white/20 backdrop-blur text-white px-3 py-1 rounded-full text-sm font-medium border border-white/30">
                                <i class="fas fa-map-marker-alt mr-1"></i>Rak {{ $book->shelf_code }}
                            </span>
                        </div>
                        <!-- <div class="flex items-center">
                            <div class="flex text-yellow-300">
                                @for ($i = 1; $i <= 5; $i++)
                                    <i class="fas fa-star {{ $i <= ($book->rating ?? 4) ? '' : 'text-gray-400' }}"></i>
                                @endfor
                            </div>
                            <span class="ml-2 text-green-100 text-sm">({{ $book->reviews_count ?? 12 }} ulasan)</span>
                        </div> -->
                    </div>
                </div>
            </div>

            {{-- Konten dengan Tab --}}
            <div class="p-6">
                <div class="border-b border-gray-200 mb-6">
                    <nav class="-mb-px flex space-x-8">
                        <button class="tab-btn py-2 px-1 border-b-2 font-medium text-sm border-green-500 text-green-600" data-tab="details">Detail Buku</button>
                        <button class="tab-btn py-2 px-1 border-b-2 font-medium text-sm border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300" data-tab="synopsis">Sinopsis</button>
                        <!-- <button class="tab-btn py-2 px-1 border-b-2 font-medium text-sm border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300" data-tab="reviews">Ulasan</button> -->
                    </nav>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    {{-- Kolom Kiri (Tab Content) --}}
                    <div class="lg:col-span-2">
                        <div id="details" class="tab-content space-y-6">
                            <section class="bg-gray-50 rounded-lg p-5">
                                <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                                    <i class="fas fa-info-circle text-green-600 mr-2"></i>Informasi Buku
                                </h2>
                                <dl class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                    <div class="bg-white p-3 rounded-lg border border-gray-200">
                                        <dt class="text-sm font-medium text-gray-500 flex items-center"><i class="fas fa-building mr-2 text-purple-500"></i>Penerbit</dt>
                                        <dd class="mt-1 font-semibold text-gray-800">{{ $book->publisher }}</dd>
                                    </div>
                                    <div class="bg-white p-3 rounded-lg border border-gray-200">
                                        <dt class="text-sm font-medium text-gray-500 flex items-center"><i class="fas fa-calendar mr-2 text-blue-500"></i>Tahun Terbit</dt>
                                        <dd class="mt-1 font-semibold text-gray-800">{{ $book->year }}</dd>
                                    </div>
                                    <div class="bg-white p-3 rounded-lg border border-gray-200">
                                        <dt class="text-sm font-medium text-gray-500 flex items-center"><i class="fas fa-barcode mr-2 text-green-500"></i>ISBN</dt>
                                        <dd class="mt-1 font-semibold text-gray-800">{{ $book->isbn ?? '-' }}</dd>
                                    </div>
                                    <!-- <div class="bg-white p-3 rounded-lg border border-gray-200">
                                        <dt class="text-sm font-medium text-gray-500 flex items-center"><i class="fas fa-file-alt mr-2 text-orange-500"></i>Halaman</dt>
                                        <dd class="mt-1 font-semibold text-gray-800">{{ $book->pages ?? '-' }}</dd>
                                    </div> -->
                                </dl>
                            </section>
                        </div>
                        <div id="synopsis" class="tab-content hidden space-y-6">
                            <section class="bg-gray-50 rounded-lg p-5">
                                <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                                    <i class="fas fa-book-open text-green-600 mr-2"></i>Sinopsis
                                </h2>
                                <p class="text-gray-600 leading-relaxed whitespace-pre-line">{!! $book->synopsis ?? 'Sinopsis untuk buku ini belum tersedia.' !!}</p>
                            </section>
                        </div>
                        <!-- <div id="reviews" class="tab-content hidden space-y-6">
                            <section class="bg-gray-50 rounded-lg p-5">
                                <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                                    <i class="fas fa-comments text-green-600 mr-2"></i>Ulasan Pembaca
                                </h2>
                                <div class="bg-white p-4 rounded-lg border border-gray-200 mb-4">
                                    <div class="flex items-center">
                                        <div class="text-4xl font-bold text-gray-800 mr-4">4.5</div>
                                        <div>
                                            <div class="flex text-yellow-400 mb-1">
                                                @for ($i = 1; $i <= 5; $i++)
                                                    <i class="fas fa-star {{ $i <= 4 ? '' : 'text-gray-300' }}"></i>
                                                @endfor
                                            </div>
                                            <p class="text-sm text-gray-600">Berdasarkan {{ $book->reviews_count ?? 12 }} ulasan</p>
                                        </div>
                                    </div>
                                </div>
                                <div class="space-y-4">
                                    @for ($i = 1; $i <= 3; $i++)
                                        <div class="bg-white p-4 rounded-lg border border-gray-200">
                                            <div class="flex items-center mb-2">
                                                <img src="https://picsum.photos/seed/reviewer{{ $i }}/40/40.jpg" alt="Reviewer" class="w-10 h-10 rounded-full mr-3">
                                                <div>
                                                    <h4 class="font-semibold text-gray-800">Pembaca {{ $i }}</h4>
                                                    <div class="flex text-yellow-400 text-sm">
                                                        @for ($j = 1; $j <= 5; $j++)
                                                            <i class="fas fa-star {{ $j <= (5 - $i + 2) ? '' : 'text-gray-300' }}"></i>
                                                        @endfor
                                                    </div>
                                                </div>
                                            </div>
                                            <p class="text-gray-600 text-sm">Buku ini sangat menarik dan informatif. Saya merekomendasikannya untuk semua pembaca.</p>
                                        </div>
                                    @endfor
                                </div>
                            </section>
                        </div> -->
                    </div>

                    {{-- Kolom Kanan (Stok & Info) --}}
                    <div class="lg:col-span-1">
                        <div class="space-y-4 sticky top-6">
                            {{-- Kartu Info Stok --}}
                            <div class="bg-gradient-to-br from-green-50 to-emerald-50 border border-green-200 rounded-lg p-4 shadow-sm">
                                <h3 class="text-lg font-bold text-gray-800 mb-2 flex items-center">
                                    <i class="fas fa-layer-group text-green-600 mr-2"></i>Stok Tersedia
                                </h3>
                                <div class="flex items-baseline">
                                    <p class="text-3xl font-bold text-green-600">{{ $book->stock }}</p>
                                    <span class="ml-2 text-base font-medium text-gray-600">Eksemplar</span>
                                </div>
                                <div class="mt-3">
                                    <div class="w-full bg-gray-200 rounded-full h-2">
                                        <div class="bg-green-600 h-2 rounded-full transition-all duration-500" style="width: {{ min(100, $book->stock * 10) }}%"></div>
                                    </div>
                                    <p class="text-xs text-gray-500 mt-1">{{ $book->stock > 5 ? 'Tersedia' : 'Terbatas' }}</p>
                                </div>
                                <div class="mt-4">
                                    @php use App\Providers\AppServiceProvider; @endphp
                                    @if (AppServiceProvider::isLibraryOpen() && $book->stock > 0)
                                        @auth('student')
                                            @if (Auth::guard('student')->user()->is_active)
                                                <button onclick="showBorrowModal()" class="w-full bg-gradient-to-r from-green-600 to-emerald-600 text-white py-3 px-4 rounded-lg font-medium hover:from-green-700 hover:to-emerald-700 transition-all duration-300 flex items-center justify-center transform hover:-translate-y-0.5 shadow-md">
                                                    <i class="fas fa-hand-holding mr-2"></i>Pinjam Buku
                                                </button>
                                            @else
                                                <button disabled title="Akun Anda tidak aktif. Silakan hubungi administrator." class="w-full bg-gray-400 text-white py-3 px-4 rounded-lg font-medium cursor-not-allowed flex items-center justify-center">
                                                    <i class="fas fa-user-slash mr-2"></i>Akun Tidak Aktif
                                                </button>
                                            @endif
                                        @else
                                            <a href="{{ route('student.login.form') }}" class="block w-full bg-gradient-to-r from-blue-600 to-indigo-600 text-white py-3 px-4 rounded-lg font-medium hover:from-blue-700 hover:to-indigo-700 transition-all duration-300 text-center">
                                                <i class="fas fa-sign-in-alt mr-2"></i>Login untuk Meminjam
                                            </a>
                                        @endauth
                                    @else
                                        <button disabled title="Peminjaman hanya dapat dilakukan pada jam operasional (Senin-Jumat, 07:00-16:00 WIB)" class="w-full bg-gray-400 text-white py-3 px-4 rounded-lg font-medium cursor-not-allowed flex items-center justify-center">
                                            <i class="fas fa-clock mr-2"></i>{{ $book->stock > 0 ? 'Di Luar Jam Operasional' : 'Stok Habis' }}
                                        </button>
                                    @endif
                                </div>
                            </div>

                            {{-- Info Peminjaman --}}
                            <div class="bg-white border border-gray-200 rounded-lg p-4 shadow-sm">
                                <h3 class="text-lg font-bold text-gray-800 mb-3 flex items-center">
                                    <i class="fas fa-info-circle text-green-600 mr-2"></i>Informasi Peminjaman
                                </h3>
                                <ul class="space-y-3 text-sm text-gray-600">
                                    <li class="flex items-start bg-gray-50 p-2 rounded">
                                        <i class="fas fa-clock mr-2 mt-1 text-green-500"></i>
                                        <div>
                                            <span class="font-medium">Maksimal peminjaman:</span>
                                            <span class="block text-gray-800">7 hari</span>
                                        </div>
                                    </li>
                                    <li class="flex items-start bg-gray-50 p-2 rounded">
                                        <i class="fas fa-exclamation-triangle mr-2 mt-1 text-orange-500"></i>
                                        <div>
                                            <span class="font-medium">Denda keterlambatan:</span>
                                            <span class="block text-gray-800">Rp1.000/hari</span>
                                        </div>
                                    </li>
                                    <li class="flex items-start bg-gray-50 p-2 rounded">
                                        <i class="fas fa-user-check mr-2 mt-1 text-teal-500"></i>
                                        <div>
                                            <span class="font-medium">Status:</span>
                                            <span class="block text-gray-800">Hanya untuk siswa terverifikasi</span>
                                        </div>
                                    </li>
                                </ul>
                            </div>

                            {{-- BUKU TERKAIT --}}
                            @if(isset($relatedBooks) && $relatedBooks->count() > 0)
                                <div class="bg-white border border-gray-200 rounded-lg p-4 shadow-sm">
                                    <h3 class="text-lg font-bold text-gray-800 mb-3 flex items-center">
                                        <i class="fas fa-book text-green-600 mr-2"></i>Buku Terkait
                                    </h3>
                                    <div class="space-y-3">
                                        @forelse ($relatedBooks as $relatedBook)
                                            <a href="{{ route('books.show', $relatedBook) }}" class="flex items-center space-x-3 cursor-pointer hover:bg-gray-50 p-2 rounded transition-colors group">
                                                <img src="{{ $relatedBook->cover_image ? (filter_var($relatedBook->cover_image, FILTER_VALIDATE_URL) ? $relatedBook->cover_image : asset('storage/' . $relatedBook->cover_image)) : 'https://via.placeholder.com/60x80.png?text=No+Cover' }}" 
                                                     alt="{{ $relatedBook->title }}" 
                                                     class="w-12 h-16 object-cover rounded shadow-sm group-hover:shadow-md transition-shadow duration-300">
                                                <div class="flex-1">
                                                    <h4 class="text-sm font-medium text-gray-800 group-hover:text-green-600 transition-colors line-clamp-1">{{ $relatedBook->title }}</h4>
                                                    <p class="text-xs text-gray-500">oleh {{ $relatedBook->author }}</p>
                                                </div>
                                            </a>
                                        @empty
                                            <p class="text-sm text-gray-500">Belum ada buku terkait di kategori ini.</p>
                                        @endforelse
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- MODAL PINJAM BUKU --}}
        <div id="borrowModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm flex items-center justify-center p-4 z-50 hidden">
            <div class="bg-white rounded-lg max-w-md w-full shadow-xl transform transition-all modal-content scale-95">
                <div class="p-5">
                    <div class="flex justify-between items-center mb-4 border-b pb-2">
                        <h3 class="text-lg font-bold text-green-700 flex items-center">
                            <i class="fas fa-hand-holding mr-2"></i>Pinjam Buku
                        </h3>
                        <button onclick="closeBorrowModal()" class="text-gray-500 hover:text-gray-700 transition-colors">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    <div class="text-center py-3 border-b border-gray-200">
                        <div class="w-16 h-16 bg-gradient-to-br from-green-100 to-emerald-100 rounded-full flex items-center justify-center mx-auto mb-3">
                            <i class="fas fa-book text-green-600 text-xl"></i>
                        </div>
                        <h4 class="font-semibold mb-1">{{ $book->title }}</h4>
                        <p class="text-gray-600 text-sm">oleh {{ $book->author }}</p>
                    </div>
                    <div class="pt-4">
                        @auth('student')
                            @if (Auth::guard('student')->user()->is_active_flag)
                                <form id="borrowForm" action="{{ route('books.borrow', $book) }}" method="POST" data-book-id="{{ $book->id }}">
                                    @csrf
                                    <div class="space-y-3">
                                        <div class="bg-gradient-to-r from-green-50 to-emerald-50 rounded p-3 text-sm space-y-2">
                                            <div class="flex justify-between"><span class="font-medium text-gray-700 flex items-center"><i class="fas fa-user mr-2 text-green-600"></i>Peminjam:</span><span>{{ Auth::guard('student')->user()->name }}</span></div>
                                            <div class="flex justify-between"><span class="font-medium text-gray-700 flex items-center"><i class="fas fa-calendar-alt mr-2 text-green-600"></i>Tanggal Pinjam:</span><span>{{ now()->format('d M Y') }}</span></div>
                                            <div class="flex justify-between"><span class="font-medium text-gray-700 flex items-center"><i class="fas fa-calendar-check mr-2 text-green-600"></i>Jatuh Tempo:</span><span class="font-bold text-green-600">{{ now()->addDays(7)->format('d M Y') }}</span></div>
                                        </div>
                                        <div class="flex items-start bg-blue-50 p-3 rounded">
                                            <input type="checkbox" name="terms" id="terms" required class="mt-1 mr-2 text-green-600 border-gray-300 rounded focus:ring-green-500">
                                            <label for="terms" class="text-xs text-gray-600">Saya setuju untuk mengembalikan buku sesuai tanggal jatuh tempo dan menjaga kondisi buku dengan baik.</label>
                                        </div>
                                        <button type="submit" class="w-full bg-gradient-to-r from-green-600 to-emerald-600 text-white py-2 px-4 rounded-lg font-medium hover:from-green-700 hover:to-emerald-700 transition-all duration-300 flex items-center justify-center transform hover:-translate-y-0.5">
                                            <i class="fas fa-check-circle mr-2"></i>Konfirmasi Peminjaman
                                        </button>
                                    </div>
                                </form>
                            @else
                                <div class="space-y-3 text-center">
                                    <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-3">
                                        <i class="fas fa-user-slash text-red-600 text-xl"></i>
                                    </div>
                                    <p class="text-gray-600 text-sm font-medium">Akun Anda tidak aktif.</p>
                                    <p class="text-gray-500 text-xs">Anda tidak dapat meminjam buku saat ini. Silakan hubungi administrator untuk mengaktifkan akun Anda.</p>
                                </div>
                            @endif
                        @else
                            <div class="space-y-3">
                                <p class="text-gray-600 text-center text-sm">Untuk meminjam buku ini, silakan login sebagai siswa terdaftar atau daftar terlebih dahulu.</p>
                                <div class="flex gap-2">
                                    <a href="{{ route('student.login.form') }}" class="flex-1 bg-gradient-to-r from-green-600 to-emerald-600 text-white py-2 px-3 rounded-lg font-medium hover:from-green-700 hover:to-emerald-700 transition-all duration-300 text-center text-sm">
                                        <i class="fas fa-sign-in-alt mr-1"></i>Login
                                    </a>
                                    <a href="{{ route('student.register.form') }}" class="flex-1 bg-gradient-to-r from-teal-600 to-cyan-600 text-white py-2 px-3 rounded-lg font-medium hover:from-teal-700 hover:to-cyan-700 transition-all duration-300 text-center text-sm">
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

@section('scripts')
<script src="{{ asset('assets/js/books-show.js') }}"></script>
@stop 

@endsection