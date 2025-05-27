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
        Schema::create('asistencias', function (Blueprint $table) {
            $table->id();
            $table->integer('empresa_id');
            $table->integer('user_id'); // quien registra la asistencia
            $table->datetime('fecha_hora');
            $table->boolean('asistio')->default(true);
            $table->text('observaciones')->nullable();
            $table->timestamps();

            // Relaciones
            $table->foreignId('paciente_id')->constrained('pacientes')->onDelete('cascade');

            // Índices útiles
            $table->index('empresa_id');
            $table->index('paciente_id');
            $table->index('user_id');
            $table->index(['fecha_hora', 'paciente_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('asistencias');
    }
};
