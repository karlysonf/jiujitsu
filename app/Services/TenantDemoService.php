<?php

namespace App\Services;

use App\Models\Tenant;
use App\Models\User;
use App\Scopes\TenantScope;

class TenantDemoService
{
    protected ?Tenant $currentTenant;

    /**
     * Inject the active Tenant resolved by TenantServiceProvider.
     */
    public function __construct(?Tenant $currentTenant)
    {
        $this->currentTenant = $currentTenant;
    }

    /**
     * Get statistics or information scoped to the current tenant.
     */
    public function getTenantStats(): array
    {
        if (!$this->currentTenant) {
            return [
                'error' => 'No active tenant resolved.',
            ];
        }

        // The BelongsToTenant trait automatically applies TenantScope,
        // so querying User::count() will only return users belonging to this tenant.
        $totalUsers = User::count();

        // If we need to count across all tenants (e.g. for super-admin/root users),
        // we can bypass the scope explicitly:
        $globalUsersCount = User::withoutGlobalScope(TenantScope::class)->count();

        return [
            'tenant_name' => $this->currentTenant->name,
            'max_users_limit' => $this->currentTenant->max_users,
            'active_users_count' => $this->currentTenant->getActiveUsersCount(),
            'total_users_in_tenant' => $totalUsers,
            'global_users_across_all_tenants' => $globalUsersCount,
            'has_reached_limit' => $this->currentTenant->hasReachedUserLimit(),
        ];
    }
}
