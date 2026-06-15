<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Producto;
use App\Http\Requests\StoreProductoRequest;
use App\Http\Requests\UpdateProductoRequest;
use App\Http\Resources\ProductoResource;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Gate;

class ProductoController extends Controller
{
    use AuthorizesRequests;
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request) {
        $productos = Producto::with('categoria')
            ->buscar($request->busqueda)
            ->deCategoria($request->categoria_id)
            ->rangoPrecio($request->precio_min, $request->precio_max)
            ->orderBy($request->get('orden', 'nombre'),
                $request->get('dir', 'asc'))
            ->paginate($request->get('por_pagina', 15));
        return ProductoResource::collection($productos);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProductoRequest $request)
    {
        $this->authorize('create', Producto::class);
        $data = $request->except('imagen');

        if ($request->hasFile('imagen')) {
            $data['imagen'] = $request->file('imagen')
                ->store('productos', 'public');
        }

        $producto = Producto::create($data);
        return new ProductoResource($producto);
    }

    /**
     * Display the specified resource.
     */
    public function show(Producto $producto)
    {
        return new ProductoResource($producto);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductoRequest $request, $id)
    {
        $producto = Producto::find($id);
        if (!$producto) {
            return response()->json([
                'message' => 'Producto no encontrado'
            ], 404);
        }
        $this->authorize('update', $producto);

        $producto->update([
            'nombre' => $request->nombre,
            'descripcion' => $request->descripcion,
            'precio' => $request->precio,
            'stock' => $request->stock
        ]);

        return response()->json($producto);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $producto = Producto::find($id);
        if (!$producto) {
            return response()->json([
                'mensaje' => 'Producto no encontrado'
            ], 404);
        }
        $this->authorize('delete', $producto);

        $producto->delete();
        return response()->json(null, 204);
    }
}
