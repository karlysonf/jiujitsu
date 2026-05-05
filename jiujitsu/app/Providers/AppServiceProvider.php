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
            return $user->hasAnyRole(['admin', 'professor']);
        });

        // Users management
        Gate::define('manage-users', function ($user) {
            return $user->hasAnyRole(['admin', 'professor']);
        });

        // Financial management
        Gate::define('manage-finance', function ($user) {
            return $user->hasRole('admin');
        });

        // Attendance management
        Gate::define('manage-attendance', function ($user) {
            return $user->hasAnyRole(['admin', 'professor']);
        });

        // Plans management
        Gate::define('manage-plans', function ($user) {
            return $user->hasRole('admin');
        });

        // Reports access
        Gate::define('view-reports', function ($user) {
            return $user->hasRole('admin');
        });

    }

}
