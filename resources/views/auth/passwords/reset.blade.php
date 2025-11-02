@extends('layouts.app')
@section('title', 'Reset Password')
@section('content')
<div class="min-h-screen bg-gray-50 flex items-center justify-center py-8 px-4">
    <div class="max-w-md w-full">
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
            <div class="bg-green-600 text-white text-center py-6 px-6 rounded-t-lg">
                <h1 class="text-3xl font-bold mb-2">Buat Password Baru</h1>
                <p class="text-green-100">Silakan masukkan password baru Anda di bawah ini.</p>
            </div>
            <div class="p-8">
                <form method="POST" action="{{ route('student.password.update') }}">
                    @csrf
                    <input type="hidden" name="token" value="{{ $token }}">

                    <div class="space-y-6">
                        {{-- PERBAIKAN: AKTIFKAN KEMBALI INPUT EMAIL (buat readonly) --}}
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Alamat Email</label>
                            <div class="relative">
                                <input id="email" type="email" name="email" value="{{ $email ?? old('email') }}" required readonly
                                       class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg bg-gray-100 cursor-not-allowed @error('email') border-red-500 @enderror"
                                       placeholder="nama@email.com">
                                <i class="fas fa-envelope absolute left-3 top-3.5 text-gray-400"></i>
                            </div>
                            @error('email')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        {{-- AKHIR PERBAIKAN --}}

                        {{-- Input Password Baru --}}
                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                                Password Baru <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 flex items-center pl-3">
                                    <i class="fas fa-lock text-gray-400"></i>
                                </div>
                                <input id="password" type="password" name="password" required minlength="8"
                                    autocomplete="new-password"
                                    class="w-full pl-10 pr-10 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-green-500 @error('password') border-red-500 @enderror"
                                    placeholder="Minimal 8 karakter">
                                <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                                    <button type="button" aria-label="Tampilkan atau sembunyikan password" onclick="togglePassword('password', this)" class="text-gray-400 hover:text-green-500 focus:outline-none">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                            </div>
                            @error('password')
                                <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Input Konfirmasi Password --}}
                        <div>
                            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">
                                Konfirmasi Password Baru <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 flex items-center pl-3">
                                    <i class="fas fa-lock text-gray-400"></i>
                                </div>
                                <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password"
                                    class="w-full pl-10 pr-10 py-3 border border-gray-300 rounded-lg focus:outline-none focus:border-green-500"
                                    placeholder="Konfirmasi Password">
                                <div class="absolute inset-y-0 right-0 flex items-center pr-3">
                                    <button type="button" aria-label="Tampilkan atau sembunyikan password" onclick="togglePassword('password_confirmation', this)" class="text-gray-400 hover:text-green-500 focus:outline-none">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-8">
                        <button type="submit" class="w-full bg-green-600 text-white font-semibold py-3 px-6 rounded-lg hover:opacity-90 transition duration-300 flex items-center justify-center">
                            <i class="fas fa-key mr-2"></i>Reset Password
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

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