<?php

namespace App\Console\Commands;

use App\Mail\DueDateReminder;
use App\Mail\OverdueReminder;
use App\Models\Borrowing;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendReminderEmails extends Command
{
    protected $signature = 'app:send-reminder';
    protected $description = 'Mengirim email pengingat untuk buku yang akan jatuh tempo atau sudah terlambat.';

    public function handle()
    {
        $this->info('Memulai proses pengiriman email pengingat...');

        // Reminder H-1
        $dueSoonBorrowings = Borrowing::where('status', 'Dipinjam')
            ->whereDate('due_date', today()->addDay())
            ->whereDoesntHave('reminders', function ($q) {
                $q->where('type', 'pre_due');
            })
            ->get();

        foreach ($dueSoonBorrowings as $borrowing) {
            Mail::to($borrowing->student->email)->send(new DueDateReminder($borrowing));

            $borrowing->reminders()->create([
                'type' => 'pre_due',
            ]);

            $this->info('Reminder H-1 dikirim ke: '.$borrowing->student->email);
        }

        // Reminder Overdue
        $overdueBorrowings = Borrowing::where('status', 'Dipinjam')
            ->whereDate('due_date', '<', today())
            ->whereDoesntHave('reminders', function ($q) {
                $q->where('type', 'overdue');
            })
            ->get();

        foreach ($overdueBorrowings as $borrowing) {
            Mail::to($borrowing->student->email)->send(new OverdueReminder($borrowing));

            $borrowing->reminders()->create([
                'type' => 'overdue',
            ]);

            $this->warn('Reminder terlambat dikirim ke: '.$borrowing->student->email);
        }

        $this->info('Selesai ✅');
    }
}
