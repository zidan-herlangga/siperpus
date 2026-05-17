<?php

namespace App\Filament\Resources\Books\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\RichEditor;
use Filament\Schemas\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Schemas\Schema;

class BookForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                // 🔹 Section Informasi Utama
                Section::make('Informasi Buku')
                    ->columns(2)
                    ->schema([

                        TextInput::make('title')
                            ->label('Judul Buku')
                            ->required()
                            ->maxLength(255),

                        TextInput::make('author')
                            ->label('Pengarang')
                            ->required(),

                        Select::make('category_id')
                            ->label('Kategori')
                            ->relationship('category', 'name')
                            ->searchable()
                            ->required()
                            ->createOptionForm([
                                TextInput::make('name')
                                    ->label('Nama Kategori')
                                    ->required(),
                            ]),

                        TextInput::make('publisher')
                            ->label('Penerbit')
                            ->required(),

                        TextInput::make('year')
                            ->label('Tahun Terbit')
                            ->numeric()
                            ->minValue(1900)
                            ->maxValue(now()->year)
                            ->required(),

                        TextInput::make('isbn')
                            ->label('ISBN')
                            ->maxLength(20)
                            ->unique(
                                table: 'books',
                                column: 'isbn',
                                ignorable: fn ($record) => $record
                            )
                            ->rule('regex:/^(?:\d{10}|\d{13}|\d{17}[\dX])?$/')
                            ->validationMessages([
                                'unique' => 'ISBN sudah terdaftar.',
                                'regex' => 'Format ISBN tidak valid (10 atau 13 digit).',
                            ])
                            ->nullable(),
                    ]),

                // 🔹 Section Stok & Lokasi
                Section::make('Stok & Lokasi')
                    ->columns(3)
                    ->schema([

                        TextInput::make('stock')
                            ->label('Stok')
                            ->numeric()
                            ->default(0)
                            ->minValue(0)
                            ->required(),

                        Select::make('condition')
                            ->label('Kondisi')
                            ->options([
                                'Baik' => 'Baik',
                                'Rusak' => 'Rusak',
                                'Hilang' => 'Hilang',
                            ])
                            ->default('Baik')
                            ->required(),

                        TextInput::make('shelf_code')
                            ->label('Kode Rak')
                            ->required(),
                    ]),

                // 🔹 Section Media
                Section::make('Media')
                    ->schema([

                        FileUpload::make('cover_image')
                            ->label('Sampul Buku')
                            ->image()
                            ->directory('book-covers')
                            ->visibility('public')
                            ->imageResizeMode('cover')
                            ->imageResizeTargetWidth(300)
                            ->imageResizeTargetHeight(400)
                            ->maxSize(2048)
                            ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp', 'image/gif'])
                            ->nullable(),
                    ]),

                // 🔹 Section Deskripsi
                Section::make('Deskripsi')
                    ->schema([

                        RichEditor::make('synopsis')
                            ->label('Sinopsis')
                            ->toolbarButtons([
                                ['bold', 'italic', 'link'],
                                ['h2', 'h3'],
                                ['undo', 'redo'],
                            ])
                            ->columnSpanFull()
                            ->nullable(),
                    ]),
            ]);
    }
}