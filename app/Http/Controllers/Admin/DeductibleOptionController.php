<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DeductibleOption;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DeductibleOptionController extends Controller
{
    public function index()
    {
        $options = DeductibleOption::ordered()
            ->get()
            ->map(fn($opt) => [
                'id' => $opt->id,
                'name' => $opt->name,
                'percentage' => $opt->percentage,
                'is_active' => $opt->is_active,
            ])
            ->values();

        return Inertia::render('Admin/DeductibleOptions/Index', [
            'options' => $options,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'min:1',
                'max:50',
                'regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑüÜ0-9\s\-\.\%]+$/',
            ],
            'percentage' => 'required|numeric|min:0|max:100',
            'is_active' => 'boolean',
        ], [
            'name.regex' => 'El nombre solo puede contener letras, números, espacios, guiones, puntos y %.',
        ]);

        try {
            DeductibleOption::create([
                'name' => $validated['name'],
                'percentage' => $validated['percentage'],
                'is_active' => $validated['is_active'] ?? true,
                'sort_order' => DeductibleOption::max('sort_order') + 1,
            ]);

            return back()->with('success', 'Opción de deducible creada exitosamente');
        } catch (\Exception $e) {
            return back()->withErrors(['server' => 'Error al crear la opción de deducible. Intente nuevamente.']);
        }
    }

    public function update(Request $request, DeductibleOption $deductibleOption)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'min:1',
                'max:50',
                'regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑüÜ0-9\s\-\.\%]+$/',
            ],
            'percentage' => 'required|numeric|min:0|max:100',
            'is_active' => 'boolean',
        ], [
            'name.regex' => 'El nombre solo puede contener letras, números, espacios, guiones, puntos y %.',
        ]);

        try {
            $deductibleOption->update([
                'name' => $validated['name'],
                'percentage' => $validated['percentage'],
                'is_active' => $validated['is_active'] ?? true,
            ]);

            return back()->with('success', 'Opción de deducible actualizada exitosamente');
        } catch (\Exception $e) {
            return back()->withErrors(['server' => 'Error al actualizar la opción de deducible.']);
        }
    }

    public function destroy(DeductibleOption $deductibleOption)
    {
        try {
            $deductibleOption->delete();
            return back()->with('success', 'Opción de deducible eliminada exitosamente');
        } catch (\Exception $e) {
            return back()->withErrors(['server' => 'Error al eliminar la opción de deducible.']);
        }
    }
}
