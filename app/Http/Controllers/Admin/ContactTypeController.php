<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactType;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ContactTypeController extends Controller
{
    /**
     * Display a listing of contact types.
     */
    public function index()
    {
        $contactTypes = ContactType::ordered()
            ->get()
            ->map(fn($type) => [
                'id' => $type->id,
                'name' => $type->name,
                'is_active' => $type->is_active,
                'sort_order' => $type->sort_order,
            ])
            ->values();

        return Inertia::render('Admin/ContactTypes/Index', [
            'contactTypes' => $contactTypes,
        ]);
    }

    /**
     * Store a newly created contact type.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'min:2',
                'max:100',
                'regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑüÜ0-9\s\-\.]+$/',
                'unique:contact_types,name',
            ],
            'is_active' => 'boolean',
        ], [
            'name.regex' => 'El nombre solo puede contener letras, números, espacios, guiones y puntos.',
        ]);

        try {
            ContactType::create([
                'name' => $validated['name'],
                'is_active' => $validated['is_active'] ?? true,
                'sort_order' => ContactType::max('sort_order') + 1,
            ]);

            return back()->with('success', 'Tipo de contacto creado exitosamente');
        } catch (\Exception $e) {
            return back()->withErrors(['server' => 'Error al crear el tipo de contacto. Intente nuevamente.']);
        }
    }

    /**
     * Update the specified contact type.
     */
    public function update(Request $request, ContactType $contactType)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'min:2',
                'max:100',
                'regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑüÜ0-9\s\-\.]+$/',
                'unique:contact_types,name,' . $contactType->id,
            ],
            'is_active' => 'boolean',
        ], [
            'name.regex' => 'El nombre solo puede contener letras, números, espacios, guiones y puntos.',
        ]);

        try {
            $contactType->name = $validated['name'];
            $contactType->is_active = $validated['is_active'] ?? true;
            $contactType->save();

            return back()->with('success', 'Tipo de contacto actualizado exitosamente');
        } catch (\Exception $e) {
            return back()->withErrors(['server' => 'Error al actualizar el tipo de contacto.']);
        }
    }

    /**
     * Remove the specified contact type.
     */
    public function destroy(ContactType $contactType)
    {
        try {
            $contactType->delete();
            return back()->with('success', 'Tipo de contacto eliminado exitosamente');
        } catch (\Exception $e) {
            return back()->withErrors(['server' => 'Error al eliminar el tipo de contacto.']);
        }
    }
}
