<?php

namespace Tests\Feature;

use App\Models\Asistencia;
use App\Models\Categoria;
use App\Models\Cliente;
use App\Models\Empleado;
use App\Models\Marca;
use App\Models\Producto;
use App\Models\Proveedor;
use App\Models\User;
use App\Models\Venta;
use App\Models\VentaDetalle;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class DashboardTest extends TestCase
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

    private function venta(float $total): Venta
    {
        $cliente = Cliente::create(['documento' => (string) random_int(10000000, 99999999), 'nombre' => 'Cliente']);

        return Venta::create([
            'cliente_id' => $cliente->id,
            'user_id' => $this->admin()->id,
            'tipo_comprobante' => 'Boleta',
            'numero_comprobante' => '001-' . random_int(1, 999999),
            'total' => $total,
        ]);
    }

    public function test_dashboard_muestra_kpis_reales(): void
    {
        $this->producto();
        $this->producto(['nombre' => 'Memoria RAM', 'stock_actual' => 2, 'stock_minimo' => 5]);

        $response = $this->actingAs($this->admin())->get('/dashboard');

        $response->assertOk()->assertSee('Resumen Operativo');
        $this->assertEquals(2, $response->viewData('totalProductos'));
        $this->assertEquals(1, $response->viewData('stockCritico'));
        $this->assertCount(1, $response->viewData('productosCriticos'));
        $this->assertEquals('Memoria RAM', $response->viewData('productosCriticos')->first()->nombre);
    }

    public function test_dashboard_calcula_ventas_del_dia_y_variacion(): void
    {
        $this->venta(1250);

        $response = $this->actingAs($this->admin())->get('/dashboard');

        $this->assertEquals(1250, $response->viewData('ventasHoy'));
        $this->assertEquals('Primeras ventas del día', $response->viewData('variacionTexto'));
        $response->assertSee('$1,250.00');
    }

    public function test_dashboard_variacion_vs_ayer(): void
    {
        $ayer = $this->venta(1000);
        $ayer->created_at = now()->subDay();
        $ayer->save();

        $this->venta(1120);

        $response = $this->actingAs($this->admin())->get('/dashboard');

        $this->assertEquals(1120, $response->viewData('ventasHoy'));
        $this->assertEquals('+12.0% vs ayer', $response->viewData('variacionTexto'));
    }

    public function test_dashboard_acumula_ventas_de_la_semana(): void
    {
        $this->venta(300);
        $this->venta(200);

        $response = $this->actingAs($this->admin())->get('/dashboard');

        $ventasSemana = $response->viewData('ventasSemana');
        $indiceHoy = (int) now()->format('N') - 1;

        $this->assertEquals(500, array_sum($ventasSemana));
        $this->assertEquals(500, $ventasSemana[$indiceHoy]);
    }

    public function test_dashboard_puntualidad_sin_asistencias_cuenta_faltas(): void
    {
        Empleado::factory()->create(['estado' => true]);
        Empleado::factory()->create(['estado' => true]);

        $response = $this->actingAs($this->admin())->get('/dashboard');

        $this->assertEquals(0, $response->viewData('presentes'));
        $this->assertEquals(2, $response->viewData('faltas'));
        $this->assertEquals(2, $response->viewData('totalPuntualidad'));
        $this->assertEquals(0.0, $response->viewData('pctGauge'));
    }

    public function test_dashboard_puntualidad_con_asistencia_registrada(): void
    {
        $empleado = Empleado::factory()->create(['estado' => true]);

        Asistencia::create([
            'empleado_id' => $empleado->id,
            'fecha' => now()->toDateString(),
            'hora_entrada' => '08:05:00',
            'estado' => 'presente',
        ]);

        $response = $this->actingAs($this->admin())->get('/dashboard');

        $this->assertEquals(1, $response->viewData('aTiempo'));
        $this->assertEquals(0, $response->viewData('faltas'));
        $this->assertEquals(1, $response->viewData('presentes'));
        $this->assertEquals(1.0, $response->viewData('pctGauge'));
        $this->assertEquals(1, $response->viewData('totalPuntualidad'));
    }

    public function test_dashboard_lista_los_ultimos_escaneos_del_dia(): void
    {
        $empleado = Empleado::factory()->create(['estado' => true, 'cargo' => 'Ventas']);

        Asistencia::create([
            'empleado_id' => $empleado->id,
            'fecha' => now()->toDateString(),
            'hora_entrada' => '07:58:00',
            'hora_salida' => '17:30:00',
            'estado' => 'presente',
        ]);

        $response = $this->actingAs($this->admin())->get('/dashboard');

        $escaneos = $response->viewData('escaneos');
        $this->assertCount(2, $escaneos);
        $this->assertEquals('salida', $escaneos[0]['tipo']);
        $this->assertEquals('05:30 PM', $escaneos[0]['horaTexto']);
        $this->assertEquals('entrada', $escaneos[1]['tipo']);
        $this->assertEquals('Ventas', $escaneos[1]['cargo']);
        $this->assertNotEmpty($escaneos[1]['iniciales']);
    }

    public function test_dashboard_sin_escaneos_muestra_lista_vacia(): void
    {
        $response = $this->actingAs($this->admin())->get('/dashboard');

        $this->assertCount(0, $response->viewData('escaneos'));
        $response->assertSee('Sin escaneos registrados hoy.');
    }

    public function test_dashboard_calcula_rotacion_por_categoria(): void
    {
        $categoria = Categoria::create(['nombre_categoria' => 'Componentes']);
        $producto = $this->producto(['categoria_id' => $categoria->id]);

        $venta = $this->venta(700);
        VentaDetalle::create([
            'venta_id' => $venta->id,
            'producto_id' => $producto->id,
            'cantidad' => 7,
            'precio_unitario' => 100,
            'subtotal' => 700,
        ]);

        $response = $this->actingAs($this->admin())->get('/dashboard');

        $rotacion = $response->viewData('categoriasRotacion');
        $this->assertCount(1, $rotacion);
        $this->assertEquals('Componentes', $rotacion->first()->nombre_categoria);
        $this->assertEquals(7, $rotacion->first()->volumen);
        $this->assertEquals(10, $response->viewData('escalaBarras'));
    }

    public function test_dashboard_grafico_semanal_tiene_7_puntos(): void
    {
        $this->venta(100);

        $response = $this->actingAs($this->admin())->get('/dashboard');

        $this->assertCount(7, $response->viewData('puntosGrafico'));
        $this->assertCount(6, $response->viewData('etiquetasY'));
        $this->assertNotEmpty($response->viewData('rutaLinea'));
        $this->assertStringStartsWith('M 80 ', $response->viewData('rutaLinea'));
    }

    public function test_entrada_antes_de_las_08_10_se_marca_presente(): void
    {
        Carbon::setTestNow('2026-10-22 08:09:59');
        $empleado = Empleado::factory()->create(['estado' => true]);

        $this->actingAs($this->admin())->post('/asistencias/registrar', [
            'modo' => 'entrada',
            'dni' => $empleado->dni,
        ]);

        $this->assertEquals('presente', Asistencia::first()->estado);
        Carbon::setTestNow();
    }

    public function test_entrada_despues_de_las_08_10_se_marca_tardanza(): void
    {
        Carbon::setTestNow('2026-10-22 08:10:01');
        $empleado = Empleado::factory()->create(['estado' => true]);

        $this->actingAs($this->admin())->post('/asistencias/registrar', [
            'modo' => 'entrada',
            'dni' => $empleado->dni,
        ]);

        $this->assertEquals('tardanza', Asistencia::first()->estado);
        Carbon::setTestNow();
    }

    public function test_dashboard_alerta_de_stock_se_muestra_en_tabla(): void
    {
        $this->producto(['nombre' => 'Cartucho Tinta', 'stock_actual' => 1, 'stock_minimo' => 3]);

        $this->actingAs($this->admin())
            ->get('/dashboard')
            ->assertOk()
            ->assertSee('Cartucho Tinta')
            ->assertSee('Reabastecer');
    }
}
