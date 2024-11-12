<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProgramaEstudiosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        DB::table('programa_estudios')->insert([

            // INGENIERIA
            ['nombreProgramaEstudio' => 'Ingenieria de Sistemas', 'facultad_id' => 1],
            ['nombreProgramaEstudio' => 'Ingenieria Mecanica Electrica', 'facultad_id' => 1],
            ['nombreProgramaEstudio' => 'Ingenieria Civil', 'facultad_id' => 2],
            ['nombreProgramaEstudio' => 'Ingenieria de Minas', 'facultad_id' => 3],
            ['nombreProgramaEstudio' => 'Ingenieria Economica', 'facultad_id' => 4],


            // BIOMEDICAS
            ['nombreProgramaEstudio' => 'Medicina Humana', 'facultad_id' => 5],
            ['nombreProgramaEstudio' => 'Odontologia', 'facultad_id' => 6],
            ['nombreProgramaEstudio' => 'Enfermeria', 'facultad_id' => 7],


            // SOCIALES
            
            ['nombreProgramaEstudio' => 'Turismo', 'facultad_id' => 8],
            ['nombreProgramaEstudio' => 'Sociologia', 'facultad_id' => 8],
            ['nombreProgramaEstudio' => 'Educacion Inicial', 'facultad_id' => 9],
            ['nombreProgramaEstudio' => 'Trabajo Social', 'facultad_id' => 10],

        ]);
    }
}
