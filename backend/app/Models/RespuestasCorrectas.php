<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RespuestasCorrectas extends Model
{
    use HasFactory;
    protected $fillable = [
        'campo1',  
        'campo2',
        'campo3',
        'campo4',
        'id_archivo',
        'litho',
        'tipo',
        'respuestas',
    ];
}
