<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['nombre_categoria', 'descripcion'])]
class Categoria extends Model
{
    protected $table = 'categorias';
}
