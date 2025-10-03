<?php

namespace Database\Seeders;

use App\Models\Book;
use Illuminate\Database\Seeder;

class BookSeeder extends Seeder
{
    public function run(): void
    {
        Book::create([
            'title' => 'Laskar Pelangi',
            'author' => 'Andrea Hirata',
            'publisher' => 'Bentang Pustaka',
            'year' => '2005',
            'isbn' => '979-3062-79-7',
            'category' => 'Novel',
            'shelf_code' => 'NVL-001',
            'stock' => 10,
        ]);

        Book::create([
            'title' => 'Bumi Manusia',
            'author' => 'Pramoedya Ananta Toer',
            'publisher' => 'Hasta Mitra',
            'year' => '1980',
            'isbn' => '978-979-97312-3-4',
            'category' => 'Sejarah',
            'shelf_code' => 'SJH-002',
            'stock' => 5,
        ]);

        Book::create([
            'title' => 'Dasar-Dasar Pemrograman',
            'author' => 'Rinaldi Munir',
            'publisher' => 'Informatika',
            'year' => '2016',
            'isbn' => '978-602-1514-46-9',
            'category' => 'Komputer',
            'shelf_code' => 'KOM-003',
            'stock' => 15,
        ]);

        Book::create([
            'title' => 'Atomic Habits',
            'author' => 'James Clear',
            'publisher' => 'Gramedia Pustaka Utama',
            'year' => '2019',
            'isbn' => '978-602-06-3317-6',
            'category' => 'Pengembangan Diri',
            'shelf_code' => 'PD-004',
            'stock' => 8,
        ]);

        Book::create([
            'title' => 'Sapiens: Riwayat Singkat Umat Manusia',
            'author' => 'Yuval Noah Harari',
            'publisher' => 'KPG',
            'year' => '2017',
            'isbn' => '978-602-424-416-3',
            'category' => 'Sains',
            'shelf_code' => 'SCI-005',
            'stock' => 7,
        ]);
    }
}