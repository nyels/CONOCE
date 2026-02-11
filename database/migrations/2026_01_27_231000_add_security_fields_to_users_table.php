<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Agrega campos de seguridad bancaria a usuarios:
     * - username: login único
     * - staff_id: relación con personal
     * - password_expires_at: expiración cada 90 días
     * - password_changed_at: última actualización de password
     * - failed_login_attempts: intentos fallidos (para futuro bloqueo)
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Username único para login (en lugar de email)
            $table->string('username', 50)->nullable()->unique()->after('id');

            // Relación con personal (opcional, el usuario puede o no estar ligado)
            $table->foreignId('staff_id')
                ->nullable()
                ->after('username')
                ->constrained('staff')
                ->nullOnDelete();

            // Seguridad de contraseña
            $table->timestamp('password_expires_at')->nullable()->after('password');
            $table->timestamp('password_changed_at')->nullable()->after('password_expires_at');

            // Control de intentos fallidos
            $table->unsignedTinyInteger('failed_login_attempts')->default(0)->after('password_changed_at');
            $table->timestamp('locked_until')->nullable()->after('failed_login_attempts');

            // Índices
            $table->index('password_expires_at');
            $table->index('staff_id');
        });

        // Tabla de historial de contraseñas
        Schema::create('password_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('password');
            $table->timestamp('created_at');

            // Índice para búsqueda eficiente
            $table->index(['user_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('password_histories');

        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['password_expires_at']);
            $table->dropIndex(['staff_id']);
            $table->dropForeign(['staff_id']);
            $table->dropColumn([
                'username',
                'staff_id',
                'password_expires_at',
                'password_changed_at',
                'failed_login_attempts',
                'locked_until',
            ]);
        });
    }
};
