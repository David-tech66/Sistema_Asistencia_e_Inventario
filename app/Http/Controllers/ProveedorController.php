<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use App\Models\Proveedor;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ProveedorController extends Controller
{
    public function index(): View
    {
        $proveedores = Proveedor::orderBy('razon_social')->get();

        return view('proveedores', compact('proveedores'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validar($request);

        Proveedor::create($data);

        return redirect()->back()->with('exito', 'Proveedor registrado correctamente.');
    }

    public function edit(Proveedor $proveedor): JsonResponse
    {
        return response()->json($proveedor);
    }

    public function update(Request $request, Proveedor $proveedor): RedirectResponse
    {
        $data = $this->validar($request, $proveedor->id);

        $proveedor->update($data);

        return redirect()->back()->with('exito', 'Proveedor actualizado correctamente.');
    }

    public function destroy(Proveedor $proveedor): RedirectResponse
    {
        if (Producto::where('proveedor_id', $proveedor->id)->exists()) {
            return redirect()->back()->with('error', 'No se puede eliminar: el proveedor tiene productos asociados.');
        }

        $proveedor->delete();

        return redirect()->back()->with('exito', 'Proveedor eliminado correctamente.');
    }

    private function validar(Request $request, ?int $id = null): array
    {
        return $request->validate([
            'razon_social' => ['required', 'string', 'max:150'],
            'ruc' => ['required', 'string', 'max:20', Rule::unique('proveedores', 'ruc')->ignore($id)],
            'telefono' => ['nullable', 'string', 'max:20'],
            'correo' => ['nullable', 'email', 'max:100'],
            'direccion' => ['nullable', 'string', 'max:200'],
        ]);
    }
}
