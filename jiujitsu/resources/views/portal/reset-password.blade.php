@extends('layouts.guest')
@section('content')
<div style="text-align: center; margin-bottom: 2rem;">
    <h1 style="font-size: 1.5rem; font-weight: 700; color: #1e40af;">Cadastrar Nova Senha</h1>
    <p style="color: var(--text-muted); margin-top: 0.5rem;">Escolha uma nova senha de acesso para o seu Portal do Aluno.</p>
</div>

<form action="{{ route('portal.password.update') }}" method="POST">
    @csrf
    
    <input type="hidden" name="token" value="{{ $token }}">

    @if ($errors->any())
        <div class="alert-error" style="background-color: #fee2e2; color: #b91c1c; padding: 1rem; border-radius: 0.5rem; margin-bottom: 1.5rem;">
            <i class="fas fa-exclamation-circle" style="margin-right: 0.5rem;"></i>
            {{ $errors->first() }}
        </div>
    @endif

    <div class="form-group" style="margin-bottom: 1.5rem;">
        <label for="email" style="display: block; font-weight: 600; margin-bottom: 0.5rem;">E-mail do Aluno</label>
        <input name="email" id="email" type="email" value="{{ $email ?? old('email') }}" readonly style="width: 100%; padding: 0.75rem; border: 1px solid #d1d5db; background-color: #f3f4f6; border-radius: 0.5rem; color: #6b7280;">
    </div>

    <div class="form-group" style="margin-bottom: 1.5rem;">
        <label for="password" style="display: block; font-weight: 600; margin-bottom: 0.5rem;">Nova Senha</label>
        <input name="password" id="password" type="password" placeholder="Digite aqui..." required autofocus style="width: 100%; padding: 0.75rem; border: 1px solid #d1d5db; border-radius: 0.5rem;">
    </div>

    <div class="form-group" style="margin-bottom: 1.5rem;">
        <label for="password_confirmation" style="display: block; font-weight: 600; margin-bottom: 0.5rem;">Confirmar Nova Senha</label>
        <input name="password_confirmation" id="password_confirmation" type="password" placeholder="Digite aqui..." required style="width: 100%; padding: 0.75rem; border: 1px solid #d1d5db; border-radius: 0.5rem;">
    </div>

    <button type="submit" class="btn" style="width: 100%; padding: 0.75rem; background-color: #1e40af; color: white; border: none; border-radius: 0.5rem; font-weight: 600; cursor: pointer; transition: 0.2s;">
        Salvar Nova Senha
    </button>
</form>
@endsection
