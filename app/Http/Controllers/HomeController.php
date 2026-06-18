<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Borrowing;
use App\Models\Category;
use App\Models\Student;
use App\Models\Testimonial;
use Illuminate\Support\Facades\Cache;

class HomeController extends Controller
{
    public function index()
    {
        $stats = Cache::remember('homepage.stats', 300, function () {
            return [
                'bookCount' => Book::count(),
                'studentCount' => Student::count(),
                'borrowCount' => Borrowing::whereMonth('borrow_date', now()->month)
                    ->whereYear('borrow_date', now()->year)->count(),
                'categoryCount' => Category::count(),
            ];
        });

        $approvedTestimonials = Cache::remember('homepage.testimonials', 300, function () {
            return Testimonial::with('student')
                ->approved()
                ->latest()
                ->take(3)
                ->get();
        });

        return view('index', array_merge($stats, [
            'approvedTestimonials' => $approvedTestimonials,
        ]));
    }
}