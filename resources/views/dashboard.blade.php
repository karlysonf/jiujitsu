@extends('layouts.app')

@section('content')
@can('view-dashboard')
<!-- Welcome Card -->
<section class="mb-6">
    <div class="relative overflow-hidden bg-gradient-to-br from-[#090d16] via-[#111726] to-[#182234] rounded-2xl p-6 md:p-8 flex justify-between items-center text-white border border-rose-500/30 shadow-2xl shadow-rose-950/20">
        <div class="absolute inset-0 bg-gradient-to-r from-rose-600/10 to-cyan-500/10 pointer-events-none"></div>
        <div class="relative z-10 w-full md:w-2/3">
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-rose-500/10 border border-rose-500/20 text-rose-400 text-xs font-bold uppercase tracking-wider mb-3">
                <span class="w-2 h-2 rounded-full bg-rose-500 animate-pulse"></span>
                Gestão de Elite
            </div>
            <h1 class="font-['Outfit'] font-black text-3xl md:text-5xl text-white mb-2 tracking-tight">Oss, Prof. {{ explode(' ', auth()->user()->name)[0] }}!</h1>
            <p class="text-sm md:text-base text-slate-300 max-w-md font-light">
                O tatame está pronto. Você tem <span class="text-rose-400 font-bold">{{ $late_count }}</span> pendências financeiras urgentes.
            </p>
            <div class="mt-6 flex flex-wrap gap-4">
                <div class="bg-[#111726]/80 backdrop-blur-md border border-rose-500/30 rounded-xl px-4 py-2.5 flex items-center gap-3 shadow-lg">
                    <span class="material-symbols-outlined text-rose-400 text-xl">military_tech</span>
                    <div>
                        <p class="text-[10px] text-slate-400 uppercase tracking-wider font-semibold">Alunos Ativos</p>
                        <span class="font-bold text-white text-base">{{ $active_users }} Atletas</span>
                    </div>
                </div>
                <div class="bg-[#111726]/80 backdrop-blur-md border border-cyan-500/30 rounded-xl px-4 py-2.5 flex items-center gap-3 shadow-lg">
                    <span class="material-symbols-outlined text-cyan-400 text-xl">event_available</span>
                    <div>
                        <p class="text-[10px] text-slate-400 uppercase tracking-wider font-semibold">Presença Média</p>
                        <span class="font-bold text-white text-base">{{ $attendance_rate }}% Taxa</span>
                    </div>
                </div>
            </div>
        </div>
        <div class="hidden md:block absolute right-0 top-0 h-full w-1/3 opacity-15 pointer-events-none">
            <img class="h-full w-full object-cover grayscale" alt="Jiu-Jitsu Belt" src="https://lh3.googleusercontent.com/aida-public/AB6AXuDO84udy_5nZZSK1F4KkuE9dHTCdEv6PiaAxznWKslgMTmQtUkZ7EWf66_vHsyzuygWrJOQkm_17ceoBXTh3KXE3pqTF1cnWSaCsqzZEu7wdN-Swwy8jf40bJ0UFhFcE6PA4RUFo_ucPKfVLF5aVsGH01k_g8VqIs_yfHmujpEkXz3t1cVqUh3dr-q9Uii4xTPsiWfIm0inL3MPauhn6qsDQZ3pCKIAWTnfuDx-fLa_xyCWeF-7dFheJENtdN4TrnyoTukiUWYa3_8A"/>
        </div>
    </div>
</section>

