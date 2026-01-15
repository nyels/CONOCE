<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migración para el sistema de contactos (intermediarios)
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('contacts', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();

            // Tipo de intermediario
            $table->enum('type', ['AGENT', 'SUB_AGENT', 'EMPLOYEE', 'DIRECT']);

            // Datos personales
            $table->string('name');
            $table->string('email')->nullable();
            $table->string('phone', 20)->nullable();
            $table->string('mobile', 20)->nullable();

            // Datos de agente
            $table->string('cnsf_license', 50)->nullable()->comment('Cédula CNSF para agentes');
            $table->date('license_expiry')->nullable();

            // Comisión (porcentaje, ej: 0.15 = 15%)
            $table->decimal('commission_rate', 5, 4)->default(0);

            // Relación con agente padre (para subagentes)
            $table->foreignId('parent_agent_id')
                ->nullable()
                ->constrained('contacts')
                ->nullOnDelete();

            // Usuario que lo registró
            $table->foreignId('created_by')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->boolean('is_active')->default(true);
            $table->text('notes')->nullable();

            $table->timestamps();
            $table->softDeletes();

            // Índices
            $table->index(['type', 'is_active']);
            $table->index('cnsf_license');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('contacts');
    }
};
