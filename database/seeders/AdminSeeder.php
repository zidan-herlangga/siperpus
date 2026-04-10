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
            'email' => 'admin@admin.com',
            'password' => Hash::make('password'),
        ]);

        // Admin::create([
        //     'name' => 'Penjaga',
        //     'email' => 'penjaga@perpustakaan.com',
        //     'password' => Hash::make('PenjagaPerpustakaan'),
        // ]);
    }
}
