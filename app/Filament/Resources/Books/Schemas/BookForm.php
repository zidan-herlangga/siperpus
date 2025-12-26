<?php

namespace App\Filament\Resources\Books\Schemas;

use Filament\Forms\Components\Field;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Section;
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
                TextInput::make('author')
                    ->label('Pengarang')
                    ->required(),
                FileUpload::make('cover_image')
                    ->label('Sampul Buku')
                    ->image()
                    ->directory('book-covers')
                    ->visibility('public')
                    ->nullable()
                    ->columnSpan(2),
                TextInput::make('publisher')
                    ->label('Penerbit')
                    ->required(),
                TextInput::make('category')
                    ->label('Kategori')
                    ->required(),
                TextInput::make('year')
                    ->label('Tahun Terbit')
                    ->required(),
                TextInput::make('isbn')
                    ->label('ISBN')
                    ->default(null),
                TextInput::make('stock')
                    ->label('Stok')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('shelf_code')
                    ->label('Kode Rak')
                    ->required(),
                RichEditor::make('synopsis')
                    ->label('Sinopsis')
                    ->toolbarButtons([
                        ['bold', 'italic', 'link', 'h2', 'h3'],
                        ['grid', 'undo', 'redo'],
                    ])
                    ->nullable()
                    ->columnSpan(2),
            ]);
    }
}
