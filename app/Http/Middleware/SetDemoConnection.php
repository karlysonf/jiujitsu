<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

/**
 * Detecta modo demo via cookie e troca a conexão de banco para o SQLite isolado.
 *
 * IMPORTANTE: Este middleware roda com PREPEND para executar antes de StartSession.
 * Por isso, não podemos usar session() aqui. Usamos um cookie com HMAC assinado
 * manualmente para autenticar o flag sem depender do EncryptCookies middleware.
 *
 * O cookie 'demo_mode_raw' é setado como texto puro (não criptografado pelo Laravel),
 * com um HMAC SHA-256 para impedir falsificação.
 */
class SetDemoConnection
{
    public function handle(Request $request, Closure $next): Response
    {
        if ($this->isDemoRequest($request)) {
            DB::setDefaultConnection('demo');
            app('db')->setDefaultConnection('demo');
        }

        return $next($request);
    }

    private function isDemoRequest(Request $request): bool
    {
        $cookie = $request->cookies->get('demo_mode_raw');
        if (!$cookie) {
            return false;
        }

        // Se o arquivo SQLite sumiu (ex: novo deploy na Railway), invalidamos o cookie
        // para não quebrar a leitura de sessão (a tabela sessions não existiria).
        if (!file_exists(storage_path('app/demo.sqlite'))) {
            return false;
        }

        // Formato: "active|HMAC"
        $parts = explode('|', $cookie, 2);
        if (count($parts) !== 2) {
            return false;
        }

        [$payload, $signature] = $parts;

        $secret = config('app.key');
        $expected = hash_hmac('sha256', $payload, $secret);

        return $payload === 'active' && hash_equals($expected, $signature);
    }
}
