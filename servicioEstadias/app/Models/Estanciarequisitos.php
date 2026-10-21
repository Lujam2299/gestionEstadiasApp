<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Estanciarequisitos extends Model
{
    use HasFactory;

    protected $fillable = ['id_estancia', 'id_requisito'];

    // Relación con Estancia
    public function estancia()
    {
        return $this->belongsTo(Estancia::class);
    }

    // Relación con Requisito
    /*public function requisito()
    {
        return $this->belongsTo(Requisitos::class);
    }*/
}
