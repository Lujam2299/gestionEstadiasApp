<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Requisitos;

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
