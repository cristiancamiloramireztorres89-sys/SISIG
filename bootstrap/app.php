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
        $middleware->validateCsrfTokens(except: [
            'modules/*/canvas',
            'modules/upload-media',
            'modules/media',
            'modules/media/*',
            'sisig/modules/*/canvas',
            'sisig/modules/upload-media',
            'sisig/modules/media',
            'sisig/modules/media/*',
            'sisig/editor/modules/*/canvas',
            'sisig/editor/modules/upload-media',
            'sisig/editor/modules/media',
            'sisig/editor/modules/media/*',
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
