<?php
// app/Http/Controllers/DashboardController.php
namespace App\Http\Controllers;

use App\Services\Dashboard\AdminDashboardService;
use App\Services\Dashboard\OperatorDashboardService;
use App\Services\Dashboard\ManagerDashboardService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function __construct(
        private AdminDashboardService $adminService,
    ) {}

    public function index(Request $request)
    {
        $user = $request->user();

        // For now, default to Admin dashboard for all authenticated users
        // TODO: Implement role-based routing when spatie/permission is configured

        $trendPeriod = $request->input('trend_period', 'month');
        if (!in_array($trendPeriod, ['month', 'quarter', 'year'])) {
            $trendPeriod = 'month';
        }

        try {
            $dashboardData = $this->adminService->getData($trendPeriod);
        } catch (\Exception $e) {
            $dashboardData = [
                'financialKpis' => [],
                'trends' => ['data' => [], 'periodType' => $trendPeriod, 'summary' => ['growth_quotes' => 0, 'growth_premium' => 0]],
                'conversionByInsurer' => [],
                'systemAlerts' => [],
                'period' => ['current' => now()->format('F Y'), 'previous' => now()->subMonth()->format('F Y')],
                'timestamp' => now()->toIso8601String(),
            ];
        }

        return Inertia::render('Dashboard/Admin', [
            'dashboardData' => $dashboardData,
            'userRole' => 'admin'
        ]);

        /*
        // FUTURE: Role-based routing
        if ($user->hasRole(['super_admin', 'admin'])) {
            return Inertia::render('Dashboard/Admin', [
                'dashboardData' => $this->adminService->getData(),
                'userRole' => 'admin'
            ]);
        }

        if ($user->hasRole('manager')) {
            return Inertia::render('Dashboard/Manager', [
                'dashboardData' => $this->managerService->getData(),
                'userRole' => 'manager'
            ]);
        }

        if ($user->hasRole('operator')) {
            return Inertia::render('Dashboard/Operator', [
                'dashboardData' => $this->operatorService->getData(),
                'userRole' => 'operator'
            ]);
        }

        abort(403, 'No tienes permisos para acceder al dashboard');
        */
    }
}
