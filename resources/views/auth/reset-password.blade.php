@extends('layouts.guest')

@section('content')
<div style="text-align: center; margin-bottom: 2rem;">
    <h1 style="font-size: 1.5rem; font-weight: 700;">Redefinir Senha</h1>
    <p style="color: var(--text-muted); margin-top: 0.5rem;">Crie uma nova senha para sua conta.</p>
</div>

<form action="{{ route('password.update') }}" method="POST">
    @csrf
    
    <!-- Password Reset Token -->
    <input type="hidden" name="token" value="{{ $token }}">

    @if ($errors->any())
        <div class="alert-error" style="background-color: #fee2e2; color: #b91c1c; padding: 1rem; border-radius: 0.5rem; margin-bottom: 1.5rem;">
            <i class="fas fa-exclamation-circle" style="margin-right: 0.5rem;"></i>
            {{ $errors->first() }}
        </div>
    @endif

    <div class="form-group" style="margin-bottom: 1.5rem;">
        <label for="email" style="display: block; font-weight: 600; margin-bottom: 0.5rem;">Email</label>
        <input name="email" id="email" type="email" value="{{ request()->email ?? old('email') }}" required readonly style="width: 100%; padding: 0.75rem; border: 1px solid #d1d5db; border-radius: 0.5rem; background-color: #f3f4f6; color: #6b7280; cursor: not-allowed;">
    </div>

    <div class="form-group" style="margin-bottom: 1.5rem;">
        <label for="password" style="display: block; font-weight: 600; margin-bottom: 0.5rem;">Nova Senha</label>
        <input name="password" id="password" type="password" placeholder="Digite aqui..." required autofocus style="width: 100%; padding: 0.75rem; border: 1px solid #d1d5db; border-radius: 0.5rem;">
    </div>

    <div class="form-group" style="margin-bottom: 1.5rem;">
        <label for="password_confirmation" style="display: block; font-weight: 600; margin-bottom: 0.5rem;">Confirmar Nova Senha</label>
        <input name="password_confirmation" id="password_confirmation" type="password" placeholder="Digite aqui..." required style="width: 100%; padding: 0.75rem; border: 1px solid #d1d5db; border-radius: 0.5rem;">
    </div>

    <button type="submit" class="btn" style="width: 100%; padding: 0.75rem; background-color: #10b981; color: white; border: none; border-radius: 0.5rem; font-weight: 600; cursor: pointer;">
        Salvar Nova Senha
    </button>
</form>
@endsection
