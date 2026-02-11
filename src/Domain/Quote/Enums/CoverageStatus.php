<?php

namespace Src\Domain\Quote\Enums;

/**
 * Estados de cobertura: AMPARADA o EXCLUIDA
 * Exactamente como en el sistema legacy
 */
enum CoverageStatus: string
{
    case AMPARADA = 'AMPARADA';
    case EXCLUIDA = 'EXCLUIDA';

    /**
     * Obtiene la etiqueta
     */
    public function label(): string
    {
        return $this->value;
    }

    /**
     * Determina si la cobertura está incluida
     */
    public function isIncluded(): bool
    {
        return $this === self::AMPARADA;
    }

    /**
     * Color para UI
     */
    public function color(): string
    {
        return match ($this) {
            self::AMPARADA => 'green',
            self::EXCLUIDA => 'gray',
        };
    }

    /**
     * Obtiene todos los estados para select
     */
    public static function forSelect(): array
    {
        return array_map(fn($case) => [
            'value' => $case->value,
            'label' => $case->label(),
        ], self::cases());
    }
}
