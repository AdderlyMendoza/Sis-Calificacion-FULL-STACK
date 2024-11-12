<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // USUARIO
        User::factory()->create([
            'name' => 'Adderly',
            'email' => 'example@example.com',
            'password' => '12345678',
            'apellido_paterno' => 'Mendoza',
            'apellido_materno' => 'Nina',
        ]);

        // Llamar al seeder de Procesos
        $this->call(ProcesoAdmisionSeeder::class);

        // Llamar al seeder de Areas
        $this->call(AreasSeeder::class);

        // Llamar al seeder de Facultades
        $this->call(FacultadesSeeder::class);
        
        // Llamar al seeder de Programa de Estudios
        $this->call(ProgramaEstudiosSeeder::class);

        // Llamar al seeder de Vacantes
        $this->call(VacantesSeeder::class);

        // Llamar al seeder de Ponderacion
        $this->call(PonderacionSeeder::class);
    }
}
