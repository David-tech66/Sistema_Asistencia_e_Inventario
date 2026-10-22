<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'empleado_id',
    'fecha',
    'hora_entrada',
    'hora_salida',
    'estado',
    'salida_anticipada'
])]
class Asistencia extends Model
{
    protected $table = 'asistencias';

    protected $casts = [
        'salida_anticipada' => 'boolean',
    ];

    public function empleado(): BelongsTo
    {
        return $this->belongsTo(Empleado::class);
    }
}
