@extends('layouts.app')

@section('title', 'Lupa Password')

@section('styles')
<link rel="stylesheet" href="{{ asset('assets/css/email.css') }}">
@stop

@section('content')
<div class="min-h-screen bg-gradient-to-br from-green-50 via-emerald-50 to-teal-50 flex items-center justify-center px-4 py-8">
    <!-- Background decoration -->
    <div class="absolute top-0 left-0 w-full h-full overflow-hidden pointer-events-none">
        <div class="absolute top-20 right-20 w-64 h-64 bg-green-200 rounded-full opacity-20 animate-pulse"></div>
        <div class="absolute bottom-20 left-20 w-80 h-80 bg-emerald-200 rounded-full opacity-20 animate-pulse" style="animation-delay: 1s;"></div>
    </div>

    <div class="max-w-md w-full relative z-10">
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden animate-fade-in">
            {{-- Header with Enhanced Design --}}
            <div class="bg-gradient-to-r from-green-600 to-emerald-500 text-white text-center py-8 px-6 relative overflow-hidden">
                <!-- Animated background elements -->
                <div class="absolute top-0 left-0 w-full h-full">
                    <div class="absolute top-2 left-2 w-16 h-16 bg-white/10 rounded-full animate-pulse"></div>
                    <div class="absolute bottom-2 right-2 w-24 h-24 bg-white/10 rounded-full animate-pulse" style="animation-delay: 1s;"></div>
                </div>
                
                <div class="relative z-10">
                    <div class="w-20 h-20 bg-white/20 backdrop-blur rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-key text-3xl"></i>
                    </div>
                    <h1 class="text-3xl font-bold mb-2">Lupa Password?</h1>
                    <p class="text-green-100">Masukkan email Anda di bawah ini, dan kami akan mengirimkan link untuk mereset password Anda.</p>
                </div>
            </div>

            {{-- Form --}}
            <div class="p-8">
                {{-- Pesan Sukses (setelah link terkirim) --}}
                @if (session('status'))
                    <div class="bg-green-50 border-l-4 border-green-500 text-green-700 p-4 mb-6 text-sm rounded-lg animate-slide-down" role="alert">
                        <div class="flex items-center">
                            <i class="fas fa-check-circle mr-2"></i>
                            <span>{{ session('status') }}</span>
                        </div>
                    </div>
                @endif

                {{-- Pesan Error Validasi --}}
                @error('email')
                    <div class="bg-red-50 border-l-4 border-red-500 text-red-700 p-4 mb-6 text-sm rounded-lg animate-slide-down" role="alert">
                        <div class="flex items-center">
                            <i class="fas fa-exclamation-circle mr-2"></i>
                            <span>{{ $message }}</span>
                        </div>
                    </div>
                @enderror

                <form method="POST" action="{{ route('password.email') }}" id="forgotPasswordForm">
                    @csrf
                    <div class="mb-6">
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Alamat Email Terdaftar <span class="text-red-500">*</span></label>
                        <div class="relative group">
                            <div class="absolute inset-y-0 left-0 pl-3 pb-5 flex items-center pointer-events-none">
                                <i class="fas fa-envelope text-gray-400 group-focus-within:text-green-600 transition-colors"></i>
                            </div>
                            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="email"
                                   class="w-full pl-10 pr-4 py-3 bg-white border border-gray-300 rounded-lg focus:outline-none focus:border-green-500 focus:ring-2 focus:ring-green-200 transition-all group-hover:border-gray-400 @error('email') border-red-500 @enderror"
                                   placeholder="nama@email.com">
                            <div class="validation-message"></div>
                        </div>
                    </div>

                    <div class="mt-6">
                        <button type="submit" id="submitButton" class="w-full bg-gradient-to-r from-green-600 to-emerald-600 text-white font-semibold py-3 px-6 rounded-lg hover:from-green-700 hover:to-emerald-700 transition-all duration-300 transform hover:-translate-y-0.5 shadow-md flex items-center justify-center">
                            <i class="fas fa-paper-plane mr-2"></i>
                            <span id="buttonText">Kirim Link Reset Password</span>
                            <div id="buttonLoader" class="hidden ml-2">
                                <div class="w-4 h-4 border-2 border-white/30 border-t-white rounded-full animate-spin"></div>
                            </div>
                        </button>
                    </div>
                </form>

                <!-- Additional Help Section -->
                <div class="mt-6 p-4 bg-blue-50 rounded-lg">
                    <h3 class="font-medium text-blue-900 mb-2 flex items-center">
                        <i class="fas fa-question-circle mr-2"></i>
                        Butuh bantuan?
                    </h3>
                    <ul class="text-sm text-blue-800 space-y-1">
                        <li>• Pastikan email yang Anda masukkan terdaftar di sistem</li>
                        <li>• Periksa folder spam atau junk jika tidak menerima email</li>
                        <li>• Link reset password akan berlaku selama 60 menit</li>
                    </ul>
                </div>

                {{-- Link Kembali ke Login --}}
                <div class="text-center mt-6 pt-4 border-t border-gray-200">
                    <a href="{{ route('student.login.form') }}" class="text-sm text-gray-600 hover:text-green-600 transition-colors flex items-center justify-center">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Kembali ke Halaman Login
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

@section('scripts')
<script src=" {{ asset('assets/js/email.js') }} "></script>
@stop

@endsection