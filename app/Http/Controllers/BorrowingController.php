<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Borrowing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Providers\AppServiceProvider;

class BorrowingController extends Controller
{
    /**
     * Menyimpan data peminjaman baru.
     */
    public function store(Request $request, Book $book)
    {

        // --- VALIDASI BARU: Cek Jam Operasional ---
        if (! AppServiceProvider::isLibraryOpen()) {
            return back()->withErrors(['borrow' => 'Peminjaman hanya dapat dilakukan pada jam operasional.']);
        }
        
        $student = Auth::guard('student')->user();
        
        // --- VALIDASI BARU: Mencegah pinjam buku yang sama ---
        $isAlreadyBorrowed = Borrowing::where('student_id', $student->id)
            ->where('book_id', $book->id)
            ->where('status', 'Dipinjam')
            ->exists();

        
        if ($isAlreadyBorrowed) {
            return back()->withErrors(['borrow' => 'Anda sudah meminjam buku ini dan belum mengembalikannya.']);
        }
        // --- AKHIR VALIDASI BARU ---

        // 1. Pastikan stok buku masih tersedia
        if ($book->stock < 1) {
            return back()->withErrors(['stock' => 'Maaf, stok buku ini sudah habis.']);
        }
        
        // 3. Buat data peminjaman baru
        Borrowing::create([
            'student_id' => $student->id,
            'book_id' => $book->id,
            'borrow_date' => now(),
            'due_date' => now()->addDays(7), // Jatuh tempo 7 hari dari sekarang
            'status' => 'Pending', // Status awal peminjaman : Dipinjam
        ]);

        // 4. Kurangi stok buku
        // $book->decrement('stock');

        // 5. Redirect ke dashboard dengan pesan sukses
        return redirect()->route('student.dashboard')->with('status', 'Buku "' . $book->title . '" berhasil dipinjam!');
    }
}