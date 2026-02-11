<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

/**
 * Refactoriza el campo 'name' en customers para separarlo en:
 * - first_name (Nombres)
 * - paternal_surname (Apellido Paterno)
 * - maternal_surname (Apellido Materno)
 *
 * Para persona moral: business_name (Razón Social)
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            // Agregar nuevos campos después de 'type'
            $table->string('first_name', 100)->nullable()->after('type');
            $table->string('paternal_surname', 100)->nullable()->after('first_name');
            $table->string('maternal_surname', 100)->nullable()->after('paternal_surname');
            $table->string('business_name', 255)->nullable()->after('maternal_surname')
                ->comment('Razón Social para persona moral');
        });

        // Migrar datos existentes: intentar dividir el nombre
        DB::statement("
            UPDATE customers
            SET
                first_name = CASE
                    WHEN type = 'MORAL' THEN NULL
                    ELSE SUBSTRING_INDEX(name, ' ', 1)
                END,
                paternal_surname = CASE
                    WHEN type = 'MORAL' THEN NULL
                    WHEN LENGTH(name) - LENGTH(REPLACE(name, ' ', '')) >= 1
                    THEN SUBSTRING_INDEX(SUBSTRING_INDEX(name, ' ', 2), ' ', -1)
                    ELSE NULL
                END,
                maternal_surname = CASE
                    WHEN type = 'MORAL' THEN NULL
                    WHEN LENGTH(name) - LENGTH(REPLACE(name, ' ', '')) >= 2
                    THEN SUBSTRING_INDEX(name, ' ', -1)
                    ELSE NULL
                END,
                business_name = CASE
                    WHEN type = 'MORAL' THEN name
                    ELSE NULL
                END
        ");

        // Eliminar columna 'name' y su índice
        Schema::table('customers', function (Blueprint $table) {
            $table->dropIndex(['name']);
            $table->dropColumn('name');
        });

        // Agregar índices para búsqueda
        Schema::table('customers', function (Blueprint $table) {
            $table->index('first_name');
            $table->index('paternal_surname');
            $table->index('business_name');
        });
    }

    public function down(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            // Restaurar columna 'name'
            $table->string('name')->after('type')->comment('Nombre completo o Razón Social');
        });

        // Restaurar datos
        DB::statement("
            UPDATE customers
            SET name = CASE
                WHEN type = 'MORAL' THEN COALESCE(business_name, '')
                ELSE CONCAT_WS(' ',
                    COALESCE(first_name, ''),
                    COALESCE(paternal_surname, ''),
                    COALESCE(maternal_surname, '')
                )
            END
        ");

        Schema::table('customers', function (Blueprint $table) {
            $table->dropIndex(['first_name']);
            $table->dropIndex(['paternal_surname']);
            $table->dropIndex(['business_name']);
            $table->dropColumn(['first_name', 'paternal_surname', 'maternal_surname', 'business_name']);
            $table->index('name');
        });
    }
};
