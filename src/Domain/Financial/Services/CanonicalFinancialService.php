<?php

declare(strict_types=1);

namespace Src\Domain\Financial\Services;

use App\Models\InsurerFinancialSetting;
use Src\Domain\Financial\Contracts\FinancialCalculator;
use Src\Domain\Financial\DTOs\FinancialBreakdown;
use Src\Domain\Financial\DTOs\FinancialInput;
use Src\Domain\Financial\Exceptions\FinancialCalculationException;

/**
 * SERVICIO FINANCIERO CANÓNICO ÚNICO.
 *
 * Matemática canónica:
 *   T = ( N * (1 + R) + D ) * 1.16
 *   N = ( (T / 1.16) - D ) / (1 + R)
 *
 * Donde:
 *   T = Prima total anual (lo que ingresa el usuario)
 *   N = Prima neta
 *   R = Tasa de recargo (decimal, ej: 0.035 para 3.5%)
 *   D = Derecho de póliza
 *   1.16 = Factor IVA (1 + 0.16)
 *
 * Subsecuentes:
 *   ANUAL:      primer_pago = T, subsecuente = 0
 *   SEMESTRAL:  subsecuente = T - primer_pago
 *   TRIMESTRAL: subsecuente = (T - primer_pago) / 3
 *   MENSUAL:    subsecuente = (T - primer_pago) / 11
 */
final class CanonicalFinancialService implements FinancialCalculator
{
    /**
     * IVA México. CONSTANTE. NO MODIFICAR.
     */
    private const IVA_RATE = 0.16;

    /**
     * Factor IVA = 1 + IVA_RATE. Usado para dividir/multiplicar.
     */
    private const IVA_FACTOR = 1.16;

    /**
     * Mapa frecuencia → número total de pagos.
     */
    private const PAYMENTS_COUNT = [
        'ANNUAL' => 1,
        'SEMIANNUAL' => 2,
        'QUARTERLY' => 4,
        'MONTHLY' => 12,
    ];

    /**
     * Mapa frecuencia → número de pagos SUBSECUENTES (total - 1).
     * Usado como divisor en la fórmula de subsecuentes.
     *
     * SEMESTRAL: 1 subsecuente (= T - primer_pago)
     * TRIMESTRAL: 3 subsecuentes
     * MENSUAL: 11 subsecuentes
     */
    private const SUBSEQUENT_DIVISOR = [
        'ANNUAL' => 0,
        'SEMIANNUAL' => 1,
        'QUARTERLY' => 3,
        'MONTHLY' => 11,
    ];

    // ================================================================
    // IMPLEMENTACIÓN DEL CONTRATO
    // ================================================================

    public function calculate(FinancialInput $input): FinancialBreakdown
    {
        // 1. Obtener configuración financiera vigente
        $settings = $this->resolveSettings($input->insurerId);
        $policyFee = $settings['policy_fee'];
        $surchargePercentage = $settings['surcharges'][$input->frequency];

        // 2. Calcular prima neta (fórmula inversa legacy)
        //    N = ( (T / 1.16) - D ) / (1 + R)
        $netPremium = $this->computeNetPremium(
            $input->totalAnnualPremium,
            $policyFee,
            $surchargePercentage
        );

        // 3. Validar N >= 0
        if ($netPremium < 0) {
            throw FinancialCalculationException::negativeNetPremium(
                $netPremium,
                $input->totalAnnualPremium
            );
        }

        // 4. Recalcular hacia adelante para consistencia
        //    T = ( N * (1 + R) + D ) * 1.16
        $surcharge = round($netPremium * ($surchargePercentage / 100), 2);
        $baseForIva = $netPremium + $policyFee + $surcharge;
        $iva = round($baseForIva * self::IVA_RATE, 2);
        $totalAnnual = round($baseForIva + $iva, 2);

        // 5. Calcular pagos según fórmula legacy
        $payments = $this->computePayments(
            $totalAnnual,
            $input->frequency,
            $input->customFirstPayment
        );

        return new FinancialBreakdown(
            netPremium: $netPremium,
            policyFee: $policyFee,
            surcharge: $surcharge,
            surchargePercentage: $surchargePercentage,
            iva: $iva,
            totalAnnual: $totalAnnual,
            frequency: $input->frequency,
            firstPayment: $payments['first_payment'],
            subsequentPayment: $payments['subsequent_payment'],
            paymentsCount: $payments['count'],
            insurerId: $input->insurerId,
        );
    }

