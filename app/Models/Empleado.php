<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

#[Fillable([
    'nombre_completo',
    'dni',
    'cargo',
    'telefono',
    'estado'
])]
class Empleado extends Model
{
    use HasFactory;
    protected $table = 'empleados';

    protected $casts = [
        'estado' => 'boolean',
    ];

    public function asistencias(): HasMany
    {
        return $this->hasMany(Asistencia::class);
    }
}
