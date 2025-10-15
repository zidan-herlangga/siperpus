@extends('layouts.app')

@section('title', 'Pendaftaran Siswa - Peminjaman Buku Online')

@section('content')
    <div
        class="min-h-screen bg-gradient-to-br from-green-100 via-emerald-50 to-yellow-50 flex items-center justify-center px-4 py-12">
        <div class="w-full max-w-3xl">
            {{-- Card utama --}}
            <div
                class="bg-white/80 backdrop-blur-xl border border-white/40 shadow-2xl rounded-2xl overflow-hidden transform transition duration-300 hover:shadow-green-200/70">

                {{-- Header --}}
                <div class="bg-gradient-to-r from-green-700 to-emerald-500 text-white text-center py-8 px-6">
                    <h1 class="text-3xl font-bold mb-2 tracking-wide">Pendaftaran Siswa Baru</h1>
                    <p class="text-green-100 text-sm">Buat akun untuk mengakses layanan perpustakaan digital.</p>
                </div>

                {{-- Form --}}
                <div class="p-8">
                    <form id="registrationForm" action="{{ route('student.register.store') }}" method="POST"
                        class="space-y-10">
                        @csrf

                        {{-- Pesan Status --}}
                        @if (session('status'))
                            <div
                                class="bg-green-50 border border-green-200 text-green-700 text-sm p-3 rounded-lg text-center">
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

                        {{-- Informasi Pribadi --}}
                        <div>
                            <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center gap-2">
                                <i class="fas fa-user-circle text-green-600"></i> Informasi Pribadi
                            </h3>
                            <div class="grid md:grid-cols-2 gap-6">
                                {{-- Nama --}}
                                <div>
                                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Nama
                                        Lengkap</label>
                                    <div class="relative">
                                        <input type="text" name="name" id="name" value="{{ old('name') }}"
                                            required
                                            class="w-full pl-10 pr-4 py-3 bg-white/70 border border-gray-300 rounded-lg focus:outline-none focus:border-green-600 focus:ring-2 focus:ring-green-100 transition"
                                            placeholder="Masukkan nama lengkap">
                                        <i class="fas fa-user absolute left-3 top-3.5 text-gray-400"></i>
                                    </div>
                                </div>

                                {{-- NIS --}}
                                <div>
                                    <label for="nis" class="block text-sm font-medium text-gray-700 mb-2">NIS</label>
                                    <div class="relative">
                                        <input type="text" name="nis" id="nis" value="{{ old('nis') }}"
                                            required
                                            class="w-full pl-10 pr-4 py-3 bg-white/70 border border-gray-300 rounded-lg focus:outline-none focus:border-green-600 focus:ring-2 focus:ring-green-100 transition"
                                            placeholder="Contoh: 20250001">
                                        <i class="fas fa-id-card absolute left-3 top-3.5 text-gray-400"></i>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Informasi Sekolah --}}
                        <div>
                            <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center gap-2">
                                <i class="fas fa-school text-emerald-600"></i> Informasi Sekolah
                            </h3>
                            <div class="grid md:grid-cols-2 gap-6">
                                {{-- Kelas --}}
                                <div>
                                    <label for="class" class="block text-sm font-medium text-gray-700 mb-2">Kelas</label>
                                    <div class="relative">
                                        <select name="class" id="class" required
                                            class="w-full pl-10 pr-4 py-3 bg-white/70 border border-gray-300 rounded-lg focus:outline-none focus:border-green-600 focus:ring-2 focus:ring-green-100 transition appearance-none">
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
                                                        <option value="{{ $classOption }}"
                                                            {{ old('class') == $classOption ? 'selected' : '' }}>
                                                            {{ $classOption }}
                                                        </option>
                                                    @endfor
                                                @endforeach
                                            @endforeach
                                        </select>
                                        <i class="fas fa-graduation-cap absolute left-3 top-3.5 text-gray-400"></i>
                                    </div>
                                </div>

                                {{-- No HP --}}
                                <div>
                                    <label for="contact" class="block text-sm font-medium text-gray-700 mb-2">Nomor
                                        Telepon</label>
                                    <div class="relative">
                                        <input type="tel" name="contact" id="contact" value="{{ old('contact') }}"
                                            required
                                            class="w-full pl-10 pr-4 py-3 bg-white/70 border border-gray-300 rounded-lg focus:outline-none focus:border-green-600 focus:ring-2 focus:ring-green-100 transition"
                                            placeholder="081234567890">
                                        <i class="fas fa-phone absolute left-3 top-3.5 text-gray-400"></i>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Informasi Akun --}}
                        <div>
                            <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center gap-2">
                                <i class="fas fa-lock text-teal-600"></i> Informasi Akun
                            </h3>
                            <div class="space-y-6">
                                {{-- Email --}}
                                <div>
                                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                                    <div class="relative">
                                        <input type="email" name="email" id="email" value="{{ old('email') }}"
                                            required
                                            class="w-full pl-10 pr-4 py-3 bg-white/70 border border-gray-300 rounded-lg focus:outline-none focus:border-green-600 focus:ring-2 focus:ring-green-100 transition"
                                            placeholder="nama@email.com">
                                        <i class="fas fa-envelope absolute left-3 top-3.5 text-gray-400"></i>
                                    </div>
                                </div>

                                {{-- Password --}}
                                <div class="grid md:grid-cols-2 gap-6">
                                    <div>
                                        <label for="password"
                                            class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                                        <div class="relative">
                                            <input type="password" name="password" id="password" required
                                                class="w-full pl-10 pr-10 py-3 bg-white/70 border border-gray-300 rounded-lg focus:outline-none focus:border-green-600 focus:ring-2 focus:ring-green-100 transition"
                                                placeholder="Minimal 8 karakter">
                                            <i class="fas fa-lock absolute left-3 top-3.5 text-gray-400"></i>
                                            <button type="button" onclick="togglePassword('password', this)"
                                                class="absolute right-3 top-3.5 text-gray-400 hover:text-green-500 focus:outline-none">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div>
                                        <label for="password_confirmation"
                                            class="block text-sm font-medium text-gray-700 mb-2">Konfirmasi Password</label>
                                        <div class="relative">
                                            <input type="password" name="password_confirmation" id="password_confirmation"
                                                required
                                                class="w-full pl-10 pr-10 py-3 bg-white/70 border border-gray-300 rounded-lg focus:outline-none focus:border-green-600 focus:ring-2 focus:ring-green-100 transition"
                                                placeholder="Ulangi password">
                                            <i class="fas fa-lock absolute left-3 top-3.5 text-gray-400"></i>
                                            <button type="button" onclick="togglePassword('password_confirmation', this)"
                                                class="absolute right-3 top-3.5 text-gray-400 hover:text-green-500 focus:outline-none">
                                                <i class="fas fa-eye"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Tombol --}}
                        <div class="pt-2">
                            <button type="submit"
                                class="w-full bg-gradient-to-r from-green-700 to-emerald-600 text-white py-3 rounded-lg font-semibold flex items-center justify-center gap-2 transform transition hover:scale-[1.02] hover:shadow-lg hover:from-green-600 hover:to-emerald-500">
                                <i class="fas fa-user-plus"></i> Buat Akun Saya
                            </button>
                        </div>

                        {{-- Link ke login --}}
                        <div class="text-center text-sm mt-8 border-t border-gray-200 pt-6">
                            <p class="text-gray-600">
                                Sudah punya akun?
                                <a href="{{ route('student.login.form') }}"
                                    class="font-semibold text-green-600 hover:text-green-700 transition">
                                    Login di sini
                                </a>
                            </p>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Footer --}}
            <p class="text-center text-gray-500 text-xs mt-6">
                &copy; {{ date('Y') }} Perpustakaan SMK Karya Guna 2 Bekasi. All rights reserved.
            </p>
        </div>
    </div>

    {{-- Script show/hide password --}}
    <script>
        function togglePassword(id, btn) {
            const input = document.getElementById(id);
            const icon = btn.querySelector('i');
            const isHidden = input.type === 'password';
            input.type = isHidden ? 'text' : 'password';
            icon.classList.toggle('fa-eye');
            icon.classList.toggle('fa-eye-slash');
        }
    </script>
@endsection
