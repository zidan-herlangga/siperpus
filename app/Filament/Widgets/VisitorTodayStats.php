<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

// HAPUS "extends TableWidget", GANTI DENGAN "extends BaseWidget"
class VisitorTodayStats extends BaseWidget
{
    protected static ?int $sort = -1; // Taruh di posisi paling atas di dashboard

    protected function getStats(): array
    {
        // PERBAIKAN LOGIC: whereDate-nya ke 'created_at', BUKAN 'ip_address'
        $todayCount = DB::table('visitors')
                        ->whereDate('created_at', Carbon::today())
                        ->count();

        return [
            Stat::make('Jumlah Pengunjung Hari Ini', $todayCount)
                ->description('Jumlah pengunjung yang datang hari ini')
                ->icon('heroicon-o-eye') // Saya ganti jadi icon mata, lebih cocok untuk pengunjung
                ->color('primary'),
        ];
    }
}