<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        // Confia em todos os proxies reversos (necessário para Railway, Heroku, etc.)
        // Isso garante que o Laravel reconheça HTTPS mesmo com SSL terminado no proxy.
        $middleware->trustProxies(at: '*', headers: Request::HEADER_X_FORWARDED_FOR |
            Request::HEADER_X_FORWARDED_HOST |
            Request::HEADER_X_FORWARDED_PORT |
            Request::HEADER_X_FORWARDED_PROTO |
            Request::HEADER_X_FORWARDED_AWS_ELB);

        // SetDemoConnection precisa rodar ANTES de StartSession e do auth guard.
        // Usa cookie HMAC assinado (não criptografado) para detectar o modo demo
        // sem depender da sessão ou do EncryptCookies.
        $middleware->web(prepend: [
            \App\Http\Middleware\SetDemoConnection::class,
        ]);

        // Exclui o cookie de flag de demo da criptografia do Laravel
        // (ele é assinado com HMAC, não precisa de criptografia simétrica)
        $middleware->encryptCookies(except: ['demo_mode_raw']);

        // ResolveTenant roda depois da sessão, usando a conexão já trocada se for demo
        $middleware->web(append: [
            \App\Http\Middleware\ResolveTenant::class,
        ]);

        // Ignora verificação CSRF no webhook do Asaas
        $middleware->validateCsrfTokens(except: [
            'webhooks/asaas',
            'webhooks/asaas/*',
        ]);

        $middleware->alias([
            'role' => \Spatie\Permission\Middleware\RoleMiddleware::class,
            'permission' => \Spatie\Permission\Middleware\PermissionMiddleware::class,
            'role_or_permission' => \Spatie\Permission\Middleware\RoleOrPermissionMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
