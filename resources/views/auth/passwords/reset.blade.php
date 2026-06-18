@extends('layouts.app')

@section('title', 'Reset Password')

@section('styles')
<link rel="stylesheet" href="{{ asset('assets/css/reset-password.css') }}" media="print" onload="this.media='all'" fetchpriority="low">
    <noscript><link rel="stylesheet" href="{{ asset('assets/css/reset-password.css') }}"></noscript>
<style>
    /* Custom style spesifik halaman ini */
    .bg-mesh {
        background-color: #f0fdf4;
        background-image: 
            radial-gradient(at 40% 20%, rgba(52, 211, 153, 0.15) 0px, transparent 50%),
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
    .input-modern:hover:not(:disabled):not([readonly]) { border-color: #d1d5db; }
    .input-modern:focus {
        border-color: #10b981;
        box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1);
    }
    .input-modern.border-red-400 {
        border-color: #f87171;
    }
    .input-modern.border-red-400:focus {
        box-shadow: 0 0 0 3px rgba(248, 113, 113, 0.1);
        border-color: #f87171;
    }
    .input-icon { transition: color 0.3s ease; }
    .input-group:focus-within .input-icon { color: #059669; }
    
    .btn-submit {
        background: linear-gradient(135deg, #059669, #047857);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .btn-submit:hover:not(:disabled) {
        background: linear-gradient(135deg, #047857, #065f46);
        transform: translateY(-2px);
        box-shadow: 0 10px 25px -5px rgba(5, 150, 105, 0.4);
    }
    .btn-submit:active:not(:disabled) { transform: translateY(0); }
    .btn-submit:disabled { opacity: 0.7; cursor: not-allowed; }
</style>
@stop

@section('content')
<div class="min-h-screen bg-mesh flex items-center justify-center py-12 px-4">
    <div class="max-w-md w-full relative z-10">
        <div class="card-glass rounded-2xl overflow-hidden animate-fade-in">
            
            {{-- Header Card --}}
            <div class="bg-gradient-to-br from-emerald-600 to-emerald-700 text-white p-10 text-center relative">
                <div class="absolute inset-0 bg-black/5"></div>
                <div class="relative z-10">
                    <div class="w-16 h-16 bg-white/15 backdrop-blur-sm rounded-2xl flex items-center justify-center mx-auto mb-4 border border-white/20">
                        <i class="fas fa-shield-halved text-2xl"></i>
                    </div>
                    <h1 class="text-2xl font-bold tracking-tight mb-1">Buat Password Baru</h1>
                    <p class="text-emerald-100 text-sm">Masukkan password baru yang aman untuk akun Anda.</p>
                </div>
            </div>
            
            <div class="p-8">
                <form method="POST" action="{{ route('password.update') }}" id="resetPasswordForm">
                    @csrf
                    <input type="hidden" name="token" value="{{ $token }}">

                    <div class="space-y-5">
                        {{-- Email Field (Readonly) --}}
                        <div>
                            <label for="email" class="block text-sm font-semibold text-gray-700 mb-1.5">Alamat Email</label>
                            <div class="relative input-group">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <i class="fas fa-at input-icon text-gray-400 text-sm"></i>
                                </div>
                                <input id="email" type="email" name="email" value="{{ $email ?? old('email') }}" required readonly
                                       class="input-modern w-full pl-11 pr-4 py-3 rounded-xl bg-gray-50 text-gray-500 cursor-not-allowed @error('email') border-red-400 @enderror"
                                       placeholder="nama@email.com">
                            </div>
                            @error('email')
                                <p class="text-red-500 text-xs mt-1.5 ml-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Password Baru --}}
                        <div>
                            <label for="password" class="block text-sm font-semibold text-gray-700 mb-1.5">Password Baru</label>
                            <div class="relative input-group">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <i class="fas fa-lock input-icon text-gray-400 text-sm"></i>
                                </div>
                                <input id="password" type="password" name="password" required minlength="8"
                                    autocomplete="new-password"
                                    class="input-modern w-full pl-11 pr-12 py-3 rounded-xl bg-white outline-none text-gray-800 placeholder-gray-400 @error('password') border-red-400 @enderror"
                                    placeholder="Min. 8 karakter">
                                <button type="button" data-target="password"
                                    class="toggle-pass absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400 hover:text-gray-600 transition-colors">
                                    <i class="fas fa-eye text-sm"></i>
                                </button>
                            </div>
                            @error('password')
                                <p class="text-red-500 text-xs mt-1.5 ml-1">{{ $message }}</p>
                            @enderror
                            
                            {{-- Strength Bar --}}
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

                        {{-- Konfirmasi Password --}}
                        <div>
                            <label for="password_confirmation" class="block text-sm font-semibold text-gray-700 mb-1.5">Konfirmasi Password Baru</label>
                            <div class="relative input-group">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                    <i class="fas fa-lock input-icon text-gray-400 text-sm"></i>
                                </div>
                                <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password"
                                    class="input-modern w-full pl-11 pr-12 py-3 rounded-xl bg-white outline-none text-gray-800 placeholder-gray-400"
                                    placeholder="Ulangi password baru">
                                <button type="button" data-target="password_confirmation"
                                    class="toggle-pass absolute inset-y-0 right-0 pr-4 flex items-center text-gray-400 hover:text-gray-600 transition-colors">
                                    <i class="fas fa-eye text-sm"></i>
                                </button>
                            </div>
                            <div id="password-match" class="text-xs mt-1.5 ml-1 min-h-[1rem]"></div>
                        </div>
                    </div>

                    {{-- Checklist Syarat Password --}}
                    <div class="mt-6 bg-gray-50 border border-gray-100 rounded-xl p-4">
                        <h3 class="text-xs font-semibold text-gray-600 uppercase tracking-wider mb-3">Syarat Password:</h3>
                        <ul class="text-xs text-gray-500 space-y-2.5">
                            <li id="req-length" class="flex items-center gap-2 req-item">
                                <i class="fas fa-circle text-[6px] text-gray-300 req-icon"></i>
                                <span>Minimal 8 karakter</span>
                            </li>
                            <li id="req-uppercase" class="flex items-center gap-2 req-item">
                                <i class="fas fa-circle text-[6px] text-gray-300 req-icon"></i>
                                <span>Setidaknya satu huruf besar (A-Z)</span>
                            </li>
                            <li id="req-lowercase" class="flex items-center gap-2 req-item">
                                <i class="fas fa-circle text-[6px] text-gray-300 req-icon"></i>
                                <span>Setidaknya satu huruf kecil (a-z)</span>
                            </li>
                            <li id="req-number" class="flex items-center gap-2 req-item">
                                <i class="fas fa-circle text-[6px] text-gray-300 req-icon"></i>
                                <span>Setidaknya satu angka (0-9)</span>
                            </li>
                        </ul>
                    </div>

                    <div class="mt-8">
                        <button type="submit" id="submitButton" 
                                class="btn-submit w-full text-white py-3.5 rounded-xl font-semibold flex items-center justify-center gap-2 text-sm">
                            <i class="fas fa-shield-halved text-xs" id="btnIcon"></i>
                            <span id="buttonText">Simpan Password Baru</span>
                            <div id="buttonLoader" class="hidden">
                                <div class="w-5 h-5 border-2 border-white/30 border-t-white rounded-full animate-spin"></div>
                            </div>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Footer Copyright --}}
        <p class="text-center text-gray-400 text-xs mt-6">
            &copy; {{ date('Y') }} SMK Karya Guna 2 Bekasi &mdash; Perpustakaan Digital
        </p>
    </div>
</div>

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const passInput = document.getElementById('password');
    const passConfirmInput = document.getElementById('password_confirmation');
    const strengthBar = document.getElementById('strength-bar');
    const strengthText = document.getElementById('strength-text');
    const matchText = document.getElementById('password-match');
    const form = document.getElementById('resetPasswordForm');
    const submitBtn = document.getElementById('submitButton');
    const btnText = document.getElementById('buttonText');
    const btnIcon = document.getElementById('btnIcon');
    const btnLoader = document.getElementById('buttonLoader');

    // --- 1. Toggle Password Visibility ---
    document.querySelectorAll('.toggle-pass').forEach(button => {
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

    // --- 2. Password Checker Logic ---
    passInput.addEventListener('input', function() {
        const val = this.value;
        
        // Cek syarat individual
        const checks = {
            'req-length': val.length >= 8,
            'req-uppercase': /[A-Z]/.test(val),
            'req-lowercase': /[a-z]/.test(val),
            'req-number': /[0-9]/.test(val)
        };

        // Update UI Checklist
        Object.entries(checks).forEach(([id, isValid]) => {
            const el = document.getElementById(id);
            const icon = el.querySelector('.req-icon');
            if (isValid) {
                icon.classList.remove('fa-circle', 'text-gray-300');
                icon.classList.add('fa-check-circle', 'text-emerald-500');
                el.classList.add('text-emerald-600');
                el.classList.remove('text-gray-500');
            } else {
                icon.classList.add('fa-circle', 'text-gray-300');
                icon.classList.remove('fa-check-circle', 'text-emerald-500');
                el.classList.remove('text-emerald-600');
                el.classList.add('text-gray-500');
            }
        });

        // Hitung Score untuk Strength Bar
        let score = Object.values(checks).filter(Boolean).length;
        
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

        // Cek kecocokan konfirmasi password (jika sudah ada isinya)
        checkMatch();
    });

    // --- 3. Cek Kecocokan Password ---
    passConfirmInput.addEventListener('input', checkMatch);

    function checkMatch() {
        const pass = passInput.value;
        const confirm = passConfirmInput.value;
        
        if (confirm.length === 0) {
            matchText.textContent = '';
            passConfirmInput.classList.remove('border-emerald-400', 'border-red-400');
        } else if (pass === confirm) {
            matchText.innerHTML = '<span class="text-emerald-500 font-medium">Password cocok</span>';
            passConfirmInput.classList.add('border-emerald-400');
            passConfirmInput.classList.remove('border-red-400');
        } else {
            matchText.innerHTML = '<span class="text-red-500 font-medium">Password tidak cocok</span>';
            passConfirmInput.classList.add('border-red-400');
            passConfirmInput.classList.remove('border-emerald-400');
        }
    }

    // --- 4. Submit & Loading State ---
    form.addEventListener('submit', function() {
        submitBtn.disabled = true;
        btnIcon.classList.add('hidden');
        btnLoader.classList.remove('hidden');
        btnText.textContent = 'Menyimpan...';
    });
});
</script>
@stop

@endsection