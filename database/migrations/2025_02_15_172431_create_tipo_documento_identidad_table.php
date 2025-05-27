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
        Schema::create('tipo_documento_identidad', function (Blueprint $table) {
            $table->id();
            $table->string('nombre'); // Ej: Documento Nacional de Identidad
            $table->string('codigo_corto')->unique(); // Ej: DNI, CE, PAS
            $table->boolean('estado')->default(true); // activo/inactivo
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tipo_documento_identidad');
    }
};
