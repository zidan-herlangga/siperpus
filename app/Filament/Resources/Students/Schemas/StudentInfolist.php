<?php

namespace App\Filament\Resources\Students\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\ImageEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class StudentInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                // 🔹 Profil
                Section::make('Profil Siswa')
                    ->columns(2)
                    ->schema([

                        ImageEntry::make('avatar')
                            ->label('Foto')
                            ->circular()
                            ->defaultImageUrl(url('/images/default-avatar.png')),

                        TextEntry::make('name')
                            ->label('Nama')
                            ->weight('bold'),

                        TextEntry::make('nis')
                            ->label('NIS')
                            ->placeholder('-'),

                        TextEntry::make('class')
                            ->label('Kelas')
                            ->badge()
                            ->color('info'),
                    ]),

                // 🔹 Kontak
                Section::make('Kontak')
                    ->columns(2)
                    ->schema([

                        TextEntry::make('contact')
                            ->label('No. Telp')
                            ->formatStateUsing(fn ($state) => $state ? '+62 ' . $state : '-'),

                        TextEntry::make('email')
                            ->label('Email'),
                    ]),

                // 🔹 Status
                Section::make('Status')
                    ->columns(2)
                    ->schema([

                        TextEntry::make('email_verified_at')
                            ->label('Verifikasi Email')
                            ->badge()
                            ->formatStateUsing(fn ($state) => $state ? 'Terverifikasi' : 'Belum')
                            ->color(fn ($state) => $state ? 'success' : 'warning'),

                        TextEntry::make('is_active')
                            ->label('Status Akun')
                            ->badge()
                            ->formatStateUsing(fn ($state) => $state ? 'Aktif' : 'Nonaktif')
                            ->color(fn ($state) => $state ? 'success' : 'danger'),
                    ]),

                // 🔹 Metadata
                Section::make('Metadata')
                    ->columns(2)
                    ->schema([

                        TextEntry::make('created_at')
                            ->label('Dibuat')
                            ->dateTime()
                            ->placeholder('-'),

                        TextEntry::make('updated_at')
                            ->label('Diperbarui')
                            ->dateTime()
                            ->placeholder('-'),
                    ]),
            ]);
    }
}