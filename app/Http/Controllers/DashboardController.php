<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Borrowing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $student = Auth::guard('student')->user();

        $borrowings = $student->borrowings()->with('book')->latest()->get();

        $pendingBorrowings = $borrowings->where('status', 'Pending');
        $currentBorrowings = $borrowings->where('status', 'Dipinjam');
        $returnedBorrowings = $borrowings->where('status', 'Dikembalikan');

        return view('student.dashboard', [
            'student' => $student,
            'pendingBorrowings' => $pendingBorrowings,
            'currentBorrowings' => $currentBorrowings,
            'returnedBorrowings' => $returnedBorrowings,
        ]);
    }
}
