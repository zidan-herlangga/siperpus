@extends('layouts.app')

@section('title', 'Edit Profile Siswa - ' . config('app.name'))

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

            

            {{-- Card utama --}}
            <div class="bg-white border border-gray-200 rounded-xl shadow-xl overflow-hidden animate-fade-in">
                {{-- Header --}}
                <div class="bg-gradient-to-r from-green-600 to-emerald-600 text-white text-center py-8 px-6 relative overflow-hidden">
                    <div class="absolute top-0 left-0 w-full h-full">
                        <div class="absolute top-2 left-2 w-16 h-16 bg-white/10 rounded-full animate-pulse"></div>
                        <div class="absolute bottom-2 right-2 w-24 h-24 bg-white/10 rounded-full animate-pulse" style="animation-delay: 1s;"></div>
                    </div>
                    
                    <div class="relative z-10">
                        <div class="w-20 h-20 bg-white/20 backdrop-blur rounded-full flex items-center justify-center mx-auto mb-4">
                            <i class="fas fa-user-edit text-3xl"></i>
                        </div>
                        <h1 class="text-2xl font-bold mb-1">Edit Profile Siswa</h1>
                        <p class="text-green-100 text-sm">Perbarui informasi akun Anda.</p>
                    </div>
                </div>

                {{-- Form --}}
                <div class="p-6">
                    <form id="editProfileForm" action="{{ route('student.update') }}" method="POST" enctype="multipart/form-data"
                        class="space-y-6">
                        @csrf

                        {{-- Pesan Sukses --}}
                        @if (session('success'))
                            <div class="bg-green-50 border border-green-200 text-green-700 text-sm p-3 rounded-lg flex items-start animate-slide-down">
                                <i class="fas fa-check-circle mr-2 mt-0.5"></i>
                                <span>{{ session('success') }}</span>
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
                        <div class="form-section">
                            <h3 class="text-lg font-semibold text-gray-800 mb-3 flex items-center gap-2">
                                <i class="fas fa-user-circle text-green-600 text-sm"></i> Informasi Pribadi
                            </h3>
                            <div class="grid md:grid-cols-2 gap-4">
                                {{-- Nama --}}
                                <div class="form-group">
                                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap</label>
                                    <div class="relative group">
                                        <div class="absolute inset-y-0 left-0 pl-3 pb-0 flex items-center pointer-events-none">
                                            <i class="fas fa-user text-gray-400 group-focus-within:text-green-600 transition-colors text-sm"></i>
                                        </div>
                                        <input type="text" name="name" id="name" value="{{ old('name', auth()->user()->name) }}"
                                            required
                                            class="w-full pl-10 pr-3 py-3 bg-white border border-gray-300 rounded-lg focus:outline-none focus:border-green-500 focus:ring-2 focus:ring-green-200 transition-all group-hover:border-gray-400 @error('name') border-red-500 @enderror"
                                            placeholder="Masukkan nama lengkap">
                                    </div>
                                    @error('name')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                              {{-- NIS --}}
                              <div class="form-group">
                                  <label for="nis" class="block text-sm font-medium text-gray-700 mb-2">NIS</label>
                                  <div class="relative group">
                                      <div class="absolute inset-y-0 left-0 pl-3 pb-0 flex items-center pointer-events-none">
                                          <i class="fas fa-id-card text-gray-400 group-focus-within:text-green-600 transition-colors text-sm"></i>
                                      </div>
                                      <input type="text" name="nis" id="nis" value="{{ old('nis', auth()->user()->nis) }}"
                                          required
                                          disabled class="w-full pl-10 pr-3 py-3 bg-white border border-gray-300 rounded-lg focus:outline-none focus:border-green-500 focus:ring-2 focus:ring-green-200 transition-all group-hover:border-gray-400 @error('nis') border-red-500 @enderror"
                                          placeholder="Contoh: 20250001">
                                  </div>
                                  @error('nis')
                                      <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                  @enderror
                              </div>
                            </div>
                        </div>

                        {{-- Informasi Sekolah & Kontak --}}
                        <div class="form-section">
                            <h3 class="text-lg font-semibold text-gray-800 mb-3 flex items-center gap-2">
                                <i class="fas fa-school text-emerald-600 text-sm"></i> Informasi Sekolah & Kontak
                            </h3>
                            <div class="grid md:grid-cols-2 gap-4">
                                {{-- Kelas --}}
                                <div class="form-group">
                                    <label for="class" class="block text-sm font-medium text-gray-700 mb-2">Kelas</label>
                                    <div class="relative group">
                                        <div class="absolute inset-y-0 left-0 pl-3 pb-0 flex items-center pointer-events-none">
                                            <i class="fas fa-graduation-cap text-gray-400 group-focus-within:text-green-600 transition-colors text-sm"></i>
                                        </div>
                                        <input type="text" name="class" id="class" value="{{ old('class', auth()->user()->class) }}" required class="w-full pl-10 pr-3 py-3 bg-white border border-gray-300 rounded-lg focus:outline-none focus:border-green-500 focus:ring-2 focus:ring-green-200 transition-all group-hover:border-gray-400 @error('class') border-red-500 @enderror" placeholder="Contoh: XII Akuntansi">
                                    </div>
                                    @error('class')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                {{-- No HP --}}
                                <div class="form-group">
                                    <label for="contact" class="block text-sm font-medium text-gray-700 mb-2">Nomor Telepon</label>
                                    <div class="relative group">
                                        <div class="absolute inset-y-0 left-0 pl-3 pb-0 flex items-center pointer-events-none">
                                            <i class="fas fa-phone text-gray-400 group-focus-within:text-green-600 transition-colors text-sm"></i>
                                        </div>
                                        <input type="tel" name="contact" id="contact" value="{{ old('contact', auth()->user()->contact) }}" class="w-full pl-10 pr-3 py-3 bg-white border border-gray-300 rounded-lg focus:outline-none focus:border-green-500 focus:ring-2 focus:ring-green-200 transition-all group-hover:border-gray-400 @error('contact') border-red-500 @enderror" placeholder="081234567890">
                                    </div>
                                    @error('contact')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        {{-- Informasi Akun --}}
                        <div class="form-section">
                            <h3 class="text-lg font-semibold text-gray-800 mb-3 flex items-center gap-2">
                                <i class="fas fa-lock text-teal-600 text-sm"></i> Informasi Akun
                            </h3>
                            <div class="form-group">
                                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                                <div class="relative group">
                                    <div class="absolute inset-y-0 left-0 pl-3 pb-0 flex items-center pointer-events-none">
                                        <i class="fas fa-envelope text-gray-400 group-focus-within:text-green-600 transition-colors text-sm"></i>
                                    </div>
                                    <input type="email" name="email" id="email" value="{{ old('email', auth()->user()->email) }}"
                                        required
                                        class="w-full pl-10 pr-3 py-3 bg-white border border-gray-300 rounded-lg focus:outline-none focus:border-green-500 focus:ring-2 focus:ring-green-200 transition-all group-hover:border-gray-400 @error('email') border-red-500 @enderror"
                                        placeholder="nama@email.com">
                                </div>
                                @error('email')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        {{-- Upload Avatar --}}
                        <div class="form-section">
                            <h3 class="text-lg font-semibold text-gray-800 mb-3 flex items-center gap-2">
                                <i class="fas fa-image text-purple-600 text-sm"></i> Upload Avatar
                            </h3>
                            <div class="form-group">
                                <label for="avatar" class="block text-sm font-medium text-gray-700 mb-2">Pilih Foto Profil</label>
                                <input type="file" name="avatar" id="avatar"
                                    class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4
                                    file:rounded-lg file:border-0
                                    file:text-sm file:font-semibold
                                    file:bg-green-600 file:text-white
                                    hover:file:bg-green-700
                                    transition-all"
                                    accept="image/*">
                                @error('avatar')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        {{-- Tombol --}}
                        <div class="flex flex-col md:flex-row md:space-x-4 gap-4 mt-6">
                            <a href="{{ route('student.dashboard') }}" class="w-full bg-gray-200 text-gray-700 py-3 rounded-lg font-medium flex items-center justify-center gap-2 hover:bg-gray-300 transition-all duration-300">
                                <i class="fas fa-arrow-left"></i> 
                                <span id="buttonText">Batal Perubahan</span>
                            </a>
                            <button type="submit" id="submitButton"
                                class="w-full bg-gradient-to-r from-green-600 to-emerald-600 text-white py-3 rounded-lg font-medium flex items-center justify-center gap-2 hover:from-green-700 hover:to-emerald-700 transition-all duration-300 transform hover:-translate-y-0.5 shadow-md">
                                <i class="fas fa-save text-sm"></i> 
                                <span id="buttonText">Simpan Perubahan</span>
                                <div id="buttonLoader" class="hidden">
                                    <div class="w-4 h-4 border-2 border-white/30 border-t-white rounded-full animate-spin"></div>
                                </div>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script src="{{ asset('assets/js/register-student.js') }}"></script>
@stop