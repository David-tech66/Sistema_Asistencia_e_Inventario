<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use Illuminate\Contracts\View\View;

class ClienteController extends Controller
{
    public function index(): View
    {
        $clientes = Cliente::orderBy('nombre')->get();

        return view('clientes', compact('clientes'));
    }
}
