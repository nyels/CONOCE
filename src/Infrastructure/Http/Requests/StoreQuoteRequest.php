<?php

namespace Src\Infrastructure\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreQuoteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Permitimos que cualquiera use este endpoint por ahora
    }

    public function rules(): array
    {
        return [
            'customer_id' => ['required', 'exists:customers,id'],
            'type' => ['required', 'in:NEW,RENEWAL'],
            'payment_frequency' => ['required', 'in:ANNUAL,SEMIANNUAL,QUARTERLY,MONTHLY'],

            // Datos del Vehículo (Validamos el objeto JSON)
            'vehicle' => ['required', 'array'],
            'vehicle.brand' => ['required', 'string'],
            'vehicle.model' => ['required', 'string'],
            'vehicle.year' => ['required', 'integer'],
            'vehicle.description' => ['nullable', 'string'],

            // Opciones (Debe haber al menos 1 aseguradora seleccionada)
            'options' => ['required', 'array', 'min:1'],
            'options.*.insurer_id' => ['required', 'exists:insurers,id'],
            'options.*.package' => ['required', 'string'], // Ej: Amplia
            'options.*.net_premium' => ['required', 'numeric', 'min:0'], // El precio base
        ];
    }
}
