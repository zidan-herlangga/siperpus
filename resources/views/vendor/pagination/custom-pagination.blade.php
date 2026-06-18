@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Pagination Navigation" class="flex flex-col items-center gap-3">
        <div class="flex items-center space-x-1">
            {{-- Tombol Previous --}}
            <a href="{{ $paginator->previousPageUrl() }}" @class([
                'inline-flex items-center px-3 py-2 rounded-l-md border transition',
                'bg-emerald-100 text-emerald-700 hover:bg-emerald-200' => !$paginator->onFirstPage(),
                'bg-emerald-50 text-emerald-400 cursor-not-allowed' => $paginator->onFirstPage(),
            ])
                aria-disabled="{{ $paginator->onFirstPage() }}">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293
                        3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0
                        010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                </svg>
            </a>

            {{-- Nomor halaman --}}
            @foreach ($elements as $element)
                @if (is_string($element))
                    <span class="px-3 py-2 bg-emerald-50 text-emerald-700 border">{{ $element }}</span>
                @endif

                @if (is_array($element))
                    @foreach ($element as $page => $url)
                        <a href="{{ $url }}" @class([
                            'px-3 py-2 border transition',
                            'bg-emerald-600 text-white font-semibold' =>
                                $page == $paginator->currentPage(),
                            'bg-emerald-100 text-emerald-700 hover:bg-emerald-200' =>
                                $page != $paginator->currentPage(),
                        ])
                            aria-current="{{ $page == $paginator->currentPage() ? 'page' : 'false' }}">
                            {{ $page }}
                        </a>
                    @endforeach
                @endif
            @endforeach

            {{-- Tombol Next --}}
            <a href="{{ $paginator->nextPageUrl() }}" @class([
                'inline-flex items-center px-3 py-2 rounded-r-md border transition',
                'bg-emerald-100 text-emerald-700 hover:bg-emerald-200' => $paginator->hasMorePages(),
                'bg-emerald-50 text-emerald-400 cursor-not-allowed' => !$paginator->hasMorePages(),
            ])
                aria-disabled="{{ !$paginator->hasMorePages() }}">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10
                        7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0
                        010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                </svg>
            </a>
        </div>

        {{-- Info jumlah data --}}
        <div class="text-sm text-emerald-700 mt-1">
            Menampilkan
            <span class="font-medium">{{ $paginator->firstItem() }}</span>
            hingga
            <span class="font-medium">{{ $paginator->lastItem() }}</span>
            dari
            <span class="font-medium">{{ $paginator->total() }}</span>
            hasil
        </div>
    </nav>
@endif
