<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Producto extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'nombre',
        'descripcion',
        'precio',
        'stock',
        'imagen',
        'categoria_id'
    ];

    public function categoria() {
        return $this->belongsTo(Categoria::class);
    }

    public function scopeBuscar($query, $termino)
    {
        return $query->when($termino, function ($q) use ($termino) {
            $q->where(function ($sub) use ($termino) {
                $sub->where('nombre', 'like', "%{$termino}%")
                    ->orWhere('descripcion', 'like', "%{$termino}%");
            });
        });
    }
    
    public function scopeDeCategoria($query, $categoriaId) {
        return $query->when($categoriaId,
            fn($q) => $q->where('categoria_id', $categoriaId)
        );
    }
    
    public function scopeRangoPrecio($query, $min, $max) {
        return $query
            ->when($min, fn($q) => $q->where('precio', '>=', $min))
            ->when($max, fn($q) => $q->where('precio', '<=', $max));
    }

    public function pedidoItems()
    {
        return $this->hasMany(PedidoItem::class);
    }
}
