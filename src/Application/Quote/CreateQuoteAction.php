<?php

namespace Src\Application\Quote;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Src\Domain\Quote\Models\Quote;
use Src\Domain\Insurer\Models\Insurer;
use Src\Domain\Insurer\Services\FinancialCalculatorService;

class CreateQuoteAction
{
    // Inyectamos el servicio de cálculo que creaste en el paso anterior
    public function __construct(
        protected FinancialCalculatorService $calculator
    ) {}

    public function execute(array $data, int $agentId): Quote
    {
        return DB::transaction(function () use ($data, $agentId) {

            // 1. Crear la cabecera de la Cotización
            $quote = Quote::create([
                'uuid' => (string) Str::uuid(),
                'customer_id' => $data['customer_id'],
                'agent_id' => $agentId,
                'type' => $data['type'], // NEW o RENEWAL
                'folio' => 'COT-' . time(), // Generamos un folio simple por ahora
                'vehicle_data' => $data['vehicle'], // Guardamos los datos del auto
                'status' => 'DRAFT',
            ]);

            // 2. Procesar cada opción (Cada aseguradora seleccionada)
            foreach ($data['options'] as $optionData) {

                // Buscamos la aseguradora y su configuración de precios
                $insurer = Insurer::with('currentSettings')->find($optionData['insurer_id']);

                if (!$insurer || !$insurer->currentSettings) {
                    continue; // Si no hay config, saltamos
                }

                // 3. ¡AQUÍ USAMOS TU SERVICIO! Calculamos los montos exactos
                $financials = $this->calculator->calculate(
                    (float) $optionData['net_premium'], // Lo que capturó el usuario
                    $insurer->currentSettings,
                    $data['payment_frequency'] // ANUAL, SEMESTRAL, etc.
                );

                // 4. Guardar el detalle de la opción
                $quote->options()->create([
                    'insurer_id' => $insurer->id,
                    'coverage_package' => $optionData['package'], // Amplia, Limitada...
                    'coverages' => $optionData['coverages'] ?? [], // Coberturas extra
                    'payment_frequency' => $data['payment_frequency'],

                    // Guardamos los resultados del cálculo
                    'net_premium' => $financials['net_premium'],
                    'policy_fee' => $financials['policy_fee'],
                    'surcharge' => $financials['surcharge'],
                    'tax' => $financials['tax'],
                    'total_premium' => $financials['total_premium'],
                    'first_payment' => $financials['first_payment'],
                    'subsequent_payments' => $financials['subsequent_payments'],
                ]);
            }

            return $quote;
        });
    }
}
