<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Borrowing;
use App\Models\Category;
use App\Models\Student;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Menampilkan halaman beranda dengan data statistik.
     */
    public function index()
    {
        // Mengambil data statistik dari database
        $bookCount = Book::count();
        $studentCount = Student::count();
        
        // Menghitung jumlah peminjaman yang terjadi hanya di bulan dan tahun saat ini
        $borrowCount = Borrowing::whereMonth('borrow_date', now()->month)
                                ->whereYear('borrow_date', now()->year)
                                ->count();
                                
        $categoryCount = Category::count();

        // Mengirim semua data statistik ke view 'index'
        return view('index', [
            'bookCount' => $bookCount,
            'studentCount' => $studentCount,
            'borrowCount' => $borrowCount,
            'categoryCount' => $categoryCount,
        ]);
    }
}