    public function calculateBatch(array $inputs): array
    {
        $results = [];

        foreach ($inputs as $index => $input) {
            try {
                $results[$index] = $this->calculate($input);
            } catch (FinancialCalculationException $e) {
                $results[$index] = $e;
            }
        }

        return $results;
    }

    public function validate(FinancialBreakdown $breakdown, float $tolerance = 1.0): array
    {
        $errors = [];

        // Recalcular desde la prima neta para verificar coherencia
        $surcharge = round($breakdown->netPremium * ($breakdown->surchargePercentage / 100), 2);
        $baseForIva = $breakdown->netPremium + $breakdown->policyFee + $surcharge;
        $iva = round($baseForIva * self::IVA_RATE, 2);
        $expectedTotal = round($baseForIva + $iva, 2);

        if (abs($expectedTotal - $breakdown->totalAnnual) > $tolerance) {
            $errors[] = [
                'field' => 'total_annual',
                'expected' => $expectedTotal,
                'received' => $breakdown->totalAnnual,
                'message' => "Total anual inconsistente: esperado {$expectedTotal}, recibido {$breakdown->totalAnnual}",
            ];
        }

        // Validar que primer pago no exceda total (excepto ANUAL donde son iguales)
        if ($breakdown->paymentsCount > 1 && $breakdown->firstPayment >= $breakdown->totalAnnual) {
            $errors[] = [
                'field' => 'first_payment',
                'expected' => $breakdown->totalAnnual,
                'received' => $breakdown->firstPayment,
                'message' => "El primer pago no puede ser mayor o igual a la prima total anual",
            ];
        }

        // Validar subsecuente según fórmula legacy
        $expectedSubsequent = $this->computeExpectedSubsequent(
            $breakdown->totalAnnual,
            $breakdown->firstPayment,
            $breakdown->frequency
        );

        if (abs($expectedSubsequent - $breakdown->subsequentPayment) > $tolerance) {
            $errors[] = [
                'field' => 'subsequent_payment',
                'expected' => $expectedSubsequent,
                'received' => $breakdown->subsequentPayment,
                'message' => "Pago subsecuente inconsistente: esperado {$expectedSubsequent}, recibido {$breakdown->subsequentPayment}",
            ];
        }

        // Validar suma de pagos ≈ total anual
        $paymentsCount = self::PAYMENTS_COUNT[$breakdown->frequency] ?? 1;
        if ($paymentsCount > 1) {
            $sumOfPayments = $breakdown->firstPayment + ($breakdown->subsequentPayment * ($paymentsCount - 1));
            if (abs($sumOfPayments - $breakdown->totalAnnual) > ($tolerance * $paymentsCount)) {
                $errors[] = [
                    'field' => 'payments_sum',
                    'expected' => $breakdown->totalAnnual,
                    'received' => $sumOfPayments,
                    'message' => "Suma de pagos ({$sumOfPayments}) no coincide con total anual ({$breakdown->totalAnnual})",
                ];
            }
        }

        return $errors;
    }

    // ================================================================
    // MATEMÁTICA CANÓNICA (PRIVADA)
    // ================================================================

    /**
     * Fórmula inversa legacy EXACTA.
     *
     * ANUAL:       N = (T / 1.16) - D
     * FRACCIONADO: N = ((T / 1.16) - D) / (1 + R/100)
     *
     * Equivale a: N = ( (T / 1.16) - D ) / (1 + R)
     * donde R está en porcentaje (ej: 5 para 5%).
     */
    private function computeNetPremium(
        float $totalAnnualPremium,
        float $policyFee,
        float $surchargePercentage,
    ): float {
        $beforeTax = $totalAnnualPremium / self::IVA_FACTOR;
        $afterPolicyFee = $beforeTax - $policyFee;

        if ($surchargePercentage > 0) {
            $surchargeFactor = 1 + ($surchargePercentage / 100);
            return round($afterPolicyFee / $surchargeFactor, 2);
        }

        return round($afterPolicyFee, 2);
    }

