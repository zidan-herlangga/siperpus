<?php

namespace App\Models;

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
        static::created(function (Student $student) {
            $admins = \App\Models\Admin::all();

            Notification::make()
                ->title('Pengingat Dikirim!')
                ->body('Pengingat ' . $student->name . ' telah dikirim.')
                ->sendToDatabase($admins);
        });
    }

    public function borrowing()
    {
        return $this->belongsTo(Borrowing::class);
    }
}
