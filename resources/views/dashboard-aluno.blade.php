@extends('layouts.app')

@section('content')
<style>
    .belt-blue { background: linear-gradient(90deg, #1e40af 0%, #3b82f6 100%); }
    .belt-white { background: linear-gradient(90deg, #e5e7eb 0%, #ffffff 100%); border: 1px solid #d1d5db; }
    .belt-gray { background: linear-gradient(90deg, #4b5563 0%, #9ca3af 100%); }
    .belt-yellow { background: linear-gradient(90deg, #ca8a04 0%, #facc15 100%); }
    .belt-orange { background: linear-gradient(90deg, #ea580c 0%, #fb923c 100%); }
    .belt-green { background: linear-gradient(90deg, #16a34a 0%, #4ade80 100%); }
    .belt-purple { background: linear-gradient(90deg, #7c3aed 0%, #a78bfa 100%); }
    .belt-brown { background: linear-gradient(90deg, #78350f 0%, #b45309 100%); }
    .belt-black { background: linear-gradient(90deg, #000000 0%, #374151 100%); }
    
    /* Faixas com listras */
    .belt-cinza-branca { background: linear-gradient(90deg, #9ca3af 50%, #ffffff 50%); border: 1px solid #d1d5db; }
    .belt-cinza-preta { background: linear-gradient(90deg, #9ca3af 50%, #000000 50%); }
    .belt-amarela-branca { background: linear-gradient(90deg, #facc15 50%, #ffffff 50%); border: 1px solid #d1d5db; }
    .belt-amarela-preta { background: linear-gradient(90deg, #facc15 50%, #000000 50%); }
    .belt-laranja-branca { background: linear-gradient(90deg, #fb923c 50%, #ffffff 50%); border: 1px solid #d1d5db; }
    .belt-laranja-preta { background: linear-gradient(90deg, #fb923c 50%, #000000 50%); }
    .belt-verde-branca { background: linear-gradient(90deg, #4ade80 50%, #ffffff 50%); border: 1px solid #d1d5db; }
    .belt-verde-preta { background: linear-gradient(90deg, #4ade80 50%, #000000 50%); }
    
    .belt-stripe { background: #ffffff; width: 8px; height: 100%; border-radius: 1px; }
    .bento-grid {
        display: grid;
        grid-template-columns: 1fr;
        gap: 24px;
    }
    @media (min-width: 1024px) {
        .bento-grid {
            grid-template-columns: repeat(12, 1fr);
        }
    }
</style>

<div class="max-w-[1600px] mx-auto">
    <div class="bento-grid">
        <!-- Welcome & Student Profile Section -->
        <section class="col-span-12 lg:col-span-8 space-y-gutter">
            <div class="bg-white p-lg rounded-xl border border-outline-variant shadow-sm relative overflow-hidden group">
                <div class="absolute top-0 right-0 p-8 opacity-10 group-hover:opacity-20 transition-opacity">
                    <span class="material-symbols-outlined text-[120px]">fitness_center</span>
                </div>
                <div class="relative z-10 flex flex-col md:flex-row md:items-center gap-8">
                    <div class="relative">
                        <div class="w-32 h-32 rounded-full overflow-hidden border-4 border-slate-50 ring-2 ring-blue-600">
                            @if($user->photo)
                                <img class="w-full h-full object-cover" src="{{ Storage::disk('public')->url($user->photo) }}" alt="{{ $user->name }}">
                            @else
                                <img class="w-full h-full object-cover" src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=0D8ABC&color=fff&size=128" alt="{{ $user->name }}">
                            @endif
                        </div>
                    </div>
                    <div>
                        <h1 class="font-headline-lg text-slate-900 mb-xs">Bem-vindo de volta, {{ explode(' ', $user->name)[0] }}</h1>
                        <div class="flex flex-wrap gap-4">
                            <div class="flex items-center gap-2 bg-surface-container-low px-4 py-2 rounded-full border border-outline-variant">
                                <span class="material-symbols-outlined text-on-tertiary-fixed-variant text-lg">location_on</span>
                                <span class="font-label-bold text-slate-900">Unidade: Matriz Central</span>
                            </div>
                            <div class="flex items-center gap-2 bg-surface-container-low px-4 py-2 rounded-full border border-outline-variant">
                                <span class="material-symbols-outlined text-on-tertiary-fixed-variant text-lg">calendar_today</span>
                                <span class="font-label-bold text-slate-900">Membro desde: {{ $user->start_date ? $user->start_date->format('M Y') : 'N/A' }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Belt Progress Bento Item -->
            <div class="bg-white p-lg rounded-xl border border-outline-variant shadow-sm">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="font-label-bold text-slate-900 uppercase tracking-wider">Status de Graduação</h3>
                    <span class="text-xs font-bold bg-blue-50 text-blue-700 px-3 py-1 rounded-full border border-blue-100">
                        Faixa {{ $user->faixa ?? 'Branca' }} - {{ $user->grau ?? 0 }} Graus
                    </span>
                </div>
                <div class="flex items-center gap-4 mb-8">
                    @php
                        $belt = $user->faixa ?? 'Branca';
                        $beltSlug = str_replace(['/', ' '], ['-', '-'], strtolower(strtr(utf8_decode($belt), utf8_decode('áàâãéèêíïóôõöúçÑÁÀÂÃÉÈÊÍÏÓÔÕÖÚÇ'), 'aaaaeeeiiiooooucnAAAAEEEIIIOOOOUCN')));
                        $beltClass = 'belt-' . $beltSlug;
                        
                        // Fallback cases for exact matches if needed
                        if ($belt == 'Branca') $beltClass = 'belt-white';
                        if ($belt == 'Cinza') $beltClass = 'belt-gray';
                    @endphp
                    <div class="h-16 flex-1 {{ $beltClass }} rounded-lg flex items-center justify-end px-12 gap-3 shadow-inner relative overflow-hidden border border-slate-200">
                        @for($i = 0; $i < ($user->grau ?? 0); $i++)
                            <div class="belt-stripe"></div>
                        @endfor
                        <div class="w-8 h-full bg-slate-900 absolute left-0 opacity-40"></div>
                    </div>
                    <div class="text-right">
                        <p class="text-display-xl font-bold text-slate-900 leading-none">65%</p>
                        <p class="text-label-sm text-on-surface-variant">Concluído</p>
                    </div>
                </div>
                <div class="grid grid-cols-2 md:grid-cols-3 gap-gutter">
                    <div class="bg-surface-container-lowest p-4 rounded border border-slate-100 text-center">
                        <p class="text-label-sm text-on-surface-variant uppercase mb-1">Aulas Totais</p>
                        <p class="font-headline-md text-slate-900">{{ $attendancesCount }}</p>
                    </div>
                    <div class="bg-surface-container-lowest p-4 rounded border border-slate-100 text-center">
                        <p class="text-label-sm text-on-surface-variant uppercase mb-1">Este Mês</p>
                        <p class="font-headline-md text-slate-900">{{ $attendancesThisMonth }}</p>
                    </div>
                    <div class="bg-surface-container-lowest p-4 rounded border border-slate-100 text-center">
                        <p class="text-label-sm text-on-surface-variant uppercase mb-1">Frequência</p>
                        <p class="font-headline-md text-blue-600">
                            {{ $attendancesCount > 0 ? round(($attendancesThisMonth / max(1, now()->day)) * 100) : 0 }}%
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Sidebar Content Section -->
        <section class="col-span-12 lg:col-span-4 space-y-gutter">
            <!-- Check-in Card -->
            <div class="bg-primary-container p-lg rounded-xl shadow-lg border border-slate-800 text-white flex flex-col justify-between min-h-[320px]">
                <div>
                    <div class="flex items-center gap-2 mb-4">
                        <span class="w-3 h-3 bg-green-500 rounded-full animate-pulse"></span>
                        <span class="text-label-bold text-blue-100 uppercase tracking-widest">Treino de Hoje</span>
                    </div>
                    <h2 class="font-headline-md mb-2">Treino Coletivo</h2>
                    <p class="text-on-primary-container text-sm">Disponível para check-in agora</p>
                </div>
                <div class="space-y-4">
                    @if(!$hasCheckedInToday)
                    <form action="{{ route('dashboard.checkin') }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full bg-secondary-container text-on-secondary-fixed font-headline-md py-4 rounded-xl flex items-center justify-center gap-3 hover:scale-[1.02] active:scale-95 transition-all shadow-md">
                            <span class="material-symbols-outlined">how_to_reg</span>
                            Confirmar Presença
                        </button>
                    </form>
                    @else
                    <div class="w-full bg-green-100 text-green-800 font-headline-md py-4 rounded-xl flex items-center justify-center gap-3 shadow-md border border-green-200">
                        <span class="material-symbols-outlined">check_circle</span>
                        Presença Confirmada
                    </div>
                    @endif
                    <p class="text-center text-label-sm text-blue-300 italic">Check-in disponível até as 22h</p>
                </div>
            </div>

            <!-- Financial Status Card -->
            <div class="bg-white p-lg rounded-xl border border-outline-variant shadow-sm">
                <h3 class="font-label-bold text-slate-900 uppercase tracking-wider mb-6 flex items-center justify-between">
                    Situação Financeira
                    <span class="material-symbols-outlined text-on-surface-variant text-lg">account_balance_wallet</span>
                </h3>
                <div class="flex items-center gap-4 mb-6">
                    @if($isFinancialOk)
                    <div class="w-12 h-12 rounded-full bg-green-50 flex items-center justify-center text-green-600">
                        <span class="material-symbols-outlined" data-weight="fill">verified</span>
                    </div>
                    <div>
                        <p class="font-headline-md text-green-600 leading-none">Em Dia</p>
                        <p class="text-label-sm text-on-surface-variant">
                            Próximo: {{ $nextPayment ? $nextPayment->due_date->format('d/m') : 'N/A' }}
                        </p>
                    </div>
                    @else
                    <div class="w-12 h-12 rounded-full bg-red-50 flex items-center justify-center text-red-600">
                        <span class="material-symbols-outlined" data-weight="fill">warning</span>
                    </div>
                    <div>
                        <p class="font-headline-md text-red-600 leading-none">Pendente</p>
                        <p class="text-label-sm text-on-surface-variant">Regularize seu plano</p>
                    </div>
                    @endif
                </div>
                <div class="flex flex-col gap-2">
                    <a class="flex justify-between items-center p-3 rounded hover:bg-surface-container-low transition-colors group" href="#">
                        <span class="text-body-md text-slate-700">Ver histórico de pagamentos</span>
                        <span class="material-symbols-outlined text-on-surface-variant group-hover:translate-x-1 transition-transform">chevron_right</span>
                    </a>
                </div>
            </div>
        </section>

        <!-- Recent Classes Section -->
        <section class="col-span-12">
            <div class="bg-white p-lg rounded-xl border border-outline-variant shadow-sm">
                <div class="flex justify-between items-center mb-8">
                    <h3 class="font-label-bold text-slate-900 uppercase tracking-wider">Histórico de Aulas Recentes</h3>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-gutter">
                    @forelse($recentAttendances as $attendance)
                    <div class="flex items-start gap-4 p-4 rounded-xl border border-slate-100 hover:border-blue-200 transition-colors">
                        <div class="bg-blue-50 text-blue-600 p-3 rounded-lg flex flex-col items-center justify-center min-w-[64px]">
                            <span class="text-xs font-bold uppercase">{{ $attendance->date->translatedFormat('M') }}</span>
                            <span class="text-xl font-bold">{{ $attendance->date->day }}</span>
                        </div>
                        <div class="flex-1">
                            <p class="font-label-bold text-slate-900">Treino de Jiu-Jitsu</p>
                            <p class="text-label-sm text-on-surface-variant mb-2">{{ isset($currentTenant) ? $currentTenant->name : 'Gestão Combate' }}</p>
                            <div class="flex items-center gap-1 text-[10px] font-bold text-green-600 uppercase">
                                <span class="material-symbols-outlined text-sm">check_circle</span>
                                Presença Confirmada
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="col-span-12 text-center py-8 text-slate-400 italic">
                        Nenhum treino registrado recentemente.
                    </div>
                    @endforelse
                </div>
            </div>
        </section>
    </div>
</div>
@endsection
