@extends('layouts.app')

@section('title', 'Dashboard Siswa' . ' - ' . config('app.name'))

@section('styles')
<link rel="stylesheet" href="{{ asset('assets/css/dashboard-student.css') }}">
@stop

@section('content')
    <div class="min-h-screen bg-gradient-to-br from-gray-50 to-green-50 py-8">
        <div class="container mx-auto px-4">
            {{-- Header dengan Animasi --}}
            <div class="mb-8 animate-fade-in">
                <div class="bg-gradient-to-r from-green-600 to-emerald-600 text-white rounded-xl p-6 shadow-lg relative overflow-hidden">
                    <!-- Animated background elements -->
                    <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -mr-16 -mt-16"></div>
                    <div class="absolute bottom-0 left-0 w-24 h-24 bg-white/10 rounded-full -ml-12 -mb-12"></div>
                    
                    <div class="relative z-10 flex flex-col md:flex-row items-center justify-between">
                        <div>
                            <h1 class="text-3xl font-bold mb-2">
                                Hai, <span class="text-yellow-300">{{ $student->name }}</span>
                            </h1>
                            <p class="text-green-100">
                                Selamat datang kembali di <span class="font-medium">{{ config('app.name') }}</span>
                            </p>
                        </div>
                        <div class="mt-4 md:mt-0">
                            <div class="bg-white/20 backdrop-blur rounded-lg p-4 text-center">
                                <p class="text-green-100 text-sm mb-1">Status Keanggotaan</p>
                                <p class="text-xl font-bold">{{ $student->is_active }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="lg:grid lg:grid-cols-3 lg:gap-6">
                {{-- Kolom Kiri (Konten Utama) --}}
                <div class="lg:col-span-2 space-y-6">
                    {{-- Statistik Kartu --}}
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6 animate-slide-up">
                        <div class="bg-white rounded-lg shadow-sm p-4 border border-gray-200 hover:shadow-md transition-shadow">
                            <div class="flex items-center justify-between mb-2">
                                <i class="fas fa-book text-green-600 text-xl"></i>
                                <span class="text-xs text-gray-500">Bulan ini</span>
                            </div>
                            <p class="text-2xl font-bold text-gray-800">{{ $currentBorrowings->count() }}</p>
                            <p class="text-xs text-gray-600">Sedang Dipinjam</p>
                        </div>
                        <div class="bg-white rounded-lg shadow-sm p-4 border border-gray-200 hover:shadow-md transition-shadow">
                            <div class="flex items-center justify-between mb-2">
                                <i class="fas fa-history text-blue-600 text-xl"></i>
                                <span class="text-xs text-gray-500">Total</span>
                            </div>
                            <p class="text-2xl font-bold text-gray-800">{{ $returnedBorrowings->count() }}</p>
                            <p class="text-xs text-gray-600">Riwayat Peminjaman</p>
                        </div>
                        <div class="bg-white rounded-lg shadow-sm p-4 border border-gray-200 hover:shadow-md transition-shadow">
                            <div class="flex items-center justify-between mb-2">
                                <i class="fas fa-exclamation-triangle text-red-600 text-xl"></i>
                                <span class="text-xs text-gray-500">Aktif</span>
                            </div>
                            <p class="text-2xl font-bold text-gray-800">{{ $currentBorrowings->where('due_date', '<', now())->count() }}</p>
                            <p class="text-xs text-gray-600">Terlambat</p>
                        </div>
                        <div class="bg-white rounded-lg shadow-sm p-4 border border-gray-200 hover:shadow-md transition-shadow">
                            <div class="flex items-center justify-between mb-2">
                                <i class="fas fa-coins text-yellow-600 text-xl"></i>
                                <span class="text-xs text-gray-500">Total</span>
                            </div>
                            <p class="text-2xl font-bold text-gray-800">Rp {{ number_format($currentBorrowings->sum('fine_amount'), 0, ',', '.') }}</p>
                            <p class="text-xs text-gray-600">Denda</p>
                        </div>
                    </div>

                    {{-- Buku Dipinjam --}}
                    <div class="animate-slide-up" style="animation-delay: 0.1s;">
                        <div class="flex items-center justify-between mb-3">
                            <h2 class="text-xl font-bold text-gray-700 flex items-center">
                                <i class="fas fa-book-reader mr-2 text-green-600"></i>
                                Buku yang Sedang Dipinjam
                            </h2>
                            <span class="bg-green-100 text-green-700 text-xs font-medium px-2 py-1 rounded-full">
                                {{ $currentBorrowings->count() }} buku
                            </span>
                        </div>

                        <div class="grid md:grid-cols-2 gap-4">
                            @forelse ($currentBorrowings as $borrowing)
                                @php $isOverdue = now()->gt($borrowing->due_date); @endphp
                                <div class="bg-white rounded-lg shadow-sm border border-gray-200 hover:shadow-md transition-all duration-300 transform hover:-translate-y-1 overflow-hidden">
                                    <div class="h-2 {{ $isOverdue ? 'bg-red-500' : 'bg-green-500' }}"></div>
                                    <div class="p-4">
                                        <div class="flex justify-between items-start mb-2">
                                            <h3 class="font-bold text-gray-800 line-clamp-1">{{ $borrowing->book->title }}</h3>
                                            @if ($isOverdue)
                                                <span class="bg-red-100 text-red-700 text-xs font-medium px-2 py-1 rounded-full">
                                                    <i class="fas fa-clock mr-1"></i>Terlambat
                                                </span>
                                            @else
                                                <span class="bg-green-100 text-green-700 text-xs font-medium px-2 py-1 rounded-full">
                                                    <i class="fas fa-check-circle mr-1"></i>Aktif
                                                </span>
                                            @endif
                                        </div>
                                        <p class="text-sm text-gray-500 mb-3">oleh {{ $borrowing->book->author }}</p>

                                        <div class="space-y-2 text-sm">
                                            <div class="flex justify-between items-center">
                                                <span class="text-gray-500 flex items-center">
                                                    <i class="fas fa-calendar-alt mr-1 text-blue-500"></i>Tgl Pinjam:
                                                </span>
                                                <span>{{ \Carbon\Carbon::parse($borrowing->borrow_date)->format('d M Y') }}</span>
                                            </div>
                                            <div class="flex justify-between items-center">
                                                <span class="text-gray-500 flex items-center">
                                                    <i class="fas fa-calendar-check mr-1 text-green-500"></i>Jatuh Tempo:
                                                </span>
                                                <span class="font-medium {{ $isOverdue ? 'text-red-600' : 'text-green-700' }}">
                                                    {{ \Carbon\Carbon::parse($borrowing->due_date)->format('d M Y') }}
                                                </span>
                                            </div>
                                            <div class="flex justify-between items-center">
                                                <span class="text-gray-500 flex items-center">
                                                    <i class="fas fa-coins mr-1 text-yellow-500"></i>Denda:
                                                </span>

                                                @php
                                                    $fine = $borrowing->fine_amount;
                                                @endphp

                                                @if ($fine > 0)
                                                    <span class="font-medium text-red-600">
                                                        Rp {{ number_format($fine, 0, ',', '.') }}
                                                    </span>
                                                @elseif ($borrowing->due_date->isFuture())
                                                    <span class="text-gray-400 italic">Belum jatuh tempo</span>
                                                @else
                                                    <span class="text-gray-400 italic">Tidak ada</span>
                                                @endif
                                            </div>
                                        </div>
                                        @if ($isOverdue)
                                            <div class="mt-3 bg-red-50 text-red-700 text-xs font-medium p-2 rounded text-center">
                                                <i class="fas fa-exclamation-triangle mr-1"></i>
                                                Terlambat {{ abs(floor(now()->diffInDays($borrowing->due_date))) }} hari
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @empty
                                <div class="col-span-2 bg-green-50 border border-green-200 text-green-700 text-center p-6 rounded-lg font-medium">
                                    <i class="fas fa-check-circle text-2xl mb-2"></i>
                                    <p>Tidak ada buku yang sedang dipinjam</p>
                                </div>
                            @endforelse
                        </div>
                    </div>

                    {{-- Riwayat Peminjaman --}}
                    <div class="animate-slide-up" style="animation-delay: 0.2s;">
                        <div class="flex items-center justify-between mb-3">
                            <h2 class="text-xl font-bold text-gray-700 flex items-center">
                                <i class="fas fa-history mr-2 text-gray-500"></i>
                                Riwayat Peminjaman
                            </h2>
                            <span class="bg-blue-100 text-blue-700 text-xs font-medium px-2 py-1 rounded-full">
                                {{ $returnedBorrowings->count() }} buku
                            </span>
                        </div>
                        <div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
                            <div class="overflow-x-auto">
                                <table class="w-full text-left text-sm">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="p-3 font-medium text-gray-600">Judul Buku</th>
                                            <th class="p-3 font-medium text-gray-600 hidden sm:table-cell">Tgl Pinjam</th>
                                            <th class="p-3 font-medium text-gray-600 hidden md:table-cell">Tgl Kembali</th>
                                            <th class="p-3 font-medium text-gray-600">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y">
                                        @forelse($returnedBorrowings as $borrowing)
                                            <tr class="hover:bg-gray-50 transition-colors">
                                                <td class="p-3">
                                                    <div class="flex items-center">
                                                        <div class="w-8 h-10 bg-green-100 rounded flex items-center justify-center mr-3">
                                                            <i class="fas fa-book text-green-600 text-xs"></i>
                                                        </div>
                                                        <span class="font-medium text-gray-800">{{ $borrowing->book->title }}</span>
                                                    </div>
                                                </td>
                                                <td class="p-3 text-gray-600 hidden sm:table-cell">
                                                    {{ \Carbon\Carbon::parse($borrowing->borrow_date)->format('d M Y') }}
                                                </td>
                                                <td class="p-3 text-gray-600 hidden md:table-cell">
                                                    {{ \Carbon\Carbon::parse($borrowing->return_date)->format('d M Y') }}
                                                </td>
                                                <td class="p-3">
                                                    <span class="bg-green-100 text-green-700 text-xs font-medium px-2 py-1 rounded-full">
                                                        <i class="fas fa-check-circle mr-1"></i>Selesai
                                                    </span>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4" class="text-center p-6 text-gray-500">
                                                    <i class="fas fa-inbox text-2xl mb-2 text-gray-300"></i>
                                                    <p>Belum ada riwayat pengembalian buku</p>
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
                    <div class="bg-white rounded-lg shadow-sm p-4 border border-gray-200 animate-slide-up" style="animation-delay: 0.3s;">
                        <div class="flex items-center mb-4">
                            <div class="w-16 h-16 bg-gradient-to-br from-green-400 to-emerald-500 rounded-full flex items-center justify-center text-white text-xl font-bold mr-3">
                                {{ substr($student->name, 0, 1) }}
                            </div>
                            <div>
                                <h3 class="text-lg font-bold text-gray-800">{{ $student->name }}</h3>
                                <p class="text-sm text-gray-500">{{ $student->class }}</p>
                            </div>
                        </div>
                        <ul class="space-y-3 text-sm text-gray-700">
                            <li class="flex items-center bg-gray-50 p-2 rounded">
                                <i class="fas fa-location-dot mr-3 text-green-600 w-4"></i>
                                <span class="truncate">{{ $ipAddress }}</span>
                            </li>
                            <li class="flex items-center bg-gray-50 p-2 rounded">
                                <i class="fas fa-id-badge mr-3 text-blue-600 w-4"></i>
                                <span>{{ $student->nis }}</span>
                            </li>
                            <li class="flex items-center bg-gray-50 p-2 rounded">
                                <i class="fas fa-envelope mr-3 text-purple-600 w-4"></i>
                                <span class="truncate">{{ $student->email }}</span>
                            </li>
                            <li class="flex items-center bg-gray-50 p-2 rounded">
                                <i class="fas fa-graduation-cap mr-3 text-yellow-600 w-4"></i>
                                <span>{{ $student->class }}</span>
                            </li>
                        </ul>
                    </div>

                    {{-- Aksi Cepat --}}
                    <div class="bg-white rounded-lg shadow-sm p-4 border border-gray-200 animate-slide-up" style="animation-delay: 0.4s;">
                        <h3 class="text-lg font-bold text-gray-800 mb-3 flex items-center">
                            <i class="fas fa-bolt text-yellow-500 mr-2"></i>Aksi Cepat
                        </h3>
                        <div class="space-y-3">
                            <a href="{{ route('books.index') }}"
                                class="block w-full text-center bg-gradient-to-r from-green-600 to-emerald-600 text-white font-medium py-2.5 px-3 rounded-lg hover:from-green-700 hover:to-emerald-700 transition-all duration-300 transform hover:-translate-y-0.5 shadow-md">
                                <i class="fas fa-search mr-2"></i>Cari Buku Baru
                            </a>
                            <a href="/chatbot"
                                class="block w-full text-center bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-medium py-2.5 px-3 rounded-lg hover:from-blue-700 hover:to-indigo-700 transition-all duration-300 transform hover:-translate-y-0.5 shadow-md">
                                <i class="fas fa-robot mr-2"></i>Chatbot Perpustakaan
                            </a>
                            <button onclick="openBorrowGuideModal()"
                                class="block w-full text-center bg-gradient-to-r from-yellow-500 to-orange-500 text-white font-medium py-2.5 px-3 rounded-lg hover:from-yellow-600 hover:to-orange-600 transition-all duration-300 transform hover:-translate-y-0.5 shadow-md">
                                <i class="fas fa-info-circle mr-2"></i>Petunjuk Peminjaman
                            </button>
                        </div>
                    </div>

                    <!-- {{-- Rekomendasi Buku --}}
                    <div class="bg-white rounded-lg shadow-sm p-4 border border-gray-200 animate-slide-up" style="animation-delay: 0.5s;">
                        <h3 class="text-lg font-bold text-gray-800 mb-3 flex items-center">
                            <i class="fas fa-star text-yellow-500 mr-2"></i>Rekomendasi Buku
                        </h3>
                        <div class="space-y-3">
                            @for ($i = 1; $i <= 3; $i++)
                                <div class="flex items-center space-x-3 cursor-pointer hover:bg-gray-50 p-2 rounded-lg transition-colors">
                                    <img src="" alt="Book Cover" class="w-12 h-16 object-cover rounded">
                                    <div class="flex-1">
                                        <h4 class="text-sm font-medium text-gray-800 line-clamp-1">Buku Rekomendasi {{ $i }}</h4>
                                        <p class="text-xs text-gray-500">Penulis {{ $i }}</p>
                                        <div class="flex text-yellow-400 text-xs mt-1">
                                            @for ($j = 1; $j <= 5; $j++)
                                                <i class="fas fa-star {{ $j <= 4 ? '' : 'text-gray-300' }}"></i>
                                            @endfor
                                        </div>
                                    </div>
                                </div>
                            @endfor
                        </div>
                    </div> -->
                </aside>
            </div>
        </div>
    </div>

    {{-- Modal Petunjuk dengan Desain Lebih Menarik --}}
    <div id="borrowGuideModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm flex items-center justify-center hidden z-50 p-4">
        <div class="bg-white rounded-lg max-w-md w-full shadow-xl p-5 transform transition-all modal-content">
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
<script src="{{ asset('assets/js/')  }}"></script>
@stop

@endsection