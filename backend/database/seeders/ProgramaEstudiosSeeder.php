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

            // INGENIERÍAS
            ['nombreProgramaEstudio' => 'Ingenieria Agronomica', 'facultad_id' => 1],
            ['nombreProgramaEstudio' => 'Ingenieria Agroindustrial', 'facultad_id' => 1],
            ['nombreProgramaEstudio' => 'Ingenieria Topografia y Agrimensura', 'facultad_id' => 1],

            ['nombreProgramaEstudio' => 'Ingenieria Economica', 'facultad_id' => 2],

            ['nombreProgramaEstudio' => 'Ingenieria de Minas', 'facultad_id' => 3],

            ['nombreProgramaEstudio' => 'Ingenieria Quimica', 'facultad_id' => 4],

            ['nombreProgramaEstudio' => 'Ingenieria Geologica', 'facultad_id' => 5],
            ['nombreProgramaEstudio' => 'Ingenieria Metalurgica', 'facultad_id' => 5],

            ['nombreProgramaEstudio' => 'Ingenieria Estadistica e Informatica', 'facultad_id' => 6],

            ['nombreProgramaEstudio' => 'Ingenieria Civil', 'facultad_id' => 7],
            ['nombreProgramaEstudio' => 'Arquitectura y Urbanismo', 'facultad_id' => 7],

            ['nombreProgramaEstudio' => 'Ingenieria Agricola', 'facultad_id' => 8],

            ['nombreProgramaEstudio' => 'Ingenieria Electronica', 'facultad_id' => 9],
            ['nombreProgramaEstudio' => 'Ingenieria Mecanica Electrica', 'facultad_id' => 9],
            ['nombreProgramaEstudio' => 'Ingenieria de Sistemas', 'facultad_id' => 9],  //total 15

            // BIOMÉDICAS
            ['nombreProgramaEstudio' => 'Medicina Veterinaria y Zootecnia', 'facultad_id' => 10],

            ['nombreProgramaEstudio' => 'Medicina Humana', 'facultad_id' => 11],

            ['nombreProgramaEstudio' => 'Nutricion Humana', 'facultad_id' => 12],
            ['nombreProgramaEstudio' => 'Odontologia', 'facultad_id' => 12],

            ['nombreProgramaEstudio' => 'Enfermeria', 'facultad_id' => 13], // total 5

            // SOCIALES
            ['nombreProgramaEstudio' => 'Trabajo Social', 'facultad_id' => 14],

            ['nombreProgramaEstudio' => 'Antropologia', 'facultad_id' => 15],
            ['nombreProgramaEstudio' => 'Sociologia', 'facultad_id' => 15],
            ['nombreProgramaEstudio' => 'Turismo', 'facultad_id' => 15],
            ['nombreProgramaEstudio' => 'Ciencias de la Comunicacion Social', 'facultad_id' => 15],
            ['nombreProgramaEstudio' => 'Arte: Artes Plasticas', 'facultad_id' => 15],
            ['nombreProgramaEstudio' => 'Arte: Danza', 'facultad_id' => 15],
            ['nombreProgramaEstudio' => 'Arte: Musica', 'facultad_id' => 15],
            ['nombreProgramaEstudio' => 'Arte: Teatro', 'facultad_id' => 15],

            ['nombreProgramaEstudio' => 'Educacion Fisica', 'facultad_id' => 16],
            ['nombreProgramaEstudio' => 'Educacion Inicial', 'facultad_id' => 16],
            ['nombreProgramaEstudio' => 'Educacion Primaria', 'facultad_id' => 16],
            ['nombreProgramaEstudio' => 'E.S. Ciencia, Tecnologia y Ambiente', 'facultad_id' => 16],
            ['nombreProgramaEstudio' => 'E.S. Ciencias Sociales', 'facultad_id' => 16],
            ['nombreProgramaEstudio' => 'E.S. Lengua, Literatura, Psicologia y Filosofia', 'facultad_id' => 16],
            ['nombreProgramaEstudio' => 'E.S. Matematicas, Fisica, Computacion e Informatica', 'facultad_id' => 16],

            ['nombreProgramaEstudio' => 'Derecho', 'facultad_id' => 17],

            ['nombreProgramaEstudio' => 'Ciencias Contables', 'facultad_id' => 18],

            ['nombreProgramaEstudio' => 'Administración', 'facultad_id' => 19], // total 19

        ]);
    }
}
