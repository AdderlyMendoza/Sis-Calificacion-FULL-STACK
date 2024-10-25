<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Calificacion extends Model
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
    ];
}
