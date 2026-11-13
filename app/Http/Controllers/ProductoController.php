<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use App\Models\Configuracion;
use App\Models\MovimientoStock;
use App\Models\Marca;
use App\Models\Producto;
use App\Models\Proveedor;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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
        $movimientos = MovimientoStock::with(['producto', 'user'])
            ->latest('id')
            ->limit(30)
            ->get();

        $stockMinimoDefecto = (int) Configuracion::obtener('stock_minimo_defecto', '5');

        return view('productos', compact('productos', 'categorias', 'marcas', 'proveedores', 'movimientos', 'stockMinimoDefecto'));
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

        MovimientoStock::create([
            'producto_id' => $producto->id,
            'user_id' => Auth::id(),
            'tipo' => 'entrada',
            'cantidad' => 1,
            'motivo' => 'Conteo físico (escáner)',
            'stock_resultante' => $producto->fresh()->stock_actual,
        ]);

        return response()->json([
            'encontrado' => true,
            'producto' => [
                'id' => $producto->id,
                'nombre' => $producto->nombre,
                'stock_actual' => $producto->stock_actual,
            ],
        ]);
    }

    /**
     * Ajuste manual de stock (entrada o salida) con motivo obligatorio.
     * Para salidas: merma, daño, robo, uso interno, etc.
     */
    public function ajustarStock(Request $request, Producto $producto): JsonResponse
    {
        $datos = $request->validate([
            'tipo' => ['required', 'in:entrada,salida'],
            'cantidad' => ['required', 'integer', 'min:1', 'max:100000'],
            'motivo' => ['required', 'string', 'max:255'],
        ]);

        try {
            $resultado = DB::transaction(function () use ($datos, $producto) {
                $bloqueado = Producto::lockForUpdate()->findOrFail($producto->id);

                if ($datos['tipo'] === 'salida' && $bloqueado->stock_actual < $datos['cantidad']) {
                    throw new \Exception(
                        "Stock insuficiente para salida. Disponible: {$bloqueado->stock_actual}, solicitado: {$datos['cantidad']}"
                    );
                }

                if ($datos['tipo'] === 'entrada') {
                    $bloqueado->increment('stock_actual', $datos['cantidad']);
                } else {
                    $bloqueado->decrement('stock_actual', $datos['cantidad']);
                }

                $bloqueado->refresh();

                MovimientoStock::create([
                    'producto_id' => $bloqueado->id,
                    'user_id' => Auth::id(),
                    'tipo' => $datos['tipo'],
                    'cantidad' => $datos['cantidad'],
                    'motivo' => $datos['motivo'],
                    'stock_resultante' => $bloqueado->stock_actual,
                ]);

                return $bloqueado;
            });
        } catch (\Exception $e) {
            return response()->json(['exito' => false, 'mensaje' => $e->getMessage()], 422);
        }

        return response()->json([
            'exito' => true,
            'mensaje' => ($datos['tipo'] === 'entrada' ? 'Entrada' : 'Salida') . ' de stock registrada.',
            'producto' => [
                'id' => $resultado->id,
                'stock_actual' => $resultado->stock_actual,
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
