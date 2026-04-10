<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Auth\Middleware\Authenticate;
use Illuminate\Contracts\Auth\Factory as AuthFactory;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        app()->bind(Authenticate::class, function ($app) {
            return new class($app->make(AuthFactory::class)) extends Authenticate {
                protected function redirectTo($request)
                {
                    return route('homepage'); 
                }
            };
        });

        // REKAM PENGUNJUNG HARI INI
        try {
            $ip = request()->ip();
            $today = Carbon::today()->toDateString();

            // Cek apakah IP ini sudah tercatat hari ini
            $exists = DB::table('visitors')
                        ->where('ip_address', $ip)
                        ->whereDate('created_at', $today)
                        ->exists();

            // Jika belum tercatat, masukkan ke database
            if (!$exists) {
                DB::table('visitors')->insert([
                    'ip_address' => $ip,
                    'created_at' => Carbon::now()
                ]);
            }
        } catch (\Exception $e) {
            // Biarkan silent, agar tidak error saat migrasi atau saat database belum terbentuk
        }
    }

    public static function isLibraryOpen(): bool
    {
        $now = now()->setTimezone('Asia/Jakarta');
        $dayOfWeek = $now->dayOfWeek; // Senin = 1, Jumat = 5
        $hour = $now->hour;

        // Buka hanya pada hari Senin–Jumat (1-5) antara jam 7 pagi sampai 4 sore (16)
        return ($dayOfWeek >= 1 && $dayOfWeek <= 5 && $hour >= 7 && $hour < 16);
    }
}
