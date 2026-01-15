<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;

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

    // TODO: Agregar rutas de módulos
    // Route::resource('quotes', QuoteController::class);
    // Route::resource('customers', CustomerController::class);
    // Route::resource('contacts', ContactController::class);
    // Route::resource('insurers', InsurerController::class);

    // Rutas de administración (solo admin)
    Route::middleware(['can:users.view'])->prefix('admin')->name('admin.')->group(function () {
        // Route::resource('users', UserController::class);
        // Route::get('settings', [SettingController::class, 'index'])->name('settings.index');
    });
});
