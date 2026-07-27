@extends('layouts.guest')
@section('content')
<div style="text-align: center; margin-bottom: 2rem;">
    <h1 style="font-family: 'Outfit', sans-serif; font-size: 1.6rem; font-weight: 800; color: var(--primary);">Portal do Aluno</h1>
    <p style="color: var(--text-muted); margin-top: 0.5rem; font-size: 0.9rem;">Acesse sua área restrita informando seu CPF.</p>
</div>

<form action="{{ route('portal.login.post') }}" method="POST">
    @csrf

    @if ($errors->any())
        <div class="alert-error">
            <i class="fas fa-exclamation-circle" style="margin-right: 0.5rem;"></i>
            {{ $errors->first() }}
        </div>
    @endif

    <div class="form-group" style="margin-bottom: 1.5rem;">
        <label for="cpf" style="display: block; font-weight: 600; margin-bottom: 0.5rem; color: var(--text-main);">CPF</label>
        <input name="cpf" id="cpf" type="text" placeholder="000.000.000-00" required autofocus>
    </div>

    <div class="form-group" style="margin-bottom: 1.5rem;">
        <label for="password" style="display: block; font-weight: 600; margin-bottom: 0.5rem; color: var(--text-main);">Senha</label>
        <input name="password" id="password" type="password" placeholder="Sua senha secreta..." required>
    </div>

    <button type="submit" class="btn-primary">
        <i class="fas fa-sign-in-alt" style="margin-right: 0.5rem;"></i> Acessar Portal
    </button>
</form>

<div class="footer-text" style="text-align: center; margin-top: 1.5rem;">
    <p style="margin-bottom: 0.5rem;">Esqueceu sua senha? <a href="{{ route('portal.password.request') }}">Clique aqui</a></p>
    <a href="{{ route('login') }}" style="color: var(--text-muted); font-size: 0.85rem; text-decoration: none;"><i class="fas fa-arrow-left"></i> Ir para o Login do Gestor</a>
</div>
@endsection
