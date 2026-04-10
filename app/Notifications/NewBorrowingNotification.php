<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class NewBorrowingNotification extends Notification
{
    use Queueable;

    protected $borrowing;

    public function __construct($borrowing)
    {
        $this->borrowing = $borrowing;
    }

    public function via($notifiable)
    {
        return ['database']; // WAJIB
    }

    public function toDatabase($notifiable)
    {
        return [
            'title' => 'Peminjaman Buku Baru!',
            'message' => $this->borrowing->student->name . 
                ' meminjam buku "' . $this->borrowing->book->title . '"',
            'url' => '/adminperpustakaan/borrowings/' . $this->borrowing->id,
        ];
    }
}