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
        // Ambil user yang sedang login
        $student = Auth::guard('student')->user();

        // --- VALIDASI: Cek Apakah Siswa Login dan Aktif ---
        // Periksa apakah siswa ada dan apakah properti is_active-nya bernilai true
        if (!$student || !$student->is_active) {
            return response()->json([
                'message' => 'Akun Anda tidak aktif. Silakan hubungi administrator.',
                'errors' => ['account' => 'Akun Anda tidak aktif.']
            ], 403); // Status 403 Forbidden
        }
        
        // --- VALIDASI: Cek Jam Operasional ---
        if (! AppServiceProvider::isLibraryOpen()) {
            return response()->json([
                'message' => 'Peminjaman hanya dapat dilakukan pada jam operasional perpustakaan.',
                'errors' => ['borrow' => 'Peminjaman hanya dapat dilakukan pada jam operasional.']
            ], 403);
        }
        
        // --- VALIDASI: Mencegah pinjam buku yang sama ---
        $isAlreadyBorrowed = Borrowing::where('student_id', $student->id)
            ->where('book_id', $book->id)
            ->whereIn('status', ['Pending', 'Dipinjam'])
            ->exists();

        if ($isAlreadyBorrowed) {
            return response()->json([
                'message' => 'Anda sudah meminjam atau sedang dalam proses peminjaman buku ini.',
                'errors' => ['borrow' => 'Anda sudah meminjam buku ini dan belum mengembalikannya.']
            ], 409);
        }

        // 1. Pastikan stok buku masih tersedia
        if ($book->stock < 1) {
            return response()->json([
                'message' => 'Maaf, stok buku ini sudah habis.',
                'errors' => ['stock' => 'Maaf, stok buku ini sudah habis.']
            ], 400);
        }
        
        // 2. Buat data peminjaman baru
        $borrowing = Borrowing::create([
            'student_id' => $student->id,
            'book_id' => $book->id,
            'borrow_date' => now(),
            'due_date' => now()->addDays(7),
            'status' => 'Pending',
        ]);

        // 3. Kembalikan respons JSON sukses
        return response()->json([
            'message' => 'Permintaan peminjaman buku "' . $book->title . '" berhasil diajukan! Menunggu persetujuan admin.',
            'redirect_url' => route('books.show', $book)
        ], 200);
    }
}