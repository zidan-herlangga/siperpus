@extends('layouts.app')

@section('title', 'Verifikasi Email')

@section('styles')
<link rel="stylesheet" href="{{ asset('assets/css/email.css') }}" media="print" onload="this.media='all'" fetchpriority="low">
    <noscript><link rel="stylesheet" href="{{ asset('assets/css/email.css') }}"></noscript>
<style>
    .bg-mesh {
        background-color: #f4f6f1;
        background-image: radial-gradient(#e2e9e1 1px, transparent 1px);
        background-size: 24px 24px;
    }
    .card-glass {
        background: #ffffff;
        border: 1px solid #e2e9e1;
        box-shadow: 0 20px 50px -30px rgba(0, 66, 37, 0.25);
    }
    .btn-submit {
        background: #ffc600;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .btn-submit:hover:not(:disabled) {
        background: #e0b400;
        box-shadow: 0 10px 25px -10px rgba(22, 39, 29, 0.25);
    }
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
            <div class="bg-[#006739] text-white p-10 text-center relative">
                <div class="absolute inset-0 opacity-10" style="background-image: radial-gradient(#ffffff 1px, transparent 1px); background-size: 18px 18px;"></div>
                <div class="relative z-10">
                    <div class="w-16 h-16 bg-white/15 backdrop-blur-sm rounded-2xl flex items-center justify-center mx-auto mb-4 border border-white/20">
                        <i class="fas fa-envelope text-2xl"></i>
                    </div>
                    <h1 class="text-2xl font-bold tracking-tight mb-1">Verifikasi Email</h1>
                    <p class="text-emerald-100 text-sm">Konfirmasi alamat email Anda untuk mengaktifkan akun.</p>
                </div>
            </div>

            {{-- Content Area --}}
            <div class="p-8">
                
                {{-- Pesan Sukses --}}
                @if (session('message'))
                    <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm p-4 rounded-xl flex items-start mb-6 animate-slide-down">
                        <i class="fas fa-check-circle mr-3 mt-0.5 text-emerald-500"></i>
                        <span>{{ session('message') }}</span>
                    </div>
                @endif

                <div class="text-center mb-6">
                    <div class="w-20 h-20 bg-emerald-50 rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-envelope-open-text text-3xl text-emerald-500"></i>
                    </div>
                    <p class="text-gray-600 text-sm leading-relaxed">
                        Sebelum melanjutkan, silakan periksa email Anda untuk link verifikasi.
                    </p>
                    <p class="text-gray-500 text-xs mt-2">
                        Jika tidak menerima email, klik tombol di bawah untuk mengirim ulang.
                    </p>
                </div>

                <form method="POST" action="{{ route('verification.send') }}" id="verifyForm">
                    @csrf
                    <button type="submit" id="submitButton" 
                            class="btn-submit w-full text-[#16271d] py-3.5 rounded-xl font-semibold flex items-center justify-center gap-2 text-sm">
                        <i class="fas fa-paper-plane text-xs" id="btnIcon"></i>
                        <span id="buttonText">Kirim Ulang Email Verifikasi</span>
                        <div id="buttonLoader" class="hidden">
                            <div class="w-5 h-5 border-2 border-[#16271d]/20 border-t-[#16271d] rounded-full animate-spin"></div>
                        </div>
                    </button>
                </form>

                {{-- Logout --}}
                <div class="mt-8 border-t border-gray-100 pt-5 text-center">
                    <form method="POST" action="{{ route('student.logout') }}">
                        @csrf
                        <button type="submit" class="text-sm text-gray-500 hover:text-emerald-600 transition-colors inline-flex items-center gap-2 font-medium">
                            <i class="fas fa-sign-out-alt text-xs"></i>
                            Keluar
                        </button>
                    </form>
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
    const form = document.getElementById('verifyForm');
    const submitBtn = document.getElementById('submitButton');
    const btnText = document.getElementById('buttonText');
    const btnIcon = document.getElementById('btnIcon');
    const btnLoader = document.getElementById('buttonLoader');

    form.addEventListener('submit', function() {
        submitBtn.disabled = true;
        btnIcon.classList.add('hidden');
        btnLoader.classList.remove('hidden');
        btnText.textContent = 'Mengirim...';
    });
});
</script>
@stop

@endsection
