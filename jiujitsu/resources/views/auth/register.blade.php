@extends('layouts.guest')

@section('content')
<style>
    .card { max-width: 700px !important; }
    .grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 1.25rem; }
    .section-title { font-size: 1rem; font-weight: 700; color: var(--primary); margin: 2rem 0 1rem; border-bottom: 1px solid #E5E7EB; padding-bottom: 0.5rem; }
    .section-title:first-of-type { margin-top: 0; }
    .checkbox-group { display: flex; flex-direction: column; align-items: flex-start; gap: 0.75rem; background: #F9FAFB; padding: 1.25rem; border-radius: 0.75rem; border: 1px solid #E5E7EB; margin-bottom: 1rem; width: 100%; text-align: left; }
    .checkbox-row { display: flex; align-items: center; gap: 0.75rem; cursor: pointer; width: 100%; text-align: left; }
    .hidden { display: none; }
    @media (max-width: 600px) { .grid-2 { grid-template-columns: 1fr; } }
</style>

<div style="text-align: center; margin-bottom: 2rem;">
    <h1 style="font-size: 1.5rem; font-weight: 700;">Crie sua conta</h1>
    <p style="color: var(--text-muted); margin-top: 0.5rem;">Preencha os dados abaixo para se cadastrar.</p>
</div>

<form action="{{ route('register.post') }}" method="POST" style="text-align: left; width: 100%;">
    @csrf

    @if ($errors->any())
        <div class="alert-error">
            <i class="fas fa-exclamation-circle" style="margin-right: 0.5rem;"></i>
            {{ $errors->first() }}
        </div>
    @endif

    <div class="section-title">Dados Pessoais</div>
    <div class="grid-2">
        <div class="form-group" style="grid-column: span 2;">
            <label for="name">Nome Completo</label>
            <input name="name" id="name" type="text" value="{{ old('name') }}" required placeholder="Seu nome completo">
        </div>
        <div class="form-group">
            <label for="cpf">CPF</label>
            <input name="cpf" id="cpf" type="text" value="{{ old('cpf') }}" required placeholder="000.000.000-00">
        </div>
        <div class="form-group">
            <label for="telefone">Telefone</label>
            <input name="telefone" id="telefone" type="text" value="{{ old('telefone') }}" required placeholder="(00) 00000-0000">
        </div>
        <div class="form-group">
            <label for="data_nascimento">Data de Nascimento</label>
            <input name="data_nascimento" id="data_nascimento" type="date" value="{{ old('data_nascimento') }}" required onchange="checkAge(this.value)">
        </div>
        <div class="form-group">
            <label for="sexo">Sexo</label>
            <select name="sexo" id="sexo" required style="width: 100%; padding: 0.75rem; border-radius: 0.75rem; border: 1px solid #D1D5DB;">
                <option value="">Selecione</option>
                <option value="M" {{ old('sexo') == 'M' ? 'selected' : '' }}>Masculino</option>
                <option value="F" {{ old('sexo') == 'F' ? 'selected' : '' }}>Feminino</option>
                <option value="Outro" {{ old('sexo') == 'Outro' ? 'selected' : '' }}>Outro</option>
            </select>
        </div>
        <div class="form-group" style="grid-column: span 2;">
            <label for="email">E-mail</label>
            <input name="email" id="email" type="email" value="{{ old('email') }}" required placeholder="seu@email.com">
        </div>
        <div class="form-group" style="grid-column: span 2;">
            <label for="endereco">Endereço Completo</label>
            <input name="endereco" id="endereco" type="text" value="{{ old('endereco') }}" required placeholder="Rua, número, bairro, cidade">
        </div>
    </div>

    <div id="guardian-section" class="hidden">
        <div class="section-title">Dados do Responsável</div>
        <div class="grid-2">
            <div class="form-group">
                <label for="nome_responsavel">Nome do Responsável</label>
                <input name="nome_responsavel" id="nome_responsavel" type="text" value="{{ old('nome_responsavel') }}">
            </div>
            <div class="form-group">
                <label for="cpf_responsavel">CPF do Responsável</label>
                <input name="cpf_responsavel" id="cpf_responsavel" type="text" value="{{ old('cpf_responsavel') }}">
            </div>
            <div class="form-group">
                <label for="telefone_responsavel">Telefone do Responsável</label>
                <input name="telefone_responsavel" id="telefone_responsavel" type="text" value="{{ old('telefone_responsavel') }}">
            </div>
        </div>
    </div>

    <div class="section-title">Informações de Treino</div>
    <div class="grid-2">
        <div class="form-group">
            <label for="faixa">Faixa Atual</label>
            <select name="faixa" id="faixa" required style="width: 100%; padding: 0.75rem; border-radius: 0.75rem; border: 1px solid #D1D5DB;">
                @foreach(['Branca', 'Cinza', 'Amarela', 'Laranja', 'Verde', 'Azul', 'Roxa', 'Marrom', 'Preta'] as $f)
                    <option value="{{ $f }}" {{ old('faixa') == $f ? 'selected' : '' }}>{{ $f }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="grau">Grau</label>
            <input name="grau" id="grau" type="number" min="0" max="4" value="{{ old('grau', 0) }}" required>
        </div>
        <div class="form-group">
            <label for="peso">Peso (kg)</label>
            <input name="peso" id="peso" type="number" step="0.1" value="{{ old('peso') }}" required>
        </div>
        <div class="form-group">
            <label for="vencimento_mensalidade">Dia de Vencimento</label>
            <select name="vencimento_mensalidade" id="vencimento_mensalidade" required style="width: 100%; padding: 0.75rem; border-radius: 0.75rem; border: 1px solid #D1D5DB;">
                @foreach(['05', '10', '15', '20', '25', '30'] as $dia)
                    <option value="{{ $dia }}" {{ old('vencimento_mensalidade') == $dia ? 'selected' : '' }}>Dia {{ $dia }}</option>
                @endforeach
            </select>
        </div>
    </div>

    <div class="section-title">Informações de Saúde</div>
    
    <div class="checkbox-group">
        <label class="checkbox-row">
            <input type="hidden" name="possui_lesao" value="0">
            <input type="checkbox" name="possui_lesao" value="1" id="possui_lesao" {{ old('possui_lesao') ? 'checked' : '' }} onchange="toggleHealthDesc('possui_lesao', 'desc_lesao')">
            <span style="font-weight: 500;">Possui alguma lesão?</span>
        </label>
        <div id="desc_lesao" class="{{ old('possui_lesao') ? '' : 'hidden' }}" style="width: 100%;">
            <textarea name="descricao_lesao" placeholder="Descreva a lesão..." style="width: 100%; padding: 0.75rem; border-radius: 0.5rem; border: 1px solid #D1D5DB;">{{ old('descricao_lesao') }}</textarea>
        </div>
    </div>

    <div class="checkbox-group">
        <label class="checkbox-row">
            <input type="hidden" name="medicamento_continuo" value="0">
            <input type="checkbox" name="medicamento_continuo" value="1" id="medicamento_continuo" {{ old('medicamento_continuo') ? 'checked' : '' }} onchange="toggleHealthDesc('medicamento_continuo', 'desc_medicamento')">
            <span style="font-weight: 500;">Usa medicamento contínuo?</span>
        </label>
        <div id="desc_medicamento" class="{{ old('medicamento_continuo') ? '' : 'hidden' }}" style="width: 100%;">
            <textarea name="descricao_medicamento" placeholder="Liste os medicamentos..." style="width: 100%; padding: 0.75rem; border-radius: 0.5rem; border: 1px solid #D1D5DB;">{{ old('descricao_medicamento') }}</textarea>
        </div>
    </div>

    <div class="checkbox-group">
        <label class="checkbox-row">
            <input type="hidden" name="problema_cardiaco" value="0">
            <input type="checkbox" name="problema_cardiaco" value="1" id="problema_cardiaco" {{ old('problema_cardiaco') ? 'checked' : '' }} onchange="toggleHealthDesc('problema_cardiaco', 'desc_cardiaco')">
            <span style="font-weight: 500;">Possui problema cardíaco?</span>
        </label>
        <div id="desc_cardiaco" class="{{ old('problema_cardiaco') ? '' : 'hidden' }}" style="width: 100%;">
            <textarea name="descricao_problema_cardiaco" placeholder="Descreva o problema..." style="width: 100%; padding: 0.75rem; border-radius: 0.5rem; border: 1px solid #D1D5DB;">{{ old('descricao_problema_cardiaco') }}</textarea>
        </div>
    </div>

    <div class="form-group">
        <label for="outros">Outras observações de saúde</label>
        <textarea name="outros" id="outros" placeholder="Alergias, restrições, etc." style="width: 100%; padding: 0.75rem; border-radius: 0.5rem; border: 1px solid #D1D5DB;">{{ old('outros') }}</textarea>
    </div>

    <div class="section-title">Credenciais de Acesso</div>
    <div class="grid-2">
        <div class="form-group">
            <label for="login">Nome de Usuário</label>
            <input name="login" id="login" type="text" value="{{ old('login') }}" required placeholder="Ex: user123">
        </div>
        <div></div>
        <div class="form-group">
            <label for="password">Senha</label>
            <input name="password" id="password" type="password" required placeholder="Mínimo 8 caracteres">
        </div>
        <div class="form-group">
            <label for="password_confirmation">Confirmar Senha</label>
            <input name="password_confirmation" id="password_confirmation" type="password" required placeholder="Repita sua senha">
        </div>
    </div>

    <button type="submit" class="btn">
        Finalizar Cadastro
    </button>
</form>

<div class="footer-text">
    Já tem uma conta? <a href="{{ route('login') }}">Faça login</a>
</div>

<script>
    function toggleHealthDesc(checkboxId, descId) {
        const checkbox = document.getElementById(checkboxId);
        const descDiv = document.getElementById(descId);
        const textarea = descDiv.querySelector('textarea');
        
        if (checkbox.checked) {
            descDiv.classList.remove('hidden');
            textarea.setAttribute('required', 'required');
        } else {
            descDiv.classList.add('hidden');
            textarea.removeAttribute('required');
            textarea.value = '';
        }
    }

    function checkAge(birthDate) {
        if (!birthDate) return;
        const today = new Date();
        const birth = new Date(birthDate);
        let age = today.getFullYear() - birth.getFullYear();
        const m = today.getMonth() - birth.getMonth();
        if (m < 0 || (m === 0 && today.getDate() < birth.getDate())) age--;
        
        const section = document.getElementById('guardian-section');
        const inputs = section.querySelectorAll('input');
        
        if (age < 18) {
            section.classList.remove('hidden');
            inputs.forEach(input => input.setAttribute('required', 'required'));
        } else {
            section.classList.add('hidden');
            inputs.forEach(input => input.removeAttribute('required'));
        }
    }

    // Input Masks
    function maskCPF(i) {
        let v = i.value.replace(/\D/g, "");
        if (v.length > 11) v = v.substring(0, 11);
        i.value = v.replace(/(\d{3})(\d{3})(\d{3})(\d{2})/, "$1.$2.$3-$4");
    }

    function maskPhone(i) {
        let v = i.value.replace(/\D/g, "");
        if (v.length > 11) v = v.substring(0, 11);
        if (v.length > 2) v = "(" + v.substring(0, 2) + ") " + v.substring(2);
        if (v.length > 9) v = v.substring(0, 10) + "-" + v.substring(10);
        i.value = v;
    }

    document.getElementById('cpf')?.addEventListener('input', function() { maskCPF(this); });
    document.getElementById('cpf_responsavel')?.addEventListener('input', function() { maskCPF(this); });
    document.getElementById('telefone')?.addEventListener('input', function() { maskPhone(this); });
    document.getElementById('telefone_responsavel')?.addEventListener('input', function() { maskPhone(this); });

    window.onload = function() {
        const birthInput = document.getElementById('data_nascimento');
        if (birthInput?.value) checkAge(birthInput.value);
    }
</script>
@endsection
