<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Models\InsurerFinancialSetting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Src\Domain\Financial\Contracts\FinancialCalculator;
use Src\Domain\Financial\DTOs\FinancialBreakdown;
use Src\Domain\Financial\DTOs\FinancialInput;
use Src\Domain\Financial\Exceptions\FinancialCalculationException;
use Src\Domain\Financial\Services\CanonicalFinancialService;
use Tests\TestCase;

/**
 * Tests de paridad legacy para el servicio financiero canónico.
 *
 * Cada test reproduce un cálculo EXACTO del sistema legacy PHP.
 *
 * Fórmulas verificadas:
 *   N = ( (T / 1.16) - D ) / (1 + R)
 *   T = ( N * (1 + R) + D ) * 1.16
 *
 * Subsecuentes:
 *   ANUAL:      0
 *   SEMESTRAL:  T - primer_pago
 *   TRIMESTRAL: (T - primer_pago) / 3
 *   MENSUAL:    (T - primer_pago) / 11
 */
class CanonicalFinancialServiceTest extends TestCase
{
    use RefreshDatabase;

    private CanonicalFinancialService $service;
    private int $insurerId;

    protected function setUp(): void
    {
        parent::setUp();

        $this->service = new CanonicalFinancialService();

        // Crear usuario para FK created_by
        $user = \App\Models\User::create([
            'name' => 'Test User',
            'email' => 'test@test.com',
            'password' => bcrypt('password'),
        ]);

        // Crear aseguradora de prueba
        $insurer = \App\Models\Insurer::create([
            'name' => 'Test Insurer',
            'code' => 'TST',
            'is_active' => true,
        ]);
        $this->insurerId = $insurer->id;

        // Configuración financiera:
        // Derecho: $500 (50000 centavos)
        // Recargo semestral: 3.5% (0.035)
        // Recargo trimestral: 5% (0.05)
        // Recargo mensual: 7% (0.07)
        InsurerFinancialSetting::create([
            'insurer_id' => $this->insurerId,
            'policy_fee_cents' => 50000,
            'surcharge_semiannual' => 0.035,
            'surcharge_quarterly' => 0.05,
            'surcharge_monthly' => 0.07,
            'valid_from' => now()->subYear(),
            'valid_until' => null,
            'created_by' => $user->id,
        ]);
    }

    // ================================================================
    // PARIDAD LEGACY: CÁLCULO INVERSO (T → N)
    // ================================================================

    /**
     * Legacy ANUAL:
     *   T = 10000
     *   N = (10000 / 1.16) - 500 = 8620.69 - 500 = 8120.69
     */
    public function test_anual_calculo_inverso_paridad_legacy(): void
    {
        $input = FinancialInput::fromTotalPremium(
            insurerId: $this->insurerId,
            frequency: 'ANNUAL',
            totalAnnualPremium: 10000.00,
        );

        $result = $this->service->calculate($input);

        // N = (10000 / 1.16) - 500
        $expectedNet = round((10000 / 1.16) - 500, 2);
        $this->assertEqualsWithDelta($expectedNet, $result->netPremium, 0.01);
        $this->assertEquals(500.0, $result->policyFee);
        $this->assertEquals(0.0, $result->surcharge);
        $this->assertEquals(0.0, $result->surchargePercentage);
        $this->assertEquals(1, $result->paymentsCount);
        // ANUAL: primer_pago = total
        $this->assertEqualsWithDelta($result->totalAnnual, $result->firstPayment, 0.01);
        $this->assertEquals(0.0, $result->subsequentPayment);
    }

    /**
     * Legacy SEMESTRAL:
     *   T = 10000, D = 500, R = 3.5%
     *   N = ((10000 / 1.16) - 500) / (1 + 0.035) = 8120.69 / 1.035
     */
    public function test_semestral_calculo_inverso_paridad_legacy(): void
    {
        $input = FinancialInput::fromTotalPremium(
            insurerId: $this->insurerId,
            frequency: 'SEMIANNUAL',
            totalAnnualPremium: 10000.00,
        );

        $result = $this->service->calculate($input);

        $expectedNet = round(((10000 / 1.16) - 500) / 1.035, 2);
        $this->assertEqualsWithDelta($expectedNet, $result->netPremium, 0.01);
        $this->assertEquals(500.0, $result->policyFee);
        $this->assertEqualsWithDelta(3.5, $result->surchargePercentage, 0.001);
        $this->assertEquals(2, $result->paymentsCount);
        // SEMESTRAL sin PP custom: PP = T/2, sub = T - T/2 = T/2 → iguales
        $this->assertEqualsWithDelta($result->firstPayment, $result->subsequentPayment, 0.01);
    }

