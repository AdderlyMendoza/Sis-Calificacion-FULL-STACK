<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProcesoAdmision extends Model
{
    use HasFactory;

    protected $fillable = [
        'nombre',
        'ubicacion'
    ];

    public function postulantes()
    {
        return $this->hasMany(Postulante::class);
    }

    public function fichaIdentificacion()
    {
        return $this->hasMany(FichasIdentificacion::class);
    }

    public function fichaRespuestas()
    {
        return $this->hasMany(FichasRespuestas::class);
    }

    public function respuestasCorrectas()
    {
        return $this->hasMany(RespuestasCorrectas::class);
    }

    public function ponderacion()
    {
        return $this->hasMany(Ponderacion::class);
    }

    
}
