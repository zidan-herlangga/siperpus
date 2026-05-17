<?php

namespace App\Filament\Resources\Concerns;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

trait HasRoleBasedAccess
{
    public static function canViewAny(): bool
    {
        $admin = Auth::guard('web')->user();
        return $admin && in_array($admin->role, ['admin', 'staff', 'kepsek']);
    }

    public static function canCreate(): bool
    {
        $admin = Auth::guard('web')->user();
        return $admin && $admin->role !== 'kepsek';
    }

    public static function canEdit(Model $record): bool
    {
        $admin = Auth::guard('web')->user();
        return $admin && $admin->role !== 'kepsek';
    }

    public static function canDelete(Model $record): bool
    {
        $admin = Auth::guard('web')->user();
        return $admin && $admin->role === 'admin';
    }

    public static function canDeleteAny(): bool
    {
        $admin = Auth::guard('web')->user();
        return $admin && $admin->role === 'admin';
    }
}
