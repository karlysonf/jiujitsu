@extends('layouts.app')

@section('content')
<div class="max-w-2xl mx-auto bg-white p-6 rounded-lg shadow-md border border-gray-200">
    <h2 class="text-2xl font-bold mb-6 border-b pb-2">Cadastrar Novo Aluno</h2>

    <form action="#" method="POST">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="mb-4 col-span-2">
                <label class="block text-gray-700 text-sm font-bold mb-2">Nome Completo</label>
                <input type="text" class="w-full border rounded p-2 focus:ring-2 focus:ring-black outline-none">
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">CPF</label>
                <input type="text" class="w-full border rounded p-2 focus:ring-2 focus:ring-black outline-none">
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Telefone</label>
                <input type="text" class="w-full border rounded p-2 focus:ring-2 focus:ring-black outline-none">
            </div>
             <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="data_nascimento">Data de Nascimento</label>
                    <input name="data_nascimento" id="data_nascimento" type="date" value="{{ old('data_nascimento') }}" required onchange="checkAge(this.value)" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-black">
                </div>
                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="email">E-mail</label>
                    <input name="email" id="email" type="email" value="{{ old('email') }}" required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-black">
                </div>
                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="sexo">Sexo</label>
                    <select name="sexo" id="sexo" required class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-black">
                        <option value="">Selecione</option>
                        <option value="M" {{ old('sexo') == 'M' ? 'selected' : '' }}>Masculino</option>
                        <option value="F" {{ old('sexo') == 'F' ? 'selected' : '' }}>Feminino</option>
                        <option value="Outro" {{ old('sexo') == 'Outro' ? 'selected' : '' }}>Outro</option>
                    </select>
                </div>
                <div class="md:col-span-2">
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="endereco">Endereço Completo</label>
                    <input name="endereco" id="endereco" type="text" value="{{ old('endereco') }}" required placeholder="Rua, número, bairro, cidade" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-black">
                </div>
            </div>
        </div>

        <!-- Responsável (Apenas para menores) -->
        <div id="guardian-section" class="hidden bg-gray-50 p-4 border border-gray-200 rounded mb-4">
            <h3 class="text-lg font-semibold text-gray-700 mb-4">Dados do Responsável (Menor de Idade)</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="nome_responsavel">Nome do Responsável</label>
                    <input name="nome_responsavel" id="nome_responsavel" type="text" value="{{ old('nome_responsavel') }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-black">
                </div>
                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="cpf_responsavel">CPF do Responsável</label>
                    <input name="cpf_responsavel" id="cpf_responsavel" type="text" value="{{ old('cpf_responsavel') }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-black">
                </div>
                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="telefone_responsavel">Telefone do Responsável</label>
                    <input name="telefone_responsavel" id="telefone_responsavel" type="text" value="{{ old('telefone_responsavel') }}" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-black">
                </div>
            </div>
        </div>

            <div class="mb-4 col-span-2 md:col-span-1">
                <label class="block text-gray-700 text-sm font-bold mb-2">Faixa Atual</label>
                <select class="w-full border rounded p-2 focus:ring-2 focus:ring-black outline-none bg-white">
                     <option value="Branca">Branca</option>
                        <option value="Cinza">Cinza</option>
                        <option value="Amarela">Amarela</option>
                        <option value="Laranja">Laranja</option>
                        <option value="Verde">Verde</option>
                        <option value="Azul">Azul</option>
                        <option value="Roxa">Roxa</option>
                        <option value="Marrom">Marrom</option>
                        <option value="Preta">Preta</option>
                </select>
            </div>

              <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="grau">Grau</label>
                    <input name="grau" id="grau" type="number" min="0" max="4" value="{{ old('grau', 0) }}" required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-black">
                </div>
                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="peso">Peso (kg)</label>
                    <input name="peso" id="peso" type="number" step="0.1" value="{{ old('peso') }}" required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-black">
                </div>
                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="vencimento_mensalidade">Vencimento da Mensalidade</label>
                    <select name="vencimento_mensalidade" id="vencimento_mensalidade" required class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-black">
                        <option value="">Selecione um dia</option>
                        <option value="05" {{ old('vencimento_mensalidade') == '05' ? 'selected' : '' }}>Dia 05</option>
                        <option value="10" {{ old('vencimento_mensalidade') == '10' ? 'selected' : '' }}>Dia 10</option>
                        <option value="15" {{ old('vencimento_mensalidade') == '15' ? 'selected' : '' }}>Dia 15</option>
                        <option value="20" {{ old('vencimento_mensalidade') == '20' ? 'selected' : '' }}>Dia 20</option>
                        <option value="25" {{ old('vencimento_mensalidade') == '25' ? 'selected' : '' }}>Dia 25</option>
                        <option value="30" {{ old('vencimento_mensalidade') == '30' ? 'selected' : '' }}>Dia 30</option>
                    </select>
                </div>

            <div class="mb-4 col-span-2">
                <label class="block text-gray-700 text-sm font-bold mb-2">Nome dos Pais (Para menores de idade)</label>
                <input type="text" class="w-full border rounded p-2 focus:ring-2 focus:ring-black outline-none" placeholder="Opcional">
            </div>
        </div>

          <!-- Saúde -->
        <div class="border-b border-gray-200 pb-4 mb-4">
            <h3 class="text-lg font-semibold text-gray-700 mb-4">Informações de Saúde</h3>
            <div class="space-y-4">
                <div>
                    <div class="flex items-center">
                        <input type="hidden" name="possui_lesao" value="0">
                        <input type="checkbox" name="possui_lesao" value="1" id="possui_lesao" class="h-4 w-4 text-black focus:ring-black border-gray-300 rounded" {{ old('possui_lesao') ? 'checked' : '' }} onchange="toggleHealthDesc('possui_lesao', 'desc_lesao')">
                        <label for="possui_lesao" class="ml-2 block text-sm text-gray-900 font-bold">Possui Lesão?</label>
                    </div>
                    <div id="desc_lesao" class="{{ old('possui_lesao') ? '' : 'hidden' }} mt-2">
                        <label class="block text-gray-700 text-xs font-bold mb-1" for="descricao_lesao">Descreva a lesão:</label>
                        <textarea name="descricao_lesao" id="descricao_lesao" rows="2" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-black">{{ old('descricao_lesao') }}</textarea>
                    </div>
                </div>

                <div>
                    <div class="flex items-center">
                        <input type="hidden" name="medicamento_continuo" value="0">
                        <input type="checkbox" name="medicamento_continuo" value="1" id="medicamento_continuo" class="h-4 w-4 text-black focus:ring-black border-gray-300 rounded" {{ old('medicamento_continuo') ? 'checked' : '' }} onchange="toggleHealthDesc('medicamento_continuo', 'desc_medicamento')">
                        <label for="medicamento_continuo" class="ml-2 block text-sm text-gray-900 font-bold">Usa algum medicamento contínuo?</label>
                    </div>
                    <div id="desc_medicamento" class="{{ old('medicamento_continuo') ? '' : 'hidden' }} mt-2">
                        <label class="block text-gray-700 text-xs font-bold mb-1" for="descricao_medicamento">Liste os medicamentos:</label>
                        <textarea name="descricao_medicamento" id="descricao_medicamento" rows="2" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-black">{{ old('descricao_medicamento') }}</textarea>
                    </div>
                </div>

                <div>
                    <div class="flex items-center">
                        <input type="hidden" name="problema_cardiaco" value="0">
                        <input type="checkbox" name="problema_cardiaco" value="1" id="problema_cardiaco" class="h-4 w-4 text-black focus:ring-black border-gray-300 rounded" {{ old('problema_cardiaco') ? 'checked' : '' }} onchange="toggleHealthDesc('problema_cardiaco', 'desc_cardiaco')">
                        <label for="problema_cardiaco" class="ml-2 block text-sm text-gray-900 font-bold">Algum problema cardíaco?</label>
                    </div>
                    <div id="desc_cardiaco" class="{{ old('problema_cardiaco') ? '' : 'hidden' }} mt-2">
                        <label class="block text-gray-700 text-xs font-bold mb-1" for="descricao_problema_cardiaco">Descreva o problema:</label>
                        <textarea name="descricao_problema_cardiaco" id="descricao_problema_cardiaco" rows="2" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-black">{{ old('descricao_problema_cardiaco') }}</textarea>
                    </div>
                </div>

                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="outros">Outros</label>
                    <textarea name="outros" id="outros" rows="2" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-black">{{ old('outros') }}</textarea>
                </div>
            </div>
        </div>

        <!-- Credenciais de Acesso -->
        <div class="pb-4">
            <h3 class="text-lg font-semibold text-gray-700 mb-4">Credenciais de Acesso</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="login">Login (Usuário)</label>
                    <input name="login" id="login" type="text" value="{{ old('login') }}" required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-black">
                </div>
                <div></div> <!-- Spacer -->
                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="password">Senha</label>
                    <input name="password" id="password" type="password" required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-black">
                </div>
                <div>
                    <label class="block text-gray-700 text-sm font-bold mb-2" for="password_confirmation">Confirmar Senha</label>
                    <input name="password_confirmation" id="password_confirmation" type="password" required class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:ring-2 focus:ring-black">
                </div>
            </div>
        </div>


        <div class="mt-6 text-right">
            <button type="button" class="bg-black text-white px-6 py-2 rounded font-bold hover:bg-gray-800">Salvar Aluno</button>
        </div>
    </form>
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
        
        if (m < 0 || (m === 0 && today.getDate() < birth.getDate())) {
            age--;
        }
        
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
        let v = i.value;
        if (isNaN(v[v.length-1])) {
            i.value = v.substring(0, v.length-1);
            return;
        }
        i.setAttribute("maxlength", "14");
        if (v.length == 3 || v.length == 7) i.value += ".";
        if (v.length == 11) i.value += "-";
    }

    function maskPhone(i) {
        let v = i.value;
        i.setAttribute("maxlength", "15");
        if (v.length == 1) i.value = "(" + i.value;
        if (v.length == 3) i.value += ") ";
        if (v.length == 10) i.value += "-";
    }

    document.getElementById('cpf')?.addEventListener('input', function() { maskCPF(this); });
    document.getElementById('cpf_responsavel')?.addEventListener('input', function() { maskCPF(this); });
    document.getElementById('telefone')?.addEventListener('input', function() { maskPhone(this); });
    document.getElementById('telefone_responsavel')?.addEventListener('input', function() { maskPhone(this); });

    window.onload = function() {
        const birthInput = document.getElementById('data_nascimento');
        if (birthInput?.value) {
            checkAge(birthInput.value);
        }
    }
</script>
@endsection
