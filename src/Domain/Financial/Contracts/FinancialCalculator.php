<?php

declare(strict_types=1);

namespace Src\Domain\Financial\Contracts;

use Src\Domain\Financial\DTOs\FinancialInput;
use Src\Domain\Financial\DTOs\FinancialBreakdown;
use Src\Domain\Financial\Exceptions\FinancialCalculationException;

/**
 * CONTRATO ÚNICO del servicio financiero canónico.
 *
 * Matemática canónica (NO MODIFICAR):
 *   T = ( N * (1 + R) + D ) * 1.16
 *   N = ( (T / 1.16) - D ) / (1 + R)
 *
 * Reglas legacy (OBLIGATORIAS):
 * - El usuario ingresa TOTAL o PRIMER PAGO, NUNCA la neta.
 * - El primer pago NO se calcula si el usuario lo ingresó.
 * - Recargo solo aplica si forma de pago ≠ ANUAL.
 * - IVA siempre es 16%.
 * - Derecho se suma antes del IVA.
 * - Si N < 0 → FinancialCalculationException::negativeNetPremium.
 * - No puede haber dos servicios financieros.
 *
 * @throws FinancialCalculationException
 */
interface FinancialCalculator
{
    /**
     * Calcula el desglose financiero completo desde la prima total anual.
     *
     * Flujo principal: usuario ingresa T, sistema calcula N y desglose.
     *
     * @throws FinancialCalculationException
     */
    public function calculate(FinancialInput $input): FinancialBreakdown;

    /**
     * Calcula en lote para múltiples aseguradoras.
     *
     * @param FinancialInput[] $inputs
     * @return array<int, FinancialBreakdown|FinancialCalculationException>
     *         Key = índice del input. Value = resultado o excepción.
     */
    public function calculateBatch(array $inputs): array;

    /**
     * Valida coherencia entre valores ya persistidos.
     *
     * Retorna array vacío si es coherente.
     * Retorna array de discrepancias si no lo es.
     *
     * @return array<int, array{field: string, expected: float, received: float, message: string}>
     */
    public function validate(FinancialBreakdown $breakdown, float $tolerance = 1.0): array;
}
