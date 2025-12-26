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
                        'Pending' => 'gray',
                        'Dipinjam' => 'warning',
                        'Dikembalikan' => 'success',
                        'Batal' => 'danger',
                    }),
                
                Tables\Columns\TextColumn::make('fine')
                    ->label('Denda')
                    ->getStateUsing(function ($record) {
                        $now = now();
                        
                        // Jika status masih Pending, belum ada denda
                        if ($record->status === 'Pending') {
                            return 0;
                        }
                        
                        // Jika sudah dikembalikan → gunakan denda final dari DB
                        if ($record->status === 'Dikembalikan') {
                            return $record->fine;
                        }

                        // Jika status Batal, tidak ada denda
                        if ($record->status === 'Batal') {
                            return 0;
                        }
                
                        // Jika belum dikembalikan & sudah telat → hitung berjalan
                        if ($now->isAfter($record->due_date)) {
                            $daysLate = $record->due_date->diffInDays($now);
                            return $daysLate * 1000;
                        }
                
                        return 0;
                    })
                    ->money('IDR')
                    ->prefix('Rp ')
                    ->formatStateUsing(fn ($state) => number_format($state, 0, ',', '.'))
                    ->sortable()
            ])
            ->headerActions([
                // Tombol untuk ekspor ke Excel/CSV
                FilamentExportHeaderAction::make('export')
                    ->label('Ekspor Laporan'),
            ]);
    }
}