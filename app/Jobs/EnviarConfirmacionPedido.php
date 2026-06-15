<?php

namespace App\Jobs;

use Throwable;
use App\Models\Pedido;
use Illuminate\Support\Facades\Log;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Bus\Queueable;
use App\Notifications\ConfirmacionPedidoNotification;

class EnviarConfirmacionPedido implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Número máximo de reintentos.
     */
    public int $tries = 3;

    /**
     * Tiempo máximo de ejecución en segundos.
     */
    public int $timeout = 60;

    /**
     * Crear una nueva instancia del Job.
     */
    public function __construct(public Pedido $pedido)
    {
    }

    /**
     * Ejecutar el Job.
     */
    public function handle(): void
    {
        try {
            $this->pedido->user->notify(
                new ConfirmacionPedidoNotification($this->pedido)
            );
            $this->pedido->update([
                'email_enviado_at' => now()
            ]);

        } catch (\Exception $e) {
            Log::error(
                'Error enviando correo',
                [
                    'pedido' => $this->pedido->id,
                    'error' => $e->getMessage()
                ]
            );
            throw $e;
        }
    }

    /**
     * Se ejecuta si el Job falla definitivamente.
     */
    public function failed(Throwable $e): void
    {
        Log::error(
            'Fallo envío email pedido ' . $this->pedido->id,
            [
                'error' => $e->getMessage()
            ]
        );
    }
}