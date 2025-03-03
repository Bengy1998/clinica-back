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
        Schema::create('empresas', function (Blueprint $table) {
            $table->id(); // ID único de la empresa
            $table->string('nombre'); // Nombre de la empresa
            $table->string('ruc')->unique(); // RUC de la empresa (único)
            $table->string('dominio')->unique(); // dominio de la empresa (único)
            $table->string('correo')->nullable(); // Correo de la empresa (puede ser nulo)
            $table->string('telefono')->nullable(); // Teléfono de la empresa (puede ser nulo)
            $table->boolean('estado')->default(true); // Teléfono de la empresa (puede ser nulo)
            $table->timestamps(); // Campos created_at y updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('empresas');
    }
};