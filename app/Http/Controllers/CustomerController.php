<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCustomerRequest;
use App\Http\Requests\UpdateCustomerRequest;
use App\Models\Customer;
use App\Models\Contact;
use App\Models\MexicanState;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;
use Inertia\Inertia;
use Src\Domain\Customer\Enums\CustomerType;

class CustomerController extends Controller
{
    /**
     * Lista de clientes
     */
    public function index(Request $request)
    {
        $query = Customer::with(['contact', 'createdBy'])
            ->withCount('quotes')
            ->latest();

        // Busqueda
        if ($search = $request->input('search')) {
            $query->search($search);
        }

        // Filtro por tipo
        if ($type = $request->input('type')) {
            $query->where('type', $type);
        }

        // Filtro por estado
        if ($request->has('active')) {
            $query->where('is_active', $request->boolean('active'));
        }

        $customers = $query->paginate(20)->through(fn($customer) => [
            'id' => $customer->id,
            'uuid' => $customer->uuid,
            'name' => $customer->full_name,
            'first_name' => $customer->first_name,
            'paternal_surname' => $customer->paternal_surname,
            'maternal_surname' => $customer->maternal_surname,
            'business_name' => $customer->business_name,
            'type' => strtolower($customer->type->value), // Convertir a minúsculas para frontend
            'type_label' => $customer->type->label(),
            'rfc' => $customer->rfc,
            'email' => $customer->email,
            'phone' => $customer->phone,
            'mobile' => $customer->mobile,
            'neighborhood' => $customer->neighborhood,
            'city' => $customer->city,
            'state' => $customer->state,
            'zip_code' => $customer->zip_code,
            'quotes_count' => $customer->quotes_count,
            'is_active' => $customer->is_active,
            'created_at' => $customer->created_at->format('d/m/Y'),
        ]);

        return Inertia::render('Customers/Index', [
            'customers' => $customers,
            'filters' => $request->only(['search', 'type', 'active']),
            'types' => collect(CustomerType::cases())->map(fn($t) => [
                'value' => strtolower($t->value),
                'label' => $t->label(),
            ]),
            'mexicanStates' => MexicanState::forSelect(),
        ]);
    }

    /**
     * Formulario de creacion
     */
    public function create()
    {
        $contacts = Contact::active()
            ->orderBy('paternal_surname')
            ->orderBy('first_name')
            ->get()
            ->map(fn($c) => [
                'id' => $c->id,
                'name' => $c->full_name,
                'type' => $c->type->value ?? $c->type,
            ]);

        return Inertia::render('Customers/Create', [
            'contacts' => $contacts,
            'types' => collect(CustomerType::cases())->map(fn($t) => [
                'value' => strtolower($t->value),
                'label' => $t->label(),
            ]),
            'mexicanStates' => MexicanState::forSelect(),
        ]);
    }

    /**
     * Guardar nuevo cliente
     */
    public function store(StoreCustomerRequest $request)
    {
        try {
            DB::beginTransaction();

            $validated = $request->validated();

            $customer = Customer::create([
                ...$validated,
                'type' => $validated['type'] === 'physical' ? CustomerType::PHYSICAL : CustomerType::MORAL,
                'created_by' => $request->user()->id,
                'is_active' => true,
            ]);

            DB::commit();

            Log::info('Cliente creado', [
                'customer_id' => $customer->id,
                'type' => $customer->type->value,
                'created_by' => $request->user()->id,
            ]);

            return redirect()->route('customers.show', $customer->id)
                ->with('success', 'Cliente creado exitosamente');
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Error al crear cliente', [
                'error' => $e->getMessage(),
                'user_id' => $request->user()->id,
            ]);

