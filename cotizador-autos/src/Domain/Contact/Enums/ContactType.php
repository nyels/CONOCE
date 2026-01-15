<?php

declare(strict_types=1);

namespace Src\Domain\Contact\Enums;

/**
 * Tipo de intermediario/contacto comercial
 * 
 * Define la relación comercial con el intermediario
 */
enum ContactType: string
{
    /**
     * Agente - Intermediario principal con cédula CNSF
     */
    case AGENT = 'AGENT';

    /**
     * Subagente - Dependiente de un agente principal
     */
    case SUB_AGENT = 'SUB_AGENT';

    /**
     * Empleado - Personal interno de la correduría
     */
    case EMPLOYEE = 'EMPLOYEE';

    /**
     * Cliente Directo - Sin intermediario
     */
    case DIRECT = 'DIRECT';

    public function label(): string
    {
        return match ($this) {
            self::AGENT => 'Agente',
            self::SUB_AGENT => 'Subagente',
            self::EMPLOYEE => 'Empleado',
            self::DIRECT => 'Cliente Directo',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::AGENT => 'purple',
            self::SUB_AGENT => 'indigo',
            self::EMPLOYEE => 'blue',
            self::DIRECT => 'green',
        };
    }

    /**
     * Indica si puede tener comisión
     */
    public function hasCommission(): bool
    {
        return in_array($this, [self::AGENT, self::SUB_AGENT]);
    }

    /**
     * Indica si requiere datos de cédula CNSF
     */
    public function requiresCNSFLicense(): bool
    {
        return $this === self::AGENT;
    }

    /**
     * Indica si puede tener agente padre
     */
    public function canHaveParentAgent(): bool
    {
        return $this === self::SUB_AGENT;
    }

    /**
     * Comisión por defecto (porcentaje)
     */
    public function defaultCommissionRate(): float
    {
        return match ($this) {
            self::AGENT => 0.15,
            self::SUB_AGENT => 0.10,
            self::EMPLOYEE => 0.00,
            self::DIRECT => 0.00,
        };
    }
}
