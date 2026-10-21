<?php

namespace App\Http\Controllers;

use App\Models\Asistencia;
use App\Models\Empleado;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class AsistenciaController extends Controller
{
    public function index(): View
    {
        $asistencias = Asistencia::with('empleado')
            ->orderByDesc('fecha')
            ->orderByDesc('hora_entrada')
            ->get();

        $empleados = Empleado::where('estado', true)
            ->orderBy('nombre_completo')
            ->get();

        return view('asistencias', compact('asistencias', 'empleados'));
    }

    public function registrar(Request $request): RedirectResponse
    {
        $datos = $request->validate([
            'dni' => ['required', 'string', 'max:15', 'regex:/^[0-9]+$/'],
            'modo' => ['required', 'in:entrada,salida'],
        ], [
            'dni.required' => 'Escribe o escanea el DNI del empleado.',
            'dni.regex' => 'El DNI solo debe contener números.',
            'modo.required' => 'Selecciona el modo: entrada o salida.',
            'modo.in' => 'Selecciona el modo: entrada o salida.',
        ]);

        $empleado = Empleado::where('dni', $datos['dni'])->first();

        if (!$empleado) {
            return redirect('/asistencias')
                ->with('error', 'No existe ningún empleado con DNI ' . $datos['dni'] . '.');
        }

        if (!$empleado->estado) {
            return redirect('/asistencias')
                ->with('error', 'El empleado ' . $empleado->nombre_completo . ' está inactivo.');
        }

        $hoy = now()->toDateString();

        $asistencia = Asistencia::where('empleado_id', $empleado->id)
            ->where('fecha', $hoy)
            ->first();

        if ($datos['modo'] === 'entrada') {
            if ($asistencia) {
                return redirect('/asistencias')
                    ->with('error', 'Este empleado ya tiene la entrada registrada para hoy (' . $asistencia->hora_entrada . ').');
            }

            Asistencia::create([
                'empleado_id' => $empleado->id,
                'fecha' => $hoy,
                'hora_entrada' => now()->format('H:i:s'),
                'hora_salida' => null,
            ]);

            return redirect('/asistencias')
                ->with('success', 'Entrada registrada para ' . $empleado->nombre_completo . '.');
        }

        // Modo salida
        if (!$asistencia) {
            return redirect('/asistencias')
                ->with('error', 'Este empleado no tiene entrada registrada hoy. Primero registra su entrada.');
        }

        if ($asistencia->hora_salida) {
            return redirect('/asistencias')
                ->with('error', 'Este empleado ya tiene la salida registrada para hoy (' . $asistencia->hora_salida . ').');
        }

        $asistencia->hora_salida = now()->format('H:i:s');
        $asistencia->save();

        return redirect('/asistencias')
            ->with('success', 'Salida registrada para ' . $empleado->nombre_completo . '.');
    }
}
