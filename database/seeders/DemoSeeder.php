<?php

namespace Database\Seeders;

use App\Models\Tenant;
use App\Models\User;
use App\Models\Plan;
use App\Models\Payment;
use App\Models\Attendance;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class DemoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Resolve or create the default tenant
        $tenant = Tenant::where('subdomain', 'ctdenyson')->first();
        if (!$tenant) {
            $tenant = Tenant::create([
                'name' => 'CT Denyson Anderson',
                'subdomain' => 'ctdenyson',
                'primary_color' => '#3b82f6',
                'secondary_color' => '#1e3a8a',
                'status' => 'active',
            ]);
        }

        // Bind the tenant context so all models with BelongsToTenant get mapped correctly
        app()->instance('currentTenant', $tenant);

        // 2. Ensure roles exist
        $alunoRole = Role::firstOrCreate(['name' => 'aluno', 'guard_name' => 'web']);
        $adminRole = Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web']);
        $professorRole = Role::firstOrCreate(['name' => 'professor', 'guard_name' => 'web']);

        // 3. Create demo plans
        $plans = [
            Plan::firstOrCreate(['name' => 'Mensal', 'tenant_id' => $tenant->id], ['price' => 150.00]),
            Plan::firstOrCreate(['name' => 'Trimestral', 'tenant_id' => $tenant->id], ['price' => 400.00]),
            Plan::firstOrCreate(['name' => 'Semestral', 'tenant_id' => $tenant->id], ['price' => 750.00]),
            Plan::firstOrCreate(['name' => 'Anual', 'tenant_id' => $tenant->id], ['price' => 1300.00]),
        ];

        // 4. List of realistic students
        $studentsData = [
            ['name' => 'Carlos Eduardo Silva', 'belt' => 'Branca', 'grau' => 2],
            ['name' => 'Mariana Oliveira Souza', 'belt' => 'Branca', 'grau' => 4],
            ['name' => 'Bruno Henrique Santos', 'belt' => 'Azul', 'grau' => 1],
            ['name' => 'Camila Lima Costa', 'belt' => 'Azul', 'grau' => 3],
            ['name' => 'Rodrigo Alves Pereira', 'belt' => 'Roxa', 'grau' => 2],
            ['name' => 'Juliana Ferreira Gomes', 'belt' => 'Roxa', 'grau' => 0],
            ['name' => 'Lucas Gabriel Rodrigues', 'belt' => 'Marrom', 'grau' => 1],
            ['name' => 'Fernanda Costa Martins', 'belt' => 'Marrom', 'grau' => 3],
            ['name' => 'Thiago Rocha Barbosa', 'belt' => 'Preta', 'grau' => 1],
            ['name' => 'Amanda Ribeiro Carvalho', 'belt' => 'Branca', 'grau' => 0],
            ['name' => 'Rafael Teixeira Pinto', 'belt' => 'Branca', 'grau' => 3],
            ['name' => 'Beatriz Melo Fonseca', 'belt' => 'Azul', 'grau' => 0],
            ['name' => 'Gabriel Correia Neves', 'belt' => 'Azul', 'grau' => 2],
            ['name' => 'Larissa Mendes Viana', 'belt' => 'Roxa', 'grau' => 1],
            ['name' => 'Matheus Castro Nogueira', 'belt' => 'Marrom', 'grau' => 0],
        ];

        // 5. Generate Students, Payments and Attendances
        $now = Carbon::now();
        $methods = ['pix', 'credit_card', 'cash'];

        foreach ($studentsData as $index => $data) {
            $cpf = '222' . str_pad($index, 8, '0', STR_PAD_LEFT);
            $email = strtolower(str_replace(' ', '.', $data['name'])) . '@demo.com';
            
            // Create student
            $student = User::updateOrCreate(
                ['cpf' => $cpf],
                [
                    'name' => $data['name'],
                    'email' => $email,
                    'password' => Hash::make('senha123'),
                    'telefone' => '(11) 9' . rand(7000, 9999) . '-' . rand(1000, 9999),
                    'data_nascimento' => Carbon::now()->subYears(rand(18, 45))->subDays(rand(1, 365)),
                    'faixa' => $data['belt'],
                    'grau' => $data['grau'],
                    'plan_id' => $plans[rand(0, 3)]->id,
                    'vencimento_mensalidade' => str_pad(rand(1, 5) * 5, 2, '0', STR_PAD_LEFT), // 05, 10, 15, 20, 25
                    'is_admin' => false,
                    'status' => 'active',
                    'start_date' => Carbon::now()->subMonths(6),
                ]
            );

            $student->assignRole($alunoRole);

            // Generate Payment History (March, April, May, June 2026)
            $months = [
                ['ref' => '2026-03', 'due' => Carbon::create(2026, 3, (int) $student->vencimento_mensalidade)],
                ['ref' => '2026-04', 'due' => Carbon::create(2026, 4, (int) $student->vencimento_mensalidade)],
                ['ref' => '2026-05', 'due' => Carbon::create(2026, 5, (int) $student->vencimento_mensalidade)],
                ['ref' => '2026-06', 'due' => Carbon::create(2026, 6, (int) $student->vencimento_mensalidade)],
            ];

            foreach ($months as $monthIdx => $m) {
                // Determine payment status randomly
                // 90% chance to be paid in past months. For current/June month, some are paid, some pending, some late.
                $status = 'paid';
                $payDate = (clone $m['due'])->subDays(rand(0, 3));
                
                if ($m['ref'] === '2026-06') {
                    $rand = rand(1, 10);
                    if ($rand > 7) {
                        $status = 'pending';
                        $payDate = null;
                    } elseif ($rand === 7) {
                        $status = 'late';
                        $payDate = null;
                    }
                } else {
                    if (rand(1, 10) === 10) {
                        $status = 'late';
                        $payDate = null;
                    }
                }

                Payment::firstOrCreate(
                    [
                        'user_id' => $student->id,
                        'reference_month' => $m['ref'],
                    ],
                    [
                        'amount' => $student->plan->price,
                        'due_date' => $m['due'],
                        'payment_date' => $payDate,
                        'status' => $status,
                        'payment_method' => $status === 'paid' ? $methods[rand(0, 2)] : null,
                        'notes' => $status === 'paid' ? 'Mensalidade quitada.' : null,
                    ]
                );
            }

            // Generate Attendance History (for the last 30 days)
            // Generate random attendance dates (excluding Sundays)
            for ($day = 0; $day < 30; $day++) {
                $attendanceDate = (clone $now)->subDays($day);
                
                // 40% attendance rate, excluding Sundays
                if ($attendanceDate->dayOfWeek !== Carbon::SUNDAY && rand(1, 10) <= 4) {
                    Attendance::firstOrCreate([
                        'user_id' => $student->id,
                        'date' => $attendanceDate->format('Y-m-d'),
                    ]);
                }
            }
        }

        // 6. Ensure teachers and root have roles assigned
        $teachers = User::role('professor')->get();
        foreach ($teachers as $teacher) {
            $teacher->update(['tenant_id' => $tenant->id]);
        }
    }
}
