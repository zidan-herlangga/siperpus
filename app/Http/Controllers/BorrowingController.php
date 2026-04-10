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
        if (!$student || !$student->is_active_flag) {
            return response()->json([
                'success' => false,
                'message' => 'Akun Anda tidak aktif. Silakan hubungi administrator.',
                'errors' => ['account' => 'Akun Anda tidak aktif.']
            ], 403);
        }
        
        // --- VALIDASI: Cek Jam Operasional ---
        if (!AppServiceProvider::isLibraryOpen()) {
            return response()->json([
                'success' => false,
                'message' => 'Peminjaman hanya dapat dilakukan pada jam operasional perpustakaan.',
                'errors' => ['borrow' => 'Peminjaman hanya dapat dilakukan pada jam operasional.']
            ], 403);
        }
        
        // --- VALIDASI: Cek apakah siswa sudah pernah meminjam atau mengajukan peminjaman buku ini ---
        $existingBorrowing = Borrowing::where('student_id', $student->id)
            ->where('book_id', $book->id)
            ->whereIn('status', ['Pending', 'Dipinjam'])
            ->first();

        if ($existingBorrowing) {
            if ($existingBorrowing->status === 'Pending') {
                $errorMessage = 'Anda sudah mengajukan peminjaman untuk buku ini. Silakan tunggu konfirmasi dari admin.';
            } else { 
                $errorMessage = 'Anda sedang meminjam buku ini dan belum mengembalikannya.';
            }

            return response()->json([
                'success' => false,
                'message' => $errorMessage,
                'errors' => ['borrow' => $errorMessage]
            ], 409); 
        }

        // --- VALIDASI: Pastikan stok buku masih tersedia ---
        if ($book->stock < 1) {
            return response()->json([
                'success' => false,
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
            'status' => 'Pending', 
        ]);

        // DIPERBAIKI: JANGAN kurangi stok di sini karena statusnya masih "Pending" (menunggu admin).
        // Stok sebaiknya dikurangi saat admin menekan tombol "Setujui/Dipinjam" menggunakan Observer atau logic lainnya.
        // $book->decrement('stock'); 

        // KODE DI BAWAH INI DIHAPUS KARENA TIDAK LOGIS (status barusan dibuat 'Pending', bukan 'Batal')
        // if ($borrowing->status === 'Batal') {
        //     $book->increment('stock');
        // }

        // --- RESPONSE: Kembalikan respons JSON sukses ---
        // DIPERBAIKI: Menambahkan 'success' => true dan 'borrow_id' agar modal tiket bisa muncul
        return response()->json([
            'success' => true,
            'borrow_id' => $borrowing->id, // Dikirim agar muncul ID di Tiket
            'message' => 'Permintaan peminjaman buku "' . $book->title . '" berhasil diajukan! Menunggu persetujuan admin.',
            'redirect_url' => route('books.show', $book)
        ], 200);
    }
}