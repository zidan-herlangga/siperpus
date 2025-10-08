@extends('layouts.app')

@section('title', 'Login Siswa - Perpustakaan Sekolah')

@section('content')
    <div class="min-h-screen bg-gradient-to-br from-cyan-50 to-blue-50 py-12">
        <div class="container mx-auto px-4 max-w-2xl">
            <div class="bg-white rounded-2xl shadow-xl overflow-hidden mb-8">
                <div class="gradient-bg text-white p-8 text-center">
                    <h1 class="text-3xl md:text-4xl font-bold mb-4">Login Akun Siswa</h1>
                    <p class="text-white text-lg">Masukkan email dan password Anda untuk masuk.</p>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
                <form id="loginForm" action="{{ route('student.login.auth') }}" method="POST" class="p-8">
                    @csrf

                    {{-- Pesan Sukses atau Status --}}
                    @if (session('status'))
                        <div class="bg-green-50 border border-green-200 rounded-xl p-4 mb-6">
                            <p class="text-green-700 text-sm">{{ session('status') }}</p>
                        </div>
                    @endif

                    {{-- Pesan Error Validasi --}}
                    @if ($errors->any())
                        <div class="bg-red-50 border border-red-200 rounded-xl p-4 mb-6">
                            <div class="flex items-center">
                                <i class="fas fa-exclamation-circle text-red-600 text-xl mr-3"></i>
                                <div>
                                    <h4 class="font-semibold text-red-800">Terjadi Kesalahan</h4>
                                    <ul class="text-red-600 text-sm list-disc list-inside">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    @endif

                    <div class="space-y-6">
                        {{-- Input Email --}}
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email Terdaftar <span
                                    class="text-red-500">*</span></label>
                            <div class="relative">
                                <input type="email" name="email" id="email" value="{{ old('email') }}" required
                                    class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-green-500 transition"
                                    placeholder="Masukkan email yang terdaftar">
                                <i class="fas fa-envelope absolute left-3 top-3.5 text-gray-400"></i>
                            </div>
                        </div>

                        {{-- Input Password --}}
                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Password <span
                                    class="text-red-500">*</span></label>
                            <div class="relative">
                                <input type="password" name="password" id="password" required
                                    class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-green-500 transition"
                                    placeholder="Masukkan password Anda">
                                <i class="fas fa-lock absolute left-3 top-3.5 text-gray-400"></i>
                                <button type="button" onclick="togglePassword('password', this)"
                                    class="absolute right-3 top-3.5 text-gray-400 hover:text-green-500 focus:outline-none">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="mt-8 flex flex-col sm:flex-row space-y-4 sm:space-y-0 sm:space-x-4">
                        <a href="{{ route('books.index') }}"
                            class="flex-1 px-6 py-4 border-2 border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition duration-300 font-semibold text-center flex items-center justify-center">
                            <i class="fas fa-arrow-left mr-2"></i>Kembali
                        </a>
                        <button type="submit" id="submitButton"
                            class="flex-1 px-6 py-4 text-white bg-green-700 rounded-lg transition duration-300 font-semibold transform hover:scale-105 shadow-lg flex items-center justify-center">
                            <i class="fas fa-sign-in-alt mr-2"></i>Login
                        </button>
                    </div>

                    <div class="text-center pt-6 border-t border-gray-200 mt-6">
                        <p class="text-gray-600">
                            Belum punya akun?
                            <a href="{{ route('student.register.form') }}"
                                class="font-semibold  hover:border-b-2 transition">Daftar di sini</a>
                        </p>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
