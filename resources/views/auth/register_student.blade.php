@extends('layouts.app')

@section('title', 'Pendaftaran Siswa - Perpustakaan Sekolah')

@section('content')
    <div class="min-h-screen bg-gradient-to-br from-purple-50 to-blue-50 py-12">
        <div class="container mx-auto px-4 max-w-2xl">
            <!-- Header Card -->
            <div class="bg-white rounded-2xl shadow-xl overflow-hidden mb-8">
                <div class="gradient-bg text-white p-8 text-center">
                    <h1 class="text-3xl md:text-4xl font-bold mb-4">Pendaftaran Siswa Baru</h1>
                    <p class="text-white text-lg">Daftarkan diri Anda untuk mengakses layanan perpustakaan digital</p>
                </div>
            </div>

            <!-- Registration Form Card -->
            <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
                <form id="registrationForm" action="{{ route('student.register.store') }}" method="POST" class="p-8">
                    @csrf

                    <!-- Success Message -->
                    @if (session('status'))
                        <div id="successMessage"
                            class="bg-green-50 border border-green-200 rounded-xl p-4 mb-6 animate-fade-in">
                            <div class="flex items-center">
                                <i class="fas fa-check-circle text-green-600 text-xl mr-3"></i>
                                <div>
                                    <h4 class="font-semibold text-green-800">Pendaftaran Berhasil!</h4>
                                    <p class="text-green-600 text-sm">{{ session('status') }}</p>
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Error Messages -->
                    @if ($errors->any())
                        <div class="bg-red-50 border border-red-200 rounded-xl p-4 mb-6 animate-shake">
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

                    <!-- Personal Information -->
                    <div class="mb-8">
                        <h3 class="text-xl font-semibold text-gray-800 mb-4 flex items-center">
                            <i class="fas fa-user-circle mr-3 text-purple-600"></i>
                            Informasi Pribadi
                        </h3>
                        <div class="grid md:grid-cols-2 gap-6">
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                                    Nama Lengkap <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <input type="text" name="name" id="name" value="{{ old('name') }}" required
                                        class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg form-input focus:outline-none focus:border-green-500 transition"
                                        placeholder="Masukkan nama lengkap">
                                    <i class="fas fa-user absolute left-3 top-3.5 text-gray-400"></i>
                                </div>
                                <div class="text-xs text-gray-500 mt-1">Isi dengan nama lengkap sesuai ijazah</div>
                            </div>

                            <div>
                                <label for="nis" class="block text-sm font-medium text-gray-700 mb-2">
                                    NIS (Nomor Induk Siswa) <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <input type="text" name="nis" id="nis" value="{{ old('nis') }}" required
                                        class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg form-input focus:outline-none focus:border-green-500 transition"
                                        placeholder="Contoh: 20240001">
                                    <i class="fas fa-id-card absolute left-3 top-3.5 text-gray-400"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- School Information -->
                    <div class="mb-8">
                        <h3 class="text-xl font-semibold text-gray-800 mb-4 flex items-center">
                            <i class="fas fa-school mr-3 text-blue-600"></i>
                            Informasi Sekolah
                        </h3>
                        <div class="grid md:grid-cols-2 gap-6">
                            <div>
                                <label for="class" class="block text-sm font-medium text-gray-700 mb-2">
                                    Kelas <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <select name="class" id="class" required
                                        class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg form-input focus:outline-none focus:border-green-500 transition appearance-none">
                                        <option value="">Pilih Kelas</option>
                                        {{-- Akuntansi --}}
                                        <option value="X AK 1" {{ old('class') == 'X AK 1' ? 'selected' : '' }}>X AK 1
                                        </option>
                                        <option value="X AK 2" {{ old('class') == 'X AK 2' ? 'selected' : '' }}>X AK 2
                                        </option>
                                        <option value="XI AK 1" {{ old('class') == 'XI AK 1' ? 'selected' : '' }}>XI AK 1
                                        </option>
                                        <option value="XI AK 2" {{ old('class') == 'XI AK 2' ? 'selected' : '' }}>XI AK 2
                                        </option>
                                        <option value="XII AK 1" {{ old('class') == 'XII AK 1' ? 'selected' : '' }}>XII AK 1
                                        </option>
                                        <option value="XII AK 2" {{ old('class') == 'XII AK 2' ? 'selected' : '' }}>XII AK
                                            2</option>

                                        {{-- Manajemen Perkantoran --}}
                                        <option value="X MP 1" {{ old('class') == 'X MP 1' ? 'selected' : '' }}>X MP 1
                                        </option>
                                        <option value="XI MP 1" {{ old('class') == 'XI MP 1' ? 'selected' : '' }}>XI MP 1
                                        </option>
                                        <option value="XII MP 1" {{ old('class') == 'XII MP 1' ? 'selected' : '' }}>XII MP
                                            1</option>

                                        {{-- Teknik Kendaraan Ringan (TKR) --}}
                                        <option value="X TKR 1" {{ old('class') == 'X TKR 1' ? 'selected' : '' }}>X TKR 1
                                        </option>
                                        <option value="XI TKR 1" {{ old('class') == 'XI TKR 1' ? 'selected' : '' }}>XI TKR
                                            1</option>
                                        <option value="XII TKR 1" {{ old('class') == 'XII TKR 1' ? 'selected' : '' }}>XII
                                            TKR 1</option>

                                        {{-- Teknik Komputer dan Jaringan (TKJ) --}}
                                        <option value="X TKJ 1" {{ old('class') == 'X TKJ 1' ? 'selected' : '' }}>X TKJ 1
                                        </option>
                                        <option value="XI TKJ 1" {{ old('class') == 'XI TKJ 1' ? 'selected' : '' }}>XI TKJ
                                            1</option>
                                        <option value="XII TKJ 1" {{ old('class') == 'XII TKJ 1' ? 'selected' : '' }}>XII
                                            TKJ 1</option>
                                    </select>
                                    <i class="fas fa-graduation-cap absolute left-3 top-3.5 text-gray-400"></i>
                                </div>
                            </div>

                            <div>
                                <label for="contact" class="block text-sm font-medium text-gray-700 mb-2">
                                    Nomor Telepon/HP <span class="text-red-500">*</span>
                                </label>
                                <div class="relative">
                                    <input type="tel" name="contact" id="contact" value="{{ old('contact') }}"
                                        required
                                        class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg form-input focus:outline-none focus:border-green-500 transition"
                                        placeholder="Contoh: 081234567890">
                                    <i class="fas fa-phone absolute left-3 top-3.5 text-gray-400"></i>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Account Information -->
                    <div class="mb-8">
                        <h3 class="text-xl font-semibold text-gray-800 mb-4 flex items-center">
                            <i class="fas fa-envelope mr-3 text-green-600"></i>
                            Informasi Akun
                        </h3>
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                                Email <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <input type="email" name="email" id="email" value="{{ old('email') }}" required
                                    class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg form-input focus:outline-none focus:border-green-500 transition"
                                    placeholder="nama@email.com">
                                <i class="fas fa-envelope absolute left-3 top-3.5 text-gray-400"></i>
                            </div>
                            <div class="text-xs text-gray-500 mt-1">Email aktif untuk verifikasi dan notifikasi</div>
                        </div>
                    </div>

                    <div class="grid md:grid-cols-2 gap-6 mt-6">
                        <!-- Password -->
                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                                Password <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <input type="password" name="password" id="password" required
                                    class="w-full pl-10 pr-10 py-3 border rounded-lg focus:outline-none focus:border-green-500"
                                    placeholder="Minimal 8 karakter">
                                <i class="fas fa-lock absolute left-3 top-3.5 text-gray-400"></i>
                                <button type="button" onclick="togglePassword('password', this)"
                                    class="absolute right-3 top-3.5 text-gray-400 hover:text-green-500 focus:outline-none">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Konfirmasi Password -->
                        <div>
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                                Konfirmasi Password <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <input type="password" name="password_confirmation" id="password_confirmation" required
                                    class="w-full pl-10 pr-10 py-3 border rounded-lg focus:outline-none focus:border-green-500"
                                    placeholder="Ulangi password">
                                <i class="fas fa-lock absolute left-3 top-3.5 text-gray-400"></i>
                                <button type="button" onclick="togglePassword('password_confirmation', this)"
                                    class="absolute right-3 top-3.5 text-gray-400 hover:text-green-500 focus:outline-none">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                        </div>
                    </div>


                    <!-- Terms and Conditions -->
                    <div class="bg-gray-50 rounded-xl p-6 mb-8">
                        <div class="flex items-start">
                            <input type="checkbox" name="terms" id="terms" required
                                class="mt-1 mr-3 w-5 h-5 text-green-600 border-gray-300 rounded focus:ring-green-500">
                            <label for="terms" class="text-sm text-gray-700">
                                Saya menyetujui
                                <a href="#" class="text-green-600 hover:text-green-800 font-medium">Syarat dan
                                    Ketentuan</a>
                                serta
                                <a href="#" class="text-green-600 hover:text-green-800 font-medium">Kebijakan
                                    Privasi</a>
                                yang berlaku. Saya memahami bahwa akses penuh ke layanan perpustakaan hanya dapat dilakukan
                                setelah verifikasi email.
                            </label>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="flex flex-col sm:flex-row space-y-4 sm:space-y-0 sm:space-x-4">
                        <a href="{{ route('books.index') }}"
                            class="flex-1 px-6 py-4 border-2 border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition duration-300 font-semibold text-center flex items-center justify-center">
                            <i class="fas fa-arrow-left mr-2"></i>Kembali
                        </a>
                        <button type="submit" id="submitButton"
                            class="flex-1 px-6 py-4 text-white bg-green-700 rounded-lg transition duration-300 font-semibold transform hover:scale-105 shadow-lg flex items-center justify-center">
                            <i class="fas fa-user-plus mr-2"></i>Daftar Sekarang
                        </button>
                    </div>

                    <!-- Login Link -->
                    <div class="text-center pt-6 border-t border-gray-200 mt-6">
                        <p class="text-gray-600">
                            Sudah punya akun?
                            <a href="/login" class="font-semibold hover:border-b transition duration-300">Login di
                                sini</a>
                        </p>
                    </div>
                </form>
            </div>

            <!-- Information Box -->
            <div class="mt-8 bg-blue-50 border border-blue-200 rounded-2xl p-6">
                <h4 class="font-semibold text-blue-800 mb-3 flex items-center">
                    <i class="fas fa-info-circle mr-2"></i>
                    Informasi Penting
                </h4>
                <ul class="text-blue-700 space-y-2 text-sm">
                    <li class="flex items-center">
                        <i class="fas fa-check-circle mr-2 text-green-500"></i>
                        Pastikan data yang diisi sesuai dengan data sekolah
                    </li>
                    <li class="flex items-center">
                        <i class="fas fa-check-circle mr-2 text-green-500"></i>
                        Email akan digunakan untuk verifikasi dan notifikasi peminjaman
                    </li>
                    <li class="flex items-center">
                        <i class="fas fa-check-circle mr-2 text-green-500"></i>
                        Setelah pendaftaran, silakan cek email untuk verifikasi akun
                    </li>
                    <li class="flex items-center">
                        <i class="fas fa-check-circle mr-2 text-green-500"></i>
                        Akun yang sudah terverifikasi dapat langsung meminjam buku
                    </li>
                </ul>
            </div>
        </div>
    </div>
@endsection
