<?php

namespace App\Mail;

use App\Models\Borrowing;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class DueDateReminder extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Borrowing $borrowing)
    {
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Pengingat: Batas Waktu Pengembalian Buku',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.reminders.due_date',
        );
    }
}