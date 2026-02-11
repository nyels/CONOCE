<?php

declare(strict_types=1);

namespace Src\Domain\Quote\Enums;

/**
 * Frecuencias de pago disponibles.
 *
 * Valores en español para coincidir con frontend y validaciones.
 */
enum PaymentFrequency: string
{
    case ANNUAL = 'ANUAL';
    case SEMIANNUAL = 'SEMESTRAL';
    case QUARTERLY = 'TRIMESTRAL';
    case MONTHLY = 'MENSUAL';

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

    public function numberOfPayments(): int
    {
        return match ($this) {
            self::ANNUAL => 1,
            self::SEMIANNUAL => 2,
            self::QUARTERLY => 4,
            self::MONTHLY => 12,
        };
    }

    public function hasSurcharge(): bool
    {
        return $this !== self::ANNUAL;
    }

    public function surchargeKey(): ?string
    {
        return match ($this) {
            self::ANNUAL => null,
            self::SEMIANNUAL => 'surcharge_semiannual',
            self::QUARTERLY => 'surcharge_quarterly',
            self::MONTHLY => 'surcharge_monthly',
        };
    }

    public function calculatePaymentAmount(float $total): float
    {
        return round($total / $this->numberOfPayments(), 2);
    }

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
