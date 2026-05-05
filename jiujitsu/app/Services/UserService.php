<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserService
{
    public function getAllUsers($search = null)
    {
        return User::role(['aluno', 'professor', 'admin'])
            ->with('plan')
            ->when($search, function ($query, $search) {
                $query->where(function($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%");
                });
            })
            ->orderBy('name')
            ->paginate(15);
    }

    public function createUser(array $data)
    {
        DB::beginTransaction();
        try {
            if (empty($data['password'])) {
                $data['password'] = Hash::make('mudar123');
            } else {
                $data['password'] = Hash::make($data['password']);
            }

            $roleName = $data['user_role'] ?? 'aluno';

            if ($roleName === 'professor') {
                $cortesia = \App\Models\Plan::where('name', 'Cortesia')->first();
                if ($cortesia) {
                    $data['plan_id'] = $cortesia->id;
                }
            }

            // Store CPF digits only
            if (!empty($data['cpf'])) {
                $data['cpf'] = preg_replace('/[^0-9]/', '', $data['cpf']);
            }

            // Map fields
            $userData = [
                'name' => $data['name'],
                'email' => $data['email'],
                'cpf' => $data['cpf'],
                'password' => $data['password'],
                'telefone' => $data['phone'] ?? null,
                'data_nascimento' => $data['birth_date'] ?? null,
                'faixa' => $data['belt'] ?? null,
                'grau' => $data['grau'] ?? 0,
                'is_admin' => false,
                'role_id' => Role::where('name', $roleName)->value('id'),
                'status' => $data['status'] ?? 'active',
                'user_status' => isset($data['status']) && $data['status'] === 'active' ? 1 : 0,
                'plan_id' => $data['plan_id'] ?? null,
                'start_date' => $data['start_date'] ?? now(),
                'emergency_contact_name' => $data['emergency_contact_name'] ?? null,
                'emergency_contact_phone' => $data['emergency_contact_phone'] ?? null,
                'notes' => $data['notes'] ?? null,
                'vencimento_mensalidade' => $data['due_day'] ?? 10,
                'possui_lesao' => false,
                'medicamento_continuo' => false,
                'problema_cardiaco' => false,
            ];

            // Calcula o dia de vencimento se for novo
            if (empty($data['due_day'])) {
                $firstDueDate = now()->addDays(30);
                $userData['vencimento_mensalidade'] = $firstDueDate->day;
            } else {
                $firstDueDate = now()->day($data['due_day']);
                if ($firstDueDate->isPast()) {
                    $firstDueDate->addMonth();
                }
            }

            $user = User::create($userData);
            $user->syncRoles([$roleName]);

            // 3. Gerar o primeiro pagamento pendente (apenas se ativo e não for Cortesia)
            if ($user->status === 'active' && (!$user->plan || $user->plan->name !== 'Cortesia')) {
                \App\Models\Payment::create([
                    'user_id' => $user->id,
                    'amount' => $user->plan ? $user->plan->price : 150.00,
                    'due_date' => $firstDueDate,
                    'status' => 'pending',
                    'reference_month' => $firstDueDate->format('Y-m'),
                ]);
            }

            DB::commit();
            return $user;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function updateUser(User $user, array $data)
    {
        DB::beginTransaction();
        try {
            if (empty($data['password'])) {
                unset($data['password']);
            } else {
                $data['password'] = Hash::make($data['password']);
            }

            // Store CPF digits only
            if (!empty($data['cpf'])) {
                $data['cpf'] = preg_replace('/[^0-9]/', '', $data['cpf']);
            }

            $userData = [
                'name' => $data['name'] ?? $user->name,
                'email' => $data['email'] ?? $user->email,
                'cpf' => $data['cpf'] ?? $user->cpf,
                'telefone' => $data['phone'] ?? $user->telefone,
                'data_nascimento' => $data['birth_date'] ?? $user->data_nascimento,
                'faixa' => $data['belt'] ?? $user->faixa,
                'grau' => $data['grau'] ?? $user->grau,
                'plan_id' => $data['plan_id'] ?? $user->plan_id,
                'start_date' => $data['start_date'] ?? $user->start_date,
                'emergency_contact_name' => $data['emergency_contact_name'] ?? $user->emergency_contact_name,
                'emergency_contact_phone' => $data['emergency_contact_phone'] ?? $user->emergency_contact_phone,
                'notes' => $data['notes'] ?? $user->notes,
                'status' => $data['status'] ?? $user->status,
                'user_status' => isset($data['status']) ? ($data['status'] === 'active' ? 1 : 0) : $user->user_status,
                'vencimento_mensalidade' => $data['due_day'] ?? $user->vencimento_mensalidade,
            ];

            if (isset($data['password'])) {
                $userData['password'] = $data['password'];
            }

            $user->update($userData);

            if (isset($data['user_role'])) {
                $user->syncRoles([$data['user_role']]);
            }

            DB::commit();
            return $user;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function deleteUser(User $user)
    {
        return $user->delete();
    }

    public function getActiveUsersCount()
    {
        return User::role(['aluno', 'professor', 'admin'])->where('status', 'active')->count();
    }
}
