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
            'name' => 'Zidan Herlangga',
            'nis' => '1001',
            'class' => 'XII TKJ 1',
            'contact' => '085161334009',
            'email' => 'zidanherlangga24@gmail.com', // Ganti dengan email valid untuk tes
            'email_verified_at' => null,
            'password' => Hash::make('password'), // Password default
            'status' => 'Aktif',
        ]);

        Student::create([
            'name' => 'Gustiar Ilham',
            'nis' => '1002',
            'class' => 'XII TKJ 1',
            'contact' => '',
            'email' => '', // Ganti dengan email valid untuk tes
            'email_verified_at' => null,
            'password' => Hash::make('password'), // Password default
            'status' => 'Aktif',
        ]);

        Student::create([
            'name' => 'Rival Rivaldy',
            'nis' => '1003',
            'class' => 'XII TKJ 1',
            'contact' => '',
            'email' => '', // Ganti dengan email valid untuk tes
            'email_verified_at' => null,
            'password' => Hash::make('password'), // Password default
            'status' => 'Aktif',
        ]);

        Student::create([
            'name' => 'Naufal Rafly S',
            'nis' => '1004',
            'class' => 'XII TKJ 1',
            'contact' => '',
            'email' => '', // Ganti dengan email valid untuk tes
            'email_verified_at' => null,
            'password' => Hash::make('password'), // Password default
            'status' => 'Aktif',
        ]);

        Student::create([
            'name' => 'Andre Budi Setiyawan',
            'nis' => '1005',
            'class' => 'XII TKJ 1',
            'contact' => '',
            'email' => '', // Ganti dengan email valid untuk tes
            'email_verified_at' => null,
            'password' => Hash::make('password'), // Password default
            'status' => 'Aktif',
        ]);
    }
}