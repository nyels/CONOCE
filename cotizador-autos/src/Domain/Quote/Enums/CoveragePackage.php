<?php

declare(strict_types=1);

namespace Src\Domain\Quote\Enums;

/**
 * Paquetes de cobertura estándar del mercado de seguros automotrices
 */
enum CoveragePackage: string
{
    /**
     * Cobertura Amplia - Todo riesgo
     * Incluye: Daños Materiales, Robo Total, RC, Gastos Médicos, 
     * Asistencia Vial, Protección Legal
     */
    case FULL = 'FULL';

    /**
     * Cobertura Limitada - Sin daños materiales
     * Incluye: Robo Total, RC, Gastos Médicos, Asistencia Vial
     */
    case LIMITED = 'LIMITED';

    /**
     * Solo Responsabilidad Civil
     * Incluye: RC Daños a Terceros
     */
    case LIABILITY_ONLY = 'LIABILITY_ONLY';

    /**
     * Personalizado - Combinación a medida
     */
    case CUSTOM = 'CUSTOM';

    public function label(): string
    {
        return match ($this) {
            self::FULL => 'Cobertura Amplia',
            self::LIMITED => 'Cobertura Limitada',
            self::LIABILITY_ONLY => 'RC Básica',
            self::CUSTOM => 'Personalizada',
        };
    }

    public function shortLabel(): string
    {
        return match ($this) {
            self::FULL => 'Amplia',
            self::LIMITED => 'Limitada',
            self::LIABILITY_ONLY => 'RC',
            self::CUSTOM => 'Custom',
        };
    }

    public function description(): string
    {
        return match ($this) {
            self::FULL => 'Protección completa incluyendo daños materiales, robo, responsabilidad civil y asistencias',
            self::LIMITED => 'Protección sin cobertura de daños materiales propios',
            self::LIABILITY_ONLY => 'Cobertura mínima legal de responsabilidad civil',
            self::CUSTOM => 'Configuración personalizada de coberturas',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::FULL => 'emerald',
            self::LIMITED => 'blue',
            self::LIABILITY_ONLY => 'amber',
            self::CUSTOM => 'purple',
        };
    }

    /**
     * Indica si incluye cobertura de daños materiales
     */
    public function includesMaterialDamage(): bool
    {
        return $this === self::FULL || $this === self::CUSTOM;
    }

    /**
     * Indica si incluye cobertura de robo total
     */
    public function includesTotalTheft(): bool
    {
        return in_array($this, [self::FULL, self::LIMITED, self::CUSTOM]);
    }

    /**
     * Obtiene las coberturas base de este paquete
     */
    public function getBaseCoverages(): array
    {
        return match ($this) {
            self::FULL => [
                'material_damage',
                'total_theft',
                'liability',
                'medical_expenses',
                'driver_accident',
                'legal_protection',
                'roadside_assistance'
            ],
            self::LIMITED => [
                'total_theft',
                'liability',
                'medical_expenses',
                'roadside_assistance'
            ],
            self::LIABILITY_ONLY => [
                'liability'
            ],
            self::CUSTOM => [],
        };
    }

    /**
     * Valor de ordenamiento para UI
     */
    public function sortOrder(): int
    {
        return match ($this) {
            self::FULL => 1,
            self::LIMITED => 2,
            self::LIABILITY_ONLY => 3,
            self::CUSTOM => 4,
        };
    }
}
