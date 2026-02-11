<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreContactRequest;
use App\Http\Requests\UpdateContactRequest;
use App\Models\Contact;
use App\Models\ContactType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;
use Inertia\Inertia;

/**
 * Controlador para gestion de Contactos/Intermediarios
 * CRUD completo con trazabilidad y seguridad
 */
class ContactController extends Controller
{
    /**
     * Lista de contactos/intermediarios
     */
    public function index(Request $request)
    {
        $query = Contact::with(['parentAgent', 'createdBy', 'contactType'])
            ->withCount(['customers', 'quotes'])
            ->latest();

        // Busqueda
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                    ->orWhere('paternal_surname', 'like', "%{$search}%")
                    ->orWhere('maternal_surname', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%")
                    ->orWhere('cnsf_license', 'like', "%{$search}%");
            });
        }

        // Filtro por tipo
        if ($type = $request->input('type')) {
            $query->where('contact_type_id', $type);
        }

        // Filtro por estado
        if ($request->has('active')) {
            $query->where('is_active', $request->boolean('active'));
        }

        $contacts = $query->paginate(20)->through(fn($contact) => [
            'id' => $contact->id,
            'uuid' => $contact->uuid,
            'name' => $contact->full_name,
            'first_name' => $contact->first_name,
            'paternal_surname' => $contact->paternal_surname,
            'maternal_surname' => $contact->maternal_surname,
            'type' => $contact->contact_type_id,
            'type_label' => $contact->contactType?->name ?? $contact->type->label(),
            'email' => $contact->email,
            'phone' => $contact->phone,
            'mobile' => $contact->mobile,
            'notes' => $contact->notes,
            'parent_agent' => $contact->parentAgent?->full_name,
            'customers_count' => $contact->customers_count,
            'quotes_count' => $contact->quotes_count,
            'is_active' => $contact->is_active,
            'created_at' => $contact->created_at->format('d/m/Y'),
        ]);

        return Inertia::render('Contacts/Index', [
            'contacts' => $contacts,
            'filters' => $request->only(['search', 'type', 'active']),
            'types' => ContactType::active()->ordered()->get()->map(fn($t) => [
                'value' => $t->id,
                'label' => $t->name,
            ]),
        ]);
    }

    /**
     * Formulario de creacion
     */
    public function create()
    {
        // Obtener agentes activos para selector de agente padre (subagentes)
        $agents = Contact::active()
            ->agents()
            ->orderBy('paternal_surname')
            ->orderBy('first_name')
            ->get()
            ->map(fn($a) => [
                'id' => $a->id,
                'name' => $a->full_name,
            ]);

        return Inertia::render('Contacts/Create', [
            'agents' => $agents,
            'types' => ContactType::active()->ordered()->get()->map(fn($t) => [
                'value' => $t->id,
                'label' => $t->name,
            ]),
        ]);
    }

    /**
     * Guardar nuevo contacto
     */
    public function store(StoreContactRequest $request)
    {
        try {
            DB::beginTransaction();

            $validated = $request->validated();

            // Mapear a valor de enum para el campo type (compatibilidad)
            $enumType = 'DIRECT';

            $contact = Contact::create([
                'first_name' => $validated['first_name'],
                'paternal_surname' => $validated['paternal_surname'],
                'maternal_surname' => $validated['maternal_surname'] ?? null,
                'type' => $enumType,
                'contact_type_id' => $validated['type'],
                'email' => $validated['email'] ?? null,
                'phone' => $validated['phone'] ?? null,
                'mobile' => $validated['mobile'] ?? null,
                'cnsf_license' => $validated['cnsf_license'] ?? null,
                'license_expiry' => $validated['license_expiry'] ?? null,
                'commission_rate' => $validated['commission_rate'] ?? 0,
                'parent_agent_id' => $validated['parent_agent_id'] ?? null,
                'notes' => $validated['notes'] ?? null,
                'created_by' => $request->user()->id,
                'is_active' => true,
            ]);

            DB::commit();

            Log::info('Contacto creado', [
                'contact_id' => $contact->id,
                'created_by' => $request->user()->id,
            ]);

            return redirect()->route('contacts.index')
                ->with('success', 'Contacto creado exitosamente');
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Error al crear contacto', [
                'error' => $e->getMessage(),
                'user_id' => $request->user()->id,
            ]);

            return back()->withErrors(['server' => 'Error al crear el contacto. Intente nuevamente.']);
        }
    }

    /**
     * Ver detalle de contacto
     */
    public function show(Contact $contact)
    {
        $contact->load([
            'parentAgent',
            'subAgents' => fn($q) => $q->active(),
            'createdBy',
            'customers' => fn($q) => $q->active()->latest()->limit(10),
            'quotes' => fn($q) => $q->latest()->limit(10),
        ]);

        return Inertia::render('Contacts/Show', [
            'contact' => [
                'id' => $contact->id,
                'uuid' => $contact->uuid,
                'name' => $contact->full_name,
                'first_name' => $contact->first_name,
                'paternal_surname' => $contact->paternal_surname,
                'maternal_surname' => $contact->maternal_surname,
                'type' => $contact->type->value,
                'type_label' => $contact->type->label(),
                'type_color' => $contact->type->color(),
                'email' => $contact->email,
                'phone' => $contact->phone,
                'mobile' => $contact->mobile,
                'cnsf_license' => $contact->cnsf_license,
                'license_expiry' => $contact->license_expiry?->format('d/m/Y'),
                'license_valid' => $contact->hasValidLicense(),
                'commission_rate' => $contact->commission_rate
                    ? number_format($contact->commission_rate * 100, 2)
                    : null,
                'parent_agent' => $contact->parentAgent ? [
                    'id' => $contact->parentAgent->id,
                    'name' => $contact->parentAgent->full_name,
                ] : null,
                'sub_agents' => $contact->subAgents->map(fn($s) => [
                    'id' => $s->id,
                    'name' => $s->full_name,
                    'is_active' => $s->is_active,
                ]),
                'customers' => $contact->customers->map(fn($c) => [
                    'id' => $c->id,
                    'name' => $c->full_name,
                    'phone' => $c->phone,
                ]),
                'quotes' => $contact->quotes->map(fn($q) => [
                    'id' => $q->id,
                    'folio' => $q->folio,
                    'status' => $q->status->value,
                    'status_label' => $q->status->label(),
                    'created_at' => $q->created_at->format('d/m/Y'),
                ]),
                'notes' => $contact->notes,
                'created_by' => $contact->createdBy?->name,
                'is_active' => $contact->is_active,
                'created_at' => $contact->created_at->format('d/m/Y H:i'),
            ],
        ]);
    }

    /**
     * Formulario de edicion
     */
    public function edit(Contact $contact)
    {
        // Obtener agentes activos (excluyendo al contacto actual si es agente)
        $agents = Contact::active()
            ->agents()
            ->where('id', '!=', $contact->id)
            ->orderBy('paternal_surname')
            ->orderBy('first_name')
            ->get()
            ->map(fn($a) => [
                'id' => $a->id,
                'name' => $a->full_name,
            ]);

        return Inertia::render('Contacts/Edit', [
            'contact' => [
                'id' => $contact->id,
                'first_name' => $contact->first_name,
                'paternal_surname' => $contact->paternal_surname,
                'maternal_surname' => $contact->maternal_surname,
                'type' => $contact->contact_type_id,
                'email' => $contact->email,
                'phone' => $contact->phone,
                'mobile' => $contact->mobile,
                'cnsf_license' => $contact->cnsf_license,
                'license_expiry' => $contact->license_expiry?->format('Y-m-d'),
                'commission_rate' => $contact->commission_rate
                    ? $contact->commission_rate * 100
                    : null,
                'parent_agent_id' => $contact->parent_agent_id,
                'notes' => $contact->notes,
                'is_active' => $contact->is_active,
            ],
            'agents' => $agents,
            'types' => ContactType::active()->ordered()->get()->map(fn($t) => [
                'value' => $t->id,
                'label' => $t->name,
            ]),
        ]);
    }

    /**
     * Actualizar contacto
     */
    public function update(UpdateContactRequest $request, Contact $contact)
    {
        try {
            DB::beginTransaction();

            $validated = $request->validated();

            $contact->update([
                'first_name' => $validated['first_name'],
                'paternal_surname' => $validated['paternal_surname'],
                'maternal_surname' => $validated['maternal_surname'] ?? null,
                'contact_type_id' => $validated['type'],
                'email' => $validated['email'] ?? null,
                'phone' => $validated['phone'] ?? null,
                'mobile' => $validated['mobile'] ?? null,
                'cnsf_license' => $validated['cnsf_license'] ?? null,
                'license_expiry' => $validated['license_expiry'] ?? null,
                'commission_rate' => $validated['commission_rate'] ?? $contact->commission_rate ?? 0,
                'parent_agent_id' => $validated['parent_agent_id'] ?? null,
                'notes' => $validated['notes'] ?? null,
                'is_active' => $validated['is_active'] ?? true,
            ]);

            DB::commit();

            Log::info('Contacto actualizado', [
                'contact_id' => $contact->id,
                'updated_by' => $request->user()->id,
            ]);

            return redirect()->route('contacts.index')
                ->with('success', 'Contacto actualizado exitosamente');
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Error al actualizar contacto', [
                'contact_id' => $contact->id,
                'error' => $e->getMessage(),
            ]);

            return back()->withErrors(['server' => 'Error al actualizar el contacto.']);
        }
    }

    /**
     * Eliminar contacto (soft delete)
     */
    public function destroy(Contact $contact)
    {
        // Verificar que no tenga clientes o cotizaciones
        if ($contact->customers()->exists()) {
            return back()->withErrors(['error' => 'No se puede eliminar un contacto con clientes asociados']);
        }

        if ($contact->quotes()->exists()) {
            return back()->withErrors(['error' => 'No se puede eliminar un contacto con cotizaciones asociadas']);
        }

        // Verificar que no tenga subagentes
        if ($contact->subAgents()->exists()) {
            return back()->withErrors(['error' => 'No se puede eliminar un agente con subagentes asociados']);
        }

        try {
            $contactName = $contact->full_name;
            $contact->delete();

            Log::info('Contacto eliminado', [
                'contact_id' => $contact->id,
                'deleted_by' => auth()->id(),
            ]);

            return redirect()->route('contacts.index')
                ->with('success', "Contacto {$contactName} eliminado exitosamente");
        } catch (\Exception $e) {
            Log::error('Error al eliminar contacto', [
                'contact_id' => $contact->id,
                'error' => $e->getMessage(),
            ]);

            return back()->withErrors(['server' => 'Error al eliminar el contacto.']);
        }
    }

    /**
     * Busqueda AJAX para selectores
     * Rate limited: 60 requests por minuto por usuario
     */
    public function search(Request $request)
    {
        $key = 'contact-search:' . ($request->user()?->id ?? $request->ip());

        if (RateLimiter::tooManyAttempts($key, 60)) {
            return response()->json([
                'error' => 'Demasiadas solicitudes. Intente de nuevo en un momento.',
            ], 429);
        }

        RateLimiter::hit($key, 60);

        $term = $request->input('q', '');
        $type = $request->input('type');

        $query = Contact::active()
            ->when($term, function ($q) use ($term) {
                $q->where(function ($query) use ($term) {
                    $query->where('first_name', 'like', "%{$term}%")
                        ->orWhere('paternal_surname', 'like', "%{$term}%")
                        ->orWhere('maternal_surname', 'like', "%{$term}%")
                        ->orWhere('email', 'like', "%{$term}%")
                        ->orWhere('phone', 'like', "%{$term}%");
                });
            })
            ->when($type, fn($q) => $q->where('type', $type))
            ->select('id', 'first_name', 'paternal_surname', 'maternal_surname', 'type', 'phone')
            ->orderBy('paternal_surname')
            ->orderBy('first_name')
            ->limit(20)
            ->get()
            ->map(fn($c) => [
                'id' => $c->id,
                'name' => $c->full_name,
                'type' => $c->type->value,
                'type_label' => $c->type->label(),
                'phone' => $c->phone ?? '',
            ]);

        return response()->json($query);
    }

    /**
     * Toggle estado activo/inactivo
     */
    public function toggleActive(Contact $contact)
    {
        $contact->update([
            'is_active' => !$contact->is_active,
        ]);

        $status = $contact->is_active ? 'activado' : 'desactivado';

        Log::info('Estado de contacto cambiado', [
            'contact_id' => $contact->id,
            'new_status' => $contact->is_active,
            'changed_by' => auth()->id(),
        ]);

        return back()->with('success', "Contacto {$status} exitosamente");
    }
}
