<?php

namespace Tests\Feature;

use App\Models\Asistencia;
use App\Models\Categoria;
use App\Models\Empleado;
use App\Models\Marca;
use App\Models\Producto;
use App\Models\Proveedor;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ReportesTest extends TestCase
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
            'nombre' => 'Laptop Generica',
            'stock_actual' => 10,
            'stock_minimo' => 5,
            'precio_compra' => 100,
            'precio_venta' => 150,
        ], $datos));
    }

    private function asistencia(array $datos = []): Asistencia
    {
        return Asistencia::create(array_merge([
            'empleado_id' => Empleado::factory()->create()->id,
            'fecha' => '2026-10-21',
            'hora_entrada' => '08:00:00',
            'hora_salida' => '18:00:00',
            'estado' => 'presente',
            'salida_anticipada' => false,
        ], $datos));
    }

    public function test_reporte_productos_es_solo_lectura(): void
    {
        $this->producto();

        $response = $this->actingAs($this->admin())->get('/reportes/productos');

        $response->assertOk()
            ->assertSee('consulta precios, stock')
            ->assertDontSee('Nuevo producto')
            ->assertDontSee('modal-producto-crear')
            ->assertDontSee('modal-producto-editar')
            ->assertDontSee('form-eliminar')
            ->assertDontSee('btn-delete')
            ->assertDontSee('btn-edit')
            ->assertDontSee('card-actions')
            ->assertDontSee('editarProducto')
            ->assertDontSee('eliminarProducto')
            ->assertDontSee('abrirModal')
            ->assertDontSee('_method');
    }

    public function test_reporte_productos_conserva_buscador(): void
    {
        $this->producto();

        $response = $this->actingAs($this->admin())->get('/reportes/productos');

        $response->assertOk()
            ->assertSee('buscar-producto')
            ->assertSee('Laptop Generica');
    }

    public function test_reporte_ventas_es_solo_lectura(): void
    {
        $response = $this->actingAs($this->admin())->get('/reportes/ventas');

        $response->assertOk()
            ->assertDontSee('btn-delete')
            ->assertDontSee('btn-edit')
            ->assertDontSee('form-eliminar')
            ->assertDontSee('_method')
            ->assertDontSee('abrirModal');
    }

    public function test_reporte_asistencias_no_tiene_acciones_mutantes(): void
    {
        $response = $this->actingAs($this->admin())->get('/reportes/asistencias');

        $response->assertOk()
            ->assertSee('Reporte de Asistencias')
            ->assertDontSee('btn-delete')
            ->assertDontSee('btn-edit')
            ->assertDontSee('form-eliminar')
            ->assertDontSee('_method')
            ->assertDontSee('abrirModal');
    }

    public function test_modulo_productos_conserva_la_gestion_completa(): void
    {
        $this->producto();

        $response = $this->actingAs($this->admin())->get('/productos');

        $response->assertOk()
            ->assertSee('modal-producto-crear')
            ->assertSee('modal-producto-editar')
            ->assertSee('modal-eliminar')
            ->assertSee('editarProducto')
            ->assertSee('eliminarProducto')
            ->assertSee('form-eliminar')
            ->assertSee('btn-delete');
    }

    public function test_editar_producto_devuelve_json_desde_el_modulo(): void
    {
        $producto = $this->producto();

        $response = $this->actingAs($this->admin())
            ->get('/productos/' . $producto->id . '/edit');

        $response->assertOk()->assertJson([
            'id' => $producto->id,
            'nombre' => 'Laptop Generica',
        ]);
    }

    public function test_eliminar_producto_funciona_desde_el_modulo(): void
    {
        $producto = $this->producto();

        $this->actingAs($this->admin())
            ->delete('/productos/' . $producto->id)
            ->assertRedirect();

        $this->assertDatabaseMissing('productos', ['id' => $producto->id]);
    }

    public function test_reporte_asistencias_muestra_stats_tabla_y_filtros(): void
    {
        $this->asistencia();
        $this->asistencia([
            'hora_entrada' => '08:45:00',
            'hora_salida' => '16:30:00',
            'estado' => 'tardanza',
            'salida_anticipada' => true,
        ]);
        $this->asistencia([
            'hora_entrada' => '08:05:00',
            'hora_salida' => null,
            'estado' => 'presente',
        ]);

        $response = $this->actingAs($this->admin())->get('/reportes/asistencias');

        $response->assertOk()
            ->assertSee('Total registros')
            ->assertSee('Presentes')
            ->assertSee('Tardanzas')
            ->assertSee('Salidas anticipadas')
            ->assertSee('filtro-estado')
            ->assertSee('filtro-desde')
            ->assertSee('data-fecha')
            ->assertSee('data-estado')
            ->assertSee('Mostrando');

        $this->assertEquals(3, $response->viewData('asistencias')->count());
        $this->assertEquals(2, $response->viewData('presentes'));
        $this->assertEquals(1, $response->viewData('tardanzas'));
        $this->assertEquals(1, $response->viewData('sinSalida'));
        $this->assertEquals(1, $response->viewData('salidasAnticipadas'));
    }

    public function test_reporte_asistencias_sin_datos_muestra_estado_vacio(): void
    {
        $response = $this->actingAs($this->admin())->get('/reportes/asistencias');

        $response->assertOk()->assertSee('No hay asistencias registradas');
    }

    public function test_reporte_asistencias_sigue_solo_lectura_con_datos(): void
    {
        $this->asistencia();

        $response = $this->actingAs($this->admin())->get('/reportes/asistencias');

        $response->assertOk()
            ->assertSee('solo lectura')
            ->assertDontSee('btn-delete')
            ->assertDontSee('btn-edit')
            ->assertDontSee('form-eliminar')
            ->assertDontSee('_method')
            ->assertDontSee('eliminarAsistencia')
            ->assertDontSee('abrirModal');
    }
}
