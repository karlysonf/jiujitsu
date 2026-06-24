@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-slate-900 dark:text-slate-50 leading-tight">Configurações da Academia</h1>
        <p class="text-sm text-slate-500 mt-1">Personalize a identidade visual e configure gateways de pagamento automatizados.</p>
    </div>

    <form action="{{ route('settings.update') }}" method="POST" enctype="multipart/form-data" class="space-y-8">
        @csrf
        @method('PUT')

        <!-- Plano e Assinatura Section -->
        <div class="bg-white dark:bg-slate-900 shadow-md rounded-2xl p-6 md:p-8 border border-slate-200 dark:border-slate-800">
            <div class="flex items-center gap-3 pb-6 border-b border-slate-100 dark:border-slate-800 mb-6">
                <span class="material-symbols-outlined text-primary text-2xl">workspace_premium</span>
                <h2 class="text-xl font-bold text-slate-900 dark:text-slate-50">Plano e Assinatura do Sistema</h2>
            </div>

            @php
                $activeCount = $tenant->getActiveUsersCount();
                $limit = $tenant->max_users;
                $percent = $limit ? min(100, ($activeCount / $limit) * 100) : 0;
                $tierNames = [
                    'bronze' => 'Bronze (Até 50 cadastros)',
                    'silver' => 'Prata (Até 100 cadastros)',
                    'gold' => 'Ouro (Ilimitado)'
                ];
                $planName = $tierNames[$tenant->plan_tier] ?? ucfirst($tenant->plan_tier ?? 'Bronze');
            @endphp

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 items-center">
                <div>
                    <p class="text-sm font-semibold text-slate-500">Plano Atual</p>
                    <p class="text-lg font-bold text-slate-900 dark:text-slate-100">{{ $planName }}</p>
                    
                    @if($tenant->expires_at)
                        <p class="text-xs text-slate-400 mt-1">Sua assinatura expira em: {{ $tenant->expires_at->format('d/m/Y') }}</p>
                    @endif
                </div>

                <div>
                    <div class="flex justify-between text-sm mb-2">
                        <span class="font-semibold text-slate-700 dark:text-slate-300">Uso do Limite de Cadastros</span>
                        <span class="font-bold text-slate-900 dark:text-slate-100">
                            {{ $activeCount }} / {{ $limit ?? '∞' }}
                        </span>
                    </div>
                    
                    @if($limit)
                        <div class="w-full bg-slate-100 dark:bg-slate-800 rounded-full h-3.5 overflow-hidden">
                            <div class="bg-primary h-full rounded-full transition-all duration-500" style="width: {{ $percent }}%"></div>
                        </div>
                        <p class="text-xs text-slate-400 mt-2">Inclui alunos, professores e instrutores com status ativo.</p>
                    @else
                        <div class="w-full bg-slate-100 dark:bg-slate-800 rounded-full h-3.5 overflow-hidden">
                            <div class="bg-green-500 h-full rounded-full" style="width: 100%"></div>
                        </div>
                        <p class="text-xs text-slate-400 mt-2">Você possui cadastros ilimitados no plano atual.</p>
                    @endif
                </div>
            </div>
            
            @if($limit && $activeCount >= $limit)
                <div class="mt-6 p-4 rounded-xl bg-red-50 dark:bg-red-950/30 border border-red-100 dark:border-red-900/55 text-red-800 dark:text-red-300 flex items-start gap-3">
                    <span class="material-symbols-outlined mt-0.5">warning</span>
                    <div>
                        <p class="text-sm font-bold">Limite Atingido</p>
                        <p class="text-xs mt-1">Você atingiu o limite máximo de cadastros permitidos pelo seu plano. Novas matrículas ou ativações de alunos/professores estão bloqueadas.</p>
                    </div>
                </div>
            @endif
        </div>

        <!-- Branding Section -->
        <div class="bg-white dark:bg-slate-900 shadow-md rounded-2xl p-6 md:p-8 border border-slate-200 dark:border-slate-800">
            <div class="flex items-center gap-3 pb-6 border-b border-slate-100 dark:border-slate-800 mb-6">
                <span class="material-symbols-outlined text-primary text-2xl">palette</span>
                <h2 class="text-xl font-bold text-slate-900 dark:text-slate-50">Identidade Visual (White-Label)</h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Academy Name -->
                <div class="md:col-span-2">
                    <label for="name" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Nome da Academia</label>
                    <input type="text" name="name" id="name" value="{{ old('name', $tenant->name) }}" required
                           class="w-full px-4 py-3 rounded-xl border border-slate-300 dark:border-slate-700 dark:bg-slate-800 text-slate-900 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-all">
                </div>

                <!-- Logo Upload -->
                <div class="md:col-span-2">
                    <label class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Logotipo da Academia</label>
                    <div class="flex items-center gap-6">
                        <div class="w-24 h-24 rounded-xl border border-slate-200 dark:border-slate-800 bg-slate-50 dark:bg-slate-950 flex items-center justify-center overflow-hidden">
                            @if($tenant->logo)
                                <img src="{{ Storage::disk('public')->url($tenant->logo) }}" alt="Logo preview" class="w-full h-full object-contain">
                            @else
                                <span class="material-symbols-outlined text-slate-400 text-3xl">sports_kabaddi</span>
                            @endif
                        </div>
                        <div>
                            <input type="file" name="logo" id="logo" accept="image/*" class="hidden" onchange="previewLogo(event)">
                            <button type="button" onclick="document.getElementById('logo').click()" class="bg-primary text-white px-5 py-2.5 rounded-xl font-semibold shadow-sm hover:opacity-90 active:scale-95 transition-all text-sm">
                                Escolher Imagem
                            </button>
                            <p class="text-xs text-slate-400 mt-2">Formatos aceitos: JPG, PNG, WEBP. Max: 1MB.</p>
                        </div>
                    </div>
                </div>

                <!-- Primary Color Picker -->
                <div>
                    <label for="primary_color" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Cor Primária</label>
                    <div class="flex gap-3">
                        <input type="color" id="primary_color_picker" value="{{ $tenant->primary_color }}" oninput="updateColorInput('primary_color', this.value)" class="w-12 h-12 p-1 rounded-xl border border-slate-300 dark:border-slate-700 cursor-pointer">
                        <input type="text" name="primary_color" id="primary_color" value="{{ old('primary_color', $tenant->primary_color) }}" required
                               class="flex-1 px-4 py-3 rounded-xl border border-slate-300 dark:border-slate-700 dark:bg-slate-800 text-slate-900 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-all uppercase">
                    </div>
                </div>

                <!-- Secondary Color Picker -->
                <div>
                    <label for="secondary_color" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Cor Secundária</label>
                    <div class="flex gap-3">
                        <input type="color" id="secondary_color_picker" value="{{ $tenant->secondary_color }}" oninput="updateColorInput('secondary_color', this.value)" class="w-12 h-12 p-1 rounded-xl border border-slate-300 dark:border-slate-700 cursor-pointer">
                        <input type="text" name="secondary_color" id="secondary_color" value="{{ old('secondary_color', $tenant->secondary_color) }}" required
                               class="flex-1 px-4 py-3 rounded-xl border border-slate-300 dark:border-slate-700 dark:bg-slate-800 text-slate-900 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-all uppercase">
                    </div>
                </div>
            </div>
        </div>

        <!-- Payment Gateway Section -->
        <div class="bg-white dark:bg-slate-900 shadow-md rounded-2xl p-6 md:p-8 border border-slate-200 dark:border-slate-800">
            <div class="flex items-center gap-3 pb-6 border-b border-slate-100 dark:border-slate-800 mb-6">
                <span class="material-symbols-outlined text-primary text-2xl">payments</span>
                <h2 class="text-xl font-bold text-slate-900 dark:text-slate-50">Configurações de Pagamento (Asaas)</h2>
            </div>

            <div class="space-y-6">
                <!-- Environment Selection -->
                <div>
                    <label for="asaas_environment" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Ambiente do Asaas</label>
                    <select name="asaas_environment" id="asaas_environment" required
                            class="w-full px-4 py-3 rounded-xl border border-slate-300 dark:border-slate-700 dark:bg-slate-800 text-slate-900 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-all">
                        <option value="sandbox" {{ old('asaas_environment', $tenant->asaas_environment) === 'sandbox' ? 'selected' : '' }}>Sandbox (Testes)</option>
                        <option value="production" {{ old('asaas_environment', $tenant->asaas_environment) === 'production' ? 'selected' : '' }}>Produção (Dinheiro Real)</option>
                    </select>
                </div>

                <!-- API Key -->
                <div>
                    <label for="asaas_api_key" class="block text-sm font-semibold text-slate-700 dark:text-slate-300 mb-2">Chave de API (Asaas)</label>
                    <input type="password" name="asaas_api_key" id="asaas_api_key" placeholder="{{ $tenant->asaas_api_key ? '••••••••••••••••••••••••••••••••' : 'Insira a chave de API' }}"
                           class="w-full px-4 py-3 rounded-xl border border-slate-300 dark:border-slate-700 dark:bg-slate-800 text-slate-900 dark:text-slate-100 focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent transition-all">
                    <p class="text-xs text-slate-400 mt-2">Você pode encontrar sua chave de API nas configurações do painel do Asaas.</p>
                </div>
            </div>
        </div>

        <!-- Submit Buttons -->
        <div class="flex justify-end gap-4">
            <button type="button" onclick="window.history.back()" class="px-6 py-3 rounded-xl font-semibold border border-slate-300 dark:border-slate-700 text-slate-700 dark:text-slate-300 hover:bg-slate-50 dark:hover:bg-slate-800 active:scale-95 transition-all text-sm">
                Cancelar
            </button>
            <button type="submit" class="bg-primary text-white px-8 py-3 rounded-xl font-semibold shadow-md hover:opacity-90 active:scale-95 transition-all text-sm">
                Salvar Alterações
            </button>
        </div>
    </form>
</div>

<script>
    function updateColorInput(inputId, value) {
        document.getElementById(inputId).value = value;
    }

    function previewLogo(event) {
        const input = event.target;
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function(e) {
                // Instantly update colors & styles if possible
            };
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endsection
