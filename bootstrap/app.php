<?php

use App\Exceptions\InactiveTenantException;
use App\Http\Middleware\CheckApiToken;
use App\Http\Middleware\EnsureTenantMiddleware;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Exceptions\InvalidCredentialException;
use App\Exceptions\InactiveUserException;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        //
        $middleware->alias([
            'tenant.isactive' => EnsureTenantMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(fn(InvalidCredentialException $e, Request $request) => $e->render($request));
        $exceptions->render(fn(InActiveUserException $e, Request $request) => $e->render($request));
        $exceptions->render(fn(InactiveTenantException $e, Request $request) => $e->render($request));
    })->create();
