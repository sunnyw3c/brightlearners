<?php

namespace App\Notifications;

use App\Domains\Commerce\Models\Order;
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
    ) {}

    /**
     * @return array<int, string>
     */
    public function via(mixed $notifiable): array
    {
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
}
