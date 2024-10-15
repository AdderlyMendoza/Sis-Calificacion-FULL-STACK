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

        User::factory()->create([
            'name' => 'Adderly',
            'email' => 'example@example.com',
            'password' => '12345678',
            'apellido_paterno' => 'Mendoza',
            'apellido_materno' => 'Nina',
        ]);
    }
}
