<?php

namespace App\Filament\Resources\Borrowings\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class BorrowingForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('student_id')
                    ->relationship('student', 'name')
                    ->required(),
                Select::make('book_id')
                    ->relationship('book', 'title')
                    ->required(),
                DatePicker::make('borrow_date')
                    ->required(),
                DatePicker::make('due_date')
                    ->required(),
                DatePicker::make('return_date'),
                TextInput::make('fine')
                    ->required()
                    ->numeric()
                    ->default(0.0),
                Select::make('status')
                    ->options(['Dipinjam' => 'Dipinjam', 'Dikembalikan' => 'Dikembalikan'])
                    ->default('Dipinjam')
                    ->required(),
            ]);
    }
}
