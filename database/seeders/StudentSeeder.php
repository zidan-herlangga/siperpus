<?php

namespace Database\Seeders;

use App\Models\Student;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class StudentSeeder extends Seeder
{
    public function run(): void
    {
        Student::create([
            'name' => 'Andi Budiman',
            'nis' => '1001',
            'class' => 'XII IPA 1',
            'contact' => '081234567890',
            'email' => 'andi@example.com', // Ganti dengan email valid untuk tes
            'email_verified_at' => now(),
            'status' => 'Aktif',
        ]);

        Student::create([
            'name' => 'Citra Lestari',
            'nis' => '1002',
            'class' => 'XI IPS 2',
            'contact' => '081234567891',
            'email' => 'citra@example.com', // Ganti dengan email valid untuk tes
            'email_verified_at' => null, // Belum verifikasi
            'status' => 'Aktif',
        ]);

        Student::create([
            'name' => 'Doni Saputra',
            'nis' => '1003',
            'class' => 'X-A',
            'contact' => '081234567892',
            'email' => 'doni@example.com', // Ganti dengan email valid untuk tes
            'email_verified_at' => now(),
            'status' => 'Nonaktif',
        ]);
    }
}