<?php

namespace App\Providers;

use App\Models\Tenant;
use Illuminate\Support\ServiceProvider;

class TenantServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        // Register a shorter helper string key 'currentTenantInstance' if desired
        $this->app->bind('currentTenantInstance', function ($app) {
            return Tenant::current();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Add custom blade directives or model boot logic if needed.
    }
}
