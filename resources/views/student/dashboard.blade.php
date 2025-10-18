@extends('layouts.app')

@section('title', 'Dashboard Siswa')

@section('content')
    <div class="min-h-screen bg-gradient-to-br from-green-50 to-yellow-50 py-12">
        <div class="container mx-auto px-6">

            {{-- Header --}}
            <div class="mb-10">
                <h1 class="text-4xl font-extrabold text-gray-800 tracking-tight">
                    Hai, <span class="text-green-600">{{ $student->name }}</span> 👋
                </h1>
                <p class="text-gray-600 mt-2">
                    Selamat datang kembali di <span class="font-semibold">Siperpus</span>! Yuk lihat aktivitasmu hari ini.
                </p>
            </div>

            <div class="lg:grid lg:grid-cols-3 lg:gap-10">
                {{-- Kolom Kiri (Konten Utama) --}}
                <div class="lg:col-span-2 space-y-10">

                    {{-- Buku Dipinjam --}}
                    <div>
                        <h2 class="text-2xl font-bold text-gray-700 mb-4 flex items-center">
                            <i class="fas fa-book-reader mr-3 text-green-600"></i>
                            Buku yang Sedang Dipinjam
                        </h2>

                        <div class="grid md:grid-cols-2 gap-6">
                            @forelse ($currentBorrowings as $borrowing)
                                @php $isOverdue = now()->gt($borrowing->due_date); @endphp
                                <div
                                    class="relative group bg-white/70 backdrop-blur-xl border border-gray-200 rounded-2xl shadow-lg hover:shadow-2xl transition-all duration-300 overflow-hidden">
                                    <div class="p-6">
                                        <h3 class="font-bold text-lg text-gray-800 mb-1">{{ $borrowing->book->title }}</h3>
                                        <p class="text-sm text-gray-500 mb-4">oleh {{ $borrowing->book->author }}</p>

                                        <div class="space-y-2 text-sm">
                                            <div class="flex justify-between">
                                                <span class="font-medium text-gray-500">Tgl Pinjam:</span>
                                                <span>{{ \Carbon\Carbon::parse($borrowing->borrow_date)->format('d M Y') }}</span>
                                            </div>
                                            <div class="flex justify-between">
                                                <span class="font-medium text-gray-500">Jatuh Tempo:</span>
                                                <span
                                                    class="font-bold {{ $isOverdue ? 'text-red-600' : 'text-green-700' }}">
                                                    {{ \Carbon\Carbon::parse($borrowing->due_date)->format('d M Y') }}
                                                </span>
                                            </div>
                                        </div>
                                        @if ($isOverdue)
                                            <div
                                                class="mt-4 bg-red-100 text-red-700 text-xs font-semibold p-3 rounded-lg text-center">
                                                <i class="fas fa-exclamation-triangle mr-1"></i>
                                                Terlambat {{ abs(floor(now()->diffInDays($borrowing->due_date))) }} hari
                                            </div>
                                        @endif
                                    </div>
                                    <div
                                        class="absolute left-0 top-0 h-full w-1 {{ $isOverdue ? 'bg-red-500' : 'bg-green-500' }}">
                                    </div>
                                </div>
                            @empty
                                <div class="col-span-2 bg-green-100 text-green-700 text-center p-6 rounded-2xl font-medium">
                                    Tidak ada buku yang sedang kamu pinjam 📚
                                </div>
                            @endforelse
                        </div>
                    </div>

                    {{-- Riwayat Peminjaman --}}
                    <div>
                        <h2 class="text-2xl font-bold text-gray-700 mb-4 flex items-center">
                            <i class="fas fa-history mr-3 text-gray-500"></i>
                            Riwayat Peminjaman
                        </h2>
                        <div
                            class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-lg overflow-hidden border border-gray-200">
                            <div class="overflow-x-auto">
                                <table class="w-full text-left text-sm">
                                    <thead class="bg-gray-100">
                                        <tr>
                                            <th class="p-4 font-semibold text-gray-600">Judul Buku</th>
                                            <th class="p-4 font-semibold text-gray-600 hidden sm:table-cell">Tgl Pinjam</th>
                                            <th class="p-4 font-semibold text-gray-600 hidden md:table-cell">Tgl Kembali
                                            </th>
                                            <th class="p-4 font-semibold text-gray-600">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y">
                                        @forelse($returnedBorrowings as $borrowing)
                                            <tr class="hover:bg-gray-50 transition">
                                                <td class="p-4 font-medium text-gray-800">{{ $borrowing->book->title }}</td>
                                                <td class="p-4 text-gray-600 hidden sm:table-cell">
                                                    {{ \Carbon\Carbon::parse($borrowing->borrow_date)->format('d M Y') }}
                                                </td>
                                                <td class="p-4 text-gray-600 hidden md:table-cell">
                                                    {{ \Carbon\Carbon::parse($borrowing->return_date)->format('d M Y') }}
                                                </td>
                                                <td class="p-4">
                                                    <span
                                                        class="bg-green-100 text-green-700 text-xs font-semibold px-3 py-1 rounded-full">
                                                        Selesai
                                                    </span>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4" class="text-center p-8 text-gray-500">Belum ada riwayat
                                                    pengembalian buku.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Sidebar --}}
                <aside class="lg:col-span-1 space-y-8 mt-10 lg:mt-0">
                    <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-lg p-6 border border-gray-200">
                        <h3 class="text-xl font-bold text-gray-800 mb-4 flex items-center">
                            <i class="fas fa-user-circle mr-2 text-green-600"></i> Profil Siswa
                        </h3>
                        <ul class="space-y-3 text-sm text-gray-700">
                            <li class="flex items-center"><i class="fas fa-id-badge mr-3 text-gray-400"></i>
                                {{ $student->nis }}</li>
                            <li class="flex items-center"><i class="fas fa-envelope mr-3 text-gray-400"></i>
                                {{ $student->email }}</li>
                            <li class="flex items-center"><i class="fas fa-graduation-cap mr-3 text-gray-400"></i>
                                {{ $student->class }}</li>
                        </ul>
                    </div>

                    <div class="bg-white/80 backdrop-blur-xl rounded-2xl shadow-lg p-6 border border-gray-200">
                        <h3 class="text-xl font-bold text-gray-800 mb-4">Aksi Cepat</h3>
                        <div class="space-y-4">
                            <a href="{{ route('books.index') }}"
                                class="block w-full text-center bg-green-600 text-white font-semibold py-3 px-4 rounded-lg hover:bg-green-500 transition duration-300 transform hover:scale-105">
                                <i class="fas fa-search mr-2"></i>Cari Buku Baru
                            </a>
                            <a href="/chatbot"
                                class="block w-full text-center bg-green-600 text-white font-semibold py-3 px-4 rounded-lg hover:bg-green-500 transition duration-300 transform hover:scale-105">
                                Chatbot
                            </a>
                            <button onclick="openBorrowGuideModal()"
                                class="block w-full text-center bg-yellow-100 text-yellow-700 font-semibold py-3 px-4 rounded-lg hover:bg-yellow-200 transition duration-300">
                                <i class="fas fa-info-circle mr-2"></i>Petunjuk Peminjaman
                            </button>
                        </div>
                    </div>
                </aside>
            </div>
        </div>
    </div>

    {{-- Modal Petunjuk --}}
    <div id="borrowGuideModal" class="fixed inset-0 bg-black/50 flex items-center justify-center hidden z-50">
        <div class="bg-white rounded-2xl max-w-lg w-full shadow-xl p-6 animate-fade-in">
            <div class="flex justify-between items-center mb-5">
                <h3 class="text-xl font-bold text-gray-800">Petunjuk Peminjaman Buku</h3>
                <button onclick="closeBorrowGuideModal()" class="text-gray-600 hover:text-gray-800">
                    <i class="fas fa-times"></i>
                </button>
            </div>
            <ol class="list-decimal list-inside text-gray-700 space-y-2 text-sm leading-relaxed">
                <li>Login atau daftar terlebih dahulu sebelum meminjam buku.</li>
                <li>Pilih buku dari katalog, lalu klik tombol “Pinjam”.</li>
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
