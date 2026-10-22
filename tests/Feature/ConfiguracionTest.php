<?php

namespace Tests\Feature;

use App\Models\Asistencia;
use App\Models\Configuracion;
use App\Models\Empleado;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class ConfiguracionTest extends TestCase
{
    use RefreshDatabase;

    private function usuario(string $rol = 'admin'): User
    {
        return User::factory()->create([
            'rol' => $rol,
            'estado' => true,
        ]);
    }

    public function test_vista_configuracion_requiere_sesion(): void
    {
        $this->get('/configuracion')->assertRedirect('/login');
    }

    public function test_vista_configuracion_solo_admin(): void
    {
        $this->actingAs($this->usuario('admin'))
            ->get('/configuracion')
            ->assertOk()
            ->assertSee('Hora de entrada')
            ->assertSee('Hora de salida')
            ->assertSee('value="08:10"', false)
            ->assertSee('value="18:00"', false);

        $this->actingAs($this->usuario('empleado'))
            ->get('/configuracion')
            ->assertForbidden();
    }

    public function test_sidebar_muestra_configuracion_solo_admin(): void
    {
        $this->actingAs($this->usuario('admin'))
            ->get('/dashboard')
            ->assertSee('href="/configuracion"', false);

        $this->actingAs($this->usuario('empleado'))
            ->get('/dashboard')
            ->assertDontSee('href="/configuracion"', false);
    }

    public function test_post_configuracion_solo_admin(): void
    {
        $this->actingAs($this->usuario('empleado'))
            ->post('/configuracion', [
                'hora_entrada' => '09:00',
                'hora_salida' => '17:00',
            ])
            ->assertForbidden();

        $this->assertEquals('08:10', Configuracion::obtener('hora_entrada', '08:10'));
    }

    public function test_actualiza_el_horario(): void
    {
        $this->actingAs($this->usuario('admin'))
            ->post('/configuracion', [
                'hora_entrada' => '09:15',
                'hora_salida' => '17:45',
            ])
            ->assertRedirect('/configuracion')
            ->assertSessionHas('success');

        $this->assertEquals('09:15', Configuracion::obtener('hora_entrada', '08:10'));
        $this->assertEquals('17:45', Configuracion::obtener('hora_salida', '18:00'));
    }

    public function test_valida_formato_y_orden_de_las_horas(): void
    {
        $this->actingAs($this->usuario('admin'))
            ->post('/configuracion', [
                'hora_entrada' => '25:99',
                'hora_salida' => '17:00',
            ])
            ->assertSessionHasErrors('hora_entrada');

        $this->actingAs($this->usuario('admin'))
            ->post('/configuracion', [
                'hora_entrada' => '09:00',
                'hora_salida' => '07:00',
            ])
            ->assertSessionHasErrors('hora_salida');

        $this->actingAs($this->usuario('admin'))
            ->post('/configuracion', [
                'hora_entrada' => '',
                'hora_salida' => '',
            ])
            ->assertSessionHasErrors(['hora_entrada', 'hora_salida']);

        $this->assertEquals('08:10', Configuracion::obtener('hora_entrada', '08:10'));
    }

    public function test_tardanza_usa_la_hora_configurada(): void
    {
        Configuracion::guardar('hora_entrada', '09:30');

        Carbon::setTestNow('2026-10-23 09:20:00');
        $empleadoAntes = Empleado::factory()->create(['estado' => true]);

        $this->actingAs($this->usuario('admin'))->post('/asistencias/registrar', [
            'modo' => 'entrada',
            'dni' => $empleadoAntes->dni,
        ]);

        Carbon::setTestNow('2026-10-23 09:40:00');
        $empleadoDespues = Empleado::factory()->create(['estado' => true]);

        $this->actingAs($this->usuario('admin'))->post('/asistencias/registrar', [
            'modo' => 'entrada',
            'dni' => $empleadoDespues->dni,
        ]);

        $this->assertEquals(
            'presente',
            Asistencia::where('empleado_id', $empleadoAntes->id)->first()->estado
        );
        $this->assertEquals(
            'tardanza',
            Asistencia::where('empleado_id', $empleadoDespues->id)->first()->estado
        );

        Carbon::setTestNow();
    }

    public function test_salida_anticipada_usa_la_hora_configurada(): void
    {
        Configuracion::guardar('hora_salida', '18:00');

        // Salida antes de la hora → anticipada
        Carbon::setTestNow('2026-10-23 08:00:00');
        $empleadoAntes = Empleado::factory()->create(['estado' => true]);

        $this->actingAs($this->usuario('admin'))->post('/asistencias/registrar', [
            'modo' => 'entrada',
            'dni' => $empleadoAntes->dni,
        ]);

        Carbon::setTestNow('2026-10-23 12:30:00');
        $this->actingAs($this->usuario('admin'))->post('/asistencias/registrar', [
            'modo' => 'salida',
            'dni' => $empleadoAntes->dni,
        ]);

        // Salida después o en la hora → no anticipada
        Carbon::setTestNow('2026-10-23 18:10:00');
        $empleadoDespues = Empleado::factory()->create(['estado' => true]);

        $this->actingAs($this->usuario('admin'))->post('/asistencias/registrar', [
            'modo' => 'entrada',
            'dni' => $empleadoDespues->dni,
        ]);

        Carbon::setTestNow('2026-10-23 18:30:00');
        $this->actingAs($this->usuario('admin'))->post('/asistencias/registrar', [
            'modo' => 'salida',
            'dni' => $empleadoDespues->dni,
        ]);

        $this->assertTrue(
            Asistencia::where('empleado_id', $empleadoAntes->id)->first()->salida_anticipada
        );
        $this->assertFalse(
            Asistencia::where('empleado_id', $empleadoDespues->id)->first()->salida_anticipada
        );

        Carbon::setTestNow();
    }

    public function test_listado_muestra_marcas_de_tardanza_y_salida_anticipada(): void
    {
        $empleado = Empleado::factory()->create(['estado' => true]);

        Asistencia::create([
            'empleado_id' => $empleado->id,
            'fecha' => now()->toDateString(),
            'hora_entrada' => '08:35:00',
            'hora_salida' => '14:00:00',
            'estado' => 'tardanza',
            'salida_anticipada' => true,
        ]);

        $this->actingAs($this->usuario('admin'))
            ->get('/asistencias')
            ->assertOk()
            ->assertSee('Tardanza')
            ->assertSee('Salida anticipada')
            ->assertSee('Horario: entrada 08:10');
    }

    public function test_dashboard_muestra_salida_anticipada(): void
    {
        $empleado = Empleado::factory()->create(['estado' => true]);

        Asistencia::create([
            'empleado_id' => $empleado->id,
            'fecha' => now()->toDateString(),
            'hora_entrada' => '08:00:00',
            'hora_salida' => '13:00:00',
            'estado' => 'presente',
            'salida_anticipada' => true,
        ]);

        $response = $this->actingAs($this->usuario('admin'))->get('/dashboard');

        $escaneos = $response->viewData('escaneos');
        $this->assertEquals('Salida anticipada', $escaneos[0]['detalle']);
        $this->assertEquals('yellow', $escaneos[0]['clase']);
        $response->assertSee('Salida anticipada');
    }

    public function test_valores_por_defecto_del_horario(): void
    {
        $this->assertEquals('08:10', Configuracion::obtener('hora_entrada', 'x'));
        $this->assertEquals('18:00', Configuracion::obtener('hora_salida', 'x'));
        $this->assertEquals('valor', Configuracion::obtener('inexistente', 'valor'));
    }
}
