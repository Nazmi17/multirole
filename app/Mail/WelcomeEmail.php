<?php

namespace App\Mail;

use App\Models\User; // <--- Pastikan ini di-import
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class WelcomeEmail extends Mailable
{
    use Queueable, SerializesModels;

    // ERROR SEBELUMNYA KARENA INI KOSONG.
    // KITA UBAH MENJADI:
    public function __construct(public User $user) {} 

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Selamat Datang di Aplikasi Kami!',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.welcome',
        );
    }
}