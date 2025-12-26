<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class StudentController extends Controller
{
    public function index()
    {
        $student = Auth::user();
        return view('student.edit', compact('student'));
    }

    public function update(Request $request)
    {
        $student = Auth::user();

        $request->validate([
            'name'    => ['required', 'string', 'max:255'],
            'avatar'  => ['nullable', 'image', 'max:2048'],
            'email'   => [
                'required', 
                'string', 
                'email', 
                'max:255',
                Rule::unique('students')->ignore($student->id),
            ],
            'class'   => ['required', 'string', 'max:50'],
            'contact' => ['nullable', 'string', 'max:20'],
        ]);

        $data = $request->only(['name', 'email', 'class', 'contact']);

        // Jika ada file avatar baru
        if ($request->hasFile('avatar')) {

            $avatarPath = $request->file('avatar')->store('avatars', 'public');

            // Hapus avatar lama jika ada
            if ($student->avatar && Storage::disk('public')->exists($student->avatar)) {
                Storage::disk('public')->delete($student->avatar);
            }

            $data['avatar'] = $avatarPath;
        }

        // Update database
        $student->update($data);

        return redirect()->route('student.edit')->with('success', 'Profile berhasil diperbarui!');
    }
}
