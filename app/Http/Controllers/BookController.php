<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\BookComment;
use App\Models\Category;
use Illuminate\Http\Request;

class BookController extends Controller
{
    public function index(Request $request)
    {
        $query = Book::query();

        /**
         * =========================
         * SEARCH (AMAN)
         * =========================
         */
        if ($request->filled('search')) {
            $search = trim($request->search);

            $query->where(function ($q) use ($search) {
                $q->where('title', 'LIKE', "%{$search}%")
                  ->orWhere('author', 'LIKE', "%{$search}%");
            });
        }

        /**
         * =========================
         * CATEGORY FILTER (AMAN)
         * =========================
         */
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        /**
         * =========================
         * SORTING (WHITELIST = ANTI SQL INJECTION)
         * =========================
         */
        $sort = $request->input('sort', 'newest');

        $allowedSorts = [
            'newest'     => ['created_at', 'desc'],
            'oldest'     => ['created_at', 'asc'],
            'title_asc'  => ['title', 'asc'],
            'title_desc' => ['title', 'desc'],
            'popular'    => ['borrow_count', 'desc'],
        ];

        // fallback default
        [$column, $direction] = $allowedSorts[$sort] ?? $allowedSorts['newest'];

        // Kolom yang diizinkan untuk sorting (whitelist), tanpa Schema introspection
        $allowedColumns = ['created_at', 'title', 'borrow_count'];
        if (in_array($column, $allowedColumns)) {
            $query->orderBy($column, $direction);
        } else {
            $query->orderBy('created_at', 'desc');
        }

        /**
         * =========================
         * PAGINATION
         * =========================
         */
        $books = $query->paginate(12)->withQueryString();

        /**
         * =========================
         * CATEGORY LIST
         * =========================
         */
        $categories = Category::orderBy('name')->pluck('name', 'id');

        return view('books.index', compact('books', 'categories'));
    }

    public function show(Request $request, string $slug)
    {
        $book = Book::with(['category', 'comments.student'])->where('slug', $slug)->firstOrFail();

        $relatedBooks = Book::query()
            ->where('category_id', $book->category_id)
            ->whereKeyNot($book->id)
            ->inRandomOrder()
            ->limit(4)
            ->get();

        $comments = BookComment::with('student')
            ->where('book_id', $book->id)
            ->approved()
            ->latest()
            ->paginate(10, ['*'], 'commentPage');

        return view('books.show', compact('book', 'relatedBooks', 'comments'));
    }

    public function getStock(Book $book)
    {
        return response()->json([
            'stock' => (int) $book->stock,
            'available' => $book->stock > 0,
        ]);
    }
}
