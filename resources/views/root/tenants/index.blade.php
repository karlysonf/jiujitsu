@extends('layouts.app')

@section('content')
<div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-8">
    <div>
        <h1 class="text-3xl font-bold text-slate-900 dark:text-slate-50 leading-tight">Gerenciamento de Academias</h1>
        <p class="text-sm text-slate-500 mt-1">Visualize e configure todos os clientes contratantes do sistema (SaaS).</p>
    </div>
    <a href="{{ route('root.tenants.create') }}" class="bg-primary text-white px-6 py-3 rounded-xl font-semibold flex items-center gap-2 hover:opacity-90 active:scale-95 transition-all text-sm shadow-md">
        <span class="material-symbols-outlined text-base">add_business</span>
        Nova Academia
    </a>
</div>

<div class="bg-white dark:bg-slate-900 rounded-2xl shadow-sm border border-slate-200 dark:border-slate-800 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead class="bg-slate-50 dark:bg-slate-950 border-b border-slate-200 dark:border-slate-800">
                <tr>
                    <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Academia</th>
                    <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Subdomínio</th>
                    <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Plano Ativo</th>
                    <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Uso do Limite</th>
                    <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Status</th>
                    <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Vencimento</th>
                    <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider text-right">Ações</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100 dark:divide-slate-800">
                @forelse($tenants as $tenant)
                <tr class="hover:bg-slate-50 dark:hover:bg-slate-800/40 transition-colors">
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <div class="h-10 w-10 rounded-lg border border-slate-200 dark:border-slate-800 bg-slate-100 dark:bg-slate-950 flex items-center justify-center overflow-hidden">
                                @if($tenant->logo)
                                    <img src="{{ Storage::disk('public')->url($tenant->logo) }}" alt="Logo" class="w-full h-full object-contain">
                                @else
                                    <span class="material-symbols-outlined text-slate-400">sports_kabaddi</span>
                                @endif
                            </div>
                            <div>
                                <span class="font-bold text-slate-900 dark:text-slate-100 block">{{ $tenant->name }}</span>
                                @if($tenant->domain)
                                    <span class="text-xs text-slate-400 block">{{ $tenant->domain }}</span>
                                @endif
                            </div>
                        </div>
                    </td>
                    <td class="px-6 py-4 text-slate-600 dark:text-slate-300 font-mono text-sm">
                        {{ $tenant->subdomain }}
                    </td>
                    <td class="px-6 py-4">
                        @php
                            $tiers = [
                                'bronze' => ['name' => 'Bronze', 'class' => 'bg-amber-100 text-amber-800 dark:bg-amber-900/30 dark:text-amber-300'],
                                'silver' => ['name' => 'Prata', 'class' => 'bg-slate-200 text-slate-800 dark:bg-slate-800/50 dark:text-slate-300'],
                                'gold' => ['name' => 'Ouro', 'class' => 'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-300']
                            ];
                            $tier = $tiers[$tenant->plan_tier] ?? ['name' => ucfirst($tenant->plan_tier), 'class' => 'bg-slate-100 text-slate-700'];
                        @endphp
                        <span class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-bold {{ $tier['class'] }}">
                            {{ $tier['name'] }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        @php
                            $activeCount = $tenant->getActiveUsersCount();
                            $limit = $tenant->max_users;
                            $percent = $limit ? min(100, ($activeCount / $limit) * 100) : 0;
                        @endphp
                        <div class="flex flex-col gap-1 min-w-[120px]">
                            <div class="flex justify-between text-xs font-semibold">
                                <span class="text-slate-700 dark:text-slate-300">{{ $activeCount }} de {{ $limit ?? '∞' }}</span>
                                @if($limit)
                                    <span class="text-slate-500">{{ round($percent) }}%</span>
                                @endif
                            </div>
                            @if($limit)
                                <div class="w-full bg-slate-100 dark:bg-slate-800 rounded-full h-2 overflow-hidden">
                                    <div class="h-full rounded-full {{ $percent >= 90 ? 'bg-red-500' : ($percent >= 70 ? 'bg-amber-500' : 'bg-primary') }}" style="width: {{ $percent }}%"></div>
                                </div>
                            @else
                                <div class="w-full bg-slate-100 dark:bg-slate-800 rounded-full h-2 overflow-hidden">
                                    <div class="bg-green-500 h-full rounded-full w-full"></div>
                                </div>
                            @endif
                        </div>
                    </td>
                    <td class="px-6 py-4">
                        @php
                            $statuses = [
                                'active' => ['name' => 'Ativo', 'class' => 'bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300'],
                                'suspended' => ['name' => 'Suspenso', 'class' => 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300'],
                                'trial' => ['name' => 'Período de Testes', 'class' => 'bg-blue-100 text-blue-800 dark:bg-blue-900/30 dark:text-blue-300']
                            ];
                            $status = $statuses[$tenant->status] ?? ['name' => ucfirst($tenant->status), 'class' => 'bg-slate-100 text-slate-700'];
                        @endphp
                        <span class="inline-flex px-2.5 py-0.5 rounded-full text-xs font-bold {{ $status['class'] }}">
                            {{ $status['name'] }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-slate-600 dark:text-slate-400 text-sm">
                        {{ $tenant->expires_at ? $tenant->expires_at->format('d/m/Y') : 'Sem expiração' }}
                    </td>
                    <td class="px-6 py-4 text-right">
                        <div class="flex gap-2 justify-end">
                            <a href="{{ route('root.tenants.edit', $tenant) }}" class="p-2 bg-slate-50 dark:bg-slate-850 text-slate-400 hover:text-amber-600 hover:bg-amber-50 dark:hover:bg-amber-950/40 rounded-lg transition-colors">
                                <span class="material-symbols-outlined text-sm">edit</span>
                            </a>
                            @if($tenant->subdomain !== 'ctdenyson')
                                <form action="{{ route('root.tenants.destroy', $tenant) }}" method="POST" class="inline" onsubmit="return confirm('Deseja excluir permanentemente esta academia e todos os seus dados?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="p-2 bg-slate-50 dark:bg-slate-850 text-slate-400 hover:text-rose-600 hover:bg-rose-50 dark:hover:bg-rose-950/40 rounded-lg transition-colors">
                                        <span class="material-symbols-outlined text-sm">delete</span>
                                    </button>
                                </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="px-6 py-12 text-center text-slate-400 italic">
                        Nenhuma academia cadastrada.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    
    @if($tenants->hasPages())
    <div class="px-6 py-4 border-t border-slate-100 dark:border-slate-800">
        {{ $tenants->links() }}
    </div>
    @endif
</div>
@endsection
