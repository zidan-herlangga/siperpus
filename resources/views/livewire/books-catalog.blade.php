<div>
    {{-- Search & Filter Bar (Sticky) --}}
    <div class="bg-white/90 backdrop-blur-md rounded-2xl p-5 mb-8 border border-gray-100 shadow-sm transition-all duration-300" id="filterBar">
        <div class="grid md:grid-cols-5 gap-4 items-end">
            {{-- Search (REALTIME) --}}
            <div class="md:col-span-2">
                <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">Cari Buku</label>
                <div class="relative">
                    <i class="fas fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-gray-400 text-sm"></i>
                    <!-- MAGIC DISINI: wire:model.live akan mengirim data saat mengetik -->
                    <input type="text" wire:model.live="search" placeholder="Judul, pengarang, ISBN..." 
                        class="input-modern w-full pl-10 pr-4 py-2.5 rounded-xl bg-white outline-none text-gray-800 placeholder-gray-400 text-sm">
                </div>
            </div>

            {{-- Category (REALTIME) --}}
            <div>
                <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">Kategori</label>
                <select wire:model.live="category"
                    class="input-modern w-full px-4 py-2.5 rounded-xl bg-white outline-none text-gray-800 text-sm appearance-none cursor-pointer">
                    <option value="">Semua</option>
                    @foreach ($categories as $id => $name)
                        <option value="{{ $id }}">{{ $name }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Sort (REALTIME) --}}
            <div>
                <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">Urutkan</label>
                <select wire:model.live="sort"
                    class="input-modern w-full px-4 py-2.5 rounded-xl bg-white outline-none text-gray-800 text-sm appearance-none cursor-pointer">
                    <option value="newest">Terbaru</option>
                    <option value="oldest">Terlama</option>
                    <option value="title_asc">Judul A-Z</option>
                    <option value="title_desc">Judul Z-A</option>
                </select>
            </div>

            {{-- Buttons --}}
            <div class="flex gap-2">
                <!-- Tombol Cari dihilangkan, karena sudah realtime -->
                <!-- Tombol Reset mengarah ke fungsi PHP resetFilters() -->
                <button wire:click="resetFilters"
                    class="flex-1 bg-gray-100 hover:bg-gray-200 text-gray-600 px-4 py-2.5 rounded-xl font-semibold transition-all text-center text-sm flex items-center justify-center gap-2 border border-gray-200">
                    <i class="fas fa-arrow-rotate-left text-xs"></i> Reset
                </button>
            </div>
        </div>
        
        {{-- Loading Indicator Kecil saat sedang mencari --}}
        <div class="mt-3 flex items-center gap-2 text-xs text-gray-400" wire:loading.remove>
            <span>Ketik untuk mencari buku...</span>
        </div>
        <div class="mt-3 flex items-center gap-2 text-xs text-emerald-600 font-medium" wire:loading>
            <i class="fas fa-spinner fa-spin"></i>
            <span>Mencari...</span>
        </div>
    </div>

    {{-- Filter Summary --}}
    @if ($search || $category)
        <div class="mb-6 text-center text-sm text-gray-500 reveal">
            Menampilkan <span class="font-bold text-gray-800">{{ $books->total() }}</span> hasil
            @if ($search)
                untuk "<span class="font-semibold text-emerald-600">{{ $search }}</span>"
            @endif
            @if ($category)
                di kategori 
                    <span class="bg-emerald-50 text-emerald-700 px-3 py-1 rounded-full text-xs font-bold inline-flex items-center gap-1 border border-emerald-100">
                        <i class="fas fa-tag text-[10px]"></i>{{ $selectedCategory }}
                    </span>
            @endif
        </div>
    @endif

    {{-- Book Grid --}}
    @if ($books->count() > 0)
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            @foreach ($books as $book)
                <div class="book-card">
                    {{-- Cover Area --}}
                    <div class="relative bg-gradient-to-br from-gray-50 to-emerald-50/50 h-48 flex items-center justify-center p-4">
                        {{-- <i class="fas fa-book-open book-cover-icon text-5xl text-emerald-300"></i> --}}
                        @if (filter_var($book->cover_image, FILTER_VALIDATE_URL))
                            <img src="{{ $book->cover_image }}" class="h-48 w-96 object-cover" alt="{{ $book->title }}" loading="lazy">
                        @elseif ($book->cover_image)
                            <img src="{{ asset('storage/' . $book->cover_image) }}" alt="{{ $book->title }}" class="h-48 w-96 object-cover" loading="lazy">
                        @endif
                        
                        <div class="absolute top-3 left-3">
                            <span class="bg-white/90 backdrop-blur-sm text-gray-600 text-[10px] font-bold px-2 py-1 rounded-md shadow-sm border border-gray-100">
                                {{ $book->shelf_code }}
                            </span>
                        </div>
                        <div class="absolute top-3 right-3">
                            @if ($book->stock > 0)
                                <span class="bg-emerald-500 text-white text-[10px] font-bold px-2.5 py-1 rounded-md shadow-sm">
                                    Stok: {{ $book->stock }}
                                </span>
                            @else
                                <span class="bg-red-500 text-white text-[10px] font-bold px-2.5 py-1 rounded-md shadow-sm">
                                    Habis
                                </span>
                            @endif
                        </div>
                    </div>

                    {{-- Info Area --}}
                    <div class="p-4">
                        <h3 class="font-bold text-gray-800 text-sm leading-snug line-clamp-2 mb-3 min-h-[2.5rem] hover:text-emerald-600 transition-colors">
                            {{ $book->title }}
                        </h3>
                        
                        <div class="space-y-1.5 mb-4 text-xs text-gray-500">
                            <div class="flex items-center gap-2">
                                <i class="fas fa-pen-fancy w-3 text-center text-purple-400"></i>
                                <span class="truncate">{{ $book->author }}</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <i class="fas fa-layer-group w-3 text-center text-blue-400"></i>
                                <span class="truncate">{{ $book->category->name ?? '-' }}</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <i class="fas fa-calendar w-3 text-center text-orange-400"></i>
                                <span>{{ $book->year }}</span>
                            </div>
                        </div>

                        {{-- Tambahkan wire:navigate agar pindah halaman terasa super cepat --}}
                        <a href="{{ route('books.show', $book) }}" wire:navigate.prefetch="false"
                            class="btn-detail block w-full text-center text-white font-semibold py-2.5 rounded-xl text-sm">
                            Lihat Detail
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        {{-- Empty State --}}
        <div class="py-16">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-12 max-w-md mx-auto text-center">
                <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-5 border border-gray-100">
                    <i class="fas fa-magnifying-glass text-3xl text-gray-300"></i>
                </div>
                <h3 class="text-lg font-bold text-gray-800 mb-2">Buku Tidak Ditemukan</h3>
                <p class="text-gray-400 text-sm mb-6 leading-relaxed">Maaf, tidak ada buku yang cocok dengan pencarian Anda. Coba ganti kata kunci atau filter.</p>
                <button wire:click="resetFilters"
                    class="btn-detail inline-flex items-center gap-2 text-white px-6 py-2.5 rounded-xl font-semibold text-sm">
                    <i class="fas fa-arrow-rotate-left text-xs"></i> Reset Pencarian
                </button>
            </div>
        </div>
    @endif

    {{-- Pagination Khusus Livewire --}}
    <div class="mt-10">
        {{ $books->links('vendor.pagination.custom-pagination') }}
    </div>
</div>