<?php

use App\Http\Controllers\BookController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\BorrowingController;
use App\Http\Controllers\StudentLoginController;
use App\Http\Controllers\StudentRegistrationController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Redirect root ke daftar buku
Route::get('/', function () {
    return view('index');
});

// ==========================
// AUTH ROUTES (STUDENT)
// ==========================

// --- Hanya untuk tamu (belum login) ---
Route::middleware('guest:student')->group(function () {
    // Registrasi siswa
    Route::get('/register-student', [StudentRegistrationController::class, 'create'])
        ->name('student.register.form');
    Route::post('/register-student', [StudentRegistrationController::class, 'store'])
        ->name('student.register.store');

    // Login siswa
    Route::get('/login', [StudentLoginController::class, 'showLoginForm'])
        ->name('student.login.form');
    Route::post('/login', [StudentLoginController::class, 'authenticate'])
        ->name('student.login.auth');
});

// --- Logout hanya bisa dilakukan saat login ---
Route::post('/logout', [StudentLoginController::class, 'logout'])
    ->middleware('auth:student')
    ->name('student.logout');

// ==========================
// BOOKS & BORROWING
// ==========================

// Katalog buku (public)
Route::get('/books', [BookController::class, 'index'])->name('books.index');
Route::get('/books/{book}', [BookController::class, 'show'])->name('books.show');

// Pinjam buku (hanya siswa login)
Route::post('/borrow/{book}', [BorrowingController::class, 'store'])
    ->middleware('auth:student')
    ->name('books.borrow');

// ==========================
// DASHBOARD
// ==========================
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware('auth:student')
    ->name('student.dashboard');

// ==========================
// EMAIL VERIFICATION
// ==========================
Route::get('/email/verify', function () {
    return view('auth.verify-email');
})->middleware('auth:student')->name('verification.notice');

Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    if ($request->user('student')->hasVerifiedEmail()) {
        return redirect()->route('student.dashboard')->with('email_verified', 'true');
    }

    if ($request->user('student')->markEmailAsVerified()) {
        event(new \Illuminate\Auth\Events\Verified($request->user('student')));
    }
    
    return redirect()->route('student.dashboard')->with('email_verified', 'true');
})->middleware(['auth:student', 'signed'])->name('verification.verify');

Route::post('/email/verification-notification', function (Request $request) {
    $request->user('student')->sendEmailVerificationNotification();
    return back()->with('message', 'Link verifikasi baru telah dikirim ke email Anda!');
})->middleware(['auth:student', 'throttle:6,1'])->name('verification.send');