<!-- Financial Summary -->
@if(auth()->user()->hasAnyRole(['root', 'admin']) || in_array(auth()->user()->role_id, [1, 2]))
<section class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
    <!-- Received -->
    <div class="bg-[#111726] rounded-2xl p-6 border border-emerald-500/20 shadow-xl flex flex-col justify-between hover:border-emerald-500/40 transition-all group">
        <div class="flex justify-between items-start mb-4">
            <div class="p-3 bg-emerald-500/10 rounded-xl border border-emerald-500/20">
                <span class="material-symbols-outlined text-emerald-400">account_balance_wallet</span>
            </div>
            <span class="text-emerald-400 bg-emerald-500/10 border border-emerald-500/20 px-2.5 py-1 rounded-full text-xs font-bold">Mês Atual</span>
        </div>
        <div>
            <p class="text-slate-400 text-xs uppercase tracking-wider mb-1 font-semibold">Valor Recebido</p>
            <h3 class="font-['Outfit'] font-extrabold text-2xl md:text-3xl text-emerald-400">R$ {{ number_format($total_received, 2, ',', '.') }}</h3>
        </div>
    </div>

    <!-- Pending -->
    <div class="bg-[#111726] rounded-2xl p-6 border border-amber-500/20 shadow-xl flex flex-col justify-between hover:border-amber-500/40 transition-all group">
        <div class="flex justify-between items-start mb-4">
            <div class="p-3 bg-amber-500/10 rounded-xl border border-amber-500/20">
                <span class="material-symbols-outlined text-amber-400">pending_actions</span>
            </div>
            <span class="text-amber-400 bg-amber-500/10 border border-amber-500/20 px-2.5 py-1 rounded-full text-xs font-bold">{{ $pending_count }} faturas</span>
        </div>
        <div>
            <p class="text-slate-400 text-xs uppercase tracking-wider mb-1 font-semibold">Valor a Receber</p>
            <h3 class="font-['Outfit'] font-extrabold text-2xl md:text-3xl text-amber-400">R$ {{ number_format($total_pending, 2, ',', '.') }}</h3>
        </div>
    </div>

    <!-- Overdue -->
    <div class="bg-[#111726] rounded-2xl p-6 border border-rose-500/30 shadow-xl flex flex-col justify-between hover:border-rose-500/50 transition-all group">
        <div class="flex justify-between items-start mb-4">
            <div class="p-3 bg-rose-500/10 rounded-xl border border-rose-500/30">
                <span class="material-symbols-outlined text-rose-400">warning</span>
            </div>
            <button onclick="window.location='{{ route('payments.index') }}'" class="text-rose-400 hover:text-rose-300 hover:underline text-xs font-bold transition-colors">Ver Pendências →</button>
        </div>
        <div>
            <p class="text-slate-400 text-xs uppercase tracking-wider mb-1 font-semibold">Valor Inadimplente</p>
            <h3 class="font-['Outfit'] font-extrabold text-2xl md:text-3xl text-rose-400">R$ {{ number_format($total_late, 2, ',', '.') }}</h3>
        </div>
    </div>
</section>
@endif

<!-- Chart & Recent Activity Section -->
<section class="grid grid-cols-1 {{ auth()->user()->hasAnyRole(['root', 'admin']) || in_array(auth()->user()->role_id, [1, 2]) ? 'lg:grid-cols-3' : '' }} gap-6 mb-6">
    <!-- Monthly Flow Chart -->
    @if(auth()->user()->hasAnyRole(['root', 'admin']) || in_array(auth()->user()->role_id, [1, 2]))
    <div class="lg:col-span-2 bg-[#111726] rounded-2xl p-6 border border-white/10 shadow-xl flex flex-col justify-between">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h4 class="font-['Outfit'] font-bold text-xl text-white">Fluxo Mensal de Pagamentos</h4>
                <p class="text-slate-400 text-xs mt-0.5">Performance financeira do último semestre</p>
            </div>
            <span class="text-xs text-rose-400 font-bold bg-rose-500/10 border border-rose-500/20 px-3 py-1 rounded-full">Financeiro</span>
        </div>
        <!-- Visual Representation of a Bar Chart -->
        <div class="h-[280px] flex items-end justify-between gap-4 px-2 pt-4">
            @php $maxFlow = collect($monthly_flow)->max('value') ?: 1; @endphp
            @foreach($monthly_flow as $flow)
            <div class="flex-1 flex flex-col items-center gap-2 h-full justify-end">
                <div class="w-full bg-[#182234] rounded-t-xl h-full relative group flex items-end">
                    <div class="w-full bg-gradient-to-t from-rose-700 to-rose-500 rounded-t-xl transition-all duration-500 shadow-lg shadow-rose-500/20 group-hover:from-rose-600 group-hover:to-rose-400" style="height: {{ max(($flow['value'] / $maxFlow) * 100, 5) }}%"></div>
                    <div class="opacity-0 group-hover:opacity-100 absolute -top-9 left-1/2 -translate-x-1/2 bg-[#090d16] border border-rose-500/30 text-rose-300 text-[10px] font-bold px-2 py-1 rounded-md whitespace-nowrap transition-opacity shadow-lg z-20">
                        R$ {{ number_format($flow['value'], 0, ',', '.') }}
                    </div>
                </div>
                <span class="text-xs text-slate-400 font-medium">{{ $flow['label'] }}</span>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Presence Highlights -->
    <div class="bg-[#111726] rounded-2xl border border-white/10 shadow-xl overflow-hidden flex flex-col justify-between">
        <div class="p-6 border-b border-white/10">
            <h4 class="font-['Outfit'] font-bold text-xl text-white">Destaques de Presença</h4>
            <p class="text-slate-400 text-xs mt-0.5">Alunos mais ativos no tatame este mês</p>
        </div>
        <div class="divide-y divide-white/5 flex-1">
            @foreach($graduation_candidates as $candidate)
            <div class="p-4 flex items-center gap-4 hover:bg-white/5 transition-colors cursor-pointer" onclick="window.location='{{ route('users.show', $candidate->id) }}'">
                <div class="h-10 w-10 rounded-xl bg-gradient-to-br from-rose-600 to-rose-700 flex items-center justify-center font-bold text-white text-xs shadow-md shadow-rose-600/30">
                    {{ strtoupper(substr($candidate->name, 0, 2)) }}
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-semibold text-white truncate">{{ $candidate->name }}</p>
                    <div class="flex items-center gap-2 mt-1">
                        <div class="h-1.5 flex-1 bg-[#182234] rounded-full overflow-hidden">
                            <div class="h-full bg-gradient-to-r from-cyan-500 to-cyan-400" style="width: {{ min(($candidate->attendances_count / 12) * 100, 100) }}%"></div>
                        </div>
                        <span class="text-[10px] text-cyan-400 font-bold">Faixa {{ $candidate->faixa }}</span>
                    </div>
                </div>
                <span class="material-symbols-outlined text-slate-500 text-lg">chevron_right</span>
            </div>
            @endforeach
        </div>
        <div class="p-4 bg-[#0d1320] text-center border-t border-white/5">
            <button onclick="window.location='{{ route('attendances.index') }}'" class="text-cyan-400 text-xs font-bold hover:text-cyan-300 transition-colors">Ver todas as presenças →</button>
        </div>
    </div>
