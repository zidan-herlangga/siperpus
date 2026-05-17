<?php

namespace App\Filament\Resources\Students\Tables;

use AlperenErsoy\FilamentExport\Actions\FilamentExportHeaderAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Table;

class StudentsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([

                // 🔹 Avatar
                ImageColumn::make('avatar')
                    ->label('Foto')
                    ->circular()
                    ->defaultImageUrl(url('/images/default-avatar.png')),

                // 🔹 Nama
                TextColumn::make('name')
                    ->label('Nama Siswa')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                // 🔹 NIS
                TextColumn::make('nis')
                    ->label('NIS')
                    ->icon(Heroicon::Identification)
                    ->iconColor('primary')
                    ->searchable(),

                // 🔹 Kelas
                TextColumn::make('class')
                    ->label('Kelas')
                    ->badge()
                    ->color('info')
                    ->searchable(),

                // 🔹 Kontak
                TextColumn::make('contact')
                    ->label('No. Telp')
                    ->formatStateUsing(fn ($state) => $state ? '+62 ' . $state : '-')
                    ->icon(Heroicon::Phone)
                    ->iconColor('success'),

                // 🔹 Email
                TextColumn::make('email')
                    ->label('Email')
                    ->icon(Heroicon::Envelope)
                    ->iconColor('primary')
                    ->searchable(),

                // 🔹 Verifikasi Email
                TextColumn::make('email_verified_at')
                    ->label('Email')
                    ->badge()
                    ->formatStateUsing(fn ($state) => $state ? 'Terverifikasi' : 'Belum')
                    ->color(fn ($state) => $state ? 'success' : 'warning'),

                // 🔹 Status Aktif
                TextColumn::make('is_active')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn ($state) => $state ? 'Aktif' : 'Nonaktif')
                    ->color(fn ($state) => $state ? 'success' : 'danger'),

                // 🔹 Created
                TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),

                // 🔹 Updated
                TextColumn::make('updated_at')
                    ->label('Diperbarui')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])

            ->filters([
                //
            ])

            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
            ])

            ->toolbarActions([
                FilamentExportHeaderAction::make('export')
                    ->label('Ekspor Siswa'),
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}