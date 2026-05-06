@extends('layouts.app')

@section('content')
@can('view-dashboard')
<!-- Welcome Card -->
<section class="mb-gutter">
    <div class="relative overflow-hidden bg-primary-container rounded-xl p-4 md:p-8 flex justify-between items-center text-white border border-slate-800">
        <div class="relative z-10 w-full md:w-2/3">
            <h1 class="font-display-xl text-3xl md:text-5xl text-white mb-2">Oss, Prof. {{ explode(' ', auth()->user()->name)[0] }}!</h1>
            <p class="font-body-lg text-sm md:text-base text-slate-300 max-w-md">
                O tatame está pronto. Você tem {{ $graduation_candidates->count() }} alunos em destaque para graduação este mês e {{ $late_count }} pendências financeiras urgentes.
            </p>
            <div class="mt-6 flex gap-4">
                <div class="bg-white/10 backdrop-blur-md border border-white/20 rounded-lg px-4 py-2 flex items-center gap-2">
                    <span class="material-symbols-outlined text-secondary-fixed">military_tech</span>
                    <span class="font-label-bold">{{ $active_users }} Alunos Ativos</span>
                </div>
                <div class="bg-white/10 backdrop-blur-md border border-white/20 rounded-lg px-4 py-2 flex items-center gap-2">
                    <span class="material-symbols-outlined text-blue-400">event_available</span>
                    <span class="font-label-bold">{{ $attendance_rate }}% Presença Média</span>
                </div>
            </div>
        </div>
        <div class="hidden md:block absolute right-0 top-0 h-full w-1/3 opacity-20 pointer-events-none">
            <img class="h-full w-full object-cover grayscale" alt="Jiu-Jitsu Belt" src="https://lh3.googleusercontent.com/aida-public/AB6AXuDO84udy_5nZZSK1F4KkuE9dHTCdEv6PiaAxznWKslgMTmQtUkZ7EWf66_vHsyzuygWrJOQkm_17ceoBXTh3KXE3pqTF1cnWSaCsqzZEu7wdN-Swwy8jf40bJ0UFhFcE6PA4RUFo_ucPKfVLF5aVsGH01k_g8VqIs_yfHmujpEkXz3t1cVqUh3dr-q9Uii4xTPsiWfIm0inL3MPauhn6qsDQZ3pCKIAWTnfuDx-fLa_xyCWeF-7dFheJENtdN4TrnyoTukiUWYa3_8A"/>
        </div>
    </div>
</section>

<!-- Financial Summary -->
<section class="grid grid-cols-1 md:grid-cols-3 gap-gutter mb-gutter">
    <!-- Received -->
    <div class="bg-white rounded-xl p-6 border border-slate-200 shadow-sm flex flex-col justify-between">
        <div class="flex justify-between items-start mb-4">
            <div class="p-3 bg-emerald-50 rounded-lg">
                <span class="material-symbols-outlined text-emerald-600">account_balance_wallet</span>
            </div>
            <span class="text-emerald-600 bg-emerald-50 px-2 py-1 rounded text-label-sm font-bold">Mês Atual</span>
        </div>
        <div>
            <p class="text-slate-500 font-label-sm uppercase tracking-wider mb-1">Valor Recebido</p>
            <h3 class="font-headline-lg text-slate-900">R$ {{ number_format($total_received, 2, ',', '.') }}</h3>
        </div>
    </div>
    <!-- Pending -->
    <div class="bg-white rounded-xl p-6 border border-slate-200 shadow-sm flex flex-col justify-between">
        <div class="flex justify-between items-start mb-4">
            <div class="p-3 bg-amber-50 rounded-lg">
                <span class="material-symbols-outlined text-amber-600">pending_actions</span>
            </div>
            <span class="text-amber-600 font-label-sm font-bold italic">{{ $pending_count }} faturas</span>
        </div>
        <div>
            <p class="text-slate-500 font-label-sm uppercase tracking-wider mb-1">Valor a Receber</p>
            <h3 class="font-headline-lg text-slate-900">R$ {{ number_format($total_pending, 2, ',', '.') }}</h3>
        </div>
    </div>
    <!-- Overdue -->
    <div class="bg-white rounded-xl p-6 border border-slate-200 shadow-sm flex flex-col justify-between">
        <div class="flex justify-between items-start mb-4">
            <div class="p-3 bg-rose-50 rounded-lg">
                <span class="material-symbols-outlined text-rose-600">warning</span>
            </div>
            <button onclick="window.location='{{ route('payments.index') }}'" class="text-rose-600 hover:underline font-label-sm font-bold">Ver Pendências</button>
        </div>
        <div>
            <p class="text-slate-500 font-label-sm uppercase tracking-wider mb-1">Valor Inadimplente</p>
            <h3 class="font-headline-lg text-rose-700">R$ {{ number_format($total_late, 2, ',', '.') }}</h3>
        </div>
    </div>
</section>

