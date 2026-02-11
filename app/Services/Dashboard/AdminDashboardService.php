<?php
// app/Services/Dashboard/AdminDashboardService.php
namespace App\Services\Dashboard;

use App\Models\Quote;
use App\Models\Insurer;
use App\Models\Contact;
use Src\Domain\Quote\Enums\QuoteStatus;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

class AdminDashboardService
{
    private Carbon $today;
    private Carbon $startOfMonth;
    private Carbon $startOfPreviousMonth;

    public function __construct()
    {
        $this->today = Carbon::today();
        $this->startOfMonth = Carbon::now()->startOfMonth();
        $this->startOfPreviousMonth = Carbon::now()->subMonth()->startOfMonth();
    }

    public function getData(string $trendPeriod = 'month'): array
    {
        return [
            // NIVEL 1: KPIs FINANCIEROS CLAVE
            'financialKpis' => $this->getFinancialKpis(),

            // NIVEL 2: TENDENCIAS Y COMPARATIVAS
            'trends' => $this->getTrendsData($trendPeriod),

            // NIVEL 3: CONVERSIÓN POR ASEGURADORA
            'conversionByInsurer' => $this->getConversionByInsurer($trendPeriod),

            // NIVEL 4: ALERTAS CRÍTICAS DEL SISTEMA
            'systemAlerts' => $this->getSystemAlerts(),

            // METADATOS
            'period' => [
                'current' => $this->startOfMonth->translatedFormat('F Y'),
                'previous' => $this->startOfPreviousMonth->translatedFormat('F Y'),
            ],
            'timestamp' => now()->toIso8601String(),
        ];
    }

    private function getFinancialKpis(): array
    {
        $currentMonthData = $this->getMonthFinancialData($this->startOfMonth);
        $previousMonthData = $this->getMonthFinancialData($this->startOfPreviousMonth);

        return [
            [
                'id' => 'total_premium',
                'title' => 'Prima Total',
                'value' => $currentMonthData['total_premium'],
                'previousValue' => $previousMonthData['total_premium'],
                'format' => 'currency',
                'icon' => '💰',
                'priority' => 1,
                'subtitle' => 'Prima emitida este mes',
            ],
            [
                'id' => 'policy_fee',
                'title' => 'Comisiones',
                'value' => $currentMonthData['policy_fee'],
                'previousValue' => $previousMonthData['policy_fee'],
                'format' => 'currency',
                'icon' => '💼',
                'priority' => 1,
                'subtitle' => 'Comisiones generadas',
            ],
            [
                'id' => 'quote_count',
                'title' => 'Cotizaciones',
                'value' => $currentMonthData['quote_count'],
                'previousValue' => $previousMonthData['quote_count'],
                'format' => 'number',
                'icon' => '📄',
                'priority' => 2,
                'subtitle' => 'Cotizaciones creadas',
            ],
            [
                'id' => 'conversion_rate',
                'title' => 'Tasa Conversión',
                'value' => $currentMonthData['conversion_rate'],
                'previousValue' => $previousMonthData['conversion_rate'],
                'format' => 'percent',
                'icon' => '📈',
                'priority' => 1,
                'subtitle' => 'Cotizaciones → Pólizas',
            ],
            [
                'id' => 'avg_premium',
                'title' => 'Prima Promedio',
                'value' => $currentMonthData['avg_premium'],
                'previousValue' => $previousMonthData['avg_premium'],
                'format' => 'currency',
                'icon' => '⚖️',
                'priority' => 2,
                'subtitle' => 'Por póliza emitida',
            ],
            [
                'id' => 'active_agents',
                'title' => 'Agentes Activos',
                'value' => $this->getActiveAgentsCount(),
                'previousValue' => null,
                'format' => 'number',
                'icon' => '👥',
                'priority' => 3,
                'subtitle' => 'Con actividad este mes',
            ],
        ];
    }

