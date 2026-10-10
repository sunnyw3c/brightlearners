<?php

namespace App\Notifications;

use App\Domains\Accounts\Services\NotificationGate;
use App\Domains\Membership\Models\LearningProgram;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class MonthlyCheckAvailable extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public readonly LearningProgram $program,
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
            ->subject("Monthly Learning Programme Available: {$this->program->title}")
            ->greeting("Hello {$notifiable->name},")
            ->line("The monthly programme '{$this->program->title}' has been published for your child’s class.")
            ->line('Check out the weekly roadmap and resources designed to build steady study routines.')
            ->action('View Parent Dashboard', url('/account/dashboard'))
            ->line('Thank you for learning with BrightLearners.');
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(mixed $notifiable): array
    {
        return [
            'type' => 'monthly_check_available',
            'title' => "Monthly Programme: {$this->program->title}",
            'body' => "The monthly programme '{$this->program->title}' has been published.",
            'action_url' => '/account/dashboard',
            'program_id' => $this->program->id,
        ];
    }
}
