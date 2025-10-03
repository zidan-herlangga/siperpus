<?php

namespace App\Filament\Resources\Books\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class BookForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('title')
                    ->required(),
                TextInput::make('author')
                    ->required(),
                TextInput::make('publisher')
                    ->required(),
                TextInput::make('year')
                    ->required(),
                TextInput::make('isbn')
                    ->default(null),
                TextInput::make('category')
                    ->required(),
                TextInput::make('shelf_code')
                    ->required(),
                TextInput::make('stock')
                    ->required()
                    ->numeric()
                    ->default(0),
            ]);
    }
}
