<?php

namespace Tests\Feature;

use App\Models\Estancia;
use App\Models\Solicitudes;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AccessControlTest extends TestCase
{
    use RefreshDatabase;

    public function test_docente_cannot_use_administrative_or_vinculacion_routes(): void
    {
        $this->actingAs(User::factory()->create());
        foreach (['/dashboard', '/docente/create', '/showUsers', '/Convenios', '/vinculaciondashboard'] as $url) {
            $this->get($url)->assertForbidden();
        }
        $this->post('/aceptar-solicitud/999')->assertForbidden();
        $this->post('/validar-convenio/999')->assertForbidden();
    }

    public function test_area_accounts_can_access_their_own_routes(): void
    {
        $this->actingAs(User::factory()->create(['email' => 'desarrolloacademico@tecvalles.mx']))
            ->get('/docente/create')->assertOk();
        $this->get('/Convenios')->assertForbidden();
        $this->actingAs(User::factory()->create(['email' => 'vinculacion@tecvalles.mx']))
            ->get('/Convenios')->assertOk();
        $this->get('/docente/create')->assertForbidden();
    }

    public function test_docente_cannot_cancel_or_read_another_users_request(): void
    {
        $estancia = new Estancia();
        $estancia->forceFill(['nombre' => 'Prueba', 'fecha_convocatoria' => '2026-01-01',
            'fecha_cierre' => '2026-12-31', 'archivo_convocatoria' => '', 'empresa' => 'Empresa'])->save();
        $solicitud = Solicitudes::create(['id_estancia' => $estancia->id, 'email' => 'otro@tecvalles.mx',
            'docente' => 'Otro', 'requisitos' => '[]', 'status' => 0, 'periodo_duracion' => 'Dos meses']);
        $this->actingAs(User::factory()->create());
        $this->get('/showRequestFiles/'.$solicitud->id)->assertForbidden();
        $this->delete('/cancelar-solicitud/'.$solicitud->id)->assertForbidden();
        $this->get('/descargar-carta/'.$solicitud->id)->assertForbidden();
        $this->get('/descargar-constancia/'.$solicitud->id)->assertForbidden();
        $this->assertDatabaseHas('solicitudes', ['id' => $solicitud->id, 'status' => 0]);
    }

    public function test_reserved_area_emails_cannot_be_claimed_through_registration_or_profile(): void
    {
        $this->post('/register', ['name' => 'Intruso', 'email' => 'desarrolloacademico@tecvalles.mx',
            'password' => 'password', 'password_confirmation' => 'password'])->assertSessionHasErrors('email');
        $this->actingAs(User::factory()->create())->patch('/profile',
            ['name' => 'Intruso', 'email' => 'vinculacion@tecvalles.mx'])->assertSessionHasErrors('email');
    }
}
