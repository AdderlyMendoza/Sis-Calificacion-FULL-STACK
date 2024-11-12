<?php

namespace Database\Seeders;

use App\Models\Facultades;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class FacultadesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('facultades')->insert([

            // INGENIERIA
            ['nombreFacultad' => 'Facultad de Ingenieria Mecanica Electrica, Electronica y Sistemas',   'area_id' => 1],
            ['nombreFacultad' => 'Facultad de Ingenieria Civil y Arquitectura',                         'area_id' => 1],
            ['nombreFacultad' => 'Facultad de Ingenieria de Minas',                                     'area_id' => 1],
            ['nombreFacultad' => 'Facultad de Ingenieria Economica',                                    'area_id' => 1],


            // BIOMEDICAS
            ['nombreFacultad' => 'Facultad de Medicina Humana',                                         'area_id' => 2],
            ['nombreFacultad' => 'Facultad de Ciencias de la Salud',                                    'area_id' => 2],
            ['nombreFacultad' => 'Facultad de Enfermeria',                                              'area_id' => 2],


            // SOCIALES
            ['nombreFacultad' => 'Facultad de Ciencias Sociales',                                       'area_id' => 3],
            ['nombreFacultad' => 'Facultad de Ciencias de la Educacion',                                'area_id' => 3],
            ['nombreFacultad' => 'Facultad de Trabajo Social',                                          'area_id' => 3],

        ]);
    }
}
