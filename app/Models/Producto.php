<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'categoria_id', 'marca_id', 'proveedor_id',
    'codigo_qr', 'nombre', 'imagen', 'especificaciones',
    'meses_garantia', 'stock_actual', 'stock_minimo',
    'precio_compra', 'precio_venta'
])]

class Producto extends Model
{
    protected $table = 'productos';

    public function categoria(): BelongsTo
    {
        return $this->belongsTo(Categoria::class);
    }

    public function marca(): BelongsTo
    {
        return $this->belongsTo(Marca::class);
    }

    public function proveedor(): BelongsTo
    {
        return $this->belongsTo(Proveedor::class);
    }
}
