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
        <section class="col-span-12 lg:col-span-8 space-y-6">
            <div class="bg-[#111726] p-6 md:p-8 rounded-2xl border border-white/10 shadow-xl relative overflow-hidden group">
                <div class="absolute top-0 right-0 p-8 opacity-5 group-hover:opacity-10 transition-opacity">
                    <span class="material-symbols-outlined text-[140px] text-white">sports_mma</span>
                </div>
                <div class="relative z-10 flex flex-col md:flex-row md:items-center gap-8">
                    <div class="relative">
                        <div class="w-32 h-32 rounded-full overflow-hidden border-4 border-[#182234] ring-2 ring-rose-500 shadow-xl shadow-rose-500/20">
                            @if($user->photo)
                                <img class="w-full h-full object-cover" src="{{ Storage::disk('public')->url($user->photo) }}" alt="{{ $user->name }}">
                            @else
                                <img class="w-full h-full object-cover" src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=E11D48&color=fff&size=128" alt="{{ $user->name }}">
                            @endif
                        </div>
                    </div>
                    <div>
                        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-cyan-500/10 border border-cyan-500/20 text-cyan-400 text-xs font-bold uppercase tracking-wider mb-2">
                            <span class="w-2 h-2 rounded-full bg-cyan-400 animate-pulse"></span>
                            Atleta de Elite
                        </div>
                        <h1 class="font-['Outfit'] font-black text-2xl md:text-4xl text-white mb-2 tracking-tight">Bem-vindo de volta, {{ explode(' ', $user->name)[0] }}</h1>
                        <div class="flex flex-wrap gap-3 mt-4">
                            <div class="flex items-center gap-2 bg-[#182234] px-4 py-2 rounded-xl border border-white/10 text-slate-300 text-xs font-semibold">
                                <span class="material-symbols-outlined text-rose-500 text-base">location_on</span>
                                <span>Unidade: Matriz Central</span>
                            </div>
                            <div class="flex items-center gap-2 bg-[#182234] px-4 py-2 rounded-xl border border-white/10 text-slate-300 text-xs font-semibold">
                                <span class="material-symbols-outlined text-cyan-400 text-base">calendar_today</span>
                                <span>Membro desde: {{ $user->start_date ? $user->start_date->format('M Y') : 'N/A' }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Belt Progress Bento Item -->
            <div class="bg-[#111726] p-6 md:p-8 rounded-2xl border border-white/10 shadow-xl">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="font-['Outfit'] font-bold text-lg text-white uppercase tracking-wider">Status de Graduação</h3>
                    <span class="text-xs font-bold bg-rose-500/10 border border-rose-500/30 text-rose-400 px-3 py-1 rounded-full uppercase">
                        Faixa {{ $user->faixa ?? 'Branca' }} - {{ $user->grau ?? 0 }} Graus
                    </span>
                </div>
                <div class="flex items-center gap-4 mb-8">
                    @php
                        $belt = $user->faixa ?? 'Branca';
                        $beltClass = 'belt-white';
                        if (strtolower($belt) == 'azul') $beltClass = 'belt-blue';
                        if (strtolower($belt) == 'preta') $beltClass = 'belt-black';
                    @endphp
                    <div class="h-16 flex-1 {{ $beltClass }} rounded-xl flex items-center justify-end px-12 gap-3 shadow-inner relative overflow-hidden border border-white/20">
                        @for($i = 0; $i < ($user->grau ?? 0); $i++)
                            <div class="belt-stripe"></div>
                        @endfor
                        <div class="w-8 h-full bg-slate-900 absolute left-0 opacity-40"></div>
                    </div>
                    <div class="text-right">
                        <p class="font-['Outfit'] font-extrabold text-3xl text-cyan-400 leading-none">65%</p>
                        <p class="text-xs text-slate-400 mt-1">Concluído</p>
                    </div>
                </div>
                <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                    <div class="bg-[#182234] p-4 rounded-xl border border-white/10 text-center">
                        <p class="text-xs text-slate-400 uppercase tracking-wider mb-1 font-semibold">Aulas Totais</p>
                        <p class="font-['Outfit'] font-extrabold text-2xl text-white">{{ $attendancesCount }}</p>
                    </div>
                    <div class="bg-[#182234] p-4 rounded-xl border border-white/10 text-center">
                        <p class="text-xs text-slate-400 uppercase tracking-wider mb-1 font-semibold">Este Mês</p>
                        <p class="font-['Outfit'] font-extrabold text-2xl text-rose-400">{{ $attendancesThisMonth }}</p>
                    </div>
                    <div class="bg-[#182234] p-4 rounded-xl border border-white/10 text-center">
                        <p class="text-xs text-slate-400 uppercase tracking-wider mb-1 font-semibold">Frequência</p>
                        <p class="font-['Outfit'] font-extrabold text-2xl text-cyan-400">
                            {{ $attendancesCount > 0 ? round(($attendancesThisMonth / max(1, now()->day)) * 100) : 0 }}%
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Sidebar Content Section -->
        <section class="col-span-12 lg:col-span-4 space-y-6">
            <!-- Check-in Card -->
            <div class="bg-gradient-to-br from-[#182234] via-[#111726] to-[#090d16] p-6 rounded-2xl shadow-xl border border-rose-500/30 text-white flex flex-col justify-between min-h-[320px]">
                <div>
                    <div class="flex items-center gap-2 mb-4">
                        <span class="w-3 h-3 bg-emerald-400 rounded-full animate-pulse"></span>
                        <span class="text-xs font-bold text-cyan-400 uppercase tracking-widest">Treino de Hoje</span>
                    </div>
                    <h2 class="font-['Outfit'] font-extrabold text-2xl text-white mb-1">Treino Coletivo</h2>
                    <p class="text-slate-300 text-sm">Disponível para check-in no tatame agora</p>
                </div>
                <div class="space-y-4 mt-6">
                    @if(!$hasCheckedInToday)
                    <form action="{{ route('dashboard.checkin') }}" method="POST">
                        @csrf
                        <button type="submit" class="w-full bg-gradient-to-r from-rose-600 to-rose-700 text-white font-bold py-4 rounded-xl flex items-center justify-center gap-3 hover:shadow-lg hover:shadow-rose-600/40 active:scale-95 transition-all">
                            <span class="material-symbols-outlined">how_to_reg</span>
                            Confirmar Presença
                        </button>
                    </form>
                    @else
                    <div class="w-full bg-emerald-500/10 text-emerald-400 border border-emerald-500/30 font-bold py-4 rounded-xl flex items-center justify-center gap-3 shadow-md">
                        <span class="material-symbols-outlined">check_circle</span>
                        Presença Confirmada
                    </div>
                    @endif
                    <p class="text-center text-xs text-slate-400 italic">Check-in disponível até as 22h</p>
                </div>
            </div>

            <!-- Financial Status Card -->
            <div class="bg-[#111726] p-6 rounded-2xl border border-white/10 shadow-xl">
                <h3 class="font-['Outfit'] font-bold text-white uppercase tracking-wider mb-6 flex items-center justify-between">
                    Situação Financeira
                    <span class="material-symbols-outlined text-rose-400">account_balance_wallet</span>
                </h3>
                <div class="flex items-center gap-4 mb-6">
                    @if($isFinancialOk)
                    <div class="w-12 h-12 rounded-xl bg-emerald-500/10 border border-emerald-500/30 flex items-center justify-center text-emerald-400">
                        <span class="material-symbols-outlined">verified</span>
                    </div>
                    <div>
                        <p class="font-['Outfit'] font-bold text-xl text-emerald-400 leading-none">Em Dia</p>
                        <p class="text-xs text-slate-400 mt-1">
                            Próximo: {{ $nextPayment ? $nextPayment->due_date->format('d/m') : 'N/A' }}
                        </p>
                    </div>
                    @else
                    <div class="w-12 h-12 rounded-xl bg-rose-500/10 border border-rose-500/30 flex items-center justify-center text-rose-400">
                        <span class="material-symbols-outlined">warning</span>
                    </div>
                    <div>
                        <p class="font-['Outfit'] font-bold text-xl text-rose-400 leading-none">Pendente</p>
                        <p class="text-xs text-slate-400 mt-1">Regularize seu plano</p>
                    </div>
                    @endif
                </div>
                <div class="flex flex-col gap-2">
                    <a class="flex justify-between items-center p-3 rounded-xl hover:bg-white/5 text-slate-300 transition-colors group text-sm font-medium" href="{{ route('portal.payments.index') }}">
                        <span>Ver histórico de pagamentos</span>
                        <span class="material-symbols-outlined text-slate-400 group-hover:translate-x-1 transition-transform">chevron_right</span>
                    </a>
                </div>
            </div>
        </section>

        <!-- Recent Classes Section -->
        <section class="col-span-12">
            <div class="bg-[#111726] p-6 rounded-2xl border border-white/10 shadow-xl">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="font-['Outfit'] font-bold text-lg text-white uppercase tracking-wider">Histórico de Aulas Recentes</h3>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-4">
                    @forelse($recentAttendances as $attendance)
                    <div class="flex items-start gap-4 p-4 rounded-xl bg-[#182234] border border-white/10 hover:border-cyan-500/40 transition-all">
                        <div class="bg-cyan-500/10 text-cyan-400 border border-cyan-500/20 p-3 rounded-xl flex flex-col items-center justify-center min-w-[64px]">
                            <span class="text-xs font-bold uppercase">{{ $attendance->date->translatedFormat('M') }}</span>
                            <span class="text-xl font-bold font-['Outfit']">{{ $attendance->date->day }}</span>
                        </div>
                        <div class="flex-1">
                            <p class="font-bold text-white text-sm">Treino de Jiu-Jitsu</p>
                            <p class="text-xs text-slate-400 mb-2">{{ isset($currentTenant) ? $currentTenant->name : 'Gestão Combate' }}</p>
                            <div class="flex items-center gap-1 text-[10px] font-bold text-emerald-400 uppercase">
                                <span class="material-symbols-outlined text-sm">check_circle</span>
                                Presença Confirmada
                            </div>
                        </div>
                    </div>
                    @empty
                    <div class="col-span-12 text-center py-8 text-slate-500 italic">
                        Nenhum treino registrado recentemente.
                    </div>
                    @endforelse
                </div>
            </div>
        </section>
    </div>
</div>
@endsection
