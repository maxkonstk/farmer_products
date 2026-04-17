<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OrderPlacedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(private readonly Order $order)
    {
    }

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
            ->subject("Заказ {$this->order->order_number} успешно оформлен")
            ->greeting("Здравствуйте, {$this->order->customer_name}!")
            ->line("Ваш заказ {$this->order->order_number} принят в обработку.")
            ->line('Статус заказа: '.$this->order->status->label())
            ->line('Сумма заказа: '.number_format((float) $this->order->total_price, 0, ',', ' ').' ₽')
            ->line('Мы свяжемся с вами для подтверждения заказа и деталей доставки.')
            ->action('Перейти в магазин', route('home'))
            ->line('Спасибо, что выбрали Фермерскую лавку.');
    }
}
