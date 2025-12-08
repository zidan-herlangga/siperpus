<?php

namespace App\Http\Controllers;

use App\Models\Book;
use Illuminate\Http\Request;

class BookController extends Controller
{
    public function index(Request $request)
    {
        ini_set('max_execution_time', 3600);
        $query = Book::query();

        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('author', 'like', "%{$search}%")
                    ->orWhere('category', 'like', "%{$search}%");
            });
        }

        if ($request->has('category') && $request->category != '') {
            $query->where('category', $request->category);
        }

        // Pagination aktif
        $books = $query->orderBy('title')->paginate(12)->withQueryString();

        // Get unique categories for filter dropdown
        $categories = Book::select('category')
            ->distinct()
            ->orderBy('category')
            ->pluck('category');

        return view('books.index', compact('books', 'categories'));
    }

    public function show(Book $book)
    {
        // Ambil buku terkait berdasarkan kategori, kecuali buku yang sedang dilihat
        $relatedBooks = Book::where('category', $book->category)
                            ->where('id', '!=', $book->id)
                            ->inRandomOrder() // Acak agar lebih bervariasi
                            ->limit(4) // Batasi hanya 4 buku
                            ->get();


        return view('books.show', compact('book', 'relatedBooks'));
    }
}