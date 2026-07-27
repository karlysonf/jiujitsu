@extends('layouts.app')

@section('content')
@php
    $tenant = \App\Models\Tenant::current();
    $reachedLimit = $tenant ? $tenant->hasReachedUserLimit() : false;
@endphp

<div class="max-w-[1600px] mx-auto">
    <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-6">
        <div>
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-rose-500/10 border border-rose-500/20 text-rose-400 text-xs font-bold uppercase tracking-wider mb-2">
                <span class="w-2 h-2 rounded-full bg-rose-500 animate-pulse"></span>
                Atletas & Cadastro
            </div>
            <h1 class="font-['Outfit'] font-black text-2xl md:text-4xl text-white tracking-tight">Gestão de Alunos</h1>
            <p class="text-slate-400 text-xs md:text-sm mt-0.5">Visualize e gerencie todos os alunos ativos e inativos no tatame.</p>
        </div>
        @can('manage-users')
        <div class="flex gap-2">
            <a href="{{ route('users.import') }}" class="border border-white/10 bg-[#182234] text-slate-200 px-5 py-2.5 rounded-xl font-['Outfit'] font-bold text-xs flex items-center gap-2 hover:bg-white/10 active:scale-95 transition-all @if($reachedLimit) opacity-50 cursor-not-allowed pointer-events-none @endif">
                <span class="material-symbols-outlined text-sm">upload_file</span>
                Importar CSV
            </a>
            <a href="{{ route('users.create') }}" class="bg-gradient-to-r from-rose-600 to-rose-700 text-white px-5 py-2.5 rounded-xl font-['Outfit'] font-bold text-xs flex items-center gap-2 hover:shadow-lg hover:shadow-rose-600/30 active:scale-95 transition-all @if($reachedLimit) opacity-50 cursor-not-allowed pointer-events-none @endif">
                <span class="material-symbols-outlined text-sm">person_add</span>
                Novo Aluno
            </a>
        </div>
        @endcan
    </div>

    @if($reachedLimit)
    <div class="mb-6 p-4 rounded-2xl bg-rose-500/10 border border-rose-500/30 text-rose-300 flex items-start gap-3">
        <span class="material-symbols-outlined text-rose-400 mt-0.5">warning</span>
        <div>
            <p class="text-sm font-bold">Limite de Cadastros Atingido</p>
            <p class="text-xs mt-1 leading-relaxed">Sua academia atingiu o limite máximo de {{ $tenant->max_users }} cadastros ativos no plano atual. Para cadastrar novos alunos, você precisará inativar algum cadastro existente ou solicitar o upgrade de plano.</p>
        </div>
    </div>
    @endif

    <div class="bg-[#111726] rounded-2xl border border-white/10 shadow-xl p-5 mb-6">
        <form action="{{ route('users.index') }}" method="GET" class="flex gap-3">
            <div class="relative flex-1">
                <span class="material-symbols-outlined absolute left-3.5 top-1/2 -translate-y-1/2 text-slate-500 text-sm">search</span>
                <input type="text" name="search" value="{{ request('search') }}" class="w-full pl-10 pr-4 py-2.5 bg-[#090d16] border border-white/10 rounded-xl text-sm text-white placeholder-slate-500 focus:outline-none focus:border-rose-500 transition-all" placeholder="Buscar por nome, email ou faixa...">
            </div>
            <button type="submit" class="bg-[#182234] border border-white/10 px-6 py-2.5 rounded-xl text-white font-['Outfit'] font-bold text-xs hover:bg-white/10 transition-colors">
                Buscar
            </button>
        </form>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($users as $user)
            <div class="bg-[#111726] rounded-2xl border border-white/10 shadow-xl p-6 hover:border-rose-500/40 transition-all group">
                <div class="flex justify-between items-start mb-4">
                    <div class="flex items-center gap-3">
                        <div class="h-12 w-12 rounded-xl bg-[#182234] border border-white/10 flex items-center justify-center font-bold text-rose-400 text-lg overflow-hidden">
                            @if($user->photo)
                                <img src="{{ Storage::disk('public')->url($user->photo) }}" alt="{{ $user->name }}" class="h-full w-full object-cover">
                            @else
                                {{ strtoupper(substr($user->name, 0, 2)) }}
                            @endif
                        </div>
                        <div>
                            <h3 class="font-['Outfit'] font-bold text-white text-base leading-snug">{{ $user->name }}</h3>
                            <p class="text-xs text-slate-400 truncate max-w-[150px]">{{ $user->email ?? 'Sem e-mail' }}</p>
                        </div>
                    </div>
                    @php
                        $beltColor = match(strtolower($user->belt)) {
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
                    <span class="px-2.5 py-1 {{ $beltColor }} rounded-md text-[10px] font-bold tracking-wider uppercase">FAIXA {{ strtoupper($user->belt) }}</span>
                </div>

                <div class="grid grid-cols-2 gap-4 mb-4 bg-[#182234] p-3.5 rounded-xl border border-white/5">
                    <div>
                        <p class="text-[10px] text-slate-400 uppercase font-bold tracking-wider mb-1">Status</p>
                        <span class="px-2.5 py-0.5 {{ $user->status == 'active' ? 'bg-emerald-500/10 border border-emerald-500/30 text-emerald-400' : 'bg-rose-500/10 border border-rose-500/30 text-rose-400' }} rounded-full text-[10px] font-bold">
                            {{ $user->status == 'active' ? 'ATIVO' : 'INATIVO' }}
                        </span>
                    </div>
                    <div>
                        <p class="text-[10px] text-slate-400 uppercase font-bold tracking-wider mb-1">Início</p>
                        <p class="text-xs font-bold text-slate-200">{{ $user->start_date?->format('M/Y') ?? 'N/A' }}</p>
                    </div>
                </div>

                <div class="flex justify-between items-center pt-3 border-t border-white/5">
                    <div class="text-[10px] text-slate-500 font-semibold">
                        ID: #{{ str_pad($user->id, 4, '0', STR_PAD_LEFT) }}
                    </div>
                    <div class="flex gap-2">
                        <a href="{{ route('users.show', $user) }}" class="p-2 bg-cyan-500/10 text-cyan-400 hover:bg-cyan-500/20 rounded-xl transition-colors" title="Visualizar Aluno">
                            <span class="material-symbols-outlined text-sm">visibility</span>
                        </a>
                        <a href="{{ route('users.edit', $user) }}" class="p-2 bg-amber-500/10 text-amber-400 hover:bg-amber-500/20 rounded-xl transition-colors" title="Editar Aluno">
                            <span class="material-symbols-outlined text-sm">edit</span>
                        </a>
                        @if(auth()->user()->hasAnyRole(['root', 'admin']))
                        <form action="{{ route('users.destroy', $user) }}" method="POST" class="inline" onsubmit="return confirm('Excluir este aluno?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="p-2 bg-rose-500/10 text-rose-400 hover:bg-rose-500/20 rounded-xl transition-colors" title="Excluir Aluno">
                                <span class="material-symbols-outlined text-sm">delete</span>
                            </button>
                        </form>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="col-span-full bg-[#111726] rounded-2xl border border-dashed border-white/10 p-12 text-center shadow-xl">
                <span class="material-symbols-outlined text-slate-600 text-6xl mb-4">person_search</span>
                <h3 class="text-lg font-['Outfit'] font-bold text-white">Nenhum aluno encontrado</h3>
                <p class="text-xs text-slate-400 mb-6">Tente ajustar sua busca ou cadastre um novo aluno.</p>
                <a href="{{ route('users.create') }}" class="inline-flex items-center gap-2 bg-gradient-to-r from-rose-600 to-rose-700 text-white px-6 py-3 rounded-xl font-['Outfit'] font-bold text-sm hover:shadow-lg hover:shadow-rose-600/30 @if($reachedLimit) opacity-50 cursor-not-allowed pointer-events-none @endif">
                    <span class="material-symbols-outlined">person_add</span>
                    Novo Aluno
                </a>
            </div>
        @endforelse
    </div>

    <div class="mt-8">
        {{ $users->links() }}
    </div>
</div>
@endsection
