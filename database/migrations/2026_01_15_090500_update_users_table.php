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
        // Agregar columnas nuevas (verificando que no existan)
        Schema::table('users', function (Blueprint $table) {
            $table->string('role_new', 20)->nullable()->after('password');
            
            if (!Schema::hasColumn('users', 'phone')) {
                $table->string('phone', 20)->nullable()->after('role_new');
            }
            if (!Schema::hasColumn('users', 'avatar')) {
                $table->string('avatar')->nullable()->after('phone');
            }
            if (!Schema::hasColumn('users', 'last_login_at')) {
                $table->timestamp('last_login_at')->nullable()->after('avatar');
            }
            if (!Schema::hasColumn('users', 'deleted_at')) {
                $table->softDeletes();
            }
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
            if (Schema::hasColumn('users', 'deleted_at')) {
                $table->dropSoftDeletes();
            }
            $columns = ['phone', 'avatar', 'last_login_at'];
            foreach ($columns as $column) {
                if (Schema::hasColumn('users', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
