<?php

namespace App\Filament\Widgets;

use App\Models\Borrowing;
use App\Enums\BorrowingStatus;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use AlperenErsoy\FilamentExport\Actions\FilamentExportHeaderAction;

class AllBorrowingsReport extends BaseWidget
{
    protected static ?string $heading = 'Laporan Semua Aktivitas Peminjaman';

    protected static ?int $sort = 4;
    protected int|string|array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Borrowing::query()
                    ->with(['student', 'book']) // 🔥 cegah N+1 query
                    ->latest()
            )
            ->columns([
                Tables\Columns\TextColumn::make('student.name')
                    ->label('Nama Siswa')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('book.title')
                    ->label('Judul Buku')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('borrow_date')
                    ->label('Tanggal Pinjam')
                    ->date('d M Y')
                    ->sortable(),

                Tables\Columns\TextColumn::make('return_date')
                    ->label('Tanggal Kembali')
                    ->date('d M Y')
                    ->placeholder('Belum dikembalikan')
                    ->sortable(),

                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Pending' => 'gray',
                        'Dipinjam' => 'warning',
                        'Dikembalikan' => 'success',
                        'Batal' => 'gray',
                    }),

                Tables\Columns\TextColumn::make('fine')
                    ->label('Denda')
                    ->getStateUsing(function ($record) {

                        // 🔒 Guard null
                        if (!$record->due_date) {
                            return 0;
                        }

                        // Gunakan DB sebagai source of truth
                        if ($record->status === 'Dikembalikan') {
                            return $record->fine ?? 0;
                        }

                        if (!in_array($record->status, ['Dipinjam'])) {
                            return 0;
                        }

                        $now = now();

                        if ($now->lessThanOrEqualTo($record->due_date)) {
                            return 0;
                        }

                        $daysLate = $record->due_date->diffInDays($now);

                        return $daysLate * (int) config('library.fine_per_day', 1000);
                    })
                    ->formatStateUsing(fn ($state) => 'Rp ' . number_format($state, 0, ',', '.'))
                    ->sortable(),
            ])
            ->headerActions([
                FilamentExportHeaderAction::make('export')
                    ->label('Ekspor Laporan'),
            ]);
    }
}