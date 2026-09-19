@extends('layouts.app')

@section('title', config('app.name'))

@section('styles')
<style>
  #features-section, #stats-section, #steps-section, #testimonials-section { content-visibility: auto; contain-intrinsic-size: 450px; }
</style>
<link rel="stylesheet" href="{{ asset('assets/css/style.css') }}" media="print" onload="this.media='all'" fetchpriority="low">
    <noscript><link rel="stylesheet" href="{{ asset('assets/css/style.css') }}"></noscript>
    <noscript><style>.reveal{opacity:1;transform:none}</style></noscript>
@stop

@section('content')

    {{-- ==================== HERO ==================== --}}
    <section class="lp-hero">
        <div class="lp-wrap lp-hero__inner">
            <div>
                <p class="lp-kicker reveal visible">Perpustakaan Digital &middot; SMK Karya Guna 2 Bekasi</p>
                <h1 class="lp-hero__title reveal visible">
                    Koleksi sekolah, <em>tak perlu antre.</em>
                </h1>
                <p class="lp-hero__sub reveal visible">Program SMK Karya Guna 2 untuk mencari, meminjam, dan membaca ulang koleksi perpustakaan dari mana pun kamu berada.</p>
                <div class="lp-hero__actions reveal visible">
                    <a href="{{ route('books.index') }}" wire:navigate.prefetch="false" class="lp-btn lp-btn--primary">
                        <i class="fas fa-arrow-right"></i> Jelajahi Katalog
                    </a>
                    @unless (Auth::guard('student')->check())
                    <a href="{{ route('student.register.form') }}" wire:navigate.prefetch="false" class="lp-btn lp-btn--ghost">
                        Daftar sebagai Siswa
                    </a>
                    @endunless
                </div>
            </div>

            @if ($featuredBooks && $featuredBooks->count())
                @php
                    $stackBooks = $featuredBooks->take(3);
                @endphp
                <div class="lp-stack" aria-label="Sampul buku unggulan">
                    <div class="lp-stack__board"></div>
                    @foreach ($stackBooks as $book)
                        <div class="lp-stack__item lp-stack__item--{{ $loop->iteration }}">
                            <img src="{{ filter_var($book->cover_image, FILTER_VALIDATE_URL) ? $book->cover_image : asset('storage/' . $book->cover_image) }}"
                                alt="Sampul {{ $book->title }}" loading="{{ $loop->first ? 'eager' : 'lazy' }}">
                        </div>
                    @endforeach
                    <div class="lp-stack__meta">{{ $bookCount }} judul di katalog</div>
                </div>

                <div class="lp-stack--mini" aria-hidden="true">
                    @foreach ($stackBooks as $book)
                        <div class="cvr">
                            <img src="{{ filter_var($book->cover_image, FILTER_VALIDATE_URL) ? $book->cover_image : asset('storage/' . $book->cover_image) }}"
                                alt="" loading="lazy">
                        </div>
                    @endforeach
                    <span class="reveal visible">{{ $bookCount }} judul</span>
                </div>
            @endif
        </div>
    </section>

    {{-- ==================== BENTO / FITUR ==================== --}}
    <section id="features-section" class="lp-section lp-section--white lp-section--rule-bottom">
        <div class="lp-wrap">
            <div class="lp-bento-head reveal">
                <h2 class="lp-h2">Perpustakaan yang bekerja penuh di balik layar.</h2>
                <p class="lp-sub">Fitur dibuat untuk satu alur yang jujur: temukan buku, pinjam, dan baca kembali. Tidak lebih.</p>
            </div>

            <div class="bento-grid">
                <div class="bento-cell bento-cell--big reveal">
                    <div>
                        <span class="bento-cell__icon"><i class="fas fa-book-open"></i></span>
                        <div class="bento-collect-num counter" data-target="{{ $bookCount }}">0</div>
                        <h3 class="bento-cell__title">judul siap dipinjam</h3>
                        <p class="bento-cell__desc">Fiksi, sains, referensi, dan bacaan wajib tersusun dalam satu katalog.</p>
                    </div>
                    @if ($featuredBooks && $featuredBooks->count())
                        <div class="bento-thumbs">
                            @foreach ($featuredBooks->slice(0, 2) as $book)
                                <div class="cvr" title="{{ $book->title }}">
                                    <img src="{{ filter_var($book->cover_image, FILTER_VALIDATE_URL) ? $book->cover_image : asset('storage/' . $book->cover_image) }}"
                                        alt="Sampul {{ $book->title }}" loading="lazy">
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>

                <div class="bento-cell bento-cell--dark reveal reveal-delay-1">
                    <div>
                        <span class="bento-cell__icon"><i class="fas fa-arrow-right-arrow-left"></i></span>
                        <h3 class="bento-cell__title">Pesan dari mana saja</h3>
                        <p class="bento-cell__desc">Buku disiapkan di rak, kamu tinggal mengambil.</p>
                    </div>
                </div>

                <div class="bento-cell bento-cell--plain reveal reveal-delay-2">
                    <div>
                        <span class="bento-cell__icon"><i class="fas fa-bell-concierge"></i></span>
                        <h3 class="bento-cell__title">Kembali tepat waktu</h3>
                        <p class="bento-cell__desc">Notifikasi otomatis mendekati jadwal pengembalian.</p>
                    </div>
                </div>

                <div class="bento-cell bento-cell--tint reveal reveal-delay-2">
                    <div>
                        <span class="bento-cell__icon"><i class="fas fa-clock-rotate-left"></i></span>
                        <h3 class="bento-cell__title">Baca ulang kapan pun</h3>
                        <p class="bento-cell__desc">Semua yang pernah kamu pinjam tersimpan di akunmu.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- ==================== CARA MEMINJAM ==================== --}}
    <section id="steps-section" class="lp-section lp-section--bone">
        <div class="lp-wrap">
            <div class="lp-bento-head reveal">
                <h2 class="lp-h2">Cara meminjam</h2>
                <p class="lp-sub">Tiga langkah dari rak ke tanganmu.</p>
            </div>

            <div class="step-list">
                <article class="step reveal">
                    <span class="step__no">01</span>
                    <h3 class="step__title">Daftar dan masuk</h3>
                    <p class="step__desc">Buat akun siswa, lalu masuk ke sistem.</p>
                </article>
                <article class="step reveal reveal-delay-1">
                    <span class="step__no">02</span>
                    <h3 class="step__title">Pilih buku</h3>
                    <p class="step__desc">Temukan judul lewat pencarian atau telusuri kategori.</p>
                </article>
                <article class="step reveal reveal-delay-2">
                    <span class="step__no">03</span>
                    <h3 class="step__title">Konfirmasi pinjam</h3>
                    <p class="step__desc">Setujui ketentuan, buku siap diambil di perpustakaan.</p>
                </article>
            </div>

            <p class="reveal reveal-delay-2" style="margin-top:2.25rem;">
                <button onclick="openBorrowGuideModal()"
                    class="lp-btn lp-btn--ghost">
                    <i class="fas fa-circle-info"></i> Lihat panduan lengkap
                </button>
            </p>
        </div>
    </section>

    {{-- ==================== LAPORAN PUSTAKA ==================== --}}
    <section id="stats-section" class="lp-band lp-section">
        <div class="lp-wrap">
            <div class="reveal">
                <h2 class="lp-h2" style="color:#f4f6f1;">Laporan pustaka, hari ini</h2>
            </div>
            <div class="band-grid" style="margin-top: clamp(2.25rem, 5vw, 3.5rem);">
                <div class="band-item reveal">
                    <div class="band-num band-live">
                        <span id="live-visitor-count" class="realtime-counter">0</span>
                        <span class="pulse" aria-hidden="true"></span>
                    </div>
                    <span class="band-label">Pengunjung hari ini</span>
                </div>
                <div class="band-item reveal reveal-delay-1">
                    <div class="band-num"><span class="counter" data-target="{{ $bookCount }}">0</span></div>
                    <span class="band-label">Judul tersedia</span>
                </div>
                <div class="band-item reveal reveal-delay-2">
                    <div class="band-num"><span class="counter" data-target="{{ $studentCount }}">0</span></div>
                    <span class="band-label">Siswa terdaftar</span>
                </div>
                <div class="band-item reveal reveal-delay-2">
                    <div class="band-num"><span class="counter" data-target="{{ $borrowCount }}">0</span></div>
                    <span class="band-label">Peminjaman bulan ini</span>
                </div>
            </div>
        </div>
    </section>

    {{-- ==================== TESTIMONI ==================== --}}
    <section id="testimonials-section" class="lp-section lp-section--white lp-section--rule-top">
        <div class="lp-wrap">
            <div class="lp-bento-head reveal">
                <h2 class="lp-h2">Kata anggota</h2>
                <p class="lp-sub">Catatan singkat dari siswa yang sudah meminjam lewat program ini.</p>
            </div>

            @forelse ($approvedTestimonials as $i => $t)
                <div class="quote-wall">
                    @if ($i === 0)
                        <article class="quote quote--featured reveal">
                            <span class="quote__mark">&ldquo;</span>
                            <p class="quote__text">{{ $t->content }}</p>
                            <div class="flex flex-wrap gap-1 mb-4">
                                @for ($j = 1; $j <= 5; $j++)
                                    <i class="fas fa-star {{ $j <= $t->rating ? 'text-amber-400' : 'text-gray-200' }}"></i>
                                @endfor
                            </div>
                            <div class="quote__feet">
                                @if ($t->student->getRawOriginal('avatar'))
                                    <img src="{{ asset('storage/' . $t->student->avatar) }}" alt=""
                                        class="quote__avatar" style="border-radius:50%; object-fit:cover;"
                                        onerror="this.onerror=null; this.src='{{ asset('images/default-avatar.png') }}';">
                                @else
                                    <span class="quote__avatar">{{ strtoupper(substr($t->student->name, 0, 1)) }}</span>
                                @endif
                                <div>
                                    <div class="quote__name">{{ $t->student->name }}</div>
                                    <div class="quote__role">Siswa</div>
                                </div>
                            </div>
                        </article>
                    @else
                        <article class="quote quote--side reveal reveal-delay-{{ $i }}">
                            <p class="quote__text">&ldquo;{{ $t->content }}&rdquo;</p>
                            <div class="quote__feet">
                                @if ($t->student->getRawOriginal('avatar'))
                                    <img src="{{ asset('storage/' . $t->student->avatar) }}" alt=""
                                        class="quote__avatar" style="border-radius:50%; object-fit:cover;"
                                        onerror="this.onerror=null; this.src='{{ asset('images/default-avatar.png') }}';">
                                @else
                                    <span class="quote__avatar">{{ strtoupper(substr($t->student->name, 0, 1)) }}</span>
                                @endif
                                <div>
                                    <div class="quote__name">{{ $t->student->name }}</div>
                                    <div class="quote__role">Siswa</div>
                                </div>
                            </div>
                        </article>
                    @endif
                </div>
            @empty
                <div class="quote-empty reveal">
                    <i class="fas fa-quote-left block mb-3 text-2xl text-gray-200"></i>
                    <p class="font-medium">Belum ada testimoni.</p>
                </div>
            @endforelse
        </div>
    </section>

