<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Perpustakaan Sekolah')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <style>
        .gradient-bg {
            /* background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); */
            background: #006739;
        }
        /* .nav-link {
            position: relative;
            transition: color 0.3s ease;
        }
        .nav-link::after {
            content: '';
            position: absolute;
            bottom: -5px;
            left: 0;
            width: 0;
            height: 2px;
            background: #667eea;
            transition: width 0.3s ease;
        }
        .nav-link:hover::after {
            width: 100%;
        } */
        .book-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .book-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        }
        .search-box:focus-within {
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }
        .form-input:focus {
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }
        .fade-in {
            animation: fadeIn 0.6s ease-in;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body class="bg-gray-50 min-h-screen flex flex-col">
    <!-- Header -->
    <header class="gradient-bg text-white shadow-lg sticky top-0 z-50">
        <div class="container mx-auto px-4 py-4">
            <div class="flex justify-between items-center">
                <div class="flex items-center space-x-3">
                    <div>
                        <a href="/">
                            <img src="{{ asset('assets/image/logo-light-smkg2.png') }}" alt="Logo SMK Karya Guna 2 Bekasi">
                            {{-- <p class="text-blue-100 text-sm hidden sm:block">Tempatnya mencari ilmu dan pengetahuan</p> --}}
                        </a>
                    </div>
                </div>
                
                <!-- Desktop Navigation -->
                <nav class="hidden md:flex space-x-6">
                    <a href="{{ route('books.index') }}" class="nav-link text-white font-medium py-2 hover:text-yellow-500 transision duration-300">Katalog Buku</a>
                    <a href="{{ route('student.register.form') }}" class="nav-link text-white font-medium py-2 hover:text-yellow-500 transision duration-300">Daftar Siswa</a>
                    <a href="/admin-perpus" class="bg-yellow-500 text-white hover:text-yellow-500 px-4 py-2 rounded-lg font-semibold hover:bg-gray-100 transition duration-300">
                        <i class="fas fa-sign-in-alt mr-2"></i>Login Admin
                    </a>
                </nav>

                <!-- Mobile Menu Button -->
                <button id="mobileMenuButton" class="md:hidden text-2xl">
                    <i class="fas fa-bars"></i>
                </button>
            </div>

            <!-- Mobile Navigation -->
            <div id="mobileMenu" class="hidden md:hidden mt-4 space-y-3 border-t border-white-500 pt-4">
                <a href="{{ route('books.index') }}" class="block text-white font-medium py-2 border-l-4 border-white pl-4 {{ request()->routeIs('books.index') ? 'bg-green-700' : '' }}">Katalog Buku</a>
                <a href="{{ route('student.register.form') }}" class="block text-white font-medium py-2 border-l-4 border-white pl-4 {{ request()->routeIs('student.register.*') ? 'bg-green-700' : '' }}">Daftar Siswa</a>
                <a href="/admin" class="block bg-yellow-500 text-white hover:bg-white hover:text-yellow-500 px-4 py-2 rounded-lg font-semibold text-center mt-2">
                    <i class="fas fa-sign-in-alt mr-2"></i>Login Admin
                </a>
            </div>
        </div>
    </header>

    <main class="flex-grow">
        @yield('content')
    </main>

    <!-- Footer -->
    <footer class="gradient-bg text-white py-12">
        <div class="container mx-auto px-4">
            <div class="grid md:grid-cols-4 gap-8">
                <div>
                    <img src="{{ asset('assets/image/logo-light-smkg2.png') }}" alt="Logo SMK Karya Guna 2 Bekasi">
                    <p class="text-white">Membangun generasi cerdas melalui literasi dan pengetahuan.</p>
                </div>
                <div>
                    <h4 class="text-lg font-semibold mb-4">Tautan Cepat</h4>
                    <ul class="space-y-2">
                        <li><a href="{{ route('books.index') }}" class="text-white transition">Katalog Buku</a></li>
                        <li><a href="{{ route('student.register.form') }}" class="text-white transition">Pendaftaran Siswa</a></li>
                    </ul>
                </div>
                <div>
                    <h4 class="text-lg font-semibold mb-4">Kontak</h4>
                    <div class="space-y-2 text-white">
                        <p><i class="fas fa-map-marker-alt mr-2"></i>Jl. Karang Satria No.503, RT.010/RW.016, Duren Jaya, Kec. Bekasi Tim., Kota Bks, Jawa Barat 17111</p>
                        <p><i class="fas fa-phone mr-2"></i>(021) 8800523</p>
                        <p><i class="fas fa-envelope mr-2"></i>info@smkkaryaguna2bekasi.sch.id</p>
                    </div>
                </div>
                <div>
                    <h4 class="text-lg font-semibold mb-4">Jam Operasional</h4>
                    <div class="space-y-2 text-white">
                        <p>Senin - Jumat: 07:00 - 16:00</p>
                        <p>Sabtu: 08:00 - 14:00</p>
                        <p>Minggu: Tutup</p>
                    </div>
                </div>
            </div>
            <div class="flex space-between border-t border-white-700 mt-8 pt-8 text-center text-white">
                <p>&copy; {{ date('Y') }} Perpustakaan Sekolah. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script>
        // Mobile menu toggle
        document.getElementById('mobileMenuButton').addEventListener('click', function() {
            const menu = document.getElementById('mobileMenu');
            menu.classList.toggle('hidden');
            const icon = this.querySelector('i');
            if (menu.classList.contains('hidden')) {
                icon.classList.remove('fa-times');
                icon.classList.add('fa-bars');
            } else {
                icon.classList.remove('fa-bars');
                icon.classList.add('fa-times');
            }
        });

        // Smooth scroll for anchor links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });

        // Add fade-in animation to main content
        document.addEventListener('DOMContentLoaded', function() {
            const mainContent = document.querySelector('main');
            if (mainContent) {
                mainContent.classList.add('fade-in');
            }
        });
    </script>
    
    @stack('scripts')
</body>
</html>