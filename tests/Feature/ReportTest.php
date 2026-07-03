<?php

namespace Tests\Feature;

use App\Models\Tenant;
use App\Models\User;
use App\Models\Attendance;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class ReportTest extends TestCase
{
    use RefreshDatabase;

    protected Tenant $tenant;
    protected User $adminUser;
    protected User $studentUser;

    protected function setUp(): void
    {
        parent::setUp();

        // Create Spatie roles
        Role::firstOrCreate(['name' => 'root']);
        Role::firstOrCreate(['name' => 'admin']);
        Role::firstOrCreate(['name' => 'aluno']);
        Role::firstOrCreate(['name' => 'professor']);
        Role::firstOrCreate(['name' => 'instrutor']);

        // Retrieve or create the default tenant to avoid ID conflicts
        $this->tenant = Tenant::where('subdomain', 'ctdenyson')->first() ?: Tenant::create([
            'name' => 'CT Denyson',
            'subdomain' => 'ctdenyson',
            'status' => 'active',
        ]);

        app()->instance('currentTenant', $this->tenant);

        // Create admin user
        $this->adminUser = User::factory()->create([
            'tenant_id' => $this->tenant->id,
            'cpf' => '04314745169',
        ]);
        $this->adminUser->assignRole('admin');

        // Give admin role permissions if needed, or check gates.
        // In the app, ReportController authorizes using Gate::authorize('view-reports').
        // Let's check how 'view-reports' permission is defined.
    }

    /** @test */
    public function test_unauthorized_user_cannot_access_reports()
    {
        $student = User::factory()->create([
            'tenant_id' => $this->tenant->id,
            'cpf' => '22222222222',
        ]);
        $student->assignRole('aluno');

        $this->actingAs($student);

        // Access report index
        $response = $this->get(route('reports.index'));
        $response->assertStatus(403);

        // Access attendance report
        $response = $this->get(route('reports.attendance'));
        $response->assertStatus(403);
    }

    /** @test */
    public function test_authorized_user_can_access_reports_and_see_attendance_data()
    {
        // Log in as admin
        $this->actingAs($this->adminUser);

        // Create another student to check in
        $student = User::factory()->create([
            'tenant_id' => $this->tenant->id,
            'name' => 'John Doe Student',
            'cpf' => '22222222222',
        ]);
        $student->assignRole('aluno');

        // Create some check-ins in current month
        $date1 = now()->startOfMonth()->toDateString();
        $date2 = now()->startOfMonth()->addDay()->toDateString();

        Attendance::create([
            'user_id' => $student->id,
            'date' => $date1,
            'tenant_id' => $this->tenant->id,
        ]);

        Attendance::create([
            'user_id' => $student->id,
            'date' => $date2,
            'tenant_id' => $this->tenant->id,
        ]);

        $this->assertDatabaseCount('attendances', 2);
        $this->assertEquals($this->tenant->id, Attendance::first()->tenant_id);

        // Access report index
        $response = $this->get(route('reports.index'));
        $response->assertStatus(200);
        $response->assertSee('Frequência e Presença');

        // Access attendance report page
        $response = $this->get(route('reports.attendance') . "?start_date=" . now()->startOfMonth()->toDateString() . "&end_date=" . now()->toDateString());
        $response->assertStatus(200);
        $response->assertSee('John Doe Student');
        $response->assertSee('2 aulas'); // Should display the presence count
    }
}