    /**
     * Fórmula de pagos corregida.
     *
     * ANUAL:      primer_pago = T, subsecuente = 0
     * SEMESTRAL:  subsecuente = T - primer_pago
     * TRIMESTRAL: subsecuente = (T - primer_pago) / 3
     * MENSUAL:    subsecuente = (T - primer_pago) / 11
     */
    private function computePayments(
        float $totalAnnual,
        string $frequency,
        ?float $customFirstPayment,
    ): array {
        $paymentsCount = self::PAYMENTS_COUNT[$frequency];

        // ANUAL: pago único
        if ($paymentsCount === 1) {
            return [
                'first_payment' => $totalAnnual,
                'subsequent_payment' => 0.0,
                'count' => 1,
            ];
        }

        // Determinar primer pago
        if ($customFirstPayment !== null && $customFirstPayment > 0) {
            $firstPayment = $customFirstPayment;
        } else {
            $firstPayment = round($totalAnnual / $paymentsCount, 2);
        }

        // Subsecuente según fórmula legacy
        $subsequentPayment = $this->computeExpectedSubsequent(
            $totalAnnual,
            $firstPayment,
            $frequency
        );

        return [
            'first_payment' => round($firstPayment, 2),
            'subsequent_payment' => $subsequentPayment,
            'count' => $paymentsCount,
        ];
    }

    /**
     * Calcula el pago subsecuente esperado.
     *
     * SEMESTRAL corregido: subsecuente = total_anual - primer_pago
     * (Legacy tenía bug: usaba primer_pago, lo que hacía PP+PP ≠ T)
     */
    private function computeExpectedSubsequent(
        float $totalAnnual,
        float $firstPayment,
        string $frequency,
    ): float {
        return match ($frequency) {
            'ANNUAL' => 0.0,
            'SEMIANNUAL' => round($totalAnnual - $firstPayment, 2),
            'QUARTERLY' => round(($totalAnnual - $firstPayment) / 3, 2),
            'MONTHLY' => round(($totalAnnual - $firstPayment) / 11, 2),
            default => 0.0,
        };
    }

    // ================================================================
    // RESOLUCIÓN DE CONFIGURACIÓN FINANCIERA
    // ================================================================

    /**
     * Obtiene la configuración financiera vigente de una aseguradora.
     *
     * @throws FinancialCalculationException si no existe configuración vigente.
     *
     * @return array{
     *     policy_fee: float,
     *     surcharges: array{ANNUAL: float, SEMIANNUAL: float, QUARTERLY: float, MONTHLY: float}
     * }
     */
    private function resolveSettings(int $insurerId): array
    {
        $settings = InsurerFinancialSetting::where('insurer_id', $insurerId)
            ->where('valid_from', '<=', now())
            ->where(function ($q) {
                $q->whereNull('valid_until')
                    ->orWhere('valid_until', '>=', now());
            })
            ->latest('valid_from')
            ->first();

        if (!$settings) {
            throw FinancialCalculationException::noPolicyFee($insurerId);
        }

        // BD almacena recargos como decimal (0.05 = 5%)
        // Servicio trabaja con porcentaje (5.0)
        return [
            'policy_fee' => $settings->policy_fee_cents / 100,
            'surcharges' => [
                'ANNUAL' => 0.0,
                'SEMIANNUAL' => round((float) ($settings->surcharge_semiannual ?? 0) * 100, 4),
                'QUARTERLY' => round((float) ($settings->surcharge_quarterly ?? 0) * 100, 4),
                'MONTHLY' => round((float) ($settings->surcharge_monthly ?? 0) * 100, 4),
            ],
        ];
    }
}
