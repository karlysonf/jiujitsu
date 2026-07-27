@extends('layouts.app')

@section('content')
<!-- Page Canvas -->
<div class="max-w-[1600px] w-full mx-auto">
    <!-- Header Section -->
    <div class="mb-6 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-rose-500/10 border border-rose-500/20 text-rose-400 text-xs font-bold uppercase tracking-wider mb-2">
                <span class="w-2 h-2 rounded-full bg-rose-500 animate-pulse"></span>
                Inadimplência & Cobrança
            </div>
            <h1 class="font-['Outfit'] font-black text-2xl md:text-4xl text-white tracking-tight">Relatório de Inadimplência</h1>
            <p class="text-slate-400 text-xs md:text-sm mt-0.5">
                Monitore mensalidades em atraso e utilize as ferramentas de cobrança para manter o fluxo de caixa ativo.
            </p>
        </div>
        <div class="flex gap-3">
            <button onclick="window.print()" class="flex items-center gap-2 bg-[#182234] border border-white/10 px-4 py-2 rounded-xl font-bold text-xs text-white hover:bg-white/10 transition-all shadow-sm">
                <span class="material-symbols-outlined text-[18px]">print</span>
                Imprimir Relatório
            </button>
        </div>
    </div>

    <!-- Summary Bento Grid -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <!-- Total Overdue -->
        <div class="bg-[#111726] border border-rose-500/30 p-6 rounded-2xl shadow-xl">
            <div class="flex justify-between items-start mb-4">
                <div class="p-3 bg-rose-500/10 rounded-xl border border-rose-500/30 text-rose-400">
                    <span class="material-symbols-outlined">payments</span>
                </div>
                <span class="text-xs font-bold text-rose-400 bg-rose-500/10 px-2.5 py-1 rounded-full border border-rose-500/20">Em Atraso</span>
            </div>
            <p class="text-slate-400 text-xs font-semibold uppercase tracking-wider">Valor Total em Atraso</p>
            <h3 class="font-['Outfit'] font-extrabold text-2xl md:text-3xl text-rose-400 mt-1">R$ {{ number_format($totalOverdue, 2, ',', '.') }}</h3>
        </div>
        <!-- Overdue Students -->
        <div class="bg-[#111726] border border-white/10 p-6 rounded-2xl shadow-xl">
            <div class="flex justify-between items-start mb-4">
                <div class="p-3 bg-amber-500/10 rounded-xl border border-amber-500/30 text-amber-400">
                    <span class="material-symbols-outlined">person_off</span>
                </div>
                <span class="text-xs font-bold text-amber-400 bg-amber-500/10 px-2.5 py-1 rounded-full border border-amber-500/20">{{ $overdueCount }} Alunos</span>
            </div>
            <p class="text-slate-400 text-xs font-semibold uppercase tracking-wider">Alunos Inadimplentes</p>
            <h3 class="font-['Outfit'] font-extrabold text-2xl md:text-3xl text-white mt-1">{{ $overdueCount }} Atletas</h3>
        </div>
        <!-- Recovery Rate -->
        <div class="bg-[#111726] border border-cyan-500/30 p-6 rounded-2xl shadow-xl">
            <div class="flex justify-between items-start mb-4">
                <div class="p-3 bg-cyan-500/10 rounded-xl border border-cyan-500/30 text-cyan-400">
                    <span class="material-symbols-outlined">trending_up</span>
                </div>
                <span class="text-xs font-bold text-cyan-400 bg-cyan-500/10 px-2.5 py-1 rounded-full border border-cyan-500/20">Saúde Financeira</span>
            </div>
            <p class="text-slate-400 text-xs font-semibold uppercase tracking-wider">Status Geral</p>
            <h3 class="font-['Outfit'] font-extrabold text-2xl md:text-3xl text-cyan-400 mt-1">Ação Requerida</h3>
        </div>
    </div>

    <!-- Overdue Students Table -->
    <div class="bg-[#111726] border border-white/10 rounded-2xl overflow-hidden shadow-xl">
        <div class="p-5 border-b border-white/10 bg-[#182234]/50">
            <h3 class="font-['Outfit'] font-bold text-lg text-white">Listagem de Faturas em Atraso</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-white/10 text-slate-400 text-xs font-bold uppercase tracking-wider bg-[#0d1320]/60">
                        <th class="px-6 py-4">Aluno</th>
                        <th class="px-6 py-4">Dias de Atraso</th>
                        <th class="px-6 py-4">Valor Pendente</th>
                        <th class="px-6 py-4">Vencimento</th>
                        <th class="px-6 py-4 text-right">Ações</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5">
                    @forelse($overduePayments as $payment)
                    @php
                        $daysOverdue = $payment->due_date->diffInDays(now());
                    @endphp
                    <tr class="hover:bg-white/5 transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-xl bg-[#182234] border border-white/10 flex items-center justify-center font-bold text-xs text-rose-400">
                                    {{ strtoupper(substr($payment->user->name, 0, 2)) }}
                                </div>
                                <div>
                                    <p class="font-semibold text-sm text-white">{{ $payment->user->name }}</p>
                                    <p class="text-[11px] text-slate-400">{{ $payment->user->email }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-rose-500/10 border border-rose-500/30 text-rose-400">
                                {{ $daysOverdue }} dia(s) em atraso
                            </span>
                        </td>
                        <td class="px-6 py-4 font-['Outfit'] font-extrabold text-white text-base">
                            R$ {{ number_format($payment->amount, 2, ',', '.') }}
                        </td>
                        <td class="px-6 py-4 text-xs text-slate-300">
                            {{ $payment->due_date->format('d/m/Y') }}
                        </td>
                        <td class="px-6 py-4 text-right">
                            <button onclick="window.location='{{ route('payments.index') }}'" class="px-3 py-1.5 bg-gradient-to-r from-rose-600 to-rose-700 text-white rounded-xl text-xs font-bold hover:shadow-lg hover:shadow-rose-600/30 transition-all">
                                Receber Baixa
                            </button>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-slate-500 italic">
                            Nenhum aluno em atraso registrado neste momento. Excelente gestão!
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
