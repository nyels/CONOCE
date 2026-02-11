<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\QuoteController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\Admin\VehicleBrandController;
use App\Http\Controllers\Admin\InsurerController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ContactTypeController;
use App\Http\Controllers\Admin\VehicleTypeController;
use App\Http\Controllers\Admin\CoveragePackageController;
use App\Http\Controllers\Admin\DeductibleOptionController;
use App\Http\Controllers\Admin\PaymentMethodController;
use App\Http\Controllers\Admin\AuditLogController;
use App\Http\Controllers\Admin\PositionController;
use App\Http\Controllers\Admin\StaffController;
use App\Http\Controllers\Admin\PolicyFeeController;
use App\Http\Controllers\Admin\SurchargeController;
use App\Http\Controllers\Admin\MexicanStateController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Sistema de Cotización de Seguros Automotrices
|
*/

// Ruta pública - Redirige a login o dashboard
Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('dashboard');
    }
    return redirect()->route('login');
});

// Rutas protegidas por autenticación
Route::middleware(['auth', 'verified'])->group(function () {

    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Cotizaciones
    Route::resource('quotes', QuoteController::class);
    Route::get('quotes/search/customers', [QuoteController::class, 'searchCustomers'])->name('quotes.search-customers');
    Route::post('quotes/calculate-premiums', [QuoteController::class, 'calculatePremiums'])->name('quotes.calculate-premiums');
    Route::post('quotes/{quote}/send', [QuoteController::class, 'send'])->name('quotes.send');
    Route::get('quotes/{quote}/pdf', [QuoteController::class, 'generatePdf'])->name('quotes.pdf');
    Route::get('quotes/{quote}/pdf/preview', [QuoteController::class, 'previewPdf'])->name('quotes.pdf-preview');
    Route::post('quotes/preview-draft', [QuoteController::class, 'previewDraft'])->name('quotes.preview-draft');

    // API endpoints para cotizaciones - Backend Autoritativo
    Route::get('api/insurers/{insurer}/financial-settings', [QuoteController::class, 'getFinancialSettings'])
        ->name('api.insurers.financial-settings');
    Route::post('api/quotes/calculate-realtime', [QuoteController::class, 'calculateRealtime'])
        ->name('api.quotes.calculate-realtime')
        ->middleware('throttle:60,1');
    Route::post('api/quotes/calculate-batch', [QuoteController::class, 'calculateBatch'])
        ->name('api.quotes.calculate-batch')
        ->middleware('throttle:30,1');

    // Clientes
    Route::resource('customers', CustomerController::class);
    Route::get('customers/search/ajax', [CustomerController::class, 'search'])->name('customers.search');

    // Contactos/Intermediarios
    Route::resource('contacts', ContactController::class);
    Route::get('contacts/search/ajax', [ContactController::class, 'search'])->name('contacts.search');
    Route::patch('contacts/{contact}/toggle-active', [ContactController::class, 'toggleActive'])->name('contacts.toggle-active');

    // Rutas de administración
    Route::prefix('admin')->name('admin.')->group(function () {
        // Catálogos con logo
        Route::resource('vehicle-brands', VehicleBrandController::class)->except(['create', 'show', 'edit']);
        Route::resource('insurers', InsurerController::class)->except(['create', 'show', 'edit']);

        // Catálogos simples
        Route::resource('contact-types', ContactTypeController::class)->except(['create', 'show', 'edit']);
        Route::resource('vehicle-types', VehicleTypeController::class)->except(['create', 'show', 'edit']);
        Route::resource('coverage-packages', CoveragePackageController::class)->except(['create', 'show', 'edit']);
        Route::resource('deductible-options', DeductibleOptionController::class)->except(['create', 'show', 'edit']);
        Route::resource('payment-methods', PaymentMethodController::class)->except(['create', 'show', 'edit']);
        Route::resource('policy-fees', PolicyFeeController::class)->except(['create', 'show', 'edit']);
        Route::get('policy-fees/{insurer}/history', [PolicyFeeController::class, 'history'])->name('policy-fees.history');
        Route::resource('surcharges', SurchargeController::class)->except(['create', 'show', 'edit']);
        Route::get('surcharges/{insurer}/history', [SurchargeController::class, 'history'])->name('surcharges.history');
        Route::resource('mexican-states', MexicanStateController::class)->except(['create', 'show', 'edit']);

        // Puestos
        Route::resource('positions', PositionController::class)->except(['create', 'show', 'edit']);

        // Personal
        Route::resource('staff', StaffController::class)->except(['create']);
        Route::get('staff/search/ajax', [StaffController::class, 'search'])->name('staff.search');
        Route::patch('staff/{staff}/toggle-active', [StaffController::class, 'toggleActive'])->name('staff.toggle-active');

        // Usuarios y sistema
        Route::resource('users', UserController::class)->except(['create', 'show', 'edit']);
        Route::patch('users/{user}/toggle-active', [UserController::class, 'toggleActive'])->name('users.toggle-active');
        Route::post('users/{user}/force-password-change', [UserController::class, 'forcePasswordChange'])->name('users.force-password-change');
        Route::post('users/{user}/reset-failed-logins', [UserController::class, 'resetFailedLogins'])->name('users.reset-failed-logins');

        Route::resource('audit', AuditLogController::class)->only(['index', 'show']);
    });
});
