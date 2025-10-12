<?php

use App\Http\Controllers\BookController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\BorrowingController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\StudentLoginController;
use App\Http\Controllers\StudentRegistrationController;
use App\Models\Student;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// Halaman Beranda
Route::get('/', [HomeController::class, 'index'])->name('homepage');

// ==========================
// RUTE UNTUK TAMU (GUEST)
// ==========================
Route::middleware('guest:student')->group(function () {
    Route::get('/register-student', [StudentRegistrationController::class, 'create'])->name('student.register.form');
    Route::post('/register-student', [StudentRegistrationController::class, 'store'])->name('student.register.store');
    Route::get('/login', [StudentLoginController::class, 'showLoginForm'])->name('student.login.form');
    Route::post('/login', [StudentLoginController::class, 'authenticate'])->name('student.login.auth');
});

// Logout (hanya untuk yang sudah login)
Route::post('/logout', [StudentLoginController::class, 'logout'])->middleware('auth:student')->name('student.logout');

// ==========================
// RUTE PUBLIK
// ==========================
Route::get('/books', [BookController::class, 'index'])->name('books.index');
Route::get('/books/{book:slug}', [BookController::class, 'show'])->name('books.show');

// ==========================
// RUTE YANG DILINDUNGI (MEMERLUKAN LOGIN & VERIFIKASI)
// ==========================
Route::middleware(['auth:student', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('student.dashboard');
    Route::post('/borrow/{book}', [BorrowingController::class, 'store'])->name('books.borrow');
});

// ==========================
// RUTE VERIFIKASI EMAIL
// ==========================
// Halaman notifikasi (untuk user yang mencoba akses dashboard tapi belum verify)
Route::get('/email/verify', function () {
    return view('auth.verify-email');
})->middleware('auth:student')->name('verification.notice');

// Link dari email (TIDAK PERLU LOGIN)
Route::get('/email/verify/{id}/{hash}', function (Request $request, $id) {
    $student = Student::find($id);
    if (!$student || !hash_equals((string) $request->route('hash'), sha1($student->getEmailForVerification()))) {
        abort(403);
    }
    if ($student->hasVerifiedEmail()) {
        return redirect()->route('student.login.form')->with('status', 'Akun Anda sudah terverifikasi. Silakan login.');
    }
    if ($student->markEmailAsVerified()) {
        event(new Verified($student));
    }
    return redirect()->route('student.login.form')->with('status', 'Verifikasi berhasil! Silakan login.');
})->middleware(['signed'])->name('verification.verify');

// Kirim ulang email verifikasi
Route::post('/email/verification-notification', function (Request $request) {
    $request->user('student')->sendEmailVerificationNotification();
    return back()->with('message', 'Link verifikasi baru telah berhasil dikirim!');
})->middleware(['auth:student', 'throttle:6,1'])->name('verification.send');