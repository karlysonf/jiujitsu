@extends('layouts.app')

@section('content')
<!-- Content Canvas -->
<div class="max-w-[1600px] mx-auto">
    <!-- Page Header -->
    <div class="mb-6">
        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-rose-500/10 border border-rose-500/20 text-rose-400 text-xs font-bold uppercase tracking-wider mb-2">
            <span class="w-2 h-2 rounded-full bg-rose-500 animate-pulse"></span>
            Inteligência & Métricas
        </div>
        <h2 class="font-['Outfit'] font-black text-2xl md:text-4xl text-white tracking-tight">Relatórios de Gestão</h2>
        <p class="text-slate-400 text-xs md:text-sm mt-0.5">Gere relatórios detalhados sobre o faturamento, adimplência e frequência no tatame.</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        <!-- Monthly Billing Report -->
        <div class="bg-[#111726] rounded-2xl border border-white/10 p-6 shadow-xl flex flex-col justify-between group hover:border-rose-500/40 transition-all">
            <div>
                <div class="w-12 h-12 bg-rose-500/10 border border-rose-500/30 text-rose-400 rounded-xl flex items-center justify-center text-xl mb-4 shadow-lg shadow-rose-500/10">
                    <span class="material-symbols-outlined">calendar_month</span>
                </div>
                <h3 class="font-['Outfit'] font-bold text-xl text-white mb-2">Mensal (Faturamento)</h3>
                <p class="text-xs text-slate-400 mb-6 leading-relaxed">Gere um resumo detalhado dos pagamentos recebidos e pendências de um mês específico.</p>
            </div>
            
            <form action="{{ route('reports.monthly') }}" method="GET" class="space-y-4">
                <div>
                    <label class="block text-xs font-semibold text-slate-400 uppercase mb-1.5 ml-1">Selecione o Mês</label>
                    <input type="month" name="month" value="{{ date('Y-m') }}" class="w-full h-12 px-4 rounded-xl border border-white/10 bg-[#090d16] text-white focus:outline-none focus:border-rose-500 transition-all text-sm" required>
                </div>
                <button type="submit" class="w-full h-12 bg-gradient-to-r from-rose-600 to-rose-700 text-white rounded-xl font-['Outfit'] font-bold text-sm flex items-center justify-center gap-2 hover:shadow-lg hover:shadow-rose-600/30 transition-all">
                    <span class="material-symbols-outlined text-lg">assessment</span>
                    Gerar Relatório
                </button>
            </form>
        </div>

        <!-- Delinquency Report -->
        <div class="bg-[#111726] rounded-2xl border border-white/10 p-6 shadow-xl flex flex-col justify-between group hover:border-rose-500/40 transition-all">
            <div>
                <div class="w-12 h-12 bg-rose-500/10 border border-rose-500/30 text-rose-400 rounded-xl flex items-center justify-center text-xl mb-4 shadow-lg shadow-rose-500/10">
                    <span class="material-symbols-outlined">person_off</span>
                </div>
                <h3 class="font-['Outfit'] font-bold text-xl text-white mb-2">Inadimplência</h3>
                <p class="text-xs text-slate-400 mb-6 leading-relaxed">Acompanhe alunos com mensalidades em atraso e tome ações de cobrança rápida.</p>
            </div>
            
            <div class="pt-4">
                <a href="{{ route('reports.delinquency') }}" class="w-full h-12 bg-gradient-to-r from-rose-600 to-rose-700 text-white rounded-xl font-['Outfit'] font-bold text-sm flex items-center justify-center gap-2 hover:shadow-lg hover:shadow-rose-600/30 transition-all">
                    <span class="material-symbols-outlined text-lg">visibility</span>
                    Visualizar Lista
                </a>
            </div>
        </div>

        <!-- Attendance Report -->
        <div class="bg-[#111726] rounded-2xl border border-white/10 p-6 shadow-xl flex flex-col justify-between group hover:border-cyan-500/40 transition-all">
            <div>
                <div class="w-12 h-12 bg-cyan-500/10 border border-cyan-500/30 text-cyan-400 rounded-xl flex items-center justify-center text-xl mb-4 shadow-lg shadow-cyan-500/10">
                    <span class="material-symbols-outlined">how_to_reg</span>
                </div>
                <h3 class="font-['Outfit'] font-bold text-xl text-white mb-2">Frequência e Presença</h3>
                <p class="text-xs text-slate-400 mb-6 leading-relaxed">Acompanhe a assiduidade dos alunos, presenças consolidadas e relatórios de pico por período.</p>
            </div>
            
            <form action="{{ route('reports.attendance') }}" method="GET" class="space-y-4">
                <div class="grid grid-cols-2 gap-2">
                    <div>
                        <label class="block text-[10px] font-semibold text-slate-400 uppercase mb-1 ml-1">Início</label>
                        <input type="date" name="start_date" value="{{ now()->startOfMonth()->toDateString() }}" class="w-full h-10 px-2 rounded-xl border border-white/10 bg-[#090d16] text-white focus:outline-none focus:border-cyan-500 transition-all text-xs" required>
                    </div>
                    <div>
                        <label class="block text-[10px] font-semibold text-slate-400 uppercase mb-1 ml-1">Fim</label>
                        <input type="date" name="end_date" value="{{ now()->toDateString() }}" class="w-full h-10 px-2 rounded-xl border border-white/10 bg-[#090d16] text-white focus:outline-none focus:border-cyan-500 transition-all text-xs" required>
                    </div>
                </div>
                <button type="submit" class="w-full h-12 bg-gradient-to-r from-cyan-600 to-cyan-700 text-white rounded-xl font-['Outfit'] font-bold text-sm flex items-center justify-center gap-2 hover:shadow-lg hover:shadow-cyan-600/30 transition-all">
                    <span class="material-symbols-outlined text-lg">filter_alt</span>
                    Visualizar Frequência
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
