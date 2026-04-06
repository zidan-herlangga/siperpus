@extends('layouts.app')

@section('title', 'Dashboard Siswa - ' . config('app.name'))

@section('styles')
    <link rel="stylesheet" href="{{ asset('assets/css/dashboard-student.css') }}">
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
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(16px) saturate(180%);
            -webkit-backdrop-filter: blur(16px) saturate(180%);
            border: 1px solid rgba(255, 255, 255, 0.8);
            box-shadow: 0 4px 24px -4px rgba(0, 0, 0, 0.05);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .card-glass:hover {
            box-shadow: 0 10px 30px -6px rgba(0, 0, 0, 0.08);
            transform: translateY(-4px);
        }
        .stat-card {
            position: relative;
            overflow: hidden;
        }
        .stat-card::before {
            content: '';
            position: absolute;
            top: -20px;
            right: -20px;
            width: 80px;
            height: 80px;
            border-radius: 50%;
            opacity: 0.08;
            transition: transform 0.4s ease;
        }
        .stat-card:hover::before {
            transform: scale(1.5);
        }
        .stat-green::before { background-color: #059669; }
        .stat-blue::before { background-color: #3b82f6; }
        .stat-red::before { background-color: #ef4444; }
        .stat-yellow::before { background-color: #f59e0b; }

        .book-card {
            border-left: 4px solid transparent;
            transition: all 0.3s ease;
        }
        .book-card.active { border-left-color: #10b981; }
        .book-card.overdue { border-left-color: #ef4444; }
        .book-card:hover {
            box-shadow: 0 8px 20px -4px rgba(0,0,0,0.06);
            transform: translateY(-2px);
        }

        .table-modern thead th {
            background-color: #f9fafb;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #6b7280;
        }
        .table-modern tbody tr {
            transition: background-color 0.2s ease;
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
            
            {{-- Header Welcome --}}
            <div class="mb-8 reveal">
                <div class="bg-gradient-to-br from-emerald-600 to-green-700 text-white rounded-2xl p-8 shadow-lg relative overflow-hidden">
                    <div class="absolute inset-0 bg-black/5"></div>
                    <div class="absolute top-0 right-0 w-64 h-64 bg-white/5 rounded-full -mr-32 -mt-32"></div>
                    <div class="absolute bottom-0 left-0 w-48 h-48 bg-white/5 rounded-full -ml-24 -mb-24"></div>

                    <div class="relative z-10 flex flex-col md:flex-row items-start md:items-center justify-between gap-6">
                        <div>
                            <p class="text-emerald-200 text-sm font-medium mb-1">Selamat datang kembali 👋</p>
                            <h1 class="text-3xl md:text-4xl font-extrabold tracking-tight">
                                Hai, <span class="text-yellow-300">{{ $student->name }}</span>
                            </h1>
                            <p class="text-emerald-100 mt-2 text-sm">
                                Ini adalah ringkasan aktivitas perpustakaan Anda di <span class="font-semibold">{{ config('app.name') }}</span>.
                            </p>
                        </div>
                        
                        {{-- Quick Stats di Header --}}
                        <div class="flex gap-3 flex-wrap md:flex-nowrap">
                            <div class="bg-white/10 backdrop-blur-sm border border-white/10 rounded-xl px-4 py-3 text-center min-w-[100px]">
                                <p class="text-2xl font-bold">{{ $currentBorrowings->count() }}</p>
                                <p class="text-emerald-200 text-xs">Dipinjam</p>
                            </div>
                            <div class="bg-white/10 backdrop-blur-sm border border-white/10 rounded-xl px-4 py-3 text-center min-w-[100px]">
                                <p class="text-2xl font-bold {{ $currentBorrowings->where('due_date', '<', now())->count() > 0 ? 'text-red-300' : '' }}">
                                    {{ $currentBorrowings->where('due_date', '<', now())->count() }}
                                </p>
                                <p class="text-emerald-200 text-xs">Terlambat</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="lg:grid lg:grid-cols-3 lg:gap-6">
                
                {{-- Kolom Kiri (Konten Utama) --}}
                <div class="lg:col-span-2 space-y-6">
                    
                    {{-- Statistik Kartu Utama --}}
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 reveal reveal-delay-1">
                        <div class="card-glass stat-card stat-green rounded-xl p-5">
                            <div class="flex items-center justify-between mb-3">
                                <div class="w-10 h-10 bg-emerald-100 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-book text-emerald-600"></i>
                                </div>
                            </div>
                            <p class="text-2xl font-extrabold text-gray-800">{{ $currentBorrowings->count() }}</p>
                            <p class="text-xs text-gray-500 mt-1">Sedang Dipinjam</p>
                        </div>
                        <div class="card-glass stat-card stat-blue rounded-xl p-5">
                            <div class="flex items-center justify-between mb-3">
                                <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-clock-rotate-left text-blue-600"></i>
                                </div>
                            </div>
                            <p class="text-2xl font-extrabold text-gray-800">{{ $returnedBorrowings->count() }}</p>
                            <p class="text-xs text-gray-500 mt-1">Riwayat Peminjaman</p>
                        </div>
                        <div class="card-glass stat-card stat-red rounded-xl p-5">
                            <div class="flex items-center justify-between mb-3">
                                <div class="w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-triangle-exclamation text-red-600"></i>
                                </div>
                            </div>
                            <p class="text-2xl font-extrabold text-gray-800">{{ $currentBorrowings->where('due_date', '<', now())->count() }}</p>
                            <p class="text-xs text-gray-500 mt-1">Terlambat</p>
                        </div>
                        <div class="card-glass stat-card stat-yellow rounded-xl p-5">
                            <div class="flex items-center justify-between mb-3">
                                <div class="w-10 h-10 bg-amber-100 rounded-lg flex items-center justify-center">
                                    <i class="fas fa-coins text-amber-600"></i>
                                </div>
                            </div>
                            <p class="text-2xl font-extrabold text-gray-800">Rp {{ number_format($currentBorrowings->sum('fine_amount'), 0, ',', '.') }}</p>
                            <p class="text-xs text-gray-500 mt-1">Total Denda</p>
                        </div>
                    </div>

                    {{-- Buku Sedang Dipinjam --}}
                    <div class="reveal reveal-delay-2">
                        <div class="flex items-center justify-between mb-4">
                            <h2 class="text-lg font-bold text-gray-800 flex items-center gap-2">
                                <i class="fas fa-book-open text-emerald-500 text-sm"></i>
                                Buku Sedang Dipinjam
                            </h2>
                            <span class="bg-emerald-50 text-emerald-600 text-xs font-semibold px-3 py-1 rounded-full border border-emerald-100">
                                {{ $currentBorrowings->count() }} buku
                            </span>
                        </div>

                        <div class="grid md:grid-cols-2 gap-4">
                            @forelse ($currentBorrowings as $borrowing)
                                @php $isOverdue = now()->gt($borrowing->due_date); @endphp
                                <div class="bg-white rounded-xl border border-gray-100 shadow-sm book-card {{ $isOverdue ? 'overdue' : 'active' }} overflow-hidden">
                                    <div class="p-5">
                                        <div class="flex justify-between items-start mb-3">
                                            <h3 class="font-bold text-gray-800 text-sm leading-snug line-clamp-2 pr-2">{{ $borrowing->book->title }}</h3>
                                            @if ($isOverdue)
                                                <span class="bg-red-50 text-red-600 text-[10px] font-bold px-2 py-0.5 rounded-full border border-red-100 whitespace-nowrap flex-shrink-0">
                                                    TERLAMBAT
                                                </span>
                                            @else
                                                <span class="bg-emerald-50 text-emerald-600 text-[10px] font-bold px-2 py-0.5 rounded-full border border-emerald-100 whitespace-nowrap flex-shrink-0">
                                                    AKTIF
                                                </span>
                                            @endif
                                        </div>
                                        <p class="text-xs text-gray-400 mb-4">oleh {{ $borrowing->book->author }}</p>

                                        <div class="space-y-2 text-xs bg-gray-50 rounded-lg p-3">
                                            <div class="flex justify-between">
                                                <span class="text-gray-500">Pinjam</span>
                                                <span class="font-medium text-gray-700">{{ \Carbon\Carbon::parse($borrowing->borrow_date)->format('d M Y') }}</span>
                                            </div>
                                            <div class="flex justify-between">
                                                <span class="text-gray-500">Tenggat</span>
                                                <span class="font-bold {{ $isOverdue ? 'text-red-500' : 'text-emerald-600' }}">
                                                    {{ \Carbon\Carbon::parse($borrowing->due_date)->format('d M Y') }}
                                                </span>
                                            </div>
                                            <div class="flex justify-between pt-1 border-t border-gray-200">
                                                <span class="text-gray-500">Denda</span>
                                                @if ($borrowing->fine_amount > 0)
                                                    <span class="font-bold text-red-500">Rp {{ number_format($borrowing->fine_amount, 0, ',', '.') }}</span>
                                                @else
                                                    <span class="text-gray-400">Rp 0</span>
                                                @endif
                                            </div>
                                        </div>

                                        @if ($isOverdue)
                                            <div class="mt-3 bg-red-50 text-red-600 text-[11px] font-semibold p-2 rounded-lg flex items-center gap-1.5">
                                                <i class="fas fa-clock"></i>
                                                Terlambat {{ abs(floor(now()->diffInDays($borrowing->due_date))) }} hari
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @empty
                                <div class="col-span-2 bg-emerald-50/50 border border-emerald-100 text-emerald-700 text-center p-10 rounded-xl">
                                    <i class="fas fa-circle-check text-4xl mb-3 text-emerald-300"></i>
                                    <p class="font-semibold">Tidak ada buku yang sedang dipinjam</p>
                                    <p class="text-xs text-emerald-500 mt-1">Anda bebas untuk mencari dan meminjam buku baru!</p>
                                </div>
                            @endforelse
                        </div>
                    </div>

                    {{-- Riwayat Peminjaman --}}
                    <div class="reveal reveal-delay-3">
                        <div class="flex items-center justify-between mb-4">
                            <h2 class="text-lg font-bold text-gray-800 flex items-center gap-2">
                                <i class="fas fa-clock-rotate-left text-gray-400 text-sm"></i>
                                Riwayat Peminjaman
                            </h2>
                            <span class="bg-blue-50 text-blue-600 text-xs font-semibold px-3 py-1 rounded-full border border-blue-100">
                                {{ $returnedBorrowings->count() }} buku
                            </span>
                        </div>
                        <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
                            <div class="overflow-x-auto">
                                <table class="w-full text-left text-sm table-modern">
                                    <thead>
                                        <tr>
                                            <th class="p-4 font-semibold">Judul Buku</th>
                                            <th class="p-4 font-semibold hidden sm:table-cell">Tgl Pinjam</th>
                                            <th class="p-4 font-semibold hidden md:table-cell">Tgl Kembali</th>
                                            <th class="p-4 font-semibold">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-gray-50">
                                        @forelse ($returnedBorrowings as $borrowing)
                                            <tr class="hover:bg-gray-50/50">
                                                <td class="p-4">
                                                    <div class="flex items-center gap-3">
                                                        <div class="w-9 h-11 bg-gray-100 rounded-md flex items-center justify-center flex-shrink-0">
                                                            <i class="fas fa-book text-gray-400 text-xs"></i>
                                                        </div>
                                                        <span class="font-medium text-gray-800 text-sm line-clamp-1">{{ $borrowing->book->title }}</span>
                                                    </div>
                                                </td>
                                                <td class="p-4 text-gray-500 text-xs hidden sm:table-cell">
                                                    {{ \Carbon\Carbon::parse($borrowing->borrow_date)->format('d M Y') }}
                                                </td>
                                                <td class="p-4 text-gray-500 text-xs hidden md:table-cell">
                                                    {{ \Carbon\Carbon::parse($borrowing->return_date)->format('d M Y') }}
                                                </td>
                                                <td class="p-4">
                                                    <span class="bg-emerald-50 text-emerald-600 text-[10px] font-bold px-2.5 py-1 rounded-full border border-emerald-100">
                                                        SELESAI
                                                    </span>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4" class="text-center p-10 text-gray-400">
                                                    <i class="fas fa-inbox text-3xl mb-3 text-gray-200"></i>
                                                    <p class="font-medium">Belum ada riwayat</p>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Sidebar --}}
                <aside class="lg:col-span-1 space-y-6 mt-6 lg:mt-0">
                    
                    {{-- Profil Siswa --}}
                    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5 reveal reveal-delay-2">
                        <div class="flex items-center gap-4 mb-5">
                            @if ($student->avatar)
                                <img src="{{ asset('storage/' . $student->avatar) }}" alt="{{ $student->name }}"
                                    class="w-16 h-16 rounded-xl object-cover shadow-sm border-2 border-gray-100">
                            @else
                                <div class="w-16 h-16 rounded-xl bg-gradient-to-br from-emerald-500 to-green-600 flex items-center justify-center text-white text-xl font-bold shadow-sm">
                                    {{ strtoupper(substr($student->name, 0, 1)) }}
                                </div>
                            @endif
                            <div>
                                {{-- badge status --}}
                                @if ($student->is_active_flag)
                                    <span class="bg-emerald-50 text-emerald-600 text-[10px] font-bold px-2 py-0.5 rounded-full border border-emerald-100 whitespace-nowrap">
                                        AKTIF
                                    </span>
                                @else
                                    <span class="bg-red-50 text-red-600 text-[10px] font-bold px-2 py-0.5 rounded-full border border-red-100 whitespace-nowrap">
                                        NONAKTIF
                                    </span>
                                @endif
                                <h3 class="text-base font-bold text-gray-800 leading-tight">{{ $student->name }}</h3>
                                <p class="text-xs text-gray-400 mt-0.5">{{ $student->class }}</p>
                            </div>
                        </div>
                        <div class="space-y-2 text-sm text-gray-600">
                            <div class="flex items-center gap-3 bg-gray-50 p-2.5 rounded-lg">
                                <i class="fas fa-id-badge text-blue-400 w-4 text-center text-xs"></i>
                                <span class="truncate text-xs">{{ $student->nis ?? 'Tidak ada NIS' }}</span>
                            </div>
                            <div class="flex items-center gap-3 bg-gray-50 p-2.5 rounded-lg">
                                <i class="fas fa-envelope text-purple-400 w-4 text-center text-xs"></i>
                                <span class="truncate text-xs">{{ $student->email }}</span>
                            </div>
                            <div class="flex items-center gap-3 bg-gray-50 p-2.5 rounded-lg">
                                <i class="fas fa-graduation-cap text-amber-400 w-4 text-center text-xs"></i>
                                <span class="truncate text-xs">{{ $student->class }}</span>
                            </div>
                            <div class="flex items-center gap-3 bg-gray-50 p-2.5 rounded-lg">
                                <i class="fas fa-wifi text-green-400 w-4 text-center text-xs"></i>
                                <span class="truncate text-xs">{{ $ipAddress }}</span>
                            </div>
                        </div>
                    </div>

                    {{-- Aksi Cepat --}}
                    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5 reveal reveal-delay-3">
                        <h3 class="text-sm font-bold text-gray-800 uppercase tracking-wider mb-4">Aksi Cepat</h3>
                        <div class="space-y-2.5">
                            <a href="{{ route('student.edit') }}"
                                class="flex items-center gap-3 w-full text-left bg-gray-50 hover:bg-gray-100 text-gray-700 font-medium py-3 px-4 rounded-xl transition-all text-sm">
                                <i class="fas fa-user-pen text-gray-400 w-4"></i>
                                Edit Profil
                            </a>
                            <a href="{{ route('books.index') }}"
                                class="flex items-center gap-3 w-full text-left bg-emerald-50 hover:bg-emerald-100 text-emerald-700 font-medium py-3 px-4 rounded-xl transition-all text-sm">
                                <i class="fas fa-magnifying-glass text-emerald-400 w-4"></i>
                                Cari Buku Baru
                            </a>
                            <button onclick="openModal()"
                                class="flex items-center gap-3 w-full text-left bg-amber-50 hover:bg-amber-100 text-amber-700 font-medium py-3 px-4 rounded-xl transition-all text-sm">
                                <i class="fas fa-circle-info text-amber-400 w-4"></i>
                                Petunjuk Peminjaman
                            </button>
                        </div>

                        {{-- Form Kirim Testimoni --}}
                        <div class="mt-4 bg-amber-50/50 border border-amber-100 rounded-xl p-5 reveal reveal-delay-3">
                            <h3 class="text-sm font-bold text-gray-800 uppercase tracking-wider mb-3 flex items-center gap-2">
                                <i class="fas fa-pen-to-square text-amber-500"></i> Berikan Ulasan
                            </h3>        
                            @auth('student')
                            @if (Auth::guard('student')->user()->is_active_flag)                      
                                    <p class="text-xs text-gray-500 mb-4">Bagikan pengalaman Anda menggunakan perpustakaan digital.</p>
                                    <form action="{{ route('testimonial.store') }}" method="POST" class="space-y-3">
                                        @csrf
                                        <input type="hidden" name="rating" id="ratingInput" value="5">
                                        
                                        <div>
                                            <label class="text-xs font-semibold text-gray-600 block mb-1.5">Rating</label>
                                            <div class="flex gap-1" id="starRating">
                                                @for ($i = 1; $i <= 5; $i++)
                                                    <button type="button" data-rating="{{ $i }}" class="text-2xl text-amber-400 transition-transform star-btn hover:scale-110">
                                                        <i class="fas fa-star"></i>
                                                    </button>
                                                @endfor
                                            </div>
                                        </div>
                                        
                                        <div>
                                            <textarea name="content" rows="3" required class="w-full text-sm bg-white border border-gray-200 rounded-xl p-3 outline-none focus:border-amber-400 focus:ring-2 focus:ring-amber-100 transition-all placeholder-gray-400" placeholder="Tulis komentar Anda di sini (Min. 10 karakter)...">{{ old('content') }}</textarea>
                                        </div>
                                        
                                        <button type="submit" class="w-full bg-amber-500 hover:bg-amber-600 text-white py-2.5 rounded-xl text-sm font-semibold transition-colors flex items-center justify-center gap-2">
                                            <i class="fas fa-paper-plane text-xs"></i>
                                            <span>Kirim Ulasan</span>
                                        </button>
                                    </form>

                                 @else
                                    <p class="text-xs text-red-400 text-center bg-red-50 p-4 rounded-lg">Akun Anda nonaktif, sehingga tidak bisa memberikan testimonial.</p>
                                 @endif                    
                            @endauth
                        </div>
                    </div>
                </aside>
            </div>
        </div>
    </div>

    {{-- Modal Petunjuk Peminjaman --}}
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
                            <p class="text-xs text-gray-500 mt-0.5">Pastikan Anda sudah memiliki akun dan dalam keadaan login.</p>
                        </div>
                    </li>
                    <li class="flex gap-3">
                        <div class="w-7 h-7 bg-emerald-100 text-emerald-600 rounded-full flex items-center justify-center text-xs font-bold flex-shrink-0 mt-0.5">2</div>
                        <div>
                            <p class="text-sm font-medium text-gray-800">Cari & Pinjam Buku</p>
                            <p class="text-xs text-gray-500 mt-0.5">Temukan buku dari katalog, lalu klik tombol "Pinjam Buku".</p>
                        </div>
                    </li>
                    <li class="flex gap-3">
                        <div class="w-7 h-7 bg-emerald-100 text-emerald-600 rounded-full flex items-center justify-center text-xs font-bold flex-shrink-0 mt-0.5">3</div>
                        <div>
                            <p class="text-sm font-medium text-gray-800">Konfirmasi</p>
                            <p class="text-xs text-gray-500 mt-0.5">Setujui syarat dan ketentuan pada modal konfirmasi.</p>
                        </div>
                    </li>
                    <li class="flex gap-3">
                        <div class="w-7 h-7 bg-emerald-100 text-emerald-600 rounded-full flex items-center justify-center text-xs font-bold flex-shrink-0 mt-0.5">4</div>
                        <div>
                            <p class="text-sm font-medium text-gray-800">Pengembalian</p>
                            <p class="text-xs text-gray-500 mt-0.5">Kembalikan buku sebelum jatuh tempo agar terhindar dari denda.</p>
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
            // === TESTIMONIAL FORM LOGIC ===
            const starBtns = document.querySelectorAll('.star-btn');
            const ratingInput = document.getElementById('ratingInput');

            if(starBtns.length > 0) {
                starBtns.forEach(star => {
                    star.addEventListener('click', function() {
                        const val = this.getAttribute('data-rating');
                        ratingInput.value = val;
                        
                        starBtns.forEach(s => {
                            if (s.getAttribute('data-rating') <= val) {
                                s.classList.remove('text-gray-300');
                                s.classList.add('text-amber-400');
                            } else {
                                s.classList.remove('text-amber-400');
                                s.classList.add('text-gray-300');
                            }
                        });
                    });
                });
            }

            // Scroll Reveal Logic
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

            // Modal Logic (Standalone untuk menghindari bug dengan layout.app)
            const modal = document.getElementById('borrowGuideModal');
            
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