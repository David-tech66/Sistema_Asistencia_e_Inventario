<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['cliente_id', 'producto_id', 'cantidad', 'precio_unitario', 'total'])]
class Venta extends Model
{
    protected $table = 'ventas';
}
