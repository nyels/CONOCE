<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\QuoteController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\Admin\VehicleBrandController;
use App\Http\Controllers\Admin\InsurerController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ContactTypeController;
use App\Http\Controllers\Admin\VehicleTypeController;
use App\Http\Controllers\Admin\CoveragePackageController;
use App\Http\Controllers\Admin\DeductibleOptionController;
use App\Http\Controllers\Admin\PaymentMethodController;
use App\Http\Controllers\Admin\AuditLogController;

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

    // Clientes
    Route::resource('customers', CustomerController::class);
    Route::get('customers/search/ajax', [CustomerController::class, 'search'])->name('customers.search');

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

        // Usuarios y sistema
        Route::resource('users', UserController::class)->except(['create', 'show', 'edit']);
        Route::resource('audit', AuditLogController::class)->only(['index', 'show']);
    });
});
