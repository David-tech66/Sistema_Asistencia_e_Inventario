<?php

namespace App\Http\Controllers;

use App\Models\Proveedor;
use Illuminate\Contracts\View\View;

class ProveedorController extends Controller
{
    public function index(): View
    {
        $proveedores = Proveedor::orderBy('razon_social')->get();

        return view('proveedores', compact('proveedores'));
    }
}
