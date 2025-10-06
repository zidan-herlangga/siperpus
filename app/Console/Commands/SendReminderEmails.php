<?php

namespace App\Console\Commands;

use App\Mail\DueDateReminder;
use App\Mail\OverdueReminder;
use App\Models\Borrowing;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class SendReminderEmails extends Command
{
    protected $signature = 'app:send-reminder-emails';
    protected $description = 'Mengirim email pengingat untuk buku yang akan jatuh tempo atau sudah terlambat.';

    public function handle()
    {
        $this->info('Memulai proses pengiriman email pengingat...');

        // Logika pengingat H-1
        $dueSoonBorrowings = Borrowing::where('status', 'Dipinjam')
            ->whereDate('due_date', today()->addDay())
            ->get();
        
        foreach ($dueSoonBorrowings as $borrowing) {
            Mail::to($borrowing->student->email)->send(new DueDateReminder($borrowing));
            $this->info('Mengirim pengingat jatuh tempo ke: ' . $borrowing->student->email);
        }

        // Logika pengingat keterlambatan
        $overdueBorrowings = Borrowing::where('status', 'Dipinjam')
            ->whereDate('due_date', '<', today())
            ->get();
        
        foreach ($overdueBorrowings as $borrowing) {
            Mail::to($borrowing->student->email)->send(new OverdueReminder($borrowing));
            $this->warn('Mengirim notifikasi keterlambatan ke: ' . $borrowing->student->email);
        }

        $this->info('Proses pengiriman email selesai.');
    }
}