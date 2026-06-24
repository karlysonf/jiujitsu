<?php

namespace Tests\Feature;

use App\Models\Tenant;
use App\Models\User;
use App\Models\Plan;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class TenantManagementTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Setup roles
        Role::firstOrCreate(['name' => 'root']);
        Role::firstOrCreate(['name' => 'admin']);
        Role::firstOrCreate(['name' => 'aluno']);

        // Create a default seed plan
        Plan::create([
            'name' => 'Mensal',
            'price' => 150.00,
        ]);
    }

    /** @test */
    public function test_non_root_user_cannot_access_tenant_management()
    {
        // Create standard admin user
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $this->actingAs($admin);

        // Try accessing index page
        $response = $this->get(route('root.tenants.index'));
        $response->assertStatus(403);
    }

    /** @test */
    public function test_root_user_can_list_tenants()
    {
        // Create root user
        $root = User::factory()->create();
        $root->assignRole('root');

        $this->actingAs($root);

        $response = $this->get(route('root.tenants.index'));
        $response->assertStatus(200);
        $response->assertViewIs('root.tenants.index');
    }

    /** @test */
    public function test_root_user_can_create_new_tenant_and_owner()
    {
        $root = User::factory()->create();
        $root->assignRole('root');

        $this->actingAs($root);

        $response = $this->post(route('root.tenants.store'), [
            'name' => 'Academia Nova Teste',
            'subdomain' => 'novateste',
            'domain' => 'www.novateste.com.br',
            'plan_tier' => 'silver',
            'status' => 'active',
            'expires_at' => '2027-06-24',
            
            // Owner Account
            'owner_name' => 'Dono Teste',
            'owner_email' => 'dono@novateste.com',
            'owner_cpf' => '123.456.789-01',
            'owner_password' => 'password123',
            'owner_password_confirmation' => 'password123',
        ]);

        $response->assertRedirect(route('root.tenants.index'));
        $this->assertDatabaseHas('tenants', [
            'subdomain' => 'novateste',
            'plan_tier' => 'silver',
            'max_users' => 100,
            'status' => 'active',
        ]);

        $tenant = Tenant::where('subdomain', 'novateste')->first();

        // Verify default plans were created for new tenant
        $this->assertDatabaseHas('plans', [
            'tenant_id' => $tenant->id,
            'name' => 'Plano Padrão',
            'price' => 75.00,
        ]);
        $this->assertDatabaseHas('plans', [
            'tenant_id' => $tenant->id,
            'name' => 'Cortesia',
            'price' => 0.00,
        ]);

        // Verify owner user was created and assigned the admin role
        $this->assertDatabaseHas('users', [
            'tenant_id' => $tenant->id,
            'name' => 'Dono Teste',
            'email' => 'dono@novateste.com',
            'cpf' => '12345678901',
        ]);

        $owner = User::withoutGlobalScope(\App\Scopes\TenantScope::class)->where('email', 'dono@novateste.com')->first();
        $this->assertTrue($owner->hasRole('admin'));
    }

    /** @test */
    public function test_root_user_can_update_tenant_properties()
    {
        $root = User::factory()->create();
        $root->assignRole('root');

        $this->actingAs($root);

        // Setup test tenant
        $tenant = Tenant::create([
            'name' => 'Academia Antiga',
            'subdomain' => 'antiga',
            'plan_tier' => 'bronze',
            'max_users' => 50,
            'status' => 'trial',
        ]);

        $response = $this->put(route('root.tenants.update', $tenant), [
            'name' => 'Academia Editada',
            'subdomain' => 'editada',
            'domain' => 'www.editada.com',
            'plan_tier' => 'gold',
            'status' => 'active',
            'expires_at' => '2028-12-31',
        ]);

        $response->assertRedirect(route('root.tenants.index'));
        $this->assertDatabaseHas('tenants', [
            'id' => $tenant->id,
            'name' => 'Academia Editada',
            'subdomain' => 'editada',
            'plan_tier' => 'gold',
            'max_users' => null, // gold standard limit
            'status' => 'active',
        ]);
    }

    /** @test */
    public function test_root_cannot_delete_primary_tenant()
    {
        $root = User::factory()->create();
        $root->assignRole('root');

        $this->actingAs($root);

        $tenant = Tenant::where('subdomain', 'ctdenyson')->first();

        // If not found (e.g. migration did not run in some test setups), create it
        if (!$tenant) {
            $tenant = Tenant::create([
                'name' => 'Primary Tenant',
                'subdomain' => 'ctdenyson',
                'plan_tier' => 'gold',
                'status' => 'active',
            ]);
        }

        $response = $this->delete(route('root.tenants.destroy', $tenant));
        $response->assertSessionHas('error', 'A academia principal ctdenyson não pode ser excluída.');
        $this->assertDatabaseHas('tenants', ['id' => $tenant->id]);
    }
}
