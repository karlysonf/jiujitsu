<?php

namespace App\Http\Controllers\Root;

use App\Http\Controllers\Controller;
use App\Models\Tenant;
use App\Models\User;
use App\Models\Plan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class TenantManagementController extends Controller
{
    public function index()
    {
        $tenants = Tenant::orderBy('name')->paginate(15);
        return view('root.tenants.index', compact('tenants'));
    }

    public function create()
    {
        return view('root.tenants.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'subdomain' => 'required|string|alpha_dash|unique:tenants,subdomain|max:50',
            'domain' => 'nullable|string|unique:tenants,domain|max:255',
            'plan_tier' => 'required|in:bronze,silver,gold',
            'status' => 'required|in:trial,active,suspended',
            'expires_at' => 'nullable|date',

            // Owner details
            'owner_name' => 'required|string|max:255',
            'owner_email' => 'required|email|max:255',
            'owner_cpf' => 'required|string|regex:/^\d{3}\.\d{3}\.\d{3}-\d{2}$/',
            'owner_password' => 'required|string|min:8|confirmed',
        ]);

        $limits = [
            'bronze' => 50,
            'silver' => 100,
            'gold' => null,
        ];

        $subdomain = strtolower($request->subdomain);
        $cpf = preg_replace('/[^0-9]/', '', $request->owner_cpf);

        DB::beginTransaction();
        try {
            // 1. Create Tenant
            $tenant = Tenant::create([
                'name' => $request->name,
                'subdomain' => $subdomain,
                'domain' => $request->domain,
                'plan_tier' => $request->plan_tier,
                'max_users' => $limits[$request->plan_tier],
                'status' => $request->status,
                'expires_at' => $request->expires_at,
            ]);

            // 2. Create default plans for the new tenant
            $plan1 = new Plan([
                'name' => 'Plano Padrão',
                'price' => 75.00,
            ]);
            $plan1->tenant_id = $tenant->id;
            $plan1->save();

            $plan2 = new Plan([
                'name' => 'Cortesia',
                'price' => 0.00,
            ]);
            $plan2->tenant_id = $tenant->id;
            $plan2->save();

            // 3. Ensure role admin exists
            $adminRole = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);

            // 4. Create Owner user scoped to the new tenant
            // We pass the tenant_id explicitly to bypass auto-association with super-admin's tenant
            $owner = new User([
                'name' => $request->owner_name,
                'email' => $request->owner_email,
                'cpf' => $cpf,
                'password' => Hash::make($request->owner_password),
                'is_admin' => true,
                'status' => 'active',
                'start_date' => now(),
            ]);
            $owner->tenant_id = $tenant->id;
            $owner->save();

            $owner->assignRole($adminRole);

            DB::commit();

            return redirect()->route('root.tenants.index')->with('success', "Academia '{$tenant->name}' cadastrada com sucesso!");
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()->withErrors(['error' => 'Ocorreu um erro ao criar a academia: ' . $e->getMessage()]);
        }
    }

    public function edit(Tenant $tenant)
    {
        return view('root.tenants.edit', compact('tenant'));
    }

    public function update(Request $request, Tenant $tenant)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'subdomain' => 'required|string|alpha_dash|max:50|unique:tenants,subdomain,' . $tenant->id,
            'domain' => 'nullable|string|max:255|unique:tenants,domain,' . $tenant->id,
            'plan_tier' => 'required|in:bronze,silver,gold',
            'status' => 'required|in:trial,active,suspended',
            'expires_at' => 'nullable|date',
            'max_users' => 'nullable|integer|min:0',
        ]);

        $subdomain = strtolower($request->subdomain);

        $limits = [
            'bronze' => 50,
            'silver' => 100,
            'gold' => null,
        ];

        // If tier changed and max_users was not custom override, update max_users
        $maxUsers = $request->max_users;
        if ($request->plan_tier !== $tenant->plan_tier && !$request->has('custom_limit_override')) {
            $maxUsers = $limits[$request->plan_tier];
        }

        $tenant->update([
            'name' => $request->name,
            'subdomain' => $subdomain,
            'domain' => $request->domain,
            'plan_tier' => $request->plan_tier,
            'max_users' => $maxUsers,
            'status' => $request->status,
            'expires_at' => $request->expires_at,
        ]);

        return redirect()->route('root.tenants.index')->with('success', "Configurações da academia '{$tenant->name}' atualizadas com sucesso!");
    }

    public function destroy(Tenant $tenant)
    {
        // Prevent deleting the primary/seed tenant for safety
        if ($tenant->subdomain === 'ctdenyson') {
            return back()->with('error', 'A academia principal ctdenyson não pode ser excluída.');
        }

        $tenant->delete();
        return redirect()->route('root.tenants.index')->with('success', 'Academia excluída com sucesso.');
    }
}
