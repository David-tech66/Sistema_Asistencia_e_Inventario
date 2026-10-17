<?php

namespace App\Http\Controllers;

use App\Models\Marca;
use App\Models\Producto;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class MarcaController extends Controller
{
    public function index(): View
    {
        $marcas = Marca::orderBy('nombre')->get();

        return view('marcas', compact('marcas'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'nombre' => ['required', 'string', 'max:100', Rule::unique('marcas', 'nombre')],
            'descripcion' => ['nullable', 'string', 'max:255'],
        ]);

        Marca::create($data);

        return redirect()->back()->with('exito', 'Marca registrada correctamente.');
    }

    public function edit(Marca $marca): JsonResponse
    {
        return response()->json($marca);
    }

    public function update(Request $request, Marca $marca): RedirectResponse
    {
        $data = $request->validate([
            'nombre' => ['required', 'string', 'max:100', Rule::unique('marcas', 'nombre')->ignore($marca->id)],
            'descripcion' => ['nullable', 'string', 'max:255'],
        ]);

        $marca->update($data);

        return redirect()->back()->with('exito', 'Marca actualizada correctamente.');
    }

    public function destroy(Marca $marca): RedirectResponse
    {
        if (Producto::where('marca_id', $marca->id)->exists()) {
            return redirect()->back()->with('error', 'No se puede eliminar: la marca tiene productos asociados.');
        }

        $marca->delete();

        return redirect()->back()->with('exito', 'Marca eliminada correctamente.');
    }
}
