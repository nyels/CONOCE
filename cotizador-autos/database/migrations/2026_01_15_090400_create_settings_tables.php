<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Migración para el sistema de configuraciones del sistema
 */
return new class extends Migration
{
    public function up(): void
    {
        // Configuraciones del sistema
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->string('group', 50)->default('general')->comment('Grupo de configuración');
            $table->string('key', 100)->unique();
            $table->text('value')->nullable();
            $table->string('type', 20)->default('string')->comment('string, integer, boolean, json, encrypted');
            $table->text('description')->nullable();
            $table->boolean('is_public')->default(false)->comment('Si puede verse sin autenticación');
            $table->timestamps();

            $table->index(['group', 'key']);
        });

        // Historial de cambios de configuración
        Schema::create('setting_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('setting_id')->constrained()->cascadeOnDelete();
            $table->text('old_value')->nullable();
            $table->text('new_value')->nullable();
            $table->foreignId('changed_by')->constrained('users')->cascadeOnDelete();
            $table->string('change_reason')->nullable();
            $table->timestamp('created_at');

            $table->index(['setting_id', 'created_at']);
        });

        // Tabla de secuencias para folios
        Schema::create('sequences', function (Blueprint $table) {
            $table->id();
            $table->string('type', 50)->unique()->comment('quote, policy, customer, etc.');
            $table->string('prefix', 10);
            $table->unsignedInteger('current_value')->default(0);
            $table->unsignedSmallInteger('year')->default(2026);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sequences');
        Schema::dropIfExists('setting_histories');
        Schema::dropIfExists('settings');
    }
};
