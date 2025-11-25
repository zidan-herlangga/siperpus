<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentLoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login_student');
    }

    public function authenticate(Request $request)
    {
        // Validasi input
        $request->validate([
            'login' => ['required'],
            'password' => ['required', 'min:8'],
        ]);

        $login = $request->input('login');
        $password = $request->input('password');

        // Cek apakah input berupa email atau nis
        $field = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'nis';

        // Buat array kredensial dinamis
        $credentials = [
            $field => $login,
            'password' => $password,
        ];

        // Coba login
        if (Auth::guard('student')->attempt($credentials, $request->boolean('remember'))) {

            $request->session()->regenerate();
            $student = Auth::guard('student')->user();

            // Cek email verified
            if (!$student->hasVerifiedEmail()) {
                return redirect()->route('verification.notice');
            }

            return redirect()->intended(route('student.dashboard'))
                ->with('status', 'Login berhasil!');
        }

        // Jika gagal
        return back()->withErrors([
            'login' => 'Email/NIS atau password salah.',
        ])->onlyInput('login');
    }

    public function logout(Request $request)
    {
        Auth::guard('student')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('status', 'Anda telah berhasil logout.');
    }
}
