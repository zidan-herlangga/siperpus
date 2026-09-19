@extends('layouts.app')

@section('title', 'Riwayat Peminjaman')

@section('styles')
<style>
    .history-card {
        animation: fadeInUp 0.4s ease-out forwards;
        opacity: 0;
    }
    @keyframes fadeInUp {
        from { opacity: 0; transform: translateY(15px); }
        to { opacity: 1; transform: translateY(0); }
    }
    .history-card:nth-child(1) { animation-delay: 0.05s; }
    .history-card:nth-child(2) { animation-delay: 0.1s; }
    .history-card:nth-child(3) { animation-delay: 0.15s; }
    .history-card:nth-child(4) { animation-delay: 0.2s; }
    .history-card:nth-child(5) { animation-delay: 0.25s; }

    .filter-pill {
        transition: all 0.2s ease;
        border: 1px solid transparent;
    }
    .filter-pill.active {
        background-color: #006739;
        color: white;
        box-shadow: 0 4px 10px rgba(0, 103, 57, 0.25);
    }
    .filter-pill:not(.active) {
        background-color: #eaf3ed;
        color: #4b5d52;
    }
    .filter-pill:not(.active):hover {
        background-color: #d2e6da;
        color: #006739;
    }

    .no-scrollbar::-webkit-scrollbar { display: none; }
    .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }

    .history-container::-webkit-scrollbar { width: 4px; }
    .history-container::-webkit-scrollbar-track { background: transparent; }
    .history-container::-webkit-scrollbar-thumb { background: #d1d5db; border-radius: 10px; }
</style>
@endsection

@section('content')
<div class="min-h-screen bg-[#f4f6f1]">

    {{-- Header Halaman --}}
    <div class="bg-[#006739] relative overflow-hidden"
        style="background-image: radial-gradient(rgba(255,255,255,0.09) 1px, transparent 1px); background-size: 22px 22px;">
        <div class="container mx-auto px-4 py-7 md:py-9 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
            <div class="flex items-center gap-4">
                <div class="w-11 h-11 md:w-12 md:h-12 bg-white/10 rounded-xl flex items-center justify-center shrink-0">
                    <i class="fas fa-clock-rotate-left text-white text-lg"></i>
                </div>
                <div>
                    <h1 class="text-2xl md:text-3xl font-extrabold text-white tracking-tight">Riwayat Peminjaman</h1>
                    <p class="text-white/70 text-sm mt-0.5">Daftar buku yang pernah atau sedang Anda pinjam.</p>
                </div>
            </div>

            <div class="bg-white text-[#006739] px-4 py-2 rounded-full text-sm font-bold flex items-center gap-2 shadow-sm shrink-0 w-fit">
                <i class="fas fa-layer-group"></i>
                {{ $borrowings->total() }} Transaksi
            </div>
        </div>
    </div>

    <div class="container mx-auto px-4 py-6 pb-28 md:pb-12 history-container">

        {{-- Search & Filter --}}
        <div class="bg-white rounded-2xl border border-[#e2e9e1] shadow-sm p-3 md:p-4 mb-6">
            <form method="GET" action="{{ route('student.history') }}">
                <div class="flex flex-col md:flex-row gap-3">
                    <div class="flex-1 relative">
                        <i class="fas fa-search absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                        <input type="text" name="search" placeholder="Cari judul atau penulis buku..." value="{{ request('search') }}"
                            class="w-full pl-10 pr-4 py-2.5 bg-[#f4f6f1]/60 border border-[#e2e9e1] rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-emerald-100 focus:border-emerald-400 focus:bg-white">
                    </div>
                    <div class="flex gap-2 shrink-0">
                        <button type="submit"
                            class="flex items-center gap-2 bg-cta hover:bg-cta-dark text-[#16271d] px-5 py-2.5 rounded-xl text-sm font-semibold transition-colors">
                            <i class="fas fa-filter"></i>
                            Filter
                        </button>
                        @if(request('search') || request('status'))
                            <a href="{{ route('student.history') }}"
                                class="flex items-center justify-center bg-gray-100 text-gray-500 w-11 h-11 rounded-xl text-sm font-semibold hover:bg-gray-200 transition-colors">
                                <i class="fas fa-rotate-left"></i>
                            </a>
                        @endif
                    </div>
                </div>
            </form>

            <div class="mt-3 pt-3 border-t border-[#e2e9e1]/70 flex items-center gap-2 overflow-x-auto no-scrollbar">
                @php
                    $statuses = ['Semua' => null, 'Pending' => 'Pending', 'Dipinjam' => 'Dipinjam', 'Dikembalikan' => 'Dikembalikan', 'Batal' => 'Batal'];
                @endphp
                @foreach ($statuses as $label => $val)
                    <a href="{{ route('student.history', array_filter(['search' => request('search'), 'status' => $val])) }}"
                        class="filter-pill whitespace-nowrap px-4 py-2 rounded-full text-xs font-semibold {{ (($val === null && !request('status')) || ($val !== null && request('status') === $val)) ? 'active' : '' }}">
                        {{ $label }}
                    </a>
                @endforeach
            </div>
        </div>

        {{-- Daftar Riwayat --}}
        <div id="historyList">
            @forelse($borrowings as $borrowing)
                @php
                    $isOverdue = $borrowing->status === 'Dipinjam' && $borrowing->due_date < now();
                    $displayStatus = $isOverdue ? 'Terlambat' : $borrowing->status;
                    $statusConfig = [
                        'Pending' => ['bg' => 'bg-gray-100', 'text' => 'text-gray-600', 'icon' => 'fa-clock'],
                        'Dipinjam' => ['bg' => 'bg-amber-100', 'text' => 'text-amber-700', 'icon' => 'fa-spinner'],
                        'Terlambat' => ['bg' => 'bg-red-100', 'text' => 'text-red-700', 'icon' => 'fa-triangle-exclamation'],
                        'Dikembalikan' => ['bg' => 'bg-emerald-100', 'text' => 'text-emerald-700', 'icon' => 'fa-circle-check'],
                        'Batal' => ['bg' => 'bg-red-50', 'text' => 'text-red-400', 'icon' => 'fa-xmark'],
                    ];
                    $currStatus = $statusConfig[$displayStatus] ?? ['bg' => 'bg-gray-100', 'text' => 'text-gray-600', 'icon' => 'fa-circle-question'];
                @endphp
                <div class="history-card bg-white rounded-2xl border border-[#e2e9e1] shadow-sm mb-4 overflow-hidden transition-all hover:shadow-md hover:border-[#d2e6da]" data-status="{{ $displayStatus }}">

                    <div class="flex items-start gap-4 p-4 md:p-5">
                        {{-- Cover --}}
                        <div class="w-16 md:w-20 aspect-[3/4] rounded-lg overflow-hidden shadow-sm border border-[#e2e9e1] flex items-center justify-center shrink-0
                            {{ $borrowing->status === 'Dipinjam' ? 'bg-amber-50' : ($borrowing->status === 'Dikembalikan' ? 'bg-emerald-50' : 'bg-gray-50') }}">
                            @if (filter_var($borrowing->book->cover_image, FILTER_VALIDATE_URL))
                                <img src="{{ $borrowing->book->cover_image }}" class="w-full h-full object-cover" alt="{{ $borrowing->book->title }}" loading="lazy">
                            @elseif ($borrowing->book->cover_image)
                                <img src="{{ asset('storage/' . $borrowing->book->cover_image) }}" alt="{{ $borrowing->book->title }}" class="w-full h-full object-cover" loading="lazy">
                            @else
                                <i class="fas fa-book text-xl
                                    {{ $borrowing->status === 'Dipinjam' ? 'text-amber-400' : ($borrowing->status === 'Dikembalikan' ? 'text-emerald-400' : 'text-gray-400') }}"></i>
                            @endif
                        </div>

                        {{-- Info utama --}}
                        <div class="flex-1 min-w-0">
                            <div class="flex items-start justify-between gap-3">
                                <div class="min-w-0">
                                    <h3 class="font-bold text-gray-800 text-base leading-snug truncate">{{ $borrowing->book->title }}</h3>
                                    <p class="text-sm text-gray-500 mt-0.5 truncate">
                                        <i class="fas fa-user-pen text-xs mr-1 text-[#418f69]"></i>{{ $borrowing->book->author ?? 'Tidak diketahui' }}
                                    </p>
                                </div>
                                <span class="inline-flex items-center gap-1.5 text-xs font-bold px-3 py-1.5 rounded-full shrink-0 {{ $currStatus['bg'] }} {{ $currStatus['text'] }}">
                                    <i class="fas {{ $currStatus['icon'] }} text-[10px]"></i>
                                    {{ $displayStatus }}
                                </span>
                            </div>

                            {{-- Detail tanggal --}}
                            <div class="flex flex-wrap items-center gap-x-4 gap-y-1 mt-3 text-xs text-gray-500">
                                <span class="flex items-center gap-1.5">
                                    <i class="fas fa-calendar-plus text-[#418f69]"></i>
                                    {{ \Carbon\Carbon::parse($borrowing->borrow_date)->format('d M Y') }}
                                </span>
                                <span class="flex items-center gap-1.5 {{ $isOverdue ? 'text-red-500 font-semibold' : '' }}">
                                    <i class="fas fa-calendar-check {{ $isOverdue ? 'text-red-400' : 'text-[#418f69]' }}"></i>
                                    Tenggat: {{ \Carbon\Carbon::parse($borrowing->due_date)->format('d M Y') }}
                                </span>
                                @if($borrowing->return_date)
                                    <span class="flex items-center gap-1.5">
                                        <i class="fas fa-calendar-minus text-gray-400"></i>
                                        Kembali: {{ \Carbon\Carbon::parse($borrowing->return_date)->format('d M Y') }}
                                    </span>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- Strip bawah --}}
                    <div class="px-4 md:px-5 py-3 bg-[#f7f9f7] border-t border-[#e2e9e1]/70 flex flex-wrap items-center gap-x-4 gap-y-2">
                        @if($borrowing->fine_amount > 0)
                            <span class="inline-flex items-center gap-1.5 text-xs text-red-600 font-bold">
                                <i class="fas fa-coins"></i>
                                Denda: Rp {{ number_format($borrowing->fine_amount, 0, ',', '.') }}
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1.5 text-xs text-gray-400">
                                <i class="fas fa-circle-check"></i>
                                {{ $borrowing->fine_amount === null ? 'Tidak ada denda' : 'Bebas denda' }}
                            </span>
                        @endif

                        @if($displayStatus === 'Terlambat')
                            <span class="inline-flex items-center gap-1 text-xs text-red-500 font-bold">
                                <i class="fas fa-triangle-exclamation"></i>
                                {{ abs(floor(now()->diffInDays($borrowing->due_date))) }} hari terlambat
                            </span>
                        @endif

                        <a href="{{ route('books.show', $borrowing->book) }}" wire:navigate.prefetch="false"
                            class="ml-auto inline-flex items-center gap-1.5 text-xs text-[#006739] font-semibold hover:text-[#00502d] transition-colors">
                            Lihat Buku
                            <i class="fas fa-arrow-right text-[10px]"></i>
                        </a>
                    </div>
                </div>
            @empty
                <div class="text-center py-20">
                    <div class="w-24 h-24 bg-[#eaf3ed] rounded-full flex items-center justify-center mx-auto mb-5 border border-[#d2e6da]">
                        <i class="fas fa-folder-open text-4xl text-[#74ae91]"></i>
                    </div>
                    <h3 class="text-lg font-bold text-gray-700">Belum Ada Riwayat</h3>
                    <p class="text-sm text-gray-500 mt-1 max-w-xs mx-auto">Kamu belum pernah meminjam buku. Yuk, jelajahi katalog dan pinjam buku pertamamu!</p>
                    <a href="{{ route('books.index') }}" wire:navigate.prefetch="false"
                        class="inline-flex items-center gap-2 mt-6 bg-cta hover:bg-cta-dark text-[#16271d] text-sm font-bold px-6 py-2.5 rounded-xl shadow-md transition-colors">
                        <i class="fas fa-book-open"></i>
                        Jelajahi Katalog
                    </a>
                </div>
            @endforelse
        </div>

        {{-- Pagination --}}
        @if($borrowings->hasPages())
            <div class="mt-8 flex justify-center">
                {{ $borrowings->links() }}
            </div>
        @endif

    </div>
</div>
@endsection