<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migración mejorada para clientes/prospectos
 * Reemplaza la migración anterior de customers
 */
return new class extends Migration
{
    public function up(): void
    {
        // Eliminar tabla anterior si existe
        Schema::dropIfExists('customers');

        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();

            // Tipo de persona
            $table->enum('type', ['PHYSICAL', 'MORAL']);

            // Datos principales
            $table->string('name')->comment('Nombre completo o Razón Social');
            $table->string('rfc', 13)->nullable();
            $table->string('curp', 18)->nullable()->comment('Solo para personas físicas');

            // Contacto
            $table->string('email')->nullable();
            $table->string('phone', 20)->nullable();
            $table->string('mobile', 20)->nullable();

            // Dirección
            $table->string('street')->nullable();
            $table->string('exterior_number', 20)->nullable();
            $table->string('interior_number', 20)->nullable();
            $table->string('neighborhood')->nullable()->comment('Colonia');
            $table->string('zip_code', 10)->nullable();
            $table->string('city')->nullable();
            $table->string('municipality')->nullable()->comment('Delegación/Municipio');
            $table->string('state')->nullable();

            // Datos adicionales para persona moral
            $table->string('legal_representative')->nullable();
            $table->string('legal_representative_rfc', 13)->nullable();

            // Datos de origen
            $table->string('source')->nullable()->comment('Fuente de captación');
            $table->foreignId('contact_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete()
                ->comment('Intermediario que refirió');

            // Auditoría
            $table->foreignId('created_by')
                ->constrained('users')
                ->cascadeOnDelete();

            $table->boolean('is_active')->default(true);
            $table->text('notes')->nullable();

            $table->timestamps();
            $table->softDeletes();

            // Índices
            $table->index(['type', 'is_active']);
            $table->index('rfc');
            $table->index('email');
            $table->index('name');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
