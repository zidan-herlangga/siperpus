@extends('layouts.app')

@section('title', 'Daftar Siswa - ' . config('app.name'))

@section('styles')
<link rel="stylesheet" href="{{ asset('assets/css/register-student.css') }}">
<style>
    /* Custom style spesifik halaman ini */
    .bg-mesh {
        background-color: #f0fdf4;
        background-image: 
            radial-gradient(at 20% 20%, rgba(52, 211, 153, 0.15) 0px, transparent 50%),
            radial-gradient(at 80% 0%, rgba(16, 185, 129, 0.1) 0px, transparent 50%),
            radial-gradient(at 0% 80%, rgba(167, 243, 208, 0.15) 0px, transparent 50%),
            radial-gradient(at 80% 80%, rgba(52, 211, 153, 0.1) 0px, transparent 50%);
    }
    .card-glass {
        background: rgba(255, 255, 255, 0.85);
        backdrop-filter: blur(20px) saturate(180%);
        -webkit-backdrop-filter: blur(20px) saturate(180%);
        border: 1px solid rgba(255, 255, 255, 0.8);
        box-shadow: 0 10px 40px -10px rgba(0, 0, 0, 0.05), 0 4px 20px -5px rgba(0, 0, 0, 0.03);
    }
    .input-modern {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        border: 1.5px solid #e5e7eb;
    }
    .input-modern:hover { border-color: #d1d5db; }
    .input-modern:focus {
        border-color: #10b981;
        box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1);
    }
    .input-icon { transition: color 0.3s ease; }
    .input-group:focus-within .input-icon { color: #059669; }
    
    .btn-register {
        background: linear-gradient(135deg, #059669, #047857);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .btn-register:hover:not(:disabled) {
        background: linear-gradient(135deg, #047857, #065f46);
        transform: translateY(-2px);
        box-shadow: 0 10px 25px -5px rgba(5, 150, 105, 0.4);
    }
    .btn-register:disabled { opacity: 0.8; cursor: not-allowed; }

    .toast-container {
        transform: translateX(calc(100% + 2rem));
        transition: transform 0.4s cubic-bezier(0.22, 1, 0.36, 1);
    }
    .toast-container.show { transform: translateX(0); }
</style>
@stop

@section('content')
    <div class="min-h-screen bg-mesh flex items-center justify-center px-4 py-12">
        <div class="w-full max-w-3xl">
            
            {{-- Card Utama --}}
            <div class="card-glass rounded-2xl overflow-hidden animate-fade-in">
                
                {{-- Header Card --}}
                <div class="bg-gradient-to-br from-emerald-600 to-green-700 text-white text-center py-10 px-6 relative">
                    <div class="absolute inset-0 bg-black/5"></div>
                    <div class="relative z-10">
                        <div class="w-16 h-16 bg-white/15 backdrop-blur-sm rounded-2xl flex items-center justify-center mx-auto mb-4 border border-white/20">
                            <i class="fas fa-user-plus text-2xl"></i>
                        </div>
                        <h1 class="text-2xl font-bold tracking-tight">Pendaftaran Siswa Baru</h1>
                        <p class="text-green-100 text-sm mt-1">Buat akun untuk mengakses layanan perpustakaan digital</p>
                    </div>
                </div>

                {{-- Progress Steps --}}
                <div class="bg-gray-50/50 px-8 py-5 border-b border-gray-100">
                    <div class="flex items-center justify-between">
                        <div class="step-indicator active" data-step="1">
                            <span class="step-number">1</span>
                            <span class="step-label">Data Diri</span>
                        </div>
                        <div class="step-connector"></div>
                        <div class="step-indicator" data-step="2">
                            <span class="step-number">2</span>
                            <span class="step-label">Info Sekolah</span>
                        </div>
                        <div class="step-connector"></div>
                        <div class="step-indicator" data-step="3">
                            <span class="step-number">3</span>
                            <span class="step-label">Keamanan Akun</span>
                        </div>
                    </div>
                </div>

                {{-- Form Area --}}
                <div class="p-8">
                    <form id="registrationForm" action="{{ route('student.register.store') }}" method="POST" class="space-y-8">
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
                                    <p class="font-medium mb-1">Gagal mendaftar:</p>
                                    <ul class="list-disc list-inside space-y-0.5 text-red-500">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        @endif

                        {{-- === STEP 1: Informasi Pribadi === --}}
                        <div class="form-section" data-section="1">
                            <h3 class="text-base font-semibold text-gray-800 mb-4 flex items-center gap-2">
                                <div class="w-7 h-7 rounded-lg bg-emerald-100 text-emerald-600 flex items-center justify-center text-xs">
                                    <i class="fas fa-user"></i>
                                </div>
                                Informasi Pribadi
                            </h3>
                            <div class="grid md:grid-cols-2 gap-5">
                                {{-- Nama Lengkap --}}
                                <div class="form-group">
                                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1.5">Nama Lengkap</label>
                                    <div class="relative input-group">
                                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                            <i class="fas fa-user input-icon text-gray-400 text-sm"></i>
                                        </div>
                                        <input type="text" name="name" id="name" value="{{ old('name') }}" required
                                            class="input-modern w-full pl-11 pr-4 py-3 rounded-xl bg-white outline-none text-gray-800 placeholder-gray-400"
                                            placeholder="Masukkan nama lengkap">
                                    </div>
                                </div>

                                {{-- NIS --}}
                                <div class="form-group">
                                    <label for="nis" class="block text-sm font-medium text-gray-700 mb-1.5">NIS <span class="text-gray-400 font-normal">(Opsional)</span></label>
                                    <div class="relative input-group">
                                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                            <i class="fas fa-id-card input-icon text-gray-400 text-sm"></i>
                                        </div>
                                        <input type="text" name="nis" id="nis" value="{{ old('nis') }}"
                                            class="input-modern w-full pl-11 pr-4 py-3 rounded-xl bg-white outline-none text-gray-800 placeholder-gray-400"
                                            placeholder="Contoh: 20250001">
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- === STEP 2: Informasi Sekolah === --}}
                        <div class="form-section" data-section="2">
                            <h3 class="text-base font-semibold text-gray-800 mb-4 flex items-center gap-2">
                                <div class="w-7 h-7 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center text-xs">
                                    <i class="fas fa-school"></i>
                                </div>
                                Informasi Sekolah
                            </h3>
                            <div class="grid md:grid-cols-2 gap-5">
                                {{-- Kelas --}}
                                <div class="form-group">
                                    <label for="class" class="block text-sm font-medium text-gray-700 mb-1.5">Kelas</label>
                                    <div class="relative input-group">
                                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                            <i class="fas fa-graduation-cap input-icon text-gray-400 text-sm"></i>
                                        </div>
                                        <select name="class" id="class" required
                                            class="input-modern w-full pl-11 pr-10 py-3 rounded-xl bg-white outline-none text-gray-800 appearance-none cursor-pointer">
                                            @php
                                                $grades = ['X', 'XI', 'XII'];
                                                $majors = ['Akuntansi', 'Manajemen Perkantoran', 'Teknik Komputer Jaringan', 'Teknik Kendaraan Ringan'];
                                            @endphp
                                            <option value="" disabled selected>Pilih Kelas</option>
                                            @foreach ($majors as $major)
                                                @foreach ($grades as $grade)
                                                    @php $classOption = "$grade $major"; @endphp
                                                    <option value="{{ $classOption }}" {{ old('class') == $classOption ? 'selected' : '' }}>
                                                        {{ $classOption }}
                                                    </option>
                                                @endforeach
                                            @endforeach
                                        </select>
                                        <div class="absolute inset-y-0 right-0 pr-4 flex items-center pointer-events-none">
                                            <i class="fas fa-chevron-down text-gray-400 text-xs"></i>
                                        </div>
                                    </div>
                                </div>

                                {{-- No HP --}}
                                <div class="form-group">
                                    <label for="contact" class="block text-sm font-medium text-gray-700 mb-1.5">Nomor Telepon / WA</label>
                                    <div class="relative input-group">
                                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                            <i class="fas fa-phone input-icon text-gray-400 text-sm"></i>
                                        </div>
                                        <input type="tel" name="contact" id="contact" value="{{ old('contact') }}" required
                                            class="input-modern w-full pl-11 pr-4 py-3 rounded-xl bg-white outline-none text-gray-800 placeholder-gray-400"
                                            placeholder="081234567890">
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- === STEP 3: Informasi Akun === --}}
                        <div class="form-section" data-section="3">
                            <h3 class="text-base font-semibold text-gray-800 mb-4 flex items-center gap-2">
                                <div class="w-7 h-7 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center text-xs">
                                    <i class="fas fa-shield-halved"></i>
                                </div>
                                Keamanan Akun
                            </h3>
                            <div class="space-y-5">
                                {{-- Email --}}
                                <div class="form-group">
                                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1.5">Email</label>
                                    <div class="relative input-group">
                                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                            <i class="fas fa-at input-icon text-gray-400 text-sm"></i>
                                        </div>
                                        <input type="email" name="email" id="email" value="{{ old('email') }}" required
                                            class="input-modern w-full pl-11 pr-4 py-3 rounded-xl bg-white outline-none text-gray-800 placeholder-gray-400"
                                            placeholder="nama@email.com">
                                    </div>
                                </div>

                                {{-- Password Grid --}}
                                <div class="grid md:grid-cols-2 gap-5">
                                    <div class="form-group">
                                        <label for="password" class="block text-sm font-medium text-gray-700 mb-1.5">Password</label>
                                        <div class="relative input-group">
                                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                                <i class="fas fa-lock input-icon text-gray-400 text-sm"></i>
                                            </div>
                                            <input type="password" name="password" id="password" required
                                                class="input-modern w-full pl-11 pr-12 py-3 rounded-xl bg-white outline-none text-gray-800 placeholder-gray-400"
                                                placeholder="Min. 8 karakter">
                                            <button type="button" data-target="password"
                                                class="toggle-pass absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400 hover:text-gray-600 transition-colors">
                                                <i class="fas fa-eye text-sm"></i>
                                            </button>
                                        </div>
                                        {{-- Password Strength --}}
                                        <div class="mt-3 space-y-1.5">
                                            <div class="flex items-center justify-between">
                                                <span class="text-xs text-gray-500">Kekuatan Password</span>
                                                <span id="strength-text" class="text-xs font-semibold"></span>
                                            </div>
                                            <div class="w-full bg-gray-100 rounded-full h-1.5 overflow-hidden">
                                                <div id="strength-bar" class="h-1.5 rounded-full transition-all duration-500 ease-out" style="width: 0%"></div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1.5">Konfirmasi Password</label>
                                        <div class="relative input-group">
                                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                                <i class="fas fa-lock input-icon text-gray-400 text-sm"></i>
                                            </div>
                                            <input type="password" name="password_confirmation" id="password_confirmation" required
                                                class="input-modern w-full pl-11 pr-12 py-3 rounded-xl bg-white outline-none text-gray-800 placeholder-gray-400"
                                                placeholder="Ulangi password">
                                            <button type="button" data-target="password_confirmation"
                                                class="toggle-pass absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400 hover:text-gray-600 transition-colors">
                                                <i class="fas fa-eye text-sm"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Tombol Submit --}}
                        <div class="pt-2">
                            <button type="submit" id="submitButton"
                                class="btn-register w-full text-white py-3.5 rounded-xl font-semibold flex items-center justify-center gap-2 text-sm">
                                <span id="buttonText">Buat Akun Saya</span>
                                <i class="fas fa-arrow-right text-xs" id="buttonArrow"></i>
                                <div id="buttonLoader" class="hidden">
                                    <div class="w-5 h-5 border-2 border-white/30 border-t-white rounded-full animate-spin"></div>
                                </div>
                            </button>
                        </div>

                        {{-- Link ke Login --}}
                        <div class="relative my-6">
                            <div class="absolute inset-0 flex items-center"><div class="w-full border-t border-gray-200"></div></div>
                            <div class="relative flex justify-center text-xs"><span class="bg-white px-4 text-gray-400 uppercase tracking-wider">Atau</span></div>
                        </div>
                        
                        <p class="text-center text-sm text-gray-600">
                            Sudah punya akun? 
                            <a href="{{ route('student.login.form') }}" wire:navigate.prefetch="false" class="font-semibold text-emerald-600 hover:text-emerald-700 hover:underline transition-colors">
                                Login di sini
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

    {{-- JAVASCRIPT --}}
    <script>
        function initRegisterScripts() {
            const form = document.getElementById('registrationForm');
            if (!form) return; // Hanya jalan jika ada form register

            const submitBtn = document.getElementById('submitButton');
            const btnText = document.getElementById('buttonText');
            const btnArrow = document.getElementById('buttonArrow');
            const btnLoader = document.getElementById('buttonLoader');
            const passInput = document.getElementById('password');
            const strengthBar = document.getElementById('strength-bar');
            const strengthText = document.getElementById('strength-text');
            
            const sections = document.querySelectorAll('.form-section');
            const steps = document.querySelectorAll('.step-indicator');

            // --- 1. Toggle Password Visibility ---
            document.querySelectorAll('.toggle-pass').forEach(button => {
                // Mencegah duplikat event listener
                if (button.hasAttribute('data-bound')) return;
                button.setAttribute('data-bound', 'true');

                button.addEventListener('click', function() {
                    const targetId = this.getAttribute('data-target');
                    const input = document.getElementById(targetId);
                    const icon = this.querySelector('i');
                    
                    if (input.type === 'password') {
                        input.type = 'text';
                        icon.classList.remove('fa-eye');
                        icon.classList.add('fa-eye-slash');
                    } else {
                        input.type = 'password';
                        icon.classList.remove('fa-eye-slash');
                        icon.classList.add('fa-eye');
                    }
                });
            });

            // --- 2. Password Strength Logic ---
            if (passInput) {
                passInput.addEventListener('input', function() {
                    const val = this.value;
                    let score = 0;
                    if (val.length >= 8) score++;
                    if (/[A-Z]/.test(val)) score++;
                    if (/[0-9]/.test(val)) score++;
                    if (/[^A-Za-z0-9]/.test(val)) score++;

                    const levels = [
                        { width: '0%', color: '#e5e7eb', text: '', textClass: 'text-gray-400' },
                        { width: '25%', color: '#ef4444', text: 'Lemah', textClass: 'text-red-500' },
                        { width: '50%', color: '#f59e0b', text: 'Cukup', textClass: 'text-amber-500' },
                        { width: '75%', color: '#3b82f6', text: 'Kuat', textClass: 'text-blue-500' },
                        { width: '100%', color: '#10b981', text: 'Sangat Kuat', textClass: 'text-emerald-500' }
                    ];

                    const level = val.length === 0 ? levels[0] : levels[score];
                    
                    strengthBar.style.width = level.width;
                    strengthBar.style.backgroundColor = level.color;
                    strengthText.textContent = level.text;
                    strengthText.className = `text-xs font-semibold ${level.textClass}`;
                });
            }

            // --- 3. Progress Step Tracker Logic ---
            function updateProgress() {
                if(!sections.length || !steps.length) return;

                let allFilled = true;
                sections.forEach((section, index) => {
                    const inputs = section.querySelectorAll('input[required], select[required]');
                    let sectionFilled = true;
                    inputs.forEach(input => { if (!input.value.trim()) sectionFilled = false; });

                    const step = steps[index];
                    const stepNum = step.querySelector('.step-number');
                    
                    if (sectionFilled) {
                        step.classList.add('completed');
                        step.classList.remove('active');
                        stepNum.innerHTML = '<i class="fas fa-check text-xs"></i>';
                    } else {
                        step.classList.remove('completed');
                        if (allFilled) step.classList.add('active');
                        else step.classList.remove('active');
                        stepNum.textContent = index + 1;
                    }
                    if (!sectionFilled) allFilled = false;
                });

                if(!allFilled) {
                    for (let i = 0; i < sections.length; i++) {
                        const inputs = sections[i].querySelectorAll('input[required], select[required]');
                        let isEmpty = false;
                        inputs.forEach(inp => { if(!inp.value.trim()) isEmpty = true; });
                        
                        if (isEmpty) {
                            steps[i].classList.add('active');
                            steps[i].classList.remove('completed');
                            steps[i].querySelector('.step-number').textContent = i + 1;
                            break;
                        } else {
                            steps[i].classList.remove('active');
                            steps[i].classList.add('completed');
                            steps[i].querySelector('.step-number').innerHTML = '<i class="fas fa-check text-xs"></i>';
                        }
                    }
                }
            }

            form.addEventListener('input', updateProgress);
            form.addEventListener('change', updateProgress);
            updateProgress(); 

            // --- 4. Form Submit & Loading State ---
            form.addEventListener('submit', function() {
                submitBtn.disabled = true;
                btnText.textContent = 'Memproses Pendaftaran...';
                if(btnArrow) btnArrow.classList.add('hidden');
                if(btnLoader) btnLoader.classList.remove('hidden');
            });

            // --- 5. Toast Notification ---
            window.hideNotification = function() {
                const notification = document.getElementById('notification');
                if(notification) notification.classList.remove('show');
            };

            window.showNotification = function(title, message, type) {
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
            };
        }

        // Jalankan saat pertama kali load
        document.addEventListener('DOMContentLoaded', initRegisterScripts);

        // Jalankan ULANG setiap kali navigasi Livewire berhasil
        document.addEventListener('livewire:navigated', initRegisterScripts);
    </script>
@endsection