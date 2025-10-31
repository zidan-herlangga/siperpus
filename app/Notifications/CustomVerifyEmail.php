<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\VerifyEmail as BaseVerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Carbon;

class CustomVerifyEmail extends BaseVerifyEmail
{
    /**
     * Build the mail representation of the notification.
     */
    public function toMail($notifiable)
    {
        $verifyUrl = $this->verificationUrl($notifiable);

        return (new MailMessage)
            ->subject('Verifikasi Email Anda - '. config('app.name'))
            ->greeting('Halo ' . $notifiable->name)
            ->line('Terima kasih telah mendaftar')
            ->line('Klik tombol di bawah ini untuk memverifikasi alamat email Anda dan mulai menggunakan sistem peminjaman buku online.')
            ->action('Verifikasi Sekarang', $verifyUrl)
            ->line('Jika Anda tidak membuat akun ini, abaikan pesan ini.')
            ->salutation("Salam hangat,")
            ->salutation(config('app.name', 'Elibrary KG2'));
    }

    /**
     * Create the verification URL.
     */
    protected function verificationUrl($notifiable)
    {
        return URL::temporarySignedRoute(
            'verification.verify',
            Carbon::now()->addMinutes(config('auth.verification.expire', 60)),
            [
                'id' => $notifiable->getKey(),
                'hash' => sha1($notifiable->getEmailForVerification()),
            ]
        );
    }
}