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
    public function login(Request $request)
    {
        $demoDbPath = storage_path('app/demo.sqlite');
        $needsInit = false;

        // Verifica se precisamos inicializar o banco e o cookie
        if (!$request->cookies->has('demo_mode_raw')) {
            $needsInit = true;
        } elseif (!file_exists($demoDbPath)) {
            $needsInit = true;
        }

        if ($needsInit) {
            if (!file_exists($demoDbPath)) {
                touch($demoDbPath);
            }
            
            DB::setDefaultConnection('demo');
            app('db')->setDefaultConnection('demo');
            
            if (!Schema::connection('demo')->hasTable('users')) {
                Artisan::call('migrate', ['--database' => 'demo', '--force' => true]);
                Artisan::call('db:seed', ['--class' => 'DemoSeeder', '--force' => true]);
            }

            $secret    = config('app.key');
            $payload   = 'active';
            $signature = hash_hmac('sha256', $payload, $secret);
            $cookieValue = "{$payload}|{$signature}";

            // Redireciona para si mesmo para forçar os middlewares (SetDemoConnection e StartSession)
            // a rodarem com o banco SQLite JÁ CRIADO e o cookie JÁ PRESENTE.
            return redirect()->route('demo.login')
                ->withCookie(cookie('demo_mode_raw', $cookieValue, 480, '/', null, false, false));
        }

        // =========================================================
        // Se chegou aqui, o cookie JÁ EXISTE e o BANCO JÁ EXISTE.
        // O middleware SetDemoConnection já trocou a conexão para 'demo',
        // e o StartSession já instanciou o driver de sessão apontando pro SQLite!
        // =========================================================

        Auth::logout();
        session()->invalidate();
        session()->regenerateToken();

        $demoUser = \App\Models\User::on('demo')->where('email', 'demo@gestao.com')->first();
        
        if (!$demoUser) {
            // Fallback de segurança se algo der errado
            DB::setDefaultConnection(config('database.default') === 'demo' ? env('DB_CONNECTION', 'pgsql') : config('database.default'));
            return redirect()->route('demo.landing')->with('error', 'Ambiente de demonstração temporariamente indisponível.');
        }

        // Faz login. Como a sessão está apontando pro SQLite, vai salvar lá.
        Auth::login($demoUser);

        return redirect()->route('dashboard');
    }

    /**
     * Reseta os dados do banco SQLite de demonstração.
     * Pode ser chamado via cron (demo:reset) ou manualmente com a chave correta.
     */
    public function reset(Request $request)
    {
        $secret = config('app.demo_reset_secret');
        if (empty($secret) || $request->get('secret') !== $secret) {
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
