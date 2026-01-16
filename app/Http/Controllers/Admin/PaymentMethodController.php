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
            ]);

        return Inertia::render('Admin/PaymentMethods/Index', [
            'methods' => $methods,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:50',
            'code' => 'required|string|max:10|unique:payment_methods,code',
            'installments' => 'required|integer|min:1|max:12',
            'surcharge_percentage' => 'required|numeric|min:0|max:100',
            'is_active' => 'boolean',
        ]);

        PaymentMethod::create([
            'name' => $validated['name'],
            'code' => strtoupper($validated['code']),
            'installments' => $validated['installments'],
            'surcharge_percentage' => $validated['surcharge_percentage'],
            'is_active' => $validated['is_active'] ?? true,
            'sort_order' => PaymentMethod::max('sort_order') + 1,
        ]);

        return back()->with('success', 'Forma de pago creada exitosamente');
    }

    public function update(Request $request, PaymentMethod $paymentMethod)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:50',
            'code' => 'required|string|max:10|unique:payment_methods,code,' . $paymentMethod->id,
            'installments' => 'required|integer|min:1|max:12',
            'surcharge_percentage' => 'required|numeric|min:0|max:100',
            'is_active' => 'boolean',
        ]);

        $paymentMethod->update([
            'name' => $validated['name'],
            'code' => strtoupper($validated['code']),
            'installments' => $validated['installments'],
            'surcharge_percentage' => $validated['surcharge_percentage'],
            'is_active' => $validated['is_active'] ?? true,
        ]);

        return back()->with('success', 'Forma de pago actualizada exitosamente');
    }

    public function destroy(PaymentMethod $paymentMethod)
    {
        $paymentMethod->delete();
        return back()->with('success', 'Forma de pago eliminada exitosamente');
    }
}
