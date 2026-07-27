@extends('layouts.app')

@section('content')
<div class="max-w-[1600px] mx-auto pb-24">
    <!-- Header Section -->
    <div class="mb-6 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-cyan-500/10 border border-cyan-500/20 text-cyan-400 text-xs font-bold uppercase tracking-wider mb-2">
                <span class="w-2 h-2 rounded-full bg-cyan-400 animate-pulse"></span>
                Presença e Frequência
            </div>
            <h1 class="font-['Outfit'] font-black text-2xl md:text-4xl text-white tracking-tight">Controle de Presença no Tatame</h1>
            <p class="text-slate-400 text-xs md:text-sm mt-0.5">Gerencie a frequência dos alunos nas aulas de hoje ({{ \Carbon\Carbon::parse($date)->format('d/m/Y') }}).</p>
        </div>
    </div>

    <!-- Summary Bento Grid Section -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <div class="bg-[#111726] border border-white/10 p-6 rounded-2xl shadow-xl flex flex-col justify-between">
            <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Total de Alunos</span>
            <div class="flex items-baseline gap-2 mt-2">
                <span class="font-['Outfit'] font-extrabold text-3xl text-white">{{ $users->count() }}</span>
                <span class="text-xs text-slate-400 font-medium">Ativos no cadastro</span>
            </div>
            <div class="mt-4 h-1.5 w-full bg-[#182234] rounded-full overflow-hidden">
                <div class="h-full bg-rose-500" style="width: 100%"></div>
            </div>
        </div>

        <div class="bg-[#111726] border border-white/10 p-6 rounded-2xl shadow-xl flex flex-col justify-between">
            <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Presenças Confirmadas</span>
            <div class="flex items-baseline gap-2 mt-2">
                <span class="font-['Outfit'] font-extrabold text-3xl text-cyan-400">{{ $attendances->count() }}</span>
                <span class="text-xs text-cyan-400/80 font-medium">Alunos em aula</span>
            </div>
            <div class="mt-4 h-1.5 w-full bg-[#182234] rounded-full overflow-hidden">
                <div class="h-full bg-cyan-400" style="width: {{ $users->count() > 0 ? ($attendances->count() / $users->count()) * 100 : 0 }}%"></div>
            </div>
        </div>

        <div class="bg-[#111726] border border-white/10 p-6 rounded-2xl shadow-xl flex flex-col justify-between relative overflow-hidden group">
            <div class="relative z-10">
                <span class="text-xs font-semibold text-slate-400 uppercase tracking-wider">Taxa de Ocupação</span>
                <div class="flex items-baseline gap-2 mt-2">
                    <span class="font-['Outfit'] font-extrabold text-3xl text-rose-400">{{ $users->count() > 0 ? round(($attendances->count() / $users->count()) * 100) : 0 }}%</span>
                    <span class="text-xs text-slate-400 font-medium">Capacidade do Tatame</span>
                </div>
            </div>
            <div class="absolute -right-4 -bottom-4 opacity-5 group-hover:opacity-10 transition-opacity pointer-events-none">
                <span class="material-symbols-outlined text-[120px] text-white">analytics</span>
            </div>
        </div>
    </div>

    <!-- Smart Attendance Upload Card -->
    <div class="bg-gradient-to-r from-[#111726] via-[#182234] to-[#111726] border border-cyan-500/30 p-6 rounded-2xl shadow-xl mb-6">
        <div class="flex flex-col md:flex-row items-center justify-between gap-6">
            <div class="flex items-start gap-4">
                <div class="w-12 h-12 rounded-xl bg-cyan-500/10 border border-cyan-500/30 flex items-center justify-center text-cyan-400 shrink-0 shadow-lg shadow-cyan-500/10">
                    <span class="material-symbols-outlined text-[28px]">face_retouching_natural</span>
                </div>
                <div>
                    <h3 class="text-lg font-['Outfit'] font-bold text-white">Chamada Inteligente por Foto (IA)</h3>
                    <p class="text-xs text-slate-300 mt-1 leading-relaxed max-w-3xl">
                        Faça upload de uma foto da aula de hoje. A inteligência artificial identificará os alunos presentes e pré-selecionará seus nomes na lista abaixo para sua validação.
                        <span class="text-cyan-400 block mt-1 font-semibold">Obs: Alunos devem ter foto cadastrada em seus perfis para serem reconhecidos pela IA.</span>
                    </p>
                </div>
            </div>
            <div class="w-full md:w-auto shrink-0 flex flex-col items-center gap-2">
                <button type="button" onclick="document.getElementById('photoUploadInput').click()" class="w-full md:w-auto bg-gradient-to-r from-rose-600 to-rose-700 text-white px-6 py-3 rounded-xl font-['Outfit'] font-bold text-sm flex items-center justify-center gap-2 hover:shadow-lg hover:shadow-rose-600/30 transition-all cursor-pointer">
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
    <div id="loadingOverlay" class="fixed inset-0 bg-[#090d16]/90 backdrop-blur-md z-50 flex flex-col items-center justify-center gap-4 hidden">
        <div class="w-16 h-16 border-4 border-rose-500 border-t-transparent rounded-full animate-spin"></div>
        <p class="text-white font-['Outfit'] font-bold text-xl animate-pulse">Analisando imagem com Inteligência Artificial...</p>
        <p class="text-slate-400 text-xs">Isso pode levar alguns segundos.</p>
    </div>

    <!-- Filters Bar -->
    <div class="bg-[#111726] border border-white/10 p-4 rounded-2xl mb-6 flex flex-wrap items-center justify-between gap-4 shadow-xl">
        <div class="flex-1 min-w-[280px] relative">
            <span class="material-symbols-outlined absolute left-3 top-1/2 -translate-y-1/2 text-slate-500 text-sm">search</span>
            <input id="studentSearch" class="w-full pl-10 pr-4 py-2 bg-[#090d16] border border-white/10 rounded-xl text-sm text-white placeholder-slate-500 focus:outline-none focus:border-rose-500 transition-all" placeholder="Buscar aluno pelo nome..." type="text"/>
        </div>
        <div class="flex items-center gap-4 flex-wrap">
            <div class="flex items-center gap-2">
                <span class="text-xs font-semibold text-slate-300">Data da Aula:</span>
                <input type="date" id="attendanceDate" value="{{ $date }}" class="px-3 py-2 rounded-xl border border-white/10 bg-[#090d16] text-white text-xs cursor-pointer focus:outline-none focus:border-rose-500" onchange="window.location.href = '{{ route('attendances.index') }}?date=' + this.value">
            </div>
            <div class="flex items-center gap-2">
                <span class="text-xs font-semibold text-slate-300">Status:</span>
                <div class="inline-flex rounded-xl border border-white/10 p-1 bg-[#090d16]" id="statusFilters">
                    <button data-status="all" class="filter-btn px-3 py-1 rounded-lg bg-rose-600 text-white font-bold text-xs">Todos</button>
                    <button data-status="present" class="filter-btn px-3 py-1 rounded-lg text-slate-400 hover:text-white font-semibold text-xs transition-colors">Presente</button>
                    <button data-status="absent" class="filter-btn px-3 py-1 rounded-lg text-slate-400 hover:text-white font-semibold text-xs transition-colors">Ausente</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Students List -->
    <form action="{{ route('attendances.bulk') }}" method="POST" id="attendanceForm">
        @csrf
        <input type="hidden" name="date" value="{{ $date }}">
        <div class="bg-[#111726] border border-white/10 rounded-2xl overflow-hidden shadow-xl mb-6">
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse min-w-[600px] md:min-w-full">
                <thead>
                    <tr class="border-b border-white/10 text-slate-400 text-xs font-bold uppercase tracking-wider bg-[#0d1320]/60">
                        <th class="px-6 py-4 w-16 text-center">
                            <input type="checkbox" id="selectAll" class="w-4 h-4 rounded border-white/20 bg-[#090d16] text-rose-600 focus:ring-rose-500 transition-all cursor-pointer">
                        </th>
                        <th class="px-6 py-4">Aluno</th>
                        <th class="px-6 py-4">Graduação</th>
                        <th class="px-6 py-4">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5" id="attendanceTableBody">
                    @foreach($users as $user)
                        @php
                            $hasAttended = $attendances->where('user_id', $user->id)->isNotEmpty();
                            $beltColor = match(strtolower($user->faixa)) {
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
                        <tr class="hover:bg-white/5 transition-colors group attendance-row" data-name="{{ strtolower($user->name) }}" data-present="{{ $hasAttended ? 'true' : 'false' }}">
                            <td class="px-6 py-4 text-center">
                                <input type="checkbox" name="present_users[]" value="{{ $user->id }}" {{ $hasAttended ? 'checked' : '' }} class="student-checkbox w-4 h-4 rounded border-white/20 bg-[#090d16] text-rose-600 focus:ring-rose-500 transition-all cursor-pointer">
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 rounded-xl bg-[#182234] border border-white/10 flex items-center justify-center text-rose-400 overflow-hidden font-bold text-xs">
                                        @if($user->avatar_url)
                                            <img src="{{ $user->avatar_url }}" alt="{{ $user->name }}" class="w-full h-full object-cover">
                                        @else
                                            {{ strtoupper(substr($user->name, 0, 2)) }}
                                        @endif
                                    </div>
                                    <div>
                                        <div class="font-semibold text-sm text-white">{{ $user->name }}</div>
                                        <div class="text-[11px] text-slate-400">Últ. aula: {{ $user->attendances->first()?->date?->diffForHumans() ?? 'Nenhuma registrada' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2.5 py-1 {{ $beltColor }} rounded-md text-[10px] font-bold tracking-wider uppercase">FAIXA {{ strtoupper($user->faixa) }}</span>
                            </td>
                            <td class="px-6 py-4 status-cell">
                                <span class="status-badge px-3 py-1 rounded-full text-xs font-bold transition-all duration-200 {{ $hasAttended ? 'bg-emerald-500/10 border border-emerald-500/30 text-emerald-400' : 'bg-rose-500/10 border border-rose-500/30 text-rose-400' }}">
                                    {{ $hasAttended ? 'Confirmado' : 'Ausente' }}
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                </table>
            </div>
            <div class="px-6 py-4 bg-[#0d1320] flex items-center justify-between border-t border-white/10 text-xs text-slate-400">
                <span id="showingText">Mostrando {{ $users->count() }} de {{ $users->count() }} alunos</span>
            </div>
        </div>

        <!-- Sticky Bottom Bar for bulk actions -->
        <div class="sticky bottom-4 bg-[#111726]/90 backdrop-blur-md border border-white/10 py-4 px-6 shadow-2xl z-20 mt-6 rounded-2xl flex flex-col sm:flex-row justify-between items-center gap-4">
            <div class="flex items-center gap-3">
                <span class="material-symbols-outlined text-rose-500 text-xl">checklist</span>
                <span class="text-sm font-semibold text-white" id="selectedCountText">
                    0 de {{ $users->count() }} presenças marcadas
                </span>
            </div>
            <button type="submit" class="w-full sm:w-auto bg-gradient-to-r from-rose-600 to-rose-700 text-white px-8 py-3 rounded-xl font-['Outfit'] font-bold text-sm flex items-center justify-center gap-2 hover:shadow-lg hover:shadow-rose-600/30 transition-all">
                <span class="material-symbols-outlined text-base">save</span>
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
        alertBox.className = "mt-4 p-4 rounded-xl text-xs font-semibold";
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
            
            if (data.matched_user_ids && data.matched_user_ids.length > 0) {
                alertBox.className = "mt-4 p-4 rounded-xl bg-emerald-500/10 border border-emerald-500/30 text-emerald-300 text-xs font-medium";
                alertBox.innerHTML = `<strong>✨ IA Reconhecimento Facial:</strong> ${data.matched_user_ids.length} aluno(s) identificado(s) e selecionado(s) na lista abaixo!`;
                alertBox.classList.remove('hidden');
                
                // Select checkboxes
                data.matched_user_ids.forEach(id => {
                    const cb = document.querySelector(`.student-checkbox[value="${id}"]`);
                    if (cb) {
                        cb.checked = true;
                        const row = cb.closest('tr');
                        if (row) {
                            row.dataset.present = "true";
                            const badge = row.querySelector('.status-badge');
                            if (badge) {
                                badge.textContent = "Confirmado";
                                badge.className = "status-badge px-3 py-1 rounded-full text-xs font-bold transition-all duration-200 bg-emerald-500/10 border border-emerald-500/30 text-emerald-400";
                            }
                        }
                    }
                });
                updateSelectedCount();
            } else {
                alertBox.className = "mt-4 p-4 rounded-xl bg-amber-500/10 border border-amber-500/30 text-amber-300 text-xs font-medium";
                alertBox.innerHTML = `<strong>⚠️ IA:</strong> Nenhum aluno foi reconhecido na imagem enviada. Certifique-se de que os alunos possuem fotos cadastradas no perfil.`;
                alertBox.classList.remove('hidden');
            }
        })
        .catch(err => {
            overlay.classList.add('hidden');
            alertBox.className = "mt-4 p-4 rounded-xl bg-rose-500/10 border border-rose-500/30 text-rose-300 text-xs font-medium";
            alertBox.innerHTML = `<strong>❌ Erro:</strong> ${err.message}`;
            alertBox.classList.remove('hidden');
        });
    };

    document.addEventListener('DOMContentLoaded', function() {
        const checkboxes = document.querySelectorAll('.student-checkbox');
        const selectAll = document.getElementById('selectAll');
        const selectedCountText = document.getElementById('selectedCountText');
        const studentSearch = document.getElementById('studentSearch');
        const rows = document.querySelectorAll('.attendance-row');
        const filterBtns = document.querySelectorAll('.filter-btn');

        function updateSelectedCount() {
            const checked = document.querySelectorAll('.student-checkbox:checked').length;
            const total = checkboxes.length;
            if (selectedCountText) {
                selectedCountText.textContent = `${checked} de ${total} presenças marcadas`;
            }
            if (selectAll) {
                selectAll.checked = checked === total && total > 0;
            }
        }

        checkboxes.forEach(cb => {
            cb.addEventListener('change', function() {
                const row = this.closest('tr');
                const badge = row.querySelector('.status-badge');
                if (this.checked) {
                    row.dataset.present = 'true';
                    if (badge) {
                        badge.textContent = 'Confirmado';
                        badge.className = 'status-badge px-3 py-1 rounded-full text-xs font-bold transition-all duration-200 bg-emerald-500/10 border border-emerald-500/30 text-emerald-400';
                    }
                } else {
                    row.dataset.present = 'false';
                    if (badge) {
                        badge.textContent = 'Ausente';
                        badge.className = 'status-badge px-3 py-1 rounded-full text-xs font-bold transition-all duration-200 bg-rose-500/10 border border-rose-500/30 text-rose-400';
                    }
                }
                updateSelectedCount();
            });
        });

        if (selectAll) {
            selectAll.addEventListener('change', function() {
                checkboxes.forEach(cb => {
                    cb.checked = this.checked;
                    cb.dispatchEvent(new Event('change'));
                });
            });
        }

        let currentStatusFilter = 'all';

        function filterRows() {
            const search = studentSearch.value.toLowerCase();
            let count = 0;
            rows.forEach(row => {
                const name = row.dataset.name;
                const isPresent = row.dataset.present === 'true';
                const matchesSearch = name.includes(search);
                let matchesStatus = true;
                if (currentStatusFilter === 'present') matchesStatus = isPresent;
                if (currentStatusFilter === 'absent') matchesStatus = !isPresent;

                if (matchesSearch && matchesStatus) {
                    row.style.display = '';
                    count++;
                } else {
                    row.style.display = 'none';
                }
            });

            const showingText = document.getElementById('showingText');
            if (showingText) {
                showingText.textContent = `Mostrando ${count} de ${rows.length} alunos`;
            }
        }

        if (studentSearch) {
            studentSearch.addEventListener('input', filterRows);
        }

        filterBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                filterBtns.forEach(b => {
                    b.classList.remove('bg-rose-600', 'text-white');
                    b.classList.add('text-slate-400');
                });
                this.classList.add('bg-rose-600', 'text-white');
                this.classList.remove('text-slate-400');
                currentStatusFilter = this.dataset.status;
                filterRows();
            });
        });

        updateSelectedCount();
    });
</script>
@endsection
