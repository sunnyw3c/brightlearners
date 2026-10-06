<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AccountDeletionRequested extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(private readonly User $user) {}

    /**
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Account deletion requested')
            ->line("A parent account has requested deletion: {$this->user->email} (user #{$this->user->id}).")
            ->line('Process this according to the privacy policy.');
    }
}