</section>

<!-- Active Students Table -->
<section class="mt-6">
    <div class="bg-[#111726] rounded-2xl border border-white/10 shadow-xl p-6">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6">
            <div>
                <h4 class="font-['Outfit'] font-bold text-xl text-white">Gestão de Atletas e Alunos</h4>
                <p class="text-slate-400 text-xs mt-0.5">Listagem rápida e situação cadastral no tatame</p>
            </div>
            <div class="flex gap-2 w-full md:w-auto">
                <div class="relative flex-1 md:w-80">
                    <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-sm">search</span>
                    <input class="w-full pl-10 pr-4 py-2 bg-[#090d16] border border-white/10 rounded-xl text-sm text-white placeholder-slate-500 focus:outline-none focus:border-rose-500 focus:ring-1 focus:ring-rose-500 transition-all" placeholder="Buscar aluno..." type="text"/>
                </div>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-white/10 text-slate-400 text-xs font-bold uppercase tracking-wider">
                        <th class="pb-3 px-2">Aluno</th>
                        <th class="pb-3 px-2">Graduação / Faixa</th>
                        <th class="pb-3 px-2">Plano</th>
                        <th class="pb-3 px-2">Situação</th>
                        <th class="pb-3 px-2 text-right">Ações</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5">
                    @foreach($all_students as $student)
                    <tr class="hover:bg-white/5 transition-colors">
                        <td class="py-3.5 px-2">
                            <div class="flex items-center gap-3">
                                <div class="h-9 w-9 rounded-xl bg-[#182234] border border-white/10 flex items-center justify-center text-xs font-bold text-rose-400">
                                    {{ strtoupper(substr($student->name, 0, 2)) }}
                                </div>
                                <div>
                                    <span class="font-semibold text-sm text-white block">{{ $student->name }}</span>
                                    <span class="text-[11px] text-slate-400">{{ $student->email }}</span>
                                </div>
                            </div>
                        </td>
                        <td class="py-3.5 px-2 text-sm">
                            @php
                                $beltColor = match(strtolower($student->faixa)) {
                                    'branca' => 'bg-slate-200 text-slate-900 font-bold',
                                    'cinza' => 'bg-slate-500 text-white',
                                    'amarela' => 'bg-yellow-400 text-yellow-950 font-bold',
                                    'laranja' => 'bg-orange-500 text-white',
                                    'verde' => 'bg-emerald-600 text-white',
                                    'azul' => 'bg-blue-600 text-white',
                                    'roxa' => 'bg-purple-600 text-white',
                                    'marrom' => 'bg-amber-900 text-white',
                                    'preta' => 'bg-slate-900 text-white border border-slate-700',
                                    default => 'bg-slate-800 text-slate-300'
                                };
                            @endphp
                            <span class="px-2.5 py-1 {{ $beltColor }} rounded-md text-[10px] font-bold tracking-wider shadow-sm uppercase">FAIXA {{ strtoupper($student->faixa) }}</span>
                        </td>
                        <td class="py-3.5 px-2 text-sm text-slate-300">{{ $student->plan?->name ?? 'Sem Plano' }}</td>
                        <td class="py-3.5 px-2">
                            @php
                                $hasLate = $student->payments()->where('status', 'late')->orWhere(function($q) {
                                    $q->where('status', 'pending')->where('due_date', '<', now());
                                })->exists();
                            @endphp
                            @if($hasLate)
                                <span class="px-2.5 py-1 bg-rose-500/10 border border-rose-500/30 text-rose-400 rounded-full text-[10px] font-bold uppercase">ATRASADO</span>
                            @else
                                <span class="px-2.5 py-1 bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 rounded-full text-[10px] font-bold uppercase">EM DIA</span>
                            @endif
                        </td>
                        <td class="py-3.5 px-2 text-right">
                            <button onclick="window.location='{{ route('users.show', $student->id) }}'" class="p-1.5 hover:bg-white/10 rounded-lg text-slate-400 hover:text-white transition-colors" title="Ver Perfil">
                                <span class="material-symbols-outlined text-lg">visibility</span>
                            </button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</section>
@endcan
@endsection
