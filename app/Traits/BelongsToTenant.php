<?php

namespace App\Traits;

use App\Models\Tenant;
use App\Scopes\TenantScope;

trait BelongsToTenant
{
    /**
     * Boot the trait to apply TenantScope and auto-populate tenant_id on creating.
     */
    protected static function bootBelongsToTenant(): void
    {
        static::addGlobalScope(new TenantScope());

        static::creating(function ($model) {
            $tenant = Tenant::current();
            if ($tenant) {
                if (!isset($model->tenant_id)) {
                    $model->tenant_id = $tenant->id;
                }
            } elseif (app()->runningUnitTests()) {
                // Autocreate or retrieve default test tenant if in unit tests
                $firstTenant = Tenant::first() ?: Tenant::create([
                    'name' => 'Test Tenant',
                    'subdomain' => 'test',
                ]);
                $model->tenant_id = $firstTenant->id;
                app()->instance('currentTenant', $firstTenant);
            }
        });
    }

    /**
     * Get the tenant that owns the model.
     */
    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }
}
