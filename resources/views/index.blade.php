@extends('layouts.app')

@section('title', config('app.name'))

@section('styles')
<style>
  #features-section, #stats-section, #cta-section, #testimonials-section { content-visibility: auto; contain-intrinsic-size: 450px; }
</style>
<link rel="stylesheet" href="{{ asset('assets/css/style.css') }}" media="print" onload="this.media='all'" fetchpriority="low">
    <noscript><link rel="stylesheet" href="{{ asset('assets/css/style.css') }}"></noscript>
@stop

@section('content')

    {{-- ==================== HERO ==================== --}}
    <section class="hero-section flex items-center overflow-hidden">
        <div class="grid-overlay"></div>
        
        <div class="hidden md:block absolute bottom-0 right-0 w-[340px] lg:w-[400px] z-10 translate-x-10 translate-y-5 opacity-80 hero-mascot-float">
            <svg viewBox="0 0 300 450" fill="none" xmlns="http://www.w3.org/2000/svg" class="w-full drop-shadow-2xl">
                <rect x="100" y="180" width="100" height="140" rx="5" fill="#facc15" transform="rotate(-5 150 250)"/>
                <rect x="105" y="185" width="90" height="130" rx="3" fill="#fffbeb" transform="rotate(-5 150 250)"/>
                <line x1="115" y1="205" x2="185" y2="205" stroke="#d1d5db" stroke-width="2" transform="rotate(-5 150 250)"/>
                <line x1="115" y1="220" x2="185" y2="220" stroke="#d1d5db" stroke-width="2" transform="rotate(-5 150 250)"/>
                <line x1="115" y1="235" x2="175" y2="235" stroke="#d1d5db" stroke-width="2" transform="rotate(-5 150 250)"/>
            </svg>
        </div>

        <div class="hidden lg:block absolute top-16 left-10 z-10 opacity-30 hero-icon-spin-slow">
            <div class="w-24 h-24 bg-white/10 backdrop-blur-sm rounded-2xl flex items-center justify-center border border-white/10 shadow-xl">
                <i class="fas fa-book-open text-4xl text-white"></i>
            </div>
        </div>

        <div class="hidden lg:block absolute top-24 right-[340px] z-10 opacity-20 hero-mascot-float-reverse">
            <div class="w-20 h-20 bg-white/10 backdrop-blur-sm rounded-2xl flex items-center justify-center border border-white/10 shadow-xl">
                <i class="fas fa-globe text-3xl text-white"></i>
            </div>
        </div>

        <div class="hidden md:block absolute bottom-10 left-10 z-10 opacity-40 -rotate-12">
            <div class="w-32 h-32 border-2 border-white/20 rounded-3xl backdrop-blur-sm"></div>
        </div>
        <div class="hidden md:block absolute bottom-28 left-32 z-10 opacity-20 rotate-12">
            <div class="w-20 h-20 bg-white/10 rounded-2xl backdrop-blur-sm"></div>
        </div>

        <div class="hidden lg:flex absolute top-32 right-20 z-10 gap-3 opacity-30">
            <div class="w-2.5 h-2.5 bg-yellow-300 rounded-full"></div>
            <div class="w-2.5 h-2.5 bg-yellow-300 rounded-full"></div>
            <div class="w-2.5 h-2.5 bg-yellow-300 rounded-full"></div>
        </div>

        <div class="relative z-20 container mx-auto px-6 text-center text-white py-20 md:py-28 max-w-4xl">
            <div class="hero-badge inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-white/10 backdrop-blur-sm border border-white/10 text-sm text-emerald-100 mb-6">
                <span class="w-2 h-2 bg-emerald-400 rounded-full animate-pulse"></span>
                Perpustakaan Digital Aktif
            </div>

            <h1 class="hero-title text-4xl md:text-5xl lg:text-6xl font-extrabold mb-6 leading-[1.1] tracking-tight">
                Selamat Datang di<br>
                <span class="bg-gradient-to-r from-yellow-300 via-amber-300 to-yellow-400 bg-clip-text text-transparent">
                    {{ config('app.name', 'Perpustakaan') }}
                </span>
            </h1>

            <p class="hero-sub text-lg md:text-xl text-emerald-100/90 max-w-2xl mx-auto mb-10 leading-relaxed">
                Temukan ribuan koleksi buku dan jelajahi dunia literasi secara digital — cepat, mudah, dan menyenangkan.
            </p>

            <div class="hero-actions flex flex-col sm:flex-row justify-center gap-4">
                <a href="{{ route('books.index') }}" wire:navigate.prefetch="false" class="btn-primary">
                    <i class="fas fa-search"></i> Jelajahi Koleksi
                </a>
                @unless (Auth::guard('student')->check())
                <a href="{{ route('student.register.form') }}" wire:navigate.prefetch="false" class="btn-outline">
                    <i class="fas fa-user-plus"></i> Daftar Sekarang
                </a>
                @endunless
            </div>
        </div>
    </section>

    {{-- ==================== FEATURES ==================== --}}
    <section id="features-section" class="py-20 md:py-24 bg-gradient-to-b from-gray-50/80 to-white">
        <div class="container mx-auto px-6">
            <div class="text-center mb-14 reveal">
                <span class="section-badge"><i class="fas fa-sparkles text-[.65rem]"></i> Fitur Unggulan</span>
                <h2 class="section-title mt-2">Mengapa Memilih Kami?</h2>
            </div>

            <div class="grid md:grid-cols-3 gap-6 lg:gap-8">
                @php
                    $features = [
                        ['icon' => 'fa-book-open', 'title' => 'Koleksi Lengkap', 'desc' => 'Ribuan buku dari berbagai kategori dan penerbit terkemuka tersedia untuk Anda.'],
                        ['icon' => 'fa-laptop-code', 'title' => 'Akses Digital', 'desc' => 'Sistem online yang bisa diakses kapan pun dan di mana pun melalui perangkat apa saja.'],
                        ['icon' => 'fa-bell-concierge', 'title' => 'Notifikasi Cerdas', 'desc' => 'Pengingat pengembalian otomatis dan informasi terbaru langsung ke notifikasi Anda.'],
                    ];
                @endphp
                @foreach ($features as $i => $f)
                    <div class="feature-card reveal reveal-delay-{{ $i + 1 }}">
                        <div class="feature-icon">
                            <i class="fas {{ $f['icon'] }}"></i>
                        </div>
                        <h3 class="text-lg font-bold text-gray-800 mb-2">{{ $f['title'] }}</h3>
                        <p class="text-gray-500 text-sm leading-relaxed">{{ $f['desc'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ==================== STATISTICS ==================== --}}
    <section id="stats-section" class="py-20 md:py-24 relative overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-br from-emerald-50/60 via-green-50/40 to-teal-50/60"></div>
        <div class="absolute top-0 left-0 w-72 h-72 bg-emerald-200/20 rounded-full -translate-x-1/2 -translate-y-1/2 blur-3xl"></div>
        <div class="absolute bottom-0 right-0 w-96 h-96 bg-teal-200/20 rounded-full translate-x-1/3 translate-y-1/3 blur-3xl"></div>

        <div class="relative container mx-auto px-6">
            <div class="text-center mb-14 reveal">
                <span class="section-badge"><i class="fas fa-chart-simple text-[.65rem]"></i> Data Nyata</span>
                <h2 class="section-title mt-2">Statistik Perpustakaan</h2>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-5 gap-4 lg:gap-6">
                @php
                    $stats = [
                        ['icon' => 'fa-eye', 'value' => 0, 'label' => 'Pengunjung Hari Ini', 'color' => '#047857', 'is_realtime' => true], 
                        ['icon' => 'fa-book', 'value' => $bookCount ?? 0, 'label' => 'Judul Buku', 'color' => '#059669', 'is_realtime' => false],
                        ['icon' => 'fa-users', 'value' => $studentCount ?? 0, 'label' => 'Siswa Terdaftar', 'color' => '#0d9488', 'is_realtime' => false],
                        ['icon' => 'fa-arrow-right-arrow-left', 'value' => $borrowCount ?? 0, 'label' => 'Peminjaman Bulan Ini', 'color' => '#10b981', 'is_realtime' => false],
                        ['icon' => 'fa-tags', 'value' => $categoryCount ?? 0, 'label' => 'Kategori Buku', 'color' => '#65a30d', 'is_realtime' => false],
                    ];
                @endphp
                @foreach ($stats as $i => $s)
                    <div class="stat-card reveal reveal-delay-{{ $i + 1 }}">
                        <div class="stat-icon-ring relative">
                            <i class="fas {{ $s['icon'] }} text-lg" style="color:{{ $s['color'] }}"></i>
                            @if ($s['is_realtime'])
                                <span class="absolute -top-1 -right-1 flex h-3 w-3">
                                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                                    <span class="relative inline-flex rounded-full h-3 w-3 bg-emerald-500"></span>
                                </span>
                            @endif
                        </div>
                        <div class="stat-number counter {{ $s['is_realtime'] ? 'realtime-counter' : '' }}" 
                            id="{{ $s['is_realtime'] ? 'live-visitor-count' : '' }}" 
                            data-target="{{ $s['value'] }}">
                            0
                        </div>
                        <p class="text-gray-500 text-sm mt-1 font-medium">{{ $s['label'] }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ==================== CTA ==================== --}}
    <section id="cta-section" class="py-20 md:py-24 bg-white">
        <div class="container mx-auto px-6">
            <div class="text-center mb-14 reveal">
                <span class="section-badge"><i class="fas fa-rocket text-[.65rem]"></i> Mulai Sekarang</span>
                <h2 class="section-title mt-2">Mulai Perjalanan Literasi Anda</h2>
            </div>

            <div class="grid md:grid-cols-2 gap-6 max-w-3xl mx-auto">
                <a href="{{ route('books.index') }}" wire:navigate.prefetch="false" class="cta-card reveal reveal-delay-1"
                   style="background: linear-gradient(135deg, #059669, #047857, #0f766e);">
                    <div class="cta-icon-wrap">
                        <i class="fas fa-magnifying-glass text-2xl text-white"></i>
                    </div>
                    <h3 class="text-xl font-bold text-white mb-2">Lihat Katalog Buku</h3>
                    <p class="text-emerald-100/80 text-sm leading-relaxed">Jelajahi dan temukan buku favorit Anda dari ribuan koleksi yang tersedia.</p>
                    <span class="cta-arrow text-white">Jelajahi <i class="fas fa-arrow-right text-xs"></i></span>
                </a>

                @unless (Auth::guard('student')->check())
                <a href="{{ route('student.register.form') }}" wire:navigate.prefetch="false" class="cta-card reveal reveal-delay-2"
                   style="background: linear-gradient(135deg, #0d9488, #0f766e, #115e59);">
                    <div class="cta-icon-wrap">
                        <i class="fas fa-user-graduate text-2xl text-white"></i>
                    </div>
                    <h3 class="text-xl font-bold text-white mb-2">Daftar sebagai Siswa</h3>
                    <p class="text-teal-100/80 text-sm leading-relaxed">Buat akun untuk mulai meminjam buku dan mengakses semua fitur perpustakaan.</p>
                    <span class="cta-arrow text-white">Daftar <i class="fas fa-arrow-right text-xs"></i></span>
                </a>
                @endunless
            </div>
        </div>
    </section>

    {{-- ==================== TESTIMONIALS ==================== --}}
    <section id="testimonials-section" class="py-20 md:py-24 bg-gradient-to-b from-gray-50/80 to-white">
        <div class="container mx-auto px-6">
            <div class="text-center mb-14 reveal">
                <span class="section-badge"><i class="fas fa-quote-left text-[.65rem]"></i> Testimoni</span>
                <h2 class="section-title mt-2">Apa Kata Mereka?</h2>
            </div>
            <div class="grid md:grid-cols-3 gap-6 lg:gap-8">
                @forelse ($approvedTestimonials as $i => $t)
                    <div class="testimonial-card reveal reveal-delay-{{ $i + 1 }}">
                        <div class="flex items-center gap-3 mb-4">
                            @if($t->student->avatar)
