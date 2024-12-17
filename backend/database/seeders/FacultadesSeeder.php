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

            // INGENIERÍAS
            ['nombreFacultad' => 'Facultad de Ciencias Agrarias', 'area_id' => 1],
            ['nombreFacultad' => 'Facultad de Ingenieria Economica', 'area_id' => 1],
            ['nombreFacultad' => 'Facultad de Ingenieria de Minas', 'area_id' => 1],
            ['nombreFacultad' => 'Facultad de Ingenieria Quimica', 'area_id' => 1],
            ['nombreFacultad' => 'Facultad de Ingenieria Geologica y Metalurgica', 'area_id' => 1],
            ['nombreFacultad' => 'Facultad de Ingenieria Estadistica e Informatica', 'area_id' => 1],
            ['nombreFacultad' => 'Facultad de Ingenieria Civil y Arquitectura', 'area_id' => 1],
            ['nombreFacultad' => 'Facultad de Ingenieria Agricola', 'area_id' => 1],
            ['nombreFacultad' => 'Facultad de Ingenieria Mecanica Electrica, Electronica y Sistemas', 'area_id' => 1],

            // BIOMÉDICAS
            ['nombreFacultad' => 'Facultad de Medicina Veterinaria y Zootecnia', 'area_id' => 1],
            ['nombreFacultad' => 'Facultad de Medicina Humana', 'area_id' => 2],
            ['nombreFacultad' => 'Facultad de Ciencias de la Salud', 'area_id' => 2],
            ['nombreFacultad' => 'Facultad de Enfermeria', 'area_id' => 2],

            // SOCIALES
            ['nombreFacultad' => 'Facultad de Trabajo Social', 'area_id' => 3],
            ['nombreFacultad' => 'Facultad de Ciencias Sociales', 'area_id' => 3],
            ['nombreFacultad' => 'Facultad de Ciencias de la Educacion', 'area_id' => 3],
            ['nombreFacultad' => 'Facultad de Ciencias Juridicas y Politicas', 'area_id' => 3],
            ['nombreFacultad' => 'Facultad de Ciencias Contables', 'area_id' => 3],
            ['nombreFacultad' => 'Facultad de Administración', 'area_id' => 3],

        ]);
    }
}
