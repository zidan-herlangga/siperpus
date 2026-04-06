<?php

namespace App\Filament\Resources\Students\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Filament\Tables\Columns\ImageColumn;

class StudentsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('avatar')
                    ->label('Foto')
                    ->circular(),
                TextColumn::make('name')
                    ->label('Nama Siswa')
                    ->searchable(),
                TextColumn::make('nis')
                    ->label('NIS')
                    ->icon(Heroicon::Identification)
                    ->iconColor('primary')
                    ->searchable(),
                TextColumn::make('class')
                    ->label('Kelas')
                    ->searchable(),
                TextColumn::make('contact')
                    ->label('No. Telp')
                    ->searchable(),
                TextColumn::make('email')
                    ->label('Email')
                    ->icon(Heroicon::Envelope)
                    ->iconColor('primary')
                    ->searchable(),
                TextColumn::make('email_verified_at')
                    ->label('Email Terverifikasi Pada')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('is_active')
                    ->label('Status')
                    ->badge(),
                TextColumn::make('created_at')
                    ->label('Dibuat Pada')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->label('Terakhir Diperbarui')
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
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
