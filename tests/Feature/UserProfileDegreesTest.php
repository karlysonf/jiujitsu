<?php

namespace Tests\Feature;

use App\Models\Plan;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class UserProfileDegreesTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Role::firstOrCreate(['name' => 'root']);
        Role::firstOrCreate(['name' => 'admin']);
        Role::firstOrCreate(['name' => 'professor']);
        Role::firstOrCreate(['name' => 'aluno']);

        Plan::create(['name' => 'Plano Black', 'price' => 150.00]);
    }

    /** @test */
    public function test_user_degrees_accessors_work_correctly()
    {
        $user = User::factory()->create([
            'faixa' => 'Azul',
            'grau' => 3,
            'endereco' => 'Rua das Flores, 123',
        ]);

        $this->assertEquals(3, $user->degrees);
        $this->assertEquals(3, $user->graus);
        $this->assertEquals(3, $user->grau);
        $this->assertEquals('Azul', $user->belt);
        $this->assertEquals('Rua das Flores, 123', $user->address);
    }

    /** @test */
    public function test_viewing_student_profile_displays_degrees_correctly()
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $student = User::factory()->create([
            'name' => 'Carlos Gracie',
            'faixa' => 'Azul',
            'grau' => 3,
            'status' => 'active',
        ]);
        $student->assignRole('aluno');

        $response = $this->actingAs($admin)->get(route('users.show', $student));

        $response->assertStatus(200);
        $response->assertSee('Carlos Gracie');
        $response->assertSee('FAIXA AZUL');
        $response->assertSee('3 GRAUS');
    }

    /** @test */
    public function test_viewing_student_profile_with_zero_degrees_displays_zero()
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $student = User::factory()->create([
            'name' => 'Iniciante Silva',
            'faixa' => 'Branca',
            'grau' => 0,
            'status' => 'active',
        ]);
        $student->assignRole('aluno');

        $response = $this->actingAs($admin)->get(route('users.show', $student));

        $response->assertStatus(200);
        $response->assertSee('Iniciante Silva');
        $response->assertSee('FAIXA BRANCA');
        $response->assertSee('0 GRAUS');
    }
}
