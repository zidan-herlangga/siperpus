<?php

namespace App\Filament\Resources\Borrowings\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class BorrowingInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('student.name')
                    ->label('Nama Siswa'),
                TextEntry::make('book.title')
                    ->label('Judul Buku'),
                TextEntry::make('borrow_date')
                    ->label('Tanggal Pinjam')
                    ->date(),
                TextEntry::make('due_date')
                    ->label('Jatuh Tempo')
                    ->date(),
                TextEntry::make('return_date')
                    ->label('Tanggal Kembali')
                    ->date()
                    ->placeholder('-'),
                TextEntry::make('fine')
                    ->label('Denda')
                    ->state(function ($record) {
                        if (!$record->due_date) return 0;

                        // Jika sudah dikembalikan, gunakan nilai tetap dari DB
                        if ($record->status === 'Dikembalikan') {
                            return $record->fine ?? 0;
                        }

                        // Hitung denda hanya jika sudah lewat jatuh tempo
                        if (now()->gt($record->due_date)) {
                            $daysLate = $record->due_date->diffInDays(now()); // ← arah dibalik
                            return $daysLate * 1000;
                        }

                        return 0;
                    })
                    ->formatStateUsing(fn ($state) => 'Rp ' . number_format($state, 0, ',', '.')),
                TextEntry::make('status')
                    ->label('Status')
                    ->badge(),
                TextEntry::make('created_at')
                    ->label('Dibuat Pada')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->label('Diperbarui Pada')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
