@extends('layouts.app')

@section('title', 'Lupa Password')

@section('content')
<div class="min-h-screen bg-gray-50 flex items-center justify-center px-4 py-8">
    <div class="max-w-md w-full">
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
            {{-- Header --}}
            <div class="bg-green-600 text-white text-center py-6 px-6 rounded-t-lg">
                <h1 class="text-3xl font-bold mb-2">Lupa Password?</h1>
                <p class="text-green-100">Masukkan email Anda di bawah ini, dan kami akan mengirimkan link untuk mereset password Anda.</p>
            </div>

            {{-- Form --}}
            <div class="p-8">
                {{-- Pesan Sukses (setelah link terkirim) --}}
                @if (session('status'))
                    <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 text-sm" role="alert">
                        {{ session('status') }}
                    </div>
                @endif

                {{-- Pesan Error Validasi --}}
                @error('email')
                    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 text-sm" role="alert">
                        {{ $message }}
                    </div>
                @enderror

                <form method="POST" action="{{ route('student.password.email') }}">
                    @csrf
                    <div class="mb-6">
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Alamat Email Terdaftar <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="email"
                                   class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-green-500 transition @error('email') border-red-500 @enderror"
                                   placeholder="nama@email.com">
                            <i class="fas fa-envelope absolute left-3 top-3.5 text-gray-400"></i>
                        </div>
                    </div>

                    <div class="mt-6">
                        <button type="submit" class="w-full bg-green-600  text-white font-semibold py-3 px-6 rounded-lg hover:opacity-90 transition duration-300 flex items-center justify-center">
                            <i class="fas fa-paper-plane mr-2"></i>Kirim Link Reset Password
                        </button>
                    </div>
                </form>

                {{-- Link Kembali ke Login --}}
                <div class="text-center mt-6 pt-4 border-t border-gray-200">
                    <a href="{{ route('student.login.form') }}" class="text-sm text-gray-600 hover:text-green-600 transition">
                        Kembali ke Halaman Login
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection