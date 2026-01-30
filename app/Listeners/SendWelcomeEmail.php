<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Verified; // <--- Perhatikan ini beda event
use Illuminate\Contracts\Queue\ShouldQueue; // <--- Wajib antrian
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;
use App\Mail\WelcomeEmail;

class SendWelcomeEmail implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * Handle the event.
     */
    public function handle(Verified $event): void
    {
        // Ambil user yang baru saja verifikasi
        /** @var \App\Models\User $user */
        $user = $event->user;

        // Kirim Email Selamat Datang sekarang
        Mail::to($user->email)->send(new WelcomeEmail($user));
    }
}