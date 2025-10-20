<?php

namespace Database\Seeders;

use App\Models\Book;
use App\Models\Borrowing;
use App\Models\Student;
use Illuminate\Database\Seeder;

class BorrowingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Ambil siswa dan buku pertama yang ada di database
        $studentForReminder = Student::first();
        $bookForReminder = Book::first();

        // Ambil siswa dan buku kedua
        $studentForOverdue = Student::skip(1)->first();
        $bookForOverdue = Book::skip(1)->first();

        // Pastikan data yang dibutuhkan ada
        if (!$studentForReminder || !$bookForReminder || !$studentForOverdue || !$bookForOverdue) {
            $this->command->error('Pastikan Anda memiliki setidaknya 2 siswa dan 2 buku di database untuk menjalankan seeder ini.');
            return;
        }

        // --- SKENARIO 1: UJI COBA PENGINGAT (REMINDER H-1) ---
        // Buku ini akan jatuh tempo besok
        Borrowing::create([
            'student_id' => $studentForReminder->id,
            'book_id' => $bookForReminder->id,
            'borrow_date' => now()->subDays(6), // Dipinjam 6 hari yang lalu
            'due_date' => today()->addDay(),   // Jatuh tempo besok (21 Oktober 2025)
            'status' => 'Dipinjam',
        ]);
        // Kurangi stok buku
        $bookForReminder->decrement('stock');
        $this->command->info('Data uji coba untuk PENGINGAT H-1 telah dibuat.');


        // --- SKENARIO 2: UJI COBA JATUH TEMPO (OVERDUE) ---
        // Buku ini sudah terlambat 2 hari
        Borrowing::create([
            'student_id' => $studentForOverdue->id,
            'book_id' => $bookForOverdue->id,
            'borrow_date' => now()->subDays(9), // Dipinjam 9 hari yang lalu
            'due_date' => today()->subDays(2),   // Jatuh tempo 2 hari yang lalu (18 Oktober 2025)
            'status' => 'Dipinjam',
        ]);
        // Kurangi stok buku
        $bookForOverdue->decrement('stock');
        $this->command->info('Data uji coba untuk KETERLAMBATAN telah dibuat.');
    }
}