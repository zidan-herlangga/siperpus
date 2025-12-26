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
        // Menggunakan is_active_flag sesuai dengan yang ada di file blade
        if (!$student || !$student->is_active_flag) {
            return response()->json([
                'message' => 'Akun Anda tidak aktif. Silakan hubungi administrator.',
                'errors' => ['account' => 'Akun Anda tidak aktif.']
            ], 403);
        }
        
        // --- VALIDASI: Cek Jam Operasional ---
        if (! AppServiceProvider::isLibraryOpen()) {
            return response()->json([
                'message' => 'Peminjaman hanya dapat dilakukan pada jam operasional perpustakaan.',
                'errors' => ['borrow' => 'Peminjaman hanya dapat dilakukan pada jam operasional.']
            ], 403);
        }
        
        // --- VALIDASI: Cek apakah siswa sudah pernah meminjam atau mengajukan peminjaman buku ini ---
        // Logika ini akan mencegah peminjaman ganda jika statusnya 'Pending' atau 'Dipinjam'
        $existingBorrowing = Borrowing::where('student_id', $student->id)
            ->where('book_id', $book->id)
            ->whereIn('status', ['Pending', 'Dipinjam'])
            ->first();

        if ($existingBorrowing) {
            // Berikan pesan error yang berbeda berdasarkan status yang ada
            if ($existingBorrowing->status === 'Pending') {
                $errorMessage = 'Anda sudah mengajukan peminjaman untuk buku ini. Silakan tunggu konfirmasi dari admin.';
            } else { // Statusnya 'Dipinjam'
                $errorMessage = 'Anda sedang meminjam buku ini dan belum mengembalikannya.';
            }

            return response()->json([
                'message' => $errorMessage,
                'errors' => ['borrow' => $errorMessage]
            ], 409); // 409 Conflict adalah kode status yang tepat untuk kondisi ini
        }

        // --- VALIDASI: Pastikan stok buku masih tersedia ---
        if ($book->stock < 1) {
            return response()->json([
                'message' => 'Maaf, stok buku ini sudah habis.',
                'errors' => ['stock' => 'Maaf, stok buku ini sudah habis.']
            ], 400);
        }
        
        // --- PROSES: Buat data peminjaman baru dengan status 'Pending' ---
        $borrowing = Borrowing::create([
            'student_id' => $student->id,
            'book_id' => $book->id,
            'borrow_date' => now(),
            'due_date' => now()->addDays(7),
            'status' => 'Pending', // Status awal adalah Pending
        ]);

        // Kurangi stok buku
        $book->decrement('stock');

        // --- BATAL: Jika Admin tolak buku, kembalikan stok buku ---
        if ($borrowing->status === 'Batal') {
            $book->increment('stock');
        }

        // --- RESPONSE: Kembalikan respons JSON sukses ---
        return response()->json([
            'message' => 'Permintaan peminjaman buku "' . $book->title . '" berhasil diajukan! Menunggu persetujuan admin.',
            'redirect_url' => route('books.show', $book)
        ], 200);


    }
}