@extends('layouts.app')

@section('content')
<!-- Page Canvas -->
<div class="max-w-[1600px] w-full mx-auto">
    <!-- Header Section -->
    <div class="mb-6 flex justify-between items-end flex-wrap gap-md no-print">
        <div>
            <div class="flex items-center gap-3 mb-2">
                <a href="{{ route('reports.index') }}" class="flex items-center justify-center p-2 rounded-xl bg-[#182234] border border-white/10 text-slate-300 hover:text-white hover:bg-white/10 transition-colors">
                    <span class="material-symbols-outlined text-lg">arrow_back</span>
                </a>
                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-cyan-500/10 border border-cyan-500/20 text-cyan-400 text-xs font-bold uppercase tracking-wider">
                    <span class="w-2 h-2 rounded-full bg-cyan-400 animate-pulse"></span>
                    Frequência do Tatame
                </div>
            </div>
            <h1 class="font-['Outfit'] font-black text-2xl md:text-4xl text-white tracking-tight">Relatório de Presença e Assiduidade</h1>
            <p class="text-slate-400 text-xs md:text-sm max-w-2xl mt-1">
                Acompanhe o engajamento dos alunos no tatame. Analise médias de presença por período e trace metas de retenção.
            </p>
        </div>
        <div class="flex gap-3">
            <button onclick="window.print()" class="flex items-center gap-2 bg-[#182234] border border-white/10 px-4 py-2.5 rounded-xl font-bold text-xs text-white hover:bg-white/10 transition-all shadow-sm">
                <span class="material-symbols-outlined text-[18px]">print</span>
                Imprimir
            </button>
        </div>
    </div>

    <!-- Period Filters Form -->
    <div class="bg-[#111726] border border-white/10 rounded-2xl p-6 mb-6 shadow-xl no-print">
        <form action="{{ route('reports.attendance') }}" method="GET" class="flex flex-wrap items-end gap-4">
            <div class="flex-1 min-w-[200px]">
                <label class="block text-xs font-semibold text-slate-400 uppercase mb-1.5 ml-1">Data Inicial</label>
                <input type="date" name="start_date" value="{{ $startDate }}" class="w-full h-12 px-4 rounded-xl border border-white/10 bg-[#090d16] text-white focus:outline-none focus:border-cyan-500 transition-all text-sm" required>
            </div>
            <div class="flex-1 min-w-[200px]">
                <label class="block text-xs font-semibold text-slate-400 uppercase mb-1.5 ml-1">Data Final</label>
                <input type="date" name="end_date" value="{{ $endDate }}" class="w-full h-12 px-4 rounded-xl border border-white/10 bg-[#090d16] text-white focus:outline-none focus:border-cyan-500 transition-all text-sm" required>
            </div>
            <button type="submit" class="h-12 px-6 bg-gradient-to-r from-cyan-600 to-cyan-700 text-white rounded-xl font-['Outfit'] font-bold text-sm flex items-center justify-center gap-2 hover:shadow-lg hover:shadow-cyan-600/30 transition-all shadow-sm">
                <span class="material-symbols-outlined">filter_alt</span>
                Filtrar Período
            </button>
        </form>
    </div>

    <!-- Summary Bento Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
        <!-- Total Presences -->
        <div class="bg-[#111726] border border-white/10 p-6 rounded-2xl shadow-xl flex flex-col justify-between">
            <div class="flex justify-between items-start mb-4">
                <div class="p-3 bg-cyan-500/10 rounded-xl border border-cyan-500/30 text-cyan-400">
                    <span class="material-symbols-outlined">how_to_reg</span>
                </div>
                <span class="text-xs font-bold text-slate-400 bg-[#182234] px-2.5 py-1 rounded-full border border-white/5">No período</span>
            </div>
            <div>
                <p class="text-slate-400 text-xs font-semibold uppercase tracking-wider">Total de Presenças</p>
                <h3 class="font-['Outfit'] font-extrabold text-2xl md:text-3xl text-white mt-1">{{ $totalPresences }}</h3>
            </div>
        </div>

        <!-- Average Daily -->
        <div class="bg-[#111726] border border-white/10 p-6 rounded-2xl shadow-xl flex flex-col justify-between">
            <div class="flex justify-between items-start mb-4">
                <div class="p-3 bg-rose-500/10 rounded-xl border border-rose-500/30 text-rose-400">
                    <span class="material-symbols-outlined">query_stats</span>
                </div>
                <span class="text-xs font-bold text-slate-400 bg-[#182234] px-2.5 py-1 rounded-full border border-white/5">Média Diária</span>
            </div>
            <div>
                <p class="text-slate-400 text-xs font-semibold uppercase tracking-wider">Presenças por Dia</p>
                <h3 class="font-['Outfit'] font-extrabold text-2xl md:text-3xl text-rose-400 mt-1">{{ $avgPresencesPerDay }}</h3>
            </div>
        </div>

        <!-- Peak Day -->
        <div class="bg-[#111726] border border-white/10 p-6 rounded-2xl shadow-xl flex flex-col justify-between">
            <div class="flex justify-between items-start mb-4">
                <div class="p-3 bg-amber-500/10 rounded-xl border border-amber-500/30 text-amber-400">
                    <span class="material-symbols-outlined">trending_up</span>
                </div>
                <span class="text-xs font-bold text-amber-400 bg-amber-500/10 px-2.5 py-1 rounded-full border border-amber-500/20">Pico: {{ $peakDayCount }}</span>
            </div>
            <div>
                <p class="text-slate-400 text-xs font-semibold uppercase tracking-wider">Dia de Pico</p>
                <h3 class="font-['Outfit'] font-bold text-white mt-1 text-lg truncate" title="{{ $peakDay }}">{{ $peakDay }}</h3>
            </div>
        </div>

        <!-- Most Active Student -->
        <div class="bg-[#111726] border border-white/10 p-6 rounded-2xl shadow-xl flex flex-col justify-between">
            <div class="flex justify-between items-start mb-4">
                <div class="p-3 bg-emerald-500/10 rounded-xl border border-emerald-500/30 text-emerald-400">
                    <span class="material-symbols-outlined">military_tech</span>
                </div>
                <span class="text-xs font-bold text-emerald-400 bg-emerald-500/10 px-2.5 py-1 rounded-full border border-emerald-500/20">Destaque</span>
            </div>
            <div>
                <p class="text-slate-400 text-xs font-semibold uppercase tracking-wider">Mais Frequente</p>
                <h3 class="font-['Outfit'] font-bold text-white mt-1 text-lg truncate" title="{{ $mostFrequentUser }}">{{ $mostFrequentUser }}</h3>
            </div>
        </div>
    </div>

    <!-- Attendance Details Table -->
    <div class="bg-[#111726] border border-white/10 rounded-2xl overflow-hidden shadow-xl">
        <div class="p-5 border-b border-white/10 bg-[#182234]/50">
            <h3 class="font-['Outfit'] font-bold text-lg text-white">Consolidado de Presenças por Aluno</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-white/10 text-slate-400 text-xs font-bold uppercase tracking-wider bg-[#0d1320]/60">
                        <th class="px-6 py-4">Aluno</th>
                        <th class="px-6 py-4">Faixa</th>
                        <th class="px-6 py-4">Presenças no Período</th>
                        <th class="px-6 py-4">Última Presença</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5">
                    @forelse($reportData as $item)
                    <tr class="hover:bg-white/5 transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-xl bg-[#182234] border border-white/10 flex items-center justify-center font-bold text-xs text-cyan-400">
                                    {{ strtoupper(substr($item['user']->name, 0, 2)) }}
                                </div>
                                <span class="font-semibold text-sm text-white">{{ $item['user']->name }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-xs text-slate-300 uppercase font-bold">
                            FAIXA {{ strtoupper($item['user']->faixa ?? 'Branca') }}
                        </td>
                        <td class="px-6 py-4 font-['Outfit'] font-bold text-white text-base">
                            {{ $item['presence_count'] }} aulas
                        </td>
                        <td class="px-6 py-4 text-xs text-slate-400">
                            {{ $item['last_presence'] }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="4" class="px-6 py-12 text-center text-slate-500 italic">
                            Nenhuma presença encontrada para o período selecionado.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
