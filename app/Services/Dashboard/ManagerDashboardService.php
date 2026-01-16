<?php
// app/Services/Dashboard/ManagerDashboardService.php
namespace App\Services\Dashboard;

use App\Models\User;
use App\Models\Quote;
use App\Models\Customer;
use App\Models\Contact;
use App\Models\Insurer;
use App\Enums\QuoteStatus;
use App\Enums\ContactType;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use App\Services\Dashboard\DashboardService;
use App\Services\Dashboard\AdminDashboardService;
use App\Services\Dashboard\OperatorDashboardService;

class ManagerDashboardService
{
    private User $user;
    private Carbon $today;
    private Carbon $startOfWeek;
    private Carbon $endOfWeek;
    private Carbon $startOfPreviousWeek;

    public function __construct(User $user)
    {
        $this->user = $user;
        $this->today = Carbon::today();
        $this->startOfWeek = Carbon::now()->startOfWeek();
        $this->endOfWeek = Carbon::now()->endOfWeek();
        $this->startOfPreviousWeek = Carbon::now()->subWeek()->startOfWeek();
    }

    public function getData(): array
    {
        // Obtener los agentes que este manager supervisa
        $agentIds = $this->getManagedAgentIds();

        return [
            // NIVEL 1: COTIZACIONES EN RIESGO + AGENTES CON BACKLOG
            'riskMetrics' => $this->getRiskMetrics($agentIds),

            // NIVEL 2: TABLA COMPARATIVA POR AGENTE + ESTADO POR ASEGURADORA
            'teamPerformance' => $this->getTeamPerformance($agentIds),
            'insurerPerformance' => $this->getInsurerPerformance($agentIds),

            // NIVEL 3: KPIs SEMANALES
            'weeklyKpis' => $this->getWeeklyKpis($agentIds),

            // NIVEL 4: AUDITORÍA DEL EQUIPO
            'teamAudit' => $this->getTeamAudit($agentIds),

            // METADATOS
            'period' => [
                'current_week' => $this->startOfWeek->format('d/m/Y') . ' - ' . $this->endOfWeek->format('d/m/Y'),
                'previous_week' => $this->startOfPreviousWeek->format('d/m/Y') . ' - ' .
                    $this->startOfPreviousWeek->copy()->endOfWeek()->format('d/m/Y'),
            ],
            'team_size' => count($agentIds),
            'timestamp' => now()->toIso8601String(),
        ];
    }

    /**
     * Obtener IDs de los agentes que este manager supervisa
     * Por ahora, asumimos que un manager supervisa todos los agentes
     * (esto se puede ajustar luego con relaciones reales)
     */
    private function getManagedAgentIds(): array
    {
        // TODO: Implementar lógica real de supervisión
        // Por ahora, todos los agentes activos
        return Contact::query()
            ->where('type', ContactType::AGENT)
            ->where('is_active', true)
            ->pluck('id')
            ->toArray();
    }

    private function getRiskMetrics(array $agentIds): array
    {
        // 1. Cotizaciones en riesgo (expiradas o próximas a expirar)
        $expiredQuotes = Quote::query()
            ->whereIn('agent_id', $agentIds)
            ->where('status', QuoteStatus::SENT)
            ->whereDate('quote_valid_until', '<', $this->today)
            ->count();

        $expiringSoonQuotes = Quote::query()
            ->whereIn('agent_id', $agentIds)
            ->where('status', QuoteStatus::SENT)
            ->whereDate('quote_valid_until', '>=', $this->today)
            ->whereDate('quote_valid_until', '<=', $this->today->copy()->addDays(3))
            ->count();

        // 2. Agentes con backlog (más de 5 cotizaciones pendientes)
        $agentsWithBacklog = Contact::query()
            ->whereIn('id', $agentIds)
            ->where('type', ContactType::AGENT)
            ->withCount(['quotes' => function ($query) {
                $query->whereIn('status', [QuoteStatus::DRAFT, QuoteStatus::PENDING])
                    ->whereDate('created_at', '>=', $this->startOfWeek);
            }])
            ->having('quotes_count', '>', 5)
            ->orderByDesc('quotes_count')
            ->limit(5)
            ->get()
            ->map(function ($agent) {
                return [
                    'id' => $agent->id,
                    'name' => $agent->name,
                    'pending_quotes' => $agent->quotes_count,
                    'email' => $agent->email,
                ];
            })
            ->toArray();

        // 3. Cotizaciones con errores (con comentarios de error)
        $errorQuotes = Quote::query()
            ->whereIn('agent_id', $agentIds)
            ->whereNotNull('error_message')
            ->whereDate('updated_at', '>=', $this->startOfWeek)
            ->count();

        return [
            'expired_quotes' => $expiredQuotes,
            'expiring_soon_quotes' => $expiringSoonQuotes,
            'agents_with_backlog' => $agentsWithBacklog,
            'error_quotes' => $errorQuotes,
            'total_risk_items' => $expiredQuotes + $expiringSoonQuotes + count($agentsWithBacklog) + $errorQuotes,
        ];
    }

