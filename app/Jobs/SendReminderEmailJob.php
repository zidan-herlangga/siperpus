<?php

namespace App\Jobs;

use App\Mail\DueDateReminder;
use App\Mail\OverdueReminder;
use App\Models\Borrowing;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Mail;

class SendReminderEmailJob implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public Borrowing $borrowing,
        public string $type,
    ) {}

    public function handle(): void
    {
        $mail = $this->type === 'pre_due'
            ? new DueDateReminder($this->borrowing)
            : new OverdueReminder($this->borrowing);

        Mail::to($this->borrowing->student->email)->send($mail);

        $this->borrowing->reminders()->create([
            'type' => $this->type,
        ]);
    }
}
