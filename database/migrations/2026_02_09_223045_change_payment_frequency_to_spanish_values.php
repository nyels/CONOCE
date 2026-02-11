<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Cambiar enum payment_frequency de inglés a español.
     * ANNUAL→ANUAL, SEMIANNUAL→SEMESTRAL, QUARTERLY→TRIMESTRAL, MONTHLY→MENSUAL
     */
    public function up(): void
    {
        // Primero actualizar datos existentes
        DB::table('quote_options')->where('payment_frequency', 'ANNUAL')->update(['payment_frequency' => 'ANUAL_TEMP']);
        DB::table('quote_options')->where('payment_frequency', 'SEMIANNUAL')->update(['payment_frequency' => 'SEMESTRAL_TEMP']);
        DB::table('quote_options')->where('payment_frequency', 'QUARTERLY')->update(['payment_frequency' => 'TRIMESTRAL_TEMP']);
        DB::table('quote_options')->where('payment_frequency', 'MONTHLY')->update(['payment_frequency' => 'MENSUAL_TEMP']);

        // Cambiar el enum
        DB::statement("ALTER TABLE quote_options MODIFY COLUMN payment_frequency ENUM('ANUAL','SEMESTRAL','TRIMESTRAL','MENSUAL','ANUAL_TEMP','SEMESTRAL_TEMP','TRIMESTRAL_TEMP','MENSUAL_TEMP')");

        // Actualizar de temporal a final
        DB::table('quote_options')->where('payment_frequency', 'ANUAL_TEMP')->update(['payment_frequency' => 'ANUAL']);
        DB::table('quote_options')->where('payment_frequency', 'SEMESTRAL_TEMP')->update(['payment_frequency' => 'SEMESTRAL']);
        DB::table('quote_options')->where('payment_frequency', 'TRIMESTRAL_TEMP')->update(['payment_frequency' => 'TRIMESTRAL']);
        DB::table('quote_options')->where('payment_frequency', 'MENSUAL_TEMP')->update(['payment_frequency' => 'MENSUAL']);

        // Quitar valores temporales del enum
        DB::statement("ALTER TABLE quote_options MODIFY COLUMN payment_frequency ENUM('ANUAL','SEMESTRAL','TRIMESTRAL','MENSUAL')");
    }

    /**
     * Revertir a valores en inglés.
     */
    public function down(): void
    {
        DB::table('quote_options')->where('payment_frequency', 'ANUAL')->update(['payment_frequency' => 'ANNUAL_TEMP']);
        DB::table('quote_options')->where('payment_frequency', 'SEMESTRAL')->update(['payment_frequency' => 'SEMIANNUAL_TEMP']);
        DB::table('quote_options')->where('payment_frequency', 'TRIMESTRAL')->update(['payment_frequency' => 'QUARTERLY_TEMP']);
        DB::table('quote_options')->where('payment_frequency', 'MENSUAL')->update(['payment_frequency' => 'MONTHLY_TEMP']);

        DB::statement("ALTER TABLE quote_options MODIFY COLUMN payment_frequency ENUM('ANNUAL','SEMIANNUAL','QUARTERLY','MONTHLY','ANNUAL_TEMP','SEMIANNUAL_TEMP','QUARTERLY_TEMP','MONTHLY_TEMP')");

        DB::table('quote_options')->where('payment_frequency', 'ANNUAL_TEMP')->update(['payment_frequency' => 'ANNUAL']);
        DB::table('quote_options')->where('payment_frequency', 'SEMIANNUAL_TEMP')->update(['payment_frequency' => 'SEMIANNUAL']);
        DB::table('quote_options')->where('payment_frequency', 'QUARTERLY_TEMP')->update(['payment_frequency' => 'QUARTERLY']);
        DB::table('quote_options')->where('payment_frequency', 'MONTHLY_TEMP')->update(['payment_frequency' => 'MONTHLY']);

        DB::statement("ALTER TABLE quote_options MODIFY COLUMN payment_frequency ENUM('ANNUAL','SEMIANNUAL','QUARTERLY','MONTHLY')");
    }
};
