<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\VehicleType;
use Illuminate\Http\Request;
use Inertia\Inertia;

class VehicleTypeController extends Controller
{
    public function index()
    {
        $vehicleTypes = VehicleType::ordered()
            ->get()
            ->map(fn($type) => [
                'id' => $type->id,
                'name' => $type->name,
                'is_active' => $type->is_active,
            ]);

        return Inertia::render('Admin/VehicleTypes/Index', [
            'vehicleTypes' => $vehicleTypes,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:vehicle_types,name',
            'is_active' => 'boolean',
        ]);

        VehicleType::create([
            'name' => $validated['name'],
            'is_active' => $validated['is_active'] ?? true,
            'sort_order' => VehicleType::max('sort_order') + 1,
        ]);

        return back()->with('success', 'Tipo de vehículo creado exitosamente');
    }

    public function update(Request $request, VehicleType $vehicleType)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:vehicle_types,name,' . $vehicleType->id,
            'is_active' => 'boolean',
        ]);

        $vehicleType->update([
            'name' => $validated['name'],
            'is_active' => $validated['is_active'] ?? true,
        ]);

        return back()->with('success', 'Tipo de vehículo actualizado exitosamente');
    }

    public function destroy(VehicleType $vehicleType)
    {
        $vehicleType->delete();
        return back()->with('success', 'Tipo de vehículo eliminado exitosamente');
    }
}
