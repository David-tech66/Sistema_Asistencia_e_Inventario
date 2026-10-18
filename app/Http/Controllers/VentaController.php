<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\Producto;
use App\Models\Venta;
use App\Models\VentaDetalle;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class VentaController extends Controller
{
    public function index(): View
    {
        $ventas = Venta::with(['cliente', 'detalles.producto'])->orderByDesc('id')->get();
        $clientes = Cliente::orderBy('nombre')->get();

        return view('ventas', compact('ventas', 'clientes'));
    }

    public function escanear(Request $request): JsonResponse
    {
        $request->validate(['codigo' => 'required|string']);

        $producto = Producto::where('codigo_qr', $request->codigo)->first();

        if (!$producto) {
            return response()->json(['encontrado' => false]);
        }

        if ($producto->stock_actual <= 0) {
            return response()->json([
                'encontrado' => true,
                'sin_stock' => true,
                'producto' => [
                    'id' => $producto->id,
                    'nombre' => $producto->nombre,
                    'precio_venta' => (float) $producto->precio_venta,
                    'stock_actual' => $producto->stock_actual,
                    'codigo_qr' => $producto->codigo_qr,
                ],
            ]);
        }

        return response()->json([
            'encontrado' => true,
            'sin_stock' => false,
            'producto' => [
                'id' => $producto->id,
                'nombre' => $producto->nombre,
                'precio_venta' => (float) $producto->precio_venta,
                'stock_actual' => $producto->stock_actual,
                'codigo_qr' => $producto->codigo_qr,
            ],
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'cliente_id' => 'required|exists:clientes,id',
            'tipo_comprobante' => 'required|in:Boleta,Factura',
            'items' => 'required|array|min:1',
            'items.*.producto_id' => 'required|exists:productos,id',
            'items.*.cantidad' => 'required|integer|min:1',
        ]);

        $cliente = Cliente::findOrFail($request->cliente_id);

        try {
            $venta = DB::transaction(function () use ($request, $cliente) {
                $prefijo = $request->tipo_comprobante === 'Boleta' ? 'B' : 'F';
                $ultima = Venta::where('tipo_comprobante', $request->tipo_comprobante)->count() + 1;
                $numero = $prefijo . '001-' . str_pad($ultima, 6, '0', STR_PAD_LEFT);

                $total = 0;
                $detalles = [];

                foreach ($request->items as $item) {
                    $producto = Producto::lockForUpdate()->findOrFail($item['producto_id']);

                    if ($producto->stock_actual < $item['cantidad']) {
                        throw new \Exception("Stock insuficiente para '{$producto->nombre}'. Disponible: {$producto->stock_actual}");
                    }

                    $subtotal = $producto->precio_venta * $item['cantidad'];
                    $total += $subtotal;

                    $producto->decrement('stock_actual', $item['cantidad']);

                    $detalles[] = [
                        'producto_id' => $producto->id,
                        'cantidad' => $item['cantidad'],
                        'precio_unitario' => $producto->precio_venta,
                        'subtotal' => $subtotal,
                    ];
                }

                $venta = Venta::create([
                    'cliente_id' => $cliente->id,
                    'user_id' => Auth::id(),
                    'tipo_comprobante' => $request->tipo_comprobante,
                    'numero_comprobante' => $numero,
                    'total' => $total,
                ]);

                foreach ($detalles as $detalle) {
                    $venta->detalles()->create($detalle);
                }

                return $venta;
            });

            $venta->load(['cliente', 'detalles.producto']);

            return response()->json([
                'exito' => true,
                'venta' => [
                    'id' => $venta->id,
                    'numero_comprobante' => $venta->numero_comprobante,
                    'tipo_comprobante' => $venta->tipo_comprobante,
                    'total' => (float) $venta->total,
                    'cliente' => $venta->cliente->nombre,
                    'detalles' => $venta->detalles->map(fn ($d) => [
                        'nombre' => $d->producto->nombre,
                        'cantidad' => $d->cantidad,
                        'precio_unitario' => (float) $d->precio_unitario,
                        'subtotal' => (float) $d->subtotal,
                    ]),
                ],
            ]);
        } catch (\Exception $e) {
            return response()->json(['exito' => false, 'mensaje' => $e->getMessage()], 422);
        }
    }
}