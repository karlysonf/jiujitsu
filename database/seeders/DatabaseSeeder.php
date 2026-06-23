<?php
namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Cria os cargos (roles) primeiro
        $this->call(RoleSeeder::class);

        $rootRole = Role::where('name', 'root')->first();
        $alunoRole = Role::where('name', 'aluno')->first();

        // 2. Cria o usuário Root com as credenciais solicitadas pelo usuário
        $rootUser = User::updateOrCreate(
            ['cpf' => '04314745169'],
            [
                'name' => 'Felipe Santos',
                'email' => 'kfbasantos@gmail.com',
                'password' => Hash::make('KF2404ba'),
                'telefone' => '11999999999',
                'faixa' => 'preta',
                'is_admin' => true,
                'status' => 'active',
            ]
        );
        
        if ($rootRole) {
            $rootUser->assignRole($rootRole);
        }

        // 3. Cria um usuário Aluno para testes (usando updateOrCreate para evitar erros)
        $aluno = User::updateOrCreate(
            ['cpf' => '11111111111'],
            [
                'name' => 'João Aluno',
                'email' => 'aluno@jiujitsu.com',
                'password' => Hash::make('mudar123'),
                'telefone' => '11888888888',
                'faixa' => 'branca',
                'is_admin' => false,
                'status' => 'active',
            ]
        );

        if ($alunoRole) {
            $aluno->assignRole($alunoRole);
        }

        // 4. Cria plano padrão
        $this->call(PlanSeeder::class);
    }
}