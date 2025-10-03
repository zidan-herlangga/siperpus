<?php

use App\Http\Controllers\BookController;
use App\Http\Controllers\StudentRegistrationController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('books.index');
});

// Student Registration Routes
Route::get('/register-student', [StudentRegistrationController::class, 'create'])->name('student.register.form');
Route::post('/register-student', [StudentRegistrationController::class, 'store'])->name('student.register.store');

// Book Catalog Routes
Route::get('/books', [BookController::class, 'index'])->name('books.index');
Route::get('/books/{book}', [BookController::class, 'show'])->name('books.show');

// --- Email Verification Routes (Manual Registration) ---

// Route untuk menampilkan halaman notifikasi bahwa verifikasi diperlukan.
// Laravel secara otomatis akan mengarahkan ke sini jika middleware 'verified' gagal.
Route::get('/email/verify', function () {
    return view('auth.verify-email'); // Anda mungkin perlu membuat view ini jika belum ada
})->middleware('auth')->name('verification.notice');

// Route yang akan dituju dari link di email verifikasi.
Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill();
    return redirect('/books')->with('status', 'Email Anda berhasil diverifikasi!');
})->middleware(['auth', 'signed'])->name('verification.verify');

// Route untuk mengirim ulang email verifikasi jika user memintanya.
Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();
    return back()->with('message', 'Link verifikasi baru telah berhasil dikirim ke email Anda!');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');