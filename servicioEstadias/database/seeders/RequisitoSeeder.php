<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RequisitoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('requisitos')->insert([
            [
                'nombre' => 'Convocatoria',
                'archivo_requisito' => 'public/archivos/Convocatoria.pdf',
                'descargable' => 0,
            ],
            [
                'nombre' => 'Solicitud de Estadía',
                'archivo_requisito' => 'public/archivos/SolicitudEstadia.docx',
                'descargable' => 1,
            ],
            [
                'nombre' => 'Constancia de Antigüedad',
                'archivo_requisito' => '',
                'descargable' => 0,
            ],
            [
                'nombre' => 'Currículum Vitae',
                'archivo_requisito' => '',
                'descargable' => 0,
            ],
            [
                'nombre' => 'Carta Compromiso Personal',
                'archivo_requisito' => 'public/archivos/CCPersonal.docx',
                'descargable' => 1,
            ],
            [
                'nombre' => 'Carta Compromiso Institucional',
                'archivo_requisito' => 'public/archivos/CCInstitucional.docx',
                'descargable' => 1,
            ],
            [
                'nombre' => 'Plan de Trabajo',
                'archivo_requisito' => '',
                'descargable' => 0,
            ],
            [
                'nombre' => 'Eval. Satisfactoria Act. Realiz. 2 años',
                'archivo_requisito' => '',
                'descargable' => 0,
            ],
        ]);
    }
}
