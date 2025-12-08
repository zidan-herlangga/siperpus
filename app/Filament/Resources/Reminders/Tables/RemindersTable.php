<?php

namespace App\Filament\Resources\Reminders\Tables;

use Filament\Tables;
use Filament\Actions\Action;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Actions\DeleteBulkAction;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Artisan;
use App\Models\Reminder;

class RemindersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('borrowing.student.name')
                    ->label('Siswa')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('borrowing.book.title')
                    ->label('Buku')
                    ->sortable()
                    ->searchable(),

                TextColumn::make('type')
                    ->label('Jenis Pengingat')
                    ->badge()
                    ->color(fn ($state) => $state === 'pre_due' ? 'warning' : 'danger'),

                TextColumn::make('sent_at')
                    ->label('Dikirim Pada')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('type')
                    ->label('Jenis Reminder')
                    ->options([
                        'pre_due' => 'H-1 Jatuh Tempo',
                        'overdue' => 'Terlambat',
                    ]),
            ])
            ->headerActions([
                Action::make('sendReminder')
                    ->label('Kirim Pengingat')
                    ->icon('heroicon-o-paper-airplane')
                    ->color('primary')
                    ->requiresConfirmation()
                    ->modalAutofocus(false)
                    ->extraAttributes(['wire:key' => 'send-reminder-button'])

                    ->action(function () {

                        // Reset sent_at agar command mau mengirim ulang email
                        Reminder::query()->update([
                            'sent_at' => null,
                        ]);

                        // Panggil command tanpa --force
                        Artisan::call('app:send-reminder');

                        $output = Artisan::output();

                        Notification::make()
                            ->title('Pengingat selesai dikirim')
                            ->body($output ?: 'Semua email pengingat telah dikirim.')
                            ->success()
                            ->send();
                    })

                    ->after(function (Tables\Contracts\HasTable $livewire) {
                        // Refresh tabel biar state action reset
                        $livewire->dispatch('$refresh');
                    }),
            ])
            ->bulkActions([
                DeleteBulkAction::make()
                    ->label('Hapus yang Dipilih')
                    ->icon('heroicon-o-trash')
                    ->color('danger')
                    ->requiresConfirmation()
                    ->modalHeading('Hapus Data Terpilih')
                    ->modalDescription('Yakin ingin menghapus data reminder yang dipilih? Tindakan ini tidak dapat dibatalkan.')
                    ->modalSubmitActionLabel('Ya, Hapus'),
            ]);
    }
}

