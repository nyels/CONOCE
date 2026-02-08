<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreQuoteRequest;
use App\Models\Quote;
use App\Models\QuoteOption;
use App\Models\Customer;
use App\Models\Contact;
use App\Models\Insurer;
use App\Models\VehicleBrand;
use App\Models\VehicleType;
use App\Models\CoveragePackage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\Cache;
use Inertia\Inertia;
use Src\Domain\Quote\Enums\QuoteStatus;
use Src\Domain\Quote\Enums\QuoteType;
use Src\Domain\Quote\Enums\CoveragePackage as CoveragePackageEnum;
use Src\Domain\Financial\Contracts\FinancialCalculator;
use Src\Domain\Financial\DTOs\FinancialInput;
use Src\Domain\Financial\Exceptions\FinancialCalculationException;

class QuoteController extends Controller
{
    private FinancialCalculator $calculator;

    public function __construct(FinancialCalculator $calculator)
    {
        $this->calculator = $calculator;
    }

    /**
     * Lista de cotizaciones del usuario actual
     */
    public function index(Request $request)
    {
        $query = Quote::with(['customer', 'agent', 'options'])
            ->latest();

        // Filtrar por agente si no es admin
        if (!$request->user()->hasRole(['super_admin', 'admin'])) {
            $query->where('agent_id', $request->user()->id);
        }

        // Busqueda
        if ($search = $request->input('search')) {
            $query->search($search);
        }

        // Filtro por estado
        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        $quotes = $query->paginate(20)->through(fn($quote) => [
            'id' => $quote->id,
            'uuid' => $quote->uuid,
            'folio' => $quote->folio,
            'type' => $quote->type->value,
            'type_label' => $quote->type->label(),
            'status' => $quote->status->value,
            'status_label' => $quote->status->label(),
            'status_color' => $quote->status->color(),
            'customer_name' => $quote->customer?->full_name ?? 'Sin cliente',
            'vehicle' => $quote->vehicle_description,
            'options_count' => $quote->options_count,
            'created_at' => $quote->created_at->format('d/m/Y H:i'),
        ]);

        return Inertia::render('Quotes/Index', [
            'quotes' => $quotes,
            'filters' => $request->only(['search', 'status']),
            'statuses' => collect(QuoteStatus::cases())->map(fn($s) => [
                'value' => $s->value,
                'label' => $s->label(),
            ]),
        ]);
    }

    /**
     * Formulario de creacion de cotizacion (Formulario Legacy)
     */
    public function create(Request $request)
    {
        // Cargar clientes/prospectos activos
        $customers = Customer::active()
            ->select('id', 'type', 'first_name', 'paternal_surname', 'maternal_surname', 'business_name', 'phone', 'email', 'rfc', 'zip_code', 'neighborhood', 'state')
            ->withCount('quotes')
            ->orderByRaw("COALESCE(business_name, CONCAT(first_name, ' ', paternal_surname)) ASC")
            ->limit(100)
            ->get()
            ->map(fn($c) => [
                'id' => $c->id,
                'name' => $c->full_name,
                'phone' => $c->phone ?? '',
                'email' => $c->email ?? '',
                'rfc' => $c->rfc ?? '',
                'zip_code' => $c->zip_code ?? '',
                'neighborhood' => $c->neighborhood ?? '',
                'state' => $c->state ?? '',
                'quotes' => $c->quotes_count,
            ]);

        // Cargar contactos activos (agentes/intermediarios)
        $contacts = Contact::active()
            ->select('id', 'type', 'first_name', 'paternal_surname', 'maternal_surname', 'phone')
            ->orderByRaw("CONCAT(first_name, ' ', COALESCE(paternal_surname, '')) ASC")
            ->get()
            ->map(fn($c) => [
                'id' => $c->id,
                'name' => $c->full_name,
                'type' => $c->type->value ?? $c->type,
            ]);

        // Cargar tipos de vehiculo desde catalogo en BD
        $vehicleTypes = VehicleType::forSelect();

        // Cargar marcas de vehiculos
        $brands = VehicleBrand::where('is_active', true)
            ->orderBy('name')
            ->pluck('name')
            ->toArray();

        // Anos disponibles (ano actual + 1 hasta 15 anos atras)
        $years = array_map(fn($i) => date('Y') + 1 - $i, range(0, 14));

        // Cargar aseguradoras activas
        $insurers = Insurer::where('is_active', true)
            ->with('financialSettings')
            ->orderBy('name')
            ->get()
            ->map(fn($ins) => [
                'id' => $ins->id,
                'name' => $ins->name,
                'logo_url' => $ins->logo_url,
                'policy_fee' => ($ins->financialSettings->first()?->policy_fee_cents ?? 0) / 100,
            ]);

        // Cargar paquetes de cobertura
        $coveragePackages = CoveragePackage::where('is_active', true)
            ->orderBy('name')
            ->get()
            ->map(fn($pkg) => [
                'id' => $pkg->id,
                'code' => $pkg->code ?? strtolower($pkg->name),
                'name' => $pkg->name,
                'description' => $pkg->description ?? '',
            ]);

        return Inertia::render('Quotes/Create', [
            'customers' => $customers,
            'contacts' => $contacts,
            'vehicleTypes' => $vehicleTypes,
            'brands' => $brands,
            'years' => $years,
            'insurers' => $insurers,
            'coveragePackages' => $coveragePackages,
        ]);
    }

