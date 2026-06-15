<?php

namespace App\Notifications;

use App\Models\Pedido;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class ConfirmacionPedidoNotification extends Notification
{
    use Queueable;

    public function __construct(
        public Pedido $pedido
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject(
                'Confirmación de tu pedido #' .
                $this->pedido->id
            )
            ->greeting(
                '¡Hola, ' .
                $notifiable->name .
                '!'
            )
            ->line('Recibimos tu pedido correctamente.')
            ->line(
                'Total: $' .
                number_format(
                    $this->pedido->total,
                    2
                )
            )
            ->line('Gracias por tu compra.');
    }
}
