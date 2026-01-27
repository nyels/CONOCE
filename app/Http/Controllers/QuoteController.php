<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreQuoteRequest;
use App\Models\Quote;
use App\Models\QuoteOption;
use App\Models\Customer;
use App\Models\Insurer;
use App\Models\VehicleBrand;
use App\Models\CoveragePackage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\RateLimiter;
use Inertia\Inertia;
use Src\Domain\Quote\Enums\QuoteStatus;
use Src\Domain\Quote\Enums\QuoteType;
use Src\Domain\Quote\Enums\CoveragePackage as CoveragePackageEnum;

class QuoteController extends Controller
{
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

        // Búsqueda
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
            'customer_name' => $quote->customer?->name ?? 'Sin cliente',
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
     * Formulario de creación de cotización (Wizard)
     */
    public function create(Request $request)
    {
        // Cargar datos reales para el wizard
        $customers = Customer::active()
            ->select('id', 'name', 'phone', 'email', 'rfc')
            ->withCount('quotes')
            ->orderBy('name')
            ->limit(50)
            ->get()
            ->map(fn($c) => [
                'id' => $c->id,
                'name' => $c->name,
                'phone' => $c->phone ?? '',
                'email' => $c->email ?? '',
                'rfc' => $c->rfc ?? '',
                'quotes' => $c->quotes_count,
            ]);

        $brands = VehicleBrand::where('is_active', true)
            ->orderBy('name')
            ->pluck('name')
            ->toArray();

        $years = array_map(fn($i) => date('Y') + 1 - $i, range(0, 14));

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
            ->select('id', 'name', 'phone', 'email', 'rfc')
            ->withCount('quotes')
            ->orderBy('name')
            ->limit(20)
            ->get()
            ->map(fn($c) => [
                'id' => $c->id,
                'name' => $c->name,
                'phone' => $c->phone ?? '',
                'email' => $c->email ?? '',
                'rfc' => $c->rfc ?? '',
                'quotes' => $c->quotes_count,
            ]);

        return response()->json($customers);
    }

    /**
     * Guardar nueva cotización
     */
    public function store(StoreQuoteRequest $request)
    {
        $validated = $request->validated();

        return DB::transaction(function () use ($validated, $request) {
            // Crear cliente si es nuevo
            $customerId = $validated['customer_id'];
            if (!$customerId && !empty($validated['new_customer']['name'])) {
                $customer = Customer::create([
                    'name' => $validated['new_customer']['name'],
                    'phone' => $validated['new_customer']['phone'] ?? null,
                    'email' => $validated['new_customer']['email'] ?? null,
                    'rfc' => $validated['new_customer']['rfc'] ?? null,
                    'type' => strtoupper($validated['new_customer']['type'] ?? 'PHYSICAL'),
                    'created_by' => $request->user()->id,
                    'is_active' => true,
                ]);
                $customerId = $customer->id;
            }

            // Crear cotización
            $quote = Quote::create([
                'customer_id' => $customerId,
                'agent_id' => $request->user()->id,
                'type' => $validated['quote_type'] === 'new' ? QuoteType::NEW : QuoteType::RENEWAL,
                'status' => QuoteStatus::DRAFT,
                'vehicle_data' => $validated['vehicle'],
                'package_type' => match ($validated['coverage_package']) {
                    'basic', 'liability_only' => CoveragePackageEnum::LIABILITY_ONLY,
                    'standard', 'limited' => CoveragePackageEnum::LIMITED,
                    'premium', 'full' => CoveragePackageEnum::FULL,
                    default => CoveragePackageEnum::FULL,
                },
                'previous_insurer' => $validated['renewal']['insurer'] ?? null,
                'previous_policy_number' => $validated['renewal']['policy_number'] ?? null,
                'previous_premium_cents' => isset($validated['renewal']['previous_premium'])
                    ? (int)($validated['renewal']['previous_premium'] * 100)
                    : null,
                'previous_expiry_date' => $validated['renewal']['expires_at'] ?? null,
                'quote_valid_until' => now()->addDays($validated['validity_days'] ?? 7),
                'internal_notes' => $validated['notes'] ?? null,
            ]);

            // Crear opciones de cotización
            $optionNumber = 1;
            // Crear opciones de cotización (Captura Manual)
            $optionNumber = 1;
            foreach ($validated['options'] as $optionData) {

                // Mapear paquete individual
                $pkgCode = $optionData['coverage_package'] ?? 'full';
                $pkgEnum = match ($pkgCode) {
                    'basic', 'liability_only' => CoveragePackageEnum::LIABILITY_ONLY,
                    'standard', 'limited' => CoveragePackageEnum::LIMITED,
                    'premium', 'full' => CoveragePackageEnum::FULL,
                    default => CoveragePackageEnum::FULL,
                };

                QuoteOption::create([
                    'quote_id' => $quote->id,
                    'insurer_id' => $optionData['insurer_id'],
                    'option_number' => $optionNumber++,
                    'coverage_package' => $pkgEnum,
                    'payment_frequency' => $optionData['payment_frequency'] ?? 'ANNUAL',
                    'net_premium_cents' => (int)($optionData['net_premium'] * 100),
                    'policy_fee_cents' => (int)($optionData['policy_fee'] * 100),
                    'surcharge_cents' => 0,
                    'iva_cents' => (int)(($optionData['iva'] ?? 0) * 100),
                    'total_premium_cents' => (int)($optionData['total_premium'] * 100),
                    'first_payment_cents' => (int)($optionData['total_premium'] * 100), // Asumimos pago inicial = total por ahora
                    'subsequent_payment_cents' => 0,
                    'is_selected' => true, // Todas las agregadas manualmente son relevantes
                    'coverages' => [],
                ]);
            }

            return redirect()->route('quotes.show', $quote->id)
                ->with('success', "Cotización {$quote->folio} creada exitosamente");
        });
    }

