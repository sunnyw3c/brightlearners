<?php

namespace App\Notifications;

use App\Domains\Accounts\Services\NotificationGate;
use App\Domains\Content\Models\ResourceCorrection;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CorrectionNotice extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public readonly ResourceCorrection $correction,
    ) {
        $this->queue = 'notifications';
    }

    /**
     * @return array<int, string>
     */
    public function via(mixed $notifiable): array
    {
        if ($notifiable instanceof User) {
            return app(NotificationGate::class)->channels($notifiable, 'transactional');
        }

        return ['mail'];
    }

    public function toMail(mixed $notifiable): MailMessage
    {
        $resourceTitle = $this->correction->resource?->title ?? 'Learning Resource';

        return (new MailMessage)
            ->subject("Important Update: {$resourceTitle}")
            ->greeting("Hello {$notifiable->name},")
            ->line("An updated version (v{$this->correction->version_to}) of '{$resourceTitle}' has been published.")
            ->when($this->correction->notes, fn (MailMessage $mail) => $mail->line("Correction notes: {$this->correction->notes}"))
            ->action('Download Updated Version', url('/account/library'))
            ->line('You can download the corrected edition directly from your learning library at no extra charge.');
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(mixed $notifiable): array
    {
        $resourceTitle = $this->correction->resource?->title ?? 'Learning Resource';

        return [
            'type' => 'correction_notice',
            'title' => "Updated Material: {$resourceTitle}",
            'body' => "Version {$this->correction->version_to} of {$resourceTitle} is now available.",
            'action_url' => '/account/library',
            'resource_id' => $this->correction->resource_id,
            'correction_id' => $this->correction->id,
        ];
    }
}
