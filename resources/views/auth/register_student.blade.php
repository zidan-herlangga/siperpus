@extends('layouts.app')

@section('title', 'Daftar Siswa - ' . config('app.name'))

@section('content')
    <div class="min-h-screen bg-gray-50 flex items-center justify-center px-4 py-8">
        <div class="w-full max-w-3xl">
            {{-- Card utama --}}
            <div class="bg-white border border-gray-200 rounded-lg shadow-md">

                {{-- Header --}}
                <div class="bg-green-600 text-white text-center py-6 px-6 rounded-t-lg">
                    <h1 class="text-2xl font-bold mb-1">Pendaftaran Siswa Baru</h1>
                    <p class="text-green-100 text-sm">Buat akun untuk mengakses layanan perpustakaan digital.</p>
                </div>

                {{-- Form --}}
                <div class="p-6">
                    <form id="registrationForm" action="{{ route('student.register.store') }}" method="POST"
                        class="space-y-6">
                        @csrf

                        {{-- Pesan Status --}}
                        @if (session('status'))
                            <div class="bg-green-50 border border-green-200 text-green-700 text-sm p-3 rounded text-center">
                                {{ session('status') }}
                            </div>
                        @endif

                        {{-- Pesan Error --}}
                        @if ($errors->any())
                            <div class="bg-red-50 border border-red-200 text-red-600 text-sm p-3 rounded">
                                <ul class="list-disc list-inside space-y-1">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        {{-- Informasi Pribadi --}}
                        <div>
                            <h3 class="text-lg font-semibold text-gray-800 mb-3 flex items-center gap-2">
                                <i class="fas fa-user-circle text-green-600 text-sm"></i> Informasi Pribadi
                            </h3>
                            <div class="grid md:grid-cols-2 gap-4">
                                {{-- Nama --}}
                                <div>
                                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap</label>
                                    <div class="relative">
                                        <input type="text" name="name" id="name" value="{{ old('name') }}"
                                            required
                                            class="w-full pl-9 pr-3 py-2.5 bg-white border border-gray-300 rounded focus:outline-none focus:border-green-500 focus:ring-1 focus:ring-green-500"
                                            placeholder="Masukkan nama lengkap">
                                        <i class="fas fa-user absolute left-3 top-3 text-gray-400 text-sm"></i>
                                    </div>
                                </div>

                                {{-- NIS --}}
                                <div>
                                    <label for="nis" class="block text-sm font-medium text-gray-700 mb-2">NIS</label>
                                    <div class="relative">
                                        <input type="text" name="nis" id="nis" value="{{ old('nis') }}"
                                            required
                                            class="w-full pl-9 pr-3 py-2.5 bg-white border border-gray-300 rounded focus:outline-none focus:border-green-500 focus:ring-1 focus:ring-green-500"
                                            placeholder="Contoh: 20250001">
                                        <i class="fas fa-id-card absolute left-3 top-3 text-gray-400 text-sm"></i>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Informasi Sekolah --}}
                        <div>
                            <h3 class="text-lg font-semibold text-gray-800 mb-3 flex items-center gap-2">
                                <i class="fas fa-school text-emerald-600 text-sm"></i> Informasi Sekolah
                            </h3>
                            <div class="grid md:grid-cols-2 gap-4">
                                {{-- Kelas --}}
                                <div>
                                    <label for="class" class="block text-sm font-medium text-gray-700 mb-2">Kelas</label>
                                    <div class="relative">
                                        <select name="class" id="class" required
                                            class="w-full pl-9 pr-3 py-2.5 bg-white border border-gray-300 rounded focus:outline-none focus:border-green-500 focus:ring-1 focus:ring-green-500 appearance-none">
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
                                        <i class="fas fa-graduation-cap absolute left-3 top-3 text-gray-400 text-sm"></i>
                                    </div>
                                </div>

                                {{-- No HP --}}
                                <div>
                                    <label for="contact" class="block text-sm font-medium text-gray-700 mb-2">Nomor Telepon</label>
                                    <div class="relative">
                                        <input type="tel" name="contact" id="contact" value="{{ old('contact') }}"
                                            required
                                            class="w-full pl-9 pr-3 py-2.5 bg-white border border-gray-300 rounded focus:outline-none focus:border-green-500 focus:ring-1 focus:ring-green-500"
                                            placeholder="081234567890">
                                        <i class="fas fa-phone absolute left-3 top-3 text-gray-400 text-sm"></i>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Informasi Akun --}}
                        <div>
                            <h3 class="text-lg font-semibold text-gray-800 mb-3 flex items-center gap-2">
                                <i class="fas fa-lock text-teal-600 text-sm"></i> Informasi Akun
                            </h3>
                            <div class="space-y-4">
                                {{-- Email --}}
                                <div>
                                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                                    <div class="relative">
                                        <input type="email" name="email" id="email" value="{{ old('email') }}"
                                            required
                                            class="w-full pl-9 pr-3 py-2.5 bg-white border border-gray-300 rounded focus:outline-none focus:border-green-500 focus:ring-1 focus:ring-green-500"
                                            placeholder="nama@email.com">
                                        <i class="fas fa-envelope absolute left-3 top-3 text-gray-400 text-sm"></i>
                                    </div>
                                </div>

                                {{-- Password --}}
                                <div class="grid md:grid-cols-2 gap-4">
                                    <div>
                                        <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                                        <div class="relative">
                                            <input type="password" name="password" id="password" required
                                                class="w-full pl-9 pr-9 py-2.5 bg-white border border-gray-300 rounded focus:outline-none focus:border-green-500 focus:ring-1 focus:ring-green-500"
                                                placeholder="Minimal 8 karakter">
                                            <i class="fas fa-lock absolute left-3 top-3 text-gray-400 text-sm"></i>
                                            <button type="button" onclick="togglePassword('password', this)"
                                                class="absolute right-3 top-3 text-gray-400 hover:text-green-600">
                                                <i class="fas fa-eye text-sm"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <div>
                                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">Konfirmasi Password</label>
                                        <div class="relative">
                                            <input type="password" name="password_confirmation" id="password_confirmation"
                                                required
                                                class="w-full pl-9 pr-9 py-2.5 bg-white border border-gray-300 rounded focus:outline-none focus:border-green-500 focus:ring-1 focus:ring-green-500"
                                                placeholder="Ulangi password">
                                            <i class="fas fa-lock absolute left-3 top-3 text-gray-400 text-sm"></i>
                                            <button type="button" onclick="togglePassword('password_confirmation', this)"
                                                class="absolute right-3 top-3 text-gray-400 hover:text-green-600">
                                                <i class="fas fa-eye text-sm"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Tombol --}}
                        <div class="pt-2">
                            <button type="submit"
                                class="w-full bg-green-600 text-white py-2.5 rounded font-medium flex items-center justify-center gap-2 hover:bg-green-700 transition-colors">
                                <i class="fas fa-user-plus text-sm"></i> Buat Akun Saya
                            </button>
                        </div>

                        {{-- Link ke login --}}
                        <div class="text-center text-sm mt-6 border-t border-gray-200 pt-5">
                            <p class="text-gray-600">
                                Sudah punya akun?
                                <a href="{{ route('student.login.form') }}"
                                    class="font-medium text-green-600 hover:text-green-700">
                                    Login di sini
                                </a>
                            </p>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Footer --}}
            <p class="text-center text-gray-500 text-xs mt-5">
                &copy; {{ date('Y') }} Perpustakaan SMK Karya Guna 2 Bekasi
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