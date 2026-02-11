<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePositionRequest;
use App\Http\Requests\UpdatePositionRequest;
use App\Models\Position;
use Inertia\Inertia;

class PositionController extends Controller
{
    /**
     * Lista de puestos
     */
    public function index()
    {
        $positions = Position::withCount('staff')
            ->ordered()
            ->get()
            ->map(fn($position) => [
                'id' => $position->id,
                'name' => $position->name,
                'description' => $position->description,
                'staff_count' => $position->staff_count,
                'is_active' => $position->is_active,
                'sort_order' => $position->sort_order,
            ]);

        return Inertia::render('Admin/Positions/Index', [
            'positions' => $positions,
        ]);
    }

    /**
     * Guardar nuevo puesto
     */
    public function store(StorePositionRequest $request)
    {
        try {
            Position::create($request->validated());

            return back()->with('success', 'Puesto creado exitosamente');
        } catch (\Exception $e) {
            return back()->withErrors(['server' => 'Error al crear el puesto. Intente nuevamente.']);
        }
    }

    /**
     * Actualizar puesto
     */
    public function update(UpdatePositionRequest $request, Position $position)
    {
        try {
            $position->update($request->validated());

            return back()->with('success', 'Puesto actualizado exitosamente');
        } catch (\Exception $e) {
            return back()->withErrors(['server' => 'Error al actualizar el puesto.']);
        }
    }

    /**
     * Eliminar puesto
     */
    public function destroy(Position $position)
    {
        // Verificar que no tenga personal asociado
        if ($position->staff()->exists()) {
            return back()->withErrors(['error' => 'No se puede eliminar un puesto con personal asociado']);
        }

        try {
            $position->delete();

            return back()->with('success', 'Puesto eliminado exitosamente');
        } catch (\Exception $e) {
            return back()->withErrors(['server' => 'Error al eliminar el puesto.']);
        }
    }
}
