<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Registered;
use Illuminate\Contracts\Queue\ShouldQueue; // Opsional: Aktifkan jika pakai queue
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;
use App\Models\User;
use App\Mail\WelcomeEmail;
use App\Mail\AdminNewUserAlert;

class SendRegistrationNotifications implements ShouldQueue
{
    use InteractsWithQueue;
   /**
     * Handle the event.
     */
    public function handle(Registered $event): void
    {
        // KITA PAKSA TYPE CASTING DISINI
        /** @var \App\Models\User $newUser */
        $newUser = $event->user;

        // ... logic admin ...
        $recipients = User::role(['admin', 'Pengelola'])->get();

        foreach ($recipients as $recipient) {
            Mail::to($recipient->email)->send(new AdminNewUserAlert($newUser));
        }
    }
}