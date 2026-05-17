<?php

namespace App\Providers\Filament;

use App\Filament\Resources\Books\BookResource;
use App\Filament\Resources\Borrowings\BorrowingResource;
use App\Filament\Resources\Categories\CategoryResource;
use App\Filament\Resources\Students\StudentResource;
use App\Models\Admin;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Pages\Dashboard;
use App\Filament\Widgets\LibraryStatsOverview;
use App\Filament\Widgets\BorrowedBooks;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class StaffPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default(false)
            ->sidebarFullyCollapsibleOnDesktop()
            ->id('staff')
            ->path('staff')
            ->login()
            ->authGuard('web')
            ->colors([
                'primary' => Color::Blue,
            ])
            ->resources([
                BookResource::class,
                BorrowingResource::class,
                StudentResource::class,
                CategoryResource::class,
            ])
            ->pages([
                Dashboard::class,
            ])
            ->widgets([
                LibraryStatsOverview::class,
                BorrowedBooks::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ])
            ->databaseNotifications();
    }
}
