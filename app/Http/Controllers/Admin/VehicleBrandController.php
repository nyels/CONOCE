<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\VehicleBrand;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class VehicleBrandController extends Controller
{
    /**
     * Display a listing of vehicle brands.
     */
    public function index()
    {
        $brands = VehicleBrand::orderBy('name')
            ->get()
            ->map(fn($brand) => [
                'id' => $brand->id,
                'name' => $brand->name,
                'logo_url' => $brand->logo_path ? Storage::url($brand->logo_path) : null,
                'is_active' => $brand->is_active,
            ])
            ->values();

        // DEBUG: Force output to verifying data
        // dd($brands->toArray());

        return Inertia::render('Admin/VehicleBrands/Index', [
            'brands' => $brands,
        ]);
    }

    /**
     * Store a newly created vehicle brand.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:vehicle_brands,name',
            'logo' => 'nullable|image|mimes:png,jpg,jpeg,webp|max:2048',
            'is_active' => 'boolean',
        ]);

        $logoPath = null;
        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('brands', 'public');
        }

        VehicleBrand::create([
            'name' => $validated['name'],
            'logo_path' => $logoPath,
            'is_active' => $validated['is_active'] ?? true,
        ]);

        return back()->with('success', 'Marca creada exitosamente');
    }

    /**
     * Update the specified vehicle brand.
     */
    public function update(Request $request, VehicleBrand $vehicleBrand)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:vehicle_brands,name,' . $vehicleBrand->id,
            'logo' => 'nullable|image|mimes:png,jpg,jpeg,webp|max:2048',
            'is_active' => 'boolean',
        ]);

        if ($request->hasFile('logo')) {
            // Delete old logo if exists
            if ($vehicleBrand->logo_path) {
                Storage::disk('public')->delete($vehicleBrand->logo_path);
            }
            $vehicleBrand->logo_path = $request->file('logo')->store('brands', 'public');
        }

        $vehicleBrand->name = $validated['name'];
        $vehicleBrand->is_active = $validated['is_active'] ?? true;
        $vehicleBrand->save();

        return back()->with('success', 'Marca actualizada exitosamente');
    }

    /**
     * Remove the specified vehicle brand.
     */
    public function destroy(VehicleBrand $vehicleBrand)
    {
        // Delete logo if exists
        if ($vehicleBrand->logo_path) {
            Storage::disk('public')->delete($vehicleBrand->logo_path);
        }

        $vehicleBrand->delete();

        return back()->with('success', 'Marca eliminada exitosamente');
    }
}