    /**
     * Legacy TRIMESTRAL:
     *   T = 10000, D = 500, R = 5%
     *   N = ((10000 / 1.16) - 500) / 1.05
     */
    public function test_trimestral_calculo_inverso_paridad_legacy(): void
    {
        $input = FinancialInput::fromTotalPremium(
            insurerId: $this->insurerId,
            frequency: 'QUARTERLY',
            totalAnnualPremium: 10000.00,
            customFirstPayment: 3500.00,
        );

        $result = $this->service->calculate($input);

        $expectedNet = round(((10000 / 1.16) - 500) / 1.05, 2);
        $this->assertEqualsWithDelta($expectedNet, $result->netPremium, 0.01);
        $this->assertEquals(5.0, $result->surchargePercentage);
        $this->assertEquals(4, $result->paymentsCount);
        $this->assertEqualsWithDelta(3500.0, $result->firstPayment, 0.01);
        // TRIMESTRAL: subsecuente = (T_recalc - 3500) / 3
        $expectedSubsequent = round(($result->totalAnnual - 3500) / 3, 2);
        $this->assertEqualsWithDelta($expectedSubsequent, $result->subsequentPayment, 0.01);
    }

    /**
     * Legacy MENSUAL:
     *   T = 10000, D = 500, R = 7%
     *   N = ((10000 / 1.16) - 500) / 1.07
     */
    public function test_mensual_calculo_inverso_paridad_legacy(): void
    {
        $input = FinancialInput::fromTotalPremium(
            insurerId: $this->insurerId,
            frequency: 'MONTHLY',
            totalAnnualPremium: 10000.00,
            customFirstPayment: 1000.00,
        );

        $result = $this->service->calculate($input);

        $expectedNet = round(((10000 / 1.16) - 500) / 1.07, 2);
        $this->assertEqualsWithDelta($expectedNet, $result->netPremium, 0.01);
        $this->assertEqualsWithDelta(7.0, $result->surchargePercentage, 0.001);
        $this->assertEquals(12, $result->paymentsCount);
        $this->assertEqualsWithDelta(1000.0, $result->firstPayment, 0.01);
        // MENSUAL: subsecuente = (T_recalc - 1000) / 11
        $expectedSubsequent = round(($result->totalAnnual - 1000) / 11, 2);
        $this->assertEqualsWithDelta($expectedSubsequent, $result->subsequentPayment, 0.01);
    }

    // ================================================================
    // PARIDAD LEGACY: ROUNDTRIP (T → N → T)
    // ================================================================

    /**
     * Verifica que T → N → T preserva el valor original (roundtrip).
     * Tolerancia: $1 MXN por redondeo.
     */
    public function test_roundtrip_total_neta_total(): void
    {
        $frequencies = ['ANNUAL', 'SEMIANNUAL', 'QUARTERLY', 'MONTHLY'];

        foreach ($frequencies as $freq) {
            $originalTotal = 15000.00;

            $input = FinancialInput::fromTotalPremium(
                insurerId: $this->insurerId,
                frequency: $freq,
                totalAnnualPremium: $originalTotal,
            );

            $result = $this->service->calculate($input);

            // T_recalculado debe ≈ T_original
            $this->assertEqualsWithDelta(
                $originalTotal,
                $result->totalAnnual,
                1.0,
                "Roundtrip falló para {$freq}: original={$originalTotal}, recalculado={$result->totalAnnual}"
            );
        }
    }

    // ================================================================
    // PARIDAD LEGACY: FÓRMULA DIRECTA T = (N*(1+R)+D)*1.16
    // ================================================================

    /**
     * Dado N conocido, verificar que T = (N*(1+R)+D)*1.16
     */
    public function test_formula_directa_paridad(): void
    {
        // N = 8000, D = 500, R_semestral = 3.5%
        $n = 8000.0;
        $d = 500.0;
        $r = 0.035;

        // T = (N * (1 + R) + D) * 1.16
        $expectedTotal = round(($n * (1 + $r) + $d) * 1.16, 2);

        $input = FinancialInput::fromTotalPremium(
            insurerId: $this->insurerId,
            frequency: 'SEMIANNUAL',
            totalAnnualPremium: $expectedTotal,
        );

        $result = $this->service->calculate($input);

        // N_recalculado debe ≈ N_original
        $this->assertEqualsWithDelta($n, $result->netPremium, 1.0);
    }

