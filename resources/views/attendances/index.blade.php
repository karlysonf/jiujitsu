@extends('layouts.app')

@section('content')
<div class="max-w-[1600px] mx-auto">
    <!-- Header Section -->
    <div class="mb-10">
        <h1 class="font-headline-lg text-headline-lg text-primary mb-2">Controle de Presença</h1>
        <p class="font-body-lg text-body-lg text-on-surface-variant">Gerencie a frequência dos alunos nas aulas de hoje ({{ \Carbon\Carbon::parse($date)->format('d/m/Y') }}).</p>
    </div>

    <!-- Summary Bento Grid Section -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-gutter mb-10">
        <div class="bg-white border border-outline-variant p-md rounded-xl shadow-sm flex flex-col gap-2">
            <span class="text-label-sm font-label-sm text-on-surface-variant uppercase tracking-wider">Total de Alunos</span>
            <div class="flex items-baseline gap-2">
                <span class="text-display-xl font-display-xl text-primary">{{ $users->count() }}</span>
                <span class="text-label-bold font-label-bold text-on-surface-variant">Ativos hoje</span>
            </div>
            <div class="mt-4 h-1 w-full bg-surface-container rounded-full overflow-hidden">
                <div class="h-full bg-primary" style="width: 100%"></div>
            </div>
        </div>
        <div class="bg-white border border-outline-variant p-md rounded-xl shadow-sm flex flex-col gap-2">
            <span class="text-label-sm font-label-sm text-on-surface-variant uppercase tracking-wider">Presenças Confirmadas</span>
            <div class="flex items-baseline gap-2">
                <span class="text-display-xl font-display-xl text-tertiary-container">{{ $attendances->count() }}</span>
                <span class="text-label-bold font-label-bold text-tertiary-container">Alunos</span>
            </div>
            <div class="mt-4 h-1 w-full bg-surface-container rounded-full overflow-hidden">
                <div class="h-full bg-tertiary-container" style="width: {{ $users->count() > 0 ? ($attendances->count() / $users->count()) * 100 : 0 }}%"></div>
            </div>
        </div>
        <div class="bg-white border border-outline-variant p-md rounded-xl shadow-sm flex flex-col gap-2 relative overflow-hidden group">
            <div class="relative z-10">
                <span class="text-label-sm font-label-sm text-on-surface-variant uppercase tracking-wider">Taxa de Ocupação</span>
                <div class="flex items-baseline gap-2">
                    <span class="text-display-xl font-display-xl text-secondary">{{ $users->count() > 0 ? round(($attendances->count() / $users->count()) * 100) : 0 }}%</span>
                    <span class="text-label-bold font-label-bold text-on-surface-variant">Capacidade</span>
                </div>
            </div>
            <div class="absolute -right-4 -bottom-4 opacity-5 group-hover:opacity-10 transition-opacity">
                <span class="material-symbols-outlined text-[120px]">analytics</span>
            </div>
        </div>
    </div>

    <!-- Filters Bar -->
    <div class="bg-white border border-outline-variant p-4 rounded-xl mb-6 flex flex-wrap items-center justify-between gap-4">
        <div class="flex-1 min-w-[300px] relative">
            <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-outline">search</span>
            <input id="studentSearch" class="w-full pl-10 pr-4 py-2 bg-surface-container-low border border-outline-variant rounded focus:border-tertiary-container focus:ring-2 focus:ring-tertiary-container/20 outline-none transition-all font-body-md text-body-md" placeholder="Buscar aluno pelo nome..." type="text"/>
        </div>
        <div class="flex items-center gap-2">
            <span class="text-label-bold font-label-bold text-on-surface mr-2">Status:</span>
            <div class="inline-flex rounded-lg border border-outline-variant p-1 bg-surface-container-low" id="statusFilters">
                <button data-status="all" class="filter-btn px-4 py-1.5 rounded bg-white shadow-sm text-label-bold font-label-bold text-primary">Todos</button>
                <button data-status="present" class="filter-btn px-4 py-1.5 rounded text-label-bold font-label-bold text-on-surface-variant hover:bg-white/50 transition-colors">Presente</button>
                <button data-status="absent" class="filter-btn px-4 py-1.5 rounded text-label-bold font-label-bold text-on-surface-variant hover:bg-white/50 transition-colors">Ausente</button>
            </div>
        </div>
    </div>

    <!-- Students List -->
    <div class="bg-white border border-outline-variant rounded-xl overflow-hidden shadow-sm">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-surface-container-low border-b border-outline-variant">
                    <th class="px-6 py-4 font-label-bold text-label-bold text-on-surface-variant uppercase tracking-wider">Aluno</th>
                    <th class="px-6 py-4 font-label-bold text-label-bold text-on-surface-variant uppercase tracking-wider">Graduação</th>
                    <th class="px-6 py-4 font-label-bold text-label-bold text-on-surface-variant uppercase tracking-wider">Status</th>
                    <th class="px-6 py-4 font-label-bold text-label-bold text-on-surface-variant uppercase tracking-wider text-right">Ações</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-outline-variant" id="attendanceTableBody">
                @foreach($users as $user)
                    @php
                        $hasAttended = $attendances->where('user_id', $user->id)->isNotEmpty();
                        $beltColor = match(strtolower($user->faixa)) {
                            'branca' => 'bg-slate-200',
                            'cinza' => 'bg-slate-400',
                            'cinza/branca' => 'bg-slate-300',
                            'cinza/preta' => 'bg-slate-600',
                            'amarela' => 'bg-yellow-400',
                            'amarela/branca' => 'bg-yellow-200',
                            'amarela/preta' => 'bg-yellow-600',
                            'laranja' => 'bg-orange-400',
                            'laranja/branca' => 'bg-orange-200',
                            'laranja/preta' => 'bg-orange-600',
                            'verde' => 'bg-green-500',
                            'verde/branca' => 'bg-green-200',
                            'verde/preta' => 'bg-green-700',
                            'azul' => 'bg-blue-600',
                            'roxa' => 'bg-purple-600',
                            'marrom' => 'bg-amber-800',
                            'preta' => 'bg-slate-900',
                            default => 'bg-slate-400'
                        };
                    @endphp
                    <tr class="hover:bg-slate-50 transition-colors group attendance-row" data-name="{{ strtolower($user->name) }}" data-present="{{ $hasAttended ? 'true' : 'false' }}">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 rounded-full bg-surface-container flex items-center justify-center text-outline overflow-hidden">
                                    @if($user->avatar_url)
                                        <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}" class="w-full h-full object-cover">
                                    @else
                                        <span class="material-symbols-outlined text-3xl">person</span>
                                    @endif
                                </div>
                                <div>
                                    <div class="font-label-bold text-label-bold text-primary">{{ $user->name }}</div>
                                    <div class="text-label-sm font-label-sm text-on-surface-variant">Ult. aula: {{ $user->attendances->first()?->date?->diffForHumans() ?? 'Nenhuma registrada' }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-2">
                                <div class="h-2 w-16 bg-surface-container rounded-full overflow-hidden">
                                    <div class="h-full {{ $beltColor }}" style="width: 100%"></div>
                                </div>
                                <span class="text-label-sm font-label-sm text-on-surface-variant">Faixa {{ $user->faixa }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            @if($hasAttended)
                                <span class="px-3 py-1 rounded-full bg-secondary-container text-on-secondary-container text-label-sm font-label-bold">Confirmado</span>
                            @else
                                <span class="px-3 py-1 rounded-full bg-error-container text-on-error-container text-label-sm font-label-bold">Ausente</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex items-center justify-end gap-3 {{ $hasAttended ? '' : 'opacity-0 group-hover:opacity-100' }} transition-opacity">
                                @if($hasAttended)
                                    <form action="{{ route('attendances.destroy') }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <input type="hidden" name="user_id" value="{{ $user->id }}">
                                        <input type="hidden" name="date" value="{{ $date }}">
                                        <button type="submit" class="border border-error text-error px-4 py-2 rounded font-label-bold text-label-bold hover:bg-error-container/20 transition-colors active:opacity-80">
                                            Retirar Presença
                                        </button>
                                    </form>
                                @else
                                    <form action="{{ route('attendances.store') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="user_id" value="{{ $user->id }}">
                                        <input type="hidden" name="date" value="{{ $date }}">
                                        <button type="submit" class="bg-primary text-on-primary px-4 py-2 rounded font-label-bold text-label-bold flex items-center gap-2 hover:scale-95 transition-transform active:opacity-80">
                                            <span class="material-symbols-outlined text-[18px]">check</span>
                                            Confirmar Presença
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="px-6 py-4 bg-surface-container-low flex items-center justify-between">
            <span class="text-label-sm font-label-sm text-on-surface-variant" id="showingText">Mostrando {{ $users->count() }} de {{ $users->count() }} alunos</span>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('studentSearch');
        const filterButtons = document.querySelectorAll('.filter-btn');
        const rows = document.querySelectorAll('.attendance-row');
        const showingText = document.getElementById('showingText');

        let currentStatus = 'all';
        let currentSearch = '';

        function updateFilters() {
            let visibleCount = 0;
            rows.forEach(row => {
                const name = row.getAttribute('data-name');
                const isPresent = row.getAttribute('data-present') === 'true';
                
                const matchesSearch = name.includes(currentSearch.toLowerCase());
                const matchesStatus = currentStatus === 'all' || 
                                    (currentStatus === 'present' && isPresent) || 
                                    (currentStatus === 'absent' && !isPresent);

                if (matchesSearch && matchesStatus) {
                    row.style.display = '';
                    visibleCount++;
                } else {
                    row.style.display = 'none';
                }
            });
            showingText.textContent = `Mostrando ${visibleCount} de ${rows.length} alunos`;
        }

        searchInput.addEventListener('input', (e) => {
            currentSearch = e.target.value;
            updateFilters();
        });

        filterButtons.forEach(btn => {
            btn.addEventListener('click', () => {
                filterButtons.forEach(b => {
                    b.classList.remove('bg-white', 'shadow-sm', 'text-primary');
                    b.classList.add('text-on-surface-variant');
                });
                btn.classList.add('bg-white', 'shadow-sm', 'text-primary');
                btn.classList.remove('text-on-surface-variant');
                
                currentStatus = btn.getAttribute('data-status');
                updateFilters();
            });
        });
    });
</script>
@endsection
