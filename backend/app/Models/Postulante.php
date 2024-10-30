<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Postulante extends Model
{
    use HasFactory;

    protected $fillable = [
        'dni',
        'nombre',
        'paterno',
        'materno',
        'ubigeo',
        'colegio',
        'celular',
        'email',
        'carrera',
        'codigo',
    ];

    public function proceso()
    {
        return $this->belongsTo(ProcesoAdmision::class, 'id_proceso');
    }
}
