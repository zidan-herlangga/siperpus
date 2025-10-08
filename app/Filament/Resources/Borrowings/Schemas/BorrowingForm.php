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
                    ->label('Nama Siswa')
                    ->searchable()
                    ->live()
                    ->relationship('student', 'name')
                    ->required(),
                Select::make('book_id')
                    ->label('Judul Buku')
                    ->searchable()
                    ->relationship('book', 'title')
                    ->required(),
                DatePicker::make('borrow_date')
                    ->label('Tanggal Pinjam')
                    ->live()
                    ->required(),
                DatePicker::make('due_date')
                    ->label('Jatuh Tempo')
                    ->required(),
                DatePicker::make('return_date')
                    ->label('Tanggal Kembali')
                    ->live(),
                TextInput::make('fine')
                    ->label('Denda')
                    ->required()
                    ->numeric()
                    ->default(0.0),
                Select::make('status')
                    ->label('Status')
                    ->options(['Dipinjam' => 'Dipinjam', 'Dikembalikan' => 'Dikembalikan'])
                    ->default('Dipinjam')
                    ->required(),
            ]);
    }
}
