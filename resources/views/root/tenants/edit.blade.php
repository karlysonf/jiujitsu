@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto py-8">
    <div class="mb-8">
        <a href="{{ route('root.tenants.index') }}" class="text-slate-500 hover:text-slate-700 flex items-center gap-1 text-sm font-semibold mb-2">
            <span class="material-symbols-outlined text-sm">arrow_back</span> Voltar para a listagem
        </a>
        <h1 class="text-3xl font-bold text-slate-900 dark:text-slate-50 leading-tight">Editar Academia: {{ $tenant->name }}</h1>
        <p class="text-sm text-slate-500 mt-1">Modifique as configurações de plano, limites e status da academia.</p>
    </div>

    <form action="{{ route('root.tenants.update', $tenant) }}" method="POST" class="space-y-8">
        @csrf
        @method('PUT')

        <div class="bg-white dark:bg-slate-900 shadow-md rounded-2xl p-6 md:p-8 border border-slate-200 dark:border-slate-800">
            <div class="flex items-center gap-3 pb-6 border-b border-slate-100 dark:border-slate-800 mb-6">
                <span class="material-symbols-outlined text-primary text-2xl">storefront</span>
                <h2 class="text-xl font-bold text-slate-900 dark:text-slate-50">Configurações Gerais e Assinatura</h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Academy Name -->
                <div class="md:col-span-2">
                    <label for="name" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Nome da Academia</label>
                    <input type="text" name="name" id="name" value="{{ old('name', $tenant->name) }}" required
                           class="w-full px-4 py-3 rounded-xl border border-slate-300 dark:border-slate-700 dark:bg-slate-800 text-slate-900 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-all">
                </div>

                <!-- Subdomain -->
                <div>
                    <label for="subdomain" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Subdomínio (Exclusivo)</label>
                    <div class="flex">
                        <input type="text" name="subdomain" id="subdomain" value="{{ old('subdomain', $tenant->subdomain) }}" required
                               class="flex-1 px-4 py-3 rounded-l-xl border border-slate-300 dark:border-slate-700 dark:bg-slate-800 text-slate-900 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-all">
                        <span class="inline-flex items-center px-4 rounded-r-xl border border-l-0 border-slate-300 dark:border-slate-700 bg-slate-50 dark:bg-slate-950 text-slate-500 text-sm">
                            .gerenciador.com
                        </span>
                    </div>
                </div>

                <!-- Custom Domain -->
                <div>
                    <label for="domain" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Domínio Customizado (Opcional)</label>
                    <input type="text" name="domain" id="domain" value="{{ old('domain', $tenant->domain) }}" placeholder="Ex: www.ctdenyson.com.br"
                           class="w-full px-4 py-3 rounded-xl border border-slate-300 dark:border-slate-700 dark:bg-slate-800 text-slate-900 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-all">
                </div>

                <!-- Plan Tier -->
                <div>
                    <label for="plan_tier" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Plano Contratado</label>
                    <select name="plan_tier" id="plan_tier" required
                            class="w-full px-4 py-3 rounded-xl border border-slate-300 dark:border-slate-700 dark:bg-slate-800 text-slate-900 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-all">
                        <option value="bronze" {{ old('plan_tier', $tenant->plan_tier) === 'bronze' ? 'selected' : '' }}>Bronze (Até 50 cadastros)</option>
                        <option value="silver" {{ old('plan_tier', $tenant->plan_tier) === 'silver' ? 'selected' : '' }}>Prata (Até 100 cadastros)</option>
                        <option value="gold" {{ old('plan_tier', $tenant->plan_tier) === 'gold' ? 'selected' : '' }}>Ouro (Cadastros Ilimitados)</option>
                    </select>
                </div>

                <!-- Status -->
                <div>
                    <label for="status" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Status da Conta</label>
                    <select name="status" id="status" required
                            class="w-full px-4 py-3 rounded-xl border border-slate-300 dark:border-slate-700 dark:bg-slate-800 text-slate-900 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-all">
                        <option value="trial" {{ old('status', $tenant->status) === 'trial' ? 'selected' : '' }}>Trial (Testes)</option>
                        <option value="active" {{ old('status', $tenant->status) === 'active' ? 'selected' : '' }}>Ativa</option>
                        <option value="suspended" {{ old('status', $tenant->status) === 'suspended' ? 'selected' : '' }}>Suspensa</option>
                    </select>
                </div>

                <!-- Expiration Date -->
                <div>
                    <label for="expires_at" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Validade da Assinatura</label>
                    <input type="date" name="expires_at" id="expires_at" value="{{ old('expires_at', $tenant->expires_at ? $tenant->expires_at->format('Y-m-d') : '') }}"
                           class="w-full px-4 py-3 rounded-xl border border-slate-300 dark:border-slate-700 dark:bg-slate-800 text-slate-900 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-all">
                </div>

                <!-- Custom Limit Override -->
                <div class="md:col-span-2 pt-4 border-t border-slate-100 dark:border-slate-800 mt-4">
                    @php
                        $isBronze = $tenant->plan_tier === 'bronze' && $tenant->max_users === 50;
                        $isSilver = $tenant->plan_tier === 'silver' && $tenant->max_users === 100;
                        $isGold = $tenant->plan_tier === 'gold' && is_null($tenant->max_users);
                        $isStandard = $isBronze || $isSilver || $isGold;
                    @endphp
                    <div class="flex items-center gap-2">
                        <input type="checkbox" name="custom_limit_override" id="custom_limit_override" value="1" {{ !$isStandard ? 'checked' : '' }} onchange="toggleLimitInput(this)"
                               class="rounded border-slate-300 text-primary focus:ring-primary h-4 w-4">
                        <label for="custom_limit_override" class="text-sm font-semibold text-slate-700 dark:text-slate-300">Sobrescrever limite de cadastros padrão do plano</label>
                    </div>

                    <div id="custom_limit_wrapper" class="{{ !$isStandard ? '' : 'hidden' }} mt-4">
                        <label for="max_users" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Limite Customizado de Cadastros Ativos (Use vazio para ilimitado)</label>
                        <input type="number" name="max_users" id="max_users" value="{{ old('max_users', $tenant->max_users) }}" placeholder="Ex: 75"
                               class="w-full px-4 py-3 rounded-xl border border-slate-300 dark:border-slate-700 dark:bg-slate-800 text-slate-900 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-all">
                    </div>
                </div>
            </div>
        </div>

        <!-- Submit Buttons -->
        <div class="flex justify-end gap-4">
            <a href="{{ route('root.tenants.index') }}" class="px-6 py-3 rounded-xl font-semibold border border-slate-300 dark:border-slate-700 text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800 active:scale-95 transition-all text-sm">
                Cancelar
            </a>
            <button type="submit" class="bg-primary text-white px-8 py-3 rounded-xl font-semibold shadow-md hover:opacity-90 active:scale-95 transition-all text-sm">
                Salvar Alterações
            </button>
        </div>
    </form>
</div>

<script>
    function toggleLimitInput(checkbox) {
        const wrapper = document.getElementById("custom_limit_wrapper");
        if (checkbox.checked) {
            wrapper.classList.remove("hidden");
        } else {
            wrapper.classList.add("hidden");
            document.getElementById("max_users").value = "";
        }
    }
</script>
@endsection
