<?php

namespace App\Http\Controllers;

use Illuminate\Contracts\View\View;

class ReporteController extends Controller
{
    public function productos(): View
    {
        return view('reporte_productos');
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