    /**
     * Ver detalle de cotización
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
                    'name' => $quote->customer->name,
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
     * Actualizar cotización
     */
    public function update(Request $request, Quote $quote)
    {
        if (!$quote->isEditable()) {
            return back()->with('error', 'Esta cotización ya no puede ser editada');
        }

        // TODO: Implementar actualización completa
        $validated = $request->validate([
            'notes' => 'nullable|string|max:1000',
        ]);

        $quote->update([
            'internal_notes' => $validated['notes'],
        ]);

        return back()->with('success', 'Cotización actualizada');
    }

    /**
     * Enviar cotización al cliente
     */
    public function send(Quote $quote)
    {
        if (!$quote->canTransitionTo(QuoteStatus::SENT)) {
            return back()->with('error', 'No se puede enviar esta cotización');
        }

        $quote->transitionTo(QuoteStatus::SENT);

        // TODO: Enviar email/WhatsApp

        return back()->with('success', "Cotización {$quote->folio} enviada");
    }

    /**
     * Eliminar cotización
     */
    public function destroy(Quote $quote)
    {
        if (!$quote->isEditable()) {
            return back()->with('error', 'Esta cotización no puede ser eliminada');
        }

        $folio = $quote->folio;
        $quote->delete();

        return redirect()->route('quotes.index')
            ->with('success', "Cotización {$folio} eliminada");
    }

    /**
     * Calcular primas para aseguradoras (AJAX)
     * Usado en el paso 4 del wizard
     * Rate limited: 30 requests por minuto por usuario
     */
    public function calculatePremiums(Request $request)
    {
        $key = 'calculate-premiums:' . ($request->user()?->id ?? $request->ip());

        if (RateLimiter::tooManyAttempts($key, 30)) {
            return response()->json([
                'error' => 'Demasiadas solicitudes de cálculo. Intente de nuevo en un momento.',
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

        // Tasas base simuladas (en producción vendrían de catálogo o API externa)
        $baseRates = [
            'basic' => 0.025,      // 2.5% del valor
            'standard' => 0.035,   // 3.5% del valor
            'premium' => 0.045,    // 4.5% del valor
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
            // Variación por aseguradora (simulado)
            $variation = 1 + (($insurer->id % 5) - 2) * 0.03; // -6% a +6%
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

        // Marcar la mejor opción
        foreach ($results as &$r) {
            if ($r['id'] === $bestId) {
                $r['best'] = true;
            }
        }

        return response()->json($results);
    }

    /**
     * Generar PDF de cotización
     */
    public function generatePdf(Quote $quote)
    {
        $quote->load(['customer', 'agent', 'options.insurer']);

        // Verificar si DomPDF está disponible
        if (!class_exists(\Barryvdh\DomPDF\Facade\Pdf::class)) {
            return response()->json([
                'error' => 'El generador de PDF no está instalado. Ejecute: composer require barryvdh/laravel-dompdf'
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
            return back()->with('error', 'El generador de PDF no está instalado');
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
     * Recibe datos del formulario y genera un PDF temporal
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
                'error' => 'El generador de PDF no está instalado'
            ], 500);
        }

        // Crear objeto "fake" para la plantilla
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
            'isDraft' => true, // Marca para mostrar watermark BORRADOR
        ]);

        return $pdf->stream("borrador-cotizacion.pdf");
    }
}
