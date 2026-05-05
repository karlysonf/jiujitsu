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
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            return redirect()->route('portal.dashboard');
        }

        return back()->withErrors([
            'email' => 'As credenciais fornecidas (E-mail/Senha) estão incorretas.',
        ])->onlyInput('email');
    }
}
