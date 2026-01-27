<?php

namespace Src\Domain\Quote\Enums;

/**
 * Tipos de descripción de carga para vehículos tipo camión
 * Basado en clasificación estándar de aseguradoras mexicanas
 */
enum CargoDescription: string
{
    case NONE = 'none';                     // No aplica / vehículo particular
    case NON_HAZARDOUS = 'non_hazardous';   // A - No peligrosa
    case HAZARDOUS = 'hazardous';           // B - Peligrosa
    case VERY_HAZARDOUS = 'very_hazardous'; // C - Muy peligrosa

    /**
     * Obtiene la etiqueta en español
     */
    public function label(): string
    {
        return match ($this) {
            self::NONE => 'No aplica',
            self::NON_HAZARDOUS => 'A - No peligrosa',
            self::HAZARDOUS => 'B - Peligrosa',
            self::VERY_HAZARDOUS => 'C - Muy peligrosa',
        };
    }

    /**
     * Obtiene la descripción detallada
     */
    public function description(): string
    {
        return match ($this) {
            self::NONE => 'Vehículo de uso particular o comercial sin carga especial',
            self::NON_HAZARDOUS => 'Carga general: alimentos, textiles, muebles, electrónicos',
            self::HAZARDOUS => 'Materiales inflamables, corrosivos o tóxicos controlados',
            self::VERY_HAZARDOUS => 'Explosivos, radiactivos, gases comprimidos de alto riesgo',
        };
    }

    /**
     * Factor de riesgo para cálculo de prima
     */
    public function riskFactor(): float
    {
        return match ($this) {
            self::NONE => 1.0,
            self::NON_HAZARDOUS => 1.15,
            self::HAZARDOUS => 1.35,
            self::VERY_HAZARDOUS => 1.60,
        };
    }

    /**
     * Obtiene el color para UI
     */
    public function color(): string
    {
        return match ($this) {
            self::NONE => 'gray',
            self::NON_HAZARDOUS => 'green',
            self::HAZARDOUS => 'yellow',
            self::VERY_HAZARDOUS => 'red',
        };
    }

    /**
     * Determina si requiere documentación adicional
     */
    public function requiresPermit(): bool
    {
        return match ($this) {
            self::NONE, self::NON_HAZARDOUS => false,
            self::HAZARDOUS, self::VERY_HAZARDOUS => true,
        };
    }
}
