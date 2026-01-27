<?php

namespace App\Http\Requests;

use App\Rules\MexicanPhone;
use App\Rules\MexicanRFC;
use Illuminate\Foundation\Http\FormRequest;

class StoreQuoteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            // Cliente existente o nuevo
            'customer_id' => ['nullable', 'exists:customers,id'],

            // Datos de nuevo cliente (si no se selecciona uno existente)
            'new_customer' => ['nullable', 'array'],
            'new_customer.name' => ['nullable', 'required_without:customer_id', 'string', 'max:255'],
            'new_customer.phone' => ['nullable', 'string', new MexicanPhone()],
            'new_customer.email' => ['nullable', 'email:rfc,dns', 'max:255'],
            'new_customer.rfc' => ['nullable', 'string', new MexicanRFC()],
            'new_customer.type' => ['nullable', 'string', 'in:physical,moral'],

            // Tipo de cotización
            'quote_type' => ['required', 'string', 'in:new,renewal'],

            // Vehículo
            'vehicle' => ['required', 'array'],
            'vehicle.brand' => ['required', 'string', 'max:100'],
            'vehicle.model' => ['required', 'string', 'max:100'],
            'vehicle.year' => ['required', 'integer', 'min:1990', 'max:' . (date('Y') + 1)],
            'vehicle.version' => ['nullable', 'string', 'max:100'],
            'vehicle.value' => ['nullable', 'numeric', 'min:0', 'max:99999999'],
            'vehicle.usage' => ['nullable', 'string', 'in:personal,commercial'],
            'vehicle.cargo_description' => ['nullable', 'string', 'in:none,non_hazardous,hazardous,very_hazardous'],

            // Renovación (si aplica)
            'renewal' => ['nullable', 'array'],
            'renewal.insurer' => ['nullable', 'string', 'max:100'],
            'renewal.policy_number' => ['nullable', 'string', 'max:50'],
            'renewal.previous_premium' => ['nullable', 'numeric', 'min:0'],
            'renewal.expires_at' => ['nullable', 'date'],

            // Paquete de cobertura
            'coverage_package' => ['required', 'string', 'in:basic,standard,premium,full,limited,liability_only'],

            // Opciones de aseguradoras
            'options' => ['required', 'array', 'min:1', 'max:10'],
            'options.*.insurer_id' => ['required', 'exists:insurers,id'],
            'options.*.net_premium' => ['required', 'numeric', 'min:0'],
            'options.*.policy_fee' => ['required', 'numeric', 'min:0'],
            'options.*.iva' => ['required', 'numeric', 'min:0'],
            'options.*.total_premium' => ['required', 'numeric', 'min:0'],
            'options.*.coverage_package' => ['nullable', 'string'],
            'options.*.payment_frequency' => ['nullable', 'string', 'in:ANNUAL,BIANNUAL,QUARTERLY,MONTHLY'],
            'options.*.selected' => ['boolean'],

            // Extras
            'validity_days' => ['nullable', 'integer', 'min:1', 'max:30'],
            'notes' => ['nullable', 'string', 'max:1000'],
        ];
    }

    public function messages(): array
    {
        return [
            'customer_id.exists' => 'El cliente seleccionado no existe.',
            'new_customer.name.required_without' => 'Debe seleccionar un cliente o ingresar un nombre.',
            'quote_type.required' => 'El tipo de cotización es obligatorio.',
            'quote_type.in' => 'El tipo de cotización debe ser nueva o renovación.',
            'vehicle.brand.required' => 'La marca del vehículo es obligatoria.',
            'vehicle.model.required' => 'El modelo del vehículo es obligatorio.',
            'vehicle.year.required' => 'El año del vehículo es obligatorio.',
            'vehicle.year.min' => 'El año del vehículo no puede ser menor a 1990.',
            'vehicle.year.max' => 'El año del vehículo no puede ser mayor al próximo año.',
            'coverage_package.required' => 'Debe seleccionar un paquete de cobertura.',
            'options.required' => 'Debe agregar al menos una opción de aseguradora.',
            'options.min' => 'Debe agregar al menos una opción de aseguradora.',
            'options.max' => 'No puede agregar más de 10 opciones de aseguradora.',
            'options.*.insurer_id.required' => 'Cada opción debe tener una aseguradora.',
            'options.*.insurer_id.exists' => 'La aseguradora seleccionada no existe.',
            'options.*.net_premium.required' => 'La prima neta es obligatoria.',
            'options.*.total_premium.required' => 'El total de prima es obligatorio.',
        ];
    }

    public function attributes(): array
    {
        return [
            'customer_id' => 'cliente',
            'new_customer.name' => 'nombre del cliente',
            'new_customer.phone' => 'teléfono del cliente',
            'new_customer.email' => 'email del cliente',
            'new_customer.rfc' => 'RFC del cliente',
            'quote_type' => 'tipo de cotización',
            'vehicle.brand' => 'marca del vehículo',
            'vehicle.model' => 'modelo del vehículo',
            'vehicle.year' => 'año del vehículo',
            'vehicle.value' => 'valor del vehículo',
            'vehicle.cargo_description' => 'descripción de carga',
            'coverage_package' => 'paquete de cobertura',
            'options' => 'opciones de aseguradora',
            'validity_days' => 'días de vigencia',
            'notes' => 'notas',
        ];
    }
}
