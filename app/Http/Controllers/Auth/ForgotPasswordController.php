<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;

class ForgotPasswordController extends Controller
{
    /**
     * Tampilkan form permintaan reset password.
     */
    public function showLinkRequestForm()
    {
        return view('auth.passwords.email');
    }

    /**
     * Kirim link reset password ke email user/student.
     */
    public function sendResetLinkEmail(Request $request)
    {
        // Validasi email
        $request->validate(['email' => 'required|email']);

        // Kirim link reset via broker 'students'
        $status = Password::broker('students')->sendResetLink(
            $request->only('email')
        );

        // Cek hasil dan beri respon
        return $status === Password::RESET_LINK_SENT
            ? back()->with('status', __($status))
            : back()->withErrors(['email' => __($status)]);
    }

    /**
     * Gunakan broker 'students' untuk reset password student.
     */
    public function broker()
    {
        return Password::broker('students');
    }
}
