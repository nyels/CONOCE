<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migración completa para cotizaciones y opciones
 */
return new class extends Migration
{
    public function up(): void
    {
        // Eliminar tablas anteriores
        Schema::dropIfExists('quote_options');
        Schema::dropIfExists('quotes');

        // Tabla principal de cotizaciones
        Schema::create('quotes', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->string('folio', 20)->unique()->comment('Folio legible: COT-2026-00001');

            // Relaciones principales
            $table->foreignId('customer_id')->constrained()->cascadeOnDelete();
            $table->foreignId('contact_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('agent_id')->constrained('users')->cascadeOnDelete();

            // Tipo y estado
            $table->enum('type', ['NEW', 'RENEWAL']);
            $table->enum('status', [
                'DRAFT',
                'SENT',
                'CONCRETED',
                'ISSUED',
                'REJECTED',
                'ANNULLED',
                'EXPIRED'
            ])->default('DRAFT');

            // Datos del vehículo (JSON estructurado)
            $table->json('vehicle_data')->comment('brand, model, year, type, use, vin, plates, etc.');

            // Datos de renovación (si aplica)
            $table->string('previous_policy_number')->nullable();
            $table->string('previous_insurer')->nullable();
            $table->unsignedInteger('previous_premium_cents')->nullable();
            $table->date('previous_expiry_date')->nullable();

            // Configuración de la cotización
            $table->enum('package_type', ['FULL', 'LIMITED', 'LIABILITY_ONLY', 'CUSTOM']);
            $table->unsignedTinyInteger('options_count')->default(0);

            // Vigencia de la cotización
            $table->date('quote_valid_until')->nullable();

            // Fechas de envío
            $table->timestamp('sent_at')->nullable();
            $table->timestamp('first_reminder_at')->nullable();
            $table->timestamp('second_reminder_at')->nullable();

            // Datos de cierre
            $table->foreignId('concluded_option_id')->nullable();
            $table->string('issued_policy_number')->nullable();
            $table->date('policy_start_date')->nullable();
            $table->date('policy_end_date')->nullable();
            $table->timestamp('concluded_at')->nullable();
            $table->text('rejection_reason')->nullable();
            $table->timestamp('rejected_at')->nullable();

            // Notas internas
            $table->text('internal_notes')->nullable();
            $table->text('customer_notes')->nullable()->comment('Observaciones del cliente');

            $table->timestamps();
            $table->softDeletes();

            // Índices
            $table->index(['status', 'created_at']);
            $table->index(['agent_id', 'status']);
            $table->index(['customer_id', 'status']);
            $table->index('folio');
        });

        // Opciones de cotización (1-5 por cotización)
        Schema::create('quote_options', function (Blueprint $table) {
            $table->id();
            $table->foreignId('quote_id')->constrained()->cascadeOnDelete();
            $table->foreignId('insurer_id')->constrained()->cascadeOnDelete();

            // Número de opción dentro de la cotización
            $table->unsignedTinyInteger('option_number');

            // Tipo de paquete para esta opción
            $table->enum('coverage_package', ['FULL', 'LIMITED', 'LIABILITY_ONLY', 'CUSTOM']);

            // Coberturas detalladas (JSON estructurado)
            $table->json('coverages')->comment('Array de coberturas con montos y deducibles');

            // Forma de pago seleccionada
            $table->enum('payment_frequency', ['ANNUAL', 'SEMIANNUAL', 'QUARTERLY', 'MONTHLY']);

            // Desglose económico (en centavos para precisión)
            $table->unsignedInteger('net_premium_cents')->comment('Prima neta');
            $table->unsignedInteger('policy_fee_cents')->comment('Derecho de póliza');
            $table->unsignedInteger('surcharge_cents')->default(0)->comment('Recargo por fraccionamiento');
            $table->unsignedInteger('iva_cents')->default(0)->comment('IVA (si aplica)');
            $table->unsignedInteger('total_premium_cents')->comment('Prima total');

            // Desglose de pagos
            $table->unsignedInteger('first_payment_cents')->comment('Primer pago');
            $table->unsignedInteger('subsequent_payment_cents')->comment('Pagos subsecuentes');

            // Si esta opción fue la elegida
            $table->boolean('is_selected')->default(false);

            // Notas específicas de esta opción
            $table->text('notes')->nullable();

            $table->timestamps();

            // Unique constraint: solo una opción por número por cotización
            $table->unique(['quote_id', 'option_number']);

            // Índices
            $table->index(['quote_id', 'is_selected']);
        });

        // Agregar foreign key para concluded_option_id después de crear quote_options
        Schema::table('quotes', function (Blueprint $table) {
            $table->foreign('concluded_option_id')
                ->references('id')
                ->on('quote_options')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('quotes', function (Blueprint $table) {
            $table->dropForeign(['concluded_option_id']);
        });

        Schema::dropIfExists('quote_options');
        Schema::dropIfExists('quotes');
    }
};
