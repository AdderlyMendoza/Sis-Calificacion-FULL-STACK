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
        Schema::create('ponderacions', function (Blueprint $table) {
            $table->id();
            $table->string('curso');
            $table->integer('cantidadPreguntas');
            $table->decimal('ponderacion', 7, 3);
            $table->foreignId('area_id')->constrained('areas')->onDelete('cascade');
            $table->foreignId('id_proceso')->constrained('procesos_admision')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ponderacions');
    }
};
