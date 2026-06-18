<?php

namespace App\Filament\Widgets;

use App\Models\Borrowing;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;

class BorrowedBooks extends BaseWidget
{
    protected static bool $lazy = true;
    protected static ?string $heading = 'Daftar Buku yang Sedang Dipinjam';

    protected int|string|array $columnSpan = 'full';
    protected static ?int $sort = 3;

    public function table(Table $table): Table
    {
        return $table
            ->query(
                Borrowing::query()
                    ->where('status', 'Dipinjam') // default
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
            ])
            ->filters([
                // 🔹 Filter Status
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'Dipinjam' => 'Dipinjam',
                        'Dikembalikan' => 'Dikembalikan',
                    ]),

                // 🔹 Filter Buku
                Tables\Filters\SelectFilter::make('book')
                    ->relationship('book', 'title')
                    ->searchable()
                    ->label('Buku'),

                // 🔹 Filter Overdue
                Tables\Filters\Filter::make('overdue')
                    ->label('Terlambat')
                    ->query(fn ($query) =>
                        $query->whereDate('due_date', '<', now())
                              ->where('status', 'Dipinjam')
                    ),
            ]);
    }
}