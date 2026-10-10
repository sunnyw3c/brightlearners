<?php

namespace App\Notifications;

use App\Domains\Accounts\Services\NotificationGate;
use App\Domains\Membership\Models\Subscription;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class MembershipExpiringNotice extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public readonly Subscription $subscription,
        public readonly int $daysRemaining,
    ) {
        $this->queue = 'notifications';
    }

    /**
     * @return array<int, string>
     */
    public function via(mixed $notifiable): array
    {
        if ($notifiable instanceof User) {
            return app(NotificationGate::class)->channels($notifiable, 'membership_notice');
        }

        return ['mail'];
    }

    public function toMail(mixed $notifiable): MailMessage
    {
        $endsAtFormatted = $this->subscription->ends_at?->format('M d, Y') ?? 'soon';

        return (new MailMessage)
            ->subject("Your BrightLearners Membership expires in {$this->daysRemaining} days")
            ->greeting("Hello {$notifiable->name},")
            ->line("This is a reminder that your BrightLearners weekly learning membership is scheduled to expire on {$endsAtFormatted} ({$this->daysRemaining} days remaining).")
            ->line('After this date, access to weekly released packs and member downloads will be paused.')
            ->action('View Membership', url('/account/membership'))
            ->line('Thank you for learning with us!');
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(mixed $notifiable): array
    {
        return [
            'type' => 'membership_expiring',
            'title' => 'Membership Expiring Soon',
            'body' => "Your membership access expires in {$this->daysRemaining} days.",
            'action_url' => '/account/membership',
            'subscription_id' => $this->subscription->id,
            'days_remaining' => $this->daysRemaining,
        ];
    }
}
