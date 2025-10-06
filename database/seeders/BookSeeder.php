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
            'year' => 2005,
            'isbn' => '979-3062-79-7',
            'category' => 'Novel',
            'shelf_code' => 'NVL-001',
            'stock' => 10,
        ]);

        Book::create([
            'title' => 'Bumi Manusia',
            'author' => 'Pramoedya Ananta Toer',
            'publisher' => 'Hasta Mitra',
            'year' => 1980,
            'isbn' => '978-979-97312-3-4',
            'category' => 'Sejarah',
            'shelf_code' => 'SJH-002',
            'stock' => 5,
        ]);

        Book::create([
            'title' => 'Dasar-Dasar Pemrograman',
            'author' => 'Rinaldi Munir',
            'publisher' => 'Informatika',
            'year' => 2016,
            'isbn' => '978-602-1514-46-9',
            'category' => 'Komputer',
            'shelf_code' => 'KOM-003',
            'stock' => 15,
        ]);

        Book::create([
            'title' => 'Atomic Habits',
            'author' => 'James Clear',
            'publisher' => 'Gramedia Pustaka Utama',
            'year' => 2019,
            'isbn' => '978-602-06-3317-6',
            'category' => 'Pengembangan Diri',
            'shelf_code' => 'PD-004',
            'stock' => 8,
        ]);

        Book::create([
            'title' => 'Sapiens: Riwayat Singkat Umat Manusia',
            'author' => 'Yuval Noah Harari',
            'publisher' => 'KPG',
            'year' => 2017,
            'isbn' => '978-602-424-416-3',
            'category' => 'Sains',
            'shelf_code' => 'SCI-005',
            'stock' => 7,
        ]);

        Book::create([
            'title' => 'Rich Dad Poor Dad',
            'author' => 'Robert T. Kiyosaki',
            'publisher' => 'Warner Books',
            'year' => 1997,
            'isbn' => '978-0-446-67745-5',
            'category' => 'Bisnis',
            'shelf_code' => 'BSN-006',
            'stock' => 12,
        ]);

        Book::create([
            'title' => 'The Pragmatic Programmer',
            'author' => 'Andrew Hunt, David Thomas',
            'publisher' => 'Addison-Wesley',
            'year' => 1999,
            'isbn' => '978-0-201-61622-4',
            'category' => 'Komputer',
            'shelf_code' => 'KOM-007',
            'stock' => 6,
        ]);

        Book::create([
            'title' => 'Clean Code',
            'author' => 'Robert C. Martin',
            'publisher' => 'Prentice Hall',
            'year' => 2008,
            'isbn' => '978-0-13-235088-4',
            'category' => 'Komputer',
            'shelf_code' => 'KOM-008',
            'stock' => 9,
        ]);

        Book::create([
            'title' => 'Filosofi Teras',
            'author' => 'Henry Manampiring',
            'publisher' => 'Kompas',
            'year' => 2018,
            'isbn' => '978-602-481-121-1',
            'category' => 'Filsafat',
            'shelf_code' => 'FIL-009',
            'stock' => 11,
        ]);

        Book::create([
            'title' => 'Sejarah Indonesia Modern',
            'author' => 'Sartono Kartodirdjo',
            'publisher' => 'Gramedia',
            'year' => 1993,
            'isbn' => '978-979-403-253-1',
            'category' => 'Sejarah',
            'shelf_code' => 'SJH-010',
            'stock' => 4,
        ]);

        Book::create([
            'title' => 'Thinking, Fast and Slow',
            'author' => 'Daniel Kahneman',
            'publisher' => 'Farrar, Straus and Giroux',
            'year' => 2011,
            'isbn' => '978-0-374-27563-1',
            'category' => 'Psikologi',
            'shelf_code' => 'PSI-011',
            'stock' => 10,
        ]);

        Book::create([
            'title' => 'Introduction to Algorithms',
            'author' => 'Thomas H. Cormen',
            'publisher' => 'MIT Press',
            'year' => 2009,
            'isbn' => '978-0-262-03384-8',
            'category' => 'Komputer',
            'shelf_code' => 'KOM-012',
            'stock' => 5,
        ]);
    }
}