    private function getTeamPerformance(array $agentIds): array
    {
        $startOfWeek = $this->startOfWeek;
        $endOfWeek = $this->endOfWeek;

        $agents = Contact::query()
            ->whereIn('id', $agentIds)
            ->where('type', ContactType::AGENT)
            ->withCount([
                'quotes as quotes_this_week' => function ($query) use ($startOfWeek, $endOfWeek) {
                    $query->whereBetween('created_at', [$startOfWeek, $endOfWeek]);
                },
                'quotes as sent_this_week' => function ($query) use ($startOfWeek, $endOfWeek) {
                    $query->where('status', QuoteStatus::SENT)
                        ->whereBetween('created_at', [$startOfWeek, $endOfWeek]);
                },
                'quotes as concluded_this_week' => function ($query) use ($startOfWeek, $endOfWeek) {
                    $query->where('status', QuoteStatus::CONCLUDED)
                        ->whereBetween('created_at', [$startOfWeek, $endOfWeek]);
                },
            ])
            ->withSum([
                'quotes as total_premium_this_week' => function ($query) use ($startOfWeek, $endOfWeek) {
                    $query->whereBetween('created_at', [$startOfWeek, $endOfWeek]);
                }
            ], 'total_premium_cents')
            ->orderByDesc('quotes_this_week')
            ->get();

        return $agents->map(function ($agent) {
            $conversionRate = $agent->quotes_this_week > 0
                ? ($agent->concluded_this_week / $agent->quotes_this_week) * 100
                : 0;

            $sentRate = $agent->quotes_this_week > 0
                ? ($agent->sent_this_week / $agent->quotes_this_week) * 100
                : 0;

            return [
                'id' => $agent->id,
                'name' => $agent->name,
                'email' => $agent->email,
                'quotes_count' => $agent->quotes_this_week,
                'sent_count' => $agent->sent_this_week,
                'concluded_count' => $agent->concluded_this_week,
                'total_premium' => $agent->total_premium_this_week / 100,
                'conversion_rate' => round($conversionRate, 1),
                'sent_rate' => round($sentRate, 1),
                'performance' => $this->calculateAgentPerformance($agent),
            ];
        })->toArray();
    }

    private function calculateAgentPerformance($agent): string
    {
        $score = 0;

        // Puntos por volumen
        if ($agent->quotes_this_week >= 20) $score += 3;
        elseif ($agent->quotes_this_week >= 10) $score += 2;
        elseif ($agent->quotes_this_week >= 5) $score += 1;

        // Puntos por conversión
        $conversionRate = $agent->quotes_this_week > 0
            ? ($agent->concluded_this_week / $agent->quotes_this_week) * 100
            : 0;

        if ($conversionRate >= 30) $score += 3;
        elseif ($conversionRate >= 20) $score += 2;
        elseif ($conversionRate >= 10) $score += 1;

        // Clasificación
        if ($score >= 5) return 'high';
        if ($score >= 3) return 'medium';
        return 'low';
    }

