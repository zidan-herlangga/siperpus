<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;

class CachePublicResponse
{
    public function handle(Request $request, Closure $next, int $ttl = 300): Response
    {
        if (!$request->isMethod('GET')) {
            return $next($request);
        }

        if (auth('student')->check()) {
            return $next($request);
        }

        $path = $request->path();
        if (str_starts_with($path, 'api/') || str_starts_with($path, 'livewire/')) {
            return $next($request);
        }

        $cacheKey = 'response:' . sha1($request->fullUrl());

        $cached = Cache::get($cacheKey);
        if ($cached !== null) {
            return response($cached)->header('X-Cache', 'HIT');
        }

        $response = $next($request);

        if ($response->isSuccessful() && $response instanceof \Illuminate\Http\Response) {
            Cache::put($cacheKey, $response->getContent(), $ttl);
            $response->header('X-Cache', 'MISS');
        }

        return $response;
    }
}
