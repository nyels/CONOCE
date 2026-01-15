<?php

declare(strict_types=1);

namespace Src\Domain\Quote\Enums;

/**
 * Frecuencias de pago disponibles
 * 
 * Define las modalidades de fraccionamiento del pago de la prima
 */
enum PaymentFrequency: string
{
    /**
     * Pago único - Sin recargo
     */
    case ANNUAL = 'ANNUAL';

    /**
     * Dos pagos al año
     */
    case SEMIANNUAL = 'SEMIANNUAL';

    /**
     * Cuatro pagos al año
     */
    case QUARTERLY = 'QUARTERLY';

    /**
     * Doce pagos al año
     */
    case MONTHLY = 'MONTHLY';

    public function label(): string
    {
        return match ($this) {
            self::ANNUAL => 'Contado (Anual)',
            self::SEMIANNUAL => 'Semestral',
            self::QUARTERLY => 'Trimestral',
            self::MONTHLY => 'Mensual',
        };
    }

    public function shortLabel(): string
    {
        return match ($this) {
            self::ANNUAL => 'Contado',
            self::SEMIANNUAL => 'Semestral',
            self::QUARTERLY => 'Trimestral',
            self::MONTHLY => 'Mensual',
        };
    }

    /**
     * Número de pagos que genera esta frecuencia
     */
    public function numberOfPayments(): int
    {
        return match ($this) {
            self::ANNUAL => 1,
            self::SEMIANNUAL => 2,
            self::QUARTERLY => 4,
            self::MONTHLY => 12,
        };
    }

    /**
     * Indica si aplica recargo por fraccionamiento
     */
    public function hasSurcharge(): bool
    {
        return $this !== self::ANNUAL;
    }

    /**
     * Obtiene el identificador para buscar el recargo en la configuración
     */
    public function surchargeKey(): ?string
    {
        return match ($this) {
            self::ANNUAL => null,
            self::SEMIANNUAL => 'surcharge_semiannual',
            self::QUARTERLY => 'surcharge_quarterly',
            self::MONTHLY => 'surcharge_monthly',
        };
    }

    /**
     * Calcula el monto de cada pago dado el total
     */
    public function calculatePaymentAmount(float $total): float
    {
        return round($total / $this->numberOfPayments(), 2);
    }

    /**
     * Ordena las frecuencias de menor a mayor número de pagos
     */
    public static function orderedByPayments(): array
    {
        return [
            self::ANNUAL,
            self::SEMIANNUAL,
            self::QUARTERLY,
            self::MONTHLY,
        ];
    }
}
