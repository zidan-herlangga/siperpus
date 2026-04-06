<?php

use App\Http\Controllers\BookController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\BorrowingController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\StudentLoginController;
use App\Http\Controllers\StudentRegistrationController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\TestimonialController;
use App\Http\Controllers\BookCommentController;

use App\Models\Student;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Halaman Beranda
Route::get('/', [HomeController::class, 'index'])->name('homepage');

// ==========================
// RUTE UNTUK TAMU (GUEST)
// ==========================
Route::middleware('guest:student')->group(function () {
    Route::get('/register-student', [StudentRegistrationController::class, 'create'])->name('student.register.form');
    Route::post('/register-student', [StudentRegistrationController::class, 'store'])->name('student.register.store');
    Route::get('/login-student', [StudentLoginController::class, 'showLoginForm'])->name('student.login.form');
    Route::post('/login-student', [StudentLoginController::class, 'authenticate'])->name('student.login.auth');

    Route::get('forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])
        ->name('student.password.request');

    Route::post('forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])
        ->name('password.email');

    Route::get('reset-password/{token}', [ResetPasswordController::class, 'showResetForm'])
        ->name('password.reset');

    Route::post('reset-password', [ResetPasswordController::class, 'reset'])
        ->name('password.update');
});

// Logout
Route::post('/logout', [StudentLoginController::class, 'logout'])
    ->middleware('auth:student')
    ->name('student.logout');

// ==========================
// RUTE PUBLIK
// ==========================
Route::get('/books', [BookController::class, 'index'])->name('books.index');
Route::get('/books/{book:slug}', [BookController::class, 'show'])->name('books.show');
Route::get('/books/{book:slug}/stock', [BookController::class, 'getStock'])->name('books.stock');
// Route::get('/books/{book:slug}', [BookController::class, 'show'])->name('books.show');

Route::post('/submit-testimonial', [TestimonialController::class, 'store'])
    ->middleware('auth:student') // Cukup dilindungi login saja, tidak perlu verifikasi email
    ->name('testimonial.store');

// ==========================
// RUTE YANG DILINDUNGI
// ==========================
Route::middleware(['auth:student', 'verified'])->group(function () {
    // DASHBOARD STUDENT
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('student.dashboard');

    // PROFILE STUDENT
    Route::get('/dashboard/edit-profile', [StudentController::class, 'index'])->name('student.edit');
    Route::post('/dashboard/edit-profile', [StudentController::class, 'update'])->name('student.update');

    // PEMINJAMAN BUKU
    Route::post('/borrow/{book}', [BorrowingController::class, 'store'])->name('books.borrow');
    Route::post('/books/{book:id}/comment', [BookCommentController::class, 'store'])->name('books.comment.store');
});

// ==========================
// VERIFIKASI EMAIL
// ==========================
Route::get('/email/verify', function () {
    return view('auth.verify-email');
})->middleware('auth:student')->name('verification.notice');

Route::get('/email/verify/{id}/{hash}', function (Request $request, $id) {
    $student = Student::find($id);

    if (!$student || !hash_equals((string) $request->route('hash'), sha1($student->getEmailForVerification()))) {
        abort(403);
    }

    if ($student->hasVerifiedEmail()) {
        return redirect()->route('student.login.form')->with('status', 'Akun Anda sudah terverifikasi.');
    }

    if ($student->markEmailAsVerified()) {
        event(new Verified($student));
    }

    return redirect()->route('student.login.form')->with('status', 'Verifikasi berhasil!');
})->middleware(['signed'])->name('verification.verify');

Route::post('/email/verification-notification', function (Request $request) {
    $request->user('student')->sendEmailVerificationNotification();
    return back()->with('message', 'Link verifikasi baru dikirim!');
})->middleware(['auth:student', 'throttle:6,1'])->name('verification.send');
