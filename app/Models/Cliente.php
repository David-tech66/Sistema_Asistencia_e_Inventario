<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['documento', 'nombre', 'telefono'])]
class Cliente extends Model
{
    protected $table = 'clientes';
}