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
        Schema::table('users', function (Blueprint $table) {
            // Agregar el campo numero_documento
            $table->string('numero_documento')->nullable();
            $table->string('email');

            $table->boolean('estado')->default(1);
            $table->foreignId('empresa_id')
                ->nullable() // Puede ser nulo
                ->constrained('empresas') // Relación con la tabla empresas
                ->onDelete('set null'); // Si se elimina la empresa, se establece como nulo
            $table->foreignId('tipo_documento_id')
                ->nullable()
                ->constrained('tipo_documentos')
                ->onDelete('set null');

            $table->foreignId('role_id')
                ->nullable()
                ->constrained('roles');

            // Crear un índice único compuesto por empresa_id y numero_documento con un nombre personalizado
            $table->unique(['empresa_id', 'numero_documento'], 'users_empresa_numero_documento_unique');
            $table->unique(['empresa_id', 'email'], 'users_empresa_email_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Eliminar restricciones de clave foránea primero
            $table->dropForeign(['tipo_documento_id']);
            $table->dropForeign(['role_id']);
            $table->dropForeign(['empresa_id']); // Debe eliminarse antes de eliminar el índice

            // Luego eliminar el índice único compuesto
            $table->dropUnique('users_empresa_numero_documento_unique');
            $table->dropUnique('users_empresa_email_unique');

            // Eliminar la columna empresa_id después de eliminar la clave foránea
            $table->dropColumn('empresa_id');
            $table->dropColumn('email');

            // Eliminar las demás columnas
            $table->dropColumn(['estado', 'tipo_documento_id', 'role_id', 'numero_documento']);
        });
    }
};
