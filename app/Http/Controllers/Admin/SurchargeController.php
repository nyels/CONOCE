<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Insurer;
use App\Models\InsurerFinancialSetting;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SurchargeController extends Controller
{
    private const FREQUENCY_MAP = [
        'semiannual' => ['column' => 'surcharge_semiannual', 'label' => 'Semestral'],
        'quarterly'  => ['column' => 'surcharge_quarterly',  'label' => 'Trimestral'],
        'monthly'    => ['column' => 'surcharge_monthly',    'label' => 'Mensual'],
    ];

    public function index()
    {
        $settings = InsurerFinancialSetting::with('insurer')
            ->whereNull('valid_until')
            ->get();

        // Aplanar: cada setting genera hasta 3 filas (una por frecuencia con valor > 0)
        $surcharges = collect();
        foreach ($settings as $setting) {
            foreach (self::FREQUENCY_MAP as $key => $meta) {
                $value = (float) $setting->{$meta['column']};
                if ($value > 0) {
                    $surcharges->push([
                        'id' => $setting->id,
                        'insurer_id' => $setting->insurer_id,
                        'insurer_name' => $setting->insurer->display_name ?? $setting->insurer->name,
                        'frequency' => $key,
                        'frequency_label' => $meta['label'],
                        'surcharge' => round($value * 100, 2),
                        'created_at' => $setting->created_at->format('d/m/Y H:i'),
                    ]);
                }
            }
        }

        $insurers = Insurer::where('is_active', true)
            ->orderBy('name')
            ->get()
            ->map(fn($insurer) => [
                'value' => $insurer->id,
                'label' => $insurer->display_name ?? $insurer->name,
            ])
            ->values();

        $frequencies = collect(self::FREQUENCY_MAP)->map(fn($meta, $key) => [
            'value' => $key,
            'label' => $meta['label'],
        ])->values();

        return Inertia::render('Admin/Surcharges/Index', [
            'surcharges' => $surcharges->values(),
            'insurers' => $insurers,
            'frequencies' => $frequencies,
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
            'frequency' => [
                'required',
                'string',
                'in:semiannual,quarterly,monthly',
            ],
            'surcharge' => [
                'required',
                'numeric',
                'min:0',
                'max:99.99',
                'regex:/^\d+(\.\d{1,2})?$/',
            ],
        ], [
            'insurer_id.required' => 'Debe seleccionar una aseguradora.',
            'insurer_id.exists' => 'La aseguradora seleccionada no existe.',
            'frequency.required' => 'Debe seleccionar una forma de pago.',
            'frequency.in' => 'La forma de pago seleccionada no es válida.',
            'surcharge.required' => 'El recargo es obligatorio.',
            'surcharge.numeric' => 'El recargo debe ser un número válido.',
            'surcharge.min' => 'El recargo no puede ser negativo.',
            'surcharge.max' => 'El recargo no puede exceder 99.99%.',
            'surcharge.regex' => 'El recargo solo acepta hasta 2 decimales.',
        ]);

        $column = self::FREQUENCY_MAP[$validated['frequency']]['column'];
        $decimalValue = round($validated['surcharge'] / 100, 4);

        try {
            $previous = InsurerFinancialSetting::where('insurer_id', $validated['insurer_id'])
                ->whereNull('valid_until')
                ->first();

            // Copiar valores del registro anterior o usar defaults
            $data = [
                'insurer_id' => $validated['insurer_id'],
                'policy_fee_cents' => $previous?->policy_fee_cents ?? 0,
                'surcharge_semiannual' => $previous?->surcharge_semiannual ?? 0,
                'surcharge_quarterly' => $previous?->surcharge_quarterly ?? 0,
                'surcharge_monthly' => $previous?->surcharge_monthly ?? 0,
                'valid_from' => now()->toDateString(),
                'valid_until' => null,
                'created_by' => auth()->id(),
            ];

            // Actualizar el campo de frecuencia correspondiente
            $data[$column] = $decimalValue;

            // Cerrar registro anterior
            if ($previous) {
                $previous->update(['valid_until' => now()->toDateString()]);
            }

            InsurerFinancialSetting::create($data);

            return back()->with('success', 'Recargo creado exitosamente');
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Error al crear recargo', [
                'message' => $e->getMessage(),
                'file' => $e->getFile() . ':' . $e->getLine(),
                'input' => $validated,
            ]);
            return back()->withErrors(['server' => 'Error al crear el recargo. Intente nuevamente.']);
        }
    }

    public function update(Request $request, InsurerFinancialSetting $surcharge)
    {
        $validated = $request->validate([
            'frequency' => [
                'required',
                'string',
                'in:semiannual,quarterly,monthly',
            ],
            'surcharge' => [
                'required',
                'numeric',
                'min:0',
                'max:99.99',
                'regex:/^\d+(\.\d{1,2})?$/',
            ],
        ], [
            'frequency.required' => 'Debe seleccionar una forma de pago.',
            'frequency.in' => 'La forma de pago seleccionada no es válida.',
            'surcharge.required' => 'El recargo es obligatorio.',
            'surcharge.numeric' => 'El recargo debe ser un número válido.',
            'surcharge.min' => 'El recargo no puede ser negativo.',
            'surcharge.max' => 'El recargo no puede exceder 99.99%.',
            'surcharge.regex' => 'El recargo solo acepta hasta 2 decimales.',
        ]);

        $column = self::FREQUENCY_MAP[$validated['frequency']]['column'];
        $decimalValue = round($validated['surcharge'] / 100, 4);

        // Si el valor no cambió, no hacer nada
        if ((float) $surcharge->{$column} === $decimalValue) {
            return back()->with('success', 'Sin cambios en el recargo.');
        }

        try {
            // Cerrar registro actual
            $surcharge->update(['valid_until' => now()->toDateString()]);

            // Crear nuevo registro con el surcharge actualizado
            $data = [
                'insurer_id' => $surcharge->insurer_id,
                'policy_fee_cents' => $surcharge->policy_fee_cents,
                'surcharge_semiannual' => $surcharge->surcharge_semiannual,
                'surcharge_quarterly' => $surcharge->surcharge_quarterly,
                'surcharge_monthly' => $surcharge->surcharge_monthly,
                'valid_from' => now()->toDateString(),
                'valid_until' => null,
                'created_by' => auth()->id(),
            ];
            $data[$column] = $decimalValue;

            InsurerFinancialSetting::create($data);

            return back()->with('success', 'Recargo actualizado exitosamente');
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Error al actualizar recargo', [
                'message' => $e->getMessage(),
                'file' => $e->getFile() . ':' . $e->getLine(),
                'input' => $validated,
            ]);
            return back()->withErrors(['server' => 'Error al actualizar el recargo.']);
        }
    }

    public function destroy(Request $request, InsurerFinancialSetting $surcharge)
    {
        $frequency = $request->query('frequency');

        if (!$frequency || !isset(self::FREQUENCY_MAP[$frequency])) {
            return back()->withErrors(['server' => 'Frecuencia no válida.']);
        }

        $column = self::FREQUENCY_MAP[$frequency]['column'];

        try {
            // Cerrar registro actual
            $surcharge->update(['valid_until' => now()->toDateString()]);

            // Crear nuevo registro con el surcharge en 0
            $data = [
                'insurer_id' => $surcharge->insurer_id,
                'policy_fee_cents' => $surcharge->policy_fee_cents,
                'surcharge_semiannual' => $surcharge->surcharge_semiannual,
                'surcharge_quarterly' => $surcharge->surcharge_quarterly,
                'surcharge_monthly' => $surcharge->surcharge_monthly,
                'valid_from' => now()->toDateString(),
                'valid_until' => null,
                'created_by' => auth()->id(),
            ];
            $data[$column] = 0;

            InsurerFinancialSetting::create($data);

            return back()->with('success', 'Recargo eliminado exitosamente');
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Error al eliminar recargo', [
                'message' => $e->getMessage(),
                'file' => $e->getFile() . ':' . $e->getLine(),
                'surcharge_id' => $surcharge->id,
                'frequency' => $frequency,
            ]);
            return back()->withErrors(['server' => 'Error al eliminar el recargo.']);
        }
    }

    public function history(Insurer $insurer, Request $request)
    {
        $frequency = $request->query('frequency');

        if (!$frequency || !isset(self::FREQUENCY_MAP[$frequency])) {
            return response()->json(['error' => 'Frecuencia no válida'], 422);
        }

        $column = self::FREQUENCY_MAP[$frequency]['column'];
        $label = self::FREQUENCY_MAP[$frequency]['label'];

        $history = InsurerFinancialSetting::where('insurer_id', $insurer->id)
            ->orderByDesc('created_at')
            ->orderByDesc('id')
            ->get()
            ->map(fn($setting) => [
                'id' => $setting->id,
                'surcharge' => round((float) $setting->{$column} * 100, 2),
                'valid_from' => $setting->valid_from->format('d/m/Y'),
                'valid_until' => $setting->valid_until?->format('d/m/Y'),
                'created_at' => $setting->created_at->format('d/m/Y H:i'),
            ])
            ->values();

        return response()->json([
            'insurer_name' => $insurer->display_name ?? $insurer->name,
            'frequency_label' => $label,
            'history' => $history,
        ]);
    }
}
