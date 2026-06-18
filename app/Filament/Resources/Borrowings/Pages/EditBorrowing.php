<?php

namespace App\Filament\Resources\Borrowings\Pages;

use App\Filament\Resources\Borrowings\BorrowingResource;
use Filament\Resources\Pages\EditRecord;
use Filament\Notifications\Notification;
use Filament\Actions\Action;

class EditBorrowing extends EditRecord
{
    protected static string $resource = BorrowingResource::class;

    protected function handleRecordUpdate(\Illuminate\Database\Eloquent\Model $record, array $data): \Illuminate\Database\Eloquent\Model
    {
        $oldStatus = $record->getOriginal('status');

        $record = parent::handleRecordUpdate($record, $data);

        $newStatus = $record->status;
        $newStatus = $newStatus instanceof \BackedEnum ? $newStatus->value : $newStatus;

        if ($oldStatus !== $newStatus) {
            $book = \App\Models\Book::find($record->book_id);
            if ($book) {
                if ($oldStatus === 'Pending' && $newStatus === 'Dipinjam') {
                    $book->decrement('stock');
                } elseif ($oldStatus === 'Dipinjam' && $newStatus === 'Dikembalikan') {
                    $book->increment('stock');
                    $record->return_date = $record->return_date ?: now();
                    $record->fine = $record->calculateFine();
                    $record->saveQuietly();


                } elseif ($oldStatus === 'Pending' && $newStatus === 'Dikembalikan') {
                    $record->return_date = $record->return_date ?: now();
                    $record->fine = 0;
                    $record->saveQuietly();
                } elseif ($oldStatus === 'Dikembalikan' && $newStatus === 'Dipinjam') {
                    $book->decrement('stock');
                }
            }
        }

        return $record;
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('recalcFine')
                ->label('Hitung Ulang Denda')
                ->requiresConfirmation()
                ->action(function () {
                    $record = $this->record;
                    $record->fine = $record->calculateFine();
                    $record->save();

                    Notification::make()
                        ->title('Denda berhasil dihitung ulang')
                        ->success()
                        ->send();

                    $this->redirect(request()->header('Referer'));
                }),
        ];
    }
}
