<?php

use App\Http\Middleware\EnsureOtp;
use App\Http\Middleware\EnsureOtpMiddleware;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Route;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
        then: function ($router) {
            Route::prefix('apis')
                ->middleware('api')
                ->name('apis')
                ->group(base_path('routes/api.php'));
        }
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->trustProxies(at: '*');
        // $middleware->append(EnsureOtpMiddleware::class);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();