<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Borrowing;

class HistoryController extends Controller
{
    public function index()
    {
        // Ambil data siswa yang sedang login
        $student = Auth::guard('student')->user();
        
        // Langsung paginate dari relasi (tanpa .get() sebelum paginate)
        $borrowings = $student->borrowings()->with('book')->latest()->paginate(10);

        // Kirim variabel yang BENAR ke view: 'borrowings' (bukan 'borrows')
        return view('student.history', compact('borrowings'));
    }
}