<?php

namespace App\Filament\Resources\Borrowings\Schemas;

use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Carbon;

class BorrowingInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                Section::make('Informasi Peminjaman')
                    ->columns(2)
                    ->schema([

                        TextEntry::make('student.name')
                            ->label('Nama Siswa'),

                        TextEntry::make('book.title')
                            ->label('Judul Buku'),

                        TextEntry::make('borrow_date')
                            ->label('Tanggal Pinjam')
                            ->date(),

                        TextEntry::make('due_date')
                            ->label('Jatuh Tempo')
                            ->date()
                            ->color(fn ($record) =>
                                $record->status === 'Dipinjam' && $record->due_date < now()
                                    ? 'danger'
                                    : null
                            ),

                        TextEntry::make('return_date')
                            ->label('Tanggal Kembali')
                            ->date()
                            ->placeholder('-'),
                    ]),

                Section::make('Status & Denda')
                    ->columns(2)
                    ->schema([

                        // 🔹 STATUS CERDAS
                        TextEntry::make('status')
                            ->label('Status')
                            ->badge()
                            ->formatStateUsing(function ($record) {
                                if ($record->status === 'Dipinjam' && $record->due_date < now()) {
                                    return 'Terlambat';
                                }
                                return $record->status;
                            })
                            ->color(function ($record) {
                                if ($record->status === 'Dipinjam' && $record->due_date < now()) {
                                    return 'danger';
                                }

                                return match ($record->status) {
                                    'Pending' => 'gray',
                                    'Dipinjam' => 'warning',
                                    'Dikembalikan' => 'success',
                                    'Batal' => 'gray',
                                    default => 'primary',
                                };
                            }),

                        // 🔹 DENDA KONSISTEN
                        TextEntry::make('fine')
                            ->label('Denda')
                            ->state(function ($record) {

                                if (!$record->due_date) return 0;

                                // tentukan pembanding waktu
                                $endDate = $record->return_date ?? now();

                                if ($endDate <= $record->due_date) {
                                    return 0;
                                }

                                $daysLate = Carbon::parse($record->due_date)
                                    ->diffInDays($endDate);

                                return $daysLate * 1000;
                            })
                            ->formatStateUsing(fn ($state) =>
                                'Rp ' . number_format($state, 0, ',', '.')
                            ),
                    ]),

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