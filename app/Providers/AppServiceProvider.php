<?php
// app/Providers/AppServiceProvider.php
namespace App\Providers;

use App\Services\Dashboard\AdminDashboardService;
use App\Services\Dashboard\OperatorDashboardService;
use App\Services\Dashboard\ManagerDashboardService;
use Illuminate\Support\ServiceProvider;
use Src\Domain\Financial\Contracts\FinancialCalculator;
use Src\Domain\Financial\Services\CanonicalFinancialService;

use Illuminate\Support\Facades\Event;
use Illuminate\Auth\Events\Login;
use App\Listeners\LogSuccessfulLogin;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Singleton para AdminDashboardService (sin usuario específico)
        $this->app->singleton(AdminDashboardService::class, function ($app) {
            return new AdminDashboardService();
        });

        // Singleton para OperatorDashboardService (con usuario autenticado)
        $this->app->singleton(OperatorDashboardService::class, function ($app) {
            return new OperatorDashboardService($app->make('auth')->user());
        });

        // Singleton para ManagerDashboardService (con usuario autenticado)
        $this->app->singleton(ManagerDashboardService::class, function ($app) {
            return new ManagerDashboardService($app->make('auth')->user());
        });

        // Servicio financiero canónico ÚNICO
        $this->app->singleton(FinancialCalculator::class, CanonicalFinancialService::class);
    }

    public function boot(): void
    {
        Event::listen(
            Login::class,
            LogSuccessfulLogin::class,
        );
    }
}
