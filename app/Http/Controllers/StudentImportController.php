<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Plan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class StudentImportController extends Controller
{
    public function show()
    {
        Gate::authorize('manage-users');
        return view('users.import');
    }

    public function store(Request $request)
    {
        Gate::authorize('manage-users');

        $request->validate([
            'file' => 'required|file|mimes:csv,txt|max:2048',
        ]);

        $file = $request->file('file');
        $path = $file->getRealPath();

        $data = [];
        if (($handle = fopen($path, 'r')) !== false) {
            // Get header
            $header = fgetcsv($handle, 1000, ',');
            
            // Try semicolon separator if header count is 1
            if (count($header) === 1) {
                rewind($handle);
                $header = fgetcsv($handle, 1000, ';');
                $separator = ';';
            } else {
                $separator = ',';
            }

            // Normalize header columns
            $header = array_map(function($col) {
                return strtolower(trim(preg_replace('/[^a-zA-Z0-9_]/', '', $col)));
            }, $header);

            while (($row = fgetcsv($handle, 1000, $separator)) !== false) {
                if (count($row) === count($header)) {
                    $data[] = array_combine($header, $row);
                }
            }
            fclose($handle);
        }

        if (empty($data)) {
            return back()->with('error', 'O arquivo CSV está vazio ou inválido.');
        }

        $imported = 0;
        $skipped = 0;

        // Get default plan to assign to new students
        $defaultPlan = Plan::first();

        // Ensure student role exists
        $alunoRole = Role::firstOrCreate(['name' => 'aluno', 'guard_name' => 'web']);

        foreach ($data as $item) {
            // Map Portuguese/English fields
            $name = $item['nome'] ?? $item['name'] ?? null;
            $email = $item['email'] ?? null;
            $cpf = $item['cpf'] ?? null;
            $telefone = $item['telefone'] ?? $item['phone'] ?? $item['tel'] ?? null;
            $birthDate = $item['data_nascimento'] ?? $item['birth_date'] ?? $item['nascimento'] ?? null;
            $faixa = $item['faixa'] ?? $item['belt'] ?? 'Branca';
            $grau = intval($item['grau'] ?? $item['grade'] ?? 0);

            if (!$name) {
                $skipped++;
                continue;
            }

            // Standardize CPF
            if ($cpf) {
                $cpf = preg_replace('/[^0-9]/', '', $cpf);
            }

            // Check if user already exists in this tenant
            $query = User::query();
            if ($cpf) {
                $query->where('cpf', $cpf);
            } elseif ($email) {
                $query->where('email', $email);
            } else {
                $query->where('name', $name);
            }

            $userExists = $query->exists();

            if ($userExists) {
                $skipped++;
                continue;
            }

            // Verify tenant registration limit
            $tenant = \App\Models\Tenant::current();
            if ($tenant && $tenant->hasReachedUserLimit()) {
                return redirect()->route('users.index')->with('error', "Importação interrompida: O limite do seu plano ({$tenant->max_users} cadastros ativos) foi atingido. {$imported} alunos foram importados e {$skipped} pulados.");
            }

            // Create user
            $user = User::create([
                'name' => $name,
                'email' => $email ?: 'aluno.' . uniqid() . '@ctdenyson.com',
                'cpf' => $cpf,
                'telefone' => $telefone,
                'data_nascimento' => $birthDate ? date('Y-m-d', strtotime(str_replace('/', '-', $birthDate))) : null,
                'faixa' => $faixa,
                'grau' => $grau,
                'plan_id' => $defaultPlan ? $defaultPlan->id : null,
                'password' => Hash::make($cpf ?: \Illuminate\Support\Str::random(12)),
                'status' => 'active',
            ]);

            $user->assignRole($alunoRole);
            $imported++;
        }

        return redirect()->route('users.index')->with('success', "Importação concluída! {$imported} alunos importados com sucesso e {$skipped} pulados.");
    }
}
