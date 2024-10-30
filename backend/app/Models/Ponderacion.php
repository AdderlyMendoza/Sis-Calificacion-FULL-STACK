<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ponderacion extends Model
{
    use HasFactory;

    protected $fillable = [
        'curso',
        'cantidadPreguntas',
        'ponderacion',
        'area_id',
    ];

    /**
     * Relación con el modelo Area.
     */
    
    public function area()
    {
        return $this->belongsTo(Area::class);
    }

    public function proceso()
    {
        return $this->belongsTo(ProcesoAdmision::class, 'id_proceso');
    }
    
}
