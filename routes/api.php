<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Src\Infrastructure\Http\Controllers\QuoteController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// AGREGA ESTA LÍNEA AL FINAL:
Route::post('/quotes', [QuoteController::class, 'store']);
Route::get('/quotes/{uuid}/pdf', [QuoteController::class, 'downloadPdf']);
