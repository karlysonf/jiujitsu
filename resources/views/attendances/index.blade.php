@extends('layouts.app')

@section('content')
<div class="max-w-[1600px] mx-auto pb-24">
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

    <!-- Smart Attendance Upload Card -->
    <div class="bg-white border border-outline-variant p-6 rounded-xl shadow-sm mb-6">
        <div class="flex flex-col md:flex-row items-center justify-between gap-6">
            <div class="flex items-start gap-4">
                <div class="w-12 h-12 rounded-full bg-primary/10 flex items-center justify-center text-primary shrink-0">
                    <span class="material-symbols-outlined text-[28px]">face_retouching_natural</span>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-slate-900">Chamada Inteligente por Foto (IA)</h3>
                    <p class="text-sm text-on-surface-variant mt-1 leading-relaxed">
                        Faça upload de uma foto da aula de hoje. A inteligência artificial identificará os alunos presentes e pré-selecionará seus nomes na lista abaixo para sua validação.
                        <span class="text-xs text-indigo-600 block mt-1 font-semibold">Obs: Alunos devem ter foto cadastrada em seus perfis para serem reconhecidos.</span>
                    </p>
                </div>
            </div>
            <div class="w-full md:w-auto shrink-0 flex flex-col items-center gap-2">
                <button type="button" onclick="document.getElementById('photoUploadInput').click()" class="w-full md:w-auto bg-primary text-on-primary px-6 py-3 rounded-xl font-label-bold text-label-bold flex items-center justify-center gap-2 hover:scale-[0.98] transition-transform shadow-md active:opacity-90 cursor-pointer">
                    <span class="material-symbols-outlined">upload_file</span>
                    Enviar Foto do Tatame
                </button>
                <input type="file" id="photoUploadInput" accept="image/*" class="hidden" onchange="uploadTatamePhoto(this)">
            </div>
        </div>
        
        <!-- Feedback Alert Container -->
        <div id="aiFeedbackAlert" class="mt-4 hidden">
            <!-- Will be populated via JS -->
        </div>
    </div>

    <!-- Loading Overlay -->
    <div id="loadingOverlay" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 flex flex-col items-center justify-center gap-4 hidden">
        <div class="w-16 h-16 border-4 border-primary border-t-transparent rounded-full animate-spin"></div>
        <p class="text-white font-label-bold text-headline-md animate-pulse">Analisando imagem com Inteligência Artificial...</p>
        <p class="text-slate-300 text-body-md">Isso pode levar alguns segundos.</p>
    </div>

    <!-- Filters Bar -->
    <div class="bg-white border border-outline-variant p-4 rounded-xl mb-6 flex flex-wrap items-center justify-between gap-4">
        <div class="flex-1 min-w-[300px] relative">
            <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-outline">search</span>
            <input id="studentSearch" class="w-full pl-10 pr-4 py-2 bg-surface-container-low border border-outline-variant rounded focus:border-tertiary-container focus:ring-2 focus:ring-tertiary-container/20 outline-none transition-all font-body-md text-body-md" placeholder="Buscar aluno pelo nome..." type="text"/>
        </div>
        <div class="flex items-center gap-4 flex-wrap">
            <div class="flex items-center gap-2">
                <span class="text-label-bold font-label-bold text-on-surface">Data da Aula:</span>
                <input type="date" id="attendanceDate" value="{{ $date }}" class="px-3 py-1.5 rounded border border-outline-variant bg-surface-container-low focus:border-tertiary-container focus:ring-2 focus:ring-tertiary-container/20 outline-none transition-all font-body-md text-body-md text-primary cursor-pointer" onchange="window.location.href = '{{ route('attendances.index') }}?date=' + this.value">
            </div>
            <div class="flex items-center gap-2">
                <span class="text-label-bold font-label-bold text-on-surface">Status:</span>
                <div class="inline-flex rounded-lg border border-outline-variant p-1 bg-surface-container-low" id="statusFilters">
                    <button data-status="all" class="filter-btn px-4 py-1.5 rounded bg-white shadow-sm text-label-bold font-label-bold text-primary">Todos</button>
                    <button data-status="present" class="filter-btn px-4 py-1.5 rounded text-label-bold font-label-bold text-on-surface-variant hover:bg-white/50 transition-colors">Presente</button>
                    <button data-status="absent" class="filter-btn px-4 py-1.5 rounded text-label-bold font-label-bold text-on-surface-variant hover:bg-white/50 transition-colors">Ausente</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Students List -->
    <form action="{{ route('attendances.bulk') }}" method="POST" id="attendanceForm">
        @csrf
        <input type="hidden" name="date" value="{{ $date }}">
        <div class="bg-white border border-outline-variant rounded-xl overflow-hidden shadow-sm mb-6">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse min-w-[600px] md:min-w-full">
                <thead>
                    <tr class="bg-surface-container-low border-b border-outline-variant">
                        <th class="px-6 py-4 w-16 text-center">
                            <input type="checkbox" id="selectAll" class="w-5 h-5 rounded border-outline-variant text-primary focus:ring-primary/20 transition-all cursor-pointer">
                        </th>
                        <th class="px-6 py-4 font-label-bold text-label-bold text-on-surface-variant uppercase tracking-wider">Aluno</th>
                        <th class="px-6 py-4 font-label-bold text-label-bold text-on-surface-variant uppercase tracking-wider">Graduação</th>
                        <th class="px-6 py-4 font-label-bold text-label-bold text-on-surface-variant uppercase tracking-wider">Status</th>
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
                            <td class="px-6 py-4 text-center">
                                <input type="checkbox" name="present_users[]" value="{{ $user->id }}" {{ $hasAttended ? 'checked' : '' }} class="student-checkbox w-5 h-5 rounded border-outline-variant text-primary focus:ring-primary/20 transition-all cursor-pointer">
                            </td>
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
                            <td class="px-6 py-4 status-cell">
                                <span class="status-badge px-3 py-1 rounded-full text-label-sm font-label-bold transition-all duration-200 {{ $hasAttended ? 'bg-secondary-container text-on-secondary-container' : 'bg-error-container text-on-error-container' }}">
                                    {{ $hasAttended ? 'Confirmado' : 'Ausente' }}
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                </table>
            </div>
            <div class="px-6 py-4 bg-surface-container-low flex items-center justify-between">
                <span class="text-label-sm font-label-sm text-on-surface-variant" id="showingText">Mostrando {{ $users->count() }} de {{ $users->count() }} alunos</span>
            </div>
        </div>

        <!-- Sticky Bottom Bar for bulk actions -->
        <div class="sticky bottom-4 bg-white dark:bg-slate-900 border border-outline-variant py-4 px-6 shadow-[0_-4px_12px_rgba(0,0,0,0.05)] z-20 mt-6 rounded-xl flex flex-col sm:flex-row justify-between items-center gap-4">
            <div class="flex items-center gap-3">
                <span class="material-symbols-outlined text-primary text-[24px]">checklist</span>
                <span class="text-body-md text-primary font-label-bold" id="selectedCountText">
                    0 de {{ $users->count() }} presenças marcadas
                </span>
            </div>
            <button type="submit" class="w-full sm:w-auto bg-primary text-on-primary px-8 py-3.5 rounded-xl font-label-bold text-label-bold flex items-center justify-center gap-2 hover:scale-[0.98] transition-transform shadow-md active:opacity-90">
                <span class="material-symbols-outlined text-[20px]">save</span>
                Validar Presenças
            </button>
        </div>
    </form>
