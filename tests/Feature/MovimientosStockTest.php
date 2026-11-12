<?php

namespace Tests\Feature;

use App\Models\Categoria;
use App\Models\Cliente;
use App\Models\MovimientoStock;
use App\Models\Marca;
use App\Models\Producto;
use App\Models\Proveedor;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MovimientosStockTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): User
    {
        return User::factory()->create([
            'rol' => 'admin',
            'estado' => true,
        ]);
    }

    private function producto(array $datos = []): Producto
    {
        return Producto::create(array_merge([
            'categoria_id' => Categoria::create(['nombre_categoria' => 'Laptops'])->id,
            'marca_id' => Marca::create(['nombre' => 'Marca ' . uniqid()])->id,
            'proveedor_id' => Proveedor::create([
                'razon_social' => 'Distribuidora ' . uniqid(),
                'ruc' => substr((string) uniqid('', true), 0, 11),
            ])->id,
            'codigo_qr' => 'QR-' . uniqid(),
            'nombre' => 'Laptop Genérica',
            'stock_actual' => 10,
            'stock_minimo' => 5,
            'precio_compra' => 100,
            'precio_venta' => 150,
        ], $datos));
    }

    public function test_venta_registra_salida_de_stock_con_comprobante(): void
    {
        $producto = $this->producto(['stock_actual' => 10]);
        $cliente = Cliente::create(['documento' => '12345678', 'nombre' => 'Cliente Test']);

        $response = $this->actingAs($this->admin())->postJson('/ventas', [
            'cliente_id' => $cliente->id,
            'tipo_comprobante' => 'Boleta',
            'items' => [
                ['producto_id' => $producto->id, 'cantidad' => 3],
            ],
        ]);

        $response->assertOk()->assertJson(['exito' => true]);

        $this->assertEquals(7, $producto->fresh()->stock_actual);

        $movimiento = MovimientoStock::where('producto_id', $producto->id)->firstOrFail();
        $this->assertEquals('salida', $movimiento->tipo);
        $this->assertEquals(3, $movimiento->cantidad);
        $this->assertEquals(7, $movimiento->stock_resultante);
        $this->assertStringStartsWith('Venta ', $movimiento->motivo);
        $this->assertEquals($response->json('venta.numero_comprobante'), str_replace('Venta ', '', $movimiento->motivo));
        $this->assertNotNull($movimiento->user_id);
    }

    public function test_escaneo_de_productos_registra_entrada_de_conteo_fisico(): void
    {
        $producto = $this->producto(['stock_actual' => 4]);

        $response = $this->actingAs($this->admin())->postJson('/productos/escanear', [
            'codigo' => $producto->codigo_qr,
        ]);

        $response->assertOk()->assertJsonPath('producto.stock_actual', 5);

        $this->assertEquals(5, $producto->fresh()->stock_actual);

        $movimiento = MovimientoStock::where('producto_id', $producto->id)->firstOrFail();
        $this->assertEquals('entrada', $movimiento->tipo);
        $this->assertEquals(1, $movimiento->cantidad);
        $this->assertEquals(5, $movimiento->stock_resultante);
        $this->assertStringContainsString('Conteo físico', $movimiento->motivo);
    }

    public function test_ajuste_manual_de_entrada_suma_stock_y_registra_motivo(): void
    {
        $producto = $this->producto(['stock_actual' => 10]);

        $response = $this->actingAs($this->admin())->postJson("/productos/{$producto->id}/stock", [
            'tipo' => 'entrada',
            'cantidad' => 5,
            'motivo' => 'Compra a proveedor',
        ]);

        $response->assertOk()->assertJson(['exito' => true]);
        $response->assertJsonPath('producto.stock_actual', 15);

        $this->assertEquals(15, $producto->fresh()->stock_actual);

        $movimiento = MovimientoStock::where('producto_id', $producto->id)->firstOrFail();
        $this->assertEquals('entrada', $movimiento->tipo);
        $this->assertEquals(5, $movimiento->cantidad);
        $this->assertEquals('Compra a proveedor', $movimiento->motivo);
        $this->assertEquals(15, $movimiento->stock_resultante);
        $this->assertNotNull($movimiento->user_id);
    }

    public function test_ajuste_manual_de_salida_resta_stock(): void
    {
        $producto = $this->producto(['stock_actual' => 10]);

        $response = $this->actingAs($this->admin())->postJson("/productos/{$producto->id}/stock", [
            'tipo' => 'salida',
            'cantidad' => 3,
            'motivo' => 'Merma por daño',
        ]);

        $response->assertOk()->assertJson(['exito' => true]);
        $response->assertJsonPath('producto.stock_actual', 7);

        $this->assertEquals(7, $producto->fresh()->stock_actual);

        $movimiento = MovimientoStock::where('producto_id', $producto->id)->firstOrFail();
        $this->assertEquals('salida', $movimiento->tipo);
        $this->assertEquals('Merma por daño', $movimiento->motivo);
    }

    public function test_salida_manual_no_puede_exceder_stock(): void
    {
        $producto = $this->producto(['stock_actual' => 2]);

        $response = $this->actingAs($this->admin())->postJson("/productos/{$producto->id}/stock", [
            'tipo' => 'salida',
            'cantidad' => 5,
            'motivo' => 'Intento inválido',
        ]);

        $response->assertStatus(422);
        $this->assertEquals(2, $producto->fresh()->stock_actual);
        $this->assertEquals(0, MovimientoStock::count());
    }

    public function test_ajuste_manual_requiere_motivo(): void
    {
        $producto = $this->producto();

        $response = $this->actingAs($this->admin())->postJson("/productos/{$producto->id}/stock", [
            'tipo' => 'entrada',
            'cantidad' => 1,
            'motivo' => '',
        ]);

        $response->assertStatus(422)->assertJsonValidationErrors(['motivo']);
        $this->assertEquals(0, MovimientoStock::count());
    }

    public function test_invitado_no_puede_ajustar_stock(): void
    {
        $producto = $this->producto();

        $response = $this->postJson("/productos/{$producto->id}/stock", [
            'tipo' => 'entrada',
            'cantidad' => 1,
            'motivo' => 'No autorizado',
        ]);

        $response->assertStatus(401);
        $this->assertEquals(0, MovimientoStock::count());
    }

    public function test_pagina_productos_muestra_historial_de_movimientos(): void
    {
        $producto = $this->producto();

        $this->actingAs($this->admin())->postJson("/productos/{$producto->id}/stock", [
            'tipo' => 'entrada',
            'cantidad' => 8,
            'motivo' => 'Reposición semanal',
        ]);

        $response = $this->actingAs($this->admin())->get('/productos');

        $response->assertOk()
            ->assertSee('Movimientos de stock')
            ->assertSee('Reposición semanal')
            ->assertSee('Ajustar stock');
    }
}
