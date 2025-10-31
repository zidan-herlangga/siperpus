@extends('layouts.app')

@section('title', 'Login Siswa - ' . config('app.name'))

@section('content')
    <div class="min-h-screen bg-gray-50 flex items-center justify-center px-4 py-8">
        <div class="w-full max-w-md">
            {{-- Card utama --}}
            <div class="bg-white border border-gray-200 rounded-lg shadow-md">

                {{-- Header Card --}}
                <div class="bg-green-600 text-white text-center py-6 px-6 rounded-t-lg">
                    <h1 class="text-2xl font-bold mb-1">Login Siswa</h1>
                    <p class="text-green-100 text-sm">Masuk untuk mengakses sistem peminjaman buku</p>
                </div>

                {{-- Form --}}
                <div class="p-6">
                    <form id="loginForm" action="{{ route('student.login.auth') }}" method="POST" class="space-y-5">
                        @csrf

                        {{-- Pesan Status --}}
                        @if (session('status'))
                            <div class="bg-green-50 border border-green-200 text-green-700 text-sm p-3 rounded">
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

                        {{-- Email --}}
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email Terdaftar</label>
                            <div class="relative">
                                <input type="email" name="email" id="email" value="{{ old('email') }}" required
                                    class="w-full pl-10 pr-4 py-2.5 bg-white border border-gray-300 rounded focus:outline-none focus:border-green-500 focus:ring-1 focus:ring-green-500"
                                    placeholder="nama@email.com">
                                <i class="fas fa-envelope absolute left-3 top-3 text-gray-400 text-sm"></i>
                            </div>
                        </div>

                        {{-- Password --}}
                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                            <div class="relative">
                                <input type="password" name="password" id="password" required
                                    class="w-full pl-10 pr-10 py-2.5 bg-white border border-gray-300 rounded focus:outline-none focus:border-green-500 focus:ring-1 focus:ring-green-500"
                                    placeholder="Masukkan password Anda">
                                <i class="fas fa-lock absolute left-3 top-3 text-gray-400 text-sm"></i>
                                <button type="button" onclick="togglePassword('password', this)"
                                    class="absolute right-3 top-3 text-gray-400 hover:text-green-600">
                                    <i class="fas fa-eye text-sm"></i>
                                </button>
                            </div>
                        </div>

                        {{-- Pertanyaan Keamanan --}}
                        <div>
                            <label for="randomNumberInput" class="block text-sm font-medium text-gray-700 mb-2" id="randomNumberDisplay"></label>
                            <div class="relative">
                                <input type="number" name="randomNumberInput" id="randomNumberInput" required
                                    class="w-full px-3 py-2.5 bg-white border border-gray-300 rounded focus:outline-none focus:border-green-500 focus:ring-1 focus:ring-green-500"
                                    placeholder="Jawaban">
                            </div>
                        </div>

                        {{-- Remember Me --}}
                        <div class="flex justify-between items-center text-sm">
                            <label class="flex items-center space-x-2">
                                <input type="checkbox" name="remember"
                                    class="text-green-600 border-gray-300 rounded focus:ring-green-500">
                                <span class="text-gray-700">Ingat Saya</span>
                            </label>
                        </div>

                        {{-- Tombol Login --}}
                        <button type="submit" id="submitButton"
                            class="w-full bg-green-600 text-white py-2.5 rounded font-medium flex items-center justify-center gap-2 hover:bg-green-700 transition-colors">
                            <i class="fas fa-sign-in-alt text-sm"></i> Masuk Sekarang
                        </button>

                        {{-- Link ke Register --}}
                        <div class="text-center text-sm mt-6 border-t border-gray-200 pt-5">
                            <p class="text-gray-600">
                                Belum punya akun?
                                <a href="{{ route('student.register.form') }}"
                                    class="font-medium text-green-600 hover:text-green-700">Daftar Sekarang</a>
                            </p>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Footer kecil --}}
            <p class="text-center text-gray-500 text-xs mt-5">
                &copy; {{ date('Y') }} Perpustakaan SMK Karya Guna 2 Bekasi
            </p>
        </div>
    </div>

    <script>
        // Toggle password visibility
        function togglePassword(fieldId, button) {
            const field = document.getElementById(fieldId);
            const icon = button.querySelector('i');
            
            if (field.type === 'password') {
                field.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                field.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }

        // Generate angka acak untuk pertanyaan sederhana
        function generateRandomNumberQuestion() {
            const num1 = Math.floor(Math.random() * 10) + 1; // Angka antara 1-10
            const num2 = Math.floor(Math.random() * 10) + 1; // Angka antara 1-10
            const sum = num1 + num2;

            // Tampilkan pertanyaan di label
            const questionLabel = document.getElementById('randomNumberDisplay');
            questionLabel.textContent = `Berapa hasil dari ${num1} + ${num2}?`;

            return sum;
        }

        // Simpan jawaban yang benar
        let correctAnswer = generateRandomNumberQuestion();

        // Validasi jawaban sebelum submit form
        document.getElementById('loginForm').addEventListener('submit', function(event) {
            const userAnswer = parseInt(document.getElementById('randomNumberInput').value, 10);
            if (userAnswer !== correctAnswer) {
                event.preventDefault();
                alert('Jawaban Anda salah. Silahkan coba lagi.');
                // Regenerate pertanyaan baru
                correctAnswer = generateRandomNumberQuestion();
                document.getElementById('randomNumberInput').value = '';
            }
        });
    </script>
@endsection