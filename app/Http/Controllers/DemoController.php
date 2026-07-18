<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class DemoController extends Controller
{
    /**
     * Landing page pública de demonstração.
     */
    public function landing()
    {
        return view('demo.landing');
    }

    /**
     * Realiza login automático no ambiente de demo com banco SQLite isolado.
     */
    public function login()
    {
        // 1. Garante que o arquivo demo.sqlite existe
        $demoDbPath = storage_path('app/demo.sqlite');
        if (!file_exists($demoDbPath)) {
            touch($demoDbPath);
        }

        // 2. Ativa a conexão demo ANTES de qualquer operação de banco
        DB::setDefaultConnection('demo');
        app('db')->setDefaultConnection('demo');

        // 3. Roda migrations no banco demo se ainda não foram aplicadas
        if (!Schema::connection('demo')->hasTable('users')) {
            Artisan::call('migrate', [
                '--database' => 'demo',
                '--force'    => true,
            ]);
        }

        // 4. Roda o seeder de demo se o usuário demo ainda não existe
        $demoUser = \App\Models\User::on('demo')->where('email', 'demo@gestao.com')->first();
        if (!$demoUser) {
            Artisan::call('db:seed', [
                '--class' => 'DemoSeeder',
                '--force' => true,
            ]);
            $demoUser = \App\Models\User::on('demo')->where('email', 'demo@gestao.com')->first();
        }

        if (!$demoUser) {
            // Volta para a conexão real em caso de falha
            DB::setDefaultConnection(config('database.default') === 'demo'
                ? env('DB_CONNECTION', 'pgsql')
                : config('database.default'));

            return redirect()->route('demo.landing')
                ->with('error', 'Ambiente de demonstração temporariamente indisponível.');
        }

        // 5. Faz login com o usuário demo
        Auth::login($demoUser);

        // 6. Marca a sessão como demo para que o middleware ative o SQLite em cada request
        session(['is_demo_session' => true]);

        return redirect()->route('dashboard');
    }

    /**
     * Reseta os dados do banco SQLite de demonstração.
     * Pode ser chamado via cron (demo:reset) ou manualmente com a chave correta.
     */
    public function reset(Request $request)
    {
        $secret = config('app.demo_reset_secret');
        if ($secret && $request->get('secret') !== $secret) {
            abort(403);
        }

        // Ativa conexão demo antes de rodar o seeder
        DB::setDefaultConnection('demo');
        app('db')->setDefaultConnection('demo');

        Artisan::call('db:seed', [
            '--class' => 'DemoSeeder',
            '--force' => true,
        ]);

        return response()->json(['status' => 'ok', 'message' => 'Ambiente de demo resetado com sucesso!']);
    }
}
