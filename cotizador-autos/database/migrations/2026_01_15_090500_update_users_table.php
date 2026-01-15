<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

/**
 * Actualiza la tabla de usuarios para usar los nuevos roles del sistema
 */
return new class extends Migration
{
    public function up(): void
    {
        // Primero agregar columna temporal
        Schema::table('users', function (Blueprint $table) {
            $table->string('role_new', 20)->nullable()->after('password');
            $table->string('phone', 20)->nullable()->after('role_new');
            $table->string('avatar')->nullable()->after('phone');
            $table->timestamp('last_login_at')->nullable()->after('avatar');
            $table->boolean('two_factor_enabled')->default(false)->after('last_login_at');
            $table->text('two_factor_secret')->nullable()->after('two_factor_enabled');
            $table->text('two_factor_recovery_codes')->nullable()->after('two_factor_secret');
            $table->softDeletes();
        });

        // Migrar datos existentes
        DB::statement("UPDATE users SET role_new = CASE 
            WHEN role = 'ADMIN' THEN 'super_admin'
            WHEN role = 'MASTER' THEN 'admin'
            WHEN role = 'AGENT' THEN 'operator'
            WHEN role = 'EMPLOYEE' THEN 'operator'
            ELSE 'operator'
        END");

        // Eliminar columna vieja y renombrar nueva
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('role');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->renameColumn('role_new', 'role');
        });

        // Agregar índices
        Schema::table('users', function (Blueprint $table) {
            $table->index(['role', 'is_active']);
            $table->index('email');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['role', 'is_active']);
            $table->dropIndex(['email']);
            $table->dropSoftDeletes();
            $table->dropColumn([
                'phone',
                'avatar',
                'last_login_at',
                'two_factor_enabled',
                'two_factor_secret',
                'two_factor_recovery_codes',
            ]);
        });
    }
};
