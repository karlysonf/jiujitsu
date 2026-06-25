<?php

namespace App\Providers;

use App\Models\Tenant;
use Illuminate\Support\ServiceProvider;

class TenantServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Bind the active Tenant model to the service container.
        // This enables dependency injection in Controllers, Services, etc.
        $this->app->singleton(Tenant::class, function ($app) {
            return Tenant::current() ?: new Tenant();
        });

        // Register a shorter helper string key 'currentTenant' if desired
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
