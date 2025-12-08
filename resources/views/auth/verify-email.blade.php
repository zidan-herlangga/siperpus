@extends('layouts.app') 

@section('title', 'Verifikasi Email Anda')

@section('styles')
<link rel="stylesheet" href="{{ asset('assets/css/verify-email.css') }}">
@stop

@section('content') 
<div class="min-h-screen bg-gradient-to-br from-green-50 via-emerald-50 to-teal-50 flex items-center justify-center px-4 py-12">
    <!-- Background decoration -->
    <div class="absolute top-0 left-0 w-full h-full overflow-hidden pointer-events-none">
        <div class="absolute -top-40 -right-40 w-80 h-80 bg-green-200 rounded-full opacity-20 animate-pulse"></div>
        <div class="absolute -bottom-40 -left-40 w-80 h-80 bg-emerald-200 rounded-full opacity-20 animate-pulse" style="animation-delay: 1s;"></div>
        <div class="absolute top-1/2 left-1/4 w-40 h-40 bg-teal-200 rounded-full opacity-20 animate-pulse" style="animation-delay: 2s;"></div>
    </div>

    <div class="max-w-lg w-full relative z-10">
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden animate-fade-in">
            <!-- Header with enhanced design -->
            <div class="bg-gradient-to-r from-green-600 to-emerald-500 text-white p-8 text-center relative overflow-hidden">
                <!-- Animated background elements -->
                <div class="absolute top-0 left-0 w-full h-full">
                    <div class="absolute top-2 left-2 w-16 h-16 bg-white/10 rounded-full animate-pulse"></div>
                    <div class="absolute bottom-2 right-2 w-24 h-24 bg-white/10 rounded-full animate-pulse" style="animation-delay: 1s;"></div>
                </div>
                
                <div class="relative z-10">
                    <div class="w-20 h-20 bg-white/20 backdrop-blur rounded-full flex items-center justify-center mx-auto mb-4">
                        <i class="fas fa-envelope-open-text text-3xl"></i>
                    </div>
                    <h1 class="text-3xl font-bold mb-2">Satu Langkah Lagi!</h1> 
                    <p class="text-green-100">Anda perlu memverifikasi alamat email Anda.</p>
                </div>
            </div> 
            
            <div class="p-8 text-center">
                <!-- Email icon with enhanced design -->
                <div class="relative mb-6">
                    <div class="w-24 h-24 bg-gradient-to-br from-yellow-100 to-amber-100 rounded-full mx-auto flex items-center justify-center shadow-md animate-bounce-slow">
                        <i class="fas fa-envelope-open-text text-yellow-500 text-4xl"></i>
                    </div>
                    <!-- Email sent indicator -->
                    <div class="absolute -top-2 -right-2 w-8 h-8 bg-green-500 rounded-full flex items-center justify-center animate-ping-slow">
                        <i class="fas fa-check text-white text-sm"></i>
                    </div>
                </div>
                
                <h2 class="font-semibold text-xl text-gray-800 mb-4">
                    Silakan Cek Kotak Masuk Email Anda
                </h2>
                <p class="text-gray-600 mb-6">
                    Tautan verifikasi telah dikirimkan ke alamat email Anda. Klik tautan tersebut untuk mengaktifkan akun Anda.
                </p>
                
                {{-- Pesan sukses setelah mengirim ulang --}}
                @if (session('message'))
                    <div class="bg-green-50 border-l-4 border-green-500 text-green-700 p-4 mb-6 text-left rounded-lg animate-slide-down" role="alert">
                        <div class="flex items-center">
                            <i class="fas fa-check-circle mr-2"></i>
                            <p>{{ session('message') }}</p>
                        </div>
                    </div> 
                @endif
                
                <!-- Email info card -->
                <div class="bg-gray-50 rounded-lg p-4 mb-6 text-left">
                    <div class="flex items-center">
                        <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center mr-3">
                            <i class="fas fa-info text-green-600"></i>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Email dikirim ke:</p>
                            <p class="font-semibold text-gray-800">{{ Auth::user()->email }}</p>
                        </div>
                    </div>
                </div>
                
                <p class="text-gray-500 text-sm mb-2">Tidak menerima email?</p>
                
                {{-- Tombol Kirim Ulang dengan Loading State --}}
                <form class="inline-block mt-2" method="POST" action="{{ route('verification.send') }}">
                    @csrf
                    <button type="submit" id="resendButton" class="font-semibold text-green-600 hover:text-green-800 transition duration-300 flex items-center mx-auto">
                        <i class="fas fa-redo mr-2"></i>
                        <span id="buttonText">Klik di sini untuk mengirim ulang</span>
                        <div id="buttonLoader" class="hidden ml-2">
                            <div class="w-4 h-4 border-2 border-green-600/30 border-t-green-600 rounded-full animate-spin"></div>
                        </div>
                    </button>
                </form>
                
                <!-- Additional help section -->
                <div class="mt-8 p-4 bg-blue-50 rounded-lg">
                    <h3 class="font-medium text-blue-900 mb-2 flex items-center">
                        <i class="fas fa-question-circle mr-2"></i>
                        Masih tidak menerima email?
                    </h3>
                    <ul class="text-sm text-blue-800 text-left space-y-1">
                        <li>• Periksa folder spam atau junk</li>
                        <li>• Pastikan alamat email Anda benar</li>
                        <li>• Tunggu beberapa saat sebelum mencoba lagi</li>
                    </ul>
                </div>
                
                <div class="mt-8 border-t pt-6">
                    <form action="{{ route('student.logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="text-sm text-gray-500 hover:text-red-600 transition-colors flex items-center mx-auto">
                            <i class="fas fa-sign-out-alt mr-2"></i>
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@section('scripts')
<script src="{{ asset('assets/js/verify-email.js') }}"></script>
@stop

@endsection