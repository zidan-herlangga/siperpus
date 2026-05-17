<?php

namespace App\Models;

use Filament\Notifications\Notification;
use Illuminate\Database\Eloquent\Model;

class Reminder extends Model
{
    protected $fillable = [
        'borrowing_id',
        'type',
        'sent_at',
    ];

    protected static function booted(): void
    {
        static::created(function (Reminder $reminder) {
            $admins = \App\Models\Admin::all();

            Notification::make()
                ->title('Pengingat Dikirim!')
                ->body('Pengingat telah dikirim untuk peminjaman #' . $reminder->borrowing_id)
                ->sendToDatabase($admins);
        });
    }

    public function borrowing()
    {
        return $this->belongsTo(Borrowing::class);
    }
}
