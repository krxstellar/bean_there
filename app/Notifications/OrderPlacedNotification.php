<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\Order;

class OrderPlacedNotification extends Notification
{
    use Queueable;

    protected Order $order;

    /**
     * Return a unique key for this notification (used by MailChannel).
     *
     * @return string
     */
    public function getKey(): string
    {
        if (! empty($this->id)) {
            return (string) $this->id;
        }

        $this->id = (string) \Illuminate\Support\Str::uuid();

        return $this->id;
    }

    public function __construct(Order $order)
    {
        $this->order = $order;
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        $customerName = $this->order->user?->name ?? 'Customer';

        $mail = (new MailMessage)
            ->subject('New order placed: #' . $this->order->id)
            ->greeting('Hello Admin,')
            ->line("A new order (#{$this->order->id}) was placed by {$customerName}.")
            ->line('Total: ₱' . number_format($this->order->total, 2))
            ->line('Status: ' . $this->order->status);

        try {
            $mail->action('View order', route('admin.orders.show', ['order' => $this->order->id]));
        } catch (\Throwable $e) {
            // ignore if route not available
        }

        return $mail;
    }

    public function toArray($notifiable)
    {
        return [
            'order_id' => $this->order->id,
            'total' => (string)$this->order->total,
            'customer_id' => $this->order->user_id,
            'customer_name' => $this->order->user?->name,
            'message' => "New order placed (#{$this->order->id})",
        ];
    }
}
