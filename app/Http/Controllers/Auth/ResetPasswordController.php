<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password; // <-- Use Password facade
use Illuminate\Support\Str;
use Illuminate\Auth\Events\PasswordReset; // <-- Use PasswordReset event

class ResetPasswordController extends Controller
{
    /**
     * Display the password reset view.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function showResetForm(Request $request)
    {
        // Get token and email from the request (usually query parameters)
        $token = $request->route('token'); // Assumes token is a route parameter
        $email = $request->query('email');

        return view('auth.passwords.reset')->with(
            ['token' => $token, 'email' => $email]
        );
    }

    /**
     * Handle an incoming password reset request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function reset(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
        ]);

        // Use the 'students' password broker defined in config/auth.php
        $status = Password::broker('students')->reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($student, $password) {
                // Manually update the student's password and remember token
                $student->forceFill([
                    'password' => Hash::make($password),
                    'remember_token' => Str::random(60), // Optional: Reset remember token
                ])->save();

                // Fire the PasswordReset event
                event(new PasswordReset($student));

                // Optional: Log the student in immediately after reset
                // Auth::guard('student')->login($student);
            }
        );

        // Redirect based on the reset status
        return $status === Password::PASSWORD_RESET
                    ? redirect()->route('student.login.form')->with('status', __($status)) // Redirect to login on success
                    : back()->withInput($request->only('email'))
                          ->withErrors(['email' => __($status)]); // Show error on failure
    }

    /**
     * Get the guard to be used during password reset.
     *
     * @return \Illuminate\Contracts\Auth\StatefulGuard
     */
    protected function guard()
    {
        return Auth::guard('student');
    }
}