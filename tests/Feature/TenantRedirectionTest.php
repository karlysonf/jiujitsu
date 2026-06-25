<?php

namespace Tests\Feature;

use App\Models\Tenant;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class TenantRedirectionTest extends TestCase
{
    use RefreshDatabase;

    protected Tenant $tenant;
    protected User $tenantAdmin;
    protected User $rootUser;

    protected function setUp(): void
    {
        parent::setUp();

        // Setup roles
        Role::firstOrCreate(['name' => 'root']);
        Role::firstOrCreate(['name' => 'admin']);
        Role::firstOrCreate(['name' => 'aluno']);
        Role::firstOrCreate(['name' => 'professor']);
        Role::firstOrCreate(['name' => 'instrutor']);

        // Retrieve or create tenant (to avoid duplicate seed subdomain conflicts)
        $this->tenant = Tenant::where('subdomain', 'ctdenyson')->first() ?: Tenant::create([
            'name' => 'CT Denyson',
            'subdomain' => 'ctdenyson',
            'status' => 'active',
        ]);

        // Create tenant user (admin)
        $this->tenantAdmin = User::factory()->create([
            'tenant_id' => $this->tenant->id,
            'cpf' => '11111111111',
            'password' => Hash::make('password123'),
        ]);
        $this->tenantAdmin->assignRole('admin');

        // Create root user
        $this->rootUser = User::factory()->create([
            'tenant_id' => $this->tenant->id, // root also has a tenant in this structure
            'cpf' => '00000000000',
            'password' => Hash::make('password123'),
        ]);
        $this->rootUser->assignRole('root');

        // Enable redirection for testing
        config(['tenant.bypass_redirect' => false]);
    }

    /** @test */
    public function test_tenant_user_on_apex_domain_is_redirected_to_subdomain()
    {
        $this->actingAs($this->tenantAdmin);

        // Make request on apex domain
        $response = $this->get('http://gestaocombate.com.br/dashboard');

        // Should redirect to subdomain
        $response->assertRedirect('http://ctdenyson.gestaocombate.com.br/dashboard');
    }

    public function test_root_user_on_apex_domain_is_not_redirected()
    {
        $this->actingAs($this->rootUser);

        // Make request on apex domain
        $response = $this->get('http://gestaocombate.com.br/dashboard');

        // Should redirect to root tenants index on the same apex domain (not a subdomain redirect)
        $response->assertRedirect(route('root.tenants.index'));
    }

    /** @test */
    public function test_tenant_user_login_attempt_on_apex_domain_redirects_to_subdomain_login()
    {
        // Attempt to login using tenant credentials on the apex domain
        $response = $this->post('http://gestaocombate.com.br/login', [
            'login_identity' => '111.111.111-11',
            'password' => 'password123',
        ]);

        $response->assertRedirect('http://ctdenyson.gestaocombate.com.br/login');
        $response->assertSessionHasErrors('login_identity');
    }

    /** @test */
    public function test_root_user_login_attempt_on_apex_domain_succeeds_without_redirection()
    {
        // Attempt to login using root credentials on the apex domain
        $response = $this->post('http://gestaocombate.com.br/login', [
            'login_identity' => '000.000.000-00',
            'password' => 'password123',
        ]);

        $this->assertAuthenticatedAs($this->rootUser);
        $response->assertRedirect(route('dashboard'));
    }
}
