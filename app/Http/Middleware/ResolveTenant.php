<?php

namespace App\Http\Middleware;

use App\Models\Tenant;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Symfony\Component\HttpFoundation\Response;

class ResolveTenant
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!Schema::hasTable('tenants')) {
            return $next($request);
        }

        $host = $request->getHost();
        $tenant = null;

        // 1. Try resolving by custom domain
        $tenant = Tenant::where('domain', $host)->where('status', '!=', 'suspended')->first();
        $resolvedFromHost = ($tenant !== null);

        if (!$tenant && !filter_var($host, FILTER_VALIDATE_IP)) {
            // 2. Parse subdomain
            $parts = explode('.', $host);
            $domainPartsCount = count($parts);
            $subdomain = null;

            if (end($parts) === 'localhost') {
                if ($domainPartsCount > 1) {
                    $subdomain = $parts[0];
                }
            } else {
                // Determine if it is a multi-part TLD like .com.br
                $tlds = ['com', 'org', 'net', 'edu', 'gov', 'co', 'app'];
                $secondToLast = $parts[count($parts) - 2] ?? '';

                if (in_array($secondToLast, $tlds) && $domainPartsCount > 3) {
                    $subdomain = $parts[0];
                } elseif (!in_array($secondToLast, $tlds) && $domainPartsCount > 2) {
                    $subdomain = $parts[0];
                }
            }

            if ($subdomain && !in_array($subdomain, ['www', 'admin', 'demo'])) {
                $tenant = Tenant::where('subdomain', $subdomain)->where('status', '!=', 'suspended')->first();
                
                if (!$tenant) {
                    abort(404, 'Academia não encontrada ou suspensa.');
                }
                $resolvedFromHost = true;
            }
        }

        if (!$resolvedFromHost && auth()->check()) {
            $user = auth()->user();
            
            // Demo user never gets redirected to a subdomain — stays on main domain
            $isDemo = ($user->email === 'demo@gestao.com');

            // If the logged-in user is not root (and not demo), redirect them to their specific subdomain
            if (!$isDemo && !$user->hasRole('root') && $user->tenant && !config('tenant.bypass_redirect', app()->runningUnitTests())) {
                $tenantSubdomain = $user->tenant->subdomain;
                $scheme = $request->secure() ? 'https://' : 'http://';
                $cleanHttpHost = preg_replace('/^(www\.)?/', '', $request->getHttpHost());
                $newHost = "{$tenantSubdomain}.{$cleanHttpHost}";
                
                return redirect()->to($scheme . $newHost . $request->getRequestUri());
            }

            $tenant = $user->tenant;
        }

        if ($tenant) {
            app()->instance('currentTenant', $tenant);
            app()->instance(Tenant::class, $tenant); // Bind the active Tenant model for dependency injection
            
            // Share tenant data globally with all Blade views
            view()->share('currentTenant', $tenant);

            if (isset($resolvedFromHost) && $resolvedFromHost) {
                $request->attributes->set('tenant_resolved_from_host', true);
            }
        }

        return $next($request);
    }
}
