<?php

namespace App\Console\Commands;

use App\Jobs\SendReminderEmailJob;
use App\Models\Borrowing;
use Illuminate\Console\Command;

class SendReminderEmails extends Command
{
    protected $signature = 'app:send-reminder {--force}';
    protected $description = 'Mengirim email pengingat untuk buku yang akan jatuh tempo atau sudah terlambat.';

    public function handle()
    {
        $this->info('Memproses pengingat H-1...');

        Borrowing::where('status', 'Dipinjam')
            ->whereDate('due_date', today()->addDay())
            ->whereDoesntHave('reminders', fn($q) => $q->where('type', 'pre_due'))
            ->each(fn($b) => SendReminderEmailJob::dispatch($b, 'pre_due'));

        $this->info('Memproses pengingat keterlambatan...');

        Borrowing::where('status', 'Dipinjam')
            ->whereDate('due_date', '<', today())
            ->whereDoesntHave('reminders', fn($q) => $q->where('type', 'overdue'))
            ->each(fn($b) => SendReminderEmailJob::dispatch($b, 'overdue'));

        $this->info('Selesai — semua job telah dikirim ke antrian.');
    }
}
