<?php

namespace App\Filament\Resources\Borrowings\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class BorrowingsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('student.name')
        ->label('Nama Siswa')
        ->searchable(),

            TextColumn::make('book.title')
                ->label('Judul Buku')
                ->searchable(),

            TextColumn::make('borrow_date')
                ->label('Tanggal Pinjam')
                ->date('d M Y') // Format tanggal Indonesia
                ->sortable(),

            TextColumn::make('due_date')
                ->label('Jatuh Tempo')
                ->date('d M Y')
                ->sortable(),

            TextColumn::make('return_date')
                ->label('Tanggal Kembali')
                ->date('d M Y')
                ->sortable()
                ->placeholder('Belum dikembalikan'), // Teks jika data kosong

            TextColumn::make('fine')
                ->label('Denda')
                ->money('IDR') // Format mata uang Rupiah
                ->sortable(),

            TextColumn::make('status')
                ->label('Status')
                ->badge() // Tampilan menjadi badge/pil
                ->color(fn (string $state): string => match ($state) {
                    'Dipinjam' => 'warning',
                    'Dikembalikan' => 'success',
                }),

            TextColumn::make('created_at')
                ->label('Dibuat Pada')
                ->dateTime('d M Y H:i')
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true),

            TextColumn::make('updated_at')
                ->label('Diperbarui Pada')
                ->dateTime('d M Y H:i')
                ->sortable()
                ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
