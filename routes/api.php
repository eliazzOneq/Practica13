<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\ProductoController;
use App\Http\Controllers\PedidoController;
use App\Http\Controllers\CategoriaController;
use App\Http\Controllers\AuthController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->get('/me', [AuthController::class, 'me']);
Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);

Route::apiResource('categorias', CategoriaController::class);
Route::get(
    'categorias/{categoria}/productos',
    [CategoriaController::class, 'productos']
);

Route::apiResource('productos', ProductoController::class)
    ->middleware('auth:sanctum');

Route::post('/pedidos', PedidoController::class)
    ->middleware('auth:sanctum');