<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pedido extends Model
{
    protected $fillable = [
        'user_id',
        'total',
        'estado',
        'email_enviado_at'
    ];

    public function items()
    {
        return $this->hasMany(PedidoItem::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}