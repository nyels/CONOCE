<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migración para agregar campos del sistema legacy a quotes
 * Estos campos son necesarios para mantener la funcionalidad completa
 * del formulario de cotización original
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('quotes', function (Blueprint $table) {
            // Hora solicitada - Cuándo el cliente solicitó la cotización
            $table->time('requested_at')->nullable()->after('created_at');

            // FK a vehicle_types (catálogo administrable)
            $table->foreignId('vehicle_type_id')->nullable()->after('vehicle_data')
                ->constrained('vehicle_types')->nullOnDelete();

            // Tipo de vehículo: AUTO, MOTO, PICK UP, CAMION (valor de texto para histórico)
            $table->string('vehicle_type', 20)->nullable()->after('vehicle_type_id');

            // Uso de la unidad (campo libre del legacy)
            $table->string('vehicle_usage')->nullable()->after('vehicle_type');

            // Descripción general de la tabla de coberturas
            $table->text('coverage_description')->nullable()->after('package_type');

            // Coberturas opcionales (2 filas con nombre dinámico)
            $table->string('custom_coverage_1_name')->nullable();
            $table->string('custom_coverage_2_name')->nullable();
        });

        // Agregar campos detallados a quote_options para coberturas legacy
        Schema::table('quote_options', function (Blueprint $table) {
            // Tipo de daños materiales: V.COMERCIAL, V.CONVENIDO, V.FACTURA
            $table->string('material_damage_type', 20)->nullable()->after('coverages');
            $table->decimal('material_damage_amount', 12, 2)->nullable();
            $table->tinyInteger('material_damage_deductible')->nullable(); // Porcentaje

            // Robo total
            $table->string('theft_type', 20)->nullable();
            $table->decimal('theft_amount', 12, 2)->nullable();
            $table->tinyInteger('theft_deductible')->nullable();

            // Cristales - siempre AMPARADA
            $table->string('glass_coverage', 20)->default('AMPARADA');

            // R.C. Daños a terceros
            $table->decimal('liability_third_party', 12, 2)->nullable();
            $table->tinyInteger('liability_deductible')->nullable();

            // R.C. Fallecimiento
            $table->decimal('liability_death', 12, 2)->nullable();

            // Gastos médicos ocupantes
            $table->decimal('medical_expenses', 12, 2)->nullable();

            // Accidentes al conductor
            $table->decimal('driver_accident', 12, 2)->nullable();

            // Protección legal - siempre AMPARADA
            $table->string('legal_protection', 20)->default('AMPARADA');

            // Asistencia vial - siempre AMPARADA
            $table->string('roadside_assistance', 20)->default('AMPARADA');

            // Daños por la carga: AMPARADA, EXCLUIDA
            $table->string('cargo_damage', 20)->nullable();

            // Adaptaciones, conversiones y/o equipo especial
            $table->decimal('special_equipment', 12, 2)->nullable();

            // Extensión de RC: AMPARADA, EXCLUIDA
            $table->string('extended_liability', 20)->nullable();

            // Coberturas opcionales (valores para las 2 filas dinámicas)
            $table->string('custom_coverage_1_value', 20)->nullable(); // AMPARADA/EXCLUIDA
            $table->string('custom_coverage_2_value', 20)->nullable();

            // Prima neta anual (antes solo teníamos total)
            $table->unsignedInteger('annual_net_premium_cents')->nullable();

            // Prima total anual
            $table->unsignedInteger('annual_total_premium_cents')->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('quotes', function (Blueprint $table) {
            $table->dropForeign(['vehicle_type_id']);
            $table->dropColumn([
                'requested_at',
                'vehicle_type_id',
                'vehicle_type',
                'vehicle_usage',
                'coverage_description',
                'custom_coverage_1_name',
                'custom_coverage_2_name',
            ]);
        });

        Schema::table('quote_options', function (Blueprint $table) {
            $table->dropColumn([
                'material_damage_type',
                'material_damage_amount',
                'material_damage_deductible',
                'theft_type',
                'theft_amount',
                'theft_deductible',
                'glass_coverage',
                'liability_third_party',
                'liability_deductible',
                'liability_death',
                'medical_expenses',
                'driver_accident',
                'legal_protection',
                'roadside_assistance',
                'cargo_damage',
                'special_equipment',
                'extended_liability',
                'custom_coverage_1_value',
                'custom_coverage_2_value',
                'annual_net_premium_cents',
                'annual_total_premium_cents',
            ]);
        });
    }
};
