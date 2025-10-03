<?php

namespace App\Filament\Resources\Borrowings\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;

class BorrowingInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('student.name')
                    ->label('Student'),
                TextEntry::make('book.title')
                    ->label('Book'),
                TextEntry::make('borrow_date')
                    ->date(),
                TextEntry::make('due_date')
                    ->date(),
                TextEntry::make('return_date')
                    ->date()
                    ->placeholder('-'),
                TextEntry::make('fine')
                    ->numeric(),
                TextEntry::make('status')
                    ->badge(),
                TextEntry::make('created_at')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