    private function getInsurerPerformance(array $agentIds): array
    {
        $startOfWeek = $this->startOfWeek;
        $endOfWeek = $this->endOfWeek;

        $insurers = Insurer::query()
            ->where('is_active', true)
            ->withCount([
                'quotes as quotes_this_week' => function ($query) use ($agentIds, $startOfWeek, $endOfWeek) {
                    $query->whereIn('agent_id', $agentIds)
                        ->whereBetween('created_at', [$startOfWeek, $endOfWeek]);
                },
                'quotes as concluded_this_week' => function ($query) use ($agentIds, $startOfWeek, $endOfWeek) {
                    $query->where('status', QuoteStatus::CONCLUDED)
                        ->whereIn('agent_id', $agentIds)
                        ->whereBetween('created_at', [$startOfWeek, $endOfWeek]);
                },
                'quotes as error_this_week' => function ($query) use ($agentIds, $startOfWeek, $endOfWeek) {
                    $query->whereNotNull('error_message')
                        ->whereIn('agent_id', $agentIds)
                        ->whereBetween('created_at', [$startOfWeek, $endOfWeek]);
                },
            ])
            ->orderByDesc('quotes_this_week')
            ->limit(8)
            ->get();

        return $insurers->map(function ($insurer) {
            $conversionRate = $insurer->quotes_this_week > 0
                ? ($insurer->concluded_this_week / $insurer->quotes_this_week) * 100
                : 0;

            $errorRate = $insurer->quotes_this_week > 0
                ? ($insurer->error_this_week / $insurer->quotes_this_week) * 100
                : 0;

            // Determinar status basado en performance
            $status = 'good';
            if ($errorRate > 10) $status = 'critical';
            elseif ($errorRate > 5) $status = 'warning';
            elseif ($conversionRate < 15) $status = 'needs_attention';

            return [
                'id' => $insurer->id,
                'name' => $insurer->name,
                'logo' => $insurer->logo_url,
                'quotes_count' => $insurer->quotes_this_week,
                'concluded_count' => $insurer->concluded_this_week,
                'error_count' => $insurer->error_this_week,
                'conversion_rate' => round($conversionRate, 1),
                'error_rate' => round($errorRate, 1),
                'status' => $status,
                'response_time' => $this->calculateInsurerResponseTime($insurer->id), // Simulado por ahora
            ];
        })->toArray();
    }

    private function calculateInsurerResponseTime(int $insurerId): int
    {
        // TODO: Implementar lógica real de tiempo de respuesta
        // Por ahora, valores simulados basados en el ID
        $baseTime = [45, 30, 60, 25, 90, 35, 50, 40];
        return $baseTime[$insurerId % count($baseTime)];
    }

    private function getWeeklyKpis(array $agentIds): array
    {
        $startOfWeek = $this->startOfWeek;
        $endOfWeek = $this->endOfWeek;
        $startOfPreviousWeek = $this->startOfPreviousWeek;
        $endOfPreviousWeek = $startOfPreviousWeek->copy()->endOfWeek();

        // Datos semana actual
        $currentWeekData = Quote::query()
            ->whereIn('agent_id', $agentIds)
            ->whereBetween('created_at', [$startOfWeek, $endOfWeek])
            ->select([
                DB::raw('COUNT(*) as quote_count'),
                DB::raw('SUM(total_premium_cents) / 100 as total_premium'),
                DB::raw('SUM(policy_fee_cents) / 100 as total_policy_fee'),
                DB::raw('COUNT(CASE WHEN status = "' . QuoteStatus::SENT->value . '" THEN 1 END) as sent_count'),
                DB::raw('COUNT(CASE WHEN status = "' . QuoteStatus::CONCLUDED->value . '" THEN 1 END) as concluded_count'),
                DB::raw('AVG(total_premium_cents) / 100 as avg_premium'),
            ])
            ->first();

        // Datos semana anterior
        $previousWeekData = Quote::query()
            ->whereIn('agent_id', $agentIds)
            ->whereBetween('created_at', [$startOfPreviousWeek, $endOfPreviousWeek])
            ->select([
                DB::raw('COUNT(*) as quote_count'),
                DB::raw('SUM(total_premium_cents) / 100 as total_premium'),
                DB::raw('SUM(policy_fee_cents) / 100 as total_policy_fee'),
                DB::raw('COUNT(CASE WHEN status = "' . QuoteStatus::SENT->value . '" THEN 1 END) as sent_count'),
                DB::raw('COUNT(CASE WHEN status = "' . QuoteStatus::CONCLUDED->value . '" THEN 1 END) as concluded_count'),
                DB::raw('AVG(total_premium_cents) / 100 as avg_premium'),
            ])
            ->first();

        // Calcular tasas
        $conversionRateCurrent = $currentWeekData->quote_count > 0
            ? ($currentWeekData->concluded_count / $currentWeekData->quote_count) * 100
            : 0;

        $sentRateCurrent = $currentWeekData->quote_count > 0
            ? ($currentWeekData->sent_count / $currentWeekData->quote_count) * 100
            : 0;

        $conversionRatePrevious = $previousWeekData->quote_count > 0
            ? ($previousWeekData->concluded_count / $previousWeekData->quote_count) * 100
            : 0;

        return [
            'current_week' => [
                'quote_count' => (int) $currentWeekData->quote_count,
                'total_premium' => (float) ($currentWeekData->total_premium ?? 0),
                'total_policy_fee' => (float) ($currentWeekData->total_policy_fee ?? 0),
                'sent_count' => (int) $currentWeekData->sent_count,
                'concluded_count' => (int) $currentWeekData->concluded_count,
                'avg_premium' => (float) ($currentWeekData->avg_premium ?? 0),
                'conversion_rate' => round($conversionRateCurrent, 1),
                'sent_rate' => round($sentRateCurrent, 1),
            ],
            'previous_week' => [
                'quote_count' => (int) $previousWeekData->quote_count,
                'total_premium' => (float) ($previousWeekData->total_premium ?? 0),
                'total_policy_fee' => (float) ($previousWeekData->total_policy_fee ?? 0),
                'sent_count' => (int) $previousWeekData->sent_count,
                'concluded_count' => (int) $previousWeekData->concluded_count,
                'avg_premium' => (float) ($previousWeekData->avg_premium ?? 0),
                'conversion_rate' => round($conversionRatePrevious, 1),
            ],
            'growth' => [
                'quote_count' => $this->calculateGrowthRate(
                    $previousWeekData->quote_count,
                    $currentWeekData->quote_count
                ),
                'total_premium' => $this->calculateGrowthRate(
                    $previousWeekData->total_premium,
                    $currentWeekData->total_premium
                ),
                'conversion_rate' => $this->calculateGrowthRate(
                    $conversionRatePrevious,
                    $conversionRateCurrent
                ),
            ],
        ];
    }

