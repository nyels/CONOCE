<?php

namespace App\Http\Requests;

use App\Models\InsurerFinancialSetting;
use App\Models\Insurer;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

/**
 * Request para validar la creación de cotización (Formato Legacy)
 * Preserva la estructura exacta del sistema legacy con todos los campos
 *
 * VALIDACIÓN CONDICIONAL POR cantidad_aseguradoras (N):
 * - Columnas 1..N: empresa_opcion required + exists
 * - Columnas N+1..5: empresa_opcion nullable (se ignoran)
 * - '0' NO es válido para columnas habilitadas
 */
class StoreQuoteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $n = (int) $this->input('cantidad_aseguradoras', 1);

        return [
            // ==========================================
            // ENCABEZADO
            // ==========================================
            'tipo_cotizacion' => ['required', 'string', 'in:NUEVA,RENOVACION'],
            'hora_solicitada' => [
                Rule::requiredIf($this->input('tipo_cotizacion') === 'NUEVA'),
                'nullable',
                'date_format:H:i',
            ],
            'contact_id' => ['nullable', 'exists:contacts,id'],
            'customer_id' => ['required', 'exists:customers,id'],

            // ==========================================
            // DATOS DEL ASEGURADO (informativos, readonly)
            // ==========================================
            'asegurado' => ['nullable', 'array'],
            'asegurado.apellido_paterno' => ['nullable', 'string', 'max:100'],
            'asegurado.apellido_materno' => ['nullable', 'string', 'max:100'],
            'asegurado.nombre' => ['nullable', 'string', 'max:100'],
            'asegurado.codigo_postal' => ['nullable', 'string', 'max:10'],
            'asegurado.colonia' => ['nullable', 'string', 'max:150'],
            'asegurado.estado' => ['nullable', 'string', 'max:100'],

            // ==========================================
            // DESCRIPCIÓN DEL VEHÍCULO
            // ==========================================
            'vehiculo' => ['required', 'array'],
            'vehiculo.marca' => ['required', 'string', 'max:100'],
            'vehiculo.descripcion' => ['nullable', 'string', 'max:200'],
            'vehiculo.modelo' => ['required', 'string', 'size:4', 'regex:/^\d{4}$/'],
            'vehiculo.uso_de_unidad' => ['nullable', 'string', 'max:100'],
            'vehiculo.tipo_auto' => ['required', 'exists:vehicle_types,id'],
            'vehiculo.carga' => ['nullable', 'string', 'in:A NO PELIGROSA,B PELIGROSA,C MUY PELIGROSA'],

            // ==========================================
            // INFORMACIÓN PÓLIZA A RENOVAR (si aplica)
            // ==========================================
            'renovacion' => ['nullable', 'array'],
            'renovacion.compania_actual' => [
                'nullable',
                Rule::requiredIf($this->input('tipo_cotizacion') === 'RENOVACION'),
                'string',
                'max:100',
            ],
            'renovacion.fecha_vigencia' => ['nullable', 'date'],
            'renovacion.poliza_a_renovar' => ['nullable', 'string', 'max:50'],
            'renovacion.prima_año' => ['nullable', 'string', 'max:20', 'regex:/^[\d,]+(\.\d{1,2})?$/'], // Formato moneda: "1,000.00"

            // ==========================================
            // CONFIGURACIÓN DE ASEGURADORAS
            // ==========================================
            'paquete' => ['required', 'string', 'in:AMPLIA,LIMITADA,RESPONSABILIDAD CIVIL'],
            'cantidad_aseguradoras' => ['required', 'integer', 'min:1', 'max:5'],

            // ==========================================
            // COBERTURAS (estructura dinámica por columna)
            // ==========================================
            'coverages' => ['required', 'array'],
            'coverages.forma_de_pago' => ['required', 'string', 'in:ANUAL,SEMESTRAL,TRIMESTRAL,MENSUAL'],

            // ==========================================
            // ASEGURADORAS - VALIDACIÓN CONDICIONAL POR N
            // Columnas 1..N: required + exists + not_in:0
            // Columnas N+1..5: nullable (payload debe enviar null)
            // ==========================================
            'coverages.empresa_opcion_1' => ['required', 'not_in:0', 'exists:insurers,id'],
            'coverages.empresa_opcion_2' => $n >= 2
                ? ['required', 'not_in:0', 'exists:insurers,id']
                : ['nullable'],
            'coverages.empresa_opcion_3' => $n >= 3
                ? ['required', 'not_in:0', 'exists:insurers,id']
                : ['nullable'],
            'coverages.empresa_opcion_4' => $n >= 4
                ? ['required', 'not_in:0', 'exists:insurers,id']
                : ['nullable'],
            'coverages.empresa_opcion_5' => $n >= 5
                ? ['required', 'not_in:0', 'exists:insurers,id']
                : ['nullable'],

            // ==========================================
            // COBERTURAS POR COLUMNA (expandidas para 1..5)
            // NOTA: Laravel wildcard * solo funciona para indices de arrays,
            // NO para sufijos de claves planas. Se expanden explicitamente.
            // ==========================================
            ...collect(range(1, 5))->mapWithKeys(fn($i) => [
                "coverages.danos_opcion_selec_{$i}" => ['nullable', 'string', 'in:0,V.COMERCIAL,V.CONVENIDO,V.FACTURA'],
                "coverages.danos_material_importe_factura_{$i}" => ['nullable', 'string', 'max:20'],
                "coverages.deducible_opcion_{$i}" => ['nullable', 'string', 'in:na,0,3,5,10,15,20'],
                "coverages.robo_opcion_selec_{$i}" => ['nullable', 'string', 'in:0,V.COMERCIAL,V.CONVENIDO,V.FACTURA'],
                "coverages.robo_importe_factura_{$i}" => ['nullable', 'string', 'max:20'],
                "coverages.deducible_rt_{$i}" => ['nullable', 'string', 'in:na,0,5,10,15,20'],
                "coverages.cristales_opcion_selec_{$i}" => ['nullable', 'string', 'in:AMPARADA'],
                "coverages.danos_tercero_opcion_{$i}" => ['nullable', 'string', 'max:20'],
                "coverages.deducible_de_rc_opcion_{$i}" => ['nullable', 'integer', 'min:0', 'max:100'],
                "coverages.fallecimiento_opcion_{$i}" => ['nullable', 'string', 'max:20'],
                "coverages.gastos_medicos_opcion_{$i}" => ['nullable', 'string', 'max:20'],
                "coverages.accidente_conducir_opcion_{$i}" => ['nullable', 'string', 'max:20'],
                "coverages.proteccion_opcion_selec_{$i}" => ['nullable', 'string', 'in:AMPARADA'],
                "coverages.asistencia_vial_opcion_selec_{$i}" => ['nullable', 'string', 'in:AMPARADA'],
                "coverages.danos_carga_opcion_selec_{$i}" => ['nullable', 'string', 'in:0,AMPARADA,EXCLUIDA'],
                "coverages.adaptaciones_opcion_{$i}" => ['nullable', 'string', 'max:20'],
                "coverages.extension_rc_opcion_{$i}" => ['nullable', 'string', 'in:0,AMPARADA,EXCLUIDA'],
                "coverages.cobertura_opcion_1_select_{$i}" => ['nullable', 'string', 'in:0,AMPARADA,EXCLUIDA'],
                "coverages.cobertura_opcion_2_select_{$i}" => ['nullable', 'string', 'in:0,AMPARADA,EXCLUIDA'],
                "coverages.cantidad_prima_neta_opcion_{$i}" => ['nullable', 'string', 'max:20'],
                "coverages.cantidad_total_anual_opcion_{$i}" => ['nullable', 'string', 'max:20'],
                "coverages.primer_pago_opcion_{$i}" => ['nullable', 'string', 'max:20'],
                "coverages.subsecuente_opcion_{$i}" => ['nullable', 'string', 'max:20'],
            ])->all(),

            // Descripción general de coberturas
            'coverages.descripcion_tabla' => ['nullable', 'string', 'max:500'],

            // ==========================================
            // COBERTURAS OPCIONALES (nombres dinámicos)
            // ==========================================
            'custom_coverage_1_name' => ['nullable', 'string', 'max:100'],
            'custom_coverage_2_name' => ['nullable', 'string', 'max:100'],

            // ==========================================
            // NOTAS
            // ==========================================
            'notas' => ['nullable', 'string', 'max:2000'],
        ];
    }

    public function messages(): array
    {
        return [
            'tipo_cotizacion.required' => 'El tipo de cotización es obligatorio.',
            'tipo_cotizacion.in' => 'El tipo debe ser NUEVA o RENOVACION.',
            'customer_id.required' => 'Debe seleccionar un asegurado/prospecto.',
            'customer_id.exists' => 'El asegurado seleccionado no existe.',
            'contact_id.exists' => 'El contacto seleccionado no existe.',

            'vehiculo.marca.required' => 'La marca del vehículo es obligatoria.',
            'vehiculo.modelo.required' => 'El modelo (año) del vehículo es obligatorio.',
            'vehiculo.modelo.size' => 'El modelo debe ser un año de 4 dígitos.',
            'vehiculo.modelo.regex' => 'El modelo debe contener solo números.',
            'vehiculo.tipo_auto.required' => 'El tipo de vehículo es obligatorio.',
            'vehiculo.tipo_auto.exists' => 'El tipo de vehículo seleccionado no existe.',
            'vehiculo.carga.in' => 'La descripción de carga no es válida.',

            'renovacion.compania_actual.required_if' => 'La compañía actual es obligatoria para renovaciones.',

            'paquete.required' => 'Debe seleccionar un tipo de paquete.',
            'paquete.in' => 'El paquete debe ser AMPLIA, LIMITADA o RESPONSABILIDAD CIVIL.',
            'cantidad_aseguradoras.required' => 'Debe seleccionar la cantidad de aseguradoras.',
            'cantidad_aseguradoras.min' => 'Debe seleccionar al menos 1 aseguradora.',
            'cantidad_aseguradoras.max' => 'El máximo de aseguradoras es 5.',

            'coverages.required' => 'Los datos de coberturas son obligatorios.',
            'coverages.forma_de_pago.required' => 'Debe seleccionar la forma de pago.',
            'coverages.forma_de_pago.in' => 'La forma de pago no es válida.',

            'coverages.empresa_opcion_1.required' => 'Debe seleccionar la aseguradora 1.',
            'coverages.empresa_opcion_1.not_in' => 'Debe seleccionar la aseguradora 1.',
            'coverages.empresa_opcion_1.exists' => 'La aseguradora 1 no existe.',

            'coverages.empresa_opcion_2.required' => 'Debe seleccionar la aseguradora 2.',
            'coverages.empresa_opcion_2.not_in' => 'Debe seleccionar la aseguradora 2.',
            'coverages.empresa_opcion_2.exists' => 'La aseguradora 2 no existe.',

            'coverages.empresa_opcion_3.required' => 'Debe seleccionar la aseguradora 3.',
            'coverages.empresa_opcion_3.not_in' => 'Debe seleccionar la aseguradora 3.',
            'coverages.empresa_opcion_3.exists' => 'La aseguradora 3 no existe.',

            'coverages.empresa_opcion_4.required' => 'Debe seleccionar la aseguradora 4.',
            'coverages.empresa_opcion_4.not_in' => 'Debe seleccionar la aseguradora 4.',
            'coverages.empresa_opcion_4.exists' => 'La aseguradora 4 no existe.',

            'coverages.empresa_opcion_5.required' => 'Debe seleccionar la aseguradora 5.',
            'coverages.empresa_opcion_5.not_in' => 'Debe seleccionar la aseguradora 5.',
            'coverages.empresa_opcion_5.exists' => 'La aseguradora 5 no existe.',

            'hora_solicitada.required_if' => 'La hora solicitada es obligatoria para cotizaciones nuevas.',
        ];
    }

    public function attributes(): array
    {
        return [
            'tipo_cotizacion' => 'tipo de cotización',
            'hora_solicitada' => 'hora solicitada',
            'contact_id' => 'contacto',
            'customer_id' => 'asegurado/prospecto',
            'vehiculo.marca' => 'marca del vehículo',
            'vehiculo.descripcion' => 'descripción del vehículo',
            'vehiculo.modelo' => 'modelo (año)',
            'vehiculo.uso_de_unidad' => 'uso de la unidad',
            'vehiculo.tipo_auto' => 'tipo de vehículo',
            'vehiculo.carga' => 'descripción de carga',
            'renovacion.compania_actual' => 'compañía actual',
            'renovacion.fecha_vigencia' => 'fin de vigencia',
            'renovacion.poliza_a_renovar' => 'póliza a renovar',
            'renovacion.prima_año' => 'prima del año anterior',
            'paquete' => 'tipo de paquete',
            'cantidad_aseguradoras' => 'cantidad de aseguradoras',
            'notas' => 'notas internas',
        ];
    }

    /**
     * Prepara los datos antes de la validación
     * Convierte valores de moneda a formato numérico si es necesario
     */
    protected function prepareForValidation(): void
    {
        $vehiculo = $this->input('vehiculo', []);
        $changed = false;

        // Convertir modelo a string si viene como número
        if (isset($vehiculo['modelo']) && is_numeric($vehiculo['modelo'])) {
            $vehiculo['modelo'] = (string) $vehiculo['modelo'];
            $changed = true;
        }

        // Convertir placeholder '0' de carga a null
        if (isset($vehiculo['carga']) && ($vehiculo['carga'] === '0' || $vehiculo['carga'] === 0)) {
            $vehiculo['carga'] = null;
            $changed = true;
        }

        // Convertir placeholder '0' de tipo_auto a null
        if (isset($vehiculo['tipo_auto']) && ($vehiculo['tipo_auto'] === '0' || $vehiculo['tipo_auto'] === 0)) {
            $vehiculo['tipo_auto'] = null;
            $changed = true;
        }

        if ($changed) {
            $this->merge(['vehiculo' => $vehiculo]);
        }
    }

    /**
     * VALIDACION SEMANTICA BACKEND
     *
     * El backend valida coherencia matematica de los valores.
     * Estas validaciones son la UNICA fuente de verdad.
     *
     * Reglas validadas:
     * 1. primer_pago <= total_anual
     * 2. Si hay total_anual, debe haber aseguradora seleccionada
     * 3. Valores monetarios deben ser positivos
     * 4. Validacion de regex para campos criticos (backend mirror de frontend)
     */
    public function withValidator($validator): void
    {
        $validator->after(function ($validator) {
            $n = (int) $this->input('cantidad_aseguradoras', 1);
            $coverages = $this->input('coverages', []);
            $formaDePago = $coverages['forma_de_pago'] ?? 'ANUAL';

            for ($col = 1; $col <= $n; $col++) {
                $empresaKey = "empresa_opcion_{$col}";
                $totalKey = "cantidad_total_anual_opcion_{$col}";
                $primerPagoKey = "primer_pago_opcion_{$col}";
                $subsecuenteKey = "subsecuente_opcion_{$col}";
                $primaNetaKey = "cantidad_prima_neta_opcion_{$col}";

                $empresa = $coverages[$empresaKey] ?? '0';
                $totalStr = $coverages[$totalKey] ?? '';
                $primerPagoStr = $coverages[$primerPagoKey] ?? '';
                $subsecuenteStr = $coverages[$subsecuenteKey] ?? '';
                $primaNetaStr = $coverages[$primaNetaKey] ?? '';

                $total = $this->parseMoney($totalStr);
                $primerPago = $this->parseMoney($primerPagoStr);
                $subsecuente = $this->parseMoney($subsecuenteStr);
                $primaNeta = $this->parseMoney($primaNetaStr);

                // 1. Si hay total_anual, debe haber aseguradora
                if ($total > 0 && ($empresa === '0' || empty($empresa))) {
                    $validator->errors()->add(
                        "coverages.{$empresaKey}",
                        "Debe seleccionar una aseguradora para la opcion {$col}."
                    );
                }

                // 2. primer_pago no puede exceder total_anual
                if ($total > 0 && $primerPago > 0 && $primerPago > $total) {
                    $validator->errors()->add(
                        "coverages.{$primerPagoKey}",
                        "El primer pago de la opcion {$col} no puede ser mayor a la prima total anual."
                    );
                }

                // 3. Valores monetarios deben ser positivos o cero
                if ($total < 0) {
                    $validator->errors()->add(
                        "coverages.{$totalKey}",
                        "La prima total anual de la opcion {$col} no puede ser negativa."
                    );
                }

                if ($primerPago < 0) {
                    $validator->errors()->add(
                        "coverages.{$primerPagoKey}",
                        "El primer pago de la opcion {$col} no puede ser negativo."
                    );
                }

                // 4. Prima neta no puede ser negativa (si el backend la calculo)
                if ($primaNeta < 0) {
                    $validator->errors()->add(
                        "coverages.{$primaNetaKey}",
                        "La prima neta de la opcion {$col} resulto negativa. Verifique la configuracion."
                    );
                }

                // 5. Si no es ANUAL y hay total, primer_pago es obligatorio
                if ($formaDePago !== 'ANUAL' && $total > 0 && $primerPago <= 0) {
                    $validator->errors()->add(
                        "coverages.{$primerPagoKey}",
                        "Debe ingresar el primer pago para la opcion {$col}."
                    );
                }

                // 6. Si no es ANUAL, verificar coherencia de pagos
                if ($formaDePago !== 'ANUAL' && $total > 0 && $primerPago > 0) {
                    $paymentsCount = match ($formaDePago) {
                        'SEMESTRAL' => 2,
                        'TRIMESTRAL' => 4,
                        'MENSUAL' => 12,
                        default => 1,
                    };

                    // Calcular suma esperada de pagos
                    $sumaPagos = $primerPago + ($subsecuente * ($paymentsCount - 1));

                    // Tolerancia de $5 MXN por redondeos
                    $tolerance = 5.0;

                    if (abs($sumaPagos - $total) > $tolerance * $paymentsCount) {
                        // Solo advertencia, no error (el backend recalculara)
                        // Esto es para detectar manipulacion maliciosa
                        activity()
                            ->withProperties([
                                'column' => $col,
                                'total' => $total,
                                'primer_pago' => $primerPago,
                                'subsecuente' => $subsecuente,
                                'suma_pagos' => $sumaPagos,
                                'diferencia' => abs($sumaPagos - $total),
                            ])
                            ->log('payment_sum_mismatch_warning');
                    }
                }
            }

            // 7. Verificar configuración financiera para cada aseguradora seleccionada
            $this->validateFinancialSettings($validator, $n, $coverages);

            // Validaciones de campos de vehiculo (mirror de frontend)
            $this->validateVehicleFields($validator);

            // Validaciones de campos de renovacion (mirror de frontend)
            $this->validateRenewalFields($validator);
        });
    }

    /**
     * Valida campos de vehiculo con regex (mirror de frontend)
     * LEGACY: formulario.js lineas 14091-14243
     */
    private function validateVehicleFields($validator): void
    {
        $vehiculo = $this->input('vehiculo', []);

        // Marca: solo letras y espacios
        $marca = $vehiculo['marca'] ?? '';
        if (!empty($marca) && !preg_match('/^[a-zA-Z\s\x{00C0}-\x{017F}]+$/u', $marca)) {
            $validator->errors()->add(
                'vehiculo.marca',
                'La marca solo puede contener letras.'
            );
        }

        // Descripcion: alfanumerico con algunos caracteres especiales
        $descripcion = $vehiculo['descripcion'] ?? '';
        if (!empty($descripcion) && !preg_match('/^[a-zA-Z0-9\s\x{00C0}-\x{017F}\.,\-]+$/u', $descripcion)) {
            $validator->errors()->add(
                'vehiculo.descripcion',
                'La descripcion contiene caracteres no permitidos.'
            );
        }

        // Uso de unidad: alfanumerico
        $uso = $vehiculo['uso_de_unidad'] ?? '';
        if (!empty($uso) && !preg_match('/^[a-zA-Z0-9\s\x{00C0}-\x{017F}\.,\-]+$/u', $uso)) {
            $validator->errors()->add(
                'vehiculo.uso_de_unidad',
                'El uso de la unidad contiene caracteres no permitidos.'
            );
        }

        // Modelo (año): rango válido 1970 - año actual + 1
        $modelo = $vehiculo['modelo'] ?? '';
        if (!empty($modelo) && preg_match('/^\d{4}$/', $modelo)) {
            $modeloNum = (int) $modelo;
            $maxYear = (int) date('Y') + 1;
            if ($modeloNum < 1970) {
                $validator->errors()->add(
                    'vehiculo.modelo',
                    'El modelo no puede ser menor a 1970.'
                );
            } elseif ($modeloNum > $maxYear) {
                $validator->errors()->add(
                    'vehiculo.modelo',
                    "El modelo no puede ser mayor a {$maxYear}."
                );
            }
        }
    }

    /**
     * Valida campos de renovacion (mirror de frontend)
     * LEGACY: formulario.js lineas 14245-14375
     */
    private function validateRenewalFields($validator): void
    {
        $tipoCotizacion = $this->input('tipo_cotizacion');
        $renovacion = $this->input('renovacion', []);

        if ($tipoCotizacion !== 'RENOVACION') {
            return;
        }

        // Compania actual: sin caracteres especiales peligrosos
        $compania = $renovacion['compania_actual'] ?? '';
        if (!empty($compania) && !preg_match('/^[a-zA-Z0-9\s\x{00C0}-\x{017F}\*\+\.,\-]+$/u', $compania)) {
            $validator->errors()->add(
                'renovacion.compania_actual',
                'El nombre de la compania contiene caracteres no permitidos.'
            );
        }

        // Prima del ano anterior: debe ser numero positivo
        $primaAno = $renovacion['prima_año'] ?? '';
        if (!empty($primaAno)) {
            $prima = $this->parseMoney($primaAno);
            if ($prima <= 0) {
                $validator->errors()->add(
                    'renovacion.prima_año',
                    'La prima del ano anterior debe ser un numero positivo.'
                );
            }
        }
    }

    /**
     * Verifica que cada aseguradora seleccionada tenga configuración financiera vigente.
     * Sin esta configuración, el backend no puede calcular desgloces financieros.
     */
    private function validateFinancialSettings($validator, int $n, array $coverages): void
    {
        for ($col = 1; $col <= $n; $col++) {
            $insurerId = $coverages["empresa_opcion_{$col}"] ?? null;

            if (!$insurerId || $insurerId === '0') {
                continue;
            }

            $insurerId = (int) $insurerId;

            $hasSettings = InsurerFinancialSetting::where('insurer_id', $insurerId)
                ->where('valid_from', '<=', now())
                ->where(function ($q) {
                    $q->whereNull('valid_until')
                        ->orWhere('valid_until', '>=', now());
                })
                ->exists();

            if (!$hasSettings) {
                $insurerName = Insurer::find($insurerId)?->name ?? "ID {$insurerId}";
                $validator->errors()->add(
                    "coverages.empresa_opcion_{$col}",
                    "La aseguradora \"{$insurerName}\" no tiene configuración financiera vigente (derecho de póliza y recargos). Configure los derechos de póliza y recargos antes de cotizar."
                );
            }
        }
    }

    /**
     * Parsea un string de moneda a número
     * "1,234.56" -> 1234.56
     */
    private function parseMoney(string $value): float
    {
        // Remover todo excepto dígitos y punto decimal
        $cleaned = preg_replace('/[^\d.]/', '', str_replace(',', '', $value));
        return (float) $cleaned;
    }
}