    // ================================================================
    // PARIDAD LEGACY: SUBSECUENTES
    // ================================================================

    /**
     * Legacy SEMESTRAL: subsecuente = primer_pago (2 pagos IGUALES).
     * El usuario NO ingresa primer pago → sistema divide en 2.
     */
    public function test_semestral_sin_primer_pago_2_pagos_iguales(): void
    {
        $input = FinancialInput::fromTotalPremium(
            insurerId: $this->insurerId,
            frequency: 'SEMIANNUAL',
            totalAnnualPremium: 12000.00,
        );

        $result = $this->service->calculate($input);

        // Sin primer pago custom → primer_pago = total / 2
        $expectedFirst = round($result->totalAnnual / 2, 2);
        $this->assertEqualsWithDelta($expectedFirst, $result->firstPayment, 0.01);
        // Subsecuente = T - T/2 = T/2 → igual a primer_pago
        $this->assertEqualsWithDelta($result->firstPayment, $result->subsequentPayment, 0.01);
    }

    /**
     * SEMESTRAL con primer pago custom.
     * CORREGIDO: subsecuente = total_anual - primer_pago
     */
    public function test_semestral_con_primer_pago_custom(): void
    {
        $input = FinancialInput::fromTotalPremium(
            insurerId: $this->insurerId,
            frequency: 'SEMIANNUAL',
            totalAnnualPremium: 12000.00,
            customFirstPayment: 7000.00,
        );

        $result = $this->service->calculate($input);

        $this->assertEqualsWithDelta(7000.0, $result->firstPayment, 0.01);
        // CORREGIDO: subsecuente = total_anual - primer_pago
        $expectedSubsequent = round($result->totalAnnual - 7000.0, 2);
        $this->assertEqualsWithDelta($expectedSubsequent, $result->subsequentPayment, 0.01);
        // Verificar suma PP + subsecuente = total
        $this->assertEqualsWithDelta(
            $result->totalAnnual,
            $result->firstPayment + $result->subsequentPayment,
            0.01
        );
    }

    /**
     * SEMESTRAL con primer pago arbitrario: verifica que PP + sub = T.
     * Casos: PP grande, PP pequeño.
     */
    public function test_semestral_primer_pago_arbitrario_suma_es_total(): void
    {
        $testCases = [3000.0, 5000.0, 9000.0];

        foreach ($testCases as $pp) {
            $input = FinancialInput::fromTotalPremium(
                insurerId: $this->insurerId,
                frequency: 'SEMIANNUAL',
                totalAnnualPremium: 10000.00,
                customFirstPayment: $pp,
            );

            $result = $this->service->calculate($input);

            $this->assertEqualsWithDelta($pp, $result->firstPayment, 0.01);
            $expectedSub = round($result->totalAnnual - $pp, 2);
            $this->assertEqualsWithDelta($expectedSub, $result->subsequentPayment, 0.01,
                "SEMESTRAL PP={$pp}: sub esperado={$expectedSub}, obtenido={$result->subsequentPayment}");
            $this->assertEqualsWithDelta(
                $result->totalAnnual,
                $result->firstPayment + $result->subsequentPayment,
                0.01,
                "SEMESTRAL PP={$pp}: suma != total"
            );
        }
    }

    /**
     * Legacy TRIMESTRAL: subsecuente = (T - primer_pago) / 3
     */
    public function test_trimestral_subsecuentes_formula_legacy(): void
    {
        $input = FinancialInput::fromTotalPremium(
            insurerId: $this->insurerId,
            frequency: 'QUARTERLY',
            totalAnnualPremium: 20000.00,
            customFirstPayment: 8000.00,
        );

        $result = $this->service->calculate($input);

        $expectedSubsequent = round(($result->totalAnnual - 8000) / 3, 2);
        $this->assertEqualsWithDelta($expectedSubsequent, $result->subsequentPayment, 0.01);
    }

