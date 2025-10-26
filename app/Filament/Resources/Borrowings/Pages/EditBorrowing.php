<?php

namespace App\Filament\Resources\Borrowings\Pages;

use App\Filament\Resources\Borrowings\BorrowingResource;
use Filament\Resources\Pages\EditRecord;
use Filament\Notifications\Notification;
use Filament\Actions\Action;

class EditBorrowing extends EditRecord
{
    protected static string $resource = BorrowingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('recalcFine')
                ->label('Hitung Ulang Denda')
                ->requiresConfirmation()
                ->action(function () {
                    $record = $this->record; // instance Eloquent
                    $record->fine = $record->calculateFine();
                    $record->save();

                    Notification::make()
                        ->title('Denda berhasil dihitung ulang')
                        ->success()
                        ->send();
                }),
        ];
    }
}
