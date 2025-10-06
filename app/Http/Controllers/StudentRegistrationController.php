<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class StudentRegistrationController extends Controller
{
    public function create()
    {
        return view('auth.register_student');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'nis' => ['required', 'string', 'max:255', 'unique:students'],
            'class' => ['required', 'string', 'max:255'],
            'contact' => ['required', 'string', 'max:20'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:students'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        $student = Student::create([
            'name' => $request->name,
            'nis' => $request->nis,
            'class' => $request->class,
            'contact' => $request->contact,
            'email' => $request->email,
            'password' => $request->password,
        ]);

        Auth::guard('student')->login($student);
        event(new Registered($student));
        return redirect()->route('student.register.form')->with('status', 'Pendaftaran berhasil! Silakan cek email Anda untuk verifikasi.');
    }
}