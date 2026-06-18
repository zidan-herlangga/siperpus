<?php

namespace App\Filament\Resources\Borrowings\Tables;

use Filament\Tables\Table;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;

class BorrowingsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn (Builder $query) => 
                $query->with(['student', 'book']) // 🔥 cegah N+1
            )

            ->columns([
                // 🔹 Nama Siswa
                TextColumn::make('student.name')
                    ->label('Nama Siswa')
                    ->searchable()
                    ->sortable(),

                // 🔹 Judul Buku
                TextColumn::make('book.title')
                    ->label('Judul Buku')
                    ->searchable()
                    ->sortable(),

                // 🔹 Tanggal Pinjam
                TextColumn::make('borrow_date')
                    ->label('Tanggal Pinjam')
                    ->date('d M Y')
                    ->sortable(),

                // 🔹 Jatuh Tempo (dengan warning)
                TextColumn::make('due_date')
                    ->label('Jatuh Tempo')
                    ->date('d M Y')
                    ->sortable()
                    ->color(fn ($record) => 
                        $record->status === 'Dipinjam' && $record->due_date < now()
                            ? 'danger'
                            : null
                    )
                    ->tooltip(fn ($record) => 
                        $record->status === 'Dipinjam' && $record->due_date < now()
                            ? 'Sudah melewati jatuh tempo'
                            : null
                    ),

                // 🔹 Status (SMART)
                TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->icon(fn ($record) => 
                        $record->status === 'Dipinjam' && $record->due_date < now()
                            ? 'heroicon-o-exclamation-circle'
                            : null
                    )
                    ->color(function ($record) {
                        if ($record->status === 'Dipinjam' && $record->due_date < now()) {
                            return 'danger';
                        }

                        return match ($record->status) {
                            'Pending' => 'gray',
                            'Dipinjam' => 'warning',
                            'Dikembalikan' => 'success',
                            'Batal' => 'gray',
                            default => 'gray',
                        };
                    })
                    ->formatStateUsing(function ($record) {
                        if ($record->status === 'Dipinjam' && $record->due_date < now()) {
                            return 'Terlambat';
                        }

                        return $record->status;
                    })
                    ->sortable(),

                // 🔹 Denda (DYNAMIC LOGIC)
                TextColumn::make('fine_amount')
                    ->label('Denda')
                    ->getStateUsing(function ($record) {
                        if ($record->status !== 'Dipinjam') {
                            return 0;
                        }

                        if ($record->due_date >= now()) {
                            return 0;
                        }

                        $daysLate = Carbon::parse($record->due_date)
                            ->diffInDays(now());

                        return $daysLate * (int) config('library.fine_per_day', 1000);
                    })
                    ->formatStateUsing(fn ($state) => number_format((int) $state, 0, ',', '.'))
                    ->prefix('Rp ')
                    ->sortable(),
            ])

            ->filters([
                // 🔹 Filter Status
                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'Pending' => 'Pending',
                        'Dipinjam' => 'Dipinjam',
                        'Dikembalikan' => 'Dikembalikan',
                        'Batal' => 'Batal',
                    ]),

                // 🔹 Filter Overdue
                Tables\Filters\Filter::make('overdue')
                    ->label('Hanya Terlambat')
                    ->query(fn (Builder $query) => 
                        $query->where('status', 'Dipinjam')
                              ->whereDate('due_date', '<', now())
                    ),
            ])

            ->recordActions([
                // nanti bisa tambah:
                // ViewAction::make(),
                // EditAction::make(),
            ])

            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}