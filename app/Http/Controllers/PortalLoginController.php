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
            Auth::login($user);
            $request->session()->regenerate();
            return redirect()->route('portal.dashboard');
        }

        return back()->withErrors([
            'cpf' => 'As credenciais fornecidas (CPF/Senha) estão incorretas.',
        ])->onlyInput('cpf');
    }
}
