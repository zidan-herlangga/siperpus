<?php

namespace App\Filament\Resources\Books\Schemas;

use Filament\Forms\Components\Field;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Filament\Schemas\Schema;
use File;

class BookForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->label('Judul Buku')
                    ->required(),
                FileUpload::make('cover_image')
                    ->label('Sampul Buku')
                    ->image()
                    ->directory('book-covers')
                    ->visibility('public')
                    ->nullable(),
                TextInput::make('author')
                    ->label('Pengarang')
                    ->required(),
                TextInput::make('publisher')
                    ->label('Penerbit')
                    ->required(),
                TextInput::make('year')
                    ->label('Tahun Terbit')
                    ->required(),
                TextInput::make('isbn')
                    ->label('ISBN')
                    ->default(null),
                TextInput::make('category')
                    ->label('Kategori')
                    ->required(),
                TextInput::make('synopsis')
                    ->label('Sinopsis')
                    ->nullable(),
                TextInput::make('shelf_code')
                    ->label('Kode Rak')
                    ->required(),
                TextInput::make('stock')
                    ->label('Stok')
                    ->required()
                    ->numeric()
                    ->default(0),
            ]);
    }
}
