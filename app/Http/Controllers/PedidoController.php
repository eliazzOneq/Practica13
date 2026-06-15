<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Pedido;
use App\Models\PedidoItem;
use App\Models\Producto;
use App\Jobs\EnviarConfirmacionPedido;
use Illuminate\Support\Facades\DB;
use App\Events\NuevoPedidoRecibido;

class PedidoController extends Controller
{
    public function __invoke(Request $request)
    {
        $request->validate([
            'items' => 'required|array|min:1'
        ]);

        $pedido = DB::transaction(function () use ($request) {
            $total = collect($request->items)
                ->sum(fn ($item) =>
                    $item['precio'] * $item['cantidad']
                );

            $pedido = Pedido::create([
                'user_id' => auth()->id(),
                'total' => $total,
                'estado' => 'procesando'
            ]);

            foreach ($request->items as $item) {
                PedidoItem::create([
                    'pedido_id' => $pedido->id,
                    'producto_id' => $item['id'],
                    'cantidad' => $item['cantidad'],
                    'precio_unitario' => $item['precio']
                ]);

                Producto::find($item['id'])
                    ?->decrement(
                        'stock',
                        $item['cantidad']
                    );
            }

            return $pedido;
        });

        EnviarConfirmacionPedido::dispatch($pedido)
            ->delay(now()->addSeconds(5));

        event(new NuevoPedidoRecibido($pedido));

        return response()->json([
            'pedido_id' => $pedido->id
        ], 201);
    }
}
