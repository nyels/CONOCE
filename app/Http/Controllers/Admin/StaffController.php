<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreStaffRequest;
use App\Http\Requests\UpdateStaffRequest;
use App\Models\Position;
use App\Models\Staff;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

/**
 * Controlador de Personal
 * Implementa seguridad, trazabilidad y performance
 */
class StaffController extends Controller
{
    /**
     * Lista de personal con filtros y paginación optimizada
     */
    public function index(Request $request)
    {
        $query = Staff::with(['position:id,name', 'primaryEmail', 'emails'])
            ->withCount('emails');

        // Busqueda
        if ($search = $request->input('search')) {
            $query->search($search);
        }

        // Filtro por puesto
        if ($positionId = $request->input('position')) {
            $query->byPosition((int) $positionId);
        }

        // Filtro por estado
        if ($request->has('active')) {
            $query->where('is_active', $request->boolean('active'));
        }

        // Ordenamiento
        $sortField = $request->input('sort', 'paternal_surname');
        $sortDir = $request->input('dir', 'asc');
        $allowedSorts = ['employee_number', 'paternal_surname', 'position_id', 'hire_date', 'created_at'];

        if (in_array($sortField, $allowedSorts)) {
            $query->orderBy($sortField, $sortDir === 'desc' ? 'desc' : 'asc');
        } else {
            $query->orderBy('paternal_surname')->orderBy('first_name');
        }

        $staff = $query->paginate(20)->through(fn(Staff $s) => [
            'id' => $s->id,
            'uuid' => $s->uuid,
            'employee_number' => $s->employee_number,
            'first_name' => $s->first_name,
            'paternal_surname' => $s->paternal_surname,
            'maternal_surname' => $s->maternal_surname,
            'full_name' => $s->full_name,
            'birth_date' => $s->birth_date?->format('d/m/Y'),
            'curp' => $s->curp,
            'rfc' => $s->rfc,
            'position' => $s->position?->name,
            'position_id' => $s->position_id,
            'primary_email' => $s->primaryEmail?->email,
            'emails' => $s->emails->map(fn($e) => [
                'email' => $e->email,
                'type' => $e->type,
                'is_primary' => $e->is_primary,
            ]),
            'phone' => $s->phone,
            'mobile' => $s->mobile,
            'hire_date' => $s->hire_date?->format('d/m/Y'),
            'termination_date' => $s->termination_date?->format('d/m/Y'),
            'notes' => $s->notes,
            'is_active' => $s->is_active,
            'has_user' => $s->user()->exists(),
            'created_at' => $s->created_at->format('d/m/Y'),
        ]);

        return Inertia::render('Admin/Staff/Index', [
            'staff' => $staff,
            'filters' => $request->only(['search', 'position', 'active', 'sort', 'dir']),
            'positions' => Position::active()
                ->ordered()
                ->select('id', 'name')
                ->get()
                ->map(fn($p) => ['value' => $p->id, 'label' => $p->name]),
        ]);
    }

    /**
     * Guardar nuevo personal con transacción
     */
    public function store(StoreStaffRequest $request)
    {
        try {
            DB::beginTransaction();

            $validated = $request->validated();

            // Crear staff - asegurar que strings vacíos sean null para campos con índice unique
            $staff = Staff::create([
                'first_name' => $validated['first_name'],
                'paternal_surname' => $validated['paternal_surname'],
                'maternal_surname' => $validated['maternal_surname'] ?? null,
                'birth_date' => $validated['birth_date'] ?? null,
                'curp' => !empty($validated['curp']) ? $validated['curp'] : null,
                'rfc' => !empty($validated['rfc']) ? $validated['rfc'] : null,
                'phone' => $validated['phone'] ?? null,
                'mobile' => $validated['mobile'] ?? null,
                'position_id' => $validated['position_id'],
                'hire_date' => $validated['hire_date'] ?? null,
                'termination_date' => $validated['termination_date'] ?? null,
                'notes' => $validated['notes'] ?? null,
            ]);

            // Sincronizar emails
            $staff->syncEmails($validated['emails']);

            DB::commit();

            Log::info('Personal creado', [
                'staff_id' => $staff->id,
                'employee_number' => $staff->employee_number,
                'created_by' => auth()->id(),
            ]);

            return back()->with('success', "Personal {$staff->full_name} creado exitosamente. No. Empleado: {$staff->employee_number}");
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Error al crear personal', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'user_id' => auth()->id(),
            ]);