    private function getMonthFinancialData(Carbon $startOfMonth): array
    {
        $endOfMonth = (clone $startOfMonth)->endOfMonth();

        // Check if quotes table has the expected columns
        try {
            $data = Quote::query()
                ->whereBetween('created_at', [$startOfMonth, $endOfMonth])
                ->select([
                    DB::raw('COUNT(*) as quote_count'),
                    DB::raw('COALESCE(SUM(CASE WHEN status = \'' . QuoteStatus::ISSUED->value . '\' THEN 1 ELSE 0 END), 0) as concluded_count'),
                ])
                ->first();

            // For now, use placeholder values for financial data
            // These columns may not exist in the current schema
            $quoteCount = (int) ($data->quote_count ?? 0);
            $concludedCount = (int) ($data->concluded_count ?? 0);

            $conversionRate = $quoteCount > 0
                ? ($concludedCount / $quoteCount) * 100
                : 0;

            return [
                'quote_count' => $quoteCount,
                'total_premium' => 0, // Placeholder - requires quote_options join
                'policy_fee' => 0,    // Placeholder - requires quote_options join
                'avg_premium' => 0,   // Placeholder - requires quote_options join
                'conversion_rate' => round($conversionRate, 1),
            ];
        } catch (\Exception $e) {
            // Fallback with zeros if there's any database issue
            return [
                'quote_count' => 0,
                'total_premium' => 0,
                'policy_fee' => 0,
                'avg_premium' => 0,
                'conversion_rate' => 0,
            ];
        }
    }

    private function getActiveAgentsCount(): int
    {
        try {
            return Quote::query()
                ->whereMonth('created_at', $this->startOfMonth->month)
                ->whereYear('created_at', $this->startOfMonth->year)
                ->distinct('agent_id')
                ->count('agent_id');
        } catch (\Exception $e) {
            return 0;
        }
    }

    private function getTrendsData(string $periodType = 'month'): array
    {
        $periods = $this->buildPeriods($periodType);

        try {
            $trends = $periods->map(function ($period) {
                $data = Quote::query()
                    ->whereBetween('created_at', [$period['start'], $period['end']])
                    ->select([
                        DB::raw('COUNT(*) as quote_count'),
                        DB::raw('COALESCE(SUM(CASE WHEN status = \'' . QuoteStatus::ISSUED->value . '\' THEN 1 ELSE 0 END), 0) as concluded_count'),
                    ])
                    ->first();

                $quoteCount = (int) ($data->quote_count ?? 0);
                $concludedCount = (int) ($data->concluded_count ?? 0);

                return [
                    'label' => $period['label'],
                    'sublabel' => $period['sublabel'] ?? null,
                    'quotes' => $quoteCount,
                    'premium' => 0,
                    'conversions' => $concludedCount,
                    'conversion_rate' => $quoteCount > 0
                        ? round(($concludedCount / $quoteCount) * 100, 1)
                        : 0,
                ];
            });

            $count = $trends->count();

            return [
                'data' => $trends->values()->toArray(),
                'periodType' => $periodType,
                'summary' => [
                    'growth_quotes' => $this->calculateGrowthRate(
                        $count >= 2 ? ($trends->get($count - 2)['quotes'] ?? 0) : 0,
                        $trends->last()['quotes'] ?? 0
                    ),
                    'growth_premium' => 0,
                ],
            ];
        } catch (\Exception $e) {
            return [
                'data' => [],
                'periodType' => $periodType,
                'summary' => [
                    'growth_quotes' => 0,
                    'growth_premium' => 0,
                ],
            ];
        }
    }

    private function buildPeriods(string $type): \Illuminate\Support\Collection
    {
        $periods = collect();

        if ($type === 'quarter') {
            // 2 trimestres: anterior y actual
            for ($i = 1; $i >= 0; $i--) {
                $start = Carbon::now()->subQuarters($i)->startOfQuarter();
                $end = (clone $start)->endOfQuarter();
                $q = (int) ceil($start->month / 3);
                $periods->push([
                    'label' => "T{$q} {$start->year}",
                    'sublabel' => $start->translatedFormat('M') . ' - ' . $end->translatedFormat('M Y'),
                    'start' => $start,
                    'end' => $end,
                ]);
            }
        } elseif ($type === 'year') {
            // 1 año: año en curso
            $start = Carbon::now()->startOfYear();
            $end = Carbon::now();
            $periods->push([
                'label' => (string) $start->year,
                'sublabel' => $start->translatedFormat('M') . ' - ' . $end->translatedFormat('M Y'),
                'start' => $start,
                'end' => $end,
            ]);
        } else {
            // Últimos 6 meses
            for ($i = 5; $i >= 0; $i--) {
                $month = Carbon::now()->subMonths($i);
                $periods->push([
                    'label' => $month->translatedFormat('M Y'),
                    'sublabel' => null,
                    'start' => $month->copy()->startOfMonth(),
                    'end' => $month->copy()->endOfMonth(),
                ]);
            }
        }

        return $periods;
    }

