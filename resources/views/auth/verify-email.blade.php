@extends('layouts.app')

@section('title', 'Lupa Password')

@section('styles')
<link rel="stylesheet" href="{{ asset('assets/css/email.css') }}">
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
    .input-modern:hover { border-color: #d1d5db; }
    .input-modern:focus {
        border-color: #10b981;
        box-shadow: 0 0 0 3px rgba(16, 185, 129, 0.1);
    }
    .input-modern.border-red-400 {
        border-color: #f87171;
    }
    .input-modern.border-red-400:focus {
        box-shadow: 0 0 0 3px rgba(248, 113, 113, 0.1);
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
    .btn-submit:disabled { 
        opacity: 0.7; 
        cursor: not-allowed; 
        transform: none !important;
        box-shadow: none !important;
    }
</style>
@stop

@section('content')
<div class="min-h-screen bg-mesh flex items-center justify-center px-4 py-12">
    <div class="max-w-md w-full relative z-10">
        <div class="card-glass rounded-2xl overflow-hidden animate-fade-in">
            
            {{-- Header Card --}}
            <div class="bg-gradient-to-br from-emerald-600 to-green-700 text-white p-10 text-center relative">
                <div class="absolute inset-0 bg-black/5"></div>
                <div class="relative z-10">
                    <div class="w-16 h-16 bg-white/15 backdrop-blur-sm rounded-2xl flex items-center justify-center mx-auto mb-4 border border-white/20">
                        <i class="fas fa-key text-2xl"></i>
                    </div>
                    <h1 class="text-2xl font-bold tracking-tight mb-1">Lupa Password?</h1>
                    <p class="text-green-100 text-sm">Jangan khawatir, kami akan bantu mereset password Anda.</p>
                </div>
            </div>

            {{-- Form Area --}}
            <div class="p-8">
                
                {{-- Pesan Sukses --}}
                @if (session('status'))
                    <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm p-4 rounded-xl flex items-start mb-6 animate-slide-down">
                        <i class="fas fa-check-circle mr-3 mt-0.5 text-emerald-500"></i>
                        <span>{{ session('status') }}</span>
                    </div>
                @endif

                {{-- Pesan Error --}}
                @error('email')
                    <div class="bg-red-50 border border-red-200 text-red-600 text-sm p-4 rounded-xl flex items-start mb-6 animate-slide-down">
                        <i class="fas fa-exclamation-triangle mr-3 mt-0.5 text-red-500"></i>
                        <span>{{ $message }}</span>
                    </div>
                @enderror

                <form method="POST" action="{{ route('password.email') }}" id="forgotPasswordForm">
                    @csrf
                    
                    <div class="mb-6">
                        <label for="email" class="block text-sm font-semibold text-gray-700 mb-1.5">Alamat Email Terdaftar</label>
                        <div class="relative input-group">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                                <i class="fas fa-at input-icon text-gray-400 text-sm"></i>
                            </div>
                            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="email"
                                   class="input-modern w-full pl-11 pr-4 py-3 rounded-xl bg-white outline-none text-gray-800 placeholder-gray-400 @error('email') border-red-400 @enderror"
                                   placeholder="nama@email.com">
                        </div>
                    </div>

                    <button type="submit" id="submitButton" 
                            class="btn-submit w-full text-white py-3.5 rounded-xl font-semibold flex items-center justify-center gap-2 text-sm">
                        <i class="fas fa-paper-plane text-xs" id="btnIcon"></i>
                        <span id="buttonText">Kirim Link Reset Password</span>
                        <div id="buttonLoader" class="hidden">
                            <div class="w-5 h-5 border-2 border-white/30 border-t-white rounded-full animate-spin"></div>
                        </div>
                    </button>
                </form>

                {{-- Tips Bantuan --}}
                <div class="mt-8 bg-blue-50/50 border border-blue-100 rounded-xl p-5">
                    <h3 class="font-semibold text-blue-800 text-sm mb-3 flex items-center gap-2">
                        <i class="fas fa-lightbulb text-blue-500"></i>
                        Butuh bantuan?
                    </h3>
                    <ul class="text-xs text-blue-600 space-y-2 leading-relaxed">
                        <li class="flex items-start gap-2">
                            <i class="fas fa-check text-blue-400 mt-0.5"></i>
                            <span>Pastikan email yang Anda masukkan terdaftar di sistem.</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <i class="fas fa-check text-blue-400 mt-0.5"></i>
                            <span>Periksa folder <strong>Spam</strong> atau <strong>Junk</strong> jika tidak menerima email.</span>
                        </li>
                        <li class="flex items-start gap-2">
                            <i class="fas fa-check text-blue-400 mt-0.5"></i>
                            <span>Link reset password hanya berlaku selama <strong>60 menit</strong>.</span>
                        </li>
                    </ul>
                </div>

                {{-- Link Kembali --}}
                <div class="mt-8 border-t border-gray-100 pt-5 text-center">
                    <a href="{{ route('student.login.form') }}" class="text-sm text-gray-500 hover:text-emerald-600 transition-colors inline-flex items-center gap-2 font-medium">
                        <i class="fas fa-arrow-left text-xs"></i>
                        Kembali ke Halaman Login
                    </a>
                </div>
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
    const form = document.getElementById('forgotPasswordForm');
    const submitBtn = document.getElementById('submitButton');
    const btnText = document.getElementById('buttonText');
    const btnIcon = document.getElementById('btnIcon');
    const btnLoader = document.getElementById('buttonLoader');
    
    // Cek apakah ada cooldown yang tersimpan (misal user baru saja kirim link)
    let cooldownEnd = localStorage.getItem('resetPassCooldownEnd');
    if (cooldownEnd && parseInt(cooldownEnd) > Date.now()) {
        startCooldown(parseInt(cooldownEnd) - Date.now());
    }

    form.addEventListener('submit', function() {
        // Aktifkan loading state
        submitBtn.disabled = true;
        btnIcon.classList.add('hidden');
        btnLoader.classList.remove('hidden');
        btnText.textContent = 'Mengirim...';

        // Set cooldown 60 detik untuk mencegah spam
        const cooldownDuration = 60000; 
        localStorage.setItem('resetPassCooldownEnd', Date.now() + cooldownDuration);
    });

    function startCooldown(remainingTime) {
        submitBtn.disabled = true;
        btnIcon.classList.add('hidden');
        btnLoader.classList.add('hidden');
        
        const interval = setInterval(() => {
            if (remainingTime <= 0) {
                clearInterval(interval);
                localStorage.removeItem('resetPassCooldownEnd');
                submitBtn.disabled = false;
                btnIcon.classList.remove('hidden');
                btnText.textContent = 'Kirim Link Reset Password';
            } else {
                const seconds = Math.ceil(remainingTime / 1000);
                btnText.textContent = `Kirim ulang dalam ${seconds}d`;
                remainingTime -= 1000;
            }
        }, 1000);
    }
});
</script>
@stop

@endsection