<?php

namespace App\Http\Controllers;

use App\Models\Marca;
use Illuminate\Contracts\View\View;

class MarcaController extends Controller
{
    public function index(): View
    {
        $marcas = Marca::orderBy('nombre')->get();

        return view('marcas', compact('marcas'));
    }
}
