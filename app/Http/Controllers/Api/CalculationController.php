<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Src\Domain\Financial\Contracts\FinancialCalculator;
use Src\Domain\Financial\DTOs\FinancialInput;
use Src\Domain\Financial\Exceptions\FinancialCalculationException;

/**
 * Controller para cálculos financieros.
 *
 * Usa CanonicalFinancialService como ÚNICA fuente de verdad.
 *
 * Endpoints:
 * - POST /api/quotes/calculate-premium → calculateNetPremium()
 * - POST /api/quotes/calculate-subsequent → calculateSubsequent()
 * - POST /api/quotes/calculate-realtime → calculateRealtime()
 * - POST /api/quotes/calculate-batch → calculateBatch()
 *
 * Response contracts LEGACY preservados:
 * - Error sin derecho: { error: 'no_derecho' }
 * - Error sin recargo: { error: 'no_recargo' }
 */
class CalculationController extends Controller
{
    private const FREQUENCY_MAP = [
        'ANUAL' => 'ANNUAL',
        'SEMESTRAL' => 'SEMIANNUAL',
        'TRIMESTRAL' => 'QUARTERLY',
        'MENSUAL' => 'MONTHLY',
    ];

    private const PAYMENTS_COUNT = [
        'ANUAL' => 1,
        'SEMESTRAL' => 2,
        'TRIMESTRAL' => 4,
        'MENSUAL' => 12,
    ];

    public function __construct(
        private readonly FinancialCalculator $calculator
    ) {}

