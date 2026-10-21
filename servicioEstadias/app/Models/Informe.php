<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Informe extends Model
{
    use HasFactory;

    protected $fillable = ['nombre', 'ruta_constancia', 'ruta_oficio', 'id_solicitud'];

    public function solicitud()
    {
        return $this->belongsTo(Solicitudes::class, 'id_solicitud');
    }
}