            return back()->withErrors(['server' => 'Error al crear el personal. Por favor intente nuevamente.']);
        }
    }

    /**
     * Mostrar detalle de personal
     */
    public function show(Staff $staff)
    {
        $staff->load(['position', 'emails', 'user', 'createdBy:id,name', 'updatedBy:id,name']);

        return Inertia::render('Admin/Staff/Show', [
            'staff' => [
                'id' => $staff->id,
                'uuid' => $staff->uuid,
                'employee_number' => $staff->employee_number,
                'first_name' => $staff->first_name,
                'paternal_surname' => $staff->paternal_surname,
                'maternal_surname' => $staff->maternal_surname,
                'full_name' => $staff->full_name,
                'birth_date' => $staff->birth_date?->format('d/m/Y'),
                'age' => $staff->age,
                'curp' => $staff->curp,
                'rfc' => $staff->rfc,
                'phone' => $staff->phone,
                'mobile' => $staff->mobile,
                'position' => $staff->position ? [
                    'id' => $staff->position->id,
                    'name' => $staff->position->name,
                ] : null,
                'hire_date' => $staff->hire_date?->format('d/m/Y'),
                'termination_date' => $staff->termination_date?->format('d/m/Y'),
                'seniority_years' => $staff->seniority_years,
                'emails' => $staff->emails->map(fn($e) => [
                    'email' => $e->email,
                    'type' => $e->type,
                    'is_primary' => $e->is_primary,
                ]),
                'has_user' => $staff->hasUserAccount(),
                'user' => $staff->user ? [
                    'id' => $staff->user->id,
                    'name' => $staff->user->name,
                ] : null,
                'notes' => $staff->notes,
                'is_active' => $staff->is_active,
                'created_by' => $staff->createdBy?->name,
                'updated_by' => $staff->updatedBy?->name,
                'created_at' => $staff->created_at->format('d/m/Y H:i'),
                'updated_at' => $staff->updated_at->format('d/m/Y H:i'),
            ],
        ]);
    }

    /**
     * Actualizar personal con transacción
     */
    public function update(UpdateStaffRequest $request, Staff $staff)
    {
        try {
            DB::beginTransaction();

            $validated = $request->validated();

            $staff->update([
                'first_name' => $validated['first_name'],
                'paternal_surname' => $validated['paternal_surname'],
                'maternal_surname' => $validated['maternal_surname'] ?? null,
                'birth_date' => $validated['birth_date'] ?? null,
                'curp' => !empty($validated['curp']) ? $validated['curp'] : null,
                'rfc' => !empty($validated['rfc']) ? $validated['rfc'] : null,
                'phone' => $validated['phone'] ?? null,
                'mobile' => $validated['mobile'] ?? null,
                'position_id' => $validated['position_id'],
                'hire_date' => $validated['hire_date'] ?? null,
                'termination_date' => $validated['termination_date'] ?? null,
                'is_active' => $validated['is_active'] ?? true,
                'notes' => $validated['notes'] ?? null,
            ]);

            // Sincronizar emails
            $staff->syncEmails($validated['emails']);

            DB::commit();

            Log::info('Personal actualizado', [
                'staff_id' => $staff->id,
                'updated_by' => auth()->id(),
            ]);

            return back()->with('success', 'Personal actualizado exitosamente');
        } catch (\Exception $e) {
            DB::rollBack();

            Log::error('Error al actualizar personal', [
                'staff_id' => $staff->id,
                'error' => $e->getMessage(),
                'user_id' => auth()->id(),
            ]);

            return back()->withErrors(['server' => 'Error al actualizar el personal. Por favor intente nuevamente.']);
        }
    }

    /**
     * Eliminar personal (soft delete)
     */
    public function destroy(Staff $staff)
    {
        // No permitir eliminar si tiene usuario asociado
        if ($staff->hasUserAccount()) {
            return back()->withErrors(['error' => 'No se puede eliminar personal que tiene una cuenta de usuario asociada.']);
        }

        try {
            $fullName = $staff->full_name;
            $employeeNumber = $staff->employee_number;

            $staff->delete();

            Log::info('Personal eliminado', [
                'staff_id' => $staff->id,
                'employee_number' => $employeeNumber,
                'deleted_by' => auth()->id(),
            ]);

            return back()->with('success', "Personal {$fullName} eliminado exitosamente");
        } catch (\Exception $e) {
            Log::error('Error al eliminar personal', [
                'staff_id' => $staff->id,
                'error' => $e->getMessage(),
                'user_id' => auth()->id(),
            ]);

            return back()->withErrors(['server' => 'Error al eliminar el personal.']);
        }
    }

    /**
     * Toggle estado activo/inactivo
     */
    public function toggleActive(Staff $staff)
    {
        $staff->update([
            'is_active' => !$staff->is_active,
        ]);

        $status = $staff->is_active ? 'activado' : 'desactivado';

        Log::info('Estado de personal cambiado', [
            'staff_id' => $staff->id,
            'new_status' => $staff->is_active,
            'changed_by' => auth()->id(),
        ]);

        return back()->with('success', "Personal {$status} exitosamente");
    }

    /**
     * Obtener datos de personal para edición (JSON)
     */
    public function edit(Staff $staff)
    {
        $staff->load(['position', 'emails']);

        return response()->json([
            'id' => $staff->id,
            'first_name' => $staff->first_name,
            'paternal_surname' => $staff->paternal_surname,
            'maternal_surname' => $staff->maternal_surname,
            'birth_date' => $staff->birth_date?->format('Y-m-d'),
            'curp' => $staff->curp,
            'rfc' => $staff->rfc,
            'phone' => $staff->phone,
            'mobile' => $staff->mobile,
            'position' => $staff->position ? [
                'id' => $staff->position->id,
                'name' => $staff->position->name,
            ] : null,
            'hire_date' => $staff->hire_date?->format('Y-m-d'),
            'termination_date' => $staff->termination_date?->format('Y-m-d'),
            'emails' => $staff->emails->map(fn($e) => [
                'email' => $e->email,
                'type' => $e->type,
                'is_primary' => $e->is_primary,
            ]),
            'notes' => $staff->notes,
            'is_active' => $staff->is_active,
        ]);
    }

    /**
     * Busqueda AJAX para selectores
     */
    public function search(Request $request)
    {
        $term = $request->input('q', '');

        $staff = Staff::active()
            ->with('primaryEmail')
            ->when($term, fn($q) => $q->search($term))
            ->select('id', 'employee_number', 'first_name', 'paternal_surname', 'maternal_surname')
            ->orderBy('paternal_surname')
            ->limit(20)
            ->get()
            ->map(fn(Staff $s) => [
                'id' => $s->id,
                'name' => $s->full_name,
                'employee_number' => $s->employee_number,
                'email' => $s->primaryEmail?->email,
            ]);

        return response()->json($staff);
    }
}
