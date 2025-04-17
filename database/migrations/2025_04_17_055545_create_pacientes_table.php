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
        Schema::create('pacientes', function (Blueprint $table) {
            $table->id();
            $table->integer('empresa_id');
            $table->string('nombres');
            $table->string('apellido_paterno');
            $table->string('apellido_materno');

            $table->foreignId('tipo_documento_identidad_id')->constrained('tipo_documento_identidad')->onDelete('cascade');

            $table->string('numero_documento_identidad', 20);
            $table->string('telefono', 20)->nullable();
            $table->string('email', 100)->nullable();
            $table->date('fecha_nacimiento')->nullable();
            $table->timestamps();

            // Índices
            // Índices existentes
            $table->index('empresa_id');
            $table->index('tipo_documento_identidad_id');

            // Nuevos índices para búsqueda rápida
            $table->index('nombres');
            $table->index('apellido_paterno');
            $table->index('apellido_materno');
            $table->index('numero_documento_identidad');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pacientes');
    }
};
