<?php

namespace Tests\Feature;

use App\Models\Estancia;
use App\Models\Estanciarequisitos;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SolicitudValidationTest extends TestCase
{
    use RefreshDatabase;

    private function convocatoria(): Estancia
    {
        $estancia = new Estancia();
        $estancia->forceFill(['nombre' => 'Prueba', 'empresa' => 'Empresa', 'fecha_convocatoria' => today()->subDay(),
            'fecha_cierre' => today()->addDay(), 'archivo_convocatoria' => ''])->save();
        $requirements = new Estanciarequisitos();
        $requirements->forceFill(['id_estancia' => $estancia->id, 'id_requisitos' => '[]'])->save();

        return $estancia;
    }

    private function payload(): array
    {
        return ['empresa' => 'Empresa', 'periodo_duracion' => 'Dos meses', 'proyecto' => 'Proyecto',
            'plan_estudios' => 'Sistemas', 'giro_empresa' => 'Servicios', 'area_complementacion' => 'Software',
            'titular_empresa' => 'Titular', 'puesto_titular' => 'Director', 'objetivo' => 'Mejorar procesos',
            'inicio_estancia' => '2026-11-01', 'fin_estancia' => '2026-12-01', 'email' => 'suplantado@example.com'];
    }

    public function test_request_uses_authenticated_identity_and_prevents_duplicates(): void
    {
        $estancia = $this->convocatoria();
        $user = User::factory()->create();
        $this->actingAs($user)->post('/generar-solicitud/'.$estancia->id, $this->payload())
            ->assertRedirect(route('userSolicitudes'));
        $this->assertDatabaseHas('solicitudes', ['email' => $user->email, 'docente' => $user->name]);
        $this->post('/generar-solicitud/'.$estancia->id, $this->payload())->assertStatus(422);
        $this->assertDatabaseCount('solicitudes', 1);
    }

    public function test_reversed_dates_and_closed_convocatorias_are_rejected(): void
    {
        $estancia = $this->convocatoria();
        $this->actingAs(User::factory()->create())->post('/generar-solicitud/'.$estancia->id,
            array_replace($this->payload(), ['fin_estancia' => '2026-10-01']))->assertSessionHasErrors('fin_estancia');
        $estancia->fecha_cierre = today()->subDay();
        $estancia->save();
        $this->post('/generar-solicitud/'.$estancia->id, $this->payload())->assertStatus(422);
        $this->assertDatabaseCount('solicitudes', 0);
    }
}