    private function getConversionByInsurer(string $trendPeriod = 'month'): array
    {
        try {
            $periods = $this->buildPeriods($trendPeriod);
            $start = $periods->first()['start'];
            $end = $periods->last()['end'];

            $results = DB::table('quote_options')
                ->join('quotes', 'quotes.id', '=', 'quote_options.quote_id')
                ->join('insurers', 'insurers.id', '=', 'quote_options.insurer_id')
                ->where('insurers.is_active', true)
                ->whereBetween('quotes.created_at', [$start, $end])
                ->select([
                    'insurers.id',
                    'insurers.name',
                    'insurers.logo_path',
                    DB::raw('COUNT(DISTINCT quote_options.quote_id) as quotes_count'),
                    DB::raw('COUNT(DISTINCT CASE WHEN quotes.status = \'' . QuoteStatus::ISSUED->value . '\' THEN quote_options.quote_id END) as concluded_count'),
                ])
                ->groupBy('insurers.id', 'insurers.name', 'insurers.logo_path')
                ->orderByDesc('quotes_count')
                ->limit(10)
                ->get();

            $avgRate = $results->count() > 0
                ? $results->avg(fn ($r) => $r->quotes_count > 0 ? ($r->concluded_count / $r->quotes_count) * 100 : 0)
                : 0;

            return $results->map(function ($row) use ($avgRate) {
                $rate = $row->quotes_count > 0
                    ? round(($row->concluded_count / $row->quotes_count) * 100, 1)
                    : 0;

                return [
                    'id' => $row->id,
                    'name' => $row->name,
                    'logo' => $row->logo_path,
                    'quotes_count' => (int) $row->quotes_count,
                    'concluded_count' => (int) $row->concluded_count,
                    'conversion_rate' => $rate,
                    'is_performing' => $rate >= $avgRate,
                ];
            })->toArray();
        } catch (\Exception $e) {
            return [];
        }
    }

    private function getSystemAlerts(): array
    {
        $alerts = [];

        try {
            // 1. Verificar aseguradoras sin configuración financiera
            $insurersWithoutSettings = Insurer::query()
                ->where('is_active', true)
                ->doesntHave('financialSettings')
                ->count();

            if ($insurersWithoutSettings > 0) {
                $alerts[] = [
                    'id' => 'insurers_without_settings',
                    'type' => 'warning',
                    'title' => 'Aseguradoras sin configuración',
                    'message' => "{$insurersWithoutSettings} aseguradora(s) activa(s) no tienen configuración financiera",
                    'action' => null, // Route doesn't exist yet
                    'priority' => 'high',
                ];
            }

            // 2. Cotizaciones antiguas sin movimiento
            $stalledQuotes = Quote::query()
                ->where('status', QuoteStatus::SENT)
                ->where('updated_at', '<', Carbon::now()->subDays(7))
                ->count();

            if ($stalledQuotes > 0) {
                $alerts[] = [
                    'id' => 'stalled_quotes',
                    'type' => 'info',
                    'title' => 'Cotizaciones estancadas',
                    'message' => "{$stalledQuotes} cotización(es) enviadas sin movimiento en 7+ días",
                    'action' => null,
                    'priority' => 'medium',
                ];
            }
        } catch (\Exception $e) {
            // Silently fail - alerts are not critical
        }

        // Sort by priority
        usort($alerts, function ($a, $b) {
            $priorityOrder = ['high' => 1, 'medium' => 2, 'low' => 3];
            return ($priorityOrder[$a['priority']] ?? 3) <=> ($priorityOrder[$b['priority']] ?? 3);
        });

        return $alerts;
    }

    private function calculateGrowthRate(float $previous, float $current): float
    {
        if ($previous === 0.0) {
            return $current > 0 ? 100.0 : 0.0;
        }

        return round((($current - $previous) / $previous) * 100, 1);
    }
}
