<?php

namespace App\Filament\Resources\Students\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

use App\Enums\StatusAktif;

class StudentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Nama Siswa')
                    ->required(),
                TextInput::make('nis')
                    ->label('NIS')
                    ->required(),
                TextInput::make('class')
                    ->label('Kelas')
                    ->required(),
                TextInput::make('contact')
                    ->label('No. Telp')
                    ->tel()
                    ->default(null),
                TextInput::make('email')
                    ->label('Email')
                    ->disabled()
                    ->email()
                    ->required(),
                DateTimePicker::make('email_verified_at')
                    ->label('Email Terverifikasi Pada')
                    ->placeholder('-')
                    ->default(null),
                Select::make('is_active')
                    ->label('Status')
                    ->options([
                        StatusAktif::Aktif->value => 'Aktif',
                        StatusAktif::Nonaktif->value => 'Nonaktif',
                    ])
                    ->default('Aktif')
                    ->required(),
            ]);
    }
}
