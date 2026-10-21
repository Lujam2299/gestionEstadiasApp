<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Estancia extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre', 'empresa', 'fecha_convocatoria', 'fecha_cierre', 'archivo_convocatoria', 'vigente',
    ];

    /*public function requisitos()
    {
        return $this->hasMany(EstanciaRequisitos::class, 'id_estancia_requisito');
    }*/
    public function requisitos()
    {
        return $this->belongsToMany(Requisitos::class, 'estanciarequisitos', 'id_estancia', 'id_requisitos');
    }

    public function estanciaRequisitos()
    {
        return $this->hasOne(EstanciaRequisitos::class, 'id_estancia');
    }
    /*public function actualizarRequisitos($requisitosSeleccionados)
    {
        // Eliminar todos los requisitos existentes para esta estancia
        $this->requisitos()->delete();

        // Agregar los nuevos requisitos seleccionados
        foreach ($requisitosSeleccionados as $requisitoId) {
            $this->requisitos()->create(['id_requisito' => $requisitoId]);
        }
    }*/
}
