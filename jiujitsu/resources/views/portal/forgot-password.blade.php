@extends('layouts.guest')
@section('content')
<div style="text-align: center; margin-bottom: 2rem;">
    <h1 style="font-size: 1.5rem; font-weight: 700; color: #1e40af;">Recuperar Acesso</h1>
    <p style="color: var(--text-muted); margin-top: 0.5rem;">Esqueceu sua senha do Portal do Aluno? Informe seu e-mail para enviarmos um link de recuperação.</p>
</div>

@if (session('status') || session('success'))
    <div class="alert-success" style="background-color: #dcfce3; color: #166534; padding: 1rem; border-radius: 0.5rem; margin-bottom: 1.5rem;">
        <i class="fas fa-check-circle" style="margin-right: 0.5rem;"></i>
        {{ session('status') ?? session('success') }}
    </div>
@endif

<form action="{{ route('portal.password.email') }}" method="POST">
    @csrf

    @if ($errors->any())
        <div class="alert-error" style="background-color: #fee2e2; color: #b91c1c; padding: 1rem; border-radius: 0.5rem; margin-bottom: 1.5rem;">
            <i class="fas fa-exclamation-circle" style="margin-right: 0.5rem;"></i>
            {{ $errors->first() }}
        </div>
    @endif

    <div class="form-group" style="margin-bottom: 1.5rem;">
        <label for="email" style="display: block; font-weight: 600; margin-bottom: 0.5rem;">E-mail do Aluno</label>
        <input name="email" id="email" type="email" placeholder="Digite aqui..." value="{{ old('email') }}" required autofocus style="width: 100%; padding: 0.75rem; border: 1px solid #d1d5db; border-radius: 0.5rem;">
    </div>

    <button type="submit" class="btn" style="width: 100%; padding: 0.75rem; background-color: #1e40af; color: white; border: none; border-radius: 0.5rem; font-weight: 600; cursor: pointer; transition: 0.2s;">
        Enviar link de recuperação
    </button>
</form>

<div class="footer-text" style="text-align: center; margin-top: 1.5rem;">
    <a href="{{ route('portal.login') }}" style="color: #6b7280; font-size: 0.9rem; text-decoration: none;">
        <i class="fas fa-arrow-left"></i> Voltar ao Login do Aluno
    </a>
</div>
@endsection
