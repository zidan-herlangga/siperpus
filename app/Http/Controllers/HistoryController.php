<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Borrowing;

class HistoryController extends Controller
{
    public function index(Request $request)
    {
        $student = Auth::guard('student')->user();
        
        $query = $student->borrowings()->with('book');

        // Search by book title
        if ($request->filled('search')) {
            $search = trim($request->search);
            $query->whereHas('book', function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%");
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $borrowings = $query->latest()->paginate(10)->withQueryString();

        return view('student.history', compact('borrowings'));
    }
}