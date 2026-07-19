<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class AuthController extends Controller
{
    // Exibe a tela de login
    public function login(Request $request)
    {
        return view('auth.login');
    }

    // Exibe a tela de registro
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    // Processa o registro
    public function register(Request $request)
    {
        $tenant = \App\Models\Tenant::current();
        if ($tenant && $tenant->hasReachedUserLimit()) {
            return back()->withErrors([
                'email' => 'O limite de cadastros ativos para esta academia foi atingido. Entre em contato com a administração.'
            ])->withInput();
        }

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'cpf' => ['required', 'string', 'unique:users,cpf', 'regex:/^\d{3}\.\d{3}\.\d{3}-\d{2}$/'],
            'telefone' => ['required', 'string', 'regex:/^\(\d{2}\) \d{4,5}-\d{4}$/'],
            'data_nascimento' => ['required', 'date'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'sexo' => ['required', 'string'],
            'endereco' => ['required', 'string'],
            'login' => ['required', 'string', 'unique:users,login'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],

            // Responsável (condicional)
            'nome_responsavel' => ['nullable', 'string', 'max:255'],
            'cpf_responsavel' => ['nullable', 'string', 'regex:/^\d{3}\.\d{3}\.\d{3}-\d{2}$/'],
            'telefone_responsavel' => ['nullable', 'string', 'regex:/^\(\d{2}\) \d{4,5}-\d{4}$/'],

            // Técnico/Saúde
            'faixa' => ['required', 'string'],
            'grau' => ['required', 'integer'],
            'peso' => ['required', 'numeric'],
            'vencimento_mensalidade' => ['required', 'string', 'in:05,10,15,20,25,30'],
            'possui_lesao' => ['required', 'boolean'],
            'descricao_lesao' => ['nullable', 'string', 'required_if:possui_lesao,1'],
            'medicamento_continuo' => ['required', 'boolean'],
            'descricao_medicamento' => ['nullable', 'string', 'required_if:medicamento_continuo,1'],
            'problema_cardiaco' => ['required', 'boolean'],
            'descricao_problema_cardiaco' => ['nullable', 'string', 'required_if:problema_cardiaco,1'],
            'outros' => ['nullable', 'string'],
        ]);

        // Verificação extra para menores de idade
        $nascimento = Carbon::parse($data['data_nascimento']);
        if ($nascimento->age < 18) {
            if (empty($data['nome_responsavel']) || empty($data['cpf_responsavel']) || empty($data['telefone_responsavel'])) {
                return back()->withErrors(['nome_responsavel' => 'Campos do responsável são obrigatórios para menores de idade.'])->withInput();
            }
        }

        $data['password'] = Hash::make($data['password']);
        $data['is_admin'] = false;
        $data['status'] = 'active';
        $data['start_date'] = now();

        $user = User::create($data);

        $user->assignRole('aluno');

        Auth::login($user);

        $route = $user->hasRole('aluno') ? 'portal.dashboard' : 'dashboard';
        return redirect()->route($route);
    }

    // Processa a tentativa de login
    public function authenticate(Request $request)
    {
        $request->validate([
            'login_identity' => ['required', 'string'],
            'password' => ['required'],
        ]);

        $identity = $request->input('login_identity');
        $password = $request->input('password');

        // Tenta buscar por CPF (removendo formatação) ou pelo campo de login username
        $cleanedCpf = preg_replace('/[^0-9]/', '', $identity);
        $user = User::where(function ($query) use ($cleanedCpf, $identity) {
            if (!empty($cleanedCpf)) {
                $query->where('cpf', $cleanedCpf);
            }
            $query->orWhere('login', $identity);
        })->first();

        if ($user && Hash::check($password, $user->password)) {
            // Check if logging in on the apex domain (no active tenant resolved from host)
            $resolvedFromHost = $request->attributes->get('tenant_resolved_from_host', false);
            if (!$resolvedFromHost && !$user->hasRole('root') && $user->tenant) {
                $subdomain = $user->tenant->subdomain;
                $scheme = $request->secure() ? 'https://' : 'http://';
                $cleanHost = preg_replace('/^(www\.)?/', '', $request->getHost());
                $newHost = "{$subdomain}.{$cleanHost}";

                return redirect()->to($scheme . $newHost . '/login')
                    ->withInput(['login_identity' => $request->input('login_identity')])
                    ->withErrors(['login_identity' => 'Por favor, faça login através do subdomínio da sua academia.']);
            }

            Auth::login($user);
            $request->session()->regenerate();

            // Auto-sincroniza a role do Spatie caso alguém tenha alterado apenas a coluna role_id direto no banco
            if ($user->role_id) {
                $expectedRole = \Spatie\Permission\Models\Role::find($user->role_id);
                if ($expectedRole && !$user->hasRole($expectedRole->name)) {
                    $user->syncRoles([$expectedRole->name]);
                    $user->load('roles'); // Recarrega a relação para as próximas verificações
                }
            }

            // Se o usuário tem perfil de gestão (root, admin, professor, instrutor) ele vai pro dashboard.
            // Apenas se ele for EXCLUSIVAMENTE aluno ele vai para o portal.
            $route = $user->hasAnyRole(['root', 'admin', 'professor', 'instrutor']) ? 'dashboard' : 'portal.dashboard';
            
            return redirect()->route($route);
        }

        return back()->withErrors([
            'login_identity' => 'As credenciais fornecidas estão incorretas.',
        ])->onlyInput('login_identity');
    }

    // Faz o logout (Sair)
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
