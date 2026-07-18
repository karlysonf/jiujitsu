<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Artisan;
use App\Models\Tenant;

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
     * Realiza login automático no ambiente de demo.
     */
    public function login()
    {
        $demoUser = \App\Models\User::where('email', 'demo@gestao.com')->first();

        if (!$demoUser) {
            // Popula o ambiente se ainda não existir
            Artisan::call('db:seed', ['--class' => 'DemoSeeder', '--force' => true]);
            $demoUser = \App\Models\User::where('email', 'demo@gestao.com')->first();
        }

        if (!$demoUser) {
            return back()->withErrors(['demo' => 'Ambiente de demo ainda não está disponível. Tente novamente em instantes.']);
        }

        Auth::login($demoUser);

        // Bind tenant para o ambiente de demo
        $tenant = Tenant::where('subdomain', 'demo')->first();
        if ($tenant) {
            app()->instance('currentTenant', $tenant);
            session(['tenant_id' => $tenant->id]);
        }

        return redirect()->route('dashboard')->with('demo_mode', true);
    }

    /**
     * Reseta os dados do ambiente de demo (para uso via cron ou rota protegida).
     */
    public function reset(Request $request)
    {
        // Só permite se chamado com chave de API ou via CLI
        $secret = config('app.demo_reset_secret');
        if ($secret && $request->get('secret') !== $secret) {
            abort(403);
        }

        Artisan::call('db:seed', ['--class' => 'DemoSeeder', '--force' => true]);

        return response()->json(['status' => 'ok', 'message' => 'Ambiente de demo resetado com sucesso!']);
    }
}
