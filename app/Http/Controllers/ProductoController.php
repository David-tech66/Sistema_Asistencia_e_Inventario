<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use Illuminate\Contracts\View\View;

class ProductoController extends Controller
{
    public function index(): View
    {
        $productos = Producto::all();
        return view('productos', compact('productos'));
    }
}
