<?php

namespace App\Http\Controllers;

use App\Models\Book;
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
                  ->orWhere('author', 'LIKE', "%{$search}%")
                  ->orWhere('category', 'LIKE', "%{$search}%");
            });
        }

        /**
         * =========================
         * CATEGORY FILTER (AMAN)
         * =========================
         */
        if ($request->filled('category')) {
            $query->where('category', $request->category);
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

        // Cegah error jika kolom tidak ada
        if (in_array($column, \Schema::getColumnListing('books'))) {
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
        $categories = Book::query()
            ->whereNotNull('category')
            ->distinct()
            ->orderBy('category')
            ->pluck('category');

        return view('books.index', compact('books', 'categories'));
    }

    public function show(string $slug)
    {
        $book = Book::where('slug', $slug)->firstOrFail();

        $relatedBooks = Book::query()
            ->where('category', $book->category)
            ->whereKeyNot($book->id)
            ->inRandomOrder()
            ->limit(4)
            ->get();

        return view('books.show', compact('book', 'relatedBooks'));
    }

    public function getStock(Book $book)
    {
        return response()->json([
            'stock' => (int) $book->stock,
            'available' => $book->stock > 0,
        ]);
    }
}
