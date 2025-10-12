@extends('layouts.app')

@section('title', 'Pendaftaran - Peminjaman Buku Online')

@section('content')
    <div class="min-h-screen bg-gradient-to-br from-green-50 to-teal-50 py-12">
        <div class="container mx-auto px-4 max-w-2xl">
            <div class="bg-white rounded-2xl shadow-xl overflow-hidden mb-8">
                <div class="gradient-bg text-white p-8 text-center">
                    <h1 class="text-3xl md:text-4xl font-bold mb-4">Pendaftaran Siswa Baru</h1>
                    <p class="text-green-100 text-lg">Buat akun untuk mengakses layanan perpustakaan digital.</p>
                </div>
            </div>

            <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
                <form id="registrationForm" action="{{ route('student.register.store') }}" method="POST" class="p-8">
                    @csrf

                    {{-- Pesan Sukses --}}
                    @if (session('status'))
                        <div class="bg-green-50 border border-green-200 rounded-xl p-4 mb-6 text-center">
                            <p class="text-green-700 text-sm">{{ session('status') }}</p>
                        </div>
                    @endif

                    {{-- Pesan Error --}}
                    @if ($errors->any())
                        <div class="bg-red-50 border border-red-200 rounded-xl p-4 mb-6">
                            <div class="flex">
                                <i class="fas fa-exclamation-circle text-red-600 text-xl mr-3 mt-1"></i>
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

                    <div class="mb-8">
                        <h3 class="text-xl font-semibold text-gray-800 mb-4 flex items-center"><i class="fas fa-user-circle mr-3 text-green-600"></i>Informasi Pribadi</h3>
                        <div class="grid md:grid-cols-2 gap-6">
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap <span class="text-red-500">*</span></label>
                                <div class="relative">
                                    <input type="text" name="name" id="name" value="{{ old('name') }}" required autocomplete="name"
                                        class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-green-500 transition"
                                        placeholder="Masukkan nama lengkap">
                                    <i class="fas fa-user absolute left-3 top-3.5 text-gray-400"></i>
                                </div>
                            </div>
                            <div>
                                <label for="nis" class="block text-sm font-medium text-gray-700 mb-2">NIS <span class="text-red-500">*</span></label>
                                <div class="relative">
                                    <input type="text" name="nis" id="nis" value="{{ old('nis') }}" required
                                        class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-green-500 transition"
                                        placeholder="Contoh: 20250001">
                                    <i class="fas fa-id-card absolute left-3 top-3.5 text-gray-400"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mb-8">
                        <h3 class="text-xl font-semibold text-gray-800 mb-4 flex items-center"><i class="fas fa-school mr-3 text-teal-600"></i>Informasi Sekolah</h3>
                        <div class="grid md:grid-cols-2 gap-6">
                            <div>
                                <label for="class" class="block text-sm font-medium text-gray-700 mb-2">Kelas <span class="text-red-500">*</span></label>
                                <div class="relative">
                                    <select name="class" id="class" required class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-green-500 transition appearance-none">
                                        @php
                                            $grades = ['X', 'XI', 'XII'];
                                            $majors = ['AK', 'MP', 'TKJ', 'TKR'];
                                        @endphp
                                        <option value="">Pilih Kelas</option>
                                        @foreach ($grades as $grade)
                                            @foreach ($majors as $major)
                                                @for ($i = 1; $i <= 6; $i++)
                                                    @php
                                                        $classOption = $grade . ' ' . $major . ' ' . $i;
                                                    @endphp
                                                    <option value="{{ $classOption }}" {{ old('class') == $classOption ? 'selected' : '' }}>{{ $classOption }}</option>
                                                @endfor
                                            @endforeach
                                        @endforeach
                                    </select>
                                    <i class="fas fa-graduation-cap absolute left-3 top-3.5 text-gray-400"></i>
                                </div>
                            </div>
                            <div>
                                <label for="contact" class="block text-sm font-medium text-gray-700 mb-2">Nomor Telepon/HP <span class="text-red-500">*</span></label>
                                <div class="relative">
                                    <input type="tel" name="contact" id="contact" value="{{ old('contact') }}" required autocomplete="tel"
                                        class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-green-500 transition"
                                        placeholder="Contoh: 081234567890">
                                    <i class="fas fa-phone absolute left-3 top-3.5 text-gray-400"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mb-8">
                        <h3 class="text-xl font-semibold text-gray-800 mb-4 flex items-center"><i class="fas fa-shield-alt mr-3 text-emerald-600"></i>Informasi Akun</h3>
                        <div class="space-y-6">
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email <span class="text-red-500">*</span></label>
                                <div class="relative">
                                    <input type="email" name="email" id="email" value="{{ old('email') }}" required autocomplete="email"
                                        class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-green-500 transition"
                                        placeholder="nama@email.com">
                                    <i class="fas fa-envelope absolute left-3 top-3.5 text-gray-400"></i>
                                </div>
                            </div>
                            <div class="grid md:grid-cols-2 gap-6">
                                <div>
                                    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Password <span class="text-red-500">*</span></label>
                                    <div class="relative">
                                        <input type="password" name="password" id="password" required autocomplete="new-password"
                                            class="w-full pl-10 pr-10 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-green-500"
                                            placeholder="Minimal 8 karakter">
                                        <i class="fas fa-lock absolute left-3 top-3.5 text-gray-400"></i>
                                        <button type="button" onclick="togglePassword('password', this)" class="absolute right-3 top-3.5 text-gray-400 hover:text-green-500 focus:outline-none"><i class="fas fa-eye"></i></button>
                                    </div>
                                </div>
                                <div>
                                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">Konfirmasi Password <span class="text-red-500">*</span></label>
                                    <div class="relative">
                                        <input type="password" name="password_confirmation" id="password_confirmation" required autocomplete="new-password"
                                            class="w-full pl-10 pr-10 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-green-500"
                                            placeholder="Ulangi password">
                                        <i class="fas fa-lock absolute left-3 top-3.5 text-gray-400"></i>
                                        <button type="button" onclick="togglePassword('password_confirmation', this)" class="absolute right-3 top-3.5 text-gray-400 hover:text-green-500 focus:outline-none"><i class="fas fa-eye"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-8">
                        <button type="submit" id="submitButton"
                            class="w-full px-6 py-4 text-white bg-green-700 rounded-lg transition duration-300 font-semibold transform hover:scale-105 shadow-lg flex items-center justify-center">
                            <i class="fas fa-user-plus mr-2"></i>Buat Akun Saya
                        </button>
                    </div>

                    <div class="text-center pt-6 border-t border-gray-200 mt-6">
                        <p class="text-gray-600">
                            Sudah punya akun?
                            <a href="{{ route('student.login.form') }}" class="font-semibold text-green-600 hover:text-green-700 transition">Login di sini</a>
                        </p>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection