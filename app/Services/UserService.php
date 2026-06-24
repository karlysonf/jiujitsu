<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Role;

class UserService
{
    public function getAllUsers($search = null)
    {
        return User::role(['aluno', 'professor', 'instrutor', 'admin'])
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

            // Processa upload de foto
            $photoPath = null;
            if (!empty($data['photo']) && $data['photo'] instanceof \Illuminate\Http\UploadedFile) {
                $photoPath = $data['photo']->store('users/photos', 'public');
            }

            $roleName = $data['user_role'] ?? 'aluno';

            // Check plan limit if creating an active user with limited role
            $status = $data['status'] ?? 'active';
            if ($status === 'active' && in_array($roleName, ['aluno', 'professor', 'instrutor'])) {
                $tenant = \App\Models\Tenant::current();
                if ($tenant && $tenant->hasReachedUserLimit()) {
                    throw \Illuminate\Validation\ValidationException::withMessages([
                        'user_limit' => "O limite de {$tenant->max_users} cadastros ativos do seu plano foi atingido. Faça o upgrade para continuar cadastrando."
                    ]);
                }
            }

            // Segurança: Apenas root/admin podem atribuir papéis privilegiados
            $privilegedRoles = ['admin', 'root'];
            if (in_array($roleName, $privilegedRoles) && (!auth()->check() || !auth()->user()->hasAnyRole(['root', 'admin']))) {
                abort(403, 'Você não tem permissão para atribuir papéis administrativos.');
            }

            if ($roleName === 'professor' || $roleName === 'instrutor') {
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
                'telefone' => $data['phone'] ?? $data['telefone'] ?? null,
                'data_nascimento' => $data['birth_date'] ?? $data['data_nascimento'] ?? null,
                'faixa' => $data['faixa'] ?? null,
                'grau' => $data['grau'] ?? 0,
                'is_admin' => false,
                'role_id' => Role::where('name', $roleName)->value('id'),
                'status' => $data['status'] ?? 'active',
                'user_status' => isset($data['status']) && $data['status'] === 'active' ? 1 : 0,
                'plan_id' => $data['plan_id'] ?? null,
                'custom_price' => $data['custom_price'] ?? null,
                'start_date' => $data['start_date'] ?? now(),
                'emergency_contact_name' => $data['emergency_contact_name'] ?? null,
                'emergency_contact_phone' => $data['emergency_contact_phone'] ?? null,
                'notes' => $data['notes'] ?? null,
                'vencimento_mensalidade' => $data['due_day'] ?? 10,
                'photo' => $photoPath,
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
                    'amount' => $user->custom_price ?? ($user->plan ? $user->plan->price : 150.00),
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
            $newStatus = $data['status'] ?? $user->status;
            $newRole = $data['user_role'] ?? ($user->roles()->first() ? $user->roles()->first()->name : null);

            $wasCounted = $user->status === 'active' && in_array($user->roles()->first()?->name, ['aluno', 'professor', 'instrutor']);
            $willBeCounted = $newStatus === 'active' && in_array($newRole, ['aluno', 'professor', 'instrutor']);

            if (!$wasCounted && $willBeCounted) {
                $tenant = \App\Models\Tenant::current();
                if ($tenant && $tenant->hasReachedUserLimit()) {
                    throw \Illuminate\Validation\ValidationException::withMessages([
                        'user_limit' => "O limite de {$tenant->max_users} cadastros ativos do seu plano foi atingido. Faça o upgrade para continuar cadastrando."
                    ]);
                }
            }
            if (empty($data['password'])) {
                unset($data['password']);
            } else {
                $data['password'] = Hash::make($data['password']);
            }

            // Store CPF digits only
            if (!empty($data['cpf'])) {
                $data['cpf'] = preg_replace('/[^0-9]/', '', $data['cpf']);
            }

            // Processa upload de foto
            if (!empty($data['photo']) && $data['photo'] instanceof \Illuminate\Http\UploadedFile) {
                // Deleta a foto antiga se existir
                if ($user->photo && Storage::disk('public')->exists($user->photo)) {
                    Storage::disk('public')->delete($user->photo);
                }
                $data['photo'] = $data['photo']->store('users/photos', 'public');
            } else {
                // Mantém a foto atual se não foi enviada nova
                unset($data['photo']);
            }

            $userData = [
                'name' => $data['name'] ?? $user->name,
                'email' => $data['email'] ?? $user->email,
                'cpf' => $data['cpf'] ?? $user->cpf,
                'telefone' => $data['phone'] ?? $data['telefone'] ?? $user->telefone,
                'data_nascimento' => $data['birth_date'] ?? $data['data_nascimento'] ?? $user->data_nascimento,
                'faixa' => $data['faixa'] ?? $user->faixa,
                'grau' => $data['grau'] ?? $user->grau,
                'plan_id' => $data['plan_id'] ?? $user->plan_id,
                'custom_price' => array_key_exists('custom_price', $data) ? $data['custom_price'] : $user->custom_price,
                'start_date' => $data['start_date'] ?? $user->start_date,
                'emergency_contact_name' => $data['emergency_contact_name'] ?? $user->emergency_contact_name,
                'emergency_contact_phone' => $data['emergency_contact_phone'] ?? $user->emergency_contact_phone,
                'notes' => $data['notes'] ?? $user->notes,
                'status' => $data['status'] ?? $user->status,
                'user_status' => isset($data['status']) ? ($data['status'] === 'active' ? 1 : 0) : $user->user_status,
                'vencimento_mensalidade' => $data['due_day'] ?? $user->vencimento_mensalidade,
            ];

            // Inclui foto apenas se foi processada uma nova
            if (isset($data['photo'])) {
                $userData['photo'] = $data['photo'];
            }

            if (isset($data['password'])) {
                $userData['password'] = $data['password'];
            }

            $user->update($userData);

            if (isset($data['user_role'])) {
                // Segurança: Impede que um usuário sem privilégios promova alguém a admin ou root
                $privilegedRoles = ['admin', 'root'];
                if (in_array($data['user_role'], $privilegedRoles) && (!auth()->check() || !auth()->user()->hasAnyRole(['root', 'admin']))) {
                    abort(403, 'Você não tem permissão para atribuir papéis administrativos.');
                }
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
        return User::role(['aluno', 'professor', 'instrutor', 'admin'])->where('status', 'active')->count();
    }
}
