<?php

namespace App\Filament\Widgets;

use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class VisitorTodayStats extends BaseWidget
{
    protected static bool $lazy = true;
    protected int|string|array $columnSpan = 'full';
    protected static ?int $sort = 1;

    protected function getStats(): array
    {
        $data = Cache::remember('widget.visitor_stats', 60, function () {
            $today = now()->startOfDay();
            $yesterday = now()->subDay()->startOfDay();

            $counts = DB::table('visitors')
                ->selectRaw("
                    SUM(CASE WHEN created_at >= ? THEN 1 ELSE 0 END) as today_count,
                    SUM(CASE WHEN created_at >= ? AND created_at < ? THEN 1 ELSE 0 END) as yesterday_count
                ", [$today, $yesterday, $today])
                ->first();

            $totalVisitors = DB::table('visitors')->count();

            $last7Days = DB::table('visitors')
                ->selectRaw('DATE(created_at) as date, COUNT(*) as total')
                ->where('created_at', '>=', now()->subDays(6)->startOfDay())
                ->groupByRaw('DATE(created_at)')
                ->pluck('total', 'date')
                ->toArray();

            $chart = [];
            $sum = 0;
            for ($i = 6; $i >= 0; $i--) {
                $date = now()->subDays($i)->format('Y-m-d');
                $val = $last7Days[$date] ?? 0;
                $chart[] = $val;
                $sum += $val;
            }

            return [
                'today' => (int) ($counts->today_count ?? 0),
                'yesterday' => (int) ($counts->yesterday_count ?? 0),
                'total' => $totalVisitors,
                'chart' => $chart,
                'average' => (int) round($sum / 7),
            ];
        });

        $difference = $data['today'] - $data['yesterday'];

        return [
            Stat::make('Hari Ini', $data['today'])
                ->description(
                    $difference >= 0
                        ? "Naik {$difference}"
                        : "Turun " . abs($difference)
                )
                ->icon('heroicon-o-eye')
                ->color($difference >= 0 ? 'success' : 'danger')
                ->chart($data['chart']),

            Stat::make('Kemarin', $data['yesterday'])
                ->icon('heroicon-o-clock')
                ->color('gray'),

            Stat::make('Total Pengunjung', $data['total'])
                ->icon('heroicon-o-users')
                ->color('info'),

            Stat::make('Rata-rata (7 Hari)', $data['average'])
                ->icon('heroicon-o-chart-bar')
                ->color('primary'),
        ];
    }
}