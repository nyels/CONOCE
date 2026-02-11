<?php

declare(strict_types=1);

namespace Src\Domain\Financial\DTOs;

/**
 * DTO de salida del servicio financiero canónico.
 *
 * Contiene el desglose completo del cálculo.
 * Todos los valores monetarios en PESOS (float, 2 decimales).
 * Inmutable.
 */
final class FinancialBreakdown
{
    public function __construct(
        /** Prima neta en pesos */
        public readonly float $netPremium,
        /** Derecho de póliza en pesos */
        public readonly float $policyFee,
        /** Monto del recargo en pesos */
        public readonly float $surcharge,
        /** Porcentaje del recargo (ej: 5.0 para 5%) */
        public readonly float $surchargePercentage,
        /** IVA en pesos */
        public readonly float $iva,
        /** Prima total anual en pesos (incluyendo todo) */
        public readonly float $totalAnnual,
        /** Frecuencia de pago */
        public readonly string $frequency,
        /** Primer pago en pesos */
        public readonly float $firstPayment,
        /** Pago subsecuente en pesos */
        public readonly float $subsequentPayment,
        /** Número total de pagos */
        public readonly int $paymentsCount,
        /** ID de la aseguradora */
        public readonly int $insurerId,
    ) {}

    /**
     * Array para persistencia en BD (pesos).
     */
    public function toArray(): array
    {
        return [
            'net_premium' => $this->netPremium,
            'policy_fee' => $this->policyFee,
            'surcharge' => $this->surcharge,
            'surcharge_percentage' => $this->surchargePercentage,
            'iva' => $this->iva,
            'total_annual' => $this->totalAnnual,
            'frequency' => $this->frequency,
            'first_payment' => $this->firstPayment,
            'subsequent_payment' => $this->subsequentPayment,
            'payments_count' => $this->paymentsCount,
            'insurer_id' => $this->insurerId,
        ];
    }

    /**
     * Array para persistencia en BD (centavos).
     * Convierte cada campo monetario a entero * 100.
     */
    public function toCents(): array
    {
        return [
            'net_premium_cents' => (int) round($this->netPremium * 100),
            'policy_fee_cents' => (int) round($this->policyFee * 100),
            'surcharge_cents' => (int) round($this->surcharge * 100),
            'iva_cents' => (int) round($this->iva * 100),
            'total_premium_cents' => (int) round($this->totalAnnual * 100),
            'first_payment_cents' => (int) round($this->firstPayment * 100),
            'subsequent_payment_cents' => (int) round($this->subsequentPayment * 100),
        ];
    }

    /**
     * Array formateado para respuestas de API / UI.
     */
    public function toFormattedArray(): array
    {
        $fmt = fn(float $v): string => number_format($v, 2, '.', ',');

        return [
            'net_premium' => $fmt($this->netPremium),
            'policy_fee' => $fmt($this->policyFee),
            'surcharge' => $fmt($this->surcharge),
            'surcharge_percentage' => $this->surchargePercentage . '%',
            'iva' => $fmt($this->iva),
            'total_annual' => $fmt($this->totalAnnual),
            'first_payment' => $fmt($this->firstPayment),
            'subsequent_payment' => $fmt($this->subsequentPayment),
            'payments_count' => $this->paymentsCount,
        ];
    }

    /**
     * Compatibilidad con contrato legacy del CalculationController.
     * Response contract: { prima_neta, derecho_costo, recargo }
     */
    public function toLegacyResponse(): array
    {
        return [
            'prima_neta' => $this->netPremium,
            'derecho_costo' => $this->policyFee,
            'recargo' => $this->surchargePercentage,
        ];
    }

    /**
     * Compatibilidad con contrato legacy de subsecuentes.
     * Response contract: { subsecuentes, numero_pagos }
     */
    public function toLegacySubsequentResponse(): array
    {
        return [
            'subsecuentes' => $this->paymentsCount === 1 ? 'N/A' : $this->subsequentPayment,
            'numero_pagos' => $this->paymentsCount,
        ];
    }
}
