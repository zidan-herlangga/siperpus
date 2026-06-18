<?php

namespace App\Filament\Resources\Borrowings\Pages;

use App\Filament\Resources\Borrowings\BorrowingResource;
use Filament\Resources\Pages\CreateRecord;

class CreateBorrowing extends CreateRecord
{
    protected static string $resource = BorrowingResource::class;

    protected function afterCreate(): void
    {
        $record = $this->record;

        $status = $record->status;
        $status = $status instanceof \BackedEnum ? $status->value : $status;

        if ($status === 'Dipinjam') {
            $book = \App\Models\Book::find($record->book_id);
            if ($book) {
                $book->decrement('stock');
            }
        }
    }
}
