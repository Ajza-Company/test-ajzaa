<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        channels: __DIR__.'/../routes/channels.php',
        health: '/up',
        then: function () {
           Route::middleware('api')
                ->prefix('api/frontend')
                ->group(base_path('routes/frontend.php'));
            Route::middleware('api')
                ->prefix('api/supplier')
                ->group(base_path('routes/supplier.php'));
            Route::middleware('api')
                ->prefix('api/general')
                ->group(base_path('routes/general.php'));
            Route::middleware('api')
                ->prefix('api/admin')
                ->group(base_path('routes/admin.php'));
        },
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Configure CORS for API routes
        $middleware->api(prepend: [
            \Illuminate\Http\Middleware\HandleCors::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
