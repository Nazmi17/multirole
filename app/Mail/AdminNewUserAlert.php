<?php

namespace App\Mail;

use App\Models\User; // <--- Import ini
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class AdminNewUserAlert extends Mailable
{
    use Queueable, SerializesModels;

    // UBAH CONSTRUCT MENJADI:
    public function __construct(public User $newUser) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Notifikasi: Pendaftaran User Baru',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.admin_alert',
        );
    }
}