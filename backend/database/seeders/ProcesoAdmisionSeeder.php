<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use DB; // Asegúrate de incluir esta línea si usas DB directamente.
use App\Models\ProcesoAdmision;

class ProcesoAdmisionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ProcesoAdmision::create([
            'nombre' => 'Proceso de Admisión 2024',
            'ubicacion' => 'Sede Principal',
        ]);

        ProcesoAdmision::create([
            'nombre' => 'Proceso de Admisión 2023',
            'ubicacion' => 'Sede Secundaria',
        ]);

        // Puedes agregar más datos según sea necesario.
    }
}
