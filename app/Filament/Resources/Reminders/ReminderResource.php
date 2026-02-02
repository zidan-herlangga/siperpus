<?php

namespace App\Filament\Resources\Reminders;

use App\Filament\Resources\Reminders\Pages\ListReminders;
use App\Filament\Resources\Reminders\Tables\RemindersTable;
use App\Models\Reminder;
use Filament\Resources\Resource;
use Filament\Tables\Table;

use UnitEnum;
use BackedEnum;

class ReminderResource extends Resource
{
    protected static ?string $model = Reminder::class;
    protected static ?string $label = 'List Pengingat';
    protected static UnitEnum|string|null $navigationGroup = 'Manajemen Peminjaman';
    protected static BackedEnum|string|null $navigationIcon = 'heroicon-o-clock';

    public static function table(Table $table): Table
    {
        // Menggunakan konfigurasi dari file RemindersTable
        return RemindersTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListReminders::route('/'),
        ];
    }
}