@extends('layouts.app')

@section('content')
<!-- Page Canvas -->
<div class="p-gutter max-w-[1600px] w-full mx-auto">
    <!-- Header Section -->
    <div class="mb-lg flex justify-between items-end flex-wrap gap-md no-print">
        <div>
            <div class="flex items-center gap-3 mb-2">
                <a href="{{ route('reports.index') }}" class="flex items-center justify-center p-2 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800 text-slate-500 transition-colors">
                    <span class="material-symbols-outlined text-slate-700 dark:text-slate-350">arrow_back</span>
                </a>
                <h1 class="font-headline-lg text-primary text-headline-lg">Relatório de Frequência e Presença</h1>
            </div>
            <p class="font-body-md text-on-surface-variant max-w-2xl text-body-md">
                Acompanhe o engajamento e a assiduidade dos alunos no tatame. Analise a média de presenças, identifique alunos inativos ou de destaque e trace metas de retenção.
            </p>
        </div>
        <div class="flex gap-3">
            <button onclick="window.print()" class="flex items-center gap-2 bg-white border border-slate-200 px-4 py-2 rounded-lg font-label-bold text-slate-700 hover:bg-slate-50 transition-all shadow-sm">
                <span class="material-symbols-outlined text-[20px]">print</span>
                Imprimir
            </button>
        </div>
    </div>

    <!-- Period Filters Form -->
    <div class="bg-white border border-slate-200 rounded-xl p-gutter mb-lg shadow-sm no-print">
        <form action="{{ route('reports.attendance') }}" method="GET" class="flex flex-wrap items-end gap-md">
            <div class="flex-1 min-w-[200px]">
                <label class="block text-label-sm font-label-sm text-outline-variant mb-2 ml-1">Data Inicial</label>
                <input type="date" name="start_date" value="{{ $startDate }}" class="w-full h-12 px-4 rounded-lg border border-slate-200 bg-surface-container-lowest focus:ring-2 focus:ring-on-tertiary-container focus:border-on-tertiary-container transition-all font-body-md text-body-md text-slate-800" required>
            </div>
            <div class="flex-1 min-w-[200px]">
                <label class="block text-label-sm font-label-sm text-outline-variant mb-2 ml-1">Data Final</label>
                <input type="date" name="end_date" value="{{ $endDate }}" class="w-full h-12 px-4 rounded-lg border border-slate-200 bg-surface-container-lowest focus:ring-2 focus:ring-on-tertiary-container focus:border-on-tertiary-container transition-all font-body-md text-body-md text-slate-800" required>
            </div>
            <button type="submit" class="h-12 px-6 bg-primary text-white rounded-lg font-label-bold flex items-center justify-center gap-2 hover:bg-slate-800 transition-all shadow-sm">
                <span class="material-symbols-outlined">filter_alt</span>
                Filtrar Período
            </button>
        </form>
    </div>

    <!-- Print Header (Hidden on screen) -->
    <div class="hidden print:block mb-lg border-b pb-4">
        <h1 class="text-2xl font-bold text-slate-900">{{ isset($currentTenant) ? $currentTenant->name : 'Gestão Combate' }}</h1>
        <h2 class="text-xl font-semibold text-slate-700 mt-1">Relatório de Presença e Frequência dos Alunos</h2>
        <p class="text-sm text-slate-500 mt-2">
            Período: <strong>{{ \Carbon\Carbon::parse($startDate)->format('d/m/Y') }}</strong> até <strong>{{ \Carbon\Carbon::parse($endDate)->format('d/m/Y') }}</strong>
        </p>
    </div>

    <!-- Summary Bento Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-gutter mb-lg">
        <!-- Total Presences -->
        <div class="bg-white border border-slate-200 p-gutter rounded-xl shadow-sm group hover:border-on-tertiary-container transition-all flex flex-col justify-between">
            <div class="flex justify-between items-start mb-4">
                <div class="p-3 bg-secondary-container/20 rounded-lg text-on-secondary-container">
                    <span class="material-symbols-outlined">how_to_reg</span>
                </div>
                <span class="text-xs font-label-bold text-outline bg-slate-100 px-2 py-1 rounded">No período</span>
            </div>
            <div>
                <p class="text-on-surface-variant font-label-sm uppercase tracking-wider text-label-sm">Total de Presenças</p>
                <h3 class="font-display-xl text-primary mt-1 text-display-xl">{{ $totalPresences }}</h3>
            </div>
        </div>

        <!-- Average Daily -->
        <div class="bg-white border border-slate-200 p-gutter rounded-xl shadow-sm group hover:border-on-tertiary-container transition-all flex flex-col justify-between">
            <div class="flex justify-between items-start mb-4">
                <div class="p-3 bg-on-tertiary-container/10 rounded-lg text-on-tertiary-container">
                    <span class="material-symbols-outlined">query_stats</span>
                </div>
                <span class="text-xs font-label-bold text-outline bg-slate-100 px-2 py-1 rounded">Média Diária</span>
            </div>
            <div>
                <p class="text-on-surface-variant font-label-sm uppercase tracking-wider text-label-sm">Presenças por Dia</p>
                <h3 class="font-display-xl text-primary mt-1 text-display-xl">{{ $avgPresencesPerDay }}</h3>
            </div>
        </div>

        <!-- Peak Day -->
        <div class="bg-white border border-slate-200 p-gutter rounded-xl shadow-sm group hover:border-on-tertiary-container transition-all flex flex-col justify-between">
            <div class="flex justify-between items-start mb-4">
                <div class="p-3 bg-error/10 rounded-lg text-error">
                    <span class="material-symbols-outlined">trending_up</span>
                </div>
                <span class="text-xs font-label-bold text-error bg-error/5 px-2 py-1 rounded">Pico: {{ $peakDayCount }}</span>
            </div>
            <div>
                <p class="text-on-surface-variant font-label-sm uppercase tracking-wider text-label-sm">Dia de Pico</p>
                <h3 class="font-display-lg text-primary mt-1 text-[22px] font-bold truncate" title="{{ $peakDay }}">{{ $peakDay }}</h3>
            </div>
        </div>

        <!-- Most Active Student -->
        <div class="bg-white border border-slate-200 p-gutter rounded-xl shadow-sm group hover:border-on-tertiary-container transition-all flex flex-col justify-between">
            <div class="flex justify-between items-start mb-4">
                <div class="p-3 bg-emerald-50 text-emerald-600 rounded-lg">
                    <span class="material-symbols-outlined">emoji_events</span>
                </div>
                <span class="text-xs font-label-bold text-emerald-600 bg-emerald-50 px-2 py-1 rounded">{{ $mostFrequentCount }} aulas</span>
            </div>
            <div>
                <p class="text-on-surface-variant font-label-sm uppercase tracking-wider text-label-sm">Mais Assíduo</p>
                <h3 class="font-display-lg text-primary mt-1 text-[22px] font-bold truncate" title="{{ $mostFrequentUser }}">{{ $mostFrequentUser }}</h3>
            </div>
        </div>
    </div>

    <!-- Filters & Search Bar (Screen only) -->
    <div class="bg-white border border-slate-200 rounded-xl p-md mb-md flex flex-wrap items-center justify-between gap-gutter no-print">
        <!-- Live Name Search -->
        <div class="flex-1 min-w-[300px] relative">
            <span class="material-symbols-outlined absolute left-4 top-1/2 -translate-y-1/2 text-slate-400">search</span>
            <input id="name-search" oninput="filterAttendanceTable()" class="w-full border-slate-200 bg-slate-50/50 rounded-lg pl-12 pr-4 py-3 focus:border-on-tertiary-container focus:ring-1 focus:ring-on-tertiary-container outline-none transition-all font-body-md text-body-md" placeholder="Pesquisar por nome do aluno..." type="text" />
        </div>
        <!-- Belt Filter -->
        <div class="flex items-center gap-3">
            <span class="text-on-surface-variant font-label-bold whitespace-nowrap text-label-bold">Graduação:</span>
            <select id="belt-filter" onchange="filterAttendanceTable()" class="border border-slate-200 bg-slate-50/50 rounded-lg px-4 py-2.5 outline-none focus:border-on-tertiary-container transition-all text-sm font-semibold text-slate-700">
                <option value="todos">Todas as Faixas</option>
                <option value="branca">Branca</option>
                <option value="cinza">Cinza</option>
                <option value="amarela">Amarela</option>
                <option value="laranja">Laranja</option>
                <option value="verde">Verde</option>
                <option value="azul">Azul</option>
                <option value="roxa">Roxa</option>
                <option value="marrom">Marrom</option>
                <option value="preta">Preta</option>
            </select>
        </div>
    </div>

    <!-- Attendance Table -->
    <div class="bg-white border border-slate-200 rounded-xl overflow-hidden shadow-sm">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="bg-slate-50 border-b border-slate-200">
                    <th class="px-6 py-4 font-label-bold text-slate-600 text-label-bold">Aluno</th>
                    <th class="px-6 py-4 font-label-bold text-slate-600 text-label-bold">Total de Aulas</th>
                    <th class="px-6 py-4 font-label-bold text-slate-600 text-label-bold">Última Presença</th>
                    <th class="px-6 py-4 font-label-bold text-slate-600 text-right text-label-bold no-print">Histórico</th>
                </tr>
            </thead>
            <tbody id="attendance-tbody" class="divide-y divide-slate-100">
                @forelse($reportData as $data)
                @php
                    $belt = strtolower($data['user']->faixa);
                    $beltClass = '';
                    switch ($belt) {
                        case 'preta':
                            $beltClass = 'bg-slate-900 text-white border border-slate-955';
                            break;
                        case 'marrom':
                            $beltClass = 'bg-amber-900 text-amber-50';
                            break;
                        case 'roxa':
                            $beltClass = 'bg-purple-600 text-purple-50';
                            break;
                        case 'azul':
                            $beltClass = 'bg-blue-600 text-blue-50';
                            break;
                        case 'verde':
                            $beltClass = 'bg-green-600 text-green-50';
                            break;
                        case 'laranja':
                            $beltClass = 'bg-orange-500 text-orange-50';
                            break;
                        case 'amarela':
                            $beltClass = 'bg-yellow-400 text-yellow-950';
                            break;
                        case 'cinza':
                            $beltClass = 'bg-slate-400 text-slate-955';
                            break;
                        default: // branca
                            $beltClass = 'bg-slate-100 text-slate-800 border border-slate-300';
                            break;
                    }
                @endphp
                <tr class="attendance-row hover:bg-slate-50/50 transition-colors" data-name="{{ strtolower($data['user']->name) }}" data-belt="{{ $belt }}">
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-3">
                            <div>
                                <h4 class="font-label-bold text-primary text-label-bold">{{ $data['user']->name }}</h4>
                                <p class="text-xs text-outline">{{ $data['user']->email }}</p>
                            </div>
                            <span class="text-[10px] uppercase font-bold px-2 py-0.5 rounded-full {{ $beltClass }}">
                                Faixa {{ $data['user']->faixa }}
                            </span>
                        </div>
                    </td>
                    <td class="px-6 py-4 font-body-md text-slate-800 font-semibold">
                        {{ $data['presence_count'] }} {{ $data['presence_count'] == 1 ? 'aula' : 'aulas' }}
                    </td>
                    <td class="px-6 py-4 font-body-md text-slate-650">
                        {{ $data['last_presence'] }}
                    </td>
                    <td class="px-6 py-4 text-right no-print">
                        <button onclick="showHistoryModal('{{ $data['user']->name }}', {{ json_encode($data['presence_dates']) }})" class="inline-flex items-center gap-1 text-sm font-semibold text-primary hover:text-slate-900 bg-slate-100 hover:bg-slate-200 px-3 py-1.5 rounded-lg transition-all">
                            <span class="material-symbols-outlined text-[16px]">history</span>
                            Ver Datas
                        </button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-6 py-12 text-center text-outline">
                        Nenhum registro de presença encontrado no período selecionado.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- History Modal (Hidden initially) -->
