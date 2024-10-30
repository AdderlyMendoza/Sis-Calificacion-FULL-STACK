<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PonderacionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Inserta los datos en la tabla 'ponderacions'
        DB::table('ponderacions')->insert([

            // INGENIERIAS = 1                              // id_proceso = 1 = general 2023
            ['curso' => 'Aritmética',               'cantidadPreguntas' => 4, 'ponderacion' => 5.201, 'area_id' => 1, 'id_proceso' => 1],
            ['curso' => 'Algebra',                  'cantidadPreguntas' => 4, 'ponderacion' => 5.202, 'area_id' => 1, 'id_proceso' => 1],
            ['curso' => 'Geometría',                'cantidadPreguntas' => 4, 'ponderacion' => 5.303, 'area_id' => 1, 'id_proceso' => 1],
            ['curso' => 'Trigonometría',            'cantidadPreguntas' => 4, 'ponderacion' => 5.404, 'area_id' => 1, 'id_proceso' => 1],
            ['curso' => 'Física',                   'cantidadPreguntas' => 4, 'ponderacion' => 5.905, 'area_id' => 1, 'id_proceso' => 1],
            ['curso' => 'Química',                  'cantidadPreguntas' => 4, 'ponderacion' => 5.406, 'area_id' => 1, 'id_proceso' => 1],
            ['curso' => 'Biología y Anatomía',      'cantidadPreguntas' => 2, 'ponderacion' => 3.177, 'area_id' => 1, 'id_proceso' => 1],
            ['curso' => 'Psicología y Filosofía',   'cantidadPreguntas' => 4, 'ponderacion' => 3.802, 'area_id' => 1, 'id_proceso' => 1],
            ['curso' => 'Geografía',                'cantidadPreguntas' => 2, 'ponderacion' => 2.576, 'area_id' => 1, 'id_proceso' => 1],
            ['curso' => 'Historia',                 'cantidadPreguntas' => 2, 'ponderacion' => 3.701, 'area_id' => 1, 'id_proceso' => 1],
            ['curso' => 'Educación Cívica',         'cantidadPreguntas' => 2, 'ponderacion' => 3.101, 'area_id' => 1, 'id_proceso' => 1],
            ['curso' => 'Economía',                 'cantidadPreguntas' => 2, 'ponderacion' => 3.502, 'area_id' => 1, 'id_proceso' => 1],
            ['curso' => 'Comunicación',             'cantidadPreguntas' => 4, 'ponderacion' => 3.352, 'area_id' => 1, 'id_proceso' => 1],
            ['curso' => 'Literatura',               'cantidadPreguntas' => 2, 'ponderacion' => 2.501, 'area_id' => 1, 'id_proceso' => 1],
            ['curso' => 'Razonamiento Matemático',  'cantidadPreguntas' => 6, 'ponderacion' => 7.603, 'area_id' => 1, 'id_proceso' => 1],
            ['curso' => 'Razonamiento Verbal',      'cantidadPreguntas' => 6, 'ponderacion' => 7.103, 'area_id' => 1, 'id_proceso' => 1],
            ['curso' => 'Inglés',                   'cantidadPreguntas' => 4, 'ponderacion' => 4.087, 'area_id' => 1, 'id_proceso' => 1],
            ['curso' => 'Quechua y aimara',         'cantidadPreguntas' => 2, 'ponderacion' => 4.087, 'area_id' => 1, 'id_proceso' => 1],

            
            // BIOMEDICAS = 2
            ['curso' => 'Aritmética',               'cantidadPreguntas' => 3, 'ponderacion' => 3.331, 'area_id' => 2, 'id_proceso' => 1],
            ['curso' => 'Algebra',                  'cantidadPreguntas' => 3, 'ponderacion' => 3.202, 'area_id' => 2, 'id_proceso' => 1],
            ['curso' => 'Geometría',                'cantidadPreguntas' => 3, 'ponderacion' => 3.301, 'area_id' => 2, 'id_proceso' => 1],
            ['curso' => 'Trigonometría',            'cantidadPreguntas' => 3, 'ponderacion' => 3.404, 'area_id' => 2, 'id_proceso' => 1],
            ['curso' => 'Física',                   'cantidadPreguntas' => 3, 'ponderacion' => 5.505, 'area_id' => 2, 'id_proceso' => 1],
            ['curso' => 'Química',                  'cantidadPreguntas' => 5, 'ponderacion' => 6.623, 'area_id' => 2, 'id_proceso' => 1],
            ['curso' => 'Biología y Anatomía',      'cantidadPreguntas' => 6, 'ponderacion' => 7.816, 'area_id' => 2, 'id_proceso' => 1],
            ['curso' => 'Psicología y Filosofía',   'cantidadPreguntas' => 4, 'ponderacion' => 4.006, 'area_id' => 2, 'id_proceso' => 1],
            ['curso' => 'Geografía',                'cantidadPreguntas' => 2, 'ponderacion' => 2.800, 'area_id' => 2, 'id_proceso' => 1],
            ['curso' => 'Historia',                 'cantidadPreguntas' => 2, 'ponderacion' => 3.302, 'area_id' => 2, 'id_proceso' => 1],
            ['curso' => 'Educación Cívica',         'cantidadPreguntas' => 2, 'ponderacion' => 3.571, 'area_id' => 2, 'id_proceso' => 1],
            ['curso' => 'Economía',                 'cantidadPreguntas' => 2, 'ponderacion' => 3.406, 'area_id' => 2, 'id_proceso' => 1],
            ['curso' => 'Comunicación',             'cantidadPreguntas' => 4, 'ponderacion' => 3.302, 'area_id' => 2, 'id_proceso' => 1],
            ['curso' => 'Literatura',               'cantidadPreguntas' => 2, 'ponderacion' => 2.805, 'area_id' => 2, 'id_proceso' => 1],
            ['curso' => 'Razonamiento Matemático',  'cantidadPreguntas' => 6, 'ponderacion' => 7.201, 'area_id' => 2, 'id_proceso' => 1],
            ['curso' => 'Razonamiento Verbal',      'cantidadPreguntas' => 6, 'ponderacion' => 7.201, 'area_id' => 2, 'id_proceso' => 1],
            ['curso' => 'Inglés',                   'cantidadPreguntas' => 4, 'ponderacion' => 4.087, 'area_id' => 2, 'id_proceso' => 1],
            ['curso' => 'Quechua y aimara',         'cantidadPreguntas' => 2, 'ponderacion' => 4.087, 'area_id' => 2, 'id_proceso' => 1],


            // SOCIALES = 3
            ['curso' => 'Aritmética',               'cantidadPreguntas' => 3, 'ponderacion' => 3.331, 'area_id' => 3, 'id_proceso' => 1],
            ['curso' => 'Algebra',                  'cantidadPreguntas' => 3, 'ponderacion' => 3.185, 'area_id' => 3, 'id_proceso' => 1],
            ['curso' => 'Geometría',                'cantidadPreguntas' => 2, 'ponderacion' => 3.120, 'area_id' => 3, 'id_proceso' => 1],
            ['curso' => 'Trigonometría',            'cantidadPreguntas' => 2, 'ponderacion' => 3.120, 'area_id' => 3, 'id_proceso' => 1],
            ['curso' => 'Física',                   'cantidadPreguntas' => 2, 'ponderacion' => 2.302, 'area_id' => 3, 'id_proceso' => 1],
            ['curso' => 'Química',                  'cantidadPreguntas' => 2, 'ponderacion' => 2.404, 'area_id' => 3, 'id_proceso' => 1],
            ['curso' => 'Biología y Anatomía',      'cantidadPreguntas' => 2, 'ponderacion' => 2.504, 'area_id' => 3, 'id_proceso' => 1],
            ['curso' => 'Psicología y Filosofía',   'cantidadPreguntas' => 4, 'ponderacion' => 4.807, 'area_id' => 3, 'id_proceso' => 1],
            ['curso' => 'Geografía',                'cantidadPreguntas' => 4, 'ponderacion' => 4.907, 'area_id' => 3, 'id_proceso' => 1],
            ['curso' => 'Historia',                 'cantidadPreguntas' => 4, 'ponderacion' => 5.805, 'area_id' => 3, 'id_proceso' => 1],
            ['curso' => 'Educación Cívica',         'cantidadPreguntas' => 4, 'ponderacion' => 6.576, 'area_id' => 3, 'id_proceso' => 1],
            ['curso' => 'Economía',                 'cantidadPreguntas' => 4, 'ponderacion' => 4.607, 'area_id' => 3, 'id_proceso' => 1],
            ['curso' => 'Comunicación',             'cantidadPreguntas' => 4, 'ponderacion' => 6.090, 'area_id' => 3, 'id_proceso' => 1],
            ['curso' => 'Literatura',               'cantidadPreguntas' => 4, 'ponderacion' => 4.300, 'area_id' => 3, 'id_proceso' => 1],
            ['curso' => 'Razonamiento Matemático',  'cantidadPreguntas' => 6, 'ponderacion' => 7.203, 'area_id' => 3, 'id_proceso' => 1],
            ['curso' => 'Razonamiento Verbal',      'cantidadPreguntas' => 6, 'ponderacion' => 7.603, 'area_id' => 3, 'id_proceso' => 1],
            ['curso' => 'Inglés',                   'cantidadPreguntas' => 4, 'ponderacion' => 4.087, 'area_id' => 3, 'id_proceso' => 1],
            ['curso' => 'Quechua y aimara',         'cantidadPreguntas' => 2, 'ponderacion' => 4.087, 'area_id' => 3, 'id_proceso' => 1],
            
            
        ]);
    }
}
