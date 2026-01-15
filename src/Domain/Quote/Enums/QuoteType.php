<?php

declare(strict_types=1);

namespace Src\Domain\Quote\Enums;

/**
 * Tipo de cotización: Nueva o Renovación
 */
enum QuoteType: string
{
    /**
     * Cotización nueva - Sin póliza previa
     */
    case NEW = 'NEW';

    /**
     * Renovación - Tiene póliza vigente a renovar
     */
    case RENEWAL = 'RENEWAL';

    public function label(): string
    {
        return match ($this) {
            self::NEW => 'Nueva',
            self::RENEWAL => 'Renovación',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::NEW => 'blue',
            self::RENEWAL => 'green',
        };
    }

    public function icon(): string
    {
        return match ($this) {
            self::NEW => 'plus-circle',
            self::RENEWAL => 'arrow-path',
        };
    }

    /**
     * Indica si requiere datos de póliza anterior
     */
    public function requiresPreviousPolicyData(): bool
    {
        return $this === self::RENEWAL;
    }
}