<div id="history-modal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 flex items-center justify-center p-4 hidden opacity-0 transition-opacity duration-300 no-print">
    <div class="bg-white dark:bg-slate-900 rounded-2xl max-w-md w-full p-6 shadow-2xl relative border border-slate-200 dark:border-slate-800 transform scale-95 transition-transform duration-300">
        <!-- Close Button -->
        <button onclick="hideHistoryModal()" type="button" class="absolute top-4 right-4 text-slate-400 hover:text-slate-600 transition-colors p-1.5 rounded-lg hover:bg-slate-100 dark:hover:bg-slate-800">
            <span class="material-symbols-outlined text-xl">close</span>
        </button>
        <!-- Modal Title -->
        <h3 class="text-lg font-bold text-slate-900 dark:text-slate-100 pr-8" id="modal-student-name">Histórico de Presenças</h3>
        <p class="text-xs text-outline mt-1 mb-4">Datas de check-in registradas no período selecionado</p>
        
        <!-- Date Lists -->
        <div class="max-h-[300px] overflow-y-auto mt-2 pr-1" id="modal-dates-container">
            <!-- Badges list will be dynamically populated here -->
        </div>

        <div class="mt-6 flex justify-end">
            <button onclick="hideHistoryModal()" class="px-4 py-2 bg-slate-100 hover:bg-slate-200 text-slate-700 font-label-bold rounded-lg transition-all text-sm">
                Fechar
            </button>
        </div>
    </div>