    /**
     * Calcula la prima neta a partir de la prima total anual.
     *
     * Response contract LEGACY:
     * { prima_neta, derecho_costo, recargo, error? }
     */
    public function calculateNetPremium(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'prima_anual_neta' => ['required', 'numeric', 'min:0'],
            'forma_pago' => ['required', 'string', 'in:ANUAL,SEMESTRAL,TRIMESTRAL,MENSUAL'],
            'insurer_id' => ['required', 'integer', 'exists:insurers,id'],
        ]);

        $frequency = self::FREQUENCY_MAP[$validated['forma_pago']] ?? 'ANNUAL';

        try {
            $input = FinancialInput::fromTotalPremium(
                insurerId: (int) $validated['insurer_id'],
                frequency: $frequency,
                totalAnnualPremium: (float) $validated['prima_anual_neta'],
            );

            $result = $this->calculator->calculate($input);

            return response()->json($result->toLegacyResponse());
        } catch (FinancialCalculationException $e) {
            return response()->json([
                'prima_neta' => 0,
                'derecho_costo' => 0,
                'recargo' => 0,
                'error' => $e->errorCode(),
            ]);
        }
    }

    /**
     * Calcula los pagos subsecuentes.
     *
     * Response contract LEGACY:
     * { subsecuentes: float|"N/A", numero_pagos: int }
     */
    public function calculateSubsequent(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'prima_total_anual' => ['required', 'numeric', 'min:0'],
            'primer_pago' => ['required', 'numeric', 'min:0'],
            'forma_pago' => ['required', 'string', 'in:ANUAL,SEMESTRAL,TRIMESTRAL,MENSUAL'],
        ]);

        $formaPago = $validated['forma_pago'];
        $totalAnual = (float) $validated['prima_total_anual'];
        $primerPago = (float) $validated['primer_pago'];
        $numeroPagos = self::PAYMENTS_COUNT[$formaPago] ?? 1;

        if ($formaPago === 'ANUAL') {
            return response()->json([
                'subsecuentes' => 'N/A',
                'numero_pagos' => 1,
            ]);
        }

        // Fórmula unificada: subsecuente = (T - PP) / divisor
        $divisor = $numeroPagos - 1; // SEMESTRAL=1, TRIMESTRAL=3, MENSUAL=11
        $subsecuentes = round(($totalAnual - $primerPago) / $divisor, 2);

        return response()->json([
            'subsecuentes' => $subsecuentes,
            'numero_pagos' => $numeroPagos,
        ]);
    }

    /**
     * Calcula en tiempo real para una columna (usado por Vue watchers).
     */
    public function calculateRealtime(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'request_id' => ['nullable', 'string'],
            'insurer_id' => ['required', 'integer', 'exists:insurers,id'],
            'frequency' => ['required', 'string', 'in:ANNUAL,SEMIANNUAL,QUARTERLY,MONTHLY'],
            'total_annual' => ['nullable', 'numeric', 'min:0'],
            'first_payment' => ['nullable', 'numeric', 'min:0'],
        ]);

        $totalAnual = (float) ($validated['total_annual'] ?? 0);
        $primerPago = isset($validated['first_payment']) ? (float) $validated['first_payment'] : null;
        $insurerId = (int) $validated['insurer_id'];

        if ($totalAnual <= 0) {
            return response()->json([
                'request_id' => $validated['request_id'] ?? null,
                'calculation' => null,
            ]);
        }

        try {
            $input = FinancialInput::fromTotalPremium(
                insurerId: $insurerId,
                frequency: $validated['frequency'],
                totalAnnualPremium: $totalAnual,
                customFirstPayment: $primerPago,
            );

            $result = $this->calculator->calculate($input);

            return response()->json([
                'request_id' => $validated['request_id'] ?? null,
                'calculation' => [
                    'net_premium' => $result->netPremium,
                    'policy_fee' => $result->policyFee,
                    'surcharge_percentage' => $result->surchargePercentage,
                    'subsequent_payment' => $result->subsequentPayment,
                    'first_payment' => $result->firstPayment,
                    'payments_count' => $result->paymentsCount,
                    'error' => null,
                ],
            ]);
        } catch (FinancialCalculationException $e) {
            return response()->json([
                'request_id' => $validated['request_id'] ?? null,
                'calculation' => [
                    'net_premium' => 0,
                    'policy_fee' => 0,
                    'surcharge_percentage' => 0,
                    'subsequent_payment' => 0,
                    'first_payment' => $primerPago ?? 0,
                    'payments_count' => 0,
                    'error' => $e->errorCode(),
                ],
            ]);
        }
    }

    /**
     * Calcula en batch para múltiples columnas.
     */
    public function calculateBatch(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'request_id' => ['nullable', 'string'],
            'frequency' => ['required', 'string', 'in:ANNUAL,SEMIANNUAL,QUARTERLY,MONTHLY'],
            'options' => ['required', 'array'],
            'options.*.column' => ['required', 'integer', 'min:1', 'max:5'],
            'options.*.insurer_id' => ['required', 'integer', 'exists:insurers,id'],
            'options.*.total_annual' => ['nullable', 'numeric', 'min:0'],
            'options.*.first_payment' => ['nullable', 'numeric', 'min:0'],
        ]);

        $frequency = $validated['frequency'];
        $results = [];

        foreach ($validated['options'] as $option) {
            $column = $option['column'];
            $insurerId = (int) $option['insurer_id'];
            $totalAnual = (float) ($option['total_annual'] ?? 0);
            $primerPago = isset($option['first_payment']) ? (float) $option['first_payment'] : null;

            if ($totalAnual <= 0) {
                $results[$column] = [
                    'net_premium' => 0,
                    'policy_fee' => 0,
                    'surcharge_percentage' => 0,
                    'subsequent_payment' => 0,
                    'first_payment' => $primerPago ?? 0,
                    'payments_count' => 0,
                    'error' => null,
                ];
                continue;
            }

            try {
                $input = FinancialInput::fromTotalPremium(
                    insurerId: $insurerId,
                    frequency: $frequency,
                    totalAnnualPremium: $totalAnual,
                    customFirstPayment: $primerPago,
                );

                $result = $this->calculator->calculate($input);

                $results[$column] = [
                    'net_premium' => $result->netPremium,
                    'policy_fee' => $result->policyFee,
                    'surcharge_percentage' => $result->surchargePercentage,
                    'subsequent_payment' => $result->subsequentPayment,
                    'first_payment' => $result->firstPayment,
                    'payments_count' => $result->paymentsCount,
                    'error' => null,
                ];
            } catch (FinancialCalculationException $e) {
                $results[$column] = [
                    'net_premium' => 0,
                    'policy_fee' => 0,
                    'surcharge_percentage' => 0,
                    'subsequent_payment' => 0,
                    'first_payment' => $primerPago ?? 0,
                    'payments_count' => 0,
                    'error' => $e->errorCode(),
                ];
            }
        }

        return response()->json([
            'request_id' => $validated['request_id'] ?? null,
            'results' => $results,
        ]);
    }
}
