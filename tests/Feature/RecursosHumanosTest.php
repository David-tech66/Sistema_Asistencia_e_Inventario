<?php

namespace Tests\Feature;

use App\Models\Empleado;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RecursosHumanosTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): User
    {
        return User::factory()->create([
            'rol' => 'admin',
            'estado' => true,
        ]);
    }

    public function test_login_con_rol_empleado_tambien_entra_al_panel(): void
    {
        $user = User::factory()->create([
            'rol' => 'empleado',
            'estado' => true,
        ]);

        $response = $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

        $response->assertRedirect('/dashboard');
        $this->assertAuthenticated();
    }

    public function test_rutas_del_panel_requieren_sesion(): void
    {
        foreach (['/dashboard', '/empleados', '/users', '/asistencias'] as $ruta) {
            $this->get($ruta)->assertRedirect('/login');
        }
    }

    public function test_listado_de_empleados(): void
    {
        $this->actingAs($this->admin())
            ->get('/empleados')
            ->assertOk()
            ->assertSee('Gestión de empleados');
    }

    public function test_crear_empleado(): void
    {
        $this->actingAs($this->admin())->post('/empleados', [
            'nombre_completo' => 'Juan Pérez López',
            'dni' => '12345678',
            'cargo' => 'Ayudante de almacén',
            'telefono' => '987654321',
        ])->assertRedirect('/empleados');

        $this->assertDatabaseHas('empleados', [
            'dni' => '12345678',
            'nombre_completo' => 'Juan Pérez López',
            'estado' => true,
        ]);
    }

    public function test_dni_duplicado_es_rechazado(): void
    {
        Empleado::factory()->create(['dni' => '87654321']);

        $this->actingAs($this->admin())->post('/empleados', [
            'nombre_completo' => 'OtroEmpleado',
            'dni' => '87654321',
            'cargo' => 'Cajero',
        ])->assertSessionHasErrors('dni');
    }

    public function test_actualizar_empleado(): void
    {
        $empleado = Empleado::factory()->create();

        $this->actingAs($this->admin())->put("/empleados/{$empleado->id}", [
            'nombre_completo' => 'Nombre Editado',
            'dni' => $empleado->dni,
            'cargo' => 'Vendedor',
            'telefono' => null,
        ])->assertRedirect('/empleados');

        $this->assertDatabaseHas('empleados', [
            'id' => $empleado->id,
            'cargo' => 'Vendedor',
        ]);
    }

    public function test_cambiar_estado_empleado(): void
    {
        $empleado = Empleado::factory()->create(['estado' => true]);

        $this->actingAs($this->admin())
            ->patch("/empleados/{$empleado->id}/estado")
            ->assertRedirect('/empleados');

        $this->assertFalse($empleado->fresh()->estado);
    }

    public function test_registrar_asistencia_por_dni(): void
    {
        $empleado = Empleado::factory()->create(['estado' => true]);

        $this->actingAs($this->admin())->post('/asistencias/registrar', [
            'modo' => 'entrada',
            'dni' => $empleado->dni,
        ])->assertRedirect('/asistencias');

        $this->assertDatabaseHas('asistencias', [
            'empleado_id' => $empleado->id,
            'fecha' => now()->toDateString(),
        ]);
    }

    public function test_no_permite_doble_asistencia_mismo_dia(): void
    {
        $empleado = Empleado::factory()->create(['estado' => true]);

        $this->actingAs($this->admin())->post('/asistencias/registrar', [
            'modo' => 'entrada',
            'dni' => $empleado->dni,
        ]);

        $this->actingAs($this->admin())->post('/asistencias/registrar', [
            'modo' => 'entrada',
            'dni' => $empleado->dni,
        ])->assertSessionHas('error');
    }

    public function test_asistencia_rechaza_dni_inexistente(): void
    {
        $this->actingAs($this->admin())->post('/asistencias/registrar', [
            'modo' => 'entrada',
            'dni' => '99999999',
        ])->assertSessionHas('error');

        $this->assertDatabaseCount('asistencias', 0);
    }

    public function test_asistencia_rechaza_empleado_inactivo(): void
    {
        $empleado = Empleado::factory()->create(['estado' => false]);

        $this->actingAs($this->admin())->post('/asistencias/registrar', [
            'modo' => 'entrada',
            'dni' => $empleado->dni,
        ])->assertSessionHas('error');

        $this->assertDatabaseCount('asistencias', 0);
    }

    public function test_asistencia_rechaza_dni_con_letras(): void
    {
        $this->actingAs($this->admin())->post('/asistencias/registrar', [
            'modo' => 'entrada',
            'dni' => '12AB5678',
        ])->assertSessionHasErrors('dni');
    }

    public function test_buscar_empleado_por_dni(): void
    {
        $empleado = Empleado::factory()->create([
            'estado' => true,
            'dni' => '12345678',
        ]);

        $this->actingAs($this->admin())
            ->getJson('/empleados/buscar?dni=12345678')
            ->assertOk()
            ->assertJson([
                'encontrado' => true,
                'empleado' => [
                    'nombre' => $empleado->nombre_completo,
                    'activo' => true,
                    'entrada_hoy' => null,
                    'salida_hoy' => null,
                ],
            ]);
    }

    public function test_buscar_dni_inexistente(): void
    {
        $this->actingAs($this->admin())
            ->getJson('/empleados/buscar?dni=00000000')
            ->assertOk()
            ->assertJson(['encontrado' => false]);
    }

    public function test_buscar_marca_entrada_registrada_hoy(): void
    {
        $empleado = Empleado::factory()->create(['estado' => true]);

        $this->actingAs($this->admin())->post('/asistencias/registrar', [
            'modo' => 'entrada',
            'dni' => $empleado->dni,
        ]);

        $this->actingAs($this->admin())
            ->getJson('/empleados/buscar?dni=' . $empleado->dni)
            ->assertOk()
            ->assertJsonPath('empleado.entrada_hoy', fn ($hora) => $hora !== null)
            ->assertJsonPath('empleado.salida_hoy', null);
    }

    public function test_registrar_salida_despues_de_entrada(): void
    {
        $empleado = Empleado::factory()->create(['estado' => true]);

        $this->actingAs($this->admin())->post('/asistencias/registrar', [
            'modo' => 'entrada',
            'dni' => $empleado->dni,
        ]);

        $this->actingAs($this->admin())->post('/asistencias/registrar', [
            'modo' => 'salida',
            'dni' => $empleado->dni,
        ])->assertRedirect('/asistencias')->assertSessionHas('success');

        $asistencia = \App\Models\Asistencia::where('empleado_id', $empleado->id)
            ->where('fecha', now()->toDateString())
            ->first();

        $this->assertNotNull($asistencia);
        $this->assertNotNull($asistencia->hora_entrada);
        $this->assertNotNull($asistencia->hora_salida);
    }

    public function test_salida_requiere_entrada_previa(): void
    {
        $empleado = Empleado::factory()->create(['estado' => true]);

        $this->actingAs($this->admin())->post('/asistencias/registrar', [
            'modo' => 'salida',
            'dni' => $empleado->dni,
        ])->assertSessionHas('error');

        $this->assertDatabaseCount('asistencias', 0);
    }

    public function test_no_permite_doble_salida(): void
    {
        $empleado = Empleado::factory()->create(['estado' => true]);

        $this->actingAs($this->admin())->post('/asistencias/registrar', [
            'modo' => 'entrada',
            'dni' => $empleado->dni,
        ]);

        $this->actingAs($this->admin())->post('/asistencias/registrar', [
            'modo' => 'salida',
            'dni' => $empleado->dni,
        ]);

        $this->actingAs($this->admin())->post('/asistencias/registrar', [
            'modo' => 'salida',
            'dni' => $empleado->dni,
        ])->assertSessionHas('error');
    }

    public function test_registrar_rechaza_modo_invalido(): void
    {
        $empleado = Empleado::factory()->create(['estado' => true]);

        $this->actingAs($this->admin())->post('/asistencias/registrar', [
            'modo' => 'mediodia',
            'dni' => $empleado->dni,
        ])->assertSessionHasErrors('modo');

        $this->assertDatabaseCount('asistencias', 0);
    }

    public function test_listado_de_usuarios(): void
    {
        $this->actingAs($this->admin())
            ->get('/users')
            ->assertOk()
            ->assertSee('Gestión de usuarios');
    }

    public function test_vista_asistencias_renderiza_con_empleados(): void
    {
        $empleado = Empleado::factory()->create(['estado' => true]);

        $this->actingAs($this->admin())
            ->get('/asistencias')
            ->assertOk()
            ->assertSee('Control de Asistencias')
            ->assertSee('DNI del empleado')
            ->assertSee('name="dni"', false);
    }
}
