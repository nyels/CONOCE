<?php

declare(strict_types=1);

namespace Src\Domain\Quote\Services;

use Src\Domain\Shared\ValueObjects\Money;
use Src\Domain\Quote\Enums\PaymentFrequency;
use App\Models\Insurer;
use App\Models\InsurerFinancialSetting;

/**
 * Servicio de cálculo de primas de seguros
 * 
 * Este servicio encapsula toda la lógica de cálculo financiero
 * para cotizaciones de seguros automotrices.
 */
class PremiumCalculatorService
{
    /**
     * Resultado del cálculo de prima
     */
    public function calculate(
        Money $netPremium,
        Insurer $insurer,
        PaymentFrequency $frequency,
        ?InsurerFinancialSetting $financialSetting = null
    ): PremiumCalculationResult {
        // Obtener configuración financiera vigente
        $settings = $financialSetting ?? $insurer->currentFinancialSetting;

        if (!$settings) {
            throw new \RuntimeException(
                "No se encontró configuración financiera vigente para {$insurer->name}"
            );
        }

        // 1. Derecho de póliza
        $policyFee = Money::fromCents($settings->policy_fee_cents);

        // 2. Recargo por fraccionamiento
        $surchargeRate = $settings->getSurchargeForFrequency($frequency->value);
        $surcharge = $netPremium->percentage($surchargeRate * 100);

        // 3. Subtotal antes de IVA
        $subtotal = $netPremium->add($policyFee)->add($surcharge);

        // 4. IVA (solo aplica sobre ciertos conceptos según la aseguradora)
        // En la mayoría de los casos, el seguro de auto no grava IVA
        $iva = Money::zero();

        // 5. Prima total
        $totalPremium = $subtotal->add($iva);

        // 6. Desglose de pagos
        $payments = $this->calculatePayments($totalPremium, $policyFee, $frequency);

        return new PremiumCalculationResult(
            netPremium: $netPremium,
            policyFee: $policyFee,
            surcharge: $surcharge,
            surchargeRate: $surchargeRate,
            iva: $iva,
            totalPremium: $totalPremium,
            paymentFrequency: $frequency,
            firstPayment: $payments['first'],
            subsequentPayment: $payments['subsequent'],
            paymentsCount: $frequency->numberOfPayments()
        );
    }

    /**
     * Calcula el desglose de pagos
     * El primer pago generalmente incluye el derecho de póliza completo
     */
    private function calculatePayments(
        Money $totalPremium,
        Money $policyFee,
        PaymentFrequency $frequency
    ): array {
        $paymentsCount = $frequency->numberOfPayments();

        if ($paymentsCount === 1) {
            return [
                'first' => $totalPremium,
                'subsequent' => Money::zero(),
            ];
        }

        // Estrategia: dividir equitativamente pero el primer pago absorbe residuos
        $allocations = $totalPremium->allocate($paymentsCount);

        return [
            'first' => $allocations[0],
            'subsequent' => $allocations[1] ?? Money::zero(),
        ];
    }

    /**
     * Recalcula la prima con una nueva frecuencia de pago
     */
    public function recalculateForFrequency(
        Money $netPremium,
        Insurer $insurer,
        PaymentFrequency $newFrequency
    ): PremiumCalculationResult {
        return $this->calculate($netPremium, $insurer, $newFrequency);
    }

    /**
     * Compara el cálculo entre múltiples aseguradoras
     * 
     * @return PremiumCalculationResult[]
     */
    public function compareInsurers(
        Money $netPremium,
        array $insurers,
        PaymentFrequency $frequency
    ): array {
        $results = [];

        foreach ($insurers as $insurer) {
            try {
                $results[$insurer->id] = $this->calculate($netPremium, $insurer, $frequency);
            } catch (\RuntimeException $e) {
                // Registrar pero no fallar todo el cálculo
                $results[$insurer->id] = null;
            }
        }

        return $results;
    }

