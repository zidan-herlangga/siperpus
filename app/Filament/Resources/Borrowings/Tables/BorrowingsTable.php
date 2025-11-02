<?php

namespace App\Filament\Resources\Borrowings\Tables;

use Filament\Tables\Table;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;

class BorrowingsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('student.name')
                    ->label('Nama Siswa')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('book.title')
                    ->label('Judul Buku')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('borrow_date')
                    ->label('Tanggal Pinjam')
                    ->date('d M Y')
                    ->sortable(),

                TextColumn::make('due_date')
                    ->label('Jatuh Tempo')
                    ->date('d M Y')
                    ->sortable(),

                BadgeColumn::make('status')
                    ->label('Status')
                    ->sortable()
                    ->formatStateUsing(function ($state) {
                        return match($state) {
                            'Pending' => 'Pending',
                            'Dipinjam' => 'Dipinjam',
                            'Dikembalikan' => 'Dikembalikan',
                            default => $state,
                        };
                    })

                    ->colors([
                        'gray' => fn ($state) => $state === 'Pending',
                        'warning' => fn ($state) => $state === 'Dipinjam',
                        'success' => fn ($state) => $state === 'Dikembalikan',
                    ]),

                TextColumn::make('fine_amount')
                    ->label('Denda')
                    ->getStateUsing(function ($record) {
                        if ($record->status === 'Pending') {
                            return 0; // belum aktif, belum bisa kena denda
                        }
                        return $record->fine_amount;
                    })
                    ->formatStateUsing(fn ($state) => number_format((int)$state, 0, ',', '.'))
                    ->prefix('Rp ')
                    ->sortable(),
            ])
            ->filters([
                // contoh filter: hanya overdue / only returned
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'Pending' => 'Pending',
                        'Dipinjam' => 'Dipinjam',
                        'Dikembalikan' => 'Dikembalikan',
                    ]),
                Tables\Filters\Filter::make('overdue')
                    ->label('Hanya Terlambat')
                    ->query(fn ($query) => $query->where('status', 'Dipinjam')->whereDate('due_date', '<', now()->toDateString())),
            ])
            ->recordActions([
                // actions handled by resource pages
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
