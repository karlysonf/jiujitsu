@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto py-8">
    <div class="mb-8">
        <a href="{{ route('root.tenants.index') }}" class="text-slate-500 hover:text-slate-700 flex items-center gap-1 text-sm font-semibold mb-2">
            <span class="material-symbols-outlined text-sm">arrow_back</span> Voltar para a listagem
        </a>
        <h1 class="text-3xl font-bold text-slate-900 dark:text-slate-50 leading-tight">Cadastrar Nova Academia</h1>
        <p class="text-sm text-slate-500 mt-1">Crie a academia e a conta do administrador/dono correspondente.</p>
    </div>

    <form action="{{ route('root.tenants.store') }}" method="POST" class="space-y-8">
        @csrf

        <!-- Dados da Academia -->
        <div class="bg-white dark:bg-slate-900 shadow-md rounded-2xl p-6 md:p-8 border border-slate-200 dark:border-slate-800">
            <div class="flex items-center gap-3 pb-6 border-b border-slate-100 dark:border-slate-800 mb-6">
                <span class="material-symbols-outlined text-primary text-2xl">storefront</span>
                <h2 class="text-xl font-bold text-slate-900 dark:text-slate-50">Dados da Academia</h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Academy Name -->
                <div class="md:col-span-2">
                    <label for="name" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Nome da Academia</label>
                    <input type="text" name="name" id="name" value="{{ old('name') }}" required placeholder="Ex: CT Denyson Anderson"
                           class="w-full px-4 py-3 rounded-xl border border-slate-300 dark:border-slate-700 dark:bg-slate-800 text-slate-900 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-all">
                </div>

                <!-- Subdomain -->
                <div>
                    <label for="subdomain" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Subdomínio (Exclusivo)</label>
                    <div class="flex">
                        <input type="text" name="subdomain" id="subdomain" value="{{ old('subdomain') }}" required placeholder="ex: ctdenyson"
                               class="flex-1 px-4 py-3 rounded-l-xl border border-slate-300 dark:border-slate-700 dark:bg-slate-800 text-slate-900 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-all">
                        <span class="inline-flex items-center px-4 rounded-r-xl border border-l-0 border-slate-300 dark:border-slate-700 bg-slate-50 dark:bg-slate-950 text-slate-500 text-sm">
                            .gerenciador.com
                        </span>
                    </div>
                </div>

                <!-- Custom Domain -->
                <div>
                    <label for="domain" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Domínio Customizado (Opcional)</label>
                    <input type="text" name="domain" id="domain" value="{{ old('domain') }}" placeholder="Ex: www.ctdenyson.com.br"
                           class="w-full px-4 py-3 rounded-xl border border-slate-300 dark:border-slate-700 dark:bg-slate-800 text-slate-900 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-all">
                </div>

                <!-- Plan Tier -->
                <div>
                    <label for="plan_tier" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Plano de Contratação</label>
                    <select name="plan_tier" id="plan_tier" required
                            class="w-full px-4 py-3 rounded-xl border border-slate-300 dark:border-slate-700 dark:bg-slate-800 text-slate-900 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-all">
                        <option value="bronze" {{ old('plan_tier') === 'bronze' ? 'selected' : '' }}>Bronze (Até 50 cadastros)</option>
                        <option value="silver" {{ old('plan_tier') === 'silver' ? 'selected' : '' }}>Prata (Até 100 cadastros)</option>
                        <option value="gold" {{ old('plan_tier') === 'gold' ? 'selected' : '' }}>Ouro (Cadastros Ilimitados)</option>
                    </select>
                </div>

                <!-- Status -->
                <div>
                    <label for="status" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Status da Conta</label>
                    <select name="status" id="status" required
                            class="w-full px-4 py-3 rounded-xl border border-slate-300 dark:border-slate-700 dark:bg-slate-800 text-slate-900 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-all">
                        <option value="trial" {{ old('status') === 'trial' ? 'selected' : '' }}>Trial (Testes)</option>
                        <option value="active" {{ old('status', 'active') === 'active' ? 'selected' : '' }}>Ativa</option>
                        <option value="suspended" {{ old('status') === 'suspended' ? 'selected' : '' }}>Suspensa</option>
                    </select>
                </div>

                <!-- Expiration Date -->
                <div>
                    <label for="expires_at" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Validade da Assinatura (Opcional)</label>
                    <input type="date" name="expires_at" id="expires_at" value="{{ old('expires_at') }}"
                           class="w-full px-4 py-3 rounded-xl border border-slate-300 dark:border-slate-700 dark:bg-slate-800 text-slate-900 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-all">
                </div>
            </div>
        </div>

        <!-- Dados do Administrador da Academia -->
        <div class="bg-white dark:bg-slate-900 shadow-md rounded-2xl p-6 md:p-8 border border-slate-200 dark:border-slate-800">
            <div class="flex items-center gap-3 pb-6 border-b border-slate-100 dark:border-slate-800 mb-6">
                <span class="material-symbols-outlined text-primary text-2xl">person</span>
                <h2 class="text-xl font-bold text-slate-900 dark:text-slate-50">Conta do Administrador / Dono</h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Owner Name -->
                <div>
                    <label for="owner_name" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Nome Completo</label>
                    <input type="text" name="owner_name" id="owner_name" value="{{ old('owner_name') }}" required placeholder="Ex: Felipe Santos"
                           class="w-full px-4 py-3 rounded-xl border border-slate-300 dark:border-slate-700 dark:bg-slate-800 text-slate-900 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-all">
                </div>

                <!-- Owner Email -->
                <div>
                    <label for="owner_email" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">E-mail</label>
                    <input type="email" name="owner_email" id="owner_email" value="{{ old('owner_email') }}" required placeholder="Ex: felipe@academia.com"
                           class="w-full px-4 py-3 rounded-xl border border-slate-300 dark:border-slate-700 dark:bg-slate-800 text-slate-900 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-all">
                </div>

                <!-- Owner CPF -->
                <div>
                    <label for="owner_cpf" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">CPF</label>
                    <input type="text" name="owner_cpf" id="owner_cpf" value="{{ old('owner_cpf') }}" required placeholder="000.000.000-00" oninput="formatCPF(this)" maxlength="14"
                           class="w-full px-4 py-3 rounded-xl border border-slate-300 dark:border-slate-700 dark:bg-slate-800 text-slate-900 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-all">
                </div>

                <div class="hidden md:block"></div>

                <!-- Owner Password -->
                <div>
                    <label for="owner_password" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Senha de Acesso</label>
                    <input type="password" name="owner_password" id="owner_password" required placeholder="Mínimo 8 caracteres"
                           class="w-full px-4 py-3 rounded-xl border border-slate-300 dark:border-slate-700 dark:bg-slate-800 text-slate-900 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-all">
                </div>

                <!-- Owner Password Confirmation -->
                <div>
                    <label for="owner_password_confirmation" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Confirmar Senha</label>
                    <input type="password" name="owner_password_confirmation" id="owner_password_confirmation" required placeholder="Repita a senha de acesso"
                           class="w-full px-4 py-3 rounded-xl border border-slate-300 dark:border-slate-700 dark:bg-slate-800 text-slate-900 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-all">
                </div>
            </div>
        </div>

        <!-- Submit Buttons -->
        <div class="flex justify-end gap-4">
            <a href="{{ route('root.tenants.index') }}" class="px-6 py-3 rounded-xl font-semibold border border-slate-300 dark:border-slate-700 text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800 active:scale-95 transition-all text-sm">
                Cancelar
            </a>
            <button type="submit" class="bg-primary text-white px-8 py-3 rounded-xl font-semibold shadow-md hover:opacity-90 active:scale-95 transition-all text-sm">
                Salvar Academia
            </button>
        </div>
    </form>
</div>

<script>
    function formatCPF(input) {
        let value = input.value.replace(/\D/g, "");
        value = value.replace(/(\d{3})(\d)/, "$1.$2");
        value = value.replace(/(\d{3})(\d)/, "$1.$2");
        value = value.replace(/(\d{3})(\d{1,2})$/, "$1-$2");
        input.value = value;
    }
</script>
@endsection