            return back()->withErrors(['server' => 'Error al crear el cliente. Intente nuevamente.']);
        }
    }

    /**
     * Ver detalle de cliente
     */
    public function show(Customer $customer)
    {
        $customer->load(['contact', 'createdBy', 'quotes' => fn($q) => $q->latest()->limit(10)]);

        return Inertia::render('Customers/Show', [
            'customer' => [
                'id' => $customer->id,
                'uuid' => $customer->uuid,
                'type' => strtolower($customer->type->value),
                'type_label' => $customer->type->label(),
                'name' => $customer->full_name,
                'first_name' => $customer->first_name,
                'paternal_surname' => $customer->paternal_surname,
                'maternal_surname' => $customer->maternal_surname,
                'business_name' => $customer->business_name,
                'rfc' => $customer->rfc,
                'curp' => $customer->curp,
                'email' => $customer->email,
                'phone' => $customer->phone,
                'mobile' => $customer->mobile,
                'neighborhood' => $customer->neighborhood,
                'city' => $customer->city,
                'state' => $customer->state,
                'zip_code' => $customer->zip_code,
                'address' => $customer->formatted_address,
                'legal_representative' => $customer->legal_representative,
                'legal_representative_rfc' => $customer->legal_representative_rfc,
                'source' => $customer->source,
                'contact' => $customer->contact ? [
                    'id' => $customer->contact->id,
                    'name' => $customer->contact->full_name,
                ] : null,
                'created_by' => $customer->createdBy?->name,
                'is_active' => $customer->is_active,
                'notes' => $customer->notes,
                'created_at' => $customer->created_at->format('d/m/Y H:i'),
                'quotes' => $customer->quotes->map(fn($q) => [
                    'id' => $q->id,
                    'folio' => $q->folio,
                    'status' => $q->status->value,
                    'status_label' => $q->status->label(),
                    'vehicle' => $q->vehicle_description,
                    'created_at' => $q->created_at->format('d/m/Y'),
                ]),
            ],
        ]);
    }

    /**
     * Formulario de edicion
     */
    public function edit(Customer $customer)
    {
        $contacts = Contact::active()
            ->orderBy('paternal_surname')
            ->orderBy('first_name')
            ->get()
            ->map(fn($c) => [
                'id' => $c->id,
                'name' => $c->full_name,
                'type' => $c->type->value ?? $c->type,
            ]);

        return Inertia::render('Customers/Edit', [
            'customer' => [
                'id' => $customer->id,
                'type' => strtolower($customer->type->value),
                'first_name' => $customer->first_name,
                'paternal_surname' => $customer->paternal_surname,
                'maternal_surname' => $customer->maternal_surname,
                'business_name' => $customer->business_name,
                'rfc' => $customer->rfc,
                'curp' => $customer->curp,
                'email' => $customer->email,
                'phone' => $customer->phone,
                'mobile' => $customer->mobile,
                'street' => $customer->street,
                'exterior_number' => $customer->exterior_number,
                'interior_number' => $customer->interior_number,
                'neighborhood' => $customer->neighborhood,
                'zip_code' => $customer->zip_code,
                'city' => $customer->city,
                'municipality' => $customer->municipality,
                'state' => $customer->state,
                'legal_representative' => $customer->legal_representative,
                'legal_representative_rfc' => $customer->legal_representative_rfc,
                'source' => $customer->source,
                'contact_id' => $customer->contact_id,
                'notes' => $customer->notes,
                'is_active' => $customer->is_active,
            ],
            'contacts' => $contacts,
            'types' => collect(CustomerType::cases())->map(fn($t) => [
                'value' => strtolower($t->value),
                'label' => $t->label(),
            ]),
            'mexicanStates' => MexicanState::forSelect(),
        ]);
    }

    /**
     * Actualizar cliente
     */
    public function update(UpdateCustomerRequest $request, Customer $customer)
    {
        try {
            DB::beginTransaction();

            $validated = $request->validated();

            $customer->update([
                ...$validated,
                'type' => $validated['type'] === 'physical' ? CustomerType::PHYSICAL : CustomerType::MORAL,
            ]);

            DB::commit();

            Log::info('Cliente actualizado', [
                'customer_id' => $customer->id,
                'updated_by' => $request->user()->id,
            ]);

            return redirect()->route('customers.show', $customer->id)
                ->with('success', 'Cliente actualizado exitosamente');
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Error al actualizar cliente', [
                'customer_id' => $customer->id,
                'error' => $e->getMessage(),
            ]);

            return back()->withErrors(['server' => 'Error al actualizar el cliente.']);
        }
    }

    /**
     * Eliminar cliente
     */
    public function destroy(Customer $customer)
    {
        // Verificar que no tenga cotizaciones
        if ($customer->quotes()->exists()) {
            return back()->withErrors(['error' => 'No se puede eliminar un cliente con cotizaciones']);
        }

        try {
            $customerName = $customer->full_name;
            $customer->delete();

            Log::info('Cliente eliminado', [
                'customer_id' => $customer->id,
                'deleted_by' => auth()->id(),
            ]);

            return redirect()->route('customers.index')
                ->with('success', "Cliente {$customerName} eliminado exitosamente");
        } catch (\Exception $e) {
            Log::error('Error al eliminar cliente', [
                'customer_id' => $customer->id,
                'error' => $e->getMessage(),
            ]);

            return back()->withErrors(['server' => 'Error al eliminar el cliente.']);
        }
    }

    /**
     * Busqueda AJAX para selectores
     * Rate limited: 60 requests por minuto por usuario
     */
    public function search(Request $request)
    {
        $key = 'customer-search:' . ($request->user()?->id ?? $request->ip());

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
            ->orderBy('paternal_surname')
            ->orderBy('first_name')
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
}
