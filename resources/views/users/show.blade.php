@extends('layouts.app')

@section('content')
<div class="max-w-[1400px] mx-auto pb-12">
    <!-- Header Back Navigation -->
    <div class="mb-6 flex items-center justify-between">
        <div class="flex items-center gap-3">
            <a href="{{ route('users.index') }}" class="flex items-center justify-center p-2 rounded-xl bg-[#182234] border border-white/10 text-slate-300 hover:text-white hover:bg-white/10 transition-colors">
                <span class="material-symbols-outlined text-lg">arrow_back</span>
            </a>
            <div>
                <h1 class="font-['Outfit'] font-black text-2xl md:text-3xl text-white">Perfil do Aluno</h1>
                <p class="text-xs text-slate-400">Informações detalhadas, histórico financeiro e presenças.</p>
            </div>
        </div>
        <div class="flex gap-2">
            <a href="{{ route('users.edit', $user) }}" class="bg-gradient-to-r from-rose-600 to-rose-700 text-white px-4 py-2 rounded-xl font-['Outfit'] font-bold text-xs flex items-center gap-2 hover:shadow-lg hover:shadow-rose-600/30 transition-all">
                <span class="material-symbols-outlined text-sm">edit</span>
                Editar Aluno
            </a>
        </div>
    </div>

    <!-- Profile Header Card -->
    <div class="bg-[#111726] border border-white/10 rounded-2xl p-6 shadow-xl mb-6 flex flex-col md:flex-row items-center justify-between gap-6">
        <div class="flex flex-col md:flex-row items-center gap-6 text-center md:text-left">
            <div class="w-24 h-24 rounded-full overflow-hidden border-4 border-[#182234] ring-2 ring-rose-500 shadow-xl shrink-0">
                @if($user->photo)
                    <img src="{{ Storage::disk('public')->url($user->photo) }}" alt="{{ $user->name }}" class="w-full h-full object-cover">
                @else
                    <div class="w-full h-full bg-[#182234] flex items-center justify-center text-rose-400 font-bold text-2xl">
                        {{ strtoupper(substr($user->name, 0, 2)) }}
                    </div>
                @endif
            </div>
            <div>
                <h2 class="font-['Outfit'] font-black text-2xl text-white mb-1">{{ $user->name }}</h2>
                <p class="text-xs text-slate-400 mb-3">{{ $user->email ?? 'Sem e-mail cadastrado' }} • {{ $user->phone ?? 'Sem telefone' }}</p>
                <div class="flex flex-wrap gap-2 justify-center md:justify-start">
                    @php
                        $belt = $user->faixa ?? $user->belt ?? 'Branca';
                        $grau = (int) ($user->grau ?? 0);
                        $beltColor = match(strtolower($belt)) {
                            'branca' => 'bg-slate-200 text-slate-900',
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
                    <span class="px-3 py-1 {{ $beltColor }} rounded-md text-xs font-bold uppercase tracking-wider shadow-sm flex items-center gap-1.5">
                        <span>FAIXA {{ strtoupper($belt) }}</span>
                        <span class="opacity-75">•</span>
                        <span>{{ $grau }} {{ $grau == 1 ? 'GRAU' : 'GRAUS' }}</span>
                    </span>
                    <span class="px-3 py-1 {{ $user->status == 'active' ? 'bg-emerald-500/10 border border-emerald-500/30 text-emerald-400' : 'bg-rose-500/10 border border-rose-500/30 text-rose-400' }} rounded-full text-xs font-bold uppercase">
                        {{ $user->status == 'active' ? 'Cadastrado Ativo' : 'Inativo' }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Grid Content -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left: Datos pessoais -->
        <div class="space-y-6">
            <div class="bg-[#111726] border border-white/10 rounded-2xl p-6 shadow-xl">
                <h3 class="font-['Outfit'] font-bold text-lg text-white mb-4 border-b border-white/10 pb-2">Informações Cadastrais</h3>
                <ul class="space-y-3 text-xs">
                    <li class="flex justify-between border-b border-white/5 pb-2">
                        <span class="text-slate-400">CPF:</span>
                        <span class="font-semibold text-white">{{ $user->cpf ?? 'Não informado' }}</span>
                    </li>
                    <li class="flex justify-between border-b border-white/5 pb-2">
                        <span class="text-slate-400">Graduação:</span>
                        <span class="font-semibold text-cyan-400 uppercase">Faixa {{ $belt }} ({{ $grau }} {{ $grau == 1 ? 'Grau' : 'Graus' }})</span>
                    </li>
                    <li class="flex justify-between border-b border-white/5 pb-2">
                        <span class="text-slate-400">Data de Nascimento:</span>
                        <span class="font-semibold text-white">{{ $user->birth_date ? $user->birth_date->format('d/m/Y') : ($user->data_nascimento ? $user->data_nascimento->format('d/m/Y') : 'Não informado') }}</span>
                    </li>
                    <li class="flex justify-between border-b border-white/5 pb-2">
                        <span class="text-slate-400">Data de Início:</span>
                        <span class="font-semibold text-white">{{ $user->start_date ? $user->start_date->format('d/m/Y') : 'Não informado' }}</span>
                    </li>
                    <li class="flex justify-between border-b border-white/5 pb-2">
                        <span class="text-slate-400">Plano Atual:</span>
                        <span class="font-semibold text-cyan-400">{{ $user->plan->name ?? 'Sem Plano' }}</span>
                    </li>
                    <li class="flex justify-between">
                        <span class="text-slate-400">Endereço:</span>
                        <span class="font-semibold text-white truncate max-w-[150px]">{{ $user->address ?? $user->endereco ?? 'Não informado' }}</span>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Right: Pagamentos e Frequência -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Pagamentos -->
            <div class="bg-[#111726] border border-white/10 rounded-2xl p-6 shadow-xl">
                <h3 class="font-['Outfit'] font-bold text-lg text-white mb-4 border-b border-white/10 pb-2 flex items-center justify-between">
                    Histórico Financeiro
                    <span class="material-symbols-outlined text-rose-400">payments</span>
                </h3>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-white/10 text-slate-400 text-xs font-bold uppercase tracking-wider bg-[#0d1320]/60">
                                <th class="py-3 px-4">Referência</th>
                                <th class="py-3 px-4">Vencimento</th>
                                <th class="py-3 px-4">Valor</th>
                                <th class="py-3 px-4">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/5">
                            @forelse($user->payments->sortByDesc('due_date') as $payment)
                            <tr class="hover:bg-white/5 transition-colors">
                                <td class="py-3 px-4 text-xs font-semibold text-white">{{ $payment->reference_month ?? $payment->due_date->format('m/Y') }}</td>
                                <td class="py-3 px-4 text-xs text-slate-300">{{ $payment->due_date->format('d/m/Y') }}</td>
                                <td class="py-3 px-4 font-['Outfit'] font-bold text-white text-sm">R$ {{ number_format($payment->amount, 2, ',', '.') }}</td>
                                <td class="py-3 px-4">
                                    @if($payment->status === 'paid')
                                        <span class="px-2.5 py-0.5 bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 rounded-full text-[10px] font-bold uppercase">PAGO</span>
                                    @elseif($payment->status === 'late')
                                        <span class="px-2.5 py-0.5 bg-rose-500/10 border border-rose-500/30 text-rose-400 rounded-full text-[10px] font-bold uppercase">ATRASADO</span>
                                    @else
                                        <span class="px-2.5 py-0.5 bg-amber-500/10 border border-amber-500/30 text-amber-400 rounded-full text-[10px] font-bold uppercase">PENDENTE</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="py-8 text-center text-slate-500 italic text-xs">Nenhum histórico de pagamento registrado.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Frequência -->
            <div class="bg-[#111726] border border-white/10 rounded-2xl p-6 shadow-xl">
                <h3 class="font-['Outfit'] font-bold text-lg text-white mb-4 border-b border-white/10 pb-2 flex items-center justify-between">
                    Presenças Recentes no Tatame
                    <span class="material-symbols-outlined text-cyan-400">how_to_reg</span>
                </h3>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                    @forelse($user->attendances->sortByDesc('date')->take(8) as $att)
                    <div class="bg-[#182234] border border-white/10 p-3 rounded-xl text-center">
                        <span class="material-symbols-outlined text-emerald-400 text-lg mb-1">check_circle</span>
                        <p class="text-xs font-bold text-white">{{ $att->date->format('d/m/Y') }}</p>
                        <p class="text-[10px] text-slate-400">{{ $att->date->translatedFormat('l') }}</p>
                    </div>
                    @empty
                    <div class="col-span-full py-6 text-center text-slate-500 italic text-xs">Nenhuma presença registrada.</div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
