<?php

namespace App\Filament\Resources\Books\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\ImageEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class BookInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                // 🔹 Informasi Utama
                Section::make('Informasi Buku')
                    ->columns(2)
                    ->schema([

                        TextEntry::make('title')
                            ->label('Judul Buku')
                            ->weight('bold'),

                        TextEntry::make('author')
                            ->label('Pengarang'),

                        TextEntry::make('category.name')
                            ->label('Kategori')
                            ->badge()
                            ->color('info'),

                        TextEntry::make('publisher')
                            ->label('Penerbit'),

                        TextEntry::make('year')
                            ->label('Tahun Terbit'),

                        TextEntry::make('isbn')
                            ->label('ISBN')
                            ->placeholder('-'),
                    ]),

                // 🔹 Media
                Section::make('Sampul Buku')
                    ->schema([

                        ImageEntry::make('cover_image')
                            ->label('Sampul')
                            ->height(200)
                            ->defaultImageUrl(url('/images/no-image.png')),
                    ]),

                // 🔹 Stok & Lokasi
                Section::make('Stok & Lokasi')
                    ->columns(2)
                    ->schema([

                        TextEntry::make('stock')
                            ->label('Stok')
                            ->badge()
                            ->color(fn ($state) => match (true) {
                                $state == 0 => 'danger',
                                $state < 5 => 'warning',
                                default => 'success',
                            }),

                        TextEntry::make('shelf_code')
                            ->label('Kode Rak'),
                    ]),

                // 🔹 Deskripsi
                Section::make('Deskripsi')
                    ->schema([

                        TextEntry::make('synopsis')
                            ->label('Sinopsis')
                            ->html()
                            ->placeholder('-'),
                    ]),

                // 🔹 Metadata
                Section::make('Metadata')
                    ->columns(2)
                    ->schema([

                        TextEntry::make('created_at')
                            ->label('Dibuat')
                            ->dateTime()
                            ->placeholder('-'),

                        TextEntry::make('updated_at')
                            ->label('Diperbarui')
                            ->dateTime()
                            ->placeholder('-'),
                    ]),
            ]);
    }
}