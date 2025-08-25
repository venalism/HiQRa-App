<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\AdminMiddleware;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'admin' => AdminMiddleware::class,
            'auth.multi' => \App\Http\Middleware\RedirectIfNotAuthenticated::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->render(function (AuthenticationException $e, Request $request) {
            if ($request->is('admin') || $request->is('admin/*')) {
                return redirect()->guest(route('admin.login'));
            }
            if ($request->is('panitia') || $request->is('panitia/*')) {
                return redirect()->guest(route('panitia.login'));
            }
            if ($request->is('peserta') || $request->is('peserta/*')) {
                return redirect()->guest(route('peserta.login'));
            }
            
            // Fallback untuk guard default jika diperlukan
            $guard = Arr::get($e->guards(), 0);
            switch ($guard) {
                case 'panitia':
                    return redirect()->guest(route('panitia.login'));
                case 'peserta':
                    return redirect()->guest(route('peserta.login'));
                default:
                    return redirect()->guest(route('admin.login'));
            }
        });
    })->create();