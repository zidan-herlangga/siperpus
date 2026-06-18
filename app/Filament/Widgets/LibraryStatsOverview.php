<?php

namespace App\Filament\Widgets;

use App\Models\Book;
use App\Models\Borrowing;
use App\Models\Student;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Cache;

class LibraryStatsOverview extends BaseWidget
{
    protected static bool $lazy = true;
    protected static ?int $sort = 2;

    protected function getStats(): array
    {
        $data = Cache::remember('widget.library_stats', 60, function () {
            $stats = Borrowing::selectRaw("
                COUNT(*) as total,
                SUM(CASE WHEN status = 'Pending' THEN 1 ELSE 0 END) as pending,
                SUM(CASE WHEN status = 'Dipinjam' THEN 1 ELSE 0 END) as borrowed,
                SUM(CASE WHEN status = 'Batal' THEN 1 ELSE 0 END) as canceled,
                SUM(CASE WHEN status = 'Dipinjam' AND due_date < NOW() THEN 1 ELSE 0 END) as overdue
            ")->first();

            return [
                'books' => Book::count(),
                'students' => Student::count(),
                'pending' => $stats->pending,
                'borrowed' => $stats->borrowed,
                'canceled' => $stats->canceled,
                'overdue' => $stats->overdue,
            ];
        });

        return [
            Stat::make('Total Judul Buku', $data['books'])
                ->description('Jumlah semua judul buku')
                ->icon('heroicon-o-book-open')
                ->color('info'),

            Stat::make('Total Siswa', $data['students'])
                ->description('Jumlah siswa terdaftar')
                ->icon('heroicon-o-users')
                ->color('success'),

            Stat::make('Pending', $data['pending'])
                ->icon('heroicon-o-clock')
                ->color('gray'),

            Stat::make('Dipinjam', $data['borrowed'])
                ->icon('heroicon-o-bookmark')
                ->color('warning'),

            Stat::make('Batal', $data['canceled'])
                ->icon('heroicon-o-x-circle')
                ->color('gray'),

            Stat::make('Terlambat', $data['overdue'])
                ->icon('heroicon-o-exclamation-triangle')
                ->color('danger'),
        ];
    }
}