<img src="{{ asset('storage/' . $t->student->avatar) }}" alt="{{ $t->student->name }}" 
    class="w-12 h-12 rounded-xl object-cover border-2 border-gray-100" loading="lazy">
                            @else
                                <div class="w-12 h-12 rounded-xl bg-emerald-100 flex items-center justify-center">
                                    <span class="text-emerald-600 font-bold">{{ strtoupper(substr($t->student->name, 0, 1)) }}</span>
                                </div>
                            @endif
                            <div>
                                <h4 class="font-bold text-gray-800 text-sm">{{ $t->student->name }}</h4>
                                <p class="text-xs text-gray-400">Siswa Perpustakaan</p>
                            </div>
                        </div>
                        <div class="flex gap-0.5 mb-3">
                            @for ($j = 1; $j <= 5; $j++)
                                <i class="star fas fa-star {{ $j <= $t->rating ? 'text-amber-400' : 'text-gray-200' }}"></i>
                            @endfor
                        </div>
                        <p class="text-gray-500 text-sm italic leading-relaxed">"{{ $t->content }}"</p>
                    </div>
                @empty
                    <div class="col-span-3 text-center py-10 text-gray-400">
                        <i class="fas fa-comments text-4xl mb-3 text-gray-200"></i>
                        <p class="font-medium">Belum ada testimoni.</p>
                    </div>
                @endforelse
            </div>
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
        // Hanya ambil elemen counter yang BUKAN realtime
        const counters = document.querySelectorAll('.counter:not(.realtime-counter):not(.counted)');
        
        const counterObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const el = entry.target;
                    el.classList.add('counted'); // Tandai sudah pernah dihitung agar tidak diulang
                    const target = parseInt(el.dataset.target) || 0;
                    
                    if (target === 0) { 
                        el.textContent = '0'; 
                        counterObserver.unobserve(el);
                        return; 
                    }

                    const duration = 1800; // Durasi animasi dalam ms
                    const startTime = performance.now();

                    // Fungsi matematika agar animasi numbering terasa halus (ease-out)
                    function easeOutExpo(t) {
                        return t === 1 ? 1 : 1 - Math.pow(2, -10 * t);
                    }

                    function updateCounter(currentTime) {
                        const elapsed = currentTime - startTime;
                        const progress = Math.min(elapsed / duration, 1);
                        const easedProgress = easeOutExpo(progress);
                        const current = Math.round(easedProgress * target);
                        
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
        }, { threshold: 0.5 });
        
        counters.forEach(el => counterObserver.observe(el));
    }

    // ==========================================
    // 3. REALTIME VISITOR COUNTER (POLLING)
    // ==========================================
    function initRealtimeVisitor() {
        const liveCounter = document.getElementById('live-visitor-count');
        if (!liveCounter) return;

        // Fungsi untuk mengambil data dari server
        const fetchVisitorCount = () => {
            fetch('{{ route("visitors.today") }}')
                .then(response => {
                    if (!response.ok) throw new Error('Network error');
                    return response.json();
                })
                .then(data => {
                    if (data.count !== undefined) {
                        const currentVal = parseInt(liveCounter.innerText.replace(/\D/g, '')) || 0;
                        
                        // Hanya animasikan jika angka berubah
                        if (currentVal !== data.count) {
                            animateRealtimeValue(liveCounter, currentVal, data.count);
                        }
                    }
                })
                .catch(err => console.log('Gagal fetch visitor:', err));
        };

        // Fungsi animasi transisi angka (lembut untuk realtime)
        const animateRealtimeValue = (el, start, end) => {
            const duration = 600; 
            const range = end - start;
            let startTime = null;
            
            const step = (timestamp) => {
                if (!startTime) startTime = timestamp;
                const progress = Math.min((timestamp - startTime) / duration, 1);
                // Animasi ease-out sederhana
                const easedProgress = 1 - Math.pow(1 - progress, 3);
                el.innerText = Math.floor(easedProgress * range + start).toLocaleString('id-ID');
                
                if (progress < 1) {
                    window.requestAnimationFrame(step);
                } else {
                    el.innerText = end.toLocaleString('id-ID');
                }
            };
            window.requestAnimationFrame(step);
        };

        // Fetch pertama kali saat halaman dimuat
        fetchVisitorCount();

        // Polling setiap 15 detik untuk cek pengunjung baru
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
    // (Agar animasi tidak rusak saat navigasi SPA)
    // ==========================================
    document.addEventListener('livewire:initialized', () => {
        initScrollReveal();
    });

    document.addEventListener('livewire:update', () => {
        initScrollReveal();
        // Counter tidak perlu di-init ulang saat Livewire update agar angka statis tidak loncat-loncat
        // Visitor juga tidak perlu di-init ulang karena sudah dihandle setInterval
    });

});
</script>
@stop