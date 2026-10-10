<?php

namespace App\Notifications;

use App\Domains\Accounts\Services\NotificationGate;
use App\Domains\Commerce\Models\Order;
use App\Models\User;
use App\Support\Money;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OrderConfirmation extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public readonly Order $order,
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
        $totalFormatted = Money::format($this->order->total, $this->order->currency);

        return (new MailMessage)
            ->subject("Order Confirmation — {$this->order->order_number}")
            ->greeting("Hello {$this->order->billing_name},")
            ->line("Thank you for your purchase on BrightLearners! We've received your payment of {$totalFormatted}.")
            ->line("Order Number: {$this->order->order_number}")
            ->action('View Your Library', url('/account/library'))
            ->line('Your purchased digital learning materials are now ready in your account library.');
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(mixed $notifiable): array
    {
        $totalFormatted = Money::format($this->order->total, $this->order->currency);

        return [
            'type' => 'order_confirmation',
            'title' => "Order Confirmed — {$this->order->order_number}",
            'body' => "We've received your payment of {$totalFormatted}. Your materials are ready in your library.",
            'action_url' => '/account/library',
            'order_id' => $this->order->id,
        ];
    }
}
