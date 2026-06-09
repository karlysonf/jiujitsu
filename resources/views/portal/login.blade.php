@extends('layouts.guest')
@section('content')
<div style="text-align: center; margin-bottom: 2rem;">
    <h1 style="font-size: 1.5rem; font-weight: 700; color: #1e40af;">Portal do Aluno</h1>
    <p style="color: var(--text-muted); margin-top: 0.5rem;">Acesse sua área restrita informando seu CPF.</p>
</div>

<form action="{{ route('portal.login.post') }}" method="POST">
    @csrf

    @if ($errors->any())
        <div class="alert-error" style="background-color: #fee2e2; color: #b91c1c; padding: 1rem; border-radius: 0.5rem; margin-bottom: 1.5rem;">
            <i class="fas fa-exclamation-circle" style="margin-right: 0.5rem;"></i>
            {{ $errors->first() }}
        </div>
    @endif

    <div class="form-group" style="margin-bottom: 1.5rem;">
        <label for="cpf" style="display: block; font-weight: 600; margin-bottom: 0.5rem;">CPF</label>
        <input name="cpf" id="cpf" type="text" placeholder="000.000.000-00" required autofocus style="width: 100%; padding: 0.75rem; border: 1px solid #d1d5db; border-radius: 0.5rem;">
    </div>

    <div class="form-group" style="margin-bottom: 1.5rem;">
        <label for="password" style="display: block; font-weight: 600; margin-bottom: 0.5rem;">Senha</label>
        <input name="password" id="password" type="password" placeholder="Digite aqui..." required style="width: 100%; padding: 0.75rem; border: 1px solid #d1d5db; border-radius: 0.5rem;">
    </div>

    <button type="submit" class="btn" style="width: 100%; padding: 0.75rem; background-color: #1e40af; color: white; border: none; border-radius: 0.5rem; font-weight: 600; cursor: pointer; transition: 0.2s;">
        Acessar Portal
    </button>
</form>

<div class="footer-text" style="text-align: center; margin-top: 1.5rem;">
    <p style="margin-bottom: 0.5rem;">Esqueceu sua senha? <a href="{{ route('portal.password.request') }}" style="color: #1e40af; text-decoration: none;">Clique aqui</a></p>
    <a href="{{ route('login') }}" style="color: #6b7280; font-size: 0.8rem; text-decoration: none;"><i class="fas fa-arrow-left"></i> Voltar ao painel administrativo</a>
</div>
@endsection
