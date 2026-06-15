<?php

namespace App\Events;

use App\Models\Pedido;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NuevoPedidoRecibido implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(
        public Pedido $pedido
    ) {
    }

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('admin-notificaciones'),
        ];
    }

    public function broadcastAs(): string
    {
        return 'nuevo-pedido';
    }

    public function broadcastWith(): array
    {
        return [
            'pedido_id' => $this->pedido->id,
            'total' => $this->pedido->total,
            'estado' => $this->pedido->estado,
            'usuario' => $this->pedido->user->name,
        ];
    }
}