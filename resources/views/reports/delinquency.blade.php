@extends('layouts.app')

@section('content')
<!-- Page Canvas -->
<div class="p-gutter max-w-[1600px] w-full mx-auto">
    <!-- Header Section -->
    <div class="mb-lg flex justify-between items-end">
        <div>
            <h1 class="font-headline-lg text-primary mb-2 text-headline-lg">Relatório de Inadimplência</h1>
            <p class="font-body-md text-on-surface-variant max-w-2xl text-body-md">
                Monitore e gerencie as mensalidades em atraso. Utilize as ferramentas de cobrança para manter a saúde financeira da academia e reduzir a taxa de cancelamento.
            </p>
        </div>
        <div class="flex gap-3">
            <button class="flex items-center gap-2 bg-white border border-slate-200 px-4 py-2 rounded-lg font-label-bold text-slate-700 hover:bg-slate-50 transition-all">
                <span class="material-symbols-outlined text-[20px]">file_download</span>
                Exportar Excel
            </button>
            <button class="flex items-center gap-2 bg-white border border-slate-200 px-4 py-2 rounded-lg font-label-bold text-slate-700 hover:bg-slate-50 transition-all">
                <span class="material-symbols-outlined text-[20px]">picture_as_pdf</span>
                Exportar PDF
            </button>
        </div>
    </div>

    <!-- Summary Bento Grid -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-gutter mb-lg">
        <!-- Total Overdue -->
        <div class="bg-white border border-slate-200 p-gutter rounded-xl shadow-sm group hover:border-on-tertiary-container transition-all">
            <div class="flex justify-between items-start mb-4">
                <div class="p-3 bg-error/10 rounded-lg text-error">
                    <span class="material-symbols-outlined">payments</span>
                </div>
                <span class="text-xs font-label-bold text-error bg-error/5 px-2 py-1 rounded">+8% vs mês passado</span>
            </div>
            <p class="text-on-surface-variant font-label-sm uppercase tracking-wider text-label-sm">Valor Total em Atraso</p>
            <h3 class="font-display-xl text-primary mt-1 text-display-xl">R$ {{ number_format($totalOverdue, 2, ',', '.') }}</h3>
        </div>
        <!-- Overdue Students -->
        <div class="bg-white border border-slate-200 p-gutter rounded-xl shadow-sm group hover:border-on-tertiary-container transition-all">
            <div class="flex justify-between items-start mb-4">
                <div class="p-3 bg-secondary-container/20 rounded-lg text-on-secondary-container">
                    <span class="material-symbols-outlined">person_off</span>
                </div>
                <span class="text-xs font-label-bold text-on-surface-variant bg-slate-100 px-2 py-1 rounded">{{ $overdueCount }} Alunos</span>
            </div>
            <p class="text-on-surface-variant font-label-sm uppercase tracking-wider text-label-sm">Alunos Inadimplentes</p>
            <h3 class="font-display-xl text-primary mt-1 text-display-xl">{{ $overdueCount }}</h3>
        </div>
        <!-- Recovery Rate -->
        <div class="bg-white border border-slate-200 p-gutter rounded-xl shadow-sm group hover:border-on-tertiary-container transition-all">
            <div class="flex justify-between items-start mb-4">
                <div class="p-3 bg-on-tertiary-container/10 rounded-lg text-on-tertiary-container">
                    <span class="material-symbols-outlined">trending_up</span>
                </div>
                <span class="text-xs font-label-bold text-on-tertiary-container bg-on-tertiary-container/5 px-2 py-1 rounded">Meta: 20%</span>
            </div>
            <p class="text-on-surface-variant font-label-sm uppercase tracking-wider text-label-sm">Taxa de Recuperação</p>
            <h3 class="font-display-xl text-primary mt-1 text-display-xl">15%</h3>
        </div>
    </div>

    <!-- Filters & Search Bar -->
    <div class="bg-white border border-slate-200 rounded-xl p-md mb-md flex flex-wrap items-center gap-gutter">
        <div class="flex-1 min-w-[300px] relative">
            <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-slate-400">search</span>
            <input class="w-full border-slate-200 rounded-lg pl-12 py-3 focus:border-on-tertiary-container focus:ring-1 focus:ring-on-tertiary-container outline-none transition-all" placeholder="Filtrar por nome do aluno..." type="text" />
        </div>
        <div class="flex items-center gap-3">
            <span class="text-on-surface-variant font-label-bold whitespace-nowrap text-label-bold">Tempo de Atraso:</span>
            <div class="flex bg-slate-100 p-1 rounded-lg">
                <button class="px-4 py-2 text-sm font-label-bold text-slate-600 hover:text-slate-900 rounded-md transition-all">Todos</button>
                <button class="px-4 py-2 text-sm font-label-bold bg-white text-on-tertiary-container shadow-sm rounded-md transition-all">30 dias</button>
                <button class="px-4 py-2 text-sm font-label-bold text-slate-600 hover:text-slate-900 rounded-md transition-all">60 dias</button>
                <button class="px-4 py-2 text-sm font-label-bold text-slate-600 hover:text-slate-900 rounded-md transition-all">90+ dias</button>
            </div>
        </div>
        <button class="flex items-center gap-2 text-on-tertiary-container font-label-bold px-4 py-3 hover:bg-on-tertiary-container/5 rounded-lg transition-all text-label-bold">
            <span class="material-symbols-outlined">filter_list</span>
            Mais Filtros
        </button>
    </div>

    <!-- Overdue Students Table -->
    <div class="bg-white border border-slate-200 rounded-xl overflow-hidden shadow-sm">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50 border-b border-slate-200">
                    <th class="px-6 py-4 font-label-bold text-slate-600 text-label-bold">Aluno</th>
                    <th class="px-6 py-4 font-label-bold text-slate-600 text-label-bold">Dias de Atraso</th>
                    <th class="px-6 py-4 font-label-bold text-slate-600 text-label-bold">Valor Pendente</th>
                    <th class="px-6 py-4 font-label-bold text-slate-600 text-label-bold">Plano</th>
                    <th class="px-6 py-4 font-label-bold text-slate-600 text-right text-label-bold">Ações</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($overduePayments as $payment)
                @php
                    $daysOverdue = $payment->due_date->diffInDays(now());
                    $statusClass = 'bg-secondary-container/20 text-on-secondary-container';
                    if ($daysOverdue >= 90) $statusClass = 'bg-error-container text-on-error-container';
                    elseif ($daysOverdue >= 60) $statusClass = 'bg-orange-100 text-orange-700';
                @endphp
                <tr class="hover:bg-slate-50/50 transition-colors">
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 rounded-full bg-slate-100 flex items-center justify-center font-bold text-slate-600">
                                {{ strtoupper(substr($payment->user->name, 0, 2)) }}
                            </div>
                            <div>
                                <div class="font-label-bold text-slate-900 text-label-bold">{{ $payment->user->name }}</div>
                                <div class="text-xs text-slate-500">ID: #{{ $payment->user->id }}</div>
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-label-bold {{ $statusClass }} text-label-bold">
                            {{ $daysOverdue }} dias
                        </span>
                    </td>
                    <td class="px-6 py-4 font-body-md text-slate-900 text-body-md">R$ {{ number_format($payment->amount, 2, ',', '.') }}</td>
                    <td class="px-6 py-4 text-sm text-slate-600">{{ $payment->user->plan->name ?? 'N/A' }}</td>
                    <td class="px-6 py-4">
                        <div class="flex justify-end gap-2">
                            <button class="p-2 text-green-600 hover:bg-green-50 rounded-lg transition-all" title="Cobrar WhatsApp">
                                <span class="material-symbols-outlined text-[20px]">chat</span>
                            </button>
                            <button class="flex items-center gap-1 text-sm font-label-bold text-on-tertiary-container hover:bg-on-tertiary-container/5 px-3 py-2 rounded-lg transition-all text-label-bold">
                                Negociar
                            </button>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-8 text-center text-slate-500">
                        Nenhum aluno inadimplente encontrado.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
        <!-- Pagination Placeholder -->
        <div class="px-6 py-4 border-t border-slate-200 bg-slate-50 flex items-center justify-between">
            <span class="text-sm text-slate-500 font-label-sm">Mostrando {{ $overduePayments->count() }} de {{ $overdueCount }} alunos inadimplentes</span>
        </div>
    </div>
    <!-- Footnote / Help -->
    <div class="mt-gutter p-md bg-on-tertiary-container/5 rounded-xl border border-on-tertiary-container/10 flex items-center gap-4">
        <span class="material-symbols-outlined text-on-tertiary-container">info</span>
        <p class="text-sm text-slate-600">
            <strong class="text-on-tertiary-container">Dica:</strong> Alunos com mais de 90 dias de atraso são movidos automaticamente para o fluxo de "Suspensão de Matrícula". Entre em contato para negociar antes do bloqueio.
        </p>
    </div>
</div>
@endsection
