<?php

declare(strict_types=1);

namespace Src\Domain\Financial\DTOs;

use Src\Domain\Financial\Exceptions\FinancialCalculationException;

/**
 * DTO de entrada para el servicio financiero canónico.
 *
 * Reglas legacy:
 * - El usuario ingresa TOTAL o PRIMER PAGO, NUNCA la neta.
 * - El primer pago NO se calcula por el sistema cuando el usuario lo ingresa.
 * - Frecuencia determina si aplica recargo.
 */
final class FinancialInput
{
    private const VALID_FREQUENCIES = ['ANUAL', 'SEMESTRAL', 'TRIMESTRAL', 'MENSUAL'];

    public readonly int $insurerId;
    public readonly string $frequency;
    public readonly float $totalAnnualPremium;
    public readonly ?float $customFirstPayment;

    private function __construct(
        int $insurerId,
        string $frequency,
        float $totalAnnualPremium,
        ?float $customFirstPayment,
    ) {
        $this->insurerId = $insurerId;
        $this->frequency = $frequency;
        $this->totalAnnualPremium = $totalAnnualPremium;
        $this->customFirstPayment = $customFirstPayment;
    }

    /**
     * Crea un DTO desde prima total anual (flujo principal del usuario).
     * Legacy: el usuario ingresa "cantidad_total_anual_opcion_X".
     */
    public static function fromTotalPremium(
        int $insurerId,
        string $frequency,
        float $totalAnnualPremium,
        ?float $customFirstPayment = null,
    ): self {
        if (!in_array($frequency, self::VALID_FREQUENCIES, true)) {
            throw FinancialCalculationException::invalidFrequency($frequency);
        }

        if ($totalAnnualPremium <= 0) {
            throw FinancialCalculationException::invalidTotalPremium($totalAnnualPremium);
        }

        if ($customFirstPayment !== null && $frequency !== 'ANUAL' && $customFirstPayment >= $totalAnnualPremium) {
            throw FinancialCalculationException::firstPaymentExceedsTotal($customFirstPayment, $totalAnnualPremium);
        }

        return new self($insurerId, $frequency, $totalAnnualPremium, $customFirstPayment);
    }
}
