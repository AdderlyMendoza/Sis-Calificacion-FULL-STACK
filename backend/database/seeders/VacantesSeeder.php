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
            ['programaEstudios_id' => 12, 'numeroVacantes' => 10],
            ['programaEstudios_id' => 13, 'numeroVacantes' => 15],
            ['programaEstudios_id' => 14, 'numeroVacantes' => 20],
            ['programaEstudios_id' => 15, 'numeroVacantes' => 4],
            ['programaEstudios_id' => 16, 'numeroVacantes' => 3],
            ['programaEstudios_id' => 17, 'numeroVacantes' => 9],
            ['programaEstudios_id' => 18, 'numeroVacantes' => 6],
            ['programaEstudios_id' => 19, 'numeroVacantes' => 9],
            ['programaEstudios_id' => 20, 'numeroVacantes' => 7],
            ['programaEstudios_id' => 21, 'numeroVacantes' => 9],
            ['programaEstudios_id' => 22, 'numeroVacantes' => 8],
            ['programaEstudios_id' => 23, 'numeroVacantes' => 9],
            ['programaEstudios_id' => 24, 'numeroVacantes' => 9],
            ['programaEstudios_id' => 25, 'numeroVacantes' => 9],
            ['programaEstudios_id' => 26, 'numeroVacantes' => 10],
            ['programaEstudios_id' => 27, 'numeroVacantes' => 19],
            ['programaEstudios_id' => 28, 'numeroVacantes' => 16],
            ['programaEstudios_id' => 29, 'numeroVacantes' => 9],
            ['programaEstudios_id' => 30, 'numeroVacantes' => 15],
            ['programaEstudios_id' => 31, 'numeroVacantes' => 9],
            ['programaEstudios_id' => 32, 'numeroVacantes' => 9],
            ['programaEstudios_id' => 33, 'numeroVacantes' => 15],
            ['programaEstudios_id' => 34, 'numeroVacantes' => 13],
            ['programaEstudios_id' => 35, 'numeroVacantes' => 9],
            ['programaEstudios_id' => 36, 'numeroVacantes' => 9],
            ['programaEstudios_id' => 37, 'numeroVacantes' => 12],
            ['programaEstudios_id' => 38, 'numeroVacantes' => 9],
            ['programaEstudios_id' => 39, 'numeroVacantes' => 11],

        ]);
    }
}
