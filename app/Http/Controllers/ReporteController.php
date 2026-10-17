<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use App\Models\Marca;
use App\Models\Producto;
use App\Models\Proveedor;
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
        return view('reporte_ventas');
    }
}
