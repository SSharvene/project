<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // route middleware aliases (used in routes ->middleware([...]))
        $middleware->alias([
    'auth'     => \Illuminate\Auth\Middleware\Authenticate::class,
    'guest'    => \App\Http\Middleware\RedirectIfAuthenticated::class,
    'verified' => \Illuminate\Auth\Middleware\EnsureEmailIsVerified::class,
    'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class,
    'role'     => \App\Http\Middleware\RoleMiddleware::class,
]);


        // the 'web' middleware group (common stack for web routes)
        $middleware->web(append: [
            \Illuminate\Cookie\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class, // <-- use framework class
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
