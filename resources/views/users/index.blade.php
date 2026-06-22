@extends('layouts.app')

@section('content')
<div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-8">
    <div>
        <h1 class="font-headline-lg text-slate-900">Gestão de Alunos</h1>
        <p class="text-slate-500">Visualize e gerencie todos os alunos ativos e inativos.</p>
    </div>
    @can('manage-users')
    <div class="flex gap-2">
        <a href="{{ route('users.import') }}" class="border border-slate-300 text-slate-700 px-6 py-3 rounded-xl font-label-bold flex items-center gap-2 hover:bg-slate-50 active:scale-95 transition-all">
            <span class="material-symbols-outlined">upload_file</span>
            Importar CSV
        </a>
        <a href="{{ route('users.create') }}" class="bg-primary text-white px-6 py-3 rounded-xl font-label-bold flex items-center gap-2 hover:opacity-90 active:scale-95 transition-all">
            <span class="material-symbols-outlined">person_add</span>
            Novo Aluno
        </a>
    </div>
    @endcan
</div>

<div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6 mb-8">
    <form action="{{ route('users.index') }}" method="GET" class="flex gap-2">
        <div class="relative flex-1">
            <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-outline">search</span>
            <input type="text" name="search" value="{{ request('search') }}" class="w-full pl-10 pr-4 py-3 border border-slate-200 rounded-xl text-sm focus:ring-2 focus:ring-on-tertiary-container focus:outline-none" placeholder="Buscar por nome, email ou faixa...">
        </div>
        <button type="submit" class="bg-slate-100 px-6 py-3 rounded-xl text-slate-700 font-label-bold hover:bg-slate-200 transition-colors">
            Buscar
        </button>
    </form>
</div>

<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-gutter">
    @forelse($users as $user)
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6 hover:shadow-md transition-all group">
            <div class="flex justify-between items-start mb-4">
                <div class="flex items-center gap-3">
                    <div class="h-12 w-12 rounded-full bg-slate-100 flex items-center justify-center font-bold text-slate-600 text-lg overflow-hidden">
                        @if($user->photo)
                            <img src="{{ Storage::disk('public')->url($user->photo) }}" alt="{{ $user->name }}" class="h-full w-full object-cover">
                        @else
                            {{ strtoupper(substr($user->name, 0, 2)) }}
                        @endif
                    </div>
                    <div>
                        <h3 class="font-label-bold text-slate-900">{{ $user->name }}</h3>
                        <p class="text-xs text-slate-500 truncate max-w-[150px]">{{ $user->email ?? 'Sem e-mail' }}</p>
                    </div>
                </div>
                @php
                    $beltColor = match(strtolower($user->belt)) {
                        'branca' => 'bg-white text-slate-900 border border-slate-300',
                        'cinza' => 'bg-slate-400 text-white',
                        'cinza/branca' => 'bg-slate-200 text-slate-700 border border-slate-400',
                        'cinza/preta' => 'bg-slate-600 text-white',
                        'amarela' => 'bg-yellow-400 text-yellow-900',
                        'amarela/branca' => 'bg-yellow-100 text-yellow-700 border border-yellow-400',
                        'amarela/preta' => 'bg-yellow-600 text-white',
                        'laranja' => 'bg-orange-400 text-orange-900',
                        'laranja/branca' => 'bg-orange-100 text-orange-700 border border-orange-400',
                        'laranja/preta' => 'bg-orange-600 text-white',
                        'verde' => 'bg-green-500 text-white',
                        'verde/branca' => 'bg-green-100 text-green-700 border border-green-400',
                        'verde/preta' => 'bg-green-700 text-white',
                        'azul' => 'bg-blue-600 text-white',
                        'roxa' => 'bg-purple-600 text-white',
                        'marrom' => 'bg-amber-900 text-white',
                        'preta' => 'bg-slate-900 text-white',
                        default => 'bg-slate-100 text-slate-600'
                    };
                @endphp
                <span class="px-2 py-1 {{ $beltColor }} rounded text-[10px] font-bold">{{ strtoupper($user->belt) }}</span>
            </div>

            <div class="grid grid-cols-2 gap-4 mb-6 bg-slate-50 p-4 rounded-lg border border-slate-100">
                <div>
                    <p class="text-[10px] text-slate-400 uppercase font-bold tracking-wider mb-1">Status</p>
                    <span class="px-2 py-0.5 {{ $user->status == 'active' ? 'bg-emerald-100 text-emerald-700' : 'bg-rose-100 text-rose-700' }} rounded-full text-[10px] font-bold">
                        {{ $user->status == 'active' ? 'ATIVO' : 'INATIVO' }}
                    </span>
                </div>
                <div>
                    <p class="text-[10px] text-slate-400 uppercase font-bold tracking-wider mb-1">Início</p>
                    <p class="text-sm font-label-bold text-slate-700">{{ $user->start_date?->format('M/Y') ?? 'N/A' }}</p>
                </div>
            </div>

            <div class="flex justify-between items-center pt-4 border-t border-slate-100">
                <div class="text-[10px] text-slate-400 font-medium">
                    ID: #{{ str_pad($user->id, 4, '0', STR_PAD_LEFT) }}
                </div>
                <div class="flex gap-2">
                    <a href="{{ route('users.show', $user) }}" class="p-2 bg-slate-50 text-slate-400 hover:text-blue-600 hover:bg-blue-50 rounded-lg transition-colors">
                        <span class="material-symbols-outlined text-sm">visibility</span>
                    </a>
                    <a href="{{ route('users.edit', $user) }}" class="p-2 bg-slate-50 text-slate-400 hover:text-amber-600 hover:bg-amber-50 rounded-lg transition-colors">
                        <span class="material-symbols-outlined text-sm">edit</span>
                    </a>
                    @if(auth()->user()->hasAnyRole(['root', 'admin']))
                    <form action="{{ route('users.destroy', $user) }}" method="POST" class="inline" onsubmit="return confirm('Excluir este aluno?')">
                        @csrf @method('DELETE')
                        <button type="submit" class="p-2 bg-slate-50 text-slate-400 hover:text-rose-600 hover:bg-rose-50 rounded-lg transition-colors">
                            <span class="material-symbols-outlined text-sm">trash</span>
                        </button>
                    </form>
                    @endif
                </div>
            </div>
        </div>
    @empty
        <div class="col-span-full bg-white rounded-xl border border-dashed border-slate-300 p-12 text-center">
            <span class="material-symbols-outlined text-slate-300 text-6xl mb-4">person_search</span>
            <h3 class="text-lg font-label-bold text-slate-900">Nenhum aluno encontrado</h3>
            <p class="text-slate-500 mb-6">Tente ajustar sua busca ou cadastre um novo aluno.</p>
            <a href="{{ route('users.create') }}" class="inline-flex items-center gap-2 bg-primary text-white px-6 py-3 rounded-xl font-label-bold hover:opacity-90">
                <span class="material-symbols-outlined">person_add</span>
                Novo Aluno
            </a>
        </div>
    @endforelse
</div>

<div class="mt-8">
    {{ $users->links() }}
</div>
@endsection
