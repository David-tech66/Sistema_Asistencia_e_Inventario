<?php

namespace App\Http\Controllers;

use App\Models\Asistencia;
use App\Models\Configuracion;
use App\Models\Empleado;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class AsistenciaController extends Controller
{
    /**
     * Horario por defecto si no hay configuración guardada.
     */
    public const HORA_LIMITE_TARDANZA = '08:10:00';
    public const HORA_LIMITE_SALIDA = '18:00:00';

    public function index(): View
    {
        $asistencias = Asistencia::with('empleado')
            ->orderByDesc('fecha')
            ->orderByDesc('hora_entrada')
            ->get();

        $empleados = Empleado::where('estado', true)
            ->orderBy('nombre_completo')
            ->get();

        return view('asistencias', [
            'asistencias' => $asistencias,
            'empleados' => $empleados,
            'horarioEntrada' => Configuracion::obtener('hora_entrada', '08:10'),
            'horarioSalida' => Configuracion::obtener('hora_salida', '18:00'),
            'toleranciaTardanza' => self::toleranciaVigente(),
        ]);
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

            $horaEntrada = now()->format('H:i:s');
            $limiteEntrada = $this->limiteTardanza();

            Asistencia::create([
                'empleado_id' => $empleado->id,
                'fecha' => $hoy,
                'hora_entrada' => $horaEntrada,
                'hora_salida' => null,
                'estado' => $horaEntrada > $limiteEntrada ? 'tardanza' : 'presente',
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

        $horaSalida = now()->format('H:i:s');
        $limiteSalida = $this->normalizarHora(
            Configuracion::obtener('hora_salida', self::HORA_LIMITE_SALIDA)
        );

        $asistencia->hora_salida = $horaSalida;
        $asistencia->salida_anticipada = $horaSalida < $limiteSalida;
        $asistencia->save();

        $mensajeSalida = 'Salida registrada para ' . $empleado->nombre_completo
            . ($asistencia->salida_anticipada ? ' (salida anticipada).' : '.');

        return redirect('/asistencias')->with('success', $mensajeSalida);
    }

    /**
     * Convierte HH:MM a HH:MM:SS para comparar con las horas guardadas.
     */
    private function normalizarHora(string $hora): string
    {
        return strlen($hora) === 5 ? $hora . ':00' : $hora;
    }

    /**
     * Hora límite real para marcar tardanza: hora de entrada + minutos de tolerancia.
     * Ej.: entrada 08:10 con 5 min de tolerancia → 08:15:00.
     */
    private function limiteTardanza(): string
    {
        $base = $this->normalizarHora(
            Configuracion::obtener('hora_entrada', self::HORA_LIMITE_TARDANZA)
        );

        $tolerancia = max(0, (int) Configuracion::obtener('tolerancia_tardanza', '0'));

        if ($tolerancia === 0) {
            return $base;
        }

        $minutos = ((int) substr($base, 0, 2)) * 60
            + ((int) substr($base, 3, 2))
            + $tolerancia;

        // Nunca pasa de las 23:59
        $minutos = min($minutos, 23 * 60 + 59);

        return sprintf('%02d:%02d:00', intdiv($minutos, 60), $minutos % 60);
    }

    /**
     * Minutos de tolerancia vigentes (para mostrar en la vista).
     */
    public static function toleranciaVigente(): int
    {
        return max(0, (int) Configuracion::obtener('tolerancia_tardanza', '0'));
    }

    /**
     * Elimina un registro de asistencia.
     */
    public function destroy(Asistencia $asistencia): RedirectResponse
    {
        $nombre = $asistencia->empleado?->nombre_completo ?? 'Empleado no disponible';
        $fecha = $asistencia->fecha;

        $asistencia->delete();

        return redirect('/asistencias')
            ->with('success', 'Asistencia del ' . $fecha . ' de ' . $nombre . ' eliminada.');
    }
}
