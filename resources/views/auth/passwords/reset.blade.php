@extends('layouts.app')

@section('title', 'Reset Password')

@section('styles')
<link rel="stylesheet" href="{{ asset('assets/css/reset-password.css') }}">
@stop

@section('content')
<div class="min-h-screen bg-gradient-to-br from-green-50 via-emerald-50 to-teal-50 flex items-center justify-center py-8 px-4">
    <!-- Background decoration -->
    <div class="absolute top-0 left-0 w-full h-full overflow-hidden pointer-events-none">
        <div class="absolute top-20 right-20 w-64 h-64 bg-green-200 rounded-full opacity-20 animate-pulse"></div>
        <div class="absolute bottom-20 left-20 w-80 h-80 bg-emerald-200 rounded-full opacity-20 animate-pulse" style="animation-delay: 1s;"></div>
    </div>

    <div class="max-w-md w-full relative z-10">
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden animate-fade-in">
            <!-- Header with Enhanced Design -->
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
                    <h1 class="text-3xl font-bold mb-2">Buat Password Baru</h1>
                    <p class="text-green-100">Silakan masukkan password baru Anda di bawah ini.</p>
                </div>
            </div>
            
            <div class="p-8">
                <form method="POST" action="{{ route('password.update') }}" id="resetPasswordForm">
                    @csrf
                    <input type="hidden" name="token" value="{{ $token }}">

                    <div class="space-y-6">
                        {{-- Email Field (Readonly) --}}
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Alamat Email</label>
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-envelope text-gray-400 group-focus-within:text-green-600 transition-colors"></i>
                                </div>
                                <input id="email" type="email" name="email" value="{{ $email ?? old('email') }}" required readonly
                                       class="w-full pl-10 pr-4 py-3 bg-gray-100 border border-gray-300 rounded-lg cursor-not-allowed @error('email') border-red-500 @enderror"
                                       placeholder="nama@email.com">
                            </div>
                            @error('email')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Password Field with Strength Indicator --}}
                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                                Password Baru <span class="text-red-500">*</span>
                            </label>
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-lock text-gray-400 group-focus-within:text-green-600 transition-colors"></i>
                                </div>
                                <input id="password" type="password" name="password" required minlength="8"
                                    autocomplete="new-password"
                                    class="w-full pl-10 pr-10 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-green-500 focus:ring-2 focus:ring-green-200 transition-all group-hover:border-gray-400 @error('password') border-red-500 @enderror"
                                    placeholder="Minimal 8 karakter">
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                    <button type="button" aria-label="Tampilkan atau sembunyikan password" onclick="togglePassword('password', this)" class="text-gray-400 hover:text-green-600 transition-colors">
                                        <i class="fas fa-eye" id="password-icon"></i>
                                    </button>
                                </div>
                            </div>
                            @error('password')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                            
                            <!-- Password Strength Indicator -->
                            <div class="password-strength mt-2">
                                <div class="flex items-center justify-between mb-1">
                                    <span class="text-xs text-gray-500">Kekuatan Password:</span>
                                    <span id="strength-text" class="text-xs font-medium"></span>
                                </div>
                                <div class="w-full bg-gray-200 rounded-full h-1.5">
                                    <div id="strength-bar" class="h-1.5 rounded-full transition-all duration-300" style="width: 0%"></div>
                                </div>
                            </div>
                        </div>

                        {{-- Password Confirmation Field --}}
                        <div>
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                                Konfirmasi Password Baru <span class="text-red-500">*</span>
                            </label>
                            <div class="relative group">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <i class="fas fa-lock text-gray-400 group-focus-within:text-green-600 transition-colors"></i>
                                </div>
                                <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password"
                                    class="w-full pl-10 pr-10 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-green-500 focus:ring-2 focus:ring-green-200 transition-all group-hover:border-gray-400"
                                    placeholder="Konfirmasi Password">
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                    <button type="button" aria-label="Tampilkan atau sembunyikan password" onclick="togglePassword('password_confirmation', this)" class="text-gray-400 hover:text-green-600 transition-colors">
                                        <i class="fas fa-eye" id="password_confirmation-icon"></i>
                                    </button>
                                </div>
                            </div>
                            <div id="password-match" class="text-xs mt-1"></div>
                        </div>
                    </div>

                    <!-- Password Requirements -->
                    <div class="bg-blue-50 p-4 rounded-lg mb-6">
                        <h3 class="text-sm font-medium text-blue-900 mb-2">Password harus memenuhi kriteria berikut:</h3>
                        <ul class="text-xs text-blue-800 space-y-1">
                            <li id="req-length" class="flex items-center">
                                <i class="fas fa-times-circle mr-2 text-red-500"></i>
                                Minimal 8 karakter
                            </li>
                            <li id="req-uppercase" class="flex items-center">
                                <i class="fas fa-times-circle mr-2 text-red-500"></i>
                                Setidaknya satu huruf besar
                            </li>
                            <li id="req-lowercase" class="flex items-center">
                                <i class="fas fa-times-circle mr-2 text-red-500"></i>
                                Setidaknya satu huruf kecil
                            </li>
                            <li id="req-number" class="flex items-center">
                                <i class="fas fa-times-circle mr-2 text-red-500"></i>
                                Setidaknya satu angka
                            </li>
                        </ul>
                    </div>

                    <div class="mt-8">
                        <button type="submit" id="submitButton" class="w-full bg-gradient-to-r from-green-600 to-emerald-600 text-white font-semibold py-3 px-6 rounded-lg hover:from-green-700 hover:to-emerald-700 transition-all duration-300 transform hover:-translate-y-0.5 shadow-md flex items-center justify-center">
                            <i class="fas fa-key mr-2"></i>
                            <span id="buttonText">Reset Password</span>
                            <div id="buttonLoader" class="hidden ml-2">
                                <div class="w-4 h-4 border-2 border-white/30 border-t-white rounded-full animate-spin"></div>
                            </div>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@section('scripts')
<script src="{{ asset('assets/js/reset-password.js') }}"></script>
@stop

@endsection