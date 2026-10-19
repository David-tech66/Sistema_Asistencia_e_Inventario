<?php

namespace App\Http\Controllers;

use App\Models\Asistencia;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class AsistenciaController extends Controller
{
    public function index(): View
    {
        $asistencias = Asistencia::with('user')
            ->orderByDesc('fecha')
            ->orderByDesc('hora_entrada')
            ->get();

        $empleados = User::orderBy('nombre_usuario')->get();

        return view('asistencias', compact('asistencias', 'empleados'));
    }

    public function registrarEntrada(Request $request): RedirectResponse
    {
        $datos = $request->validate([
            'user_id' => ['required', 'exists:users,id'],
        ]);

        $hoy = now()->toDateString();

        $asistencia = Asistencia::where('user_id', $datos['user_id'])
            ->where('fecha', $hoy)
            ->first();

        if ($asistencia) {
            return redirect('/asistencias')
                ->with('error', 'Este empleado ya tiene una asistencia registrada para hoy.');
        }

        Asistencia::create([
            'user_id' => $datos['user_id'],
            'fecha' => $hoy,
            'hora_entrada' => now()->format('H:i:s'),
            'hora_salida' => null,
        ]);

        return redirect('/asistencias')
            ->with('success', 'Entrada registrada correctamente.');
    }
}