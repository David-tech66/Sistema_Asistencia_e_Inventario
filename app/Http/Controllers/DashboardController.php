<?php

namespace App\Http\Controllers;

use App\Models\Asistencia;
use App\Models\Categoria;
use App\Models\Empleado;
use App\Models\MovimientoStock;
use App\Models\Producto;
use App\Models\Venta;
use App\Models\VentaDetalle;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Carbon;

class DashboardController extends Controller
{
    public function index(): View
    {
        // ------------------------------------------------------------
        // INVENTARIO
        // ------------------------------------------------------------
        $totalProductos = Producto::count();

        $stockCritico = Producto::whereColumn('stock_actual', '<=', 'stock_minimo')->count();

        $productosCriticos = Producto::whereColumn('stock_actual', '<=', 'stock_minimo')
            ->orderBy('stock_actual')
            ->take(5)
            ->get();

        // ------------------------------------------------------------
        // VENTAS DEL DÍA Y VARIACIÓN VS AYER
        // ------------------------------------------------------------
        $ventasHoy = (float) Venta::whereDate('created_at', today())->sum('total');
        $ventasAyer = (float) Venta::whereDate('created_at', now()->subDay())->sum('total');

        if ($ventasAyer > 0) {
            $variacion = round(($ventasHoy - $ventasAyer) / $ventasAyer * 100, 1);
            $variacionTexto = ($variacion >= 0 ? '+' : '') . number_format($variacion, 1) . '% vs ayer';
        } elseif ($ventasHoy > 0) {
            $variacionTexto = 'Primeras ventas del día';
        } else {
            $variacionTexto = 'Sin ventas hoy';
        }

        // ------------------------------------------------------------
        // VENTAS DE LA SEMANA ACTUAL (Lun..Dom)
        // ------------------------------------------------------------
        $inicioSemana = now()->startOfWeek();
        $ventasSemana = array_fill(0, 7, 0.0);

        Venta::whereBetween('created_at', [$inicioSemana, $inicioSemana->copy()->endOfWeek()])
            ->get(['total', 'created_at'])
            ->each(function ($venta) use (&$ventasSemana) {
                $indice = (int) Carbon::parse($venta->created_at)->format('N') - 1;
                $ventasSemana[$indice] += (float) $venta->total;
            });

        $escalaLinea = $this->escalaBonita(max($ventasSemana));

        $xPuntos = [80, 175, 280, 385, 490, 595, 680];
        $puntosGrafico = [];
        foreach ($ventasSemana as $indice => $totalDia) {
            $y = 220 - ($totalDia / $escalaLinea) * 200;
            $puntosGrafico[] = ['x' => $xPuntos[$indice], 'y' => round($y, 1)];
        }

        $rutaLinea = 'M ' . implode(' L ', array_map(
            fn ($punto) => $punto['x'] . ' ' . $punto['y'],
            $puntosGrafico
        ));
        $rutaArea = $rutaLinea . ' L 680 220 L 80 220 Z';

        $etiquetasY = [];
        for ($i = 0; $i < 6; $i++) {
            $etiquetasY[] = $this->formatearNumero($escalaLinea * (1 - $i * 0.2));
        }

        // ------------------------------------------------------------
        // PUNTUALIDAD (entrada después de 08:10 = tardanza)
        // ------------------------------------------------------------
        $hoy = now()->toDateString();
        $asistenciasHoy = Asistencia::where('fecha', $hoy)->get();

        $aTiempo = $asistenciasHoy->where('estado', 'presente')->count();
        $tardanzas = $asistenciasHoy->where('estado', 'tardanza')->count();
        $empleadosActivos = Empleado::where('estado', true)->count();
        $presentes = $aTiempo + $tardanzas;
        $faltas = max($empleadosActivos - $presentes, 0);
        $totalPuntualidad = $presentes + $faltas;

        // ------------------------------------------------------------
        // GAUGE DE ASISTENCIAS (presentes / activos)
        // ------------------------------------------------------------
        $pctGauge = $empleadosActivos > 0 ? min($presentes / $empleadosActivos, 1) : 0;
        $anguloGauge = M_PI * (1 - $pctGauge);
        $gaugeFinX = round(60 + 50 * cos($anguloGauge), 1);
        $gaugeFinY = round(60 - 50 * sin($anguloGauge), 1);

        // ------------------------------------------------------------
        // ARCO DEL GRÁFICO CIRCULAR
        // ------------------------------------------------------------
        $circunferencia = round(2 * M_PI * 60, 1);

        if ($totalPuntualidad > 0) {
            $lenFaltas = round($faltas / $totalPuntualidad * $circunferencia, 1);
            $lenTardanzas = round($tardanzas / $totalPuntualidad * $circunferencia, 1);
            $lenTiempo = max(0, round($circunferencia - $lenFaltas - $lenTardanzas, 1));
        } else {
            $lenFaltas = 0;
            $lenTardanzas = 0;
            $lenTiempo = 0;
        }

        // ------------------------------------------------------------
        // CATEGORÍAS DE MAYOR ROTACIÓN (unidades vendidas)
        // ------------------------------------------------------------
        $categoriasRotacion = Categoria::query()
            ->select('categorias.id', 'categorias.nombre_categoria')
            ->selectRaw('COALESCE(SUM(venta_detalles.cantidad), 0) AS volumen')
            ->leftJoin('productos', 'productos.categoria_id', '=', 'categorias.id')
            ->leftJoin('venta_detalles', 'venta_detalles.producto_id', '=', 'productos.id')
            ->groupBy('categorias.id', 'categorias.nombre_categoria')
            ->orderByDesc('volumen')
            ->havingRaw('volumen > 0')
            ->take(4)
            ->get();

        $escalaBarras = $categoriasRotacion->isEmpty()
            ? 50
            : $this->escalaBonita((float) $categoriasRotacion->first()->volumen);

        $ticksBarras = [];
        for ($i = 0; $i <= 5; $i++) {
            $ticksBarras[] = $this->formatearNumero($escalaBarras * $i / 5);
        }

        // ------------------------------------------------------------
        // TOP PRODUCTOS VENDIDOS HOY (unidades y S/ del día)
        // ------------------------------------------------------------
        $topProductosHoy = VentaDetalle::query()
            ->select('productos.id', 'productos.nombre')
            ->selectRaw('COALESCE(SUM(venta_detalles.cantidad), 0) AS unidades')
            ->selectRaw('COALESCE(SUM(venta_detalles.subtotal), 0) AS monto')
            ->join('ventas', 'ventas.id', '=', 'venta_detalles.venta_id')
            ->join('productos', 'productos.id', '=', 'venta_detalles.producto_id')
            ->whereDate('ventas.created_at', today())
            ->groupBy('productos.id', 'productos.nombre')
            ->orderByDesc('unidades')
            ->orderByDesc('monto')
            ->take(5)
            ->get();

        // ------------------------------------------------------------
        // ÚLTIMOS MOVIMIENTOS DE STOCK (ventas, escáner, ajustes manuales)
        // ------------------------------------------------------------
        $movimientos = MovimientoStock::with(['producto', 'user'])
            ->latest('id')
            ->take(5)
            ->get();

        return view('dashboard', compact(
            'totalProductos',
            'stockCritico',
            'productosCriticos',
            'ventasHoy',
            'variacionTexto',
            'ventasSemana',
            'puntosGrafico',
            'rutaLinea',
            'rutaArea',
            'etiquetasY',
            'aTiempo',
            'tardanzas',
            'faltas',
            'totalPuntualidad',
            'empleadosActivos',
            'presentes',
            'pctGauge',
            'gaugeFinX',
            'gaugeFinY',
            'circunferencia',
            'lenFaltas',
            'lenTardanzas',
            'lenTiempo',
            'categoriasRotacion',
            'escalaBarras',
            'ticksBarras',
            'topProductosHoy',
            'movimientos'
        ));
    }

    /**
     * Redondea una escala a valores "bonitos" (1, 2, 2.5, 5, 10 × 10^n).
     */
    private function escalaBonita(float $maximo): float
    {
        if ($maximo <= 0) {
            return 100;
        }

        $exponente = floor(log10($maximo));
        $base = pow(10, $exponente);
        $fraccion = $maximo / $base;

        foreach ([1, 2, 2.5, 5, 10] as $candidata) {
            if ($fraccion <= $candidata + 1e-9) {
                return $candidata * $base;
            }
        }

        return 10 * $base;
    }

    /**
     * Formatea números de ejes con miles y sin decimales innecesarios.
     */
    private function formatearNumero(float $valor): string
    {
        if (abs($valor - round($valor)) < 0.001) {
            return number_format(round($valor), 0, '.', ',');
        }

        return rtrim(rtrim(number_format($valor, 2, '.', ','), '0'), '.');
    }
}
