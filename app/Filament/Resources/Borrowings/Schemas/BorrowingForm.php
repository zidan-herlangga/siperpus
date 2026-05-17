<?php

namespace App\Filament\Resources\Borrowings\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;
use Illuminate\Support\Carbon;
use App\Enums\BorrowingStatus;

class BorrowingForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                // 🔹 Pilih Siswa
                Select::make('student_id')
                    ->label('Nama Siswa')
                    ->relationship('student', 'name')
                    ->searchable()
                    ->required(),

                // 🔹 Pilih Buku
                Select::make('book_id')
                    ->label('Judul Buku')
                    ->relationship('book', 'title')
                    ->searchable()
                    ->required(),

                // 🔹 Tanggal Pinjam
                DatePicker::make('borrow_date')
                    ->label('Tanggal Pinjam')
                    ->required()
                    ->live()
                    ->afterStateUpdated(function ($state, callable $set) {
                        if ($state) {
                            $set('due_date', Carbon::parse($state)->addDays((int) config('library.borrow_duration_days', 7)));
                        }
                    }),

                // 🔹 Jatuh Tempo (auto)
                DatePicker::make('due_date')
                    ->label('Jatuh Tempo')
                    ->required(),

                // 🔹 Tanggal Kembali
                DatePicker::make('return_date')
                    ->label('Tanggal Kembali')
                    ->live(),

                // 🔹 Denda (readonly)
                TextInput::make('fine')
                    ->label('Denda')
                    ->numeric()
                    ->prefix('Rp')
                    ->default(0)
                    ->disabled()
                    ->dehydrated(),
                

                // 🔹 Status (readonly)
                Select::make('status')
                    ->label('Status')
                    ->options(BorrowingStatus::class)
                    ->default(BorrowingStatus::Pending)
                    ->dehydrated(),
            ]);
    }
}