<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Agrega campo de descripción de carga para vehículos tipo camión
 * Valores: none, non_hazardous, hazardous, very_hazardous
 */
return new class extends Migration
{
    public function up(): void
    {
        // El campo ya está incluido en vehicle_data JSON, pero
        // agregamos un campo dedicado para reportes y filtros

        // Verificar si la columna ya existe
        if (!Schema::hasColumn('quotes', 'cargo_description')) {
            Schema::table('quotes', function (Blueprint $table) {
                $table->enum('cargo_description', [
                    'none',           // No aplica
                    'non_hazardous',  // A - No peligrosa
                    'hazardous',      // B - Peligrosa
                    'very_hazardous', // C - Muy peligrosa
                ])->nullable()->after('vehicle_data')
                    ->comment('Tipo de carga para camiones: none, non_hazardous, hazardous, very_hazardous');
            });
        }
    }

    public function down(): void
    {
        Schema::table('quotes', function (Blueprint $table) {
            $table->dropColumn('cargo_description');
        });
    }
};
