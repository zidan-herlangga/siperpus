@extends('layouts.app')

@section('title', 'Login Siswa - ' . config('app.name'))

@section('styles')
<link rel="stylesheet" href="{{ asset('assets/css/login-student.css') }}">
<style>
    /* Custom style spesifik halaman ini agar tidak konflik */
    .bg-mesh {
        background-color: #f0fdf4;
        background-image: 
            radial-gradient(at 40% 20%, rgba(52, 211, 153, 0.15) 0px, transparent 50%),
            radial-gradient(at 80% 0%, rgba(16, 185, 129, 0.1) 0px, transparent 50%),
            radial-gradient(at 0% 50%, rgba(167, 243, 208, 0.15) 0px, transparent 50%),
            radial-gradient(at 80% 50%, rgba(52, 211, 153, 0.1) 0px, transparent 50%),
            radial-gradient(at 0% 100%, rgba(16, 185, 129, 0.1) 0px, transparent 50%);
    }
    .card-glass {
        background: rgba(255, 255, 255, 0.85);
        backdrop-filter: blur(20px) saturate(180%);
        -webkit-backdrop-filter: blur(20px) saturate(180%);
        border: 1px solid rgba(255, 255, 255, 0.8);
        box-shadow: 
            0 10px 40px -10px rgba(0, 0, 0, 0.05),
            0 4px 20px -5px rgba(0, 0, 0, 0.03);
    }
    .input-modern {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        border: 1.5px solid #e5e7eb;
    }
    .input-modern:hover {
        border-color: #d1d5db;
    }
    .input-modern:focus {
        border-color: #10b981;
        box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1);
    }
    .input-icon {
        transition: color 0.3s ease;
    }
    .input-group:focus-within .input-icon {
        color: #059669;
    }
    .btn-login {
        background: linear-gradient(135deg, #059669, #047857);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .btn-login:hover:not(:disabled) {
        background: linear-gradient(135deg, #047857, #065f46);
        transform: translateY(-2px);
        box-shadow: 0 10px 25px -5px rgba(5, 150, 105, 0.4);
    }
    .btn-login:active:not(:disabled) {
        transform: translateY(0);
    }
    .btn-login:disabled {
        opacity: 0.8;
        cursor: not-allowed;
    }
    
    /* Toast Notification */
    .toast-container {
        transform: translateX(calc(100% + 2rem));
        transition: transform 0.4s cubic-bezier(0.22, 1, 0.36, 1);
    }
    .toast-container.show {
        transform: translateX(0);
    }
</style>
@stop

@section('content')
    <div class="min-h-screen bg-mesh flex items-center justify-center px-4 py-12 relative">
        
        {{-- Card Utama --}}
        <div class="w-full max-w-md animate-fade-in">
            <div class="card-glass rounded-2xl overflow-hidden">
                
                {{-- Header Card --}}
                <div class="bg-gradient-to-br from-emerald-600 to-green-700 text-white text-center py-10 px-6 relative">
                    <div class="absolute inset-0 bg-black/5"></div>
                    <div class="relative z-10">
                        <div class="w-16 h-16 bg-white/15 backdrop-blur-sm rounded-2xl flex items-center justify-center mx-auto mb-4 border border-white/20">
                            <i class="fas fa-user-graduate text-2xl"></i>
                        </div>
                        <h1 class="text-2xl font-bold tracking-tight">Masuk ke Akun</h1>
                        <p class="text-green-100 text-sm mt-1">Perpustakaan SMK Karya Guna 2</p>
                    </div>
                </div>

                {{-- Form Area --}}
                <div class="p-8">
                    <form id="loginForm" action="{{ route('student.login.auth') }}" method="POST" class="space-y-5">
                        @csrf

                        {{-- Pesan Status --}}
                        @if (session('status'))
                            <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm p-4 rounded-xl flex items-start animate-slide-down">
                                <i class="fas fa-check-circle mr-3 mt-0.5 text-emerald-500"></i>
                                <span>{{ session('status') }}</span>
                            </div>
                        @endif

                        {{-- Pesan Error --}}
                        @if ($errors->any())
                            <div class="bg-red-50 border border-red-200 text-red-600 text-sm p-4 rounded-xl flex items-start animate-slide-down">
                                <i class="fas fa-exclamation-triangle mr-3 mt-0.5 text-red-500"></i>
                                <div>
                                    <p class="font-medium mb-1">Gagal masuk:</p>
                                    <ul class="list-disc list-inside space-y-0.5 text-red-500">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        @endif

                        {{-- Input Email/NIS --}}
                        <div>
                            <label for="login" class="block text-sm font-semibold text-gray-700 mb-1.5">Email / NIS</label>
                            <div class="relative input-group">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <i class="fas fa-at input-icon text-gray-400 text-sm"></i>
                                </div>
                                <input type="text" name="login" id="login" value="{{ old('login') }}" required
                                    class="input-modern w-full pl-11 pr-4 py-3 rounded-xl bg-white outline-none text-gray-800 placeholder-gray-400"
                                    placeholder="contoh@siswa.com atau NIS">
                            </div>
                        </div>

                        {{-- Input Password --}}
                        <div>
                            <label for="password" class="block text-sm font-semibold text-gray-700 mb-1.5">Password</label>
                            <div class="relative input-group">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <i class="fas fa-lock input-icon text-gray-400 text-sm"></i>
                                </div>
                                <input type="password" name="password" id="password" required
                                    class="input-modern w-full pl-11 pr-12 py-3 rounded-xl bg-white outline-none text-gray-800 placeholder-gray-400"
                                    placeholder="Masukkan password">
                                <button type="button" id="togglePasswordBtn"
                                    class="absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400 hover:text-gray-600 transition-colors">
                                    <i class="fas fa-eye text-sm" id="password-icon"></i>
                                </button>
                            </div>
                        </div>

                        {{-- Pertanyaan Keamanan (Math Captcha) --}}
                        <div>
                            <label for="randomNumberInput" class="block text-sm font-semibold text-gray-700 mb-1.5" id="randomNumberDisplay">
                                <i class="fas fa-shield-halved mr-1 text-emerald-600"></i> Verifikasi Keamanan
                            </label>
                            <div class="relative input-group">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <i class="fas fa-calculator input-icon text-gray-400 text-sm"></i>
                                </div>
                                <input type="number" name="randomNumberInput" id="randomNumberInput" required
                                    class="input-modern w-full pl-11 pr-4 py-3 rounded-xl bg-white outline-none text-gray-800 placeholder-gray-400"
                                    placeholder="Jawaban">
                            </div>
                        </div>

                        {{-- Remember & Lupa Password --}}
                        <div class="flex items-center justify-between text-sm">
                            <label class="flex items-center gap-2 cursor-pointer select-none group">
                                <input type="checkbox" name="remember"
                                    class="w-4 h-4 text-emerald-600 border-gray-300 rounded focus:ring-emerald-500 transition">
                                <span class="text-gray-600 group-hover:text-gray-800 transition-colors">Ingat saya</span>
                            </label>
                            <a href="{{ route('student.password.request') }}" wire:navigate.prefetch="false" class="text-emerald-600 hover:text-emerald-700 font-medium transition-colors">
                                Lupa password?
                            </a>
                        </div>

                        {{-- Tombol Submit --}}
                        <button type="submit" id="submitButton"
                            class="btn-login w-full text-white py-3.5 rounded-xl font-semibold flex items-center justify-center gap-2 text-sm">
                            <span id="buttonText">Masuk ke Akun</span>
                            <i class="fas fa-arrow-right text-xs" id="buttonArrow"></i>
                            <div id="buttonLoader" class="hidden">
                                <div class="w-5 h-5 border-2 border-white/30 border-t-white rounded-full animate-spin"></div>
                            </div>
                        </button>

                        {{-- Divider --}}
                        <div class="relative my-6">
                            <div class="absolute inset-0 flex items-center">
                                <div class="w-full border-t border-gray-200"></div>
                            </div>
                            <div class="relative flex justify-center text-xs">
                                <span class="bg-white px-4 text-gray-400 uppercase tracking-wider">Atau</span>
                            </div>
                        </div>

                        {{-- Link Register --}}
                        <p class="text-center text-sm text-gray-600">
                            Belum punya akun siswa? 
                            <a href="{{ route('student.register.form') }}" wire:navigate.prefetch="false" class="font-semibold text-emerald-600 hover:text-emerald-700 hover:underline transition-colors">
                                Daftar Sekarang
                            </a>
                        </p>
                    </form>
                </div>
            </div>
        </div>
    </div>

        {{-- Toast Notification --}}
    <div id="notification" class="toast-container fixed top-6 right-6 max-w-sm z-[100]">
        <div class="bg-white rounded-xl shadow-2xl border border-gray-100 p-4 flex items-start gap-3">
            <div id="notificationIcon" class="mt-0.5"></div>
            <div class="flex-1">
                <p id="notificationTitle" class="font-semibold text-gray-800 text-sm"></p>
                <p id="notificationMessage" class="text-gray-500 text-xs mt-0.5"></p>
            </div>
            <button onclick="hideNotification()" class="text-gray-300 hover:text-gray-500 transition-colors">
                <i class="fas fa-times text-xs"></i>
            </button>
        </div>
    </div>

    <script>
        function initLoginScripts() {
            const loginForm = document.getElementById('loginForm');
            if (!loginForm) return; 

            const passwordInput = document.getElementById('password');
            const togglePasswordBtn = document.getElementById('togglePasswordBtn');
            const passwordIcon = document.getElementById('password-icon');
            const submitButton = document.getElementById('submitButton');
            const buttonText = document.getElementById('buttonText');
            const buttonArrow = document.getElementById('buttonArrow');
            const buttonLoader = document.getElementById('buttonLoader');
            const randomNumberInput = document.getElementById('randomNumberInput');
            
            let correctAnswer = generateMathQuestion();

            if (togglePasswordBtn) {
                togglePasswordBtn.addEventListener('click', function() {
                    const isPassword = passwordInput.type === 'password';
                    passwordInput.type = isPassword ? 'text' : 'password';
                    passwordIcon.classList.toggle('fa-eye', !isPassword);
                    passwordIcon.classList.toggle('fa-eye-slash', isPassword);
                });
            }

            function generateMathQuestion() {
                const num1 = Math.floor(Math.random() * 10) + 1;
                const num2 = Math.floor(Math.random() * 10) + 1;
                const sum = num1 + num2;
                
                const displayEl = document.getElementById('randomNumberDisplay');
                if(displayEl) {
                    displayEl.innerHTML = `<i class="fas fa-shield-halved mr-1 text-emerald-600"></i> Berapa ${num1} + ${num2} = ?`;
                }
                return sum;
            }

            if (randomNumberInput) {
                randomNumberInput.addEventListener('input', function(e) {
                    this.value = this.value.replace(/[^0-9-]/g, '');
                });
            }

            window.hideNotification = function() {
                const notification = document.getElementById('notification');
                if(notification) notification.classList.remove('show');
            };

            function showNotification(title, message, type) {
                const notification = document.getElementById('notification');
                const iconEl = document.getElementById('notificationIcon');
                
                if (!notification) return;

                if (type === 'error') {
                    iconEl.innerHTML = '<div class="w-8 h-8 bg-red-100 rounded-full flex items-center justify-center"><i class="fas fa-xmark text-red-500 text-sm"></i></div>';
                } else {
                    iconEl.innerHTML = '<div class="w-8 h-8 bg-emerald-100 rounded-full flex items-center justify-center"><i class="fas fa-check text-emerald-500 text-sm"></i></div>';
                }
                
                document.getElementById('notificationTitle').textContent = title;
                document.getElementById('notificationMessage').textContent = message;
                
                notification.classList.add('show');
                setTimeout(window.hideNotification, 4000);
            }

            loginForm.addEventListener('submit', function(event) {
                const userInput = document.getElementById('randomNumberInput');
                const rawAnswer = userInput.value.trim(); 
                const userAnswer = parseInt(rawAnswer, 10);
                
                if (isNaN(userAnswer) || userAnswer !== correctAnswer) {
                    event.preventDefault();
                    showNotification('Verifikasi Gagal', 'Jawaban matematika yang Anda masukkan salah.', 'error');
                    
                    correctAnswer = generateMathQuestion();
                    userInput.value = '';
                    userInput.focus();
                    return;
                }

                submitButton.disabled = true;
                buttonText.textContent = 'Memproses...';
                if(buttonArrow) buttonArrow.classList.add('hidden');
                if(buttonLoader) buttonLoader.classList.remove('hidden');
            });
        }

        document.addEventListener('DOMContentLoaded', initLoginScripts);
        // document.addEventListener('livewire:navigated', initLoginScripts);        
    </script>
@endsection