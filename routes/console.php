<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('tenant:set-plan {subdomain} {tier}', function ($subdomain, $tier) {
    $tenant = \App\Models\Tenant::where('subdomain', $subdomain)->first();
    
    if (!$tenant) {
        $this->error("Tenant com o subdomínio '{$subdomain}' não foi encontrado.");
        return;
    }

    $validTiers = ['bronze', 'silver', 'gold'];
    if (!in_array($tier, $validTiers)) {
        $this->error("Plano inválido. Use um dos seguintes: bronze, silver, gold.");
        return;
    }

    $limits = [
        'bronze' => 50,
        'silver' => 100,
        'gold'   => null,
    ];

    $tenant->update([
        'plan_tier'  => $tier,
        'max_users'  => $limits[$tier],
        'expires_at' => now()->addYear(),
    ]);

    $this->info("Tenant '{$tenant->name}' atualizado com sucesso!");
    $this->line("Plano: " . strtoupper($tier) . " | Limite: " . ($limits[$tier] ?? 'Ilimitado'));
})->purpose('Definir ou atualizar o plano de um tenant');

// ─── Reset diário do ambiente de demonstração ──────────────────────────────
Artisan::command('demo:reset', function () {
    $this->info('Iniciando reset do ambiente de demo...');
    $this->call('db:seed', ['--class' => 'DemoSeeder', '--force' => true]);
    $this->info('✅ Ambiente de demo resetado com sucesso!');
})->purpose('Reseta os dados fictícios do ambiente de demonstração');

// Agenda o reset todo dia às 03:00
Schedule::command('demo:reset')->dailyAt('03:00');
