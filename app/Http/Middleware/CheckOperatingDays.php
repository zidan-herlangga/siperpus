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
        // Ambil hari saat ini dalam zona waktu Indonesia
        // 6 = Sabtu, 0 = Minggu
        $dayOfWeek = now()->setTimezone('Asia/Jakarta')->dayOfWeek;

        // Jika hari ini adalah Sabtu atau Minggu
        if ($dayOfWeek === 6 || $dayOfWeek === 0) {
            // Tampilkan halaman "tutup" dan hentikan permintaan
            return response()->view('closed');
        }

        // Jika bukan Sabtu atau Minggu, lanjutkan permintaan ke halaman tujuan
        return $next($request);
    }
}