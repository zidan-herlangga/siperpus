<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\BookComment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BookCommentController extends Controller
{
    public function store(Request $request, $bookId)
    {
        $student = Auth::guard('student')->user();

        // --- VALIDASI: Cek Apakah Siswa Login dan Aktif ---
        if (!$student || !$student->is_active_flag) {
            return response()->json([
                'message' => 'Akun Anda tidak aktif. Silakan hubungi administrator.',
                'errors' => ['account' => 'Akun Anda tidak aktif.']
            ], 403);
        }

        // --- VALIDASI: Cek apakah buku ada ---
        $book = Book::find($bookId);
        if (!$book) {
            return back()->with('error_comment', 'Buku tidak ditemukan.');
        }

        $request->validate([
            'content' => 'required|string|min:3|max:500',
        ], [
            'content.required' => 'Komentar tidak boleh kosong.',
            'content.min' => 'Komentar terlalu pendek.',
        ]);

        BookComment::create([
            'book_id'   => $bookId,
            'student_id' => $student->id,
            'content'   => $request->content,
        ]);

        return back()->with('success_comment', 'Komentar berhasil ditambahkan!');
    }
}