<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['clave', 'valor'])]
class Configuracion extends Model
{
    protected $table = 'configuraciones';

    /**
     * Obtiene un valor de configuración o el valor por defecto.
     */
    public static function obtener(string $clave, string $defecto = ''): string
    {
        $configuracion = static::where('clave', $clave)->first();

        return $configuracion?->valor ?? $defecto;
    }

    /**
     * Guarda (crea o actualiza) un valor de configuración.
     */
    public static function guardar(string $clave, string $valor): void
    {
        static::updateOrCreate(['clave' => $clave], ['valor' => $valor]);
    }
}
