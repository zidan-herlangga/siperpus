@extends('layouts.app') 

@section('title', 'Verifikasi Email Anda') 

@section('content') 
<div class="min-h-screen bg-gradient-to-br from-green-100 via-emerald-50 to-yellow-50 flex items-center justify-center px-4 py-12">
    <div class="max-w-lg w-full">
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
            <div class="bg-gradient-to-r from-green-700 to-emerald-500 text-white p-8 text-center">
                <h1 class="text-3xl font-bold mb-2">Satu Langkah Lagi!</h1> 
                    <p class="text-green-100">Anda perlu memverifikasi alamat email Anda.</p>
                </div> 
                <div class="p-8 text-center">
                        <div class="w-20 h-20 bg-yellow-100 rounded-full mx-auto flex items-center justify-center mb-6">
                            <i class="fas fa-envelope-open-text text-yellow-500 text-4xl"></i>
                        </div>
                        <h2 class="font-semibold text-xl text-gray-800 mb-4">
                            Silakan Cek Kotak Masuk Email Anda
                        </h2>
                        <p class="text-gray-600 mb-6">
                            Tautan verifikasi telah dikirimkan ke alamat email Anda. Klik tautan tersebut untuk mengaktifkan akun Anda.
                        </p>
                        {{-- Pesan sukses setelah mengirim ulang --}}
                    @if (session('message'))
                        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 text-left" role="alert">
                            <p>{{ session('message') }}</p>
                        </div> 
                    @endif
                    <p class="text-gray-500 text-sm">Tidak menerima email?</p>
                    {{-- Tombol Kirim Ulang --}}
                    <form class="inline-block mt-2" method="POST" action="{{ route('verification.send') }}">
                        @csrf
                        <button type="submit" class="font-semibold text-green-600 hover:text-green-800 transition duration-300">
                            Klik di sini untuk mengirim ulang
                        </button>
                    </form>
                    <div class="mt-8 border-t pt-6">
                        <form action="{{ route('student.logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="text-sm text-gray-500 hover:text-red-600">
                                Logout
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div
 @endsection