<?php

namespace App\Filament\Resources\Reminders\Pages;

use App\Filament\Resources\Reminders\ReminderResource;
use App\Models\Borrowing;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class ListReminders extends ListRecords
{
    protected static string $resource = ReminderResource::class;

    public function getTitle(): string
    {
        return 'Kirim Pengingat';
    }

    protected function getTableQuery(): ?Builder
    {
        return Borrowing::query()
            ->with(['student', 'book'])
            ->needReminder()
            ->orderByRaw("
                CASE 
                    WHEN due_date < NOW() THEN 0
                    ELSE 1
                END
            ")
            ->orderBy('due_date', 'asc');
    }

    protected function getHeaderActions(): array
    {
        return [];
    }
}