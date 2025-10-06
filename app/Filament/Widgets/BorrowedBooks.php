<?php

namespace App\Filament\Widgets;

use App\Filament\Resources\BorrowingResource; // <-- TAMBAHKAN INI
use App\Models\Borrowing;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class BorrowedBooks extends BaseWidget
{
    protected static ?string $heading = 'Daftar Buku yang Sedang Dipinjam';
    
    protected int | string | array $columnSpan = 'full';
    protected static ?int $sort = 2;
    public function table(Table $table): Table
    {
        return $table
            ->query(
                Borrowing::query()
                    ->where('status', 'Dipinjam')
                    ->latest('borrow_date')
            )
            ->columns([
                Tables\Columns\TextColumn::make('book.title')
                    ->label('Judul Buku')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('student.name')
                    ->label('Peminjam')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('borrow_date')
                    ->label('Tanggal Pinjam')
                    ->date('d M Y')
                    ->sortable(),
                
                Tables\Columns\TextColumn::make('due_date')
                    ->label('Jatuh Tempo')
                    ->date('d M Y')
                    ->sortable()
                    ->color(fn ($record) => $record->due_date->isPast() ? 'danger' : 'gray'),
            ]);
            // ->actions([
            //     Action::make('view')
            //         ->label('Lihat')
            //         // Panggil resource secara langsung tanpa path lengkap
            //         ->url(fn (Borrowing $record): string => BorrowingResource::getUrl('edit', ['record' => $record]))
            //         ->icon('heroicon-o-eye'),
            // ]);
    }
}