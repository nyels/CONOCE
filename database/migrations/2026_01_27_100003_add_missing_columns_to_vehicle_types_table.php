<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Agrega columnas faltantes a vehicle_types para soporte completo del legacy
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('vehicle_types', function (Blueprint $table) {
            // Etiqueta para mostrar en UI
            $table->string('label', 100)->after('name')->nullable();

            // Descripción del tipo de vehículo
            $table->text('description')->after('label')->nullable();

            // Si requiere campo de descripción de carga (para CAMION, PICK UP)
            $table->boolean('requires_cargo_description')->after('description')->default(false);

            // Factor de riesgo (1.0 = normal, >1.0 = mayor riesgo)
            $table->decimal('risk_factor', 4, 2)->after('requires_cargo_description')->default(1.00);

            // Icono para UI
            $table->string('icon', 50)->after('risk_factor')->nullable();

            // Soft deletes para trazabilidad
            $table->softDeletes();
        });

        // Actualizar registros existentes con label = name si está vacío
        DB::table('vehicle_types')
            ->whereNull('label')
            ->update(['label' => DB::raw('name')]);
    }

    public function down(): void
    {
        Schema::table('vehicle_types', function (Blueprint $table) {
            $table->dropColumn([
                'label',
                'description',
                'requires_cargo_description',
                'risk_factor',
                'icon',
            ]);
            $table->dropSoftDeletes();
        });
    }
};
