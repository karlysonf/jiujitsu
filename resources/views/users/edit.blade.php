@extends('layouts.app')

@section('content')
<style>
    :root {
        --card-border: #E2E8F0;
        --input-bg: #FFFFFF;
        --input-border: #CBD5E1;
        --input-focus: #2563EB;
        --section-title: #475569;
    }

    .registration-container {
        max-width: 1000px;
        margin: 0 auto;
        padding-bottom: 5rem;
    }

    .registration-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 2rem;
    }

    .registration-title h1 {
        font-size: 1.5rem;
        font-weight: 800;
        color: #1E293B;
        margin-bottom: 0.25rem;
    }

    .registration-title p {
        color: #64748B;
        font-size: 0.9375rem;
    }

    .header-actions {
        display: flex;
        gap: 0.75rem;
    }

    .btn-cancel {
        background: white;
        color: #475569;
        border: 1px solid var(--card-border);
        padding: 0.75rem 1.5rem;
        border-radius: 0.5rem;
        font-weight: 600;
        transition: all 0.2s;
    }

    .btn-cancel:hover {
        background: #F8FAFC;
    }

    .btn-save {
        background: #0F172A;
        color: white;
        border: none;
        padding: 0.75rem 1.5rem;
        border-radius: 0.5rem;
        font-weight: 600;
        transition: all 0.2s;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .btn-save:hover {
        background: #1E293B;
        transform: translateY(-1px);
    }

    .form-section {
        background: white;
        border: 1px solid var(--card-border);
        border-radius: 0.75rem;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    }

    .section-header {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        margin-bottom: 1.5rem;
        padding-bottom: 0.75rem;
        border-bottom: 1px solid #F1F5F9;
    }

    .section-icon {
        width: 32px;
        height: 32px;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #3B82F6;
        font-size: 1rem;
    }

    .section-header h2 {
        font-size: 1rem;
        font-weight: 700;
        color: var(--section-title);
        margin: 0;
    }

    /* Photo Upload */
    .photo-upload-container {
        display: flex;
        align-items: center;
        gap: 2rem;
    }

    .avatar-placeholder {
        width: 100px;
        height: 100px;
        border: 2px dashed #CBD5E1;
        border-radius: 0.75rem;
        background: #F8FAFC;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #94A3B8;
        font-size: 2rem;
        position: relative;
    }

    .avatar-btn {
        position: absolute;
        bottom: -8px;
        right: -8px;
        width: 28px;
        height: 28px;
        background: #3B82F6;
        color: white;
        border: 2px solid white;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 0.75rem;
        cursor: pointer;
    }

    .upload-info h3 {
        font-size: 1rem;
        font-weight: 700;
        margin-bottom: 0.25rem;
        color: #1E293B;
    }

    .upload-info p {
        font-size: 0.8125rem;
        color: #64748B;
        margin-bottom: 0.5rem;
    }

    .upload-link {
        color: #3B82F6;
        font-size: 0.8125rem;
        font-weight: 600;
        text-decoration: none;
        display: flex;
        align-items: center;
        gap: 0.25rem;
    }

    /* Form Grid */
    .form-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 1.25rem;
    }

    .full-width {
        grid-column: 1 / -1;
    }

    .form-group label {
        display: block;
        font-size: 0.8125rem;
        font-weight: 600;
        color: #64748B;
        margin-bottom: 0.5rem;
    }

    .form-control {
        width: 100%;
        background: #F8FAFC;
        border: 1px solid var(--input-border);
        border-radius: 0.5rem;
        padding: 0.75rem 1rem;
        font-size: 0.9375rem;
        color: #1E293B;
        transition: all 0.2s;
    }

    .form-control:focus {
        background: white;
        border-color: var(--input-focus);
        box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
        outline: none;
    }

    /* Segmented Control for Degrees */
    .segmented-control {
        display: flex;
        background: #F1F5F9;
        padding: 0.25rem;
        border-radius: 0.5rem;
        gap: 0.25rem;
    }

    .segment-item {
        flex: 1;
    }

    .segment-item input {
        display: none;
    }

    .segment-label {
        display: block;
        text-align: center;
        padding: 0.5rem;
        border-radius: 0.375rem;
        font-size: 0.875rem;
        font-weight: 600;
        color: #64748B;
        cursor: pointer;
        transition: all 0.2s;
    }

    .segment-item input:checked + .segment-label {
        background: white;
        color: #1E293B;
        box-shadow: 0 1px 2px rgba(0,0,0,0.05);
    }

    /* Plan Cards */
    .plan-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
    }

    .plan-option {
        position: relative;
    }

    .plan-option input {
        display: none;
    }

    .plan-card {
        border: 1px solid var(--card-border);
        border-radius: 0.75rem;
        padding: 1.25rem;
        cursor: pointer;
        transition: all 0.2s;
        height: 100%;
        display: flex;
        flex-direction: column;
    }

    .plan-option input:checked + .plan-card {
        border-color: #3B82F6;
        background: #EFF6FF;
        box-shadow: 0 0 0 1px #3B82F6;
    }

    .plan-name {
        font-size: 0.75rem;
        font-weight: 700;
        color: #64748B;
        text-transform: uppercase;
        margin-bottom: 0.5rem;
    }

    .plan-price {
        font-size: 1.25rem;
        font-weight: 800;
        color: #1E293B;
        margin-bottom: 1rem;
    }

    .plan-price span {
        font-size: 0.8125rem;
        font-weight: 500;
        color: #94A3B8;
    }

    .plan-badge {
        position: absolute;
        top: -10px;
        right: 10px;
        background: #3B82F6;
        color: white;
        font-size: 0.625rem;
        font-weight: 800;
        padding: 0.25rem 0.5rem;
        border-radius: 1rem;
        text-transform: uppercase;
    }

    .plan-features {
        margin-top: auto;
        font-size: 0.75rem;
        color: #3B82F6;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 0.375rem;
    }

    .footer-help {
        text-align: center;
        margin-top: 2rem;
        color: #64748B;
        font-size: 0.8125rem;
        max-width: 500px;
        margin-left: auto;
        margin-right: auto;
        line-height: 1.5;
    }

    .main-submit-btn {
        width: 100%;
        max-width: 280px;
        margin-left: auto;
        background: #0F172A;
        color: white;
        border: none;
        padding: 1rem;
        border-radius: 0.5rem;
        font-weight: 700;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.75rem;
        margin-top: 2rem;
        cursor: pointer;
    }

    .plan-header-badge {
        background: #EEF2FF;
        color: #4F46E5;
        font-size: 0.6875rem;
        font-weight: 700;
        padding: 0.25rem 0.75rem;
        border-radius: 0.25rem;
        text-transform: uppercase;
    }
