<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['created_at', 'updated_at', 'user_id', 'fecha', 'hora_entrada', 'hora_salida'])]
class Asistencia extends Model
{
    protected $table = 'asistencias';
}
