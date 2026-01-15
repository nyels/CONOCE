<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Quote;
use Src\Domain\Quote\Enums\QuoteStatus;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        // Query base según permisos
        $baseQuery = Quote::query();

        if (!$user->canViewAllQuotes()) {
            $baseQuery->where('agent_id', $user->id);
        }

        // Estadísticas del mes
        $stats = [
            'quotes_this_month' => (clone $baseQuery)->thisMonth()->count(),
            'pending_quotes' => (clone $baseQuery)->sent()->count(),
            'concreted_quotes' => (clone $baseQuery)->thisMonth()->whereIn('status', [
                QuoteStatus::CONCRETED,
                QuoteStatus::ISSUED,
            ])->count(),
        ];

        // Calcular tasa de conversión
        $totalMonth = $stats['quotes_this_month'];
        $stats['conversion_rate'] = $totalMonth > 0
            ? round(($stats['concreted_quotes'] / $totalMonth) * 100, 1)
            : 0;

        // Cotizaciones recientes
        $recentQuotes = (clone $baseQuery)
            ->with(['customer:id,name', 'agent:id,name'])
            ->orderByDesc('created_at')
            ->limit(5)
            ->get();

        return view('dashboard', compact('stats', 'recentQuotes'));
    }
}
