@extends('layouts.app')

@section('title', 'Dashboard Siswa' . ' - ' . config('app.name'))

@section('content')
    <div class="min-h-screen bg-gray-50 py-8">
        <div class="container mx-auto px-4">

            {{-- Header --}}
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-800">
                    Hai, <span class="text-green-600">{{ $student->name }}</span>
                </h1>
                <p class="text-gray-600 mt-1">
                    Selamat datang kembali di <span class="font-medium">{{ config('app.name') }}</span>
                </p>
            </div>

            <div class="lg:grid lg:grid-cols-3 lg:gap-6">
                {{-- Kolom Kiri (Konten Utama) --}}
                <div class="lg:col-span-2 space-y-6">

                    {{-- Buku Dipinjam --}}
                    <div>
                        <h2 class="text-xl font-bold text-gray-700 mb-3 flex items-center">
                            <i class="fas fa-book-reader mr-2 text-green-600"></i>
                            Buku yang Sedang Dipinjam
                        </h2>

                        <div class="grid md:grid-cols-2 gap-4">
                            @forelse ($currentBorrowings as $borrowing)
                                @php $isOverdue = now()->gt($borrowing->due_date); @endphp
                                <div class="bg-white border border-gray-200 rounded-lg shadow-sm hover:shadow-md transition-shadow">
                                    <div class="p-4">
                                        <h3 class="font-bold text-gray-800 mb-1">{{ $borrowing->book->title }}</h3>
                                        <p class="text-sm text-gray-500 mb-3">oleh {{ $borrowing->book->author }}</p>

                                        <div class="space-y-1 text-sm">
                                            <div class="flex justify-between">
                                                <span class="text-gray-500">Tgl Pinjam:</span>
                                                <span>{{ \Carbon\Carbon::parse($borrowing->borrow_date)->format('d M Y') }}</span>
                                            </div>
                                            <div class="flex justify-between">
                                                <span class="text-gray-500">Jatuh Tempo:</span>
                                                <span class="font-medium {{ $isOverdue ? 'text-red-600' : 'text-green-700' }}">
                                                    {{ \Carbon\Carbon::parse($borrowing->due_date)->format('d M Y') }}
                                                </span>
                                            </div>
                                            <div class="flex justify-between">
                                                <span class="text-gray-500 font-semibold">Denda:</span>

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
                                    <div class="h-1 {{ $isOverdue ? 'bg-red-500' : 'bg-green-500' }}"></div>
                                </div>
                            @empty
                                <div class="col-span-2 bg-green-50 text-green-700 text-center p-4 rounded-lg font-medium">
                                    Tidak ada buku yang sedang dipinjam
                                </div>
                            @endforelse
                        </div>
                    </div>

                    {{-- Riwayat Peminjaman --}}
                    <div>
                        <h2 class="text-xl font-bold text-gray-700 mb-3 flex items-center">
                            <i class="fas fa-history mr-2 text-gray-500"></i>
                            Riwayat Peminjaman
                        </h2>
                        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
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
                                            <tr class="hover:bg-gray-50">
                                                <td class="p-3 font-medium text-gray-800">{{ $borrowing->book->title }}</td>
                                                <td class="p-3 text-gray-600 hidden sm:table-cell">
                                                    {{ \Carbon\Carbon::parse($borrowing->borrow_date)->format('d M Y') }}
                                                </td>
                                                <td class="p-3 text-gray-600 hidden md:table-cell">
                                                    {{ \Carbon\Carbon::parse($borrowing->return_date)->format('d M Y') }}
                                                </td>
                                                <td class="p-3">
                                                    <span class="bg-green-100 text-green-700 text-xs font-medium px-2 py-1 rounded">
                                                        Selesai
                                                    </span>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4" class="text-center p-6 text-gray-500">
                                                    Belum ada riwayat pengembalian buku
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
                    <div class="bg-white rounded-lg shadow-sm p-4 border border-gray-200">
                        <h3 class="text-lg font-bold text-gray-800 mb-3 flex items-center">
                            <i class="fas fa-user-circle mr-2 text-green-600"></i> Profil Siswa
                        </h3>
                        <ul class="space-y-2 text-sm text-gray-700">
                            <li class="flex items-center">
                                <i class="fas fa-id-badge mr-2 text-gray-400 w-4"></i>
                                {{ $student->nis }}
                            </li>
                            <li class="flex items-center">
                                <i class="fas fa-envelope mr-2 text-gray-400 w-4"></i>
                                {{ $student->email }}
                            </li>
                            <li class="flex items-center">
                                <i class="fas fa-graduation-cap mr-2 text-gray-400 w-4"></i>
                                {{ $student->class }}
                            </li>
                        </ul>
                    </div>

                    {{-- Aksi Cepat --}}
                    <div class="bg-white rounded-lg shadow-sm p-4 border border-gray-200">
                        <h3 class="text-lg font-bold text-gray-800 mb-3">Aksi Cepat</h3>
                        <div class="space-y-3">
                            <a href="{{ route('books.index') }}"
                                class="block w-full text-center bg-green-600 text-white font-medium py-2 px-3 rounded hover:bg-green-700 transition-colors">
                                <i class="fas fa-search mr-2"></i>Cari Buku Baru
                            </a>
                            <a href="/chatbot"
                                class="block w-full text-center bg-green-600 text-white font-medium py-2 px-3 rounded hover:bg-green-700 transition-colors">
                                <i class="fas fa-robot mr-2"></i>Chatbot Perpustakaan
                            </a>
                            <button onclick="openBorrowGuideModal()"
                                class="block w-full text-center bg-yellow-100 text-yellow-700 font-medium py-2 px-3 rounded hover:bg-yellow-200 transition-colors">
                                <i class="fas fa-info-circle mr-2"></i>Petunjuk Peminjaman
                            </button>
                        </div>
                    </div>
                </aside>
            </div>
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