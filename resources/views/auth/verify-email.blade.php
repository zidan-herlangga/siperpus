@extends('layouts.app')

@section('title', 'Verifikasi Email Anda')

@section('content')
    <div
        class="min-h-screen bg-gradient-to-br from-green-50 via-emerald-50 to-teal-50 flex items-center justify-center py-12 px-4">
        <div class="max-w-lg w-full">
            <div class="bg-white/90 backdrop-blur-sm rounded-2xl shadow-2xl overflow-hidden border border-green-100">

                {{-- Header --}}
                <div class="bg-gradient-to-r from-green-600 to-emerald-500 text-white p-8 text-center">
                    <h1 class="text-3xl font-bold mb-2 tracking-tight">Satu Langkah Lagi!</h1>
                    <p class="text-green-100 text-sm md:text-base">Verifikasi alamat email Anda untuk mengaktifkan akun.</p>
                </div>

                {{-- Konten --}}
                <div class="p-8 text-center">
                    <div
                        class="w-20 h-20 bg-green-50 border border-green-100 rounded-full mx-auto flex items-center justify-center mb-6">
                        <i class="fas fa-envelope-open-text text-green-500 text-4xl"></i>
                    </div>

                    <h2 class="font-semibold text-xl text-gray-800 mb-3">Cek Kotak Masuk Email Anda</h2>
                    <p class="text-gray-600 mb-6 leading-relaxed">
                        Kami telah mengirimkan tautan verifikasi ke email Anda. Klik tautan tersebut untuk menyelesaikan
                        pendaftaran.
                    </p>

                    {{-- Pesan sukses setelah kirim ulang --}}
                    @if (session('message'))
                        <div class="bg-green-50 border border-green-200 rounded-xl p-4 mb-6 text-left shadow-sm">
                            <p class="text-green-700 text-sm flex items-center gap-2">
                                <i class="fas fa-check-circle text-green-500"></i>
                                {{ session('message') }}
                            </p>
                        </div>
                    @endif

                    {{-- Opsi kirim ulang --}}
                    <p class="text-gray-500 text-sm mb-2">Tidak menerima email?</p>
                    <form method="POST" action="{{ route('verification.send') }}" class="inline-block">
                        @csrf
                        <button type="submit"
                            class="font-semibold text-green-600 hover:text-green-800 transition duration-300 underline underline-offset-4">
                            Kirim ulang tautan verifikasi
                        </button>
                    </form>

                    {{-- Garis pemisah --}}
                    <div class="mt-8 border-t border-gray-100 pt-6">
                        <form action="{{ route('student.logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="text-sm text-gray-400 hover:text-red-500 transition duration-300">
                                <i class="fas fa-sign-out-alt mr-1"></i> Logout
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
