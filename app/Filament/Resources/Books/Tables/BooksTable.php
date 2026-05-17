<?php

namespace App\Filament\Resources\Books\Tables;

use AlperenErsoy\FilamentExport\Actions\FilamentExportHeaderAction;
use App\Models\Book;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class BooksTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn (Builder $query) => $query->with(['category']))
            ->columns([
                ImageColumn::make('cover_image')
                    ->label('Sampul')
                    ->height(70),

                TextColumn::make('title')
                    ->label('Judul Buku')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                TextColumn::make('author')
                    ->label('Pengarang')
                    ->searchable(),

                TextColumn::make('category.name')
                    ->label('Kategori')
                    ->badge()
                    ->color('info'),

                TextColumn::make('year')
                    ->label('Tahun')
                    ->sortable(),

                TextColumn::make('stock')
                    ->label('Stok')
                    ->sortable()
                    ->color(fn ($state) => match (true) {
                        $state == 0 => 'danger',
                        $state < 5 => 'warning',
                        default => 'success',
                    }),

                TextColumn::make('condition')
                    ->label('Kondisi')
                    ->badge()
                    ->color(fn ($state) => match ($state) {
                        'Baik' => 'success',
                        'Rusak' => 'warning',
                        'Hilang' => 'danger',
                        default => 'gray',
                    }),

                TextColumn::make('shelf_code')
                    ->label('Rak')
                    ->toggleable(),

                TextColumn::make('isbn')
                    ->label('ISBN')
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('created_at')
                    ->dateTime()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])

            ->filters([
                // 🔹 Filter kategori
                SelectFilter::make('category_id')
                    ->label('Kategori')
                    ->relationship('category', 'name')
                    ->searchable(),

                // 🔹 Filter kondisi
                SelectFilter::make('condition')
                    ->label('Kondisi')
                    ->options([
                        'Baik' => 'Baik',
                        'Rusak' => 'Rusak',
                        'Hilang' => 'Hilang',
                    ]),

                // 🔹 Filter stok
                SelectFilter::make('stock_status')
                    ->label('Status Stok')
                    ->options([
                        'empty' => 'Habis',
                        'low' => 'Sedikit',
                        'available' => 'Tersedia',
                    ])
                    ->query(function ($query, $state) {
                        return match ($state) {
                            'empty' => $query->where('stock', 0),
                            'low' => $query->whereBetween('stock', [1, 4]),
                            'available' => $query->where('stock', '>=', 5),
                            default => $query,
                        };
                    }),
            ])

            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])

            ->toolbarActions([
                FilamentExportHeaderAction::make('export')
                    ->label('Ekspor Buku'),
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}