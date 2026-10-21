<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CarreraSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        DB::table('carreras')->insert([
            [
                'nombre' => 'Ingeniería en Sistemas Computacionales',
                'abreviatura' => 'ISC',
                'plan_de_estudios' => 'ISIC-2010-224',
            ],
            [
                'nombre' => 'Ingeniería Industrial',
                'abreviatura' => 'IIN',
                'plan_de_estudios' => 'IIND-2010-227',
            ],
            [
                'nombre' => 'Ingeniería en Gestión Empresarial',
                'abreviatura' => 'IGE',
                'plan_de_estudios' => 'IGEE-GIO-2018',
            ],
            [
                'nombre' => 'Ingeniería en Industrias Alimentarias',
                'abreviatura' => 'IIA',
                'plan_de_estudios' => 'IIAL-2010-219',
            ],
            [
                'nombre' => 'Ingeniería Ambiental',
                'abreviatura' => 'IAMB',
                'plan_de_estudios' => 'IAMB-2010-206',
            ],
            [
                'nombre' => 'Ingeniería en Agronomía',
                'abreviatura' => 'IAGR',
                'plan_de_estudios' => 'IAGR-2010-214',
            ],
            [
                'nombre' => 'Ingeniería en Desarrollo de Aplicaciones',
                'abreviatura' => 'IDAP',
                'plan_de_estudios' => 'IDAP-2024-246',
            ],
            [
                'nombre' => 'Ingeniería en Inteligencia Artificial',
                'abreviatura' => 'ISC',
                'plan_de_estudios' => 'IIAR-2024-248',
            ],
        ]);
    }
}
