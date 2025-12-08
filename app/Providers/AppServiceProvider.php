<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Auth\Middleware\Authenticate;
use Illuminate\Contracts\Auth\Factory as AuthFactory;

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
