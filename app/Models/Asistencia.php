<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[Fillable([
    'user_id',
    'fecha',
    'hora_entrada',
    'hora_salida'
])]
class Asistencia extends Model
{
    protected $table = 'asistencias';

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
