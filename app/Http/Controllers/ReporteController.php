<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use App\Models\Marca;
use App\Models\Producto;
use App\Models\Proveedor;
use App\Models\Venta;
use Illuminate\Contracts\View\View;

class ReporteController extends Controller
{
    public function productos(): View
    {
        $productos = Producto::with(['categoria', 'marca', 'proveedor'])->orderBy('nombre')->get();
        $categorias = Categoria::orderBy('nombre_categoria')->get();
        $marcas = Marca::orderBy('nombre')->get();
        $proveedores = Proveedor::orderBy('razon_social')->get();

        return view('reporte_productos', compact('productos', 'categorias', 'marcas', 'proveedores'));
    }

    public function asistencias(): View
    {
        return view('reporte_asistencias');
    }

    public function ventas(): View
    {
        $ventas = Venta::with(['cliente', 'user', 'detalles.producto'])
            ->orderByDesc('created_at')
            ->get();

        return view('reporte_ventas', [
            'ventas' => $ventas,
            'totalIngresos' => (float) $ventas->sum('total'),
            'totalBoletas' => $ventas->where('tipo_comprobante', 'Boleta')->count(),
            'totalFacturas' => $ventas->where('tipo_comprobante', 'Factura')->count(),
            'promedio' => $ventas->count() > 0 ? $ventas->sum('total') / $ventas->count() : 0,
            'unidadesVendidas' => $ventas->sum(fn ($v) => $v->detalles->sum('cantidad')),
        ]);
    }
}