</div>

<script>
    /**
     * Filter students list client-side dynamically by search string and belt selector
     */
    function filterAttendanceTable() {
        const query = document.getElementById('name-search').value.toLowerCase().trim();
        const beltFilter = document.getElementById('belt-filter').value;
        const rows = document.querySelectorAll('.attendance-row');

        rows.forEach(row => {
            const name = row.getAttribute('data-name');
            const belt = row.getAttribute('data-belt');
            
            const matchesSearch = name.includes(query);
            const matchesBelt = (beltFilter === 'todos' || belt === beltFilter);

            if (matchesSearch && matchesBelt) {
                row.classList.remove('hidden');
            } else {
                row.classList.add('hidden');
            }
        });
    }

    /**
     * Show Modal listing the exact presence check-in dates
     */
    function showHistoryModal(studentName, dates) {
        document.getElementById('modal-student-name').innerText = `Presenças de ${studentName}`;
        const container = document.getElementById('modal-dates-container');
        container.innerHTML = '';

        if (!dates || dates.length === 0) {
            container.innerHTML = `<p class="text-sm text-outline text-center py-4">Nenhuma data registrada.</p>`;
        } else {
            const gridDiv = document.createElement('div');
            gridDiv.className = 'grid grid-cols-2 gap-2';
            
            dates.forEach(date => {
                const dateBadge = document.createElement('div');
                dateBadge.className = 'flex items-center gap-1.5 bg-slate-50 dark:bg-slate-950 border border-slate-200 dark:border-slate-800 rounded-lg p-2 text-xs font-semibold text-slate-700 dark:text-slate-350';
                dateBadge.innerHTML = `
                    <span class="material-symbols-outlined text-[16px] text-emerald-600">check_circle</span>
                    <span>${date}</span>
                `;
                gridDiv.appendChild(dateBadge);
            });
            
            container.appendChild(gridDiv);
        }

        const modal = document.getElementById('history-modal');
        const modalContent = modal.querySelector('div');
        modal.classList.remove('hidden');
        setTimeout(() => {
            modal.classList.remove('opacity-0');
            modalContent.classList.remove('scale-95');
        }, 10);
    }

    /**
     * Hide Modal
     */
    function hideHistoryModal() {
        const modal = document.getElementById('history-modal');
        const modalContent = modal.querySelector('div');
        modal.classList.add('opacity-0');
        modalContent.classList.add('scale-95');
        setTimeout(() => {
            modal.classList.add('hidden');
        }, 300);
    }
</script>
@endsection
