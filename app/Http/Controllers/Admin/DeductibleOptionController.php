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
            'name' => 'required|string|max:50',
            'percentage' => 'required|numeric|min:0|max:100',
            'is_active' => 'boolean',
        ]);

        DeductibleOption::create([
            'name' => $validated['name'],
            'percentage' => $validated['percentage'],
            'is_active' => $validated['is_active'] ?? true,
            'sort_order' => DeductibleOption::max('sort_order') + 1,
        ]);

        return back()->with('success', 'Opción de deducible creada exitosamente');
    }

    public function update(Request $request, DeductibleOption $deductibleOption)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:50',
            'percentage' => 'required|numeric|min:0|max:100',
            'is_active' => 'boolean',
        ]);

        $deductibleOption->update([
            'name' => $validated['name'],
            'percentage' => $validated['percentage'],
            'is_active' => $validated['is_active'] ?? true,
        ]);

        return back()->with('success', 'Opción de deducible actualizada exitosamente');
    }

    public function destroy(DeductibleOption $deductibleOption)
    {
        $deductibleOption->delete();
        return back()->with('success', 'Opción de deducible eliminada exitosamente');
    }
}
