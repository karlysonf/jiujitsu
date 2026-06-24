<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

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
        'gold' => null,
    ];

    $tenant->update([
        'plan_tier' => $tier,
        'max_users' => $limits[$tier],
        'expires_at' => now()->addYear(), // Define 1 ano de validade por padrão
    ]);

    $this->info("Tenant '{$tenant->name}' atualizado com sucesso!");
    $this->line("Plano: " . strtoupper($tier) . " | Limite: " . ($limits[$tier] ?? 'Ilimitado'));
})->purpose('Definir ou atualizar o plano de um tenant');
