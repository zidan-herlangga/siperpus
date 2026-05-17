<?php

namespace App\Filament\Resources\Reminders\Tables;

use App\Models\Borrowing;
use App\Mail\DueDateReminder;
use App\Mail\OverdueReminder;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\Action;
use Filament\Actions\DeleteBulkAction;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Mail;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;

class RemindersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('student.name')
                    ->label('Siswa')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('book.title')
                    ->label('Buku')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('due_date')
                    ->label('Jatuh Tempo')
                    ->date('d M Y')
                    ->color(fn ($record) =>
                        $record->isOverdue() ? 'danger' : 'warning'
                    )
                    ->sortable(),

                TextColumn::make('status')
                    ->label('Status')
                    ->getStateUsing(function ($record) {
                        if ($record->isOverdue()) return 'Terlambat';
                        if ($record->isDueSoon()) return 'Segera';
                        return 'Aman';
                    })
                    ->badge()
                    ->color(fn ($state) => match ($state) {
                        'Terlambat' => 'danger',
                        'Segera' => 'warning',
                        default => 'success',
                    }),

                TextColumn::make('last_reminder_sent_at')
                    ->label('Terakhir Diingatkan')
                    ->dateTime('d M Y H:i')
                    ->placeholder('Belum pernah')
                    ->sortable(),

                TextColumn::make('fine_amount')
                    ->label('Denda')
                    ->money('IDR')
                    ->color(fn ($state) => $state > 0 ? 'danger' : 'success'),
            ])

            ->filters([
                SelectFilter::make('status')
                    ->label('Status')
                    ->options([
                        'overdue' => 'Terlambat',
                        'due_soon' => '≤ 3 Hari',
                    ])
                    ->query(function (Builder $query, array $data) {
                        return match ($data['value'] ?? null) {
                            'overdue' => $query->whereDate('due_date', '<', now()),
                            'due_soon' => $query->whereBetween('due_date', [
                                now(),
                                now()->addDays(3),
                            ]),
                            default => $query,
                        };
                    }),

                SelectFilter::make('reminder')
                    ->label('Reminder')
                    ->options([
                        'sent' => 'Sudah',
                        'not_sent' => 'Belum',
                    ])
                    ->query(function (Builder $query, array $data) {
                        return match ($data['value'] ?? null) {
                            'sent' => $query->whereNotNull('last_reminder_sent_at'),
                            'not_sent' => $query->whereNull('last_reminder_sent_at'),
                            default => $query,
                        };
                    }),
            ])

            ->actions([
                Action::make('sendIndividual')
                    ->label('Kirim')
                    ->icon('heroicon-m-paper-airplane')
                    ->color('success')
                    ->requiresConfirmation()
                    ->action(function (Borrowing $record) {

                        $record->refresh(); // 🔥 anti double click

                        if (!$record->student?->email) {
                            Notification::make()
                                ->title('Email tidak tersedia')
                                ->danger()
                                ->send();
                            return;
                        }

                        if (
                            $record->last_reminder_sent_at &&
                            $record->last_reminder_sent_at->diffInMinutes(now()) < 60
                        ) {
                            Notification::make()
                                ->title('Terlalu sering')
                                ->body('Baru saja dikirim.')
                                ->warning()
                                ->send();
                            return;
                        }

                        if ($record->isOverdue()) {
                            Mail::to($record->student->email)
                                ->queue(new OverdueReminder($record));
                        } else {
                            Mail::to($record->student->email)
                                ->queue(new DueDateReminder($record));
                        }

                        $record->update([
                            'last_reminder_sent_at' => now(),
                        ]);

                        Notification::make()
                            ->title('Masuk antrian')
                            ->success()
                            ->send();
                    }),
            ])

            ->bulkActions([
                DeleteBulkAction::make()
                    ->label('Hapus')
                    ->requiresConfirmation(),
            ]);
    }
}