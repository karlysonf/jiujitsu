<?php

namespace Tests\Feature;

use App\Models\Tenant;
use App\Models\User;
use App\Models\Plan;
use App\Services\UserService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Validation\ValidationException;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class TenantPlanLimitTest extends TestCase
{
    use RefreshDatabase;

    protected $userService;

    protected function setUp(): void
    {
        parent::setUp();
        
        $this->userService = app(UserService::class);

        // Setup roles
        Role::firstOrCreate(['name' => 'root']);
        Role::firstOrCreate(['name' => 'admin']);
        Role::firstOrCreate(['name' => 'professor']);
        Role::firstOrCreate(['name' => 'instrutor']);
        Role::firstOrCreate(['name' => 'aluno']);

        // Create a plan
        Plan::create([
            'name' => 'Mensal',
            'price' => 150.00,
        ]);
    }

    /** @test */
    public function test_tenant_reaches_user_limit()
    {
        // 1. Setup a tenant with a limit of 2 users
        $tenant = Tenant::create([
            'name' => 'Academia Limitada',
            'subdomain' => 'limitada',
            'plan_tier' => 'bronze',
            'max_users' => 2,
        ]);
        app()->instance('currentTenant', $tenant);

        // Authenticate admin for this tenant
        $admin = User::factory()->create(['tenant_id' => $tenant->id]);
        $admin->assignRole('admin');
        $this->actingAs($admin);

        // 2. Create first active student (should succeed)
        $user1 = $this->userService->createUser([
            'name' => 'Aluno Um',
            'email' => 'aluno1@limitada.com',
            'cpf' => '11111111111',
            'user_role' => 'aluno',
            'status' => 'active',
        ]);
        $this->assertDatabaseHas('users', ['id' => $user1->id, 'tenant_id' => $tenant->id]);

        // 3. Create second active instructor (should succeed, since instructors count too)
        $user2 = $this->userService->createUser([
            'name' => 'Professor Dois',
            'email' => 'prof2@limitada.com',
            'cpf' => '22222222222',
            'user_role' => 'professor',
            'status' => 'active',
        ]);
        $this->assertDatabaseHas('users', ['id' => $user2->id, 'tenant_id' => $tenant->id]);

        // 4. Verify count of active users
        $this->assertEquals(2, $tenant->getActiveUsersCount());
        $this->assertTrue($tenant->hasReachedUserLimit());

        // 5. Try creating a 3rd active student (should throw validation exception)
        $this->expectException(ValidationException::class);
        $this->userService->createUser([
            'name' => 'Aluno Tres',
            'email' => 'aluno3@limitada.com',
            'cpf' => '33333333333',
            'user_role' => 'aluno',
            'status' => 'active',
        ]);
    }

    /** @test */
    public function test_tenant_limit_does_not_count_admins_or_inactive_users()
    {
        // 1. Setup a tenant with a limit of 1 user
        $tenant = Tenant::create([
            'name' => 'Academia Limitada',
            'subdomain' => 'limitada',
            'plan_tier' => 'bronze',
            'max_users' => 1,
        ]);
        app()->instance('currentTenant', $tenant);

        // Authenticate admin for this tenant
        $admin = User::factory()->create(['tenant_id' => $tenant->id]);
        $admin->assignRole('admin');
        $this->actingAs($admin);

        // 2. Create inactive student (should succeed and not count)
        $user1 = $this->userService->createUser([
            'name' => 'Aluno Inativo',
            'email' => 'alunoinativo@limitada.com',
            'cpf' => '11111111111',
            'user_role' => 'aluno',
            'status' => 'inactive',
        ]);

        // 3. Create admin (should succeed and not count)
        $user2 = $this->userService->createUser([
            'name' => 'Admin User',
            'email' => 'admin@limitada.com',
            'cpf' => '22222222222',
            'user_role' => 'admin',
            'status' => 'active',
        ]);

        // Verify count is 0 for counted active users
        $this->assertEquals(0, $tenant->getActiveUsersCount());
        $this->assertFalse($tenant->hasReachedUserLimit());

        // 4. Create active student (should succeed as the first counted user)
        $user3 = $this->userService->createUser([
            'name' => 'Aluno Ativo',
            'email' => 'alunoativo@limitada.com',
            'cpf' => '33333333333',
            'user_role' => 'aluno',
            'status' => 'active',
        ]);

        $this->assertEquals(1, $tenant->getActiveUsersCount());
        $this->assertTrue($tenant->hasReachedUserLimit());
    }
}
