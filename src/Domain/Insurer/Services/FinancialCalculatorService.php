<?php

namespace Src\Domain\Insurer\Services;

use Src\Domain\Insurer\Models\InsurerFinancialSetting;

class FinancialCalculatorService
{
    // IVA en México (16%)
    const TAX_RATE = 0.16;

    /**
     * Calcula el desglose total a partir de una Prima Neta.
     * * @param float $netPremium La prima neta que captura el usuario.
     * @param InsurerFinancialSetting $settings La configuración de costos de la aseguradora.
     * @param string $frequency Frecuencia de pago (ANNUAL, SEMIANNUAL, QUARTERLY, MONTHLY).
     */
    public function calculate(float $netPremium, InsurerFinancialSetting $settings, string $frequency): array
    {
        // 1. Obtener el Derecho de Póliza (Costo fijo)
        $policyFee = $settings->policy_fee;

        // 2. Obtener la tasa de recargo según la frecuencia
        $surchargeRate = match ($frequency) {
            'SEMIANNUAL' => $settings->surcharge_semiannual,
            'QUARTERLY'  => $settings->surcharge_quarterly,
            'MONTHLY'    => $settings->surcharge_monthly,
            default      => 0, // Anual no suele tener recargo
        };

        // 3. Calcular Monto de Recargo (Sobre la prima neta)
        $surchargeAmount = $netPremium * $surchargeRate;

        // 4. Subtotal (Base imponible)
        $subtotal = $netPremium + $surchargeAmount + $policyFee;

        // 5. Calcular IVA
        $tax = $subtotal * self::TAX_RATE;

        // 6. Total Final
        $total = $subtotal + $tax;

        // 7. Calcular Pagos Fraccionados (Primer pago vs Siguientes)
        return $this->calculateInstallments($total, $frequency, [
            'net_premium' => round($netPremium, 2),
            'policy_fee' => round($policyFee, 2),
            'surcharge' => round($surchargeAmount, 2),
            'tax' => round($tax, 2),
            'total_premium' => round($total, 2)
        ]);
    }

    /**
     * Divide el total en los pagos correspondientes.
     */
    private function calculateInstallments(float $total, string $frequency, array $breakdown): array
    {
        $installments = match ($frequency) {
            'SEMIANNUAL' => 2,
            'QUARTERLY'  => 4,
            'MONTHLY'    => 12,
            default      => 1,
        };

        if ($installments === 1) {
            $breakdown['first_payment'] = $breakdown['total_premium'];
            $breakdown['subsequent_payments'] = 0;
        } else {
            // Lógica estándar: Dividir en pagos iguales
            // (Nota: Si tu aseguradora cobra derechos solo en el primer pago, 
            // aquí es donde ajustaríamos esa lógica más adelante).
            $payment = $total / $installments;

            $breakdown['first_payment'] = round($payment, 2);
            $breakdown['subsequent_payments'] = round($payment, 2);
        }

        return $breakdown;
    }
}
