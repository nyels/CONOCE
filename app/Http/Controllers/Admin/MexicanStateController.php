<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MexicanState;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class MexicanStateController extends Controller
{
    public function index()
    {
        $states = MexicanState::ordered()
            ->get()
            ->map(fn($state) => [
                'id' => $state->id,
                'name' => $state->name,
                'is_active' => $state->is_active,
            ])
            ->values();

        return Inertia::render('Admin/MexicanStates/Index', [
            'states' => $states,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'min:3',
                'max:100',
                'regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑüÜ\s\.\-]+$/',
                'unique:mexican_states,name',
            ],
            'is_active' => 'boolean',
        ], [
            'name.required' => 'El nombre del estado es obligatorio.',
            'name.min' => 'El nombre debe tener al menos 3 caracteres.',
            'name.max' => 'El nombre no puede exceder 100 caracteres.',
            'name.regex' => 'El nombre solo puede contener letras, espacios, puntos y guiones.',
            'name.unique' => 'Este estado ya existe.',
        ]);

        try {
            MexicanState::create([
                'name' => $validated['name'],
                'is_active' => $validated['is_active'] ?? true,
                'sort_order' => MexicanState::max('sort_order') + 1,
            ]);

            return back()->with('success', 'Estado creado exitosamente');
        } catch (\Exception $e) {
            Log::error('Error al crear estado', ['error' => $e->getMessage()]);
            return back()->withErrors(['server' => 'Error al crear el estado. Intente nuevamente.']);
        }
    }

    public function update(Request $request, MexicanState $mexicanState)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'min:3',
                'max:100',
                'regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑüÜ\s\.\-]+$/',
                'unique:mexican_states,name,' . $mexicanState->id,
            ],
            'is_active' => 'boolean',
        ], [
            'name.required' => 'El nombre del estado es obligatorio.',
            'name.min' => 'El nombre debe tener al menos 3 caracteres.',
            'name.max' => 'El nombre no puede exceder 100 caracteres.',
            'name.regex' => 'El nombre solo puede contener letras, espacios, puntos y guiones.',
            'name.unique' => 'Este estado ya existe.',
        ]);

        try {
            $mexicanState->update([
                'name' => $validated['name'],
                'is_active' => $validated['is_active'] ?? true,
            ]);

            return back()->with('success', 'Estado actualizado exitosamente');
        } catch (\Exception $e) {
            Log::error('Error al actualizar estado', ['error' => $e->getMessage(), 'id' => $mexicanState->id]);
            return back()->withErrors(['server' => 'Error al actualizar el estado.']);
        }
    }

    public function destroy(MexicanState $mexicanState)
    {
        try {
            $mexicanState->delete();
            return back()->with('success', 'Estado eliminado exitosamente');
        } catch (\Exception $e) {
            Log::error('Error al eliminar estado', ['error' => $e->getMessage(), 'id' => $mexicanState->id]);
            return back()->withErrors(['server' => 'Error al eliminar el estado.']);
        }
    }
}
