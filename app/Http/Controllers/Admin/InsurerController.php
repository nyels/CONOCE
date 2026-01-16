<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Insurer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class InsurerController extends Controller
{
    /**
     * Display a listing of insurers.
     */
    public function index()
    {
        $insurers = Insurer::ordered()
            ->get()
            ->map(fn($insurer) => [
                'id' => $insurer->id,
                'name' => $insurer->name,
                'short_name' => $insurer->short_name,
                'code' => $insurer->code,
                'logo_url' => $insurer->logo_url,
                'primary_color' => $insurer->primary_color,
                'is_active' => $insurer->is_active,
                'sort_order' => $insurer->sort_order,
            ])
            ->values();

        return Inertia::render('Admin/Insurers/Index', [
            'insurers' => $insurers,
        ]);
    }

    /**
     * Store a newly created insurer.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:insurers,name',
            'short_name' => 'nullable|string|max:20',
            'code' => 'nullable|string|max:10|unique:insurers,code',
            'primary_color' => 'nullable|string|max:7',
            'logo' => 'nullable|image|mimes:png,jpg,jpeg,webp|max:2048',
            'is_active' => 'boolean',
        ]);

        $logoPath = null;
        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('insurers', 'public');
        }

        Insurer::create([
            'name' => $validated['name'],
            'short_name' => $validated['short_name'] ?? null,
            'code' => $validated['code'] ?? strtoupper(substr($validated['name'], 0, 3)),
            'primary_color' => $validated['primary_color'] ?? '7B2D3B',
            'logo_path' => $logoPath,
            'is_active' => $validated['is_active'] ?? true,
            'sort_order' => Insurer::max('sort_order') + 1,
        ]);

        return back()->with('success', 'Aseguradora creada exitosamente');
    }

    /**
     * Update the specified insurer.
     */
    public function update(Request $request, Insurer $insurer)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:insurers,name,' . $insurer->id,
            'short_name' => 'nullable|string|max:20',
            'code' => 'nullable|string|max:10|unique:insurers,code,' . $insurer->id,
            'primary_color' => 'nullable|string|max:7',
            'logo' => 'nullable|image|mimes:png,jpg,jpeg,webp|max:2048',
            'is_active' => 'boolean',
        ]);

        if ($request->hasFile('logo')) {
            // Delete old logo if exists
            if ($insurer->logo_path) {
                Storage::disk('public')->delete($insurer->logo_path);
            }
            $insurer->logo_path = $request->file('logo')->store('insurers', 'public');
        }

        $insurer->name = $validated['name'];
        $insurer->short_name = $validated['short_name'] ?? null;
        $insurer->code = $validated['code'] ?? $insurer->code;
        $insurer->primary_color = $validated['primary_color'] ?? $insurer->primary_color;
        $insurer->is_active = $validated['is_active'] ?? true;
        $insurer->save();

        return back()->with('success', 'Aseguradora actualizada exitosamente');
    }

    /**
     * Remove the specified insurer.
     */
    public function destroy(Insurer $insurer)
    {
        // Check if has quotes
        if ($insurer->quoteOptions()->exists()) {
            return back()->with('error', 'No se puede eliminar: tiene cotizaciones asociadas');
        }

        // Delete logo if exists
        if ($insurer->logo_path) {
            Storage::disk('public')->delete($insurer->logo_path);
        }

        $insurer->delete();

        return back()->with('success', 'Aseguradora eliminada exitosamente');
    }
}
