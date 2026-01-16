<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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

        // Búsqueda
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
            'name' => $customer->name,
            'type' => $customer->type->value,
            'type_label' => $customer->type->label(),
            'rfc' => $customer->rfc,
            'email' => $customer->email,
            'phone' => $customer->phone,
            'city' => $customer->city,
            'state' => $customer->state,
            'quotes_count' => $customer->quotes_count,
            'is_active' => $customer->is_active,
            'created_at' => $customer->created_at->format('d/m/Y'),
        ]);

        return Inertia::render('Customers/Index', [
            'customers' => $customers,
            'filters' => $request->only(['search', 'type', 'active']),
            'types' => collect(CustomerType::cases())->map(fn($t) => [
                'value' => $t->value,
                'label' => $t->label(),
            ]),
        ]);
    }

    /**
     * Formulario de creación
     */
    public function create()
    {
        $contacts = Contact::active()
            ->select('id', 'name', 'type')
            ->orderBy('name')
            ->get()
            ->map(fn($c) => [
                'id' => $c->id,
                'name' => $c->name,
                'type' => $c->type->value ?? $c->type,
            ]);

        return Inertia::render('Customers/Create', [
            'contacts' => $contacts,
            'types' => collect(CustomerType::cases())->map(fn($t) => [
                'value' => $t->value,
                'label' => $t->label(),
            ]),
        ]);
    }

    /**
     * Guardar nuevo cliente
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'type' => 'required|string|in:physical,moral',
            'name' => 'required|string|max:255',
            'rfc' => 'nullable|string|max:13',
            'curp' => 'nullable|string|max:18',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'mobile' => 'nullable|string|max:20',
            'street' => 'nullable|string|max:255',
            'exterior_number' => 'nullable|string|max:20',
            'interior_number' => 'nullable|string|max:20',
            'neighborhood' => 'nullable|string|max:100',
            'zip_code' => 'nullable|string|max:10',
            'city' => 'nullable|string|max:100',
            'municipality' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'legal_representative' => 'nullable|string|max:255',
            'legal_representative_rfc' => 'nullable|string|max:13',
            'source' => 'nullable|string|max:100',
            'contact_id' => 'nullable|exists:contacts,id',
            'notes' => 'nullable|string|max:1000',
        ]);

        $customer = Customer::create([
            ...$validated,
            'type' => $validated['type'] === 'physical' ? CustomerType::PHYSICAL : CustomerType::MORAL,
            'created_by' => $request->user()->id,
            'is_active' => true,
        ]);

        return redirect()->route('customers.show', $customer->id)
            ->with('success', 'Cliente creado exitosamente');
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
                'type' => $customer->type->value,
                'type_label' => $customer->type->label(),
                'name' => $customer->name,
                'rfc' => $customer->rfc,
                'curp' => $customer->curp,
                'email' => $customer->email,
                'phone' => $customer->phone,
                'mobile' => $customer->mobile,
                'address' => $customer->formatted_address,
                'legal_representative' => $customer->legal_representative,
                'legal_representative_rfc' => $customer->legal_representative_rfc,
                'source' => $customer->source,
                'contact' => $customer->contact ? [
                    'id' => $customer->contact->id,
                    'name' => $customer->contact->name,
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
     * Formulario de edición
     */
    public function edit(Customer $customer)
    {
        $contacts = Contact::active()
            ->select('id', 'name', 'type')
            ->orderBy('name')
            ->get()
            ->map(fn($c) => [
                'id' => $c->id,
                'name' => $c->name,
                'type' => $c->type->value ?? $c->type,
            ]);

        return Inertia::render('Customers/Edit', [
            'customer' => [
                'id' => $customer->id,
                'type' => $customer->type->value,
                'name' => $customer->name,
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
                'value' => $t->value,
                'label' => $t->label(),
            ]),
        ]);
    }

    /**
     * Actualizar cliente
     */
    public function update(Request $request, Customer $customer)
    {
        $validated = $request->validate([
            'type' => 'required|string|in:physical,moral',
            'name' => 'required|string|max:255',
            'rfc' => 'nullable|string|max:13',
            'curp' => 'nullable|string|max:18',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'mobile' => 'nullable|string|max:20',
            'street' => 'nullable|string|max:255',
            'exterior_number' => 'nullable|string|max:20',
            'interior_number' => 'nullable|string|max:20',
            'neighborhood' => 'nullable|string|max:100',
            'zip_code' => 'nullable|string|max:10',
            'city' => 'nullable|string|max:100',
            'municipality' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'legal_representative' => 'nullable|string|max:255',
            'legal_representative_rfc' => 'nullable|string|max:13',
            'source' => 'nullable|string|max:100',
            'contact_id' => 'nullable|exists:contacts,id',
            'notes' => 'nullable|string|max:1000',
            'is_active' => 'boolean',
        ]);

        $customer->update([
            ...$validated,
            'type' => $validated['type'] === 'physical' ? CustomerType::PHYSICAL : CustomerType::MORAL,
        ]);

        return redirect()->route('customers.show', $customer->id)
            ->with('success', 'Cliente actualizado exitosamente');
    }

    /**
     * Eliminar cliente
     */
    public function destroy(Customer $customer)
    {
        // Verificar que no tenga cotizaciones
        if ($customer->quotes()->exists()) {
            return back()->with('error', 'No se puede eliminar un cliente con cotizaciones');
        }

        $customer->delete();

        return redirect()->route('customers.index')
            ->with('success', 'Cliente eliminado exitosamente');
    }

    /**
     * Búsqueda AJAX para selectores
     */
    public function search(Request $request)
    {
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
}
