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

    public function borrowing()
    {
        return $this->belongsTo(Borrowing::class);
    }
}
