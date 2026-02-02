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
        return 'Kirim Pengingat Manual';
    }

    protected function getTableQuery(): ?Builder
    {
        // Menyesuaikan status dengan model: 'Dipinjam'
        return Borrowing::query()
            ->with(['student', 'book'])
            ->where('status', 'Dipinjam');
    }

    protected function getHeaderActions(): array
    {
        return [];
    }
}