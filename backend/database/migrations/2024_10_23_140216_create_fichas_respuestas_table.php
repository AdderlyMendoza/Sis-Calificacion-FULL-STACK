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
        Schema::create('fichas_respuestas', function (Blueprint $table) {
            $table->id();
            $table->string('campo1'); 
            $table->string('campo2'); 
            $table->string('campo3'); 
            $table->string('campo4'); 
            $table->string('id_archivo');
            $table->string('litho'); 
            $table->string('tipo'); 
            $table->string('respuestas'); 
            $table->string('puntaje'); 
            // falta area_id
            $table->foreignId('id_proceso')->constrained('proceso_admisions')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fichas_respuestas');
    }
};
