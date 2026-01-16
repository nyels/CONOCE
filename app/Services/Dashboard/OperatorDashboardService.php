<?php

namespace App\Services\Dashboard;

use App\Models\Quote;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class OperatorDashboardService
{
    public function getData()
    {
        $user = Auth::user();
        $today = Carbon::today();

        return [
            // NIVEL 1: ACCIÓN INMEDIATA
            'immediate_actions' => [
                'pending_today' => Quote::where('agent_id', $user->id) // Assuming agent ownership
                    ->whereDate('created_at', $today)
                    ->where('status', 'draft') // Example status
                    ->count(),
                'errors' => [], // Placeholder for now
            ],

            // NIVEL 2: FLUJO DE TRABAJO (Active Pipeline)
            'active_pipeline' => Quote::where('agent_id', $user->id)
                ->whereIn('status', ['draft', 'sent', 'processing']) // Active statuses
                ->orderBy('updated_at', 'desc')
                ->take(10)
                ->with('customer') // Eager load
                ->get()
                ->map(function ($quote) {
                    return [
                        'folio' => $quote->folio,
                        'customer_name' => $quote->customer ? $quote->customer->name : 'N/A',
                        'vehicle' => $quote->vehicle_description,
                        'status' => $quote->status,
                        'time' => $quote->updated_at->format('H:i'),
                        'raw_date' => $quote->updated_at,
                    ];
                }),

            // NIVEL 3: MÉTRICAS OPERATIVAS
            'metrics' => [
                'today_created' => Quote::where('agent_id', $user->id)->whereDate('created_at', $today)->count(),
                'today_sent' => Quote::where('agent_id', $user->id)->whereDate('created_at', $today)->where('status', 'sent')->count(),
                'today_issued' => Quote::where('agent_id', $user->id)->whereDate('created_at', $today)->where('status', 'concreted')->count(),
            ],

            // NIVEL 4: SISTEMA (Simplified for Operator)
            'recent_history' => [] // Can populate via ActivityLog if needed, but keeping light as per guidelines
        ];
    }
}
