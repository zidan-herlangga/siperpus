<?php

namespace App\Filament\Widgets;

use Filament\Widgets\ChartWidget;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class VisitorMonthlyChart extends ChartWidget
{
    protected static ?int $sort = 0;
    protected int|string|array $columnSpan = 'full';
    protected ?string $heading = 'Grafik Pengunjung Bulan Ini';

    protected function getType(): string
    {
        return 'line';
    }

    protected function getData(): array
    {
        return Cache::remember('widget.visitor_chart', 60, function () {
            $now = now();
            $month = $now->month;
            $year = $now->year;
            $daysInMonth = $now->daysInMonth;

            $visitorsPerDay = DB::table('visitors')
                ->selectRaw('DAY(created_at) as day, COUNT(*) as total')
                ->whereMonth('created_at', $month)
                ->whereYear('created_at', $year)
                ->groupBy('day')
                ->orderBy('day')
                ->pluck('total', 'day')
                ->toArray();

            $data = [];
            $labels = [];

            for ($day = 1; $day <= $daysInMonth; $day++) {
                $labels[] = (string) $day;
                $data[] = $visitorsPerDay[$day] ?? 0;
            }

            return [
                'datasets' => [
                    [
                        'label' => 'Pengunjung',
                        'data' => $data,
                    ],
                ],
                'labels' => $labels,
            ];
        });
    }
}
