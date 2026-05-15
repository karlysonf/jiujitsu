<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\Plan;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class HackerAttackTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Setup roles
        Role::firstOrCreate(['name' => 'root']);
        Role::firstOrCreate(['name' => 'admin']);
        Role::firstOrCreate(['name' => 'professor']);
        Role::firstOrCreate(['name' => 'aluno']);

        Plan::create(['name' => 'Mensal', 'price' => 150.00]);
    }

    /** @test */
    public function test_professor_cannot_escalate_to_root()
    {
        // 1. O Hacker entra no sistema como um Professor
        $hacker = User::factory()->create();
        $hacker->assignRole('professor');
        $this->actingAs($hacker);

        // 2. O Hacker tenta criar um novo usuário com o papel 'root'
        $response = $this->post(route('users.store'), [
            'name' => 'Fake Root',
            'email' => 'fake@root.com',
            'cpf' => '999.999.999-99',
            'faixa' => 'Preta',
            'grau' => 4,
            'start_date' => now()->format('Y-m-d'),
            'plan_id' => Plan::first()->id,
            'user_role' => 'root', 
            'status' => 'active',
        ]);

        // 3. Verificamos se o sistema BARRROU (403) ou redirecionou por falha de validação (302)
        // No StoreUserRequest o 'root' não está na lista permitida, então dará 302.
        // Mas a lógica do Service daria 403 se passasse pela validação.
        $this->assertTrue($response->status() === 403 || $response->status() === 302);
        $this->assertNull(User::where('email', 'fake@root.com')->first());
    }

    /** @test */
    public function test_admin_cannot_delete_the_root_user()
    {
        // 1. Criamos o Superusuário (Root)
        $root = User::factory()->create(['email' => 'root@system.com']);
        $root->assignRole('root');

        // 2. Um Admin comum entra no sistema
        $admin = User::factory()->create();
        $admin->assignRole('admin');
        $this->actingAs($admin);

        // 3. O Admin tenta deletar o Root
        $response = $this->delete(route('users.destroy', $root));

        // 4. Deve ser proibido
        $response->assertStatus(403);
        $this->assertDatabaseHas('users', ['email' => 'root@system.com', 'deleted_at' => null]);
    }
}
