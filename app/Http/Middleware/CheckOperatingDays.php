<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckOperatingDays
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Ambil waktu saat ini dalam zona waktu Indonesia
        $now = now()->setTimezone('Asia/Jakarta');
        $dayOfWeek = $now->dayOfWeek; // 0 = Minggu, 6 = Sabtu
        $hour = $now->hour;

        // Jika hari Sabtu (6) atau Minggu (0)
        if ($dayOfWeek === 6 || $dayOfWeek === 0) {
            return response()->view('closed', [
                'message' => 'Sistem hanya beroperasi pada hari Senin–Jumat.',
            ]);
        }

        // Jika di luar jam operasional (07:00–16:00)
        if ($hour < 7 || $hour >= 16) {
            return response()->view('closed', [
                'message' => 'Sistem hanya dapat diakses antara pukul 07:00 hingga 16:00 WIB.',
            ]);
        }

        // Jika masih dalam jam dan hari kerja → lanjut
        return $next($request);
    }
}
