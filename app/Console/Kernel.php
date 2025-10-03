<?php

namespace App\Console;

use App\Mail\DueDateReminder;
use App\Mail\OverdueReminder;
use App\Models\Borrowing;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use Illuminate\Support\Facades\Mail;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     */
    protected function schedule(Schedule $schedule): void
    {
        // Task untuk mengirim email reminder H-1 sebelum jatuh tempo
        $schedule->call(function () {
            $borrowings = Borrowing::where('due_date', now()->addDay()->toDateString())
                ->whereNull('return_date')
                ->with(['student', 'book'])
                ->get();

            foreach ($borrowings as $borrowing) {
                Mail::to($borrowing->student->email)->send(new DueDateReminder($borrowing));
            }
        })->dailyAt('08:00');

        // Task untuk mengirim email reminder keterlambatan
        $schedule->call(function () {
            $borrowings = Borrowing::where('due_date', '<', now()->toDateString())
                ->whereNull('return_date')
                ->with(['student', 'book'])
                ->get();

            foreach ($borrowings as $borrowing) {
                Mail::to($borrowing->student->email)->send(new OverdueReminder($borrowing));
            }
        })->dailyAt('08:00');
    }

    /**
     * Register the commands for the application.
     */
    protected function commands(): void
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}