    /**
     * Buscar clientes (AJAX)
     * Rate limited: 60 requests por minuto por usuario
     */
    public function searchCustomers(Request $request)
    {
        $key = 'quote-search-customers:' . ($request->user()?->id ?? $request->ip());

        if (RateLimiter::tooManyAttempts($key, 60)) {
            return response()->json([
                'error' => 'Demasiadas solicitudes. Intente de nuevo en un momento.',
            ], 429);
        }

        RateLimiter::hit($key, 60);

        $term = $request->input('q', '');

        $customers = Customer::active()
            ->when($term, fn($q) => $q->search($term))
            ->select('id', 'type', 'first_name', 'paternal_surname', 'maternal_surname', 'business_name', 'phone', 'email', 'rfc')
            ->withCount('quotes')
            ->orderByRaw("COALESCE(business_name, CONCAT(first_name, ' ', paternal_surname)) ASC")
            ->limit(20)
            ->get()
            ->map(fn($c) => [
                'id' => $c->id,
                'name' => $c->full_name,
                'phone' => $c->phone ?? '',
                'email' => $c->email ?? '',
                'rfc' => $c->rfc ?? '',
                'quotes' => $c->quotes_count,
            ]);

        return response()->json($customers);
    }

    /**
     * GUARDAR COTIZACION — BACKEND AUTORITATIVO
     *
     * El backend es la UNICA fuente de verdad para calculos financieros.
     * Los valores monetarios del frontend son IGNORADOS y recalculados.
     *
     * Flujo:
     * 1. Frontend envia: prima_neta, total_anual, primer_pago
     * 2. Backend RECALCULA todo usando CanonicalFinancialService
     * 3. Si hay divergencia > tolerancia, se registra pero NO se rechaza
     * 4. Se persisten los valores calculados por el backend
     */
    public function store(StoreQuoteRequest $request)
    {
        $validated = $request->validated();

        return DB::transaction(function () use ($validated, $request) {
            // Mapear tipo de cotizacion
            $quoteType = $validated['tipo_cotizacion'] === 'NUEVA'
                ? QuoteType::NEW
                : QuoteType::RENEWAL;

            // Mapear paquete de cobertura
            $packageType = match ($validated['paquete']) {
                'AMPLIA' => CoveragePackageEnum::FULL,
                'LIMITADA' => CoveragePackageEnum::LIMITED,
                'RESPONSABILIDAD CIVIL' => CoveragePackageEnum::LIABILITY_ONLY,
                default => CoveragePackageEnum::FULL,
            };

            // Convertir prima anterior a centavos si existe
            $previousPremiumCents = null;
            if (!empty($validated['renovacion']['prima_año'])) {
                $primaLimpia = str_replace([',', '$'], '', $validated['renovacion']['prima_año']);
                $previousPremiumCents = (int)(floatval($primaLimpia) * 100);
            }

            // Crear cotizacion principal
            $quote = Quote::create([
                'customer_id' => $validated['customer_id'],
                'contact_id' => $validated['contact_id'] ?? null,
                'agent_id' => $request->user()->id,
                'type' => $quoteType,
                'status' => QuoteStatus::DRAFT,
                'requested_at' => $validated['hora_solicitada'] ?? null,
                'vehicle_type_id' => $validated['vehiculo']['tipo_auto'],
                'vehicle_data' => [
                    'brand' => $validated['vehiculo']['marca'],
                    'model' => $validated['vehiculo']['descripcion'] ?? '',
                    'year' => $validated['vehiculo']['modelo'],
                    'usage' => $validated['vehiculo']['uso_de_unidad'] ?? '',
                    'cargo_type' => $validated['vehiculo']['carga'] ?? null,
                ],
                'vehicle_type' => $this->getVehicleTypeName($validated['vehiculo']['tipo_auto']),
                'vehicle_usage' => $validated['vehiculo']['uso_de_unidad'] ?? null,
                'package_type' => $packageType,
                'coverage_description' => $validated['coverages']['descripcion_tabla_1'] ?? null,
                'custom_coverage_1_name' => $validated['custom_coverage_1_name'] ?? null,
                'custom_coverage_2_name' => $validated['custom_coverage_2_name'] ?? null,
                'previous_insurer' => $validated['renovacion']['compania_actual'] ?? null,
                'previous_policy_number' => $validated['renovacion']['poliza_a_renovar'] ?? null,
                'previous_premium_cents' => $previousPremiumCents,
                'previous_expiry_date' => $validated['renovacion']['fecha_vigencia'] ?? null,
                'quote_valid_until' => now()->addDays(7),
                'internal_notes' => $validated['notas'] ?? null,
            ]);

            // Crear opciones de cotizacion (una por cada aseguradora)
            $coverages = $validated['coverages'] ?? [];
            $cantidadAseguradoras = (int) $validated['cantidad_aseguradoras'];
            $paymentFrequencyLegacy = $coverages['forma_de_pago'] ?? 'ANUAL';
            $paymentFrequency = $this->mapPaymentFrequency($paymentFrequencyLegacy);

            for ($i = 1; $i <= $cantidadAseguradoras; $i++) {
                $insurerId = $coverages["empresa_opcion_{$i}"] ?? null;

                if (!$insurerId || $insurerId === '0') {
                    continue;
                }

                $insurerId = (int) $insurerId;

                // Extraer valores del frontend (seran RECALCULADOS)
                $frontendNetPremium = $this->parseMoneyToDecimal($coverages["cantidad_prima_neta_opcion_{$i}"] ?? '0');
                $frontendTotalAnnual = $this->parseMoneyToDecimal($coverages["cantidad_total_anual_opcion_{$i}"] ?? '0');
                $frontendFirstPayment = $this->parseMoneyToDecimal($coverages["primer_pago_opcion_{$i}"] ?? '0');
                $frontendSubsequent = $this->parseMoneyToDecimal($coverages["subsecuente_opcion_{$i}"] ?? '0');

                // BACKEND AUTORITATIVO: Recalcular desde prima total
                if (!$frontendTotalAnnual || $frontendTotalAnnual <= 0) {
                    continue;
                }

                try {
                    $financialInput = FinancialInput::fromTotalPremium(
                        insurerId: $insurerId,
                        frequency: $paymentFrequency,
                        totalAnnualPremium: $frontendTotalAnnual,
                        customFirstPayment: $frontendFirstPayment ?: null,
                    );

                    $calculation = $this->calculator->calculate($financialInput);
                } catch (FinancialCalculationException $e) {
                    // Registrar error para auditoria, saltar esta opción
                    activity()
                        ->performedOn($quote)
                        ->withProperties([
                            'column' => $i,
                            'insurer_id' => $insurerId,
                            'error' => $e->errorCode(),
                            'message' => $e->getMessage(),
                        ])
                        ->log('calculation_error');
                    continue;
                }

                // Validar coherencia (solo para logging, no rechazar)
                $validationErrors = $this->calculator->validate($calculation);
                if (!empty($validationErrors)) {
                    activity()
                        ->performedOn($quote)
                        ->withProperties([
                            'column' => $i,
                            'insurer_id' => $insurerId,
                            'frontend_values' => [
                                'net_premium' => $frontendNetPremium,
                                'total_annual' => $frontendTotalAnnual,
                                'first_payment' => $frontendFirstPayment,
                                'subsequent' => $frontendSubsequent,
                            ],
                            'backend_values' => $calculation->toArray(),
                            'validation_errors' => $validationErrors,
                        ])
                        ->log('calculation_divergence');
                }

                // Extraer datos de coberturas para esta columna
                $optionCoverages = $this->extractCoveragesForColumn($coverages, $i);

                // PERSISTIR VALORES DEL BACKEND (no del frontend)
                $cents = $calculation->toCents();

                QuoteOption::create([
                    'quote_id' => $quote->id,
                    'insurer_id' => $insurerId,
                    'option_number' => $i,
                    'coverage_package' => $packageType,
                    'payment_frequency' => $paymentFrequency,
                    'net_premium_cents' => $cents['net_premium_cents'],
                    'policy_fee_cents' => $cents['policy_fee_cents'],
                    'surcharge_cents' => $cents['surcharge_cents'],
                    'iva_cents' => $cents['iva_cents'],
                    'total_premium_cents' => $cents['total_premium_cents'],
                    'first_payment_cents' => $cents['first_payment_cents'],
                    'subsequent_payment_cents' => $cents['subsequent_payment_cents'],
                    'annual_net_premium_cents' => $cents['net_premium_cents'],
                    'annual_total_premium_cents' => $cents['total_premium_cents'],
                    'is_selected' => $i === 1,
                    'coverages' => $optionCoverages,

                    // Campos detallados de coberturas legacy
                    'material_damage_type' => $coverages["danos_opcion_selec_{$i}"] ?? null,
                    'material_damage_amount' => $this->parseMoneyToDecimal($coverages["danos_material_importe_factura_{$i}"] ?? null),
                    'material_damage_deductible' => $this->parseDeductible($coverages["deducible_opcion_{$i}"] ?? null),
                    'theft_type' => $coverages["robo_opcion_selec_{$i}"] ?? null,
                    'theft_amount' => $this->parseMoneyToDecimal($coverages["robo_importe_factura_{$i}"] ?? null),
                    'theft_deductible' => $this->parseDeductible($coverages["deducible_rt_{$i}"] ?? null),
                    'glass_coverage' => $coverages["cristales_opcion_selec_{$i}"] ?? 'AMPARADA',
                    'liability_third_party' => $this->parseMoneyToDecimal($coverages["danos_tercero_opcion_{$i}"] ?? null),
                    'liability_deductible' => intval($coverages["deducible_de_rc_opcion_{$i}"] ?? 0),
                    'liability_death' => $this->parseMoneyToDecimal($coverages["fallecimiento_opcion_{$i}"] ?? null),
                    'medical_expenses' => $this->parseMoneyToDecimal($coverages["gastos_medicos_opcion_{$i}"] ?? null),
                    'driver_accident' => $this->parseMoneyToDecimal($coverages["accidente_conducir_opcion_{$i}"] ?? null),
                    'legal_protection' => $coverages["proteccion_opcion_selec_{$i}"] ?? 'AMPARADA',
                    'roadside_assistance' => $coverages["asistencia_vial_opcion_selec_{$i}"] ?? 'AMPARADA',
                    'cargo_damage' => $coverages["danos_carga_opcion_selec_{$i}"] ?? null,
                    'special_equipment' => $this->parseMoneyToDecimal($coverages["adaptaciones_opcion_{$i}"] ?? null),
                    'extended_liability' => $coverages["extension_rc_opcion_{$i}"] ?? null,
                    'custom_coverage_1_value' => $coverages["cobertura_opcion_1_select_{$i}"] ?? null,
                    'custom_coverage_2_value' => $coverages["cobertura_opcion_2_select_{$i}"] ?? null,
                ]);
            }

            // RESPONSE LEGACY EXACTO: "1*{id_cotizacion}*{nombre_prospecto}-{descripcion_auto}"
            // Fuente: LEGACY_SYSTEM_FORENSIC_ANALYSIS.md
            $customer = Customer::find($validated['customer_id']);
            $customerName = $customer?->full_name ?? 'Sin nombre';
            $vehicleDesc = $validated['vehiculo']['descripcion'] ?? $validated['vehiculo']['marca'] ?? '';
            $legacyResponse = "1*{$quote->id}*{$customerName}-{$vehicleDesc}";

            // Si es una petición AJAX/API, retornar response legacy
            if (request()->expectsJson() || request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'legacy_response' => $legacyResponse,
                    'quote_id' => $quote->id,
                    'quote_uuid' => $quote->uuid,
                    'folio' => $quote->folio,
                ], 201);
            }

            return redirect()->route('quotes.show', $quote->id)
                ->with('success', "Cotizacion {$quote->folio} creada exitosamente");
        });
    }

    /**
     * STORE LEGACY — Response exacto como sistema legacy
     *
     * Response: "1*{id_cotizacion}*{nombre_prospecto}-{descripcion_auto}"
     *
     * Fuente de verdad: LEGACY_SYSTEM_FORENSIC_ANALYSIS.md
     */
    public function storeLegacy(StoreQuoteRequest $request)
    {
        $result = $this->store($request);

        // Si ya es JSON response, extraer datos
        if ($result instanceof \Illuminate\Http\JsonResponse) {
            $data = $result->getData(true);
            return response($data['legacy_response'] ?? '0*error', 201)
                ->header('Content-Type', 'text/plain');
        }

        return $result;
    }

    /**
     * ENDPOINT DE CALCULO EN TIEMPO REAL — BACKEND AUTORITATIVO
     *
     * El frontend llama a este endpoint cuando el usuario modifica valores.
     * El backend calcula y retorna los valores correctos.
     * El frontend SOLO renderiza, NO calcula.
     *
     * Rate limited: 60 requests por minuto por usuario
     */
    public function calculateRealtime(Request $request)
    {
        $key = 'quote-calculate:' . ($request->user()?->id ?? $request->ip());

        if (RateLimiter::tooManyAttempts($key, 60)) {
            return response()->json([
                'error' => 'Demasiadas solicitudes de calculo.',
                'retry_after' => RateLimiter::availableIn($key),
            ], 429);
        }

        RateLimiter::hit($key, 60);

        $validated = $request->validate([
            'insurer_id' => 'required|integer|exists:insurers,id',
            'frequency' => 'required|string|in:ANNUAL,SEMIANNUAL,QUARTERLY,MONTHLY,ANUAL,SEMESTRAL,TRIMESTRAL,MENSUAL',
            'total_annual' => 'nullable|numeric|min:0',
            'first_payment' => 'nullable|numeric|min:0',
            'request_id' => 'nullable|string|max:50', // Para control de race conditions
        ]);

        // Mapear frecuencia de espanol a ingles si es necesario
        $frequency = $this->mapPaymentFrequency($validated['frequency']);
        $insurerId = (int) $validated['insurer_id'];
        $totalAnnual = (float) ($validated['total_annual'] ?? 0);
        $firstPayment = isset($validated['first_payment']) ? (float) $validated['first_payment'] : null;

        // Si no hay total anual, retornar estructura vacia
        if ($totalAnnual <= 0) {
            return response()->json([
                'request_id' => $validated['request_id'] ?? null,
                'insurer_id' => $insurerId,
                'calculation' => null,
                'message' => 'No se proporciono prima total anual',
            ]);
        }

        try {
            $input = FinancialInput::fromTotalPremium(
                insurerId: $insurerId,
                frequency: $frequency,
                totalAnnualPremium: $totalAnnual,
                customFirstPayment: $firstPayment,
            );

            $calculation = $this->calculator->calculate($input);

            return response()->json([
                'request_id' => $validated['request_id'] ?? null,
                'insurer_id' => $insurerId,
                'calculation' => $calculation->toArray(),
                'formatted' => $calculation->toFormattedArray(),
            ]);
        } catch (FinancialCalculationException $e) {
            return response()->json([
                'request_id' => $validated['request_id'] ?? null,
                'insurer_id' => $insurerId,
                'error' => $e->errorCode(),
            ], 422);
        }
    }

    /**
     * ENDPOINT DE CALCULO BATCH — MULTIPLES COLUMNAS
     *
     * Calcula todas las columnas en una sola llamada.
     * Reduce race conditions y mejora performance.
     */
    public function calculateBatch(Request $request)
    {
        $key = 'quote-calculate-batch:' . ($request->user()?->id ?? $request->ip());

        if (RateLimiter::tooManyAttempts($key, 30)) {
            return response()->json([
                'error' => 'Demasiadas solicitudes.',
                'retry_after' => RateLimiter::availableIn($key),
            ], 429);
        }

        RateLimiter::hit($key, 60);

        $validated = $request->validate([
            'frequency' => 'required|string|in:ANNUAL,SEMIANNUAL,QUARTERLY,MONTHLY,ANUAL,SEMESTRAL,TRIMESTRAL,MENSUAL',
            'options' => 'required|array|min:1|max:5',
            'options.*.column' => 'required|integer|min:1|max:5',
            'options.*.insurer_id' => 'required|integer',
            'options.*.total_annual' => 'nullable|numeric|min:0',
            'options.*.first_payment' => 'nullable|numeric|min:0',
            'request_id' => 'nullable|string|max:50',
        ]);

        $frequency = $this->mapPaymentFrequency($validated['frequency']);

        $inputs = [];
        foreach ($validated['options'] as $opt) {
            $totalAnnual = (float) ($opt['total_annual'] ?? 0);
            if ($totalAnnual <= 0) {
                continue;
            }
            $inputs[$opt['column']] = FinancialInput::fromTotalPremium(
                insurerId: (int) $opt['insurer_id'],
                frequency: $frequency,
                totalAnnualPremium: $totalAnnual,
                customFirstPayment: isset($opt['first_payment']) ? (float) $opt['first_payment'] : null,
            );
        }

        $batchResults = $this->calculator->calculateBatch($inputs);

        $results = [];
        foreach ($batchResults as $column => $result) {
            if ($result instanceof FinancialCalculationException) {
                $results[$column] = ['error' => $result->errorCode()];
            } else {
                $results[$column] = $result->toArray();
            }
        }

        return response()->json([
            'request_id' => $validated['request_id'] ?? null,
            'frequency' => $frequency,
            'results' => $results,
        ]);
    }

    /**
     * Obtiene el nombre del tipo de vehiculo por ID
     */
    private function getVehicleTypeName(int $id): ?string
    {
        $type = VehicleType::find($id);
        return $type?->name;
    }

    /**
     * Convierte string de moneda a decimal
     * "1,000.50" -> 1000.50
     */
    private function parseMoneyToDecimal(?string $value): ?float
    {
        if (!$value) return null;
        $cleaned = str_replace([',', '$', ' '], '', $value);
        $result = floatval($cleaned);
        return $result > 0 ? $result : null;
    }

    /**
     * Parsea deducible a entero
     * "5" -> 5, "na" -> null
     */
    private function parseDeductible(?string $value): ?int
    {
        if (!$value || $value === 'na' || $value === '0') return null;
        return intval($value);
    }

    /**
     * Extrae las coberturas para una columna especifica como array JSON
     */
    private function extractCoveragesForColumn(array $coverages, int $column): array
    {
        return [
            'material_damage' => [
                'type' => $coverages["danos_opcion_selec_{$column}"] ?? null,
                'amount' => $coverages["danos_material_importe_factura_{$column}"] ?? null,
                'deductible' => $coverages["deducible_opcion_{$column}"] ?? null,
            ],
            'theft' => [
                'type' => $coverages["robo_opcion_selec_{$column}"] ?? null,
                'amount' => $coverages["robo_importe_factura_{$column}"] ?? null,
                'deductible' => $coverages["deducible_rt_{$column}"] ?? null,
            ],
            'glass' => $coverages["cristales_opcion_selec_{$column}"] ?? 'AMPARADA',
            'third_party_liability' => [
                'amount' => $coverages["danos_tercero_opcion_{$column}"] ?? null,
                'deductible' => $coverages["deducible_de_rc_opcion_{$column}"] ?? null,
            ],
            'death_liability' => $coverages["fallecimiento_opcion_{$column}"] ?? null,
            'medical_expenses' => $coverages["gastos_medicos_opcion_{$column}"] ?? null,
            'driver_accident' => $coverages["accidente_conducir_opcion_{$column}"] ?? null,
            'legal_protection' => $coverages["proteccion_opcion_selec_{$column}"] ?? 'AMPARADA',
            'roadside_assistance' => $coverages["asistencia_vial_opcion_selec_{$column}"] ?? 'AMPARADA',
            'cargo_damage' => $coverages["danos_carga_opcion_selec_{$column}"] ?? null,
            'special_equipment' => $coverages["adaptaciones_opcion_{$column}"] ?? null,
            'extended_liability' => $coverages["extension_rc_opcion_{$column}"] ?? null,
            'custom_1' => $coverages["cobertura_opcion_1_select_{$column}"] ?? null,
            'custom_2' => $coverages["cobertura_opcion_2_select_{$column}"] ?? null,
        ];
    }

    /**
     * Mapea forma de pago del frontend (espanol) al enum (ingles)
     * Legacy: ANUAL, SEMESTRAL, TRIMESTRAL, MENSUAL
     * Enum: ANNUAL, SEMIANNUAL, QUARTERLY, MONTHLY
     */
    private function mapPaymentFrequency(string $formaDePago): string
    {
        return match ($formaDePago) {
            'ANUAL' => 'ANNUAL',
            'SEMESTRAL' => 'SEMIANNUAL',
            'TRIMESTRAL' => 'QUARTERLY',
            'MENSUAL' => 'MONTHLY',
            default => $formaDePago,
        };
    }

    /**
     * Ver detalle de cotizacion
     */
    public function show(Quote $quote)
    {
        $quote->load(['customer', 'agent', 'contact', 'options.insurer']);

        return Inertia::render('Quotes/Show', [
            'quote' => [
                'id' => $quote->id,
                'uuid' => $quote->uuid,
                'folio' => $quote->folio,
                'type' => $quote->type->value,
                'type_label' => $quote->type->label(),
                'status' => $quote->status->value,
                'status_label' => $quote->status->label(),
                'status_color' => $quote->status->color(),
                'is_editable' => $quote->isEditable(),
                'customer' => $quote->customer ? [
                    'id' => $quote->customer->id,
                    'name' => $quote->customer->full_name,
                    'phone' => $quote->customer->phone,
                    'email' => $quote->customer->email,
                    'rfc' => $quote->customer->rfc,
                ] : null,
                'agent' => $quote->agent ? [
                    'id' => $quote->agent->id,
                    'name' => $quote->agent->name,
                ] : null,
                'vehicle' => $quote->vehicle_data,
                'vehicle_description' => $quote->vehicle_description,
                'package_type' => $quote->package_type?->value,
                'package_label' => $quote->package_type?->label(),
                'options' => $quote->options->map(fn($opt) => [
                    'id' => $opt->id,
                    'option_number' => $opt->option_number,
                    'insurer_name' => $opt->insurer?->name,
                    'insurer_logo' => $opt->insurer?->logo_url,
                    'net_premium' => $opt->net_premium_cents / 100,
                    'policy_fee' => $opt->policy_fee_cents / 100,
                    'total_premium' => $opt->total_premium_cents / 100,
                    'is_selected' => $opt->is_selected,
                ]),
                'quote_valid_until' => $quote->quote_valid_until?->format('d/m/Y'),
                'created_at' => $quote->created_at->format('d/m/Y H:i'),
                'notes' => $quote->internal_notes,
            ],
        ]);
    }

    /**
     * Actualizar cotizacion
     */
    public function update(Request $request, Quote $quote)
    {
        if (!$quote->isEditable()) {
            return back()->withErrors(['error' => 'Esta cotizacion ya no puede ser editada']);
        }

        $validated = $request->validate([
            'notes' => 'nullable|string|max:1000',
        ]);

        $quote->update([
            'internal_notes' => $validated['notes'],
        ]);

        return back()->with('success', 'Cotizacion actualizada');
    }

    /**
     * Enviar cotizacion al cliente
     */
    public function send(Quote $quote)
    {
        if (!$quote->canTransitionTo(QuoteStatus::SENT)) {
            return back()->withErrors(['error' => 'No se puede enviar esta cotizacion']);
        }

        $quote->transitionTo(QuoteStatus::SENT);

        return back()->with('success', "Cotizacion {$quote->folio} enviada");
    }

    /**
     * Eliminar cotizacion
     */
    public function destroy(Quote $quote)
    {
        if (!$quote->isEditable()) {
            return back()->withErrors(['error' => 'Esta cotizacion no puede ser eliminada']);
        }

        $folio = $quote->folio;
        $quote->delete();

        return redirect()->route('quotes.index')
            ->with('success', "Cotizacion {$folio} eliminada");
    }

    /**
     * Calcular primas para aseguradoras (AJAX)
     * Rate limited: 30 requests por minuto por usuario
     */
    public function calculatePremiums(Request $request)
    {
        $key = 'calculate-premiums:' . ($request->user()?->id ?? $request->ip());

        if (RateLimiter::tooManyAttempts($key, 30)) {
            return response()->json([
                'error' => 'Demasiadas solicitudes de calculo. Intente de nuevo en un momento.',
            ], 429);
        }

        RateLimiter::hit($key, 60);

        $validated = $request->validate([
            'vehicle_value' => 'required|numeric|min:0',
            'package' => 'required|string|in:basic,standard,premium,full,limited,liability_only',
            'insurer_ids' => 'required|array|min:1',
            'insurer_ids.*' => 'exists:insurers,id',
        ]);

        $vehicleValue = (float) $validated['vehicle_value'];
        $package = $validated['package'];

        $baseRates = [
            'basic' => 0.025,
            'standard' => 0.035,
            'premium' => 0.045,
            'liability_only' => 0.025,
            'limited' => 0.035,
            'full' => 0.045,
        ];

        $baseRate = $baseRates[$package] ?? 0.035;
        $insurers = Insurer::with('financialSettings')
            ->whereIn('id', $validated['insurer_ids'])
            ->where('is_active', true)
            ->get();

        $results = [];
        $minTotal = PHP_INT_MAX;
        $bestId = null;

        foreach ($insurers as $insurer) {
            $variation = 1 + (($insurer->id % 5) - 2) * 0.03;
            $netPremium = round($vehicleValue * $baseRate * $variation);
            $policyFee = $insurer->financialSettings?->policy_fee_cents / 100 ?? 850;
            $total = $netPremium + $policyFee;

            $results[] = [
                'id' => $insurer->id,
                'name' => $insurer->name,
                'logo_url' => $insurer->logo_url,
                'netPremium' => $netPremium,
                'fee' => $policyFee,
                'total' => $total,
                'selected' => false,
                'best' => false,
            ];

            if ($total < $minTotal) {
                $minTotal = $total;
                $bestId = $insurer->id;
            }
        }

        foreach ($results as &$r) {
            if ($r['id'] === $bestId) {
                $r['best'] = true;
            }
        }

        return response()->json($results);
    }

    /**
     * Generar PDF de cotizacion
     */
    public function generatePdf(Quote $quote)
    {
        $quote->load(['customer', 'agent', 'options.insurer']);

        if (!class_exists(\Barryvdh\DomPDF\Facade\Pdf::class)) {
            return response()->json([
                'error' => 'El generador de PDF no esta instalado. Ejecute: composer require barryvdh/laravel-dompdf'
            ], 500);
        }

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.quote', [
            'quote' => $quote,
            'customer' => $quote->customer,
            'options' => $quote->options,
        ]);

        return $pdf->download("cotizacion-{$quote->folio}.pdf");
    }

    /**
     * Vista previa del PDF (en navegador)
     */
    public function previewPdf(Quote $quote)
    {
        $quote->load(['customer', 'agent', 'options.insurer']);

        if (!class_exists(\Barryvdh\DomPDF\Facade\Pdf::class)) {
            return back()->withErrors(['server' => 'El generador de PDF no esta instalado']);
        }

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.quote', [
            'quote' => $quote,
            'customer' => $quote->customer,
            'options' => $quote->options,
        ]);

        return $pdf->stream("cotizacion-{$quote->folio}.pdf");
    }

    /**
     * Vista previa del PDF (BORRADOR - sin guardar)
     */
    public function previewDraft(Request $request)
    {
        $validated = $request->validate([
            'customer_name' => 'nullable|string|max:255',
            'vehicle' => 'required|array',
            'vehicle.brand' => 'required|string',
            'vehicle.model' => 'nullable|string',
            'vehicle.year' => 'required|integer',
            'options' => 'required|array|min:1',
            'options.*.insurer_name' => 'required|string',
            'options.*.insurer_id' => 'required|integer',
            'options.*.coverage_package' => 'nullable|string',
            'options.*.payment_frequency' => 'nullable|string',
            'options.*.net_premium' => 'required|numeric',
            'options.*.policy_fee' => 'required|numeric',
            'options.*.iva' => 'required|numeric',
            'options.*.total' => 'required|numeric',
        ]);

        if (!class_exists(\Barryvdh\DomPDF\Facade\Pdf::class)) {
            return response()->json([
                'error' => 'El generador de PDF no esta instalado'
            ], 500);
        }

        $quote = (object) [
            'folio' => 'BORRADOR-' . date('YmdHis'),
            'created_at' => now(),
            'vehicle_data' => $validated['vehicle'],
            'options' => collect($validated['options'])->map(function ($opt) {
                return (object) [
                    'insurer' => (object) [
                        'name' => $opt['insurer_name'],
                        'logo_path' => null,
                    ],
                    'coverage_package' => $opt['coverage_package'] ?? 'full',
                    'payment_frequency' => $opt['payment_frequency'] ?? 'ANNUAL',
                    'net_premium' => $opt['net_premium'],
                    'policy_fee' => $opt['policy_fee'],
                    'tax' => $opt['iva'],
                    'total_premium' => $opt['total'],
                    'first_payment' => $opt['total'],
                    'coverages' => [],
                ];
            }),
        ];

        $customer = (object) [
            'name' => $validated['customer_name'] ?? 'Cliente Prospecto',
            'zip_code' => '',
            'city' => '',
        ];

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.quote', [
            'quote' => $quote,
            'customer' => $customer,
            'options' => $quote->options,
            'isDraft' => true,
        ]);

        return $pdf->stream("borrador-cotizacion.pdf");
    }

    /**
     * Obtener configuracion financiera de una aseguradora (AJAX)
     */
    public function getFinancialSettings(Request $request, int $insurerId)
    {
        // Cache por 5 minutos para reducir queries
        $cacheKey = "insurer_financial_settings:{$insurerId}";

        $data = Cache::remember($cacheKey, 300, function () use ($insurerId) {
            $insurer = Insurer::with(['financialSettings' => function ($q) {
                $q->where(function ($query) {
                    $query->whereNull('valid_until')
                        ->orWhere('valid_until', '>=', now());
                })->where('valid_from', '<=', now())
                  ->latest('valid_from');
            }])->find($insurerId);

            if (!$insurer) {
                return null;
            }

            $settings = $insurer->financialSettings->first();

            return [
                'insurer_id' => $insurerId,
                'insurer_name' => $insurer->name,
                'policy_fee' => $settings ? $settings->policy_fee_cents / 100 : 0,
                'surcharges' => $settings ? [
                    'SEMIANNUAL' => ($settings->surcharge_semiannual ?? 0) * 100,
                    'QUARTERLY' => ($settings->surcharge_quarterly ?? 0) * 100,
                    'MONTHLY' => ($settings->surcharge_monthly ?? 0) * 100,
                ] : [
                    'SEMIANNUAL' => 0,
                    'QUARTERLY' => 0,
                    'MONTHLY' => 0,
                ],
            ];
        });

        if (!$data) {
            return response()->json(['error' => 'Aseguradora no encontrada'], 404);
        }

        return response()->json($data);
    }

}
