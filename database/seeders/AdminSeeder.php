<?php

namespace Database\Seeders;

use App\Models\Admin;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Admin::create([
            'name' => 'Admin',
            'email' => 'admin@smkkg2.sch.id',
            'password' => Hash::make('AdminPerpustakaan'),
            'role' => 'admin',
        ]);

        Admin::create([
            'name' => 'Staff',
            'email' => 'staff@smkkg2.sch.id',
            'password' => Hash::make('StaffPerpustakaan'),
            'role' => 'staff',
        ]);

        Admin::create([
            'name' => 'Kepala Sekolah',
            'email' => 'kepsek@smkkg2.sch.id',
            'password' => Hash::make('KepsekPerpustakaan'),
            'role' => 'kepsek',
        ]);
    }
}
