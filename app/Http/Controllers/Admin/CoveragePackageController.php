<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CoveragePackage;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CoveragePackageController extends Controller
{
    public function index()
    {
        $packages = CoveragePackage::ordered()
            ->get()
            ->map(fn($pkg) => [
                'id' => $pkg->id,
                'name' => $pkg->name,
                'code' => $pkg->code,
                'description' => $pkg->description,
                'is_active' => $pkg->is_active,
            ]);

        return Inertia::render('Admin/CoveragePackages/Index', [
            'packages' => $packages,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:coverage_packages,name',
            'code' => 'required|string|max:20|unique:coverage_packages,code',
            'description' => 'nullable|string|max:500',
            'is_active' => 'boolean',
        ]);

        CoveragePackage::create([
            'name' => $validated['name'],
            'code' => strtoupper($validated['code']),
            'description' => $validated['description'] ?? null,
            'is_active' => $validated['is_active'] ?? true,
            'sort_order' => CoveragePackage::max('sort_order') + 1,
        ]);

        return back()->with('success', 'Paquete creado exitosamente');
    }

    public function update(Request $request, CoveragePackage $coveragePackage)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:coverage_packages,name,' . $coveragePackage->id,
            'code' => 'required|string|max:20|unique:coverage_packages,code,' . $coveragePackage->id,
            'description' => 'nullable|string|max:500',
            'is_active' => 'boolean',
        ]);

        $coveragePackage->update([
            'name' => $validated['name'],
            'code' => strtoupper($validated['code']),
            'description' => $validated['description'] ?? null,
            'is_active' => $validated['is_active'] ?? true,
        ]);

        return back()->with('success', 'Paquete actualizado exitosamente');
    }

    public function destroy(CoveragePackage $coveragePackage)
    {
        $coveragePackage->delete();
        return back()->with('success', 'Paquete eliminado exitosamente');
    }
}
