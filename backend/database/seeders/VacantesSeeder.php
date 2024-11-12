<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class VacantesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        DB::table('vacantes')->insert([

            ['programaEstudios_id' => 1, 'numeroVacantes' => 10],
            ['programaEstudios_id' => 2, 'numeroVacantes' => 12],
            ['programaEstudios_id' => 3, 'numeroVacantes' => 10],
            ['programaEstudios_id' => 4, 'numeroVacantes' => 8],
            ['programaEstudios_id' => 5, 'numeroVacantes' => 7],
            ['programaEstudios_id' => 6, 'numeroVacantes' => 10],
            ['programaEstudios_id' => 7, 'numeroVacantes' => 10],
            ['programaEstudios_id' => 8, 'numeroVacantes' => 11],
            ['programaEstudios_id' => 9, 'numeroVacantes' => 12],
            ['programaEstudios_id' => 10, 'numeroVacantes' => 12],
            ['programaEstudios_id' => 11, 'numeroVacantes' => 9],
            ['programaEstudios_id' => 12, 'numeroVacantes' => 9],

        ]);
    }
}