</div>

<script>
    window.uploadTatamePhoto = function(input) {
        if (!input.files || !input.files[0]) return;
        
        const file = input.files[0];
        const formData = new FormData();
        formData.append('photo', file);
        
        // Show loading overlay
        const overlay = document.getElementById('loadingOverlay');
        overlay.classList.remove('hidden');
        
        // Hide previous alerts
        const alertBox = document.getElementById('aiFeedbackAlert');
        alertBox.classList.add('hidden');
        alertBox.className = "mt-4 p-4 rounded-lg text-sm font-semibold";
        alertBox.innerHTML = "";

        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') 
            || "{{ csrf_token() }}";

        fetch("{{ route('attendances.identify-faces') }}", {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Accept': 'application/json'
            },
            body: formData
        })
        .then(async response => {
            if (!response.ok) {
                let errMessage = 'Erro ao processar imagem.';
                try {
                    const errData = await response.json();
                    errMessage = errData.message || errMessage;
                } catch (e) {
                    errMessage = `Erro do servidor (Status ${response.status}). A foto pode ser muito grande ou a sessão expirou.`;
                }
                throw new Error(errMessage);
            }
            return response.json();
        })
        .then(data => {
            overlay.classList.add('hidden');
            
            if (data.success && data.identified_ids) {
                const identifiedIds = data.identified_ids;
                
                // Uncheck all first so we only mark the identified ones
                const checkboxes = document.querySelectorAll('.student-checkbox');
                checkboxes.forEach(cb => {
                    if (cb.checked) {
                        cb.checked = false;
                        cb.dispatchEvent(new Event('change'));
                    }
                });

                // Check the identified ones
                let checkCount = 0;
                identifiedIds.forEach(id => {
                    const cb = document.querySelector(`.student-checkbox[value="${id}"]`);
                    if (cb) {
                        cb.checked = true;
                        cb.dispatchEvent(new Event('change'));
                        checkCount++;
                    }
                });

                // Show success alert
                alertBox.classList.remove('hidden');
                if (data.simulation) {
                    alertBox.className = "mt-4 p-4 rounded-lg bg-yellow-50 border border-yellow-200 text-yellow-800 text-sm font-medium flex flex-col gap-1";
                    alertBox.innerHTML = `
                        <div class="flex items-center gap-2">
                            <span class="material-symbols-outlined text-base">warning</span>
                            <span class="font-bold">Modo Simulação Ativo</span>
                        </div>
                        <p class="text-xs mt-1">${data.message || 'Falha ao se comunicar com o serviço de reconhecimento facial (ou serviço configurado incorretamente). Simulamos a presença de <strong>' + checkCount + ' alunos</strong>.'}</p>
                    `;
                } else {
                    alertBox.className = "mt-4 p-4 rounded-lg bg-green-50 border border-green-200 text-green-800 text-sm font-medium flex flex-col gap-1";
                    alertBox.innerHTML = `
                        <div class="flex items-center gap-2">
                            <span class="material-symbols-outlined text-base">check_circle</span>
                            <span class="font-bold">Reconhecimento IA concluído!</span>
                        </div>
                        <p class="text-xs mt-1">Identificamos <strong>${checkCount} alunos</strong> na foto enviada. Verifique as seleções abaixo e clique em <strong>Validar Presenças</strong> para confirmar.</p>
                    `;
                }
            } else {
                throw new Error(data.message || 'Falha ao identificar rostos.');
            }
        })
        .catch(error => {
            overlay.classList.add('hidden');
            alertBox.classList.remove('hidden');
            alertBox.className = "mt-4 p-4 rounded-lg bg-red-50 border border-red-200 text-red-800 text-sm font-medium flex items-center gap-2";
            alertBox.innerHTML = `
                <span class="material-symbols-outlined text-base">error</span>
                <span>Erro: ${error.message}</span>
            `;
            console.error(error);
        })
        .finally(() => {
            // Reset input so they can upload same file if they want
            input.value = "";
        });
    };

    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('studentSearch');
        const filterButtons = document.querySelectorAll('.filter-btn');
        const rows = document.querySelectorAll('.attendance-row');
        const showingText = document.getElementById('showingText');
        const selectAll = document.getElementById('selectAll');

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

        function updateSelectedCount() {
            const totalChecked = document.querySelectorAll('.student-checkbox:checked').length;
            const countText = document.getElementById('selectedCountText');
            if (countText) {
                countText.textContent = `${totalChecked} de ${rows.length} presenças marcadas`;
            }
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

        // Checkbox toggle logic
        rows.forEach(row => {
            const checkbox = row.querySelector('.student-checkbox');
            const statusBadge = row.querySelector('.status-badge');
            if (checkbox && statusBadge) {
                checkbox.addEventListener('change', function() {
                    row.setAttribute('data-present', this.checked ? 'true' : 'false');
                    
                    if (this.checked) {
                        statusBadge.textContent = 'Confirmado';
                        statusBadge.className = 'status-badge px-3 py-1 rounded-full text-label-sm font-label-bold bg-secondary-container text-on-secondary-container';
                    } else {
                        statusBadge.textContent = 'Ausente';
                        statusBadge.className = 'status-badge px-3 py-1 rounded-full text-label-sm font-label-bold bg-error-container text-on-error-container';
                    }
                    
                    updateSelectedCount();
                });
            }
        });

        // Select all logic for visible rows
        if (selectAll) {
            selectAll.addEventListener('change', function() {
                rows.forEach(row => {
                    if (row.style.display !== 'none') {
                        const checkbox = row.querySelector('.student-checkbox');
                        if (checkbox && checkbox.checked !== selectAll.checked) {
                            checkbox.checked = selectAll.checked;
                            checkbox.dispatchEvent(new Event('change'));
                        }
                    }
                });
            });
        }

        // Initialize count
        updateSelectedCount();
    });
</script>
@endsection
