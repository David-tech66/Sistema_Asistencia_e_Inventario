<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['razon_social', 'ruc', 'telefono', 'correo', 'direccion'])]
class Proveedor extends Model
{
    protected $table = 'proveedores';
}
