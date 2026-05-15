<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'role' => \App\Http\Middleware\CheckRole::class,
            'subscription' => \App\Http\Middleware\CheckSubscription::class,
            'payment.webhook' => \App\Http\Middleware\VerifyPaymentWebhook::class,
            'throttle' => \App\Http\Middleware\ApiRateLimiter::class,
        ]);
        $middleware->append(\App\Http\Middleware\SecurityHeaders::class);
        $middleware->web(append: [
            \App\Http\Middleware\LogAdminActivity::class,
        ]);
        $middleware->api(prepend: [
            \App\Http\Middleware\ApiRateLimiter::class,
        ]);

        $middleware->validateCsrfTokens(except: [
            'api/webhooks/*',
            'webhooks/*',
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