@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {

    // ==========================================
    // 1. SCROLL REVEAL ANIMATION
    // ==========================================
    function initScrollReveal() {
        const reveals = document.querySelectorAll('.reveal:not(.visible)');
        if (!('IntersectionObserver' in window)) {
            reveals.forEach(el => el.classList.add('visible'));
            return;
        }
        const revealObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('visible');
                    revealObserver.unobserve(entry.target);
                }
            });
        }, { threshold: 0.15, rootMargin: '0px 0px -40px 0px' });

        reveals.forEach(el => revealObserver.observe(el));
    }

    // ==========================================
    // 2. COUNTER ANIMASI (UNTUK DATA STATIS)
    // ==========================================
    function initStaticCounterAnimation() {
        const counters = document.querySelectorAll('.counter:not(.realtime-counter):not(.counted)');

        if (!counters.length) return;

        const counterObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const el = entry.target;
                    el.classList.add('counted');
                    const target = parseInt(el.dataset.target) || 0;

                    if (target === 0) {
                        el.textContent = '0';
                        counterObserver.unobserve(el);
                        return;
                    }

                    const duration = 1400;
                    const startTime = performance.now();

                    function easeOutExpo(t) {
                        return t === 1 ? 1 : 1 - Math.pow(2, -10 * t);
                    }

                    function updateCounter(currentTime) {
                        const elapsed = currentTime - startTime;
                        const progress = Math.min(elapsed / duration, 1);
                        const current = Math.round(easeOutExpo(progress) * target);
                        el.textContent = current.toLocaleString('id-ID');
                        if (progress < 1) {
                            requestAnimationFrame(updateCounter);
                        } else {
                            el.textContent = target.toLocaleString('id-ID');
                        }
                    }

                    requestAnimationFrame(updateCounter);
                    counterObserver.unobserve(el);
                }
            });
        }, { threshold: 0.4 });

        counters.forEach(el => counterObserver.observe(el));
    }

    // ==========================================
    // 3. REALTIME VISITOR COUNTER (POLLING)
    // ==========================================
    function initRealtimeVisitor() {
        const liveCounter = document.getElementById('live-visitor-count');
        if (!liveCounter) return;

        const fetchVisitorCount = () => {
            fetch('{{ route("visitors.today") }}')
                .then(response => {
                    if (!response.ok) throw new Error('Network error');
                    return response.json();
                })
                .then(data => {
                    if (data.count !== undefined) {
                        const currentVal = parseInt(liveCounter.innerText.replace(/\D/g, '')) || 0;
                        if (currentVal !== data.count) {
                            animateRealtimeValue(liveCounter, currentVal, data.count);
                        }
                    }
                })
                .catch(err => console.log('Gagal fetch visitor:', err));
        };

        const animateRealtimeValue = (el, start, end) => {
            const duration = 600;
            const range = end - start;
            let startTime = null;

            const step = (timestamp) => {
                if (!startTime) startTime = timestamp;
                const progress = Math.min((timestamp - startTime) / duration, 1);
                const eased = 1 - Math.pow(1 - progress, 3);
                el.innerText = Math.floor(eased * range + start).toLocaleString('id-ID');
                if (progress < 1) {
                    window.requestAnimationFrame(step);
                } else {
                    el.innerText = end.toLocaleString('id-ID');
                }
            };
            window.requestAnimationFrame(step);
        };

        fetchVisitorCount();
        setInterval(fetchVisitorCount, 15000);
    }

    // ==========================================
    // INISIALISASI AWAL
    // ==========================================
    initScrollReveal();
    initStaticCounterAnimation();
    initRealtimeVisitor();

    // ==========================================
    // INISIALISASI ULANG SAAT LIVEWIRE UPDATE
    // ==========================================
    document.addEventListener('livewire:initialized', () => {
        initScrollReveal();
    });

    document.addEventListener('livewire:update', () => {
        initScrollReveal();
    });

});
</script>
@stop