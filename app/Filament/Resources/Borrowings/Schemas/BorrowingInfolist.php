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
                    ->money('IDR')
                    ->numeric(),
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
