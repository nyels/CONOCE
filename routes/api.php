<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CalculationController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// ==========================================
// ENDPOINTS LEGACY — Cálculos financieros
// Usa CanonicalFinancialService como única fuente de verdad
// ==========================================

// Cálculo de prima neta
// Request: { prima_anual_neta, forma_pago, insurer_id }
// Response: { prima_neta, derecho_costo, recargo, error? }
Route::post('/quotes/calculate-premium', [CalculationController::class, 'calculateNetPremium']);

// Cálculo de subsecuentes
// Request: { prima_total_anual, primer_pago, forma_pago }
// Response: { subsecuentes, numero_pagos }
Route::post('/quotes/calculate-subsequent', [CalculationController::class, 'calculateSubsequent']);