    /**
     * Legacy MENSUAL: subsecuente = (T - primer_pago) / 11
     */
    public function test_mensual_subsecuentes_formula_legacy(): void
    {
        $input = FinancialInput::fromTotalPremium(
            insurerId: $this->insurerId,
            frequency: 'MONTHLY',
            totalAnnualPremium: 24000.00,
            customFirstPayment: 2000.00,
        );

        $result = $this->service->calculate($input);

        $expectedSubsequent = round(($result->totalAnnual - 2000) / 11, 2);
        $this->assertEqualsWithDelta($expectedSubsequent, $result->subsequentPayment, 0.01);
    }

    // ================================================================
    // ERRORES DE DOMINIO
    // ================================================================

    /**
     * Legacy: echo 'no_derecho'
     * Sin configuración financiera → excepción con código 'no_derecho'.
     */
    public function test_error_no_derecho(): void
    {
        $input = FinancialInput::fromTotalPremium(
            insurerId: 99999, // No existe
            frequency: 'ANNUAL',
            totalAnnualPremium: 10000.00,
        );

        try {
            $this->service->calculate($input);
            $this->fail('Debió lanzar FinancialCalculationException');
        } catch (FinancialCalculationException $e) {
            $this->assertEquals('no_derecho', $e->errorCode());
        }
    }

    /**
     * Legacy: if(parseFloat(data)<0)
     * Prima neta negativa → excepción.
     */
    public function test_error_prima_neta_negativa(): void
    {
        $input = FinancialInput::fromTotalPremium(
            insurerId: $this->insurerId,
            frequency: 'ANNUAL',
            totalAnnualPremium: 100.00, // Muy bajo para cubrir derecho de $500
        );

        try {
            $this->service->calculate($input);
            $this->fail('Debió lanzar FinancialCalculationException');
        } catch (FinancialCalculationException $e) {
            $this->assertEquals('prima_neta_negativa', $e->errorCode());
        }
    }

    /**
     * Frecuencia inválida → excepción.
     */
    public function test_error_frecuencia_invalida(): void
    {
        $this->expectException(FinancialCalculationException::class);

        FinancialInput::fromTotalPremium(
            insurerId: $this->insurerId,
            frequency: 'BIMESTRAL',
            totalAnnualPremium: 10000.00,
        );
    }

    /**
     * Total <= 0 → excepción.
     */
    public function test_error_total_invalido(): void
    {
        $this->expectException(FinancialCalculationException::class);

        FinancialInput::fromTotalPremium(
            insurerId: $this->insurerId,
            frequency: 'ANNUAL',
            totalAnnualPremium: 0.0,
        );
    }

    /**
     * Primer pago >= total (fraccionado) → excepción.
     */
    public function test_error_primer_pago_excede_total(): void
    {
        $this->expectException(FinancialCalculationException::class);

        FinancialInput::fromTotalPremium(
            insurerId: $this->insurerId,
            frequency: 'QUARTERLY',
            totalAnnualPremium: 10000.00,
            customFirstPayment: 10000.00,
        );
    }

    // ================================================================
    // IVA
    // ================================================================

    /**
     * Verifica que IVA = (N + D + recargo) * 0.16
     */
    public function test_iva_se_aplica_sobre_base_correcta(): void
    {
        $input = FinancialInput::fromTotalPremium(
            insurerId: $this->insurerId,
            frequency: 'QUARTERLY',
            totalAnnualPremium: 15000.00,
        );

        $result = $this->service->calculate($input);

        $expectedBase = $result->netPremium + $result->policyFee + $result->surcharge;
        $expectedIva = round($expectedBase * 0.16, 2);
        $this->assertEqualsWithDelta($expectedIva, $result->iva, 0.01);

        // Total = base + IVA
        $expectedTotal = round($expectedBase + $expectedIva, 2);
        $this->assertEqualsWithDelta($expectedTotal, $result->totalAnnual, 0.01);
    }

    // ================================================================
    // BATCH
    // ================================================================

    public function test_calculate_batch(): void
    {
        $inputs = [
            FinancialInput::fromTotalPremium($this->insurerId, 'ANNUAL', 10000.00),
            FinancialInput::fromTotalPremium($this->insurerId, 'SEMIANNUAL', 10000.00),
            FinancialInput::fromTotalPremium($this->insurerId, 'QUARTERLY', 10000.00),
        ];

        $results = $this->service->calculateBatch($inputs);

        $this->assertCount(3, $results);
        $this->assertInstanceOf(FinancialBreakdown::class, $results[0]);
        $this->assertInstanceOf(FinancialBreakdown::class, $results[1]);
        $this->assertInstanceOf(FinancialBreakdown::class, $results[2]);
    }

