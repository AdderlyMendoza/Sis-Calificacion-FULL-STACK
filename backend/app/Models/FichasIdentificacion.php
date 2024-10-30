<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FichasIdentificacion extends Model
{
    use HasFactory;

    protected $fillable = [
        'campo1',  
        'campo2',
        'campo3',
        'campo4',
        'dni',
        'id_archivo',
        'litho',
        'tipo',
        'aula'
    ];

    public function proceso()
    {
        return $this->belongsTo(ProcesoAdmision::class, 'id_proceso');
    }
}