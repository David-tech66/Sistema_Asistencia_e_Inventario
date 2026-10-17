<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use App\Models\Marca;
use App\Models\Producto;
use App\Models\Proveedor;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class ProductoController extends Controller
{
    public function index(): View
    {
        $productos = Producto::with(['categoria', 'marca', 'proveedor'])->get();
        $categorias = Categoria::orderBy('nombre_categoria')->get();
        $marcas = Marca::orderBy('nombre')->get();
        $proveedores = Proveedor::orderBy('razon_social')->get();

        return view('productos', compact('productos', 'categorias', 'marcas', 'proveedores'));
    }

    public function escanear(Request $request): JsonResponse
    {
        $datos = $request->validate([
            'codigo' => ['required', 'string', 'max:255'],
        ]);

        $producto = Producto::where('codigo_qr', trim($datos['codigo']))->first();

        if (! $producto) {
            return response()->json([
                'encontrado' => false,
                'codigo' => trim($datos['codigo']),
            ]);
        }

        $producto->increment('stock_actual');

        return response()->json([
            'encontrado' => true,
            'producto' => [
                'id' => $producto->id,
                'nombre' => $producto->nombre,
                'stock_actual' => $producto->stock_actual,
            ],
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validar($request);
        $data['imagen'] = null;

        if ($request->hasFile('imagen')) {
            $data['imagen'] = $request->file('imagen')->store('productos', 'public');
        }

        $data['especificaciones'] = $request->filled('especificaciones')
            ? json_encode(['detalles' => $request->input('especificaciones')])
            : null;

        Producto::create($data);

        return redirect()->back()->with('exito', 'Producto registrado correctamente.');
    }

    public function edit(Producto $producto): JsonResponse
    {
        $especificaciones = $producto->especificaciones;

        if ($especificaciones) {
            $decoded = json_decode($especificaciones, true);
            if (is_array($decoded)) {
                $especificaciones = $decoded['detalles'] ?? '';
            }
        }

        $producto->especificaciones = $especificaciones;

        return response()->json($producto);
    }

    public function update(Request $request, Producto $producto): RedirectResponse
    {
        $data = $this->validar($request, $producto->id);

        if ($request->hasFile('imagen')) {
            if ($producto->imagen && Storage::disk('public')->exists($producto->imagen)) {
                Storage::disk('public')->delete($producto->imagen);
            }
            $data['imagen'] = $request->file('imagen')->store('productos', 'public');
        } else {
            unset($data['imagen']);
        }

        $data['especificaciones'] = $request->filled('especificaciones')
            ? json_encode(['detalles' => $request->input('especificaciones')])
            : null;

        $producto->update($data);

        return redirect()->back()->with('exito', 'Producto actualizado correctamente.');
    }

    public function destroy(Producto $producto): RedirectResponse
    {
        if ($producto->imagen && Storage::disk('public')->exists($producto->imagen)) {
            Storage::disk('public')->delete($producto->imagen);
        }

        $producto->delete();

        return redirect()->back()->with('exito', 'Producto eliminado correctamente.');
    }

    private function validar(Request $request, ?int $id = null): array
    {
        return $request->validate([
            'codigo_qr' => ['required', 'string', 'max:255', Rule::unique('productos', 'codigo_qr')->ignore($id)],
            'nombre' => ['required', 'string', 'max:200'],
            'categoria_id' => ['required', 'exists:categorias,id'],
            'marca_id' => ['required', 'exists:marcas,id'],
            'proveedor_id' => ['required', 'exists:proveedores,id'],
            'meses_garantia' => ['required', 'integer', 'min:0', 'max:120'],
            'stock_actual' => ['required', 'integer', 'min:0'],
            'stock_minimo' => ['required', 'integer', 'min:0'],
            'precio_compra' => ['required', 'numeric', 'min:0'],
            'precio_venta' => ['required', 'numeric', 'min:0'],
            'especificaciones' => ['nullable', 'string', 'max:2000'],
            'imagen' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);
    }
}