    public function test_calculate_batch_con_error_no_detiene_otros(): void
    {
        $inputs = [
            FinancialInput::fromTotalPremium($this->insurerId, 'ANNUAL', 10000.00),
            FinancialInput::fromTotalPremium(99999, 'ANNUAL', 10000.00), // no_derecho
            FinancialInput::fromTotalPremium($this->insurerId, 'MONTHLY', 15000.00),
        ];

        $results = $this->service->calculateBatch($inputs);

        $this->assertCount(3, $results);
        $this->assertInstanceOf(FinancialBreakdown::class, $results[0]);
        $this->assertInstanceOf(FinancialCalculationException::class, $results[1]);
        $this->assertInstanceOf(FinancialBreakdown::class, $results[2]);
    }

    // ================================================================
    // VALIDACIÓN
    // ================================================================

    public function test_validate_resultado_coherente(): void
    {
        $input = FinancialInput::fromTotalPremium(
            insurerId: $this->insurerId,
            frequency: 'QUARTERLY',
            totalAnnualPremium: 15000.00,
            customFirstPayment: 5000.00,
        );

        $result = $this->service->calculate($input);
        $errors = $this->service->validate($result);

        $this->assertEmpty($errors, 'Un resultado recién calculado debe ser coherente');
    }

    // ================================================================
    // DTOs
    // ================================================================

    public function test_dto_to_cents(): void
    {
        $input = FinancialInput::fromTotalPremium(
            insurerId: $this->insurerId,
            frequency: 'ANNUAL',
            totalAnnualPremium: 10000.00,
        );

        $result = $this->service->calculate($input);
        $cents = $result->toCents();

        $this->assertEquals((int) round($result->netPremium * 100), $cents['net_premium_cents']);
        $this->assertEquals((int) round($result->policyFee * 100), $cents['policy_fee_cents']);
        $this->assertEquals((int) round($result->iva * 100), $cents['iva_cents']);
        $this->assertEquals((int) round($result->totalAnnual * 100), $cents['total_premium_cents']);
    }

    public function test_dto_to_legacy_response(): void
    {
        $input = FinancialInput::fromTotalPremium(
            insurerId: $this->insurerId,
            frequency: 'SEMIANNUAL',
            totalAnnualPremium: 10000.00,
        );

        $result = $this->service->calculate($input);
        $legacy = $result->toLegacyResponse();

        $this->assertArrayHasKey('prima_neta', $legacy);
        $this->assertArrayHasKey('derecho_costo', $legacy);
        $this->assertArrayHasKey('recargo', $legacy);
        $this->assertEquals($result->netPremium, $legacy['prima_neta']);
        $this->assertEquals($result->policyFee, $legacy['derecho_costo']);
        $this->assertEquals($result->surchargePercentage, $legacy['recargo']);
    }

    public function test_dto_to_legacy_subsequent_response_anual(): void
    {
        $input = FinancialInput::fromTotalPremium(
            insurerId: $this->insurerId,
            frequency: 'ANNUAL',
            totalAnnualPremium: 10000.00,
        );

        $result = $this->service->calculate($input);
        $legacy = $result->toLegacySubsequentResponse();

        $this->assertEquals('N/A', $legacy['subsecuentes']);
        $this->assertEquals(1, $legacy['numero_pagos']);
    }

    public function test_dto_to_legacy_subsequent_response_mensual(): void
    {
        $input = FinancialInput::fromTotalPremium(
            insurerId: $this->insurerId,
            frequency: 'MONTHLY',
            totalAnnualPremium: 10000.00,
            customFirstPayment: 1000.00,
        );

        $result = $this->service->calculate($input);
        $legacy = $result->toLegacySubsequentResponse();

        $this->assertIsFloat($legacy['subsecuentes']);
        $this->assertEquals(12, $legacy['numero_pagos']);
    }

    // ================================================================
    // CONTRATO (Interface binding)
    // ================================================================

    public function test_service_container_resuelve_interfaz(): void
    {
        $resolved = app(FinancialCalculator::class);
        $this->assertInstanceOf(CanonicalFinancialService::class, $resolved);
    }
}
