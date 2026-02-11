<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Corrige el problema de UNIQUE index con strings vacíos
 *
 * Problema: MySQL trata '' (string vacío) como valor, causando
 * "Duplicate entry '' for key 'staff_curp_unique'"
 *
 * Solución:
 * 1. Convertir strings vacíos a NULL
 * 2. NULL no cuenta como duplicado en MySQL con índice UNIQUE
 */
return new class extends Migration
{
    public function up(): void
    {
        // Convertir strings vacíos a NULL - esto soluciona el problema
        // porque NULL no viola la restricción UNIQUE en MySQL
        DB::statement("UPDATE staff SET curp = NULL WHERE curp = ''");
        DB::statement("UPDATE staff SET rfc = NULL WHERE rfc = ''");
    }

    public function down(): void
    {
        // No hay rollback necesario
    }
};
