<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AreasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Inserta los datos en la tabla 'areas'
        DB::table('areas')->insert([
            ['nombreArea' => 'Ingenieria'],
            ['nombreArea' => 'Biomedicas'],
            ['nombreArea' => 'Sociales'],
        ]);
    }
}
