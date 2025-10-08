<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentLoginController extends Controller
{
    /**
     * Menampilkan form login.
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

        // Coba lakukan otentikasi dengan kredensial yang diberikan
        if (Auth::guard('student')->attempt($credentials)) {
            // --- KREDENSIAL BENAR ---
            
            $student = Auth::guard('student')->user();

            // Periksa apakah email sudah diverifikasi
            if ($student->hasVerifiedEmail()) {
                // JIKA SUDAH DIVERIFIKASI: Lanjutkan login
                $request->session()->regenerate();
                return redirect()->intended(route('student.dashboard'))->with('status', 'Login berhasil!');
            } else {
                // JIKA BELUM DIVERIFIKASI: Batalkan login dan beri pesan error
                Auth::guard('student')->logout();
                return back()->withErrors([
                    'email' => 'Akun Anda belum diverifikasi. Silakan cek email Anda untuk link verifikasi.',
                ])->onlyInput('email');
            }
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