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
        $student = Auth::guard('student')->user();
        return view('student.edit', compact('student'));
    }

    public function update(Request $request)
    {
        $student = Auth::guard('student')->user();

        $validatedData = $request->validate([
            'name'    => ['required', 'string', 'max:255'],
            'email'   => [
                'required', 
                'string', 
                'email', 
                'max:255',
                Rule::unique('students')->ignore($student->id),
            ],
            'contact' => ['nullable', 'string', 'max:20'],
            'avatar'  => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,webp', 'max:2048'],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ]);

        $request->request->remove('nis');
        $request->request->remove('class');

        $data = $validatedData; 

        if ($request->hasFile('avatar')) {
            $avatarPath = $request->file('avatar')->store('avatars', 'public');

            // Hapus avatar lama jika ada
            if ($student->avatar && Storage::disk('public')->exists($student->avatar)) {
                Storage::disk('public')->delete($student->avatar);
            }

            $data['avatar'] = $avatarPath;
        }

        $student->update($data);

        return response()->json([
            'success' => 'Profil berhasil diperbarui.',
            'avatar_url' => $student->avatar ? asset('storage/' . $student->avatar) : null
        ]);
    }
}