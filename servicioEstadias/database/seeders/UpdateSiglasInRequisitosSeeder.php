<?php

namespace Database\Seeders;

use App\Models\Requisitos;
use Illuminate\Database\Seeder;

class UpdateSiglasInRequisitosSeeder extends Seeder
{
    public function run()
    {
        $siglas = ['CONV', 'SOL', 'ANT', 'CV', 'CCP', 'CCI', 'PT', 'EVAL'];
        foreach (Requisitos::all() as $index => $requisito) {
            $requisito->update(['siglas' => $siglas[$index] ?? null]);
        }
    }
}
