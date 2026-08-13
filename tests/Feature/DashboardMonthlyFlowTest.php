<?php

namespace Tests\Feature;

use App\Models\Payment;
use App\Models\Plan;
use App\Models\User;
use App\Services\DashboardService;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class DashboardMonthlyFlowTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Role::firstOrCreate(['name' => 'root']);
        Role::firstOrCreate(['name' => 'admin']);
        Role::firstOrCreate(['name' => 'professor']);
        Role::firstOrCreate(['name' => 'aluno']);

        Plan::create(['name' => 'Plano Black', 'price' => 200.00]);
    }

    /** @test */
    public function test_dashboard_service_calculates_monthly_flow_for_paid_payments()
    {
        $student = User::factory()->create();
        $student->assignRole('aluno');

        // Create paid payments across different months
        $currentMonth = now()->format('Y-m');
        $lastMonth = now()->subMonth()->format('Y-m');

        Payment::create([
            'user_id' => $student->id,
            'amount' => 350.00,
            'due_date' => now(),
            'payment_date' => now(),
            'status' => 'paid',
            'reference_month' => $currentMonth,
        ]);

        Payment::create([
            'user_id' => $student->id,
            'amount' => 500.00,
            'due_date' => now()->subMonth(),
            'payment_date' => now()->subMonth(),
            'status' => 'paid',
            'reference_month' => $lastMonth,
        ]);

        $service = app(DashboardService::class);
        $data = $service->getDashboardData();

        $this->assertArrayHasKey('monthly_flow', $data);
        $this->assertCount(6, $data['monthly_flow']);

        $currentFlowItem = collect($data['monthly_flow'])->firstWhere('year_month', $currentMonth);
        $this->assertNotNull($currentFlowItem);
        $this->assertEquals(350.00, $currentFlowItem['value']);

        $lastFlowItem = collect($data['monthly_flow'])->firstWhere('year_month', $lastMonth);
        $this->assertNotNull($lastFlowItem);
        $this->assertEquals(500.00, $lastFlowItem['value']);
    }

    /** @test */
    public function test_admin_sees_monthly_flow_values_on_dashboard()
    {
        $admin = User::factory()->create();
        $admin->assignRole('admin');

        $student = User::factory()->create();
        $student->assignRole('aluno');

        $currentMonth = now()->format('Y-m');
        Payment::create([
            'user_id' => $student->id,
            'amount' => 1250.00,
            'due_date' => now(),
            'payment_date' => now(),
            'status' => 'paid',
            'reference_month' => $currentMonth,
        ]);

        $response = $this->actingAs($admin)->get(route('dashboard'));

        $response->assertStatus(200);
        $response->assertSee('Fluxo Mensal de Pagamentos');
        $response->assertSee('R$ 1.250,00');
    }
}
