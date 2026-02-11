<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

/**
 * Refactoriza el campo 'name' en contacts para separarlo en:
 * - first_name (Nombres)
 * - paternal_surname (Apellido Paterno)
 * - maternal_surname (Apellido Materno)
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('contacts', function (Blueprint $table) {
            // Agregar nuevos campos después de 'type'
            $table->string('first_name', 100)->nullable()->after('contact_type_id');
            $table->string('paternal_surname', 100)->nullable()->after('first_name');
            $table->string('maternal_surname', 100)->nullable()->after('paternal_surname');
        });

        // Migrar datos existentes: intentar dividir el nombre
        DB::statement("
            UPDATE contacts
            SET
                first_name = SUBSTRING_INDEX(name, ' ', 1),
                paternal_surname = CASE
                    WHEN LENGTH(name) - LENGTH(REPLACE(name, ' ', '')) >= 1
                    THEN SUBSTRING_INDEX(SUBSTRING_INDEX(name, ' ', 2), ' ', -1)
                    ELSE NULL
                END,
                maternal_surname = CASE
                    WHEN LENGTH(name) - LENGTH(REPLACE(name, ' ', '')) >= 2
                    THEN SUBSTRING_INDEX(name, ' ', -1)
                    ELSE NULL
                END
        ");

        // Eliminar columna 'name'
        Schema::table('contacts', function (Blueprint $table) {
            $table->dropColumn('name');
        });

        // Agregar índices para búsqueda
        Schema::table('contacts', function (Blueprint $table) {
            $table->index('first_name');
            $table->index('paternal_surname');
        });
    }

    public function down(): void
    {
        Schema::table('contacts', function (Blueprint $table) {
            // Restaurar columna 'name'
            $table->string('name')->after('contact_type_id');
        });

        // Restaurar datos
        DB::statement("
            UPDATE contacts
            SET name = CONCAT_WS(' ',
                COALESCE(first_name, ''),
                COALESCE(paternal_surname, ''),
                COALESCE(maternal_surname, '')
            )
        ");

        Schema::table('contacts', function (Blueprint $table) {
            $table->dropIndex(['first_name']);
            $table->dropIndex(['paternal_surname']);
            $table->dropColumn(['first_name', 'paternal_surname', 'maternal_surname']);
        });
    }
};
