<?php

namespace App\Filament\Resources\Students\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class StudentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                TextInput::make('nis')
                    ->required(),
                TextInput::make('class')
                    ->required(),
                TextInput::make('contact')
                    ->default(null),
                TextInput::make('email')
                    ->label('Email address')
                    ->email()
                    ->required(),
                DateTimePicker::make('email_verified_at'),
                Select::make('status')
                    ->options(['Aktif' => 'Aktif', 'Nonaktif' => 'Nonaktif'])
                    ->default('Aktif')
                    ->required(),
            ]);
    }
}