    private function getTeamAudit(array $agentIds): array
    {
        // Últimas 10 actividades del equipo
        $recentActivities = DB::table('activity_log')
            ->whereIn('causer_id', $agentIds)
            ->where('causer_type', Contact::class)
            ->orderByDesc('created_at')
            ->limit(10)
            ->get()
            ->map(function ($log) {
                return [
                    'id' => $log->id,
                    'description' => $log->description,
                    'subject_type' => $log->subject_type,
                    'subject_id' => $log->subject_id,
                    'properties' => json_decode($log->properties, true),
                    'created_at' => $log->created_at,
                    'agent_name' => Contact::find($log->causer_id)->name ?? 'Desconocido',
                ];
            })
            ->toArray();

        // Actividades más comunes esta semana
        $topActivities = DB::table('activity_log')
            ->whereIn('causer_id', $agentIds)
            ->where('causer_type', Contact::class)
            ->whereDate('created_at', '>=', $this->startOfWeek)
            ->select('description', DB::raw('COUNT(*) as count'))
            ->groupBy('description')
            ->orderByDesc('count')
            ->limit(5)
            ->get()
            ->toArray();

        return [
            'recent_activities' => $recentActivities,
            'top_activities' => $topActivities,
            'activity_summary' => [
                'total_this_week' => DB::table('activity_log')
                    ->whereIn('causer_id', $agentIds)
                    ->where('causer_type', Contact::class)
                    ->whereDate('created_at', '>=', $this->startOfWeek)
                    ->count(),
                'by_day' => $this->getActivityByDay($agentIds),
            ],
        ];
    }

    private function getActivityByDay(array $agentIds): array
    {
        $days = [];
        for ($i = 6; $i >= 0; $i--) {
            $day = Carbon::now()->subDays($i);
            $count = DB::table('activity_log')
                ->whereIn('causer_id', $agentIds)
                ->where('causer_type', Contact::class)
                ->whereDate('created_at', $day->toDateString())
                ->count();

            $days[] = [
                'day' => $day->format('D'),
                'date' => $day->format('d/m'),
                'count' => $count,
            ];
        }

        return $days;
    }

    private function calculateGrowthRate(float $previous, float $current): float
    {
        if ($previous === 0.0) {
            return $current > 0 ? 100.0 : 0.0;
        }

        return round((($current - $previous) / $previous) * 100, 1);
    }
}
