<?php

namespace App\Http\Controllers;

use App\Models\Tenant;
use App\Services\TenantDemoService;
use Illuminate\Http\Request;

class TenantDemoController extends Controller
{
    protected TenantDemoService $tenantService;

    // 1. Dependency Injection of a Service that utilizes Tenant context
    public function __construct(TenantDemoService $tenantService)
    {
        $this->tenantService = $tenantService;
    }

    /**
     * Demonstrate different ways to access the current Tenant.
     */
    public function index(Request $request, Tenant $tenant)
    {
        // Method A: Via Dependency Injection directly in the action method
        $tenantViaInjection = $tenant;

        // Method B: Via the static helper of the model
        $tenantViaStatic = Tenant::current();

        // Method C: Via the service container binding
        $tenantViaContainer = app('currentTenant'); 
        // Or using the class name binding: app(Tenant::class)

        // Method D: Via request attribute if set, or global view share
        // (The ResolveTenant middleware automatically calls view()->share('currentTenant', $tenant))

        // Get details using the service
        $stats = $this->tenantService->getTenantStats();

        return response()->json([
            'message' => 'Tenant resolved successfully!',
            'tenant_id' => $tenantViaInjection?->id,
            'tenant_name' => $tenantViaInjection?->name,
            'subdomain' => $tenantViaInjection?->subdomain,
            'is_same_tenant' => ($tenantViaInjection?->id === $tenantViaStatic?->id),
            'stats' => $stats,
        ]);
    }
}
