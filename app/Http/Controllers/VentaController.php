<?php

namespace App\Http\Controllers;

use App\Models\Venta;
use Illuminate\Contracts\View\View;

class VentaController extends Controller
{
    public function index(): View
    {
        $ventas = Venta::orderBy('id')->get();

        return view('ventas', compact('ventas'));
    }
}
