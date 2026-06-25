<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PortalLoginController extends Controller
{
    public function showLoginForm()
    {
        return view('portal.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'cpf' => ['required', 'string'],
            'password' => ['required'],
        ]);

        $cpf = preg_replace('/[^0-9]/', '', $request->input('cpf'));
        $password = $request->input('password');

        $user = \App\Models\User::where('cpf', $cpf)->first();

        if ($user && \Illuminate\Support\Facades\Hash::check($password, $user->password)) {
            // Check if logging in on the apex domain (no active tenant resolved from host)
            $resolvedFromHost = $request->attributes->get('tenant_resolved_from_host', false);
            if (!$resolvedFromHost && !$user->hasRole('root') && $user->tenant) {
                $subdomain = $user->tenant->subdomain;
                $scheme = $request->secure() ? 'https://' : 'http://';
                $cleanHost = preg_replace('/^(www\.)?/', '', $request->getHost());
                $newHost = "{$subdomain}.{$cleanHost}";
                
                return redirect()->to($scheme . $newHost . '/portal/login')
                    ->withInput(['cpf' => $request->input('cpf')])
                    ->withErrors(['cpf' => 'Por favor, faça login através do portal da sua academia.']);
            }

            Auth::login($user);
            $request->session()->regenerate();
            return redirect()->route('portal.dashboard');
        }

        return back()->withErrors([
            'cpf' => 'As credenciais fornecidas (CPF/Senha) estão incorretas.',
        ])->onlyInput('cpf');
    }
}
