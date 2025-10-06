@extends('layouts.app')

@section('title', 'Dashboard Siswa')

@section('content')
<div class="min-h-screen bg-gray-100 py-12">
    <div class="container mx-auto px-4">

        <div class="bg-white rounded-2xl shadow-lg p-8 mb-8 fade-in">
            <h1 class="text-3xl font-bold text-gray-800">Selamat Datang, {{ $student->name }}!</h1>
            <p class="text-gray-600 mt-2">Ini adalah ringkasan aktivitas perpustakaan Anda.</p>
        </div>

        <div class="mb-12">
            <h2 class="text-2xl font-semibold text-gray-700 mb-6 flex items-center">
                <i class="fas fa-book-reader mr-3 text-blue-600"></i>
                Buku yang Sedang Dipinjam
            </h2>
            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                @forelse ($currentBorrowings as $borrowing)
                    @php
                        $isOverdue = now()->gt($borrowing->due_date);
                    @endphp
                    <div class="book-card bg-white rounded-2xl shadow-md overflow-hidden border-l-4 {{ $isOverdue ? 'border-red-500' : 'border-green-500' }}">
                        <div class="p-6">
                            <h3 class="font-bold text-xl text-gray-800 mb-2">{{ $borrowing->book->title }}</h3>
                            <p class="text-gray-600 text-sm mb-4">oleh {{ $borrowing->book->author }}</p>

                            <div class="space-y-3 text-sm">
                                <div class="flex justify-between">
                                    <span class="font-semibold text-gray-500">Tgl Pinjam:</span>
                                    <span>{{ \Carbon\Carbon::parse($borrowing->borrow_date)->format('d M Y') }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="font-semibold text-gray-500">Jatuh Tempo:</span>
                                    <span class="font-bold {{ $isOverdue ? 'text-red-600' : 'text-gray-800' }}">
                                        {{ \Carbon\Carbon::parse($borrowing->due_date)->format('d M Y') }}
                                    </span>
                                </div>
                            </div>

                            @if($isOverdue)
                            <div class="mt-4 bg-red-50 text-red-700 text-xs font-semibold p-3 rounded-lg text-center">
                                <i class="fas fa-exclamation-triangle mr-2"></i>
                                TERLAMBAT {{ now()->diffInDays($borrowing->due_date) }} HARI
                            </div>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="col-span-1 md:col-span-2 lg:col-span-3 bg-gray-50 text-gray-500  p-6 rounded-2xl text-center">
                        <p>Anda sedang tidak meminjam buku apa pun.</p>
                    </div>
                @endforelse
            </div>
        </div>

        <div>
            <h2 class="text-2xl font-semibold text-gray-700 mb-6 flex items-center">
                <i class="fas fa-history mr-3 text-green-600"></i>
                Riwayat Peminjaman
            </h2>
            <div class="bg-white rounded-2xl shadow-md overflow-hidden">
                <table class="w-full text-left">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="p-4 font-semibold text-sm text-gray-600">Judul Buku</th>
                            <th class="p-4 font-semibold text-sm text-gray-600 hidden md:table-cell">Tgl Pinjam</th>
                            <th class="p-4 font-semibold text-sm text-gray-600 hidden md:table-cell">Tgl Kembali</th>
                            <th class="p-4 font-semibold text-sm text-gray-600">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y">
                        @forelse($returnedBorrowings as $borrowing)
                        <tr>
                            <td class="p-4 font-medium text-gray-800">{{ $borrowing->book->title }}</td>
                            <td class="p-4 text-gray-600 hidden md:table-cell">{{ \Carbon\Carbon::parse($borrowing->borrow_date)->format('d M Y') }}</td>
                            <td class="p-4 text-gray-600 hidden md:table-cell">{{ \Carbon\Carbon::parse($borrowing->return_date)->format('d M Y') }}</td>
                            <td class="p-4">
                                <span class="bg-green-100 text-green-800 text-xs font-semibold px-3 py-1 rounded-full">Dikembalikan</span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center p-8 text-gray-500">
                                Belum ada riwayat pengembalian buku.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>
@endsection