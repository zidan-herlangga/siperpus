<?php

namespace App\Mail;

use App\Models\Borrowing;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OverdueReminder extends Mailable
{
    use Queueable, SerializesModels;
    
    public int $fine;

    public function __construct(public Borrowing $borrowing)
    {
        // Hitung denda saat ini
        $lateDays = $this->borrowing->due_date->diffInDays(now());
        $this->fine = $lateDays * 5000;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Pemberitahuan: Keterlambatan Pengembalian Buku',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.reminders.overdue',
        );
    }
}