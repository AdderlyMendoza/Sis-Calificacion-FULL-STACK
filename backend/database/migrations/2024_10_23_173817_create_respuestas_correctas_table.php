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
        Schema::create('respuestas_correctas', function (Blueprint $table) {
            $table->id();
            $table->string('campo1'); 
            $table->string('campo2'); 
            $table->string('campo3'); 
            $table->string('campo4'); 
            $table->string('id_archivo');
            $table->string('litho'); 
            $table->string('tipo'); 
            $table->string('respuestas'); 
            $table->foreignId('id_proceso')->constrained('procesos_admision')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('respuestas_correctas');
    }
};
