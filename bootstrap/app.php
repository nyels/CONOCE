<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        channels: __DIR__.'/../routes/channels.php',
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->trustProxies(at: '*');
        $middleware->web(append: [
            \App\Http\Middleware\HandleInertiaRequests::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {

        // Loguear errores de validación (422)
        $exceptions->renderable(function (ValidationException $e, Request $request) {
            Log::warning('Validación fallida', [
                'url' => $request->fullUrl(),
                'method' => $request->method(),
                'user_id' => $request->user()?->id,
                'errors' => $e->errors(),
                'input' => collect($request->except(['password', 'password_confirmation', '_token']))
                    ->map(fn($v) => is_string($v) && mb_strlen($v) > 100 ? mb_substr($v, 0, 100) . '...' : $v)
                    ->toArray(),
            ]);

            return null; // Dejar que Laravel maneje la respuesta normal
        });

        // Loguear errores del servidor (500) con contexto completo
        $exceptions->renderable(function (Throwable $e, Request $request) {
            if ($e instanceof ValidationException) {
                return null;
            }

            Log::error('Excepción en request', [
                'url' => $request->fullUrl(),
                'method' => $request->method(),
                'user_id' => $request->user()?->id,
                'exception' => get_class($e),
                'message' => $e->getMessage(),
                'file' => $e->getFile() . ':' . $e->getLine(),
                'input' => collect($request->except(['password', 'password_confirmation', '_token']))
                    ->map(fn($v) => is_string($v) && mb_strlen($v) > 100 ? mb_substr($v, 0, 100) . '...' : $v)
                    ->toArray(),
            ]);

            return null; // Dejar que Laravel maneje la respuesta normal
        });

    })->create();
