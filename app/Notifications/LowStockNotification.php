<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\Product;
use App\Models\User;

class LowStockNotification extends Notification
{
    use Queueable;

    public function __construct(protected Product $product, protected ?User $reporter = null, protected ?string $note = null)
    {
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        $reporterName = $this->reporter?->name ?? 'Staff';

        $mail = (new MailMessage)
            ->subject('Low stock alert: ' . $this->product->name)
            ->greeting('Hello Admin,')
            ->line("{$reporterName} reported low stock for the product: " . $this->product->name)
            ->line('Category: ' . ($this->product->category->name ?? 'Uncategorized'))
            ->line('Price: ₱' . number_format($this->product->price, 2));

        if ($this->note) {
            $mail->line('Note from staff: ' . $this->note);
        }

        try {
            $mail->action('View product', route('admin.products.show', ['product' => $this->product->id]));
        } catch (\Throwable $e) {
            // ignore if route not available
        }

        $mail->line('Please review inventory and restock if needed.');

        return $mail;
    }

    public function toArray($notifiable)
    {
        return [
            'product_id' => $this->product->id,
            'product_name' => $this->product->name,
            'product_slug' => $this->product->slug ?? null,
            'reporter_id' => $this->reporter?->id,
            'reporter_name' => $this->reporter?->name,
            'note' => $this->note,
        ];
    }
}
