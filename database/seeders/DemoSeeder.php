<?php

namespace Database\Seeders;

use App\Models\Attendance;
use App\Models\Payment;
use App\Models\Plan;
use App\Models\Tenant;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class DemoSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Resolve ou cria o tenant de demo
        $tenant = Tenant::firstOrCreate(
            ['subdomain' => 'demo'],
            [
                'name'            => 'CT Demo — Gestão Combate',
                'primary_color'   => '#3b82f6',
                'secondary_color' => '#1e3a8a',
                'status'          => 'active',
            ]
        );

        app()->instance('currentTenant', $tenant);

        // 2. Garante que os papéis existem
        $alunoRole    = Role::firstOrCreate(['name' => 'aluno',    'guard_name' => 'web']);
        $adminRole    = Role::firstOrCreate(['name' => 'admin',    'guard_name' => 'web']);
        $professorRole = Role::firstOrCreate(['name' => 'professor', 'guard_name' => 'web']);

        // 3. Usuário demo fixo (admin)
        $demoAdmin = User::updateOrCreate(
            ['cpf' => '00000000000'],
            [
                'name'       => 'Professor Demo',
                'email'      => 'demo@gestao.com',
                'password'   => Hash::make('demo1234'),
                'telefone'   => '(11) 99999-0000',
                'faixa'      => 'preta',
                'grau'       => 4,
                'is_admin'   => true,
                'status'     => 'active',
                'tenant_id'  => $tenant->id,
                'start_date' => Carbon::now()->subYears(10),
            ]
        );
        if (!$demoAdmin->hasRole($adminRole)) {
            $demoAdmin->assignRole($adminRole);
        }

        // 4. Planos
        $plans = [
            Plan::firstOrCreate(['name' => 'Mensal',     'tenant_id' => $tenant->id], ['price' => 150.00]),
            Plan::firstOrCreate(['name' => 'Trimestral', 'tenant_id' => $tenant->id], ['price' => 400.00]),
            Plan::firstOrCreate(['name' => 'Semestral',  'tenant_id' => $tenant->id], ['price' => 750.00]),
            Plan::firstOrCreate(['name' => 'Anual',      'tenant_id' => $tenant->id], ['price' => 1300.00]),
        ];

        // 5. Lista de alunos fictícios realistas
        $studentsData = [
            ['name' => 'Carlos Eduardo Silva',    'belt' => 'branca',  'grau' => 2, 'status' => 'active'],
            ['name' => 'Mariana Oliveira Souza',  'belt' => 'branca',  'grau' => 4, 'status' => 'active'],
            ['name' => 'Bruno Henrique Santos',   'belt' => 'azul',    'grau' => 1, 'status' => 'active'],
            ['name' => 'Camila Lima Costa',       'belt' => 'azul',    'grau' => 3, 'status' => 'active'],
            ['name' => 'Rodrigo Alves Pereira',   'belt' => 'roxa',    'grau' => 2, 'status' => 'active'],
            ['name' => 'Juliana Ferreira Gomes',  'belt' => 'roxa',    'grau' => 0, 'status' => 'active'],
            ['name' => 'Lucas Gabriel Rodrigues', 'belt' => 'marrom',  'grau' => 1, 'status' => 'active'],
            ['name' => 'Fernanda Costa Martins',  'belt' => 'marrom',  'grau' => 3, 'status' => 'active'],
            ['name' => 'Thiago Rocha Barbosa',    'belt' => 'preta',   'grau' => 1, 'status' => 'active'],
            ['name' => 'Amanda Ribeiro Carvalho', 'belt' => 'branca',  'grau' => 0, 'status' => 'active'],
            ['name' => 'Rafael Teixeira Pinto',   'belt' => 'branca',  'grau' => 3, 'status' => 'active'],
            ['name' => 'Beatriz Melo Fonseca',    'belt' => 'azul',    'grau' => 0, 'status' => 'active'],
            ['name' => 'Gabriel Correia Neves',   'belt' => 'azul',    'grau' => 2, 'status' => 'active'],
            ['name' => 'Larissa Mendes Viana',    'belt' => 'roxa',    'grau' => 1, 'status' => 'active'],
            ['name' => 'Matheus Castro Nogueira', 'belt' => 'marrom',  'grau' => 0, 'status' => 'inactive'],
        ];

        $now     = Carbon::now();
        $methods = ['pix', 'credit_card', 'cash'];

        foreach ($studentsData as $index => $data) {
            $cpf   = 'DEMO' . str_pad($index, 7, '0', STR_PAD_LEFT);
            $email = 'aluno' . ($index + 1) . '@demo.gestao.com';
            $dueDay = str_pad([5, 10, 15, 20, 25][$index % 5], 2, '0', STR_PAD_LEFT);

            $student = User::updateOrCreate(
                ['email' => $email],
                [
                    'name'                   => $data['name'],
                    'cpf'                    => $cpf,
                    'email'                  => $email,
                    'password'               => Hash::make('mudar123'),
                    'telefone'               => '(11) 9' . rand(7000, 9999) . '-' . rand(1000, 9999),
                    'data_nascimento'        => Carbon::now()->subYears(rand(18, 45))->subDays(rand(1, 365)),
                    'faixa'                  => $data['belt'],
                    'grau'                   => $data['grau'],
                    'plan_id'                => $plans[$index % 4]->id,
                    'vencimento_mensalidade' => $dueDay,
                    'is_admin'               => false,
                    'status'                 => $data['status'],
                    'tenant_id'              => $tenant->id,
                    'start_date'             => Carbon::now()->subMonths(rand(3, 24)),
                ]
            );

            if ($data['status'] === 'active') {
                if (!$student->hasRole($alunoRole)) {
                    $student->assignRole($alunoRole);
                }

                // Histórico de pagamentos: últimos 5 meses
                for ($mOffset = 4; $mOffset >= 0; $mOffset--) {
                    $refDate = Carbon::now()->subMonths($mOffset)->startOfMonth();
                    $refKey  = $refDate->format('Y-m');
                    $dueDate = $refDate->copy()->setDay((int) $dueDay);

                    // Meses passados: 85% pago | 10% atrasado | 5% pendente
                    // Mês atual: 60% pago | 20% pendente | 20% atrasado
                    $rand = rand(1, 100);
                    if ($mOffset === 0) {
                        if ($rand <= 60) { $status = 'paid'; }
                        elseif ($rand <= 80) { $status = 'pending'; }
                        else { $status = 'late'; }
                    } else {
                        if ($rand <= 85) { $status = 'paid'; }
                        elseif ($rand <= 95) { $status = 'late'; }
                        else { $status = 'pending'; }
                    }

                    $payDate = ($status === 'paid')
                        ? $dueDate->copy()->subDays(rand(0, 5))
                        : null;

                    Payment::firstOrCreate(
                        ['user_id' => $student->id, 'reference_month' => $refKey],
                        [
                            'amount'         => $plans[$index % 4]->price,
                            'due_date'       => $dueDate,
                            'payment_date'   => $payDate,
                            'status'         => $status,
                            'payment_method' => $status === 'paid' ? $methods[rand(0, 2)] : null,
                            'notes'          => $status === 'paid' ? 'Mensalidade quitada.' : null,
                            'tenant_id'      => $tenant->id,
                        ]
                    );
                }

                // Presenças: últimos 45 dias, 3x por semana (~40% de chance por dia)
                for ($day = 0; $day < 45; $day++) {
                    $attendanceDate = $now->copy()->subDays($day);
                    if ($attendanceDate->dayOfWeek !== Carbon::SUNDAY && rand(1, 10) <= 4) {
                        Attendance::firstOrCreate(
                            ['user_id' => $student->id, 'date' => $attendanceDate->format('Y-m-d')],
                            ['tenant_id' => $tenant->id]
                        );
                    }
                }
            }
        }

        $this->command->info('✅ DemoSeeder concluído! Login: demo@gestao.com | Senha: demo1234');
    }
}
