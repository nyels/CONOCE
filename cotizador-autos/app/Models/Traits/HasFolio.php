<?php

namespace App\Models\Traits;

use Illuminate\Support\Facades\DB;

/**
 * Trait para generación de folios secuenciales
 */
trait HasFolio
{
    /**
     * Genera un folio único con formato: PREFIJO-AÑO-SECUENCIA
     * Ejemplo: COT-2026-00001
     */
    protected function generateFolio(string $prefix): string
    {
        $year = now()->year;
        $type = strtolower($prefix);

        // Obtener o crear secuencia para este tipo y año
        $sequence = DB::table('sequences')
            ->where('type', $type)
            ->where('year', $year)
            ->lockForUpdate()
            ->first();

        if ($sequence) {
            $nextValue = $sequence->current_value + 1;
            DB::table('sequences')
                ->where('id', $sequence->id)
                ->update([
                    'current_value' => $nextValue,
                    'updated_at' => now(),
                ]);
        } else {
            $nextValue = 1;
            DB::table('sequences')->insert([
                'type' => $type,
                'prefix' => $prefix,
                'current_value' => $nextValue,
                'year' => $year,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        return sprintf('%s-%d-%05d', $prefix, $year, $nextValue);
    }

    /**
     * Obtiene el próximo folio sin reservarlo (para preview)
     */
    public static function previewNextFolio(string $prefix): string
    {
        $year = now()->year;
        $type = strtolower($prefix);

        $sequence = DB::table('sequences')
            ->where('type', $type)
            ->where('year', $year)
            ->first();

        $nextValue = ($sequence?->current_value ?? 0) + 1;

        return sprintf('%s-%d-%05d', $prefix, $year, $nextValue);
    }
}
