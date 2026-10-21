<?php

namespace Tests\Feature;

use App\Models\Estancia;
use App\Models\Informe;
use App\Models\Solicitudes;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Tests\TestCase;

class InformeWorkflowTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->app->usePublicPath(storage_path('framework/testing/public-'.uniqid()));
    }

    protected function tearDown(): void
    {
        File::deleteDirectory(public_path());
        parent::tearDown();
    }

    private function pdf(string $name): UploadedFile
    {
        File::ensureDirectoryExists(public_path('input'));
        $path = public_path('input/'.uniqid().$name);
        file_put_contents($path, "%PDF-1.4\n1 0 obj << /Type /Catalog >> endobj\n%%EOF");

        return new UploadedFile($path, $name, 'application/pdf', null, true);
    }

    public function test_resubmission_updates_the_existing_report_and_files_exist(): void
    {
        $user = User::factory()->create();
        $estancia = new Estancia();
        $estancia->forceFill(['nombre' => 'Prueba', 'empresa' => 'Empresa', 'fecha_convocatoria' => today(),
            'fecha_cierre' => today()->addDay(), 'archivo_convocatoria' => ''])->save();
        $solicitud = Solicitudes::create(['id_estancia' => $estancia->id, 'email' => $user->email,
            'docente' => $user->name, 'requisitos' => '[]', 'status' => 2, 'periodo_duracion' => 'Dos meses']);
        $this->actingAs($user);
        for ($attempt = 0; $attempt < 2; $attempt++) {
            $this->post('/informes/upload', [
                'constancia' => $this->pdf('constancia.pdf'),
                'informe' => $this->pdf('informe.pdf'),
            ])->assertOk();
            $informe = Informe::where('id_solicitud', $solicitud->id)->firstOrFail();
            $this->assertFileExists(public_path($informe->ruta_constancia));
            $this->assertFileExists(public_path($informe->ruta_oficio));
            $this->assertDatabaseCount('informes', 1);
            $this->assertDatabaseHas('solicitudes', ['id' => $solicitud->id, 'status' => 4]);
            $solicitud->status = 5;
            $solicitud->save();
        }
    }
}
