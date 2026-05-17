<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckAdminRole
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        $admin = Auth::guard('web')->user();

        if (!$admin || !$admin instanceof \App\Models\Admin) {
            return redirect()->route('filament.admin.auth.login');
        }

        if (!empty($roles) && !in_array($admin->role, $roles)) {
            abort(403, 'Anda tidak memiliki akses ke halaman ini.');
        }

        return $next($request);
    }
}
