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
            'name' => [
                'required',
                'string',
                'min:2',
                'max:100',
                'regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑüÜ0-9\s\-\.]+$/',
                'unique:coverage_packages,name',
            ],
            'code' => [
                'required',
                'string',
                'max:20',
                'regex:/^[A-Z0-9_\-]+$/i', // Solo alfanumérico, guiones y guiones bajos
                'unique:coverage_packages,code',
            ],
            'description' => ['nullable', 'string', 'max:500', 'regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑüÜ0-9\s\-\.\,\(\)]+$/'],
            'is_active' => 'boolean',
        ], [
            'name.regex' => 'El nombre solo puede contener letras, números, espacios, guiones y puntos.',
            'code.regex' => 'El código solo puede contener letras, números, guiones y guiones bajos.',
            'description.regex' => 'La descripción contiene caracteres no permitidos.',
        ]);

        try {
            CoveragePackage::create([
                'name' => $validated['name'],
                'code' => strtoupper($validated['code']),
                'description' => $validated['description'] ?? null,
                'is_active' => $validated['is_active'] ?? true,
                'sort_order' => CoveragePackage::max('sort_order') + 1,
            ]);

            return back()->with('success', 'Paquete creado exitosamente');
        } catch (\Exception $e) {
            return back()->withErrors(['server' => 'Error al crear el paquete. Intente nuevamente.']);
        }
    }

    public function update(Request $request, CoveragePackage $coveragePackage)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'min:2',
                'max:100',
                'regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑüÜ0-9\s\-\.]+$/',
                'unique:coverage_packages,name,' . $coveragePackage->id,
            ],
            'code' => [
                'required',
                'string',
                'max:20',
                'regex:/^[A-Z0-9_\-]+$/i',
                'unique:coverage_packages,code,' . $coveragePackage->id,
            ],
            'description' => ['nullable', 'string', 'max:500', 'regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑüÜ0-9\s\-\.\,\(\)]+$/'],
            'is_active' => 'boolean',
        ], [
            'name.regex' => 'El nombre solo puede contener letras, números, espacios, guiones y puntos.',
            'code.regex' => 'El código solo puede contener letras, números, guiones y guiones bajos.',
            'description.regex' => 'La descripción contiene caracteres no permitidos.',
        ]);

        try {
            $coveragePackage->update([
                'name' => $validated['name'],
                'code' => strtoupper($validated['code']),
                'description' => $validated['description'] ?? null,
                'is_active' => $validated['is_active'] ?? true,
            ]);

            return back()->with('success', 'Paquete actualizado exitosamente');
        } catch (\Exception $e) {
            return back()->withErrors(['server' => 'Error al actualizar el paquete.']);
        }
    }

    public function destroy(CoveragePackage $coveragePackage)
    {
        try {
            $coveragePackage->delete();
            return back()->with('success', 'Paquete eliminado exitosamente');
        } catch (\Exception $e) {
            return back()->withErrors(['server' => 'Error al eliminar el paquete.']);
        }
    }
}