    /**
     * Calcula la diferencia respecto a una prima anterior (para renovaciones)
     */
    public function calculateRenewalDifference(
        Money $newPremium,
        Money $previousPremium
    ): RenewalDifference {
        $difference = $newPremium->subtract($previousPremium);

        $percentageChange = $previousPremium->isPositive()
            ? (($newPremium->cents() - $previousPremium->cents()) / $previousPremium->cents()) * 100
            : 0;

        return new RenewalDifference(
            previousPremium: $previousPremium,
            newPremium: $newPremium,
            difference: $difference,
            percentageChange: round($percentageChange, 2),
            isIncrease: $difference->isPositive(),
            isDecrease: $difference->isNegative(),
            isSame: $difference->isZero()
        );
    }
}

/**
 * DTO para el resultado del cálculo de prima
 */
final class PremiumCalculationResult
{
    public function __construct(
        public readonly Money $netPremium,
        public readonly Money $policyFee,
        public readonly Money $surcharge,
        public readonly float $surchargeRate,
        public readonly Money $iva,
        public readonly Money $totalPremium,
        public readonly PaymentFrequency $paymentFrequency,
        public readonly Money $firstPayment,
        public readonly Money $subsequentPayment,
        public readonly int $paymentsCount,
    ) {}

    /**
     * Convierte a array para persistencia o API
     */
    public function toArray(): array
    {
        return [
            'net_premium_cents' => $this->netPremium->cents(),
            'policy_fee_cents' => $this->policyFee->cents(),
            'surcharge_cents' => $this->surcharge->cents(),
            'surcharge_rate' => $this->surchargeRate,
            'iva_cents' => $this->iva->cents(),
            'total_premium_cents' => $this->totalPremium->cents(),
            'payment_frequency' => $this->paymentFrequency->value,
            'first_payment_cents' => $this->firstPayment->cents(),
            'subsequent_payment_cents' => $this->subsequentPayment->cents(),
            'payments_count' => $this->paymentsCount,
        ];
    }

    /**
     * Convierte a array formateado para UI
     */
    public function toDisplayArray(): array
    {
        return [
            'net_premium' => $this->netPremium->formatted(),
            'policy_fee' => $this->policyFee->formatted(),
            'surcharge' => $this->surcharge->formatted(),
            'surcharge_rate_label' => ($this->surchargeRate * 100) . '%',
            'iva' => $this->iva->formatted(),
            'total_premium' => $this->totalPremium->formatted(),
            'payment_frequency' => $this->paymentFrequency->label(),
            'first_payment' => $this->firstPayment->formatted(),
            'subsequent_payment' => $this->subsequentPayment->formatted(),
            'payments_count' => $this->paymentsCount,
        ];
    }

    /**
     * Obtiene el desglose de todos los pagos
     */
    public function getPaymentSchedule(): array
    {
        $schedule = [];

        for ($i = 1; $i <= $this->paymentsCount; $i++) {
            $schedule[] = [
                'number' => $i,
                'label' => $i === 1 ? 'Primer pago' : "Pago {$i}",
                'amount' => $i === 1 ? $this->firstPayment : $this->subsequentPayment,
            ];
        }

        return $schedule;
    }
}

/**
 * DTO para diferencia en renovación
 */
final class RenewalDifference
{
    public function __construct(
        public readonly Money $previousPremium,
        public readonly Money $newPremium,
        public readonly Money $difference,
        public readonly float $percentageChange,
        public readonly bool $isIncrease,
        public readonly bool $isDecrease,
        public readonly bool $isSame,
    ) {}

    public function toArray(): array
    {
        return [
            'previous_premium' => $this->previousPremium->formatted(),
            'new_premium' => $this->newPremium->formatted(),
            'difference' => $this->difference->formatted(),
            'percentage_change' => $this->percentageChange . '%',
            'is_increase' => $this->isIncrease,
            'is_decrease' => $this->isDecrease,
            'is_same' => $this->isSame,
            'trend' => $this->isIncrease ? 'up' : ($this->isDecrease ? 'down' : 'same'),
        ];
    }
}
