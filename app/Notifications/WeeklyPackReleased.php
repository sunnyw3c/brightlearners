<?php

namespace App\Notifications;

use App\Domains\Accounts\Services\NotificationGate;
use App\Domains\Membership\Models\LearningWeek;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class WeeklyPackReleased extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public readonly LearningWeek $week,
    ) {
        $this->queue = 'notifications';
    }

    /**
     * @return array<int, string>
     */
    public function via(mixed $notifiable): array
    {
        if ($notifiable instanceof User) {
            return app(NotificationGate::class)->channels($notifiable, 'learning_reminder');
        }

        return ['mail'];
    }

    public function toMail(mixed $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject("New Weekly Pack Released: {$this->week->title}")
            ->greeting("Hello {$notifiable->name},")
            ->line("A brand new weekly learning pack is now ready: Week {$this->week->week_number} — '{$this->week->title}'.")
            ->when($this->week->emphasis, fn (MailMessage $mail) => $mail->line("This week's focus: {$this->week->emphasis}"))
            ->action('Go to Parent Dashboard', url('/account/dashboard'))
            ->line('Log in now to guide your child through this week’s curated worksheets and activities.');
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(mixed $notifiable): array
    {
        return [
            'type' => 'weekly_pack_released',
            'title' => "New Weekly Pack: {$this->week->title}",
            'body' => "Week {$this->week->week_number} worksheets and activities are ready on your dashboard.",
            'action_url' => '/account/dashboard',
            'week_id' => $this->week->id,
            'week_number' => $this->week->week_number,
        ];
    }
}
