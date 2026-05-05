@extends('layouts.app')

@section('content')
<!-- Content Canvas -->
<div class="p-gutter max-w-[1600px] mx-auto">
    <!-- Page Header -->
    <div class="mb-lg">
        <h2 class="font-headline-lg text-headline-lg text-primary">Relatórios de Gestão</h2>
        <p class="font-body-md text-body-md text-outline">Gere relatórios detalhados sobre o faturamento e desempenho da academia.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-gutter">
        <!-- Monthly Billing Report -->
        <div class="bg-white rounded-xl border border-slate-200 p-md shadow-sm flex flex-col justify-between group hover:border-on-tertiary-container transition-all">
            <div>
                <div class="w-12 h-12 bg-on-tertiary-container/10 text-on-tertiary-container rounded-lg flex items-center justify-center text-xl mb-4">
                    <span class="material-symbols-outlined">calendar_month</span>
                </div>
                <h3 class="font-label-bold text-label-bold text-primary mb-2">Mensal (Faturamento)</h3>
                <p class="text-sm text-outline mb-6">Gere um resumo detalhado dos pagamentos recebidos e pendências de um mês específico.</p>
            </div>
            
            <form action="{{ route('reports.monthly') }}" method="GET" class="space-y-4">
                <div>
                    <label class="block text-label-sm font-label-sm text-outline-variant mb-1 ml-1">Selecione o Mês</label>
                    <input type="month" name="month" value="{{ date('Y-m') }}" class="w-full h-12 px-4 rounded-lg border border-slate-200 bg-surface-container-lowest focus:ring-2 focus:ring-on-tertiary-container focus:border-on-tertiary-container transition-all font-body-md text-body-md" required>
                </div>
                <button type="submit" class="w-full h-12 bg-primary text-white rounded-lg font-label-bold flex items-center justify-center gap-2 hover:bg-slate-800 transition-all">
                    Gerar Relatório
                </button>
            </form>
        </div>

        <!-- Delinquency Report -->
        <div class="bg-white rounded-xl border border-slate-200 p-md shadow-sm flex flex-col justify-between group hover:border-on-tertiary-container transition-all">
            <div>
                <div class="w-12 h-12 bg-error/10 text-error rounded-lg flex items-center justify-center text-xl mb-4">
                    <span class="material-symbols-outlined">person_off</span>
                </div>
                <h3 class="font-label-bold text-label-bold text-primary mb-2">Inadimplência</h3>
                <p class="text-sm text-outline mb-6">Acompanhe alunos com mensalidades em atraso e tome ações de cobrança.</p>
            </div>
            
            <div class="pt-4">
                <a href="{{ route('reports.delinquency') }}" class="w-full h-12 bg-primary text-white rounded-lg font-label-bold flex items-center justify-center gap-2 hover:bg-slate-800 transition-all">
                    Visualizar Lista
                </a>
            </div>
        </div>

        <!-- Annual Billing Report -->
        <div class="bg-slate-50 rounded-xl border border-slate-200 p-md shadow-sm flex flex-col justify-between opacity-70">
            <div>
                <div class="w-12 h-12 bg-slate-200 text-slate-500 rounded-lg flex items-center justify-center text-xl mb-4">
                    <span class="material-symbols-outlined">trending_up</span>
                </div>
                <h3 class="font-label-bold text-label-bold text-slate-500 mb-2">Anual (Performance)</h3>
                <p class="text-sm text-slate-400 mb-6">Acompanhe a evolução do faturamento ao longo do ano.</p>
            </div>
            
            <div class="pt-4">
                <button type="button" class="w-full h-12 bg-slate-200 text-slate-400 rounded-lg font-label-bold cursor-not-allowed" disabled>
                    Indisponível
                </button>
            </div>
        </div>
    </div>
</div>
@endsection
