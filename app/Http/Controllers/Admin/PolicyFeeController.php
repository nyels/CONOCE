<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Insurer;
use App\Models\InsurerFinancialSetting;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PolicyFeeController extends Controller
{
    public function index()
    {
        $policyFees = InsurerFinancialSetting::with('insurer')
            ->whereNull('valid_until')
            ->get()
            ->map(fn($setting) => [
                'id' => $setting->id,
                'insurer_id' => $setting->insurer_id,
                'insurer_name' => $setting->insurer->display_name ?? $setting->insurer->name,
                'policy_fee' => round($setting->policy_fee_cents / 100, 2),
                'created_at' => $setting->created_at->format('d/m/Y H:i'),
            ])
            ->values();

        $insurers = Insurer::where('is_active', true)
            ->orderBy('name')
            ->get()
            ->map(fn($insurer) => [
                'value' => $insurer->id,
                'label' => $insurer->display_name ?? $insurer->name,
            ])
            ->values();

        return Inertia::render('Admin/PolicyFees/Index', [
            'policyFees' => $policyFees,
            'insurers' => $insurers,
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'insurer_id' => [
                'required',
                'integer',
                'exists:insurers,id',
            ],
            'policy_fee' => [
                'required',
                'numeric',
                'min:0',
                'max:999999.99',
                'regex:/^\d+(\.\d{1,2})?$/',
            ],
        ], [
            'insurer_id.required' => 'Debe seleccionar una aseguradora.',
            'insurer_id.exists' => 'La aseguradora seleccionada no existe.',
            'policy_fee.required' => 'El derecho de póliza es obligatorio.',
            'policy_fee.numeric' => 'El derecho de póliza debe ser un número válido.',
            'policy_fee.min' => 'El derecho de póliza no puede ser negativo.',
            'policy_fee.max' => 'El derecho de póliza no puede exceder $999,999.99.',
            'policy_fee.regex' => 'El derecho de póliza solo acepta hasta 2 decimales.',
        ]);

        try {
            // Cerrar registro activo anterior si existe (genera historial)
            $previous = InsurerFinancialSetting::where('insurer_id', $validated['insurer_id'])
                ->whereNull('valid_until')
                ->first();

            $surcharges = [
                'surcharge_semiannual' => $previous?->surcharge_semiannual ?? 0,
                'surcharge_quarterly' => $previous?->surcharge_quarterly ?? 0,
                'surcharge_monthly' => $previous?->surcharge_monthly ?? 0,
            ];

            if ($previous) {
                $previous->update(['valid_until' => now()->toDateString()]);
            }

            InsurerFinancialSetting::create([
                'insurer_id' => $validated['insurer_id'],
                'policy_fee_cents' => (int) round($validated['policy_fee'] * 100),
                ...$surcharges,
                'valid_from' => now()->toDateString(),
                'valid_until' => null,
                'created_by' => auth()->id(),
            ]);

            return back()->with('success', 'Derecho de póliza creado exitosamente');
        } catch (\Exception $e) {
            return back()->withErrors(['server' => 'Error al crear el derecho de póliza. Intente nuevamente.']);
        }
    }

    public function update(Request $request, InsurerFinancialSetting $policyFee)
    {
        $validated = $request->validate([
            'policy_fee' => [
                'required',
                'numeric',
                'min:0',
                'max:999999.99',
                'regex:/^\d+(\.\d{1,2})?$/',
            ],
        ], [
            'policy_fee.required' => 'El derecho de póliza es obligatorio.',
            'policy_fee.numeric' => 'El derecho de póliza debe ser un número válido.',
            'policy_fee.min' => 'El derecho de póliza no puede ser negativo.',
            'policy_fee.max' => 'El derecho de póliza no puede exceder $999,999.99.',
            'policy_fee.regex' => 'El derecho de póliza solo acepta hasta 2 decimales.',
        ]);

        $newCents = (int) round($validated['policy_fee'] * 100);

        // Si el monto no cambió, no hacer nada
        if ($policyFee->policy_fee_cents === $newCents) {
            return back()->with('success', 'Sin cambios en el derecho de póliza.');
        }

        try {
            // Cerrar el registro actual (historial)
            $policyFee->update([
                'valid_until' => now()->toDateString(),
            ]);

            // Crear nuevo registro vigente
            InsurerFinancialSetting::create([
                'insurer_id' => $policyFee->insurer_id,
                'policy_fee_cents' => $newCents,
                'surcharge_semiannual' => $policyFee->surcharge_semiannual,
                'surcharge_quarterly' => $policyFee->surcharge_quarterly,
                'surcharge_monthly' => $policyFee->surcharge_monthly,
                'valid_from' => now()->toDateString(),
                'valid_until' => null,
                'created_by' => auth()->id(),
            ]);

            return back()->with('success', 'Derecho de póliza actualizado exitosamente');
        } catch (\Exception $e) {
            return back()->withErrors(['server' => 'Error al actualizar el derecho de póliza.']);
        }
    }

    public function destroy(InsurerFinancialSetting $policyFee)
    {
        try {
            $policyFee->delete();
            return back()->with('success', 'Derecho de póliza eliminado exitosamente');
        } catch (\Exception $e) {
            return back()->withErrors(['server' => 'Error al eliminar el derecho de póliza.']);
        }
    }

    public function history(Insurer $insurer, Request $request)
    {
        $perPage = 20;
        $page = max(1, (int) $request->query('page', 1));

        $query = InsurerFinancialSetting::where('insurer_id', $insurer->id)
            ->orderByDesc('created_at')
            ->orderByDesc('id');

        $total = $query->count();
        $lastPage = max(1, (int) ceil($total / $perPage));
        $page = min($page, $lastPage);

        $history = $query
            ->skip(($page - 1) * $perPage)
            ->take($perPage)
            ->get()
            ->map(fn($setting) => [
                'id' => $setting->id,
                'policy_fee' => round($setting->policy_fee_cents / 100, 2),
                'valid_from' => $setting->valid_from->format('d/m/Y'),
                'valid_until' => $setting->valid_until?->format('d/m/Y'),
                'created_at' => $setting->created_at->format('d/m/Y H:i'),
            ])
            ->values();

        return response()->json([
            'insurer_name' => $insurer->display_name ?? $insurer->name,
            'history' => $history,
            'pagination' => [
                'current_page' => $page,
                'last_page' => $lastPage,
                'per_page' => $perPage,
                'total' => $total,
            ],
        ]);
    }
}