<!-- Chart Section -->
<section class="grid grid-cols-1 lg:grid-cols-3 gap-gutter">
    <!-- Monthly Flow Chart -->
    <div class="lg:col-span-2 bg-white rounded-xl p-6 border border-slate-200 shadow-sm">
        <div class="flex justify-between items-center mb-8">
            <div>
                <h4 class="font-headline-md text-slate-900">Fluxo Mensal de Pagamentos</h4>
                <p class="text-slate-500 text-sm">Performance financeira do último semestre</p>
            </div>
        </div>
        <!-- Visual Representation of a Bar Chart -->
        <div class="h-[300px] flex items-end justify-between gap-4 px-4">
            @php $maxFlow = collect($monthly_flow)->max('value') ?: 1; @endphp
            @foreach($monthly_flow as $flow)
            <div class="flex-1 flex flex-col items-center gap-2">
                <div class="w-full bg-slate-100 rounded-t-lg h-full relative group">
                    <div class="absolute bottom-0 w-full bg-on-tertiary-container rounded-t-lg transition-all duration-500" style="height: {{ ($flow['value'] / $maxFlow) * 100 }}%"></div>
                    <div class="opacity-0 group-hover:opacity-100 absolute -top-8 left-1/2 -translate-x-1/2 bg-slate-800 text-white text-[10px] px-2 py-1 rounded whitespace-nowrap transition-opacity">
                        R$ {{ number_format($flow['value'], 0, ',', '.') }}
                    </div>
                </div>
                <span class="text-xs text-slate-400 font-label-sm">{{ $flow['label'] }}</span>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Recent Activities / Quick Metrics -->
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="p-6 border-b border-slate-100">
            <h4 class="font-label-bold text-slate-900">Destaques de Presença</h4>
            <p class="text-slate-500 text-xs">Alunos mais ativos este mês</p>
        </div>
        <div class="divide-y divide-slate-100">
            @foreach($graduation_candidates as $candidate)
            <div class="p-4 flex items-center gap-4 hover:bg-slate-50 transition-colors cursor-pointer" onclick="window.location='{{ route('users.show', $candidate->id) }}'">
                <div class="h-10 w-10 rounded-full bg-slate-100 flex items-center justify-center font-bold text-slate-600">
                    {{ strtoupper(substr($candidate->name, 0, 2)) }}
                </div>
                <div class="flex-1">
                    <p class="text-sm font-label-bold">{{ $candidate->name }}</p>
                    <div class="flex items-center gap-2 mt-1">
                        <div class="h-1.5 w-24 bg-slate-100 rounded-full overflow-hidden">
                            <div class="h-full bg-blue-500" style="width: {{ min(($candidate->attendances_count / 12) * 100, 100) }}%"></div>
                        </div>
                        <span class="text-[10px] text-slate-500">Faixa {{ $candidate->faixa }}</span>
                    </div>
                </div>
                <span class="material-symbols-outlined text-slate-400">chevron_right</span>
            </div>
            @endforeach
        </div>
        <div class="p-4 bg-slate-50 text-center">
            <button onclick="window.location='{{ route('attendances.index') }}'" class="text-blue-600 text-xs font-label-bold hover:underline">Ver todas as presenças</button>
        </div>
    </div>
</section>

<!-- Active Students Filter & List -->
<section class="mt-gutter">
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6">
            <h4 class="font-headline-md text-slate-900">Gestão de Alunos</h4>
            <div class="flex gap-2 w-full md:w-auto">
                <div class="relative flex-1 md:w-80">
                    <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-outline text-sm">filter_list</span>
                    <input class="w-full pl-10 pr-4 py-2 border border-slate-200 rounded-lg text-sm focus:ring-2 focus:ring-on-tertiary-container focus:outline-none" placeholder="Buscar aluno..." type="text"/>
                </div>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="border-b border-slate-100">
                        <th class="pb-4 font-label-bold text-slate-500 text-xs uppercase tracking-wider">Aluno</th>
                        <th class="pb-4 font-label-bold text-slate-500 text-xs uppercase tracking-wider">Faixa</th>
                        <th class="pb-4 font-label-bold text-slate-500 text-xs uppercase tracking-wider">Plano</th>
                        <th class="pb-4 font-label-bold text-slate-500 text-xs uppercase tracking-wider">Status Financeiro</th>
                        <th class="pb-4"></th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @foreach($all_students as $student)
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="py-4">
                            <div class="flex items-center gap-3">
                                <div class="h-8 w-8 rounded-full bg-slate-200 flex items-center justify-center text-[10px] font-bold text-slate-500">
                                    {{ strtoupper(substr($student->name, 0, 2)) }}
                                </div>
                                <span class="font-label-bold text-sm">{{ $student->name }}</span>
                            </div>
                        </td>
                        <td class="py-4 text-sm">
                            @php
                                $beltColor = match(strtolower($student->faixa)) {
                                    'branca' => 'bg-white text-slate-900 border border-slate-300',
                                    'azul' => 'bg-blue-600 text-white',
                                    'roxa' => 'bg-purple-600 text-white',
                                    'marrom' => 'bg-amber-900 text-white',
                                    'preta' => 'bg-slate-900 text-white',
                                    default => 'bg-slate-100 text-slate-600'
                                };
                            @endphp
                            <span class="px-2 py-1 {{ $beltColor }} rounded text-[10px] font-bold">{{ strtoupper($student->faixa) }}</span>
                        </td>
                        <td class="py-4 text-sm text-slate-600">{{ $student->plan?->name ?? 'Sem Plano' }}</td>
                        <td class="py-4">
                            @php
                                $hasLate = $student->payments()->where('status', 'late')->orWhere(function($q) {
                                    $q->where('status', 'pending')->where('due_date', '<', now());
                                })->exists();
                            @endphp
                            @if($hasLate)
                                <span class="px-2 py-1 bg-rose-100 text-rose-700 rounded-full text-[10px] font-bold">ATRASADO</span>
                            @else
                                <span class="px-2 py-1 bg-emerald-100 text-emerald-700 rounded-full text-[10px] font-bold">EM DIA</span>
                            @endif
                        </td>
                        <td class="py-4 text-right">
                            <button onclick="window.location='{{ route('users.show', $student->id) }}'" class="material-symbols-outlined text-slate-400 hover:text-slate-900">visibility</button>
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
