<?php

namespace App\Http\Controllers;

use App\Models\Student;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class StudentRegistrationController extends Controller
{
    /**
     * Menampilkan halaman form pendaftaran.
     */
    public function create()
    {
        return view('auth.register_student');
    }

    /**
     * Memproses dan menyimpan data pendaftaran siswa baru.
     */
    public function store(Request $request)
    {
        // Validasi input dari form
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'nis' => ['nullable', 'string', 'max:255', 'unique:students'],
            'class' => ['required', 'string', 'max:255'],
            'contact' => ['required', 'string', 'max:20'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:students'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        // Buat data siswa baru di database
        $student = Student::create([
            'name' => $request->name,
            'nis' => $request->nis,
            'class' => $request->class,
            'contact' => $request->contact,
            'email' => $request->email,
            'password' => $request->password, // Akan di-hash otomatis oleh model
        ]);

        // Kirim email verifikasi ke siswa yang baru mendaftar
        event(new Registered($student));

        // Arahkan ke halaman login dengan pesan sukses
        return redirect()->route('student.login.form')
            ->with('status', 'Pendaftaran berhasil! Silakan cek email Anda untuk verifikasi akun.');
    }
}