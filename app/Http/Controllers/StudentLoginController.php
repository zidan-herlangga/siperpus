<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentLoginController extends Controller
{
    /**
     * Menampilkan halaman form login.
     */
    public function showLoginForm()
    {
        return view('auth.login_student');
    }

    /**
     * Memproses otentikasi siswa dengan email & password.
     */
    public function authenticate(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Coba otentikasi dengan kredensial yang diberikan dan opsi "Ingat Saya"
        if (Auth::guard('student')->attempt($credentials, $request->boolean('remember'))) {
            // --- KREDENSIAL BENAR ---
            
            $request->session()->regenerate();
            $student = Auth::guard('student')->user();

            // Periksa apakah email sudah diverifikasi
            if (! $student->hasVerifiedEmail()) {
                // JIKA BELUM DIVERIFIKASI: Arahkan ke halaman notifikasi verifikasi
                return redirect()->route('verification.notice');
            }

            // JIKA SUDAH DIVERIFIKASI: Lanjutkan ke dashboard
            return redirect()->intended(route('student.dashboard'))->with('status', 'Login berhasil!');
        }

        // --- KREDENSIAL SALAH ---
        return back()->withErrors([
            'email' => 'Email atau password yang Anda masukkan salah.',
        ])->onlyInput('email');
    }
    
    /**
     * Memproses logout siswa.
     */
    public function logout(Request $request)
    {
        Auth::guard('student')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('status', 'Anda telah berhasil logout.');
    }
}