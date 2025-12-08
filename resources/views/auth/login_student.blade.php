@extends('layouts.app')

@section('title', 'Login Siswa - ' . config('app.name'))

@section('styles')
<link rel="stylesheet" href="{{ asset('assets/css/login-student.css') }}">
@stop

@section('content')
    <div class="min-h-screen bg-gradient-to-br from-green-50 to-emerald-50 flex items-center justify-center px-4 py-8">
        <div class="w-full max-w-md">
            <!-- Background decoration -->
            <div class="absolute top-0 left-0 w-full h-full overflow-hidden pointer-events-none">
                <div class="absolute -top-40 -right-40 w-80 h-80 bg-green-200 rounded-full opacity-20"></div>
                <div class="absolute -bottom-40 -left-40 w-80 h-80 bg-emerald-200 rounded-full opacity-20"></div>
            </div>

            {{-- Card utama dengan animasi --}}
            <div class="bg-white border border-gray-200 rounded-xl shadow-xl overflow-hidden animate-fade-in">

                {{-- Header Card dengan Gradien --}}
                <div class="bg-gradient-to-r from-green-600 to-emerald-600 text-white text-center py-8 px-6 relative overflow-hidden">
                    <!-- Animated background elements -->
                    <div class="absolute top-0 left-0 w-full h-full">
                        <div class="absolute top-2 left-2 w-16 h-16 bg-white/10 rounded-full animate-pulse"></div>
                        <div class="absolute bottom-2 right-2 w-24 h-24 bg-white/10 rounded-full animate-pulse" style="animation-delay: 1s;"></div>
                    </div>
                    
                    <div class="relative z-10">
                        <div class="w-20 h-20 bg-white/20 backdrop-blur rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-user-graduate text-3xl"></i>
                        </div>
                        <h1 class="text-2xl font-bold mb-1">Login Siswa</h1>
                        <p class="text-green-100 text-sm">Masuk untuk mengakses sistem peminjaman buku</p>
                    </div>
                </div>

                {{-- Form --}}
                <div class="p-6">
                    <form id="loginForm" action="{{ route('student.login.auth') }}" method="POST" class="space-y-5">
                        @csrf

                        {{-- Pesan Status --}}
                        @if (session('status'))
                            <div class="bg-green-50 border border-green-200 text-green-700 text-sm p-3 rounded-lg flex items-start animate-slide-down">
                                <i class="fas fa-check-circle mr-2 mt-0.5"></i>
                                <span>{{ session('status') }}</span>
                            </div>
                        @endif

                        {{-- Pesan Error --}}
                        @if ($errors->any())
                            <div class="bg-red-50 border border-red-200 text-red-600 text-sm p-3 rounded-lg flex items-start animate-slide-down">
                                <i class="fas fa-exclamation-circle mr-2 mt-0.5"></i>
                                <div>
                                    <p class="font-medium mb-1">Terjadi kesalahan:</p>
                                    <ul class="list-disc list-inside space-y-1">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        @endif

                        {{-- Email/NIS --}}
                        <div>
                            <label for="login" class="block text-sm font-medium text-gray-700 mb-2">Email/NIS Terdaftar</label>
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-envelope text-gray-400 group-focus-within:text-green-600 transition-colors text-sm"></i>
                                </div>
                                <input type="text" name="login" id="login" value="{{ old('login') }}" required
                                    class="w-full pl-10 pr-4 py-3 bg-white border border-gray-300 rounded-lg focus:outline-none focus:border-green-500 focus:ring-2 focus:ring-green-200 transition-all group-hover:border-gray-400"
                                    placeholder="Masukkan email/nis Anda">
                            </div>
                        </div>

                        {{-- Password --}}
                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-lock text-gray-400 group-focus-within:text-green-600 transition-colors text-sm"></i>
                                </div>
                                <input type="password" name="password" id="password" required
                                    class="w-full pl-10 pr-12 py-3 bg-white border border-gray-300 rounded-lg focus:outline-none focus:border-green-500 focus:ring-2 focus:ring-green-200 transition-all group-hover:border-gray-400"
                                    placeholder="Masukkan password Anda">
                                <button type="button" onclick="togglePassword('password', this)"
                                    class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-green-600 transition-colors">
                                    <i class="fas fa-eye text-sm" id="password-icon"></i>
                                </button>
                            </div>
                        </div>

                        {{-- Pertanyaan Keamanan --}}
                        <div>
                            <label for="randomNumberInput" class="block text-sm font-medium text-gray-700 mb-2"
                                id="randomNumberDisplay"></label>
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-calculator text-gray-400 group-focus-within:text-green-600 transition-colors text-sm"></i>
                                </div>
                                <input type="number" name="randomNumberInput" id="randomNumberInput" required
                                    class="w-full pl-10 pr-4 py-3 bg-white border border-gray-300 rounded-lg focus:outline-none focus:border-green-500 focus:ring-2 focus:ring-green-200 transition-all group-hover:border-gray-400"
                                    placeholder="Jawaban">
                            </div>
                            <p class="text-xs text-gray-500 mt-1">Pertanyaan keamanan untuk verifikasi</p>
                        </div>

                        {{-- Remember Me & Forgot Password --}}
                        <div class="flex justify-between items-center text-sm">
                            <label class="flex items-center space-x-2 cursor-pointer">
                                <input type="checkbox" name="remember"
                                    class="w-4 h-4 text-green-600 border-gray-300 rounded focus:ring-green-500">
                                <span class="text-gray-700">Ingat Saya</span>
                            </label>

                            <a href="{{ route('student.password.request') }}" class="text-gray-600 hover:text-green-600 transition-colors">
                                Lupa Password?
                            </a>
                        </div>

                        {{-- Tombol Login dengan Loading State --}}
                        <button type="submit" id="submitButton"
                            class="w-full bg-gradient-to-r from-green-600 to-emerald-600 text-white py-3 rounded-lg font-medium flex items-center justify-center gap-2 hover:from-green-700 hover:to-emerald-700 transition-all duration-300 transform hover:-translate-y-0.5 shadow-md">
                            <i class="fas fa-sign-in-alt text-sm"></i> 
                            <span id="buttonText">Masuk Sekarang</span>
                            <div id="buttonLoader" class="hidden">
                                <div class="w-4 h-4 border-2 border-white/30 border-t-white rounded-full animate-spin"></div>
                            </div>
                        </button>

                        {{-- Link ke Register --}}
                        <div class="text-center text-sm mt-6 border-t border-gray-200 pt-5">
                            <p class="text-gray-600">
                                Belum punya akun?
                                <a href="{{ route('student.register.form') }}"
                                    class="font-medium text-green-600 hover:text-green-700 transition-colors">Daftar Sekarang</a>
                            </p>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Footer kecil --}}
            <p class="text-center text-gray-500 text-xs mt-5">
                &copy; {{ date('Y') }} Perpustakaan SMK Karya Guna 2 Bekasi
            </p>
        </div>
    </div>

    <!-- Success/Error Notification -->
    <div id="notification" class="fixed top-4 right-4 max-w-sm transform translate-x-full transition-transform duration-300 z-50">
        <div class="bg-white rounded-lg shadow-lg p-4 flex items-center">
            <div id="notificationIcon" class="mr-3"></div>
            <div>
                <p id="notificationTitle" class="font-semibold text-gray-800"></p>
                <p id="notificationMessage" class="text-sm text-gray-600"></p>
            </div>
        </div>
    </div>

    <!-- @section('scripts')
    <script src="{{ asset('assets/js/login-student.js') }}"></script>
    @stop -->

    <script>
    // Toggle password visibility
        function togglePassword(fieldId, button) {
            const field = document.getElementById(fieldId);
            const icon = document.getElementById('password-icon');

            if (field.type === 'password') {
                field.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                field.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }

        // Generate angka acak untuk pertanyaan sederhana
        function generateRandomNumberQuestion() {
            const num1 = Math.floor(Math.random() * 10) + 1; // Angka antara 1-10
            const num2 = Math.floor(Math.random() * 10) + 1; // Angka antara 1-10
            const sum = num1 + num2;

            // Tampilkan pertanyaan di label
            const questionLabel = document.getElementById('randomNumberDisplay');
            questionLabel.innerHTML = `<i class="fas fa-shield-alt mr-1 text-green-600"></i> Berapa hasil dari ${num1} + ${num2}?`;

            return sum;
        }

        // Simpan jawaban yang benar
        let correctAnswer = generateRandomNumberQuestion();

        // Show notification function
        function showNotification(title, message, type) {
            const notification = document.getElementById('notification');
            const notificationIcon = document.getElementById('notificationIcon');
            const notificationTitle = document.getElementById('notificationTitle');
            const notificationMessage = document.getElementById('notificationMessage');
            
            // Set icon based on type
            if (type === 'error') {
                notificationIcon.innerHTML = '<i class="fas fa-exclamation-circle text-red-500 text-xl"></i>';
            } else if (type === 'success') {
                notificationIcon.innerHTML = '<i class="fas fa-check-circle text-green-500 text-xl"></i>';
            }
            
            // Set content
            notificationTitle.textContent = title;
            notificationMessage.textContent = message;
            
            // Show notification
            notification.classList.remove('translate-x-full');
            
            // Hide after 3 seconds
            setTimeout(() => {
                notification.classList.add('translate-x-full');
            }, 3000);
        }

        // Validasi jawaban sebelum submit form
        document.getElementById('loginForm').addEventListener('submit', function(event) {
            const userAnswer = parseInt(document.getElementById('randomNumberInput').value, 10);
            
            if (userAnswer !== correctAnswer) {
                event.preventDefault();
                showNotification('Jawaban Salah', 'Jawaban Anda salah. Silahkan coba lagi.', 'error');
                
                // Regenerate pertanyaan baru
                correctAnswer = generateRandomNumberQuestion();
                document.getElementById('randomNumberInput').value = '';
                
                // Focus on input
                document.getElementById('randomNumberInput').focus();
            } else {
                // Show loading state
                const submitButton = document.getElementById('submitButton');
                const buttonText = document.getElementById('buttonText');
                const buttonLoader = document.getElementById('buttonLoader');
                
                submitButton.disabled = true;
                buttonText.textContent = 'Memproses...';
                buttonLoader.classList.remove('hidden');
            }
        });

        // Add input validation feedback
        document.getElementById('login').addEventListener('input', function() {
            if (this.value.length > 0) {
                this.classList.add('border-green-500');
                this.classList.remove('border-gray-300');
            } else {
                this.classList.remove('border-green-500');
                this.classList.add('border-gray-300');
            }
        });

        document.getElementById('password').addEventListener('input', function() {
            if (this.value.length > 0) {
                this.classList.add('border-green-500');
                this.classList.remove('border-gray-300');
            } else {
                this.classList.remove('border-green-500');
                this.classList.add('border-gray-300');
            }
        });

        document.getElementById('randomNumberInput').addEventListener('input', function() {
            if (this.value.length > 0) {
                this.classList.add('border-green-500');
                this.classList.remove('border-gray-300');
            } else {
                this.classList.remove('border-green-500');
                this.classList.add('border-gray-300');
            }
        });
    </script>
@endsection