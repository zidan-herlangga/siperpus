<?php

namespace App\Filament\Widgets;

use App\Models\Book;
use App\Models\Borrowing;
use App\Models\Student;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class LibraryStatsOverview extends BaseWidget
{
    protected static ?int $sort = 1;
    protected function getStats(): array
    {
        return [
            // Kartu 1: Total Judul Buku
            Stat::make('Total Judul Buku', Book::count())
                ->description('Jumlah semua judul buku di perpustakaan')
                ->icon('heroicon-o-book-open')
                ->color('success'),

            // Kartu 2: Total Siswa Terdaftar
            Stat::make('Total Siswa Terdaftar', Student::count())
                ->description('Jumlah siswa yang memiliki akun')
                ->icon('heroicon-o-users')
                ->color('info'),

            // Kartu 3: Buku yang Pending
            Stat::make('Buku Sedang Pending', Borrowing::where('status', 'Pending')->count())
                ->description('Jumlah buku yang pending')
                ->icon('heroicon-o-arrows-right-left')
                ->color('gray'),
            
            // Kartu 4: Buku Sedang Dipinjam
            Stat::make('Buku Sedang Dipinjam', Borrowing::where('status', 'Dipinjam')->count())
                ->description('Jumlah buku yang belum dikembalikan')
                ->icon('heroicon-o-arrows-right-left')
                ->color('warning'),

            // Kartu 5: Buku Terlambat
            Stat::make('Buku Terlambat', Borrowing::where('status', 'Dipinjam')->where('due_date', '<', now())->count())
                ->description('Jumlah buku yang melewati jatuh tempo')
                ->icon('heroicon-o-exclamation-triangle')
                ->color('danger'),
        ];
    }
}