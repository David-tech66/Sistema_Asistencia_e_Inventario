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
        ], [
            'hora_entrada.required' => 'Indica la hora de entrada.',
            'hora_entrada.date_format' => 'La hora de entrada debe tener formato HH:MM.',
            'hora_salida.required' => 'Indica la hora de salida.',
            'hora_salida.date_format' => 'La hora de salida debe tener formato HH:MM.',
            'hora_salida.after' => 'La hora de salida debe ser posterior a la hora de entrada.',
        ]);

        Configuracion::guardar('hora_entrada', $datos['hora_entrada']);
        Configuracion::guardar('hora_salida', $datos['hora_salida']);

        return redirect('/configuracion')
            ->with('success', 'Horario actualizado: entrada ' . $datos['hora_entrada'] . ' y salida ' . $datos['hora_salida'] . '.');
    }

    /**
     * Solo los usuarios con rol admin pueden acceder.
     */
    private function autorizarAdmin(): void
    {
        abort_unless(auth()->user()?->rol === 'admin', 403);
    }
}
