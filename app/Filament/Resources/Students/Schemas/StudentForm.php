<?php

namespace App\Filament\Resources\Students\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Schemas\Schema;
use App\Enums\StudentStatus;

class StudentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                // 🔹 Nama
                TextInput::make('name')
                    ->label('Nama Siswa')
                    ->required()
                    ->maxLength(100),

                // 🔹 NIS
                TextInput::make('nis')
                    ->label('NIS')
                    ->required()
                    ->numeric()
                    ->unique(
                        table: 'students',
                        column: 'nis',
                        ignorable: fn ($record) => $record
                    )
                    ->validationMessages([
                        'unique' => 'NIS sudah terdaftar.',
                    ]),

                // 🔹 Kelas
                TextInput::make('class')
                    ->label('Kelas')
                    ->required()
                    ->maxLength(20),

                // 🔹 Kontak
                TextInput::make('contact')
                    ->label('No. Telp')
                    ->tel()
                    ->regex('/^[0-9]{10,15}$/') // validasi nyata
                    ->placeholder('08xxxxxxxxxx')
                    ->nullable(),

                // 🔹 Email (readonly / sistem)
                TextInput::make('email')
                    ->label('Email')
                    ->email()
                    ->disabled()
                    ->dehydrated(false), // ❗ tidak disimpan dari form

                // 🔹 Status
                Select::make('is_active')
                    ->label('Status')
                    ->options([
                        StudentStatus::Aktif->value => 'Aktif',
                        StudentStatus::Nonaktif->value => 'Nonaktif',
                    ])
                    ->default(StudentStatus::Aktif->value)
                    ->required(),
            ]);
    }
}