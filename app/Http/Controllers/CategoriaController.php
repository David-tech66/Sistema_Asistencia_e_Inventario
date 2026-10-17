<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use App\Models\Producto;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CategoriaController extends Controller
{
    public function index(): View
    {
        $categorias = Categoria::orderBy('nombre_categoria')->get();

        return view('categorias', compact('categorias'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'nombre_categoria' => ['required', 'string', 'max:100', Rule::unique('categorias', 'nombre_categoria')],
            'descripcion' => ['nullable', 'string', 'max:255'],
        ]);

        Categoria::create($data);

        return redirect()->back()->with('exito', 'Categoría registrada correctamente.');
    }

    public function edit(Categoria $categoria): JsonResponse
    {
        return response()->json($categoria);
    }

    public function update(Request $request, Categoria $categoria): RedirectResponse
    {
        $data = $request->validate([
            'nombre_categoria' => ['required', 'string', 'max:100', Rule::unique('categorias', 'nombre_categoria')->ignore($categoria->id)],
            'descripcion' => ['nullable', 'string', 'max:255'],
        ]);

        $categoria->update($data);

        return redirect()->back()->with('exito', 'Categoría actualizada correctamente.');
    }

    public function destroy(Categoria $categoria): RedirectResponse
    {
        if (Producto::where('categoria_id', $categoria->id)->exists()) {
            return redirect()->back()->with('error', 'No se puede eliminar: la categoría tiene productos asociados.');
        }

        $categoria->delete();

        return redirect()->back()->with('exito', 'Categoría eliminada correctamente.');
    }
}
