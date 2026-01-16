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
            ]);

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
            'name' => 'required|string|max:100|unique:contact_types,name',
            'is_active' => 'boolean',
        ]);

        ContactType::create([
            'name' => $validated['name'],
            'is_active' => $validated['is_active'] ?? true,
            'sort_order' => ContactType::max('sort_order') + 1,
        ]);

        return back()->with('success', 'Tipo de contacto creado exitosamente');
    }

    /**
     * Update the specified contact type.
     */
    public function update(Request $request, ContactType $contactType)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100|unique:contact_types,name,' . $contactType->id,
            'is_active' => 'boolean',
        ]);

        $contactType->name = $validated['name'];
        $contactType->is_active = $validated['is_active'] ?? true;
        $contactType->save();

        return back()->with('success', 'Tipo de contacto actualizado exitosamente');
    }

    /**
     * Remove the specified contact type.
     */
    public function destroy(ContactType $contactType)
    {
        $contactType->delete();
        return back()->with('success', 'Tipo de contacto eliminado exitosamente');
    }
}
