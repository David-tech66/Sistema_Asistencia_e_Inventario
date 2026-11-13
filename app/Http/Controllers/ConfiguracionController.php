<?php

namespace App\Http\Controllers;

use App\Models\Configuracion;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ConfiguracionController extends Controller
{
    /**
     * Muestra la configuración del horario (solo admin).
     */
    public function index(): View
    {
        $this->autorizarAdmin();

        return view('configuracion', [
            'horaEntrada' => Configuracion::obtener('hora_entrada', '08:10'),
            'horaSalida' => Configuracion::obtener('hora_salida', '18:00'),
            'toleranciaTardanza' => (int) Configuracion::obtener('tolerancia_tardanza', '0'),
            'stockMinimoDefecto' => (int) Configuracion::obtener('stock_minimo_defecto', '5'),
        ]);
    }

    /**
     * Guarda el horario configurado (solo admin).
     */
    public function update(Request $request): RedirectResponse
    {
        $this->autorizarAdmin();

        $datos = $request->validate([
            'hora_entrada' => ['required', 'date_format:H:i'],
            'hora_salida' => ['required', 'date_format:H:i', 'after:hora_entrada'],
            'tolerancia_tardanza' => ['required', 'integer', 'min:0', 'max:120'],
            'stock_minimo_defecto' => ['required', 'integer', 'min:0', 'max:9999'],
        ], [
            'hora_entrada.required' => 'Indica la hora de entrada.',
            'hora_entrada.date_format' => 'La hora de entrada debe tener formato HH:MM.',
            'hora_salida.required' => 'Indica la hora de salida.',
            'hora_salida.date_format' => 'La hora de salida debe tener formato HH:MM.',
            'hora_salida.after' => 'La hora de salida debe ser posterior a la hora de entrada.',
            'tolerancia_tardanza.required' => 'Indica los minutos de tolerancia.',
            'tolerancia_tardanza.integer' => 'La tolerancia debe ser un número entero de minutos.',
            'tolerancia_tardanza.min' => 'La tolerancia no puede ser negativa.',
            'tolerancia_tardanza.max' => 'La tolerancia no puede superar los 120 minutos.',
            'stock_minimo_defecto.required' => 'Indica el stock mínimo por defecto.',
            'stock_minimo_defecto.integer' => 'El stock mínimo debe ser un número entero.',
            'stock_minimo_defecto.min' => 'El stock mínimo no puede ser negativo.',
            'stock_minimo_defecto.max' => 'El stock mínimo no puede superar 9999 unidades.',
        ]);

        Configuracion::guardar('hora_entrada', $datos['hora_entrada']);
        Configuracion::guardar('hora_salida', $datos['hora_salida']);
        Configuracion::guardar('tolerancia_tardanza', (string) (int) $datos['tolerancia_tardanza']);
        Configuracion::guardar('stock_minimo_defecto', (string) (int) $datos['stock_minimo_defecto']);

        return redirect('/configuracion')
            ->with('success', 'Horario actualizado: entrada ' . $datos['hora_entrada'] . ' y salida ' . $datos['hora_salida']
                . '. Tolerancia de ' . (int) $datos['tolerancia_tardanza'] . ' min y stock mínimo por defecto '
                . (int) $datos['stock_minimo_defecto'] . ' guardados.');
    }

    /**
     * Solo los usuarios con rol admin pueden acceder.
     */
    private function autorizarAdmin(): void
    {
        abort_unless(auth()->user()?->rol === 'admin', 403);
    }
}
