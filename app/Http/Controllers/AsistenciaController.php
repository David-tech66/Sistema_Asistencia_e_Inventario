<?php

namespace App\Http\Controllers;

use App\Models\Asistencia;
use Illuminate\Contracts\View\View;

class AsistenciaController extends Controller
{
    public function index(): View
    {
        $asistencias = Asistencia::all();
        return view('asistencias', compact('asistencias'));
    }
}
