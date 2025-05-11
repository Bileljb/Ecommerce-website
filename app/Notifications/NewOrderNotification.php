<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Order;

class NewOrderNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $order;

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
        return (new MailMessage)
            ->subject('New Order Received #' . $this->order->id)
            ->greeting('New Order Alert!')
            ->line('A new order has been placed.')
            ->line('Order Details:')
            ->line('Order ID: #' . $this->order->id)
            ->line('Customer: ' . $this->order->user->name)
            ->line('Total Amount: $' . number_format($this->order->total_price, 2))
            ->action('View Order', url('/admin/orders/' . $this->order->id))
            ->line('Please process this order as soon as possible.');
    }

    public function toArray($notifiable)
    {
        return [
            'order_id' => $this->order->id,
            'message' => 'New order #' . $this->order->id . ' from ' . $this->order->user->name,
            'type' => 'new_order'
        ];
    }
} 