<?php

namespace App\Filament\Resources\Students\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Schema;


class StudentInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextEntry::make('name')
                    ->label('Nama Siswa'),
                
                TextEntry::make('nis')
                    ->label('NIS')
                    ->formatStateUsing(fn ($state) => $state ?? '-'),
                TextEntry::make('class')
                    ->label('Kelas'),
                TextEntry::make('contact')
                    ->label('No. Telp')
                    ->placeholder('-'),
                TextEntry::make('email')
                    ->label('Email'),
                TextEntry::make('email_verified_at')
                    ->label('Email Terverifikasi Pada')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('is_active')
                    ->label('Status')
                    ->color(fn ($state) => match($state) {
                        'Aktif' => 'green',
                        'Nonaktif' => 'red',
                        default => 'primary',
                    })
                    ->badge(),
                TextEntry::make('created_at')
                    ->label('Dibuat Pada')
                    ->dateTime()
                    ->placeholder('-'),
                TextEntry::make('updated_at')
                    ->label('Terakhir Diperbarui')
                    ->dateTime()
                    ->placeholder('-'),
            ]);
    }
}
