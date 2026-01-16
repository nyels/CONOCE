<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migración mejorada para aseguradoras y configuración financiera
 */
return new class extends Migration
{
    public function up(): void
    {
        // Eliminar tablas anteriores
        Schema::dropIfExists('insurer_financial_settings');
        Schema::dropIfExists('insurers');

        // Tabla de aseguradoras
        Schema::create('insurers', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();

            $table->string('name')->unique();
            $table->string('short_name', 20)->nullable()->comment('Nombre corto para reportes');
            $table->string('code', 10)->unique()->comment('Código interno');

            // Información de contacto
            $table->string('email')->nullable();
            $table->string('phone', 20)->nullable();
            $table->string('website')->nullable();

            // Branding
            $table->string('logo_path')->nullable();
            $table->string('primary_color', 7)->nullable()->comment('Color hex para UI');

            // Notas operativas
            $table->text('notes')->nullable();

            $table->boolean('is_active')->default(true);
            $table->unsignedSmallInteger('sort_order')->default(0);

            $table->timestamps();
            $table->softDeletes();

            $table->index(['is_active', 'sort_order']);
        });

        // Configuración financiera (histórica)
        Schema::create('insurer_financial_settings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('insurer_id')->constrained()->cascadeOnDelete();

            // Derecho de póliza (monto fijo)
            $table->unsignedInteger('policy_fee_cents')->default(0)->comment('En centavos');

            // Recargos por fraccionamiento (porcentaje en decimales)
            $table->decimal('surcharge_semiannual', 5, 4)->default(0)->comment('Ej: 0.02 = 2%');
            $table->decimal('surcharge_quarterly', 5, 4)->default(0);
            $table->decimal('surcharge_monthly', 5, 4)->default(0);

            // Vigencia de esta configuración
            $table->date('valid_from');
            $table->date('valid_until')->nullable()->comment('NULL = vigente actualmente');

            // Quién hizo el cambio
            $table->foreignId('created_by')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->text('change_reason')->nullable();

            $table->timestamps();

            // Índices para búsqueda de configuración vigente
            $table->index(['insurer_id', 'valid_from', 'valid_until'], 'idx_fin_settings_dates');
        });

        // Catálogo de estados de México
        Schema::create('states', function (Blueprint $table) {
            $table->id();
            $table->string('code', 5)->unique();
            $table->string('name');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('states');
        Schema::dropIfExists('insurer_financial_settings');
        Schema::dropIfExists('insurers');
    }
};
