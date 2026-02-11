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
            ])
            ->values();

        return Inertia::render('Admin/VehicleTypes/Index', [
            'vehicleTypes' => $vehicleTypes,
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
                'unique:vehicle_types,name',
            ],
            'is_active' => 'boolean',
        ], [
            'name.regex' => 'El nombre solo puede contener letras, números, espacios, guiones y puntos.',
        ]);

        try {
            VehicleType::create([
                'name' => $validated['name'],
                'is_active' => $validated['is_active'] ?? true,
                'sort_order' => VehicleType::max('sort_order') + 1,
            ]);

            return back()->with('success', 'Tipo de vehículo creado exitosamente');
        } catch (\Exception $e) {
            return back()->withErrors(['server' => 'Error al crear el tipo de vehículo. Intente nuevamente.']);
        }
    }

    public function update(Request $request, VehicleType $vehicleType)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'min:2',
                'max:100',
                'regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑüÜ0-9\s\-\.]+$/',
                'unique:vehicle_types,name,' . $vehicleType->id,
            ],
            'is_active' => 'boolean',
        ], [
            'name.regex' => 'El nombre solo puede contener letras, números, espacios, guiones y puntos.',
        ]);

        try {
            $vehicleType->update([
                'name' => $validated['name'],
                'is_active' => $validated['is_active'] ?? true,
            ]);

            return back()->with('success', 'Tipo de vehículo actualizado exitosamente');
        } catch (\Exception $e) {
            return back()->withErrors(['server' => 'Error al actualizar el tipo de vehículo.']);
        }
    }

    public function destroy(VehicleType $vehicleType)
    {
        try {
            $vehicleType->delete();
            return back()->with('success', 'Tipo de vehículo eliminado exitosamente');
        } catch (\Exception $e) {
            return back()->withErrors(['server' => 'Error al eliminar el tipo de vehículo.']);
        }
    }
}
