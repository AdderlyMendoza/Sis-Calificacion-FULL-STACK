<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('postulantes', function (Blueprint $table) {
            $table->id(); // Columna autoincremental para la clave primaria
            $table->string('dni', 8)->unique(); // DNI sin auto_increment, pero con restricción única
            $table->string('nombre'); // Nombre del postulante
            $table->string('paterno'); // Apellido paterno
            $table->string('materno'); // Apellido materno
            $table->string('ubigeo', 6); // Ubigeo sin auto_increment
            $table->string('colegio'); // Colegio del postulante
            $table->string('celular', 9); // Celular sin auto_increment
            $table->string('email')->unique(); // Email único
            $table->string('carrera'); // Carrera a la que postula
            $table->string('codigo')->nullable(); // Permitir nulos en la columna codigo
            $table->timestamps(); // Timestamps para created_at y updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('postulantes'); // Borrar la tabla en caso de rollback
    }
};

