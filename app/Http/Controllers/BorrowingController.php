<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Borrowing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Providers\AppServiceProvider;

class BorrowingController extends Controller
{
    public function store(Request $request, Book $book)
    {
        $student = Auth::guard('student')->user();

        if (!$student || !$student->is_active_flag) {
            return response()->json([
                'success' => false,
                'message' => 'Akun Anda tidak aktif. Silakan hubungi administrator.',
                'errors' => ['account' => 'Akun Anda tidak aktif.']
            ], 403);
        }

        if (!AppServiceProvider::isLibraryOpen()) {
            return response()->json([
                'success' => false,
                'message' => 'Peminjaman hanya dapat dilakukan pada jam operasional perpustakaan.',
                'errors' => ['borrow' => 'Peminjaman hanya dapat dilakukan pada jam operasional.']
            ], 403);
        }

        $existingBorrowing = Borrowing::where('student_id', $student->id)
            ->where('book_id', $book->id)
            ->whereIn('status', ['Pending', 'Dipinjam'])
            ->first();

        if ($existingBorrowing) {
            $errorMessage = $existingBorrowing->status === 'Pending'
                ? 'Anda sudah mengajukan peminjaman untuk buku ini. Silakan tunggu konfirmasi dari admin.'
                : 'Anda sedang meminjam buku ini dan belum mengembalikannya.';

            return response()->json([
                'success' => false,
                'message' => $errorMessage,
                'errors' => ['borrow' => $errorMessage]
            ], 409);
        }

        if ($book->condition !== 'Baik') {
            return response()->json([
                'success' => false,
                'message' => 'Maaf, buku ini sedang dalam kondisi ' . $book->condition . ' dan tidak dapat dipinjam.',
                'errors' => ['condition' => 'Buku tidak dalam kondisi baik.']
            ], 403);
        }

        $maxBorrow = (int) config('library.max_borrow_per_student', 3);
        $activeBorrowCount = Borrowing::where('student_id', $student->id)
            ->whereIn('status', ['Pending', 'Dipinjam'])
            ->count();

        if ($activeBorrowCount >= $maxBorrow) {
            return response()->json([
                'success' => false,
                'message' => "Anda sudah mencapai batas maksimal peminjaman ({$maxBorrow} buku). Silakan kembalikan buku terlebih dahulu.",
                'errors' => ['borrow' => "Batas maksimal peminjaman adalah {$maxBorrow} buku."]
            ], 403);
        }

        if ($book->stock < 1) {
            return response()->json([
                'success' => false,
                'message' => 'Maaf, stok buku "' . $book->title . '" sedang habis. Silakan cek kembali nanti.',
                'errors' => ['stock' => 'Stok buku habis.']
            ], 403);
        }

        $borrowing = Borrowing::create([
            'student_id' => $student->id,
            'book_id' => $book->id,
            'borrow_date' => now(),
            'due_date' => now()->addDays((int) config('library.borrow_duration_days', 7)),
            'status' => 'Pending',
        ]);

        return response()->json([
            'success' => true,
            'borrow_id' => $borrowing->id,
            'message' => 'Permintaan peminjaman buku "' . $book->title . '" berhasil diajukan! Menunggu persetujuan admin.',
            'redirect_url' => route('books.show', $book)
        ], 200);
    }
}
