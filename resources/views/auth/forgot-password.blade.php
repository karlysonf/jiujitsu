@extends('layouts.guest')

@section('content')
<div style="text-align: center; margin-bottom: 2rem;">
    <h1 style="font-size: 1.5rem; font-weight: 700;">Recuperar Senha</h1>
    <p style="color: var(--text-muted); margin-top: 0.5rem;">Informe seu email para receber o link de redefinição de senha.</p>
</div>

@if (session('status'))
    <div class="alert-success" style="background-color: #d1fae5; color: #065f46; padding: 1rem; border-radius: 0.5rem; margin-bottom: 1.5rem;">
        <i class="fas fa-check-circle" style="margin-right: 0.5rem;"></i>
        {{ session('status') }}
    </div>
@endif

<form action="{{ route('password.email') }}" method="POST">
    @csrf

    @if ($errors->any())
        <div class="alert-error" style="background-color: #fee2e2; color: #b91c1c; padding: 1rem; border-radius: 0.5rem; margin-bottom: 1.5rem;">
            <i class="fas fa-exclamation-circle" style="margin-right: 0.5rem;"></i>
            {{ $errors->first() }}
        </div>
    @endif

    <div class="form-group" style="margin-bottom: 1.5rem;">
        <label for="email" style="display: block; font-weight: 600; margin-bottom: 0.5rem;">Email</label>
        <input name="email" id="email" type="email" placeholder="Digite aqui..." value="{{ old('email') }}" required autofocus style="width: 100%; padding: 0.75rem; border: 1px solid #d1d5db; border-radius: 0.5rem;">
    </div>

    <button type="submit" class="btn" style="width: 100%; padding: 0.75rem; background-color: #3b82f6; color: white; border: none; border-radius: 0.5rem; font-weight: 600; cursor: pointer;">
        Enviar link de recuperação
    </button>
</form>

<div class="footer-text" style="text-align: center; margin-top: 1.5rem;">
    Lembrou sua senha? <a href="{{ route('login') }}" style="color: #3b82f6; text-decoration: none;">Voltar ao login</a>
</div>
@endsection
