<?php

namespace App\Filament\Resources\Reminders\Tables;

use App\Models\Borrowing;
use App\Mail\DueDateReminder; // Import class baru Anda
use App\Mail\OverdueReminder; // Import class baru Anda
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Columns\TextColumn;
use Filament\Actions\Action;
use Filament\Actions\DeleteBulkAction;
use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Artisan;

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
                    ->color(fn ($record) => $record->due_date->isPast() ? 'danger' : 'warning')
                    ->sortable(),

                TextColumn::make('last_reminder_sent_at')
                    ->label('Terakhir Diingatkan')
                    ->dateTime('d M Y H:i')
                    ->placeholder('Belum pernah')
                    ->sortable(),

                TextColumn::make('fine_amount')
                    ->label('Denda')
                    ->money('IDR'),
            ])
            ->actions([
                Action::make('sendIndividual')
                    ->label('Kirim')
                    ->icon('heroicon-m-paper-airplane')
                    ->color('success')
                    ->requiresConfirmation()
                    ->action(function (Borrowing $record) {
                        try {
                            // LOGIKA PEMILIHAN EMAIL
                            // Jika sudah lewat jatuh tempo, gunakan OverdueReminder
                            // Jika belum lewat, gunakan DueDateReminder
                            if ($record->due_date->isPast()) {
                                Mail::to($record->student->email)->send(new OverdueReminder($record));
                            } else {
                                Mail::to($record->student->email)->send(new DueDateReminder($record));
                            }

                            // Update timestamp di database
                            $record->update(['last_reminder_sent_at' => now()]);

                            Notification::make()
                                ->title('Email Berhasil Dikirim')
                                ->body('Notifikasi terkirim ke ' . $record->student->email)
                                ->success()
                                ->send();
                        } catch (\Exception $e) {
                            Notification::make()
                                ->title('Gagal Mengirim')
                                ->body('Cek SMTP/App Password. Error: ' . $e->getMessage())
                                ->danger()
                                ->persistent()
                                ->send();
                        }
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