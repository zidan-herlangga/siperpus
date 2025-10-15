@extends('layouts.app')

@section('title', 'Login Siswa - Peminjaman Buku Online')

@section('content')
    <div
        class="min-h-screen bg-gradient-to-br from-green-100 via-emerald-50 to-yellow-50 flex items-center justify-center px-4 py-12">
        <div class="w-full max-w-md">
            {{-- Card utama --}}
            <div
                class="bg-white/80 backdrop-blur-xl border border-white/40 shadow-2xl rounded-2xl overflow-hidden transform transition duration-300 hover:shadow-green-200/70">

                {{-- Header Card --}}
                <div class="bg-gradient-to-r from-green-700 to-emerald-500 text-white text-center py-8 px-6">
                    <h1 class="text-3xl font-bold mb-2 tracking-wide">Login Siswa</h1>
                    <p class="text-green-100 text-sm">Masuk untuk mengakses sistem peminjaman buku</p>
                </div>

                {{-- Form --}}
                <div class="p-8">
                    <form id="loginForm" action="{{ route('student.login.auth') }}" method="POST" class="space-y-6">
                        @csrf

                        {{-- Pesan Status --}}
                        @if (session('status'))
                            <div class="bg-green-50 border border-green-200 text-green-700 text-sm p-3 rounded-lg">
                                {{ session('status') }}
                            </div>
                        @endif

                        {{-- Pesan Error --}}
                        @if ($errors->any())
                            <div class="bg-red-50 border border-red-200 text-red-600 text-sm p-4 rounded-lg">
                                <ul class="list-disc list-inside space-y-1">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        {{-- Email --}}
                        <div>
                            <label for="email" class="block text-sm font-semibold text-gray-700 mb-2">Email
                                Terdaftar</label>
                            <div class="relative">
                                <input type="email" name="email" id="email" value="{{ old('email') }}" required
                                    class="w-full pl-10 pr-4 py-3 bg-white/70 border border-gray-300 rounded-lg focus:outline-none focus:border-green-600 focus:ring-2 focus:ring-green-100 transition"
                                    placeholder="nama@email.com">
                                <i class="fas fa-envelope absolute left-3 top-3.5 text-gray-400"></i>
                            </div>
                        </div>

                        {{-- Password --}}
                        <div>
                            <label for="password" class="block text-sm font-semibold text-gray-700 mb-2">Password</label>
                            <div class="relative">
                                <input type="password" name="password" id="password" required
                                    class="w-full pl-10 pr-10 py-3 bg-white/70 border border-gray-300 rounded-lg focus:outline-none focus:border-green-600 focus:ring-2 focus:ring-green-100 transition"
                                    placeholder="Masukkan password Anda">
                                <i class="fas fa-lock absolute left-3 top-3.5 text-gray-400"></i>
                                <button type="button" onclick="togglePassword('password', this)"
                                    class="absolute right-3 top-3.5 text-gray-400 hover:text-green-500 focus:outline-none">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                        </div>

                        {{-- Remember Me --}}
                        <div class="flex justify-between items-center text-sm">
                            <label class="flex items-center space-x-2">
                                <input type="checkbox" name="remember"
                                    class="text-green-600 border-gray-300 rounded focus:ring-green-500">
                                <span class="text-gray-700">Ingat Saya</span>
                            </label>
                            {{-- <a href="#" class="text-green-700 hover:text-green-900 font-medium">Lupa Password?</a> --}}
                        </div>

                        {{-- Tombol Login --}}
                        <button type="submit" id="submitButton"
                            class="w-full bg-gradient-to-r from-green-700 to-emerald-600 text-white py-3 rounded-lg font-semibold flex items-center justify-center gap-2 transform transition hover:scale-[1.02] hover:shadow-lg hover:from-green-600 hover:to-emerald-500">
                            <i class="fas fa-sign-in-alt"></i> Masuk Sekarang
                        </button>

                        {{-- Link ke Register --}}
                        <div class="text-center text-sm mt-8 border-t border-gray-200 pt-6">
                            <p class="text-gray-600">
                                Belum punya akun?
                                <a href="{{ route('student.register.form') }}"
                                    class="font-semibold text-green-600 hover:text-green-700">Daftar Sekarang</a>
                            </p>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Footer kecil --}}
            <p class="text-center text-gray-500 text-xs mt-6">
                &copy; {{ date('Y') }} Perpustakaan SMK Karya Guna 2 Bekasi. All rights reserved.
            </p>
        </div>
    </div>
@endsection
