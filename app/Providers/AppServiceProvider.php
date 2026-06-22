<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL; // <--- 1. IMPORTANTE: Adicione esta linha
use Illuminate\Support\Facades\Gate;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // 2. FORÇAR HTTPS EM PRODUÇÃO
        if (config('app.env') !== 'local') {
            URL::forceScheme('https');
        }

        // --- Suas Gates Existentes Abaixo ---

        // Super Admin (root) bypass
        Gate::before(function ($user, $ability) {
            return $user->hasRole('root') ? true : null;
        });

        // Dashboard access
        Gate::define('view-dashboard', function ($user) {
            return $user->hasAnyRole(['admin', 'professor', 'instrutor']);
        });

        // Users management
        Gate::define('manage-users', function ($user, $targetUser = null) {
            if ($user->hasAnyRole(['admin', 'root'])) {
                return true;
            }

            if ($user->hasAnyRole(['professor', 'instrutor'])) {
                // Se houver um usuário alvo, só permite se o alvo for aluno
                if ($targetUser instanceof \App\Models\User) {
                    return $targetUser->hasRole('aluno');
                }
                // Se não houver alvo (index/create), permite o acesso à listagem/formulário
                return true;
            }

            return false;
        });

        // Financial management
        Gate::define('manage-finance', function ($user) {
            return $user->hasRole('admin');
        });

        // Attendance management
        Gate::define('manage-attendance', function ($user) {
            return $user->hasAnyRole(['admin', 'professor', 'instrutor']);
        });

        // Plans management
        Gate::define('manage-plans', function ($user) {
            return $user->hasRole('admin');
        });

        // Reports access
        Gate::define('view-reports', function ($user) {
            return $user->hasRole('admin');
        });

        // Settings management
        Gate::define('manage-settings', function ($user) {
            return $user->hasRole('admin');
        });

    }

}