</style>

<div class="registration-container">
    <form action="{{ route('users.update', $user) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        
        <div class="registration-header">
            <div class="registration-title">
                <h1>Editar Cadastro</h1>
                <p>Atualize as informações de <strong>{{ $user->name }}</strong>.</p>
            </div>
            <div class="header-actions">
                <a href="{{ route('users.index') }}" class="btn-cancel">Cancelar</a>
                <button type="submit" class="btn-save">Salvar Alterações</button>
            </div>
        </div>

        <!-- Photo Section -->
        <div class="form-section">
            <div class="photo-upload-container">
                <!-- Input file hidden -->
                <input type="file" id="photo_input" name="photo" accept="image/jpg,image/jpeg,image/png,image/webp" style="display:none;">

                <div class="avatar-placeholder" id="avatar_preview" onclick="document.getElementById('photo_input').click()" style="cursor:pointer;" title="Clique para selecionar uma foto">
                    @if($user->photo)
                        <img src="{{ Storage::disk('public')->url($user->photo) }}" id="preview_img" alt="Foto do aluno" style="width:100%;height:100%;object-fit:cover;border-radius:0.75rem;">
                    @else
                        <i class="fas fa-user" id="preview_icon"></i>
                        <img id="preview_img" src="" alt="Preview" style="display:none;width:100%;height:100%;object-fit:cover;border-radius:0.75rem;">
                    @endif
                    <div class="avatar-btn" onclick="event.stopPropagation(); document.getElementById('photo_input').click()">
                        <i class="fas fa-camera"></i>
                    </div>
                </div>
                <div class="upload-info">
                    <h3>Foto do Aluno</h3>
                    <p>Recomendado: JPG, PNG ou WebP, mín. 400x400px, máx. 2MB.</p>
                    <a href="#" class="upload-link" onclick="event.preventDefault(); document.getElementById('photo_input').click()">
                        <i class="fas fa-upload"></i> {{ $user->photo ? 'Alterar foto' : 'Carregar nova imagem' }}
                    </a>
                    <p id="photo_name" style="margin-top:0.5rem;font-size:0.75rem;color:#3B82F6;display:none;"></p>
                    @error('photo')
                        <p style="color:#EF4444;font-size:0.8125rem;margin-top:0.5rem;"><i class="fas fa-exclamation-circle"></i> {{ $message }}</p>
                    @enderror
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-[1.8fr_1fr] gap-6 items-start">
            <!-- Personal Data Section -->
            <div class="form-section">
                <div class="section-header">
                    <div class="section-icon"><i class="fas fa-id-card"></i></div>
                    <h2>Dados Pessoais</h2>
                </div>
                
                <div class="form-grid">
                    <div class="form-group col-span-1 lg:col-span-2">
                        <label>Nome Completo</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" placeholder="Ex: Rodrigo Cavalcanti" required>
                    </div>
                    
                    <div class="form-group">
                        <label>CPF</label>
                        <input type="text" name="cpf" class="form-control" value="{{ old('cpf', $user->cpf) }}" placeholder="000.000.000-00" required>
                    </div>
                    
                    <div class="form-group">
                        <label>Data de Nascimento</label>
                        <input type="date" name="data_nascimento" class="form-control" value="{{ old('data_nascimento', $user->data_nascimento ? $user->data_nascimento->format('Y-m-d') : '') }}">
                    </div>
                    
                    <div class="form-group">
                        <label>E-mail</label>
                        <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" placeholder="contato@exemplo.com">
                    </div>
                    
                    <div class="form-group">
                        <label>Telefone</label>
                        <input type="text" name="telefone" class="form-control" value="{{ old('telefone', $user->telefone) }}" placeholder="(00) 00000-0000">
                    </div>

                    <div class="form-group">
                        <label>Status</label>
                        <select name="status" class="form-control" required>
                            <option value="active" {{ old('status', $user->status) == 'active' ? 'selected' : '' }}>Ativo</option>
                            <option value="inactive" {{ old('status', $user->status) == 'inactive' ? 'selected' : '' }}>Inativo</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Tipo de Usuário</label>
                        <select name="user_role" class="form-control" required>
                            <option value="aluno" {{ old('user_role', $user->roles->first()->name ?? 'aluno') == 'aluno' ? 'selected' : '' }}>Aluno</option>
                            <option value="professor" {{ old('user_role', $user->roles->first()->name ?? 'aluno') == 'professor' ? 'selected' : '' }}>Professor</option>
                            <option value="instrutor" {{ old('user_role', $user->roles->first()->name ?? 'aluno') == 'instrutor' ? 'selected' : '' }}>Instrutor</option>
                            <option value="admin" {{ old('user_role', $user->roles->first()->name ?? 'aluno') == 'admin' ? 'selected' : '' }}>Administrador</option>
                        </select>
                    </div>

                    <div class="form-group col-span-1 lg:col-span-2">
                        <label>Alterar Senha (deixe em branco para manter a atual)</label>
                        <input type="password" name="password" class="form-control" placeholder="Mínimo 8 caracteres">
                    </div>
                </div>
            </div>

            <!-- Training Info Section -->
            <div class="form-section">
                <div class="section-header">
                    <div class="section-icon"><i class="fas fa-running"></i></div>
                    <h2>Informações de Treino</h2>
                </div>
                
                <div class="form-group" style="margin-bottom: 1.25rem;">
                    <label>Faixa Atual</label>
                    <select name="faixa" class="form-control" required>
                        @foreach(['Branca', 'Cinza', 'Cinza/Branca', 'Cinza/Preta', 'Amarela', 'Amarela/Branca', 'Amarela/Preta', 'Laranja', 'Laranja/Branca', 'Laranja/Preta', 'Verde', 'Verde/Branca', 'Verde/Preta', 'Azul', 'Roxa', 'Marrom', 'Preta'] as $belt)
                        <option value="{{ $belt }}" {{ old('faixa', $user->faixa) == $belt ? 'selected' : '' }}>{{ $belt }}</option>
                        @endforeach
                    </select>
                </div>
                
                <div class="form-group" style="margin-bottom: 1.25rem;">
                    <label>Quantidade de Graus</label>
                    <div class="segmented-control">
                        @foreach([0, 1, 2, 3, 4] as $degree)
                        <div class="segment-item">
                            <input type="radio" name="grau" id="grau_{{ $degree }}" value="{{ $degree }}" {{ old('grau', $user->grau ?? 0) == $degree ? 'checked' : '' }}>
                            <label for="grau_{{ $degree }}" class="segment-label">{{ $degree }}</label>
                        </div>
                        @endforeach
                    </div>
                </div>
                
                <div class="form-group">
                    <label>Data de Início</label>
                    <input type="date" name="start_date" class="form-control" value="{{ old('start_date', $user->start_date ? $user->start_date->format('Y-m-d') : '') }}" required>
                </div>
            </div>
        </div>

        <!-- Plan Selection Section -->
        <div class="form-section">
            <div class="section-header" style="justify-content: space-between;">
                <div style="display: flex; align-items: center; gap: 0.75rem;">
                    <div class="section-icon"><i class="fas fa-wallet"></i></div>
                    <h2>Seleção de Plano</h2>
                </div>
                <div class="plan-header-badge">Alteração de plano afeta próxima fatura</div>
            </div>
            
            <div class="plan-grid">
                @foreach($plans as $plan)
                <label class="plan-option">
                    <input type="radio" name="plan_id" value="{{ $plan->id }}" {{ old('plan_id', $user->plan_id) == $plan->id ? 'checked' : '' }} required>
                    <div class="plan-card">
                        <div class="plan-name">{{ $plan->name }}</div>
                        <div class="plan-price">R$ {{ number_format($plan->price, 0, ',', '.') }}<span>/mês</span></div>
                        <div class="plan-features">
                            <i class="fas fa-check-circle"></i> 
                            {{ $plan->name === 'Cortesia' ? 'Isenção total' : 'Flexibilidade total' }}
                        </div>
                    </div>
                </label>
                @endforeach
            </div>

            <div class="form-group mt-6">
                <label style="display: block; font-size: 0.8125rem; font-weight: 600; color: #64748B; margin-bottom: 0.5rem;">Valor Personalizado da Mensalidade (opcional)</label>
                <div style="position: relative; max-width: 300px;">
                    <span style="position: absolute; left: 1rem; top: 50%; transform: translateY(-50%); font-weight: 600; color: #64748B;">R$</span>
                    <input type="number" step="0.01" min="0" name="custom_price" class="form-control" style="padding-left: 2.5rem;" value="{{ old('custom_price', $user->custom_price) }}" placeholder="Ex: 60,00">
                </div>
                <p style="font-size: 0.75rem; color: #64748B; margin-top: 0.375rem; line-height: 1.4;">
                    Deixe em branco para usar o valor padrão do plano selecionado. Preencha caso este aluno tenha um desconto ou valor diferenciado acordado.
                </p>
                @error('custom_price')
                    <p style="color:#EF4444;font-size:0.8125rem;margin-top:0.5rem;"><i class="fas fa-exclamation-circle"></i> {{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="footer-help">
            As alterações realizadas aqui serão aplicadas imediatamente ao perfil do aluno. Certifique-se de conferir os dados antes de salvar.
        </div>

        <button type="submit" class="main-submit-btn">
            <i class="fas fa-save"></i> Salvar Alterações
        </button>
    </form>
</div>

<script>
    // Preview de imagem ao selecionar arquivo
    document.getElementById('photo_input').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (!file) return;

        const previewImg = document.getElementById('preview_img');
        const previewIcon = document.getElementById('preview_icon');
        const photoName = document.getElementById('photo_name');

        const reader = new FileReader();
        reader.onload = function(ev) {
            previewImg.src = ev.target.result;
            previewImg.style.display = 'block';
            if (previewIcon) previewIcon.style.display = 'none';
        };
        reader.readAsDataURL(file);

        photoName.textContent = '\u2713 ' + file.name;
        photoName.style.display = 'block';
    });

    document.addEventListener('DOMContentLoaded', function() {
        // Masks
        const cpfInput = document.querySelector('input[name="cpf"]');
        const phoneInput = document.querySelector('input[name="telefone"]');

        if(cpfInput) {
            cpfInput.addEventListener('input', function(e) {
                let value = e.target.value.replace(/\D/g, '');
                if (value.length > 11) value = value.slice(0, 11);
                
                if (value.length > 9) {
                    value = value.replace(/(\d{3})(\d{3})(\d{3})(\d{2})/, "$1.$2.$3-$4");
                } else if (value.length > 6) {
                    value = value.replace(/(\d{3})(\d{3})(\d{1,3})/, "$1.$2.$3");
                } else if (value.length > 3) {
                    value = value.replace(/(\d{3})(\d{1,3})/, "$1.$2");
                }
                e.target.value = value;
            });
        }

        if(phoneInput) {
            phoneInput.addEventListener('input', function(e) {
                let value = e.target.value.replace(/\D/g, '');
                if (value.length > 11) value = value.slice(0, 11);
                
                if (value.length > 10) {
                    value = value.replace(/(\d{2})(\d{5})(\d{4})/, "($1) $2-$3");
                } else if (value.length > 6) {
                    value = value.replace(/(\d{2})(\d{4})(\d{0,4})/, "($1) $2-$3");
                } else if (value.length > 2) {
                    value = value.replace(/(\d{2})(\d{0,5})/, "($1) $2");
                } else if (value.length > 0) {
                    value = value.replace(/(\d{0,2})/, "($1");
                }
                e.target.value = value;
            });
        }
    });
</script>
@endsection
