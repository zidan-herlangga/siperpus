@extends('layouts.app')

@section('title', 'Daftar Siswa - ' . config('app.name'))

@section('styles')
<link rel="stylesheet" href="{{ asset('assets/css/register-student.css') }}">
@stop

@section('content')
    <div class="min-h-screen bg-gradient-to-br from-green-50 to-emerald-50 flex items-center justify-center px-4 py-8">
        <div class="w-full max-w-3xl">
            <!-- Background decoration -->
            <div class="absolute top-0 left-0 w-full h-full overflow-hidden pointer-events-none">
                <div class="absolute top-20 right-20 w-64 h-64 bg-green-200 rounded-full opacity-20 animate-pulse"></div>
                <div class="absolute bottom-20 left-20 w-80 h-80 bg-emerald-200 rounded-full opacity-20 animate-pulse" style="animation-delay: 1s;"></div>
            </div>

            {{-- Card utama dengan animasi --}}
            <div class="bg-white border border-gray-200 rounded-xl shadow-xl overflow-hidden animate-fade-in">

                {{-- Header dengan Gradien --}}
                <div class="bg-gradient-to-r from-green-600 to-emerald-600 text-white text-center py-8 px-6 relative overflow-hidden">
                    <!-- Animated background elements -->
                    <div class="absolute top-0 left-0 w-full h-full">
                        <div class="absolute top-2 left-2 w-16 h-16 bg-white/10 rounded-full animate-pulse"></div>
                        <div class="absolute bottom-2 right-2 w-24 h-24 bg-white/10 rounded-full animate-pulse" style="animation-delay: 1s;"></div>
                    </div>
                    
                    <div class="relative z-10">
                        <div class="w-20 h-20 bg-white/20 backdrop-blur rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-user-plus text-3xl"></i>
                        </div>
                        <h1 class="text-2xl font-bold mb-1">Pendaftaran Siswa Baru</h1>
                        <p class="text-green-100 text-sm">Buat akun untuk mengakses layanan perpustakaan digital.</p>
                    </div>
                </div>

                {{-- Progress Steps --}}
                <div class="bg-gray-50 px-6 py-4 border-b border-gray-200">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <div class="step-indicator active" data-step="1">
                                <span class="step-number">1</span>
                                <span class="step-label">Informasi Pribadi</span>
                            </div>
                            <div class="step-connector"></div>
                            <div class="step-indicator" data-step="2">
                                <span class="step-number">2</span>
                                <span class="step-label">Informasi Sekolah</span>
                            </div>
                            <div class="step-connector"></div>
                            <div class="step-indicator" data-step="3">
                                <span class="step-number">3</span>
                                <span class="step-label">Informasi Akun</span>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Form --}}
                <div class="p-6">
                    <form id="registrationForm" action="{{ route('student.register.store') }}" method="POST"
                        class="space-y-6">
                        @csrf

                        {{-- Pesan Status --}}
                        @if (session('status'))
                            <div class="bg-green-50 border border-green-200 text-green-700 text-sm p-3 rounded-lg flex items-start animate-slide-down">
                                <i class="fas fa-check-circle mr-2 mt-0.5"></i>
                                <span>{{ session('status') }}</span>
                            </div>
                        @endif

                        {{-- Pesan Error --}}
                        @if ($errors->any())
                            <div class="bg-red-50 border border-red-200 text-red-600 text-sm p-3 rounded-lg flex items-start animate-slide-down">
                                <i class="fas fa-exclamation-circle mr-2 mt-0.5"></i>
                                <div>
                                    <p class="font-medium mb-1">Terjadi kesalahan:</p>
                                    <ul class="list-disc list-inside space-y-1">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        @endif

                        {{-- Informasi Pribadi --}}
                        <div class="form-section" data-section="1">
                            <h3 class="text-lg font-semibold text-gray-800 mb-3 flex items-center gap-2">
                                <i class="fas fa-user-circle text-green-600 text-sm"></i> Informasi Pribadi
                            </h3>
                            <div class="grid md:grid-cols-2 gap-4">
                                {{-- Nama --}}
                                <div class="form-group">
                                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap</label>
                                    <div class="relative group">
                                        <div class="absolute inset-y-0 left-0 pl-3 pb-5 flex items-center pointer-events-none">
                                            <i class="fas fa-user text-gray-400 group-focus-within:text-green-600 transition-colors text-sm"></i>
                                        </div>
                                        <input type="text" name="name" id="name" value="{{ old('name') }}"
                                            required
                                            class="w-full pl-10 pr-3 py-3 bg-white border border-gray-300 rounded-lg focus:outline-none focus:border-green-500 focus:ring-2 focus:ring-green-200 transition-all group-hover:border-gray-400"
                                            placeholder="Masukkan nama lengkap">
                                        <div class="validation-message"></div>
                                    </div>
                                </div>

                                {{-- NIS --}}
                                <div class="form-group">
                                    <label for="nis" class="block text-sm font-medium text-gray-700 mb-2">NIS <span class="text-gray-500">(opsional)</span></label>
                                    <div class="relative group">
                                        <div class="absolute inset-y-0 left-0 pl-3 pb-0 flex items-center pointer-events-none">
                                            <i class="fas fa-id-card text-gray-400 group-focus-within:text-green-600 transition-colors text-sm"></i>
                                        </div>
                                        <input type="text" name="nis" id="nis" value="{{ old('nis') }}"
                                            class="w-full pl-10 pr-3 py-3 bg-white border border-gray-300 rounded-lg focus:outline-none focus:border-green-500 focus:ring-2 focus:ring-green-200 transition-all group-hover:border-gray-400"
                                            placeholder="Contoh: 20250001">
                                        <!-- <div class="validation-message"></div> -->
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Informasi Sekolah --}}
                        <div class="form-section" data-section="2">
                            <h3 class="text-lg font-semibold text-gray-800 mb-3 flex items-center gap-2">
                                <i class="fas fa-school text-emerald-600 text-sm"></i> Informasi Sekolah
                            </h3>
                            <div class="grid md:grid-cols-2 gap-4">
                                {{-- Kelas --}}
                                <div class="form-group">
                                    <label for="class" class="block text-sm font-medium text-gray-700 mb-2">Kelas</label>
                                    <div class="relative group">
                                        <div class="absolute inset-y-0 left-0 pl-3 pb-5 flex items-center pointer-events-none">
                                            <i class="fas fa-graduation-cap text-gray-400 group-focus-within:text-green-600 transition-colors text-sm"></i>
                                        </div>
                                        <select name="class" id="class" required
                                            class="w-full pl-10 pr-8 py-3 bg-white border border-gray-300 rounded-lg focus:outline-none focus:border-green-500 focus:ring-2 focus:ring-green-200 appearance-none transition-all group-hover:border-gray-400">
                                            @php
                                                $grades = ['X', 'XI', 'XII'];
                                                $majors = [
                                                    'Akuntansi',
                                                    'Manajemen Perkantoran',
                                                    'Teknik Komputer Jaringan',
                                                    'Teknik Kendaraan Ringan'
                                                ];
                                            @endphp

                                            <option value="">Pilih Kelas</option>
                                            @foreach ($majors as $major)
                                                @foreach ($grades as $grade)
                                                    @php
                                                        $classOption = "$grade $major";
                                                    @endphp
                                                    <option value="{{ $classOption }}" {{ old('class') == $classOption ? 'selected' : '' }}>
                                                        {{ $classOption }}
                                                    </option>
                                                @endforeach
                                            @endforeach
                                        </select>
                                        <div class="absolute inset-y-0 right-0 pb-5 flex items-center pr-3 pointer-events-none">
                                            <i class="fas fa-chevron-down text-gray-400"></i>
                                        </div>
                                        <div class="validation-message"></div>
                                    </div>
                                </div>

                                {{-- No HP --}}
                                <div class="form-group">
                                    <label for="contact" class="block text-sm font-medium text-gray-700 mb-2">Nomor Telepon</label>
                                    <div class="relative group">
                                        <div class="absolute inset-y-0 left-0 pl-3 pb-5 flex items-center pointer-events-none">
                                            <i class="fas fa-phone text-gray-400 group-focus-within:text-green-600 transition-colors text-sm"></i>
                                        </div>
                                        <input type="tel" name="contact" id="contact" value="{{ old('contact') }}"
                                            required
                                            class="w-full pl-10 pr-3 py-3 bg-white border border-gray-300 rounded-lg focus:outline-none focus:border-green-500 focus:ring-2 focus:ring-green-200 transition-all group-hover:border-gray-400"
                                            placeholder="081234567890">
                                        <div class="validation-message"></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Informasi Akun --}}
                        <div class="form-section" data-section="3">
                            <h3 class="text-lg font-semibold text-gray-800 mb-3 flex items-center gap-2">
                                <i class="fas fa-lock text-teal-600 text-sm"></i> Informasi Akun
                            </h3>
                            <div class="space-y-4">
                                {{-- Email --}}
                                <div class="form-group">
                                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                                    <div class="relative group">
                                        <div class="absolute inset-y-0 left-0 pl-3 pb-5 flex items-center pointer-events-none">
                                            <i class="fas fa-envelope text-gray-400 group-focus-within:text-green-600 transition-colors text-sm"></i>
                                        </div>
                                        <input type="email" name="email" id="email" value="{{ old('email') }}"
                                            required
                                            class="w-full pl-10 pr-3 py-3 bg-white border border-gray-300 rounded-lg focus:outline-none focus:border-green-500 focus:ring-2 focus:ring-green-200 transition-all group-hover:border-gray-400"
                                            placeholder="nama@email.com">
                                        <div class="validation-message"></div>
                                    </div>
                                </div>

                                {{-- Password --}}
                                <div class="grid md:grid-cols-2 gap-4">
                                    <div class="form-group">
                                        <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                                        <div class="relative group">
                                            <div class="absolute inset-y-0 left-0 pl-3 pb-5 flex items-center pointer-events-none">
                                                <i class="fas fa-lock text-gray-400 group-focus-within:text-green-600 transition-colors text-sm"></i>
                                            </div>
                                            <input type="password" name="password" id="password" required
                                                class="w-full pl-10 pr-10 py-3 bg-white border border-gray-300 rounded-lg focus:outline-none focus:border-green-500 focus:ring-2 focus:ring-green-200 transition-all group-hover:border-gray-400"
                                                placeholder="Minimal 8 karakter">
                                            <button type="button" onclick="togglePassword('password', this)"
                                                class="absolute inset-y-0 right-0 pr-3 pb-5 flex items-center text-gray-400 hover:text-green-600 transition-colors">
                                                <i class="fas fa-eye text-sm" id="password-icon"></i>
                                            </button>
                                            <div class="validation-message"></div>
                                        </div>
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
                                    <div class="form-group">
                                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">Konfirmasi Password</label>
                                        <div class="relative group">
                                            <div class="absolute inset-y-0 left-0 pl-3 pb-5 flex items-center pointer-events-none">
                                                <i class="fas fa-lock text-gray-400 group-focus-within:text-green-600 transition-colors text-sm"></i>
                                            </div>
                                            <input type="password" name="password_confirmation" id="password_confirmation"
                                                required
                                                class="w-full pl-10 pr-10 py-3 bg-white border border-gray-300 rounded-lg focus:outline-none focus:border-green-500 focus:ring-2 focus:ring-green-200 transition-all group-hover:border-gray-400"
                                                placeholder="Ulangi password">
                                            <button type="button" onclick="togglePassword('password_confirmation', this)"
                                                class="absolute inset-y-0 right-0 pr-3 pb-5 flex items-center text-gray-400 hover:text-green-600 transition-colors">
                                                <i class="fas fa-eye text-sm" id="password_confirmation-icon"></i>
                                            </button>
                                            <div class="validation-message"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Tombol dengan Loading State --}}
                        <div class="pt-2">
                            <button type="submit" id="submitButton"
                                class="w-full bg-gradient-to-r from-green-600 to-emerald-600 text-white py-3 rounded-lg font-medium flex items-center justify-center gap-2 hover:from-green-700 hover:to-emerald-700 transition-all duration-300 transform hover:-translate-y-0.5 shadow-md">
                                <i class="fas fa-user-plus text-sm"></i> 
                                <span id="buttonText">Buat Akun Saya</span>
                                <div id="buttonLoader" class="hidden">
                                    <div class="w-4 h-4 border-2 border-white/30 border-t-white rounded-full animate-spin"></div>
                                </div>
                            </button>
                        </div>

                        {{-- Link ke login --}}
                        <div class="text-center text-sm mt-6 border-t border-gray-200 pt-5">
                            <p class="text-gray-600">
                                Sudah punya akun?
                                <a href="{{ route('student.login.form') }}"
                                    class="font-medium text-green-600 hover:text-green-700 transition-colors">
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

    <!-- Success/Error Notification -->
    <div id="notification" class="fixed top-4 right-4 max-w-sm transform translate-x-full transition-transform duration-300 z-50">
        <div class="bg-white rounded-lg shadow-lg p-4 flex items-center">
            <div id="notificationIcon" class="mr-3"></div>
            <div>
                <p id="notificationTitle" class="font-semibold text-gray-800"></p>
                <p id="notificationMessage" class="text-sm text-gray-600"></p>
            </div>
        </div>
    </div>

    @section('scripts')
    <script src="{{ asset('assets/js/register-student.js') }}"></script>
    @stop
@endsection