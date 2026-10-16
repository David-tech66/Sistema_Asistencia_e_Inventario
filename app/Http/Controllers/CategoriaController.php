<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use Illuminate\Contracts\View\View;

class CategoriaController extends Controller
{
    public function index(): View
    {
        $categorias = Categoria::orderBy('nombre')->get();

        return view('categorias', compact('categorias'));
    }
}
