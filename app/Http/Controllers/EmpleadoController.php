<?php

namespace App\Http\Controllers;

use App\Models\Asistencia;
use App\Models\Empleado;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class EmpleadoController extends Controller
{
    /**
     * Mostrar listado de empleados.
     */
    public function index(): View
    {
        $empleados = Empleado::orderBy('nombre_completo')->get();

        return view('empleados', compact('empleados'));
    }

    /**
     * Buscar un empleado por DNI (usado por el registro de asistencia).
     */
    public function buscar(Request $request): JsonResponse
    {
        $dni = (string) $request->query('dni', '');

        if (!preg_match('/^[0-9]{1,15}$/', $dni)) {
            return response()->json(['encontrado' => false]);
        }

        $empleado = Empleado::where('dni', $dni)->first();

        if (!$empleado) {
            return response()->json(['encontrado' => false]);
        }

        $asistenciaHoy = Asistencia::where('empleado_id', $empleado->id)
            ->where('fecha', now()->toDateString())
            ->first();

        return response()->json([
            'encontrado' => true,
            'empleado' => [
                'nombre' => $empleado->nombre_completo,
                'cargo' => $empleado->cargo,
                'activo' => (bool) $empleado->estado,
                'entrada_hoy' => $asistenciaHoy ? $asistenciaHoy->hora_entrada : null,
                'salida_hoy' => $asistenciaHoy ? $asistenciaHoy->hora_salida : null,
            ],
        ]);
    }

    /**
     * Registrar un nuevo empleado.
     */
    public function store(Request $request): RedirectResponse
    {
        $datos = $request->validate([
            'nombre_completo' => ['required', 'string', 'max:150'],
            'dni' => ['required', 'string', 'max:15', 'regex:/^[0-9]+$/', 'unique:empleados,dni'],
            'cargo' => ['required', 'string', 'max:100'],
            'telefono' => ['nullable', 'string', 'max:20', 'regex:/^[0-9+ ]*$/'],
        ]);

        Empleado::create([
            'nombre_completo' => $datos['nombre_completo'],
            'dni' => $datos['dni'],
            'cargo' => $datos['cargo'],
            'telefono' => $datos['telefono'] ?? null,
            'estado' => true,
        ]);

        return redirect('/empleados')->with('success', 'Empleado registrado correctamente.');
    }

    /**
     * Actualizar un empleado.
     */
    public function update(Request $request, Empleado $empleado): RedirectResponse
    {
        $datos = $request->validate([
            'nombre_completo' => ['required', 'string', 'max:150'],
            'dni' => [
                'required',
                'string',
                'max:15',
                'regex:/^[0-9]+$/',
                'unique:empleados,dni,' . $empleado->id,
            ],
            'cargo' => ['required', 'string', 'max:100'],
            'telefono' => ['nullable', 'string', 'max:20', 'regex:/^[0-9+ ]*$/'],
        ]);

        $empleado->nombre_completo = $datos['nombre_completo'];
        $empleado->dni = $datos['dni'];
        $empleado->cargo = $datos['cargo'];
        $empleado->telefono = $datos['telefono'] ?? null;
        $empleado->save();

        return redirect('/empleados')->with('success', 'Empleado actualizado correctamente.');
    }

    /**
     * Activar o desactivar un empleado.
     */
    public function cambiarEstado(Empleado $empleado): RedirectResponse
    {
        $empleado->estado = !$empleado->estado;
        $empleado->save();

        return redirect('/empleados')->with(
            'success',
            $empleado->estado
                ? 'Empleado activado correctamente.'
                : 'Empleado desactivado correctamente.'
        );
    }
}
