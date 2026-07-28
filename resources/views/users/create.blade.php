@extends('layouts.app')

@section('content')
<div class="max-w-[1400px] mx-auto pb-24">
    <form action="{{ isset($user) ? route('users.update', $user) : route('users.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @if(isset($user)) @method('PUT') @endif
        
        <input type="hidden" name="user_role" value="{{ $role ?? 'aluno' }}">

        <!-- Header Section -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6">
            <div>
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-rose-500/10 border border-rose-500/20 text-rose-400 text-xs font-bold uppercase tracking-wider mb-2">
                    <span class="w-2 h-2 rounded-full bg-rose-500 animate-pulse"></span>
                    Novo Praticante
                </div>
                <h1 class="font-['Outfit'] font-black text-2xl md:text-4xl text-white tracking-tight">{{ isset($user) ? 'Editar Cadastro' : 'Cadastro de Novo Aluno' }}</h1>
                <p class="text-slate-400 text-xs md:text-sm mt-0.5">Registre as informações fundamentais para iniciar a jornada do novo praticante no tatame.</p>
            </div>
            <div class="flex items-center gap-3">
                <a href="{{ route('users.index') }}" class="px-5 py-2.5 rounded-xl bg-[#182234] border border-white/10 text-slate-300 font-['Outfit'] font-bold text-xs hover:bg-white/10 transition-colors">
                    Cancelar
                </a>
                <button type="submit" class="px-6 py-2.5 rounded-xl bg-gradient-to-r from-rose-600 to-rose-700 hover:from-rose-500 hover:to-rose-600 text-white font-['Outfit'] font-bold text-xs shadow-lg shadow-rose-900/30 flex items-center gap-2 transition-all active:scale-95">
                    <span class="material-symbols-outlined text-sm">person_add</span>
                    Salvar Cadastro
                </button>
            </div>
        </div>

        <!-- Photo Section Card -->
        <div class="bg-[#111726] border border-white/10 rounded-2xl p-6 shadow-xl mb-6">
            <div class="flex flex-col sm:flex-row items-center gap-6">
                <input type="file" id="photo_input" name="photo" accept="image/jpg,image/jpeg,image/png,image/webp" class="hidden">

                <div class="relative group cursor-pointer" onclick="document.getElementById('photo_input').click()" title="Clique para selecionar uma foto">
                    <div class="w-24 h-24 sm:w-28 sm:h-28 rounded-2xl bg-[#090d16] border-2 border-dashed border-white/20 flex items-center justify-center text-slate-500 overflow-hidden relative group-hover:border-rose-500/50 transition-colors">
                        @if(isset($user) && $user->photo)
                            <img src="{{ Storage::disk('public')->url($user->photo) }}" id="preview_img" alt="Foto do aluno" class="w-full h-full object-cover rounded-xl">
                        @else
                            <span class="material-symbols-outlined text-4xl" id="preview_icon">person</span>
                            <img id="preview_img" src="" alt="Preview" class="hidden w-full h-full object-cover rounded-xl">
                        @endif
                        <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center text-white">
                            <span class="material-symbols-outlined text-2xl">photo_camera</span>
                        </div>
                    </div>
                    <div class="absolute -bottom-2 -right-2 w-8 h-8 rounded-full bg-rose-600 text-white border-2 border-[#111726] flex items-center justify-center shadow-lg">
                        <span class="material-symbols-outlined text-xs">add_a_photo</span>
                    </div>
                </div>

                <div class="flex-1 text-center sm:text-left">
                    <h3 class="font-['Outfit'] font-bold text-lg text-white">Foto do Aluno</h3>
                    <p class="text-slate-400 text-xs mt-1 mb-3">Formatos aceitos: JPG, PNG ou WebP (máx. 2MB). Recomendado proporção quadrada 400x400px.</p>
                    <button type="button" onclick="document.getElementById('photo_input').click()" class="inline-flex items-center gap-2 text-xs font-bold text-rose-400 hover:text-rose-300 transition-colors">
                        <span class="material-symbols-outlined text-base">upload</span>
                        Carregar imagem
                    </button>
                    <p id="photo_name" class="mt-2 text-xs font-semibold text-cyan-400 hidden"></p>
                    @error('photo')
                        <p class="mt-2 text-xs text-rose-400 font-semibold flex items-center gap-1">
                            <span class="material-symbols-outlined text-sm">error</span> {{ $message }}
                        </p>
                    @enderror
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
            <!-- Personal Data Section (2 Cols) -->
            <div class="lg:col-span-2 bg-[#111726] border border-white/10 rounded-2xl p-6 shadow-xl">
                <div class="flex items-center gap-3 pb-4 mb-6 border-b border-white/10">
                    <div class="w-10 h-10 rounded-xl bg-cyan-500/10 border border-cyan-500/20 text-cyan-400 flex items-center justify-center">
                        <span class="material-symbols-outlined">badge</span>
                    </div>
                    <div>
                        <h2 class="font-['Outfit'] font-bold text-lg text-white">Dados Pessoais</h2>
                        <p class="text-slate-400 text-xs">Informações cadastrais básicas do praticante</p>
                    </div>
                </div>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="sm:col-span-2">
                        <label class="block text-xs font-semibold text-slate-300 mb-1.5">Nome Completo <span class="text-rose-500">*</span></label>
                        <input type="text" name="name" class="w-full bg-[#090d16] border border-white/10 rounded-xl px-4 py-2.5 text-sm text-white placeholder-slate-500 focus:outline-none focus:border-rose-500 transition-all" value="{{ old('name', $user->name ?? '') }}" placeholder="Ex: Rodrigo Cavalcanti" required>
                    </div>
                    
                    <div>
                        <label class="block text-xs font-semibold text-slate-300 mb-1.5">CPF <span class="text-rose-500">*</span></label>
                        <input type="text" name="cpf" class="w-full bg-[#090d16] border border-white/10 rounded-xl px-4 py-2.5 text-sm text-white placeholder-slate-500 focus:outline-none focus:border-rose-500 transition-all" value="{{ old('cpf', $user->cpf ?? '') }}" placeholder="000.000.000-00" required>
                    </div>
                    
                    <div>
                        <label class="block text-xs font-semibold text-slate-300 mb-1.5">Data de Nascimento</label>
                        <input type="date" name="data_nascimento" class="w-full bg-[#090d16] border border-white/10 rounded-xl px-4 py-2.5 text-sm text-white placeholder-slate-500 focus:outline-none focus:border-rose-500 transition-all" value="{{ old('data_nascimento', isset($user) && $user->data_nascimento ? $user->data_nascimento->format('Y-m-d') : '') }}">
                    </div>
                    
                    <div>
                        <label class="block text-xs font-semibold text-slate-300 mb-1.5">E-mail</label>
                        <input type="email" name="email" class="w-full bg-[#090d16] border border-white/10 rounded-xl px-4 py-2.5 text-sm text-white placeholder-slate-500 focus:outline-none focus:border-rose-500 transition-all" value="{{ old('email', $user->email ?? '') }}" placeholder="contato@exemplo.com">
                    </div>
                    
                    <div>
                        <label class="block text-xs font-semibold text-slate-300 mb-1.5">Telefone / WhatsApp</label>
                        <input type="text" name="telefone" class="w-full bg-[#090d16] border border-white/10 rounded-xl px-4 py-2.5 text-sm text-white placeholder-slate-500 focus:outline-none focus:border-rose-500 transition-all" value="{{ old('telefone', $user->telefone ?? '') }}" placeholder="(00) 00000-0000">
                    </div>

                    <div class="sm:col-span-2">
                        <label class="block text-xs font-semibold text-slate-300 mb-1.5">Perfil de Acesso <span class="text-rose-500">*</span></label>
                        <select name="user_role" class="w-full bg-[#090d16] border border-white/10 rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none focus:border-rose-500 transition-all" required>
                            <option value="aluno" {{ old('user_role', $role ?? 'aluno') == 'aluno' ? 'selected' : '' }}>Aluno</option>
                            <option value="professor" {{ old('user_role', $role ?? '') == 'professor' ? 'selected' : '' }}>Professor</option>
                            <option value="instrutor" {{ old('user_role', $role ?? '') == 'instrutor' ? 'selected' : '' }}>Instrutor</option>
                            <option value="admin" {{ old('user_role', $role ?? '') == 'admin' ? 'selected' : '' }}>Administrador</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Training Info Section (1 Col) -->
            <div class="bg-[#111726] border border-white/10 rounded-2xl p-6 shadow-xl flex flex-col justify-between">
                <div>
                    <div class="flex items-center gap-3 pb-4 mb-6 border-b border-white/10">
                        <div class="w-10 h-10 rounded-xl bg-rose-500/10 border border-rose-500/20 text-rose-400 flex items-center justify-center">
                            <span class="material-symbols-outlined">sports_martial_arts</span>
                        </div>
                        <div>
                            <h2 class="font-['Outfit'] font-bold text-lg text-white">Graduação & Treino</h2>
                            <p class="text-slate-400 text-xs">Nível do praticante no tatame</p>
                        </div>
                    </div>
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-xs font-semibold text-slate-300 mb-1.5">Faixa Inicial <span class="text-rose-500">*</span></label>
                            <select name="faixa" class="w-full bg-[#090d16] border border-white/10 rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none focus:border-rose-500 transition-all" required>
                                @foreach(['Branca', 'Cinza', 'Cinza/Branca', 'Cinza/Preta', 'Amarela', 'Amarela/Branca', 'Amarela/Preta', 'Laranja', 'Laranja/Branca', 'Laranja/Preta', 'Verde', 'Verde/Branca', 'Verde/Preta', 'Azul', 'Roxa', 'Marrom', 'Preta'] as $belt)
                                <option value="{{ $belt }}" {{ old('faixa', $user->faixa ?? 'Branca') == $belt ? 'selected' : '' }}>{{ $belt }}</option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div>
                            <label class="block text-xs font-semibold text-slate-300 mb-1.5">Quantidade de Graus</label>
                            <div class="grid grid-cols-5 gap-1.5 p-1 bg-[#090d16] border border-white/10 rounded-xl">
                                @foreach([0, 1, 2, 3, 4] as $degree)
                                <label class="cursor-pointer">
                                    <input type="radio" name="grau" value="{{ $degree }}" class="peer hidden" {{ old('grau', $user->grau ?? 0) == $degree ? 'checked' : '' }}>
                                    <div class="py-2 text-center text-xs font-bold rounded-lg text-slate-400 peer-checked:bg-rose-600 peer-checked:text-white transition-all">
                                        {{ $degree }}
                                    </div>
                                </label>
                                @endforeach
                            </div>
                        </div>
                        
                        <div>
                            <label class="block text-xs font-semibold text-slate-300 mb-1.5">Data de Início das Aulas <span class="text-rose-500">*</span></label>
                            <input type="date" name="start_date" class="w-full bg-[#090d16] border border-white/10 rounded-xl px-4 py-2.5 text-sm text-white focus:outline-none focus:border-rose-500 transition-all" value="{{ old('start_date', isset($user) && $user->start_date ? $user->start_date->format('Y-m-d') : date('Y-m-d')) }}" required>
                        </div>
                    </div>
                </div>

                <div class="mt-6 pt-4 border-t border-white/10">
                    <div class="flex items-center gap-3 text-slate-400 text-xs">
                        <span class="material-symbols-outlined text-rose-400 text-sm">info</span>
                        <span>Caso a senha fique em branco, será utilizado o CPF do aluno.</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Plan Selection Section Card -->
        <div class="bg-[#111726] border border-white/10 rounded-2xl p-6 shadow-xl mb-6">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-2 pb-4 mb-6 border-b border-white/10">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-amber-500/10 border border-amber-500/20 text-amber-400 flex items-center justify-center">
                        <span class="material-symbols-outlined">payments</span>
                    </div>
                    <div>
                        <h2 class="font-['Outfit'] font-bold text-lg text-white">Plano de Matrícula</h2>
                        <p class="text-slate-400 text-xs">Selecione o plano de mensalidade para gerar os pagamentos</p>
                    </div>
                </div>
                <div class="px-3 py-1 rounded-full bg-rose-500/10 border border-rose-500/20 text-rose-400 text-xs font-semibold">
                    Plano ativo imediatamente
                </div>
            </div>
            
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
                @foreach($plans as $plan)
                <label class="cursor-pointer group relative">
                    <input type="radio" name="plan_id" value="{{ $plan->id }}" class="peer hidden" {{ old('plan_id', $user->plan_id ?? '') == $plan->id ? 'checked' : '' }} required>
                    <div class="p-5 rounded-2xl bg-[#182234] border border-white/10 peer-checked:border-rose-500 peer-checked:bg-rose-500/10 transition-all flex flex-col justify-between h-full">
                        <div>
                            <span class="text-xs font-bold uppercase tracking-wider text-slate-400 peer-checked:text-rose-400 block mb-1">{{ $plan->name }}</span>
                            <div class="font-['Outfit'] font-black text-2xl text-white group-hover:text-rose-300 transition-colors">
                                R$ {{ number_format($plan->price, 0, ',', '.') }}<span class="text-xs text-slate-500 font-medium">/mês</span>
                            </div>
                        </div>
                        <div class="mt-4 pt-3 border-t border-white/10 flex items-center gap-2 text-xs font-semibold text-slate-400 peer-checked:text-rose-400">
                            <span class="material-symbols-outlined text-sm">check_circle</span>
                            {{ $plan->name === 'Cortesia' ? 'Isenção Total' : 'Flexibilidade Total' }}
                        </div>
                    </div>
                </label>
                @endforeach
            </div>

            <div class="p-4 rounded-xl bg-[#090d16] border border-white/10">
                <label class="block text-xs font-semibold text-slate-300 mb-1.5">Valor Personalizado da Mensalidade (Opcional)</label>
                <div class="relative max-w-xs">
                    <span class="absolute left-3.5 top-1/2 -translate-y-1/2 font-bold text-slate-400 text-sm">R$</span>
                    <input type="number" step="0.01" min="0" name="custom_price" class="w-full bg-[#182234] border border-white/10 rounded-xl pl-10 pr-4 py-2 text-sm text-white placeholder-slate-500 focus:outline-none focus:border-rose-500 transition-all" value="{{ old('custom_price', $user->custom_price ?? '') }}" placeholder="Ex: 60,00">
                </div>
                <p class="text-slate-400 text-xs mt-2 leading-relaxed">
                    Deixe em branco para usar o valor padrão do plano selecionado. Preencha caso este aluno tenha um valor diferenciado ou bolsa parcial acordada.
                </p>
                @error('custom_price')
                    <p class="mt-2 text-xs text-rose-400 font-semibold flex items-center gap-1">
                        <span class="material-symbols-outlined text-sm">error</span> {{ $message }}
                    </p>
                @enderror
            </div>
        </div>

        <!-- Footer Action Container -->
        <div class="flex flex-col sm:flex-row items-center justify-between gap-4 p-6 rounded-2xl bg-[#111726] border border-white/10 shadow-xl">
            <p class="text-slate-400 text-xs text-center sm:text-left">
                Ao salvar este cadastro, o novo aluno poderá acessar o portal utilizando o CPF cadastrado.
            </p>
            <div class="flex items-center gap-3 w-full sm:w-auto">
                <a href="{{ route('users.index') }}" class="flex-1 sm:flex-none text-center px-5 py-2.5 rounded-xl bg-[#182234] border border-white/10 text-slate-300 font-['Outfit'] font-bold text-xs hover:bg-white/10 transition-colors">
                    Cancelar
                </a>
                <button type="submit" class="flex-1 sm:flex-none px-6 py-2.5 rounded-xl bg-gradient-to-r from-rose-600 to-rose-700 hover:from-rose-500 hover:to-rose-600 text-white font-['Outfit'] font-bold text-xs shadow-lg shadow-rose-900/30 flex items-center justify-center gap-2 transition-all active:scale-95">
                    <span class="material-symbols-outlined text-sm">person_add</span>
                    Salvar Cadastro
                </button>
            </div>
        </div>
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
            previewImg.classList.remove('hidden');
            if (previewIcon) previewIcon.classList.add('hidden');
        };
        reader.readAsDataURL(file);

        photoName.textContent = '✓ ' + file.name;
        photoName.classList.remove('hidden');
    });

    document.addEventListener('DOMContentLoaded', function() {
        // Máscaras de entrada (CPF e Telefone)
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