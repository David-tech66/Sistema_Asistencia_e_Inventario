<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\Venta;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ClienteController extends Controller
{
    public function index(): View
    {
        $clientes = Cliente::orderBy('nombre')->get();

        return view('clientes', compact('clientes'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validar($request);

        Cliente::create($data);

        return redirect()->back()->with('exito', 'Cliente registrado correctamente.');
    }

    public function edit(Cliente $cliente): JsonResponse
    {
        return response()->json($cliente);
    }

    public function update(Request $request, Cliente $cliente): RedirectResponse
    {
        $data = $this->validar($request, $cliente->id);

        $cliente->update($data);

        return redirect()->back()->with('exito', 'Cliente actualizado correctamente.');
    }

    public function destroy(Cliente $cliente): RedirectResponse
    {
        if (Venta::where('cliente_id', $cliente->id)->exists()) {
            return redirect()->back()->with('error', 'No se puede eliminar: el cliente tiene ventas asociadas.');
        }

        $cliente->delete();

        return redirect()->back()->with('exito', 'Cliente eliminado correctamente.');
    }

    private function validar(Request $request, ?int $id = null): array
    {
        return $request->validate([
            'documento' => ['required', 'string', 'max:20', Rule::unique('clientes', 'documento')->ignore($id)],
            'nombre' => ['required', 'string', 'max:200'],
            'telefono' => ['nullable', 'string', 'max:20'],
        ]);
    }
}