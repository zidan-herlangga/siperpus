<?php

namespace App\Http\Controllers;

use App\Models\Borrowing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        // Ambil data siswa yang sedang login
        $student = Auth::guard('student')->user();

        // Ambil data peminjaman milik siswa tersebut, diurutkan dari yang terbaru
        // Eager load relasi 'book' untuk menghindari N+1 query
        $borrowings = $student->borrowings()->with('book')->latest()->get();

        // Pisahkan antara buku yang sedang dipinjam dan yang sudah dikembalikan
        $currentBorrowings = $borrowings->where('status', 'Dipinjam');
        $returnedBorrowings = $borrowings->where('status', 'Dikembalikan');
        $ipAddress = file_get_contents('https://api.ipify.org');

        return view('student.dashboard', [
            'student' => $student,
            'currentBorrowings' => $currentBorrowings,
            'returnedBorrowings' => $returnedBorrowings,
            'ipAddress' => $ipAddress,
        ]);
    }
}