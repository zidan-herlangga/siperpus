<?php

namespace App\Filament\Widgets;

use App\Models\Borrowing;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use AlperenErsoy\FilamentExport\Actions\FilamentExportHeaderAction;

class AllBorrowingsReport extends BaseWidget
{
    protected static ?string $heading = 'Laporan Semua Aktivitas Peminjaman';

    // Atur posisi widget ini di paling bawah
    protected static ?int $sort = 4;
    
    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            // Mengambil semua data peminjaman, diurutkan dari yang terbaru
            ->query(Borrowing::query()->latest())
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
                    ->sortable()
                    ->placeholder('Belum dikembalikan'),

                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Dipinjam' => 'warning',
                        'Dikembalikan' => 'success',
                    }),
                
                Tables\Columns\TextColumn::make('fine')
                    ->label('Denda')
                    ->getStateUsing(function ($record) {
                        // Hitung denda otomatis
                        $now = now();
                        if ($now->isAfter($record->due_date) && $record->status === 'Dipinjam') {
                            $daysLate = $record->due_date->diffInDays($now);
                            return $daysLate * 1000;
                        }
                        return 0;
                    })
                    ->money('IDR')
                    ->prefix('Rp ')
                    ->formatStateUsing(fn (int $state): string => number_format($state, 0, ',', '.'))
                    ->sortable(),
            ])
            ->headerActions([
                // Tombol untuk ekspor ke Excel/CSV
                FilamentExportHeaderAction::make('export')
                    ->label('Ekspor Laporan'),
            ]);
    }
}