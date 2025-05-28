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
        Schema::create('citas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('empresa_id')->constrained()->onDelete('cascade');
            $table->foreignId('paciente_id')->constrained()->onDelete('cascade');
            $table->foreignId('aseguradora_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('medico_id')->nullable()->constrained('medico')->onDelete('set null');
            $table->foreignId('especialidad_id')->nullable()->constrained('especialidades')->onDelete('set null');
            $table->date('fecha');
            $table->time('hora');
            $table->foreignId('estado_id')->constrained('cita_estado')->onDelete('cascade');
            $table->foreignId('motivo_cita_id')->nullable()->constrained('motivo_cita')->onDelete('set null');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('citas');
    }
};
