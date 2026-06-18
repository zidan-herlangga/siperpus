<?php

namespace App\Providers;

use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\ServiceProvider;
use Illuminate\Auth\Middleware\Authenticate;
use Illuminate\Contracts\Auth\Factory as AuthFactory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\RateLimiter;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

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

        $this->configureRateLimiters();
        $this->trackVisitor();
    }

    private function configureRateLimiters(): void
    {
        RateLimiter::for('public', fn (Request $request) =>
            Limit::perMinute(120)->by($request->ip())
        );

        RateLimiter::for('auth', fn (Request $request) =>
            Limit::perMinute(10)->by($request->ip())
        );

        RateLimiter::for('borrow', fn (Request $request) =>
            Limit::perMinute(5)->by(auth('student')->id() ?: $request->ip())
        );

        RateLimiter::for('api', fn ($request) =>
            Limit::perMinute(60)->by($request->user()?->id ?: $request->ip())
        );
    }

    private function trackVisitor(): void
    {
        try {
            $ip = request()->ip();
            $today = now()->toDateString();
            $cacheKey = "visitor_today:{$today}:{$ip}";

            if (Cache::has($cacheKey)) {
                return;
            }

            Cache::put($cacheKey, true, now()->endOfDay());

            DB::table('visitors')->insert([
                'ip_address' => $ip,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        } catch (\Exception $e) {
            // Silent fail during setup
        }
    }

    public static function isLibraryOpen(): bool
    {
        return true;
    }
}
