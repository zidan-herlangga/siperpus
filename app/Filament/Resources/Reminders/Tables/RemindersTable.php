<?php

namespace App\Filament\Resources\Reminders\Tables;

use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;

class RemindersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('borrowing.student.name')
                    ->label('Siswa')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('borrowing.book.title')
                    ->label('Buku')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('type')
                    ->label('Jenis Pengingat')
                    ->badge()
                    ->color(fn ($state) => $state === 'pre_due' ? 'warning' : 'danger'),

                TextColumn::make('sent_at')
                    ->label('Dikirim Pada')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('type')
                    ->label('Jenis Reminder')
                    ->options([
                        'pre_due' => 'H-1 Jatuh Tempo',
                        'overdue' => 'Terlambat',
                    ])
            ])
            ->recordActions([]) // biasanya reminder tidak perlu diedit
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
