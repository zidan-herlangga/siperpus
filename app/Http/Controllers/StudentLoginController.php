<?php

namespace App\Http\Controllers;

use App\Mail\MagicLoginLinkMail;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;

class StudentLoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login_student');
    }

    public function authenticate(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Coba otentikasi menggunakan guard 'student'
        if (Auth::guard('student')->attempt($credentials)) {
            $request->session()->regenerate();
            
            // Redirect ke halaman buku setelah login berhasil
            return redirect()->intended(route('books.index'))->with('status', 'Login berhasil!');
        }

        // Jika otentikasi gagal
        return back()->withErrors([
            'email' => 'Email atau password yang Anda masukkan salah.',
        ])->onlyInput('email');
    }
    
    // Method logout tetap sama
    public function logout(Request $request) {
        Auth::guard('student')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/')->with('status', 'Anda telah logout.');
    }
}