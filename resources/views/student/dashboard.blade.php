@extends('layouts.app')

@section('title', 'Dashboard Siswa')
{{-- {{ dd(Auth::guard('student')->check(), Auth::guard('student')->user()) }} --}}

@section('content')
    <div class="bg-gray-100 py-12">
        <div class="container mx-auto px-4">

            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-800">Selamat Datang Kembali!</h1>
                <p class="text-gray-600 mt-1">Ini adalah ringkasan aktivitas perpustakaan Anda, <span class="font-semibold text-green-700">{{ $student->name }}</span>.</p>
            </div>
            
            <div class="lg:grid lg:grid-cols-3 lg:gap-8">
                
                {{-- Kolom Kiri (Konten Utama) --}}
                <div class="lg:col-span-2 space-y-12">
                    
                    <div>
                        <h2 class="text-2xl font-semibold text-gray-700 mb-6 flex items-center">
                            <i class="fas fa-book-reader mr-3 text-green-600"></i>
                            Buku yang Sedang Dipinjam
                        </h2>
                        <div class="grid md:grid-cols-2 gap-8">
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

                                        @if ($isOverdue)
                                            <div class="mt-4 bg-red-50 text-red-700 text-xs font-semibold p-3 rounded-lg text-center">
                                                <i class="fas fa-exclamation-triangle mr-2"></i>
                                                TERLAMBAT {{ now()->diffInDays($borrowing->due_date) }} HARI
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @empty
                                <div class="col-span-1 md:col-span-2 bg-green-50 text-green-700 p-6 rounded-2xl text-center">
                                    <p>Anda sedang tidak meminjam buku apa pun. Saatnya cari buku baru!</p>
                                </div>
                            @endforelse
                        </div>
                    </div>

                    <div>
                        <h2 class="text-2xl font-semibold text-gray-700 mb-6 flex items-center">
                            <i class="fas fa-history mr-3 text-gray-500"></i>
                            Riwayat Peminjaman
                        </h2>
                        <div class="bg-white rounded-2xl shadow-md overflow-hidden">
                            <div class="overflow-x-auto">
                                <table class="w-full text-left">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="p-4 font-semibold text-sm text-gray-600">Judul Buku</th>
                                            <th class="p-4 font-semibold text-sm text-gray-600 hidden sm:table-cell">Tgl Pinjam</th>
                                            <th class="p-4 font-semibold text-sm text-gray-600 hidden md:table-cell">Tgl Kembali</th>
                                            <th class="p-4 font-semibold text-sm text-gray-600">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y">
                                        @forelse($returnedBorrowings as $borrowing)
                                        <tr>
                                            <td class="p-4 font-medium text-gray-800">{{ $borrowing->book->title }}</td>
                                            <td class="p-4 text-gray-600 hidden sm:table-cell">{{ \Carbon\Carbon::parse($borrowing->borrow_date)->format('d M Y') }}</td>
                                            <td class="p-4 text-gray-600 hidden md:table-cell">{{ \Carbon\Carbon::parse($borrowing->return_date)->format('d M Y') }}</td>
                                            <td class="p-4">
                                                <span class="bg-gray-100 text-gray-800 text-xs font-semibold px-3 py-1 rounded-full">Selesai</span>
                                            </td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="4" class="text-center p-8 text-gray-500">Belum ada riwayat pengembalian buku.</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Kolom Kanan (Sidebar) --}}
                <div class="lg:col-span-1 space-y-8 mt-12 lg:mt-0">
                    <div class="bg-white rounded-2xl shadow-lg p-6">
                        <h3 class="text-xl font-bold text-gray-800 mb-4">Profil Siswa</h3>
                        <ul class="space-y-3 text-sm">
                            <li class="flex items-center"><i class="fas fa-user fa-fw mr-3 text-gray-400"></i> <span class="font-semibold">{{ $student->name }}</span></li>
                            <li class="flex items-center"><i class="fas fa-id-card fa-fw mr-3 text-gray-400"></i> <span>NIS: {{ $student->nis }}</span></li>
                            <li class="flex items-center"><i class="fas fa-envelope fa-fw mr-3 text-gray-400"></i> <span>{{ $student->email }}</span></li>
                            <li class="flex items-center"><i class="fas fa-graduation-cap fa-fw mr-3 text-gray-400"></i> <span>Kelas: {{ $student->class }}</span></li>
                        </ul>
                    </div>

                    <div class="bg-white rounded-2xl shadow-lg p-6">
                        <h3 class="text-xl font-bold text-gray-800 mb-4">Aksi Cepat</h3>
                        <div class="space-y-4">
                            <a href="{{ route('books.index') }}" class="block w-full text-center gradient-bg text-white font-semibold py-3 px-4 rounded-lg hover:opacity-90 transition duration-300 transform hover:scale-105">
                                <i class="fas fa-search mr-2"></i>Cari Buku Baru
                            </a>
                            <button onclick="openBorrowGuideModal()" class="block w-full text-center bg-gray-200 text-gray-700 font-semibold py-3 px-4 rounded-lg hover:bg-gray-300 transition duration-300">
                                <i class="fas fa-info-circle mr-2"></i>Petunjuk Peminjaman
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="borrowGuideModal" class="fixed inset-0 bg-black bg-opacity-60 flex items-center justify-center p-4 hidden z-50">
        <div class="bg-white rounded-2xl max-w-lg w-full shadow-xl">
            <div class="p-6"> 
                <div class="flex justify-between items-center mb-5"> 
                    <h3 class="text-xl font-bold text-gray-800">Petunjuk Peminjaman Buku</h3> 
                    <button onclick="closeBorrowGuideModal()" class="text-gray-600 hover:text-gray-700"> 
                        <i class="fas fa-times text-xl"></i> 
                    </button> 
                </div> 
                <div class="space-y-4 text-gray-700 text-sm leading-relaxed"> 
                    <p>Berikut adalah langkah-langkah untuk meminjam buku di perpustakaan kami:</p>
                        <ol class="list-decimal list-inside space-y-2"> 
                            <li><strong>Login atau Daftar:</strong> 
                                Pastikan kamu sudah masuk ke akun siswa. Jika belum, gunakan tombol Login atau Daftar di modal peminjaman.
                            </li> 
                            <li><strong>Pilih Buku:</strong> 
                                Telusuri daftar buku yang tersedia dan klik tombol "Pinjam" pada buku yang kamu inginkan.
                            </li> 
                            <li><strong>Konfirmasi Peminjaman:</strong> 
                                Pada modal peminjaman, periksa detail tanggal peminjaman dan jatuh tempo, lalu klik "Konfirmasi Peminjaman".
                            </li>
                            <li><strong>Ambil Buku:</strong>
                                Setelah konfirmasi, kamu bisa mengambil buku tersebut di perpustakaan sesuai aturan yang berlaku.
                            </li>
                            <li><strong>Kembalikan Tepat Waktu:</strong>
                                Pastikan mengembalikan buku sebelum tanggal jatuh tempo untuk menghindari denda keterlambatan.
                            </li> 
                        </ol> 
                        <p class="font-semibold">Jika ada pertanyaan, silakan hubungi petugas perpustakaan atau admin.</p> 
                    </div>
                </div>
            </div>
        </div>
    </div>
    
@endsection