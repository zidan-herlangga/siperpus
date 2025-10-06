<?php

namespace App\Filament\Resources\Books\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class BookInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('title')
                    ->label('Judul Buku'),
                TextEntry::make('author')
                    ->label('Pengarang'),
                TextEntry::make('publisher')
                    ->label('Penerbit'),
                TextEntry::make('year')
                    ->label('Tahun Terbit'),
                TextEntry::make('isbn')
                    ->label('ISBN')
                    ->placeholder('-'),
                TextEntry::make('category')
                    ->label('Kategori'),
                TextEntry::make('shelf_code')
                    ->label('Kode Rak'),
                TextEntry::make('stock')
                    ->label('Stok')
                    ->numeric(),
                TextEntry::make('created_at')
                    ->label('Dibuat Pada')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->label('Terakhir Diperbarui')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
