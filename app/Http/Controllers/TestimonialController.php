<?php

namespace App\Http\Controllers;

use App\Models\Testimonial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TestimonialController extends Controller
{
    public function store(Request $request)
    {   

        $student = Auth::guard('student')->user();
        
        // --- VALIDASI: Cek Apakah Siswa Login dan Aktif ---
        // Menggunakan is_active_flag sesuai dengan yang ada di file blade
        if (!$student || !$student->is_active_flag) {
            return response()->json([
                'message' => 'Akun Anda tidak aktif. Silakan hubungi administrator.',
                'errors' => ['account' => 'Akun Anda tidak aktif.']
            ], 403);
        }

        // 1. Validasi
        $request->validate([
            'content' => 'required|string|min:10|max:500',
            'rating'  => 'required|integer|min:1|max:5',
        ], [
            'content.required' => 'Komentar tidak boleh kosong.',
            'content.min' => 'Komentar minimal 10 karakter.',
        ]);

        // 2. Langsung simpan ke database (Pengecekan is_active dihapus)
        Testimonial::create([
            'student_id' => Auth::guard('student')->id(),
            'content'   => $request->content,
            'rating'    => $request->rating,
        ]);

        // 3. Kembali ke dashboard dengan pesan sukses
        return back()->with('success_testi', 'Terima kasih! Ulasan Anda berhasil dikirim dan menunggu persetujuan admin.');
    }
}