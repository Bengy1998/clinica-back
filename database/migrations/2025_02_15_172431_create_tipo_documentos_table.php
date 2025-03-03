<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tipo_documentos', function (Blueprint $table) {
            $table->id(); // ID único del tipo de documento
            $table->string('nombre')->unique(); // Nombre del tipo de documento (único)
            $table->integer('cantidad_caracteres'); // Cantidad de caracteres del documento
            $table->timestamps(); // Campos created_at y updated_at
        });

        // Insertar los tipos de documentos de Ecuador y la opción "Otros"
        $this->insertDefaultData();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tipo_documentos');
    }
    /**
     * Insertar datos por defecto.
     */
    private function insertDefaultData(): void
    {
        // Tipos de documentos de Ecuador y sus características
        $documentos = [
            ['nombre' => 'Cédula', 'cantidad_caracteres' => 10],
            ['nombre' => 'Pasaporte', 'cantidad_caracteres' => 9],
            ['nombre' => 'RUC', 'cantidad_caracteres' => 13],
            ['nombre' => 'Cédula de Extranjería', 'cantidad_caracteres' => 10],
            ['nombre' => 'Otros', 'cantidad_caracteres' => 20], // Opción "Otros"
        ];

        // Insertar los datos en la tabla
        DB::table('tipo_documentos')->insert($documentos);
    }
};
