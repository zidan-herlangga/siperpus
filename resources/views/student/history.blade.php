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

    .filter-tab {
        transition: all 0.2s ease;
    }
    .filter-tab.active {
        background-color: #065f46;
        color: white;
        box-shadow: 0 4px 10px rgba(6, 95, 70, 0.25);
    }
    .filter-tab:not(.active):hover {
        background-color: #f3f4f6;
    }

    .history-container::-webkit-scrollbar { width: 4px; }
    .history-container::-webkit-scrollbar-track { background: transparent; }
    .history-container::-webkit-scrollbar-thumb { background: #d1d5db; border-radius: 10px; }
</style>
@endsection

@section('content')
<div class="min-h-screen bg-gray-50">
    
    {{-- Header Halaman --}}
    <div class="bg-white border-b border-gray-100 shadow-sm">
        <div class="container mx-auto px-4 py-6 md:py-8">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                <div>
                    <h1 class="text-2xl md:text-3xl font-extrabold text-gray-800 flex items-center gap-3">
                        <div class="w-10 h-10 bg-emerald-100 rounded-xl flex items-center justify-center">
                            <i class="fas fa-clock-rotate-left text-emerald-600 text-lg"></i>
                        </div>
                        Riwayat Peminjaman
                    </h1>
                    <p class="text-sm text-gray-500 mt-1 ml-[52px]">Daftar buku yang pernah atau sedang Anda pinjam.</p>
                </div>

                <div class="flex items-center gap-3 ml-[52px] md:ml-0">
                    <div class="bg-blue-50 text-blue-700 px-4 py-2 rounded-xl text-sm font-semibold flex items-center gap-2 shadow-sm">
                        <i class="fas fa-layer-group"></i>
                        Total: {{ $borrowings->total() }}
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container mx-auto px-4 py-6 pb-28 md:pb-12 history-container">
        
        {{-- Search & Filter --}}
        <form method="GET" action="{{ route('student.history') }}" class="mb-6">
            <div class="flex flex-col md:flex-row gap-3">
                <div class="flex-1 relative">
                    <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                    <input type="text" name="search" placeholder="Cari judul buku..." value="{{ request('search') }}"
                        class="w-full pl-9 pr-4 py-2.5 bg-white border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-emerald-100 focus:border-emerald-400">
                </div>
                <div class="flex gap-2">
                    <select name="status" class="px-4 py-2.5 bg-white border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-emerald-100 focus:border-emerald-400">
                        <option value="">Semua Status</option>
                        <option value="Pending" {{ request('status') == 'Pending' ? 'selected' : '' }}>Pending</option>
                        <option value="Dipinjam" {{ request('status') == 'Dipinjam' ? 'selected' : '' }}>Dipinjam</option>
                        <option value="Dikembalikan" {{ request('status') == 'Dikembalikan' ? 'selected' : '' }}>Dikembalikan</option>
                        <option value="Batal" {{ request('status') == 'Batal' ? 'selected' : '' }}>Batal</option>
                    </select>
                    <button type="submit" class="bg-emerald-600 text-white px-5 py-2.5 rounded-xl text-sm font-semibold hover:bg-emerald-700 transition-colors">
                        <i class="fas fa-filter"></i>
                    </button>
                    @if(request('search') || request('status'))
                        <a href="{{ route('student.history') }}" class="bg-gray-100 text-gray-600 px-4 py-2.5 rounded-xl text-sm font-semibold hover:bg-gray-200 transition-colors flex items-center">
                            <i class="fas fa-times"></i>
                        </a>
                    @endif
                </div>
            </div>
        </form>

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
                <div class="history-card bg-white rounded-2xl shadow-sm border border-gray-100 mb-4 overflow-hidden transition-all hover:shadow-md" data-status="{{ $displayStatus }}">
                    
                    <div class="p-4 md:p-5 flex items-start gap-4">
                        
                        <div class="w-14 h-20 rounded-xl flex-shrink-0 overflow-hidden shadow-sm border border-gray-100 flex items-center justify-center
                            {{ $borrowing->status === 'Dipinjam' ? 'bg-amber-50' : ($borrowing->status === 'Dikembalikan' ? 'bg-emerald-50' : 'bg-gray-50') }}">
                            @if (filter_var($borrowing->book->cover_image, FILTER_VALIDATE_URL))
                                    <img src="{{ $borrowing->book->cover_image }}" class="w-full h-full object-cover" alt="{{ $borrowing->book->title }}" loading="lazy">
                                    @elseif ($borrowing->book->cover_image)
                                    <img src="{{ asset('storage/' . $borrowing->book->cover_image) }}" alt="{{ $borrowing->book->title }}" class="w-full h-full object-cover" loading="lazy">
                            @else
                                <i class="fas fa-book text-2xl 
                                    {{ $borrowing->status === 'Dipinjam' ? 'text-amber-400' : ($borrowing->status === 'Dikembalikan' ? 'text-emerald-400' : 'text-gray-400') }}"></i>
                            @endif
                        </div>

                        <div class="flex-grow min-w-0">
                            <h3 class="font-bold text-gray-800 text-base leading-snug truncate">
                                {{ $borrowing->book->title }}
                            </h3>
                            <p class="text-sm text-gray-500 mt-1 truncate">
                                <i class="fas fa-user-pen text-xs mr-1"></i>{{ $borrowing->book->author ?? 'Tidak diketahui' }}
                            </p>
                            
                            <div class="flex flex-wrap items-center gap-x-4 gap-y-1 mt-3 text-xs text-gray-500">
                                <span class="flex items-center gap-1.5">
                                    <i class="fas fa-calendar-plus text-emerald-500"></i>
                                    {{ \Carbon\Carbon::parse($borrowing->borrow_date)->format('d M Y') }}
                                </span>
                                <span class="flex items-center gap-1.5">
                                    <i class="fas fa-calendar-check text-blue-500"></i>
                                    Tenggat: {{ \Carbon\Carbon::parse($borrowing->due_date)->format('d M Y') }}
                                </span>
                                @if($borrowing->return_date)
                                    <span class="flex items-center gap-1.5">
                                        <i class="fas fa-calendar-minus text-gray-400"></i>
                                        Kembali: {{ \Carbon\Carbon::parse($borrowing->return_date)->format('d M Y') }}
                                    </span>
                                @endif
                                @if($borrowing->fine_amount > 0)
                                    <span class="flex items-center gap-1.5 text-red-500 font-semibold">
                                        <i class="fas fa-coins"></i>
                                        Denda: Rp {{ number_format($borrowing->fine_amount, 0, ',', '.') }}
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="flex-shrink-0 ml-2">
                            <span class="inline-flex items-center gap-1.5 text-xs font-bold px-3 py-1.5 rounded-full {{ $currStatus['bg'] }} {{ $currStatus['text'] }}">
                                <i class="fas {{ $currStatus['icon'] }} text-[10px]"></i>
                                {{ $displayStatus }}
                            </span>
                        </div>
                    </div>

                    @if($displayStatus === 'Terlambat')
                    <div class="bg-red-50 border-t border-red-100 px-5 py-3 flex items-center justify-between">
                        <p class="text-xs text-red-600 font-medium">
                            <i class="fas fa-info-circle mr-1"></i>
                            Segera kembalikan buku untuk menghindari sanksi.
                        </p>
                        <span class="text-xs text-red-500 font-bold">{{ abs(floor(now()->diffInDays($borrowing->due_date))) }} hari terlambat</span>
                    </div>
                    @endif

                </div>
            @empty
                <div class="text-center py-20">
                    <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-5">
                        <i class="fas fa-folder-open text-4xl text-gray-300"></i>
                    </div>
                    <h3 class="text-lg font-bold text-gray-400">Belum Ada Riwayat</h3>
                    <p class="text-sm text-gray-400 mt-1 max-w-xs mx-auto">Kamu belum pernah meminjam buku. Yuk, jelajahi katalog dan pinjam buku pertamamu!</p>
                    <a href="{{ route('books.index') }}" wire:navigate.prefetch="false" 
                       class="inline-flex items-center gap-2 mt-6 bg-emerald-600 text-white text-sm font-semibold px-6 py-2.5 rounded-xl shadow-md hover:bg-emerald-700 transition-colors">
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

@section('scripts')
<script>
    function filterHistory(status) {
        document.querySelectorAll('.filter-tab').forEach(tab => {
            tab.classList.remove('active');
            if(tab.getAttribute('data-filter') === status) {
                tab.classList.add('active');
            }
        });

        const cards = document.querySelectorAll('.history-card');
        cards.forEach(card => {
            const cardStatus = card.getAttribute('data-status');
            if (status === 'Semua' || cardStatus === status) {
                card.style.display = 'block';
                card.style.opacity = '1';
                card.style.transform = 'translateY(0)';
            } else {
                card.style.display = 'none';
                card.style.opacity = '0';
                card.style.transform = 'translateY(15px)';
            }
        });
    }
</script>
@endsection