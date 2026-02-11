<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PaymentMethodController extends Controller
{
    public function index()
    {
        $methods = PaymentMethod::ordered()
            ->get()
            ->map(fn($m) => [
                'id' => $m->id,
                'name' => $m->name,
                'code' => $m->code,
                'installments' => $m->installments,
                'surcharge_percentage' => $m->surcharge_percentage,
                'is_active' => $m->is_active,
            ])
            ->values();

        return Inertia::render('Admin/PaymentMethods/Index', [
            'methods' => $methods,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'min:2',
                'max:50',
                'regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑüÜ0-9\s\-\.]+$/',
            ],
            'code' => [
                'required',
                'string',
                'max:10',
                'regex:/^[A-Z0-9_\-]+$/i',
                'unique:payment_methods,code',
            ],
            'installments' => 'required|integer|min:1|max:12',
            'surcharge_percentage' => 'required|numeric|min:0|max:100',
            'is_active' => 'boolean',
        ], [
            'name.regex' => 'El nombre solo puede contener letras, números, espacios, guiones y puntos.',
            'code.regex' => 'El código solo puede contener letras, números, guiones y guiones bajos.',
        ]);

        try {
            PaymentMethod::create([
                'name' => $validated['name'],
                'code' => strtoupper($validated['code']),
                'installments' => $validated['installments'],
                'surcharge_percentage' => $validated['surcharge_percentage'],
                'is_active' => $validated['is_active'] ?? true,
                'sort_order' => PaymentMethod::max('sort_order') + 1,
            ]);

            return back()->with('success', 'Forma de pago creada exitosamente');
        } catch (\Exception $e) {
            return back()->withErrors(['server' => 'Error al crear la forma de pago. Intente nuevamente.']);
        }
    }

    public function update(Request $request, PaymentMethod $paymentMethod)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'min:2',
                'max:50',
                'regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑüÜ0-9\s\-\.]+$/',
            ],
            'code' => [
                'required',
                'string',
                'max:10',
                'regex:/^[A-Z0-9_\-]+$/i',
                'unique:payment_methods,code,' . $paymentMethod->id,
            ],
            'installments' => 'required|integer|min:1|max:12',
            'surcharge_percentage' => 'required|numeric|min:0|max:100',
            'is_active' => 'boolean',
        ], [
            'name.regex' => 'El nombre solo puede contener letras, números, espacios, guiones y puntos.',
            'code.regex' => 'El código solo puede contener letras, números, guiones y guiones bajos.',
        ]);

        try {
            $paymentMethod->update([
                'name' => $validated['name'],
                'code' => strtoupper($validated['code']),
                'installments' => $validated['installments'],
                'surcharge_percentage' => $validated['surcharge_percentage'],
                'is_active' => $validated['is_active'] ?? true,
            ]);

            return back()->with('success', 'Forma de pago actualizada exitosamente');
        } catch (\Exception $e) {
            return back()->withErrors(['server' => 'Error al actualizar la forma de pago.']);
        }
    }

    public function destroy(PaymentMethod $paymentMethod)
    {
        try {
            $paymentMethod->delete();
            return back()->with('success', 'Forma de pago eliminada exitosamente');
        } catch (\Exception $e) {
            return back()->withErrors(['server' => 'Error al eliminar la forma de pago.']);
        }
    }
}
