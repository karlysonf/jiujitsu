@extends('layouts.app')

@section('content')
<style>
    .plans-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 1.5rem;
    }
    .plan-card {
        padding: 1.5rem;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        border-top: 4px solid var(--primary);
    }

    .modal-overlay {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0,0,0,0.5);
        z-index: 1000;
        justify-content: center;
        align-items: center;
    }
    .modal-overlay.active {
        display: flex;
    }
    .modal-content {
        background: white;
        border-radius: var(--border-radius-md);
        padding: 2rem;
        width: 90%;
        max-width: 480px;
        box-shadow: var(--shadow-lg);
    }
    .modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1.5rem;
    }
    .modal-close {
        background: none;
        border: none;
        font-size: 1.25rem;
        cursor: pointer;
        color: var(--text-muted);
    }
</style>

<div style="margin-bottom: 2.5rem; display: flex; justify-content: space-between; align-items: flex-end; flex-wrap: wrap; gap: 1rem;">
    <div>
        <h1 style="margin-bottom: 0.5rem;">Planos</h1>
        <p>Gerencie os planos de mensalidade disponíveis.</p>
    </div>
    <button type="button" class="btn btn-primary" onclick="openCreateModal()">
        <i class="fas fa-plus"></i> Novo Plano
    </button>
</div>

<div class="plans-grid">
    @forelse($plans as $plan)
        <div class="card plan-card hover-lift">
            <div style="display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 1.5rem;">
                <div style="display: flex; gap: 1rem; align-items: center;">
                    <div style="width: 48px; height: 48px; background: #EEF2FF; border-radius: 50%; display: flex; align-items: center; justify-content: center; color: var(--primary); font-size: 1.25rem;">
                        <i class="fas fa-tag"></i>
                    </div>
                    <div>
                        <h3 style="font-size: 1.125rem; font-weight: 700; margin-bottom: 0;">{{ $plan->name }}</h3>
                        <div style="font-size: 0.8125rem; color: var(--text-muted);">Mensalidade</div>
                    </div>
                </div>
            </div>
            
            <div style="margin-bottom: 1.5rem; background: #F8FAFC; padding: 1.25rem; border-radius: 0.5rem; border: 1px solid #F1F5F9; text-align: center;">
                <div style="font-size: 0.75rem; color: var(--text-muted); text-transform: uppercase; font-weight: 700; margin-bottom: 0.25rem;">Valor Mensal</div>
                <div style="font-size: 1.75rem; font-weight: 800; color: var(--primary);">R$ {{ number_format($plan->price, 2, ',', '.') }}</div>
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 0.5rem; border-top: 1px solid #E2E8F0; padding-top: 1rem;">
                <button type="button" class="btn" style="height: 36px; width: 36px; padding: 0; background: #FEF9C3; color: #854D0E;" onclick="openEditModal({{ $plan->id }}, '{{ $plan->name }}', '{{ $plan->price }}')" title="Editar">
                    <i class="fas fa-edit"></i>
                </button>
                @if(auth()->user()->hasAnyRole(['root', 'admin']))
                <form action="{{ route('plans.destroy', $plan) }}" method="POST" style="display: inline-block;" onsubmit="return confirm('Tem certeza que deseja excluir este plano?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn" style="height: 36px; width: 36px; padding: 0; background: #FEE2E2; color: #991B1B;" title="Excluir">
                        <i class="fas fa-trash"></i>
                    </button>
                </form>
                @endif
            </div>
        </div>
    @empty
        <div class="card" style="text-align: center; padding: 3rem; color: var(--text-muted); grid-column: 1 / -1;">
            <i class="fas fa-tags" style="font-size: 3rem; margin-bottom: 1rem; opacity: 0.5;"></i>
            <h3>Nenhum plano disponível</h3>
            <p>Clique em "Novo Plano" para cadastrar seu primeiro plano.</p>
        </div>
    @endforelse
</div>

<!-- Create Modal -->
<div class="modal-overlay" id="createModal">
    <div class="modal-content">
        <div class="modal-header">
            <h3 style="margin: 0;">Novo Plano</h3>
            <button type="button" class="modal-close" onclick="closeCreateModal()"><i class="fas fa-times"></i></button>
        </div>
        <form action="{{ route('plans.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label class="form-label">Nome do Plano</label>
                <input type="text" name="name" class="form-input" required placeholder="Ex: Mensalidade Padrão" value="{{ old('name') }}">
            </div>
            <div class="form-group">
                <label class="form-label">Valor (R$)</label>
                <input type="number" name="price" class="form-input" required step="0.01" min="0" placeholder="0,00" value="{{ old('price') }}">
            </div>
            <div style="display: flex; gap: 1rem; margin-top: 1.5rem;">
                <button type="submit" class="btn btn-primary" style="flex: 1;">
                    <i class="fas fa-save"></i> Criar Plano
                </button>
                <button type="button" class="btn" style="background: #F1F5F9; color: var(--text-muted); padding-inline: 1.5rem;" onclick="closeCreateModal()">Cancelar</button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Modal -->
<div class="modal-overlay" id="editModal">
    <div class="modal-content">
        <div class="modal-header">
            <h3 style="margin: 0;">Editar Plano</h3>
            <button type="button" class="modal-close" onclick="closeEditModal()"><i class="fas fa-times"></i></button>
        </div>
        <form id="editForm" method="POST">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label class="form-label">Nome do Plano</label>
                <input type="text" name="name" id="editName" class="form-input" required placeholder="Ex: Mensalidade Padrão">
            </div>
            <div class="form-group">
                <label class="form-label">Valor (R$)</label>
                <input type="number" name="price" id="editPrice" class="form-input" required step="0.01" min="0" placeholder="0,00">
            </div>
            <div style="display: flex; gap: 1rem; margin-top: 1.5rem;">
                <button type="submit" class="btn btn-primary" style="flex: 1;">
                    <i class="fas fa-save"></i> Salvar Alterações
                </button>
                <button type="button" class="btn" style="background: #F1F5F9; color: var(--text-muted); padding-inline: 1.5rem;" onclick="closeEditModal()">Cancelar</button>
            </div>
        </form>
    </div>
</div>

@if($errors->any() && !request()->routeIs('plans.update'))
<script>
    document.addEventListener('DOMContentLoaded', function() {
        openCreateModal();
    });
</script>
@endif

<script>
    function openCreateModal() {
        document.getElementById('createModal').classList.add('active');
    }
    function closeCreateModal() {
        document.getElementById('createModal').classList.remove('active');
    }
    function openEditModal(id, name, price) {
        document.getElementById('editForm').action = '{{ url("plans") }}/' + id;
        document.getElementById('editName').value = name;
        document.getElementById('editPrice').value = price;
        document.getElementById('editModal').classList.add('active');
    }
    function closeEditModal() {
        document.getElementById('editModal').classList.remove('active');
    }

    document.getElementById('createModal').addEventListener('click', function(e) {
        if (e.target === this) closeCreateModal();
    });
    document.getElementById('editModal').addEventListener('click', function(e) {
        if (e.target === this) closeEditModal();
    });
</script>
@endsection
