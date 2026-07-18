<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\Response;

/**
 * Troca silenciosamente a conexão padrão do banco de dados para o SQLite de demo
 * quando o usuário atual está em modo de demonstração.
 *
 * Isso garante isolamento total: nenhuma operação do usuário demo toca o banco de produção.
 */
class SetDemoConnection
{
    public function handle(Request $request, Closure $next): Response
    {
        // Verifica via sessão se é uma sessão de demo
        if (session('is_demo_session') === true) {
            // Troca a conexão padrão para o SQLite isolado
            DB::setDefaultConnection('demo');

            // Garante que o Spatie Permission também use a conexão correta
            app('db')->setDefaultConnection('demo');
        }

        return $next($request);
    }
}
