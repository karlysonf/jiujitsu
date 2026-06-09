@extends('layouts.app')

@section('content')
<style>
    .profile-container {
        max-width: 1200px;
        margin: 0 auto;
    }

    .profile-header {
        background: white;
        border: 1px solid #E2E8F0;
        border-radius: 1rem;
        padding: 1.5rem;
        margin-bottom: 2rem;
        display: flex;
        flex-direction: column;
        gap: 1.5rem;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    }
    @media (min-width: 1024px) {
        .profile-header {
            flex-direction: row;
            justify-content: space-between;
            align-items: center;
            padding: 2rem;
        }
    }

    .profile-info {
        display: flex;
        flex-direction: column;
        align-items: center;
        text-align: center;
        gap: 1.5rem;
    }
    @media (min-width: 640px) {
        .profile-info {
            flex-direction: row;
            text-align: left;
            gap: 2rem;
        }
    }

    .profile-avatar {
        width: 80px;
        height: 80px;
        background: #F1F5F9;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2rem;
        color: #94A3B8;
        border: 4px solid white;
        box-shadow: 0 0 0 1px #E2E8F0;
    }

    .profile-name h1 {
        font-size: 1.5rem;
        font-weight: 800;
        color: #1E293B;
        margin-bottom: 0.25rem;
    }

    .profile-meta {
        display: flex;
        align-items: center;
        gap: 1rem;
        font-size: 0.875rem;
        color: #64748B;
    }

    .belt-badge {
        padding: 0.25rem 0.75rem;
        border-radius: 2rem;
        font-weight: 700;
        font-size: 0.75rem;
        text-transform: uppercase;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .belt-color {
        width: 12px;
        height: 12px;
        border-radius: 50%;
        border: 1px solid rgba(0,0,0,0.1);
    }

    .grid-layout {
        display: grid;
        grid-template-columns: 1fr;
        gap: 1.5rem;
    }
    @media (min-width: 1024px) {
        .grid-layout {
            grid-template-columns: 1fr 2fr;
        }
    }

    .info-card {
        background: white;
        border: 1px solid #E2E8F0;
        border-radius: 1rem;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        box-shadow: 0 1px 3px rgba(0,0,0,0.05);
    }

    .info-card h2 {
        font-size: 1rem;
        font-weight: 700;
        color: #334155;
        margin-bottom: 1.25rem;
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }

    .data-row {
        display: flex;
        flex-direction: column;
        gap: 0.25rem;
        margin-bottom: 1.25rem;
    }

    .data-row:last-child {
        margin-bottom: 0;
    }

    .data-label {
        font-size: 0.75rem;
        font-weight: 600;
        color: #94A3B8;
        text-transform: uppercase;
        letter-spacing: 0.025em;
    }

    .data-value {
        font-size: 0.9375rem;
        font-weight: 600;
        color: #1E293B;
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 1rem;
        margin-bottom: 1.5rem;
    }
    @media (min-width: 640px) {
        .stats-grid {
            grid-template-columns: repeat(3, 1fr);
        }
    }

    .stat-box {
        background: #F8FAFC;
        border: 1px solid #F1F5F9;
        padding: 1rem;
        border-radius: 0.75rem;
        text-align: center;
    }

    .stat-value {
        font-size: 1.25rem;
        font-weight: 800;
        color: #2563EB;
        display: block;
    }

    .stat-label {
        font-size: 0.6875rem;
        font-weight: 700;
        color: #64748B;
        text-transform: uppercase;
    }

    .health-alert {
        background: #FFF7ED;
        border: 1px solid #FFEDD5;
        color: #9A3412;
        padding: 1rem;
        border-radius: 0.75rem;
        font-size: 0.875rem;
        display: flex;
        gap: 0.75rem;
    }

    .attendance-dots {
        display: flex;
        flex-wrap: wrap;
        gap: 0.5rem;
    }

    .attendance-dot {
        width: 40px;
        height: 40px;
        background: #EFF6FF;
        border: 1px solid #DBEAFE;
        border-radius: 0.5rem;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
    }

    .dot-day {
        font-size: 0.75rem;
        font-weight: 800;
        color: #2563EB;
    }

    .dot-month {
        font-size: 0.625rem;
        color: #60A5FA;
        text-transform: uppercase;
        font-weight: 700;
    }

    .payment-table {
        width: 100%;
        border-collapse: collapse;
    }

    .payment-table th {
        text-align: left;
        font-size: 0.75rem;
        font-weight: 700;
        color: #94A3B8;
        text-transform: uppercase;
        padding: 0.75rem 0;
        border-bottom: 1px solid #F1F5F9;
    }

    .payment-table td {
        padding: 1rem 0;
        font-size: 0.875rem;
        color: #1E293B;
        font-weight: 600;
        border-bottom: 1px solid #F8FAFC;
    }

    .status-pill {
        display: inline-flex;
        align-items: center;
        gap: 0.375rem;
        padding: 0.25rem 0.625rem;
        border-radius: 1rem;
        font-size: 0.75rem;
        font-weight: 700;
    }

    .status-paid { background: #DCFCE7; color: #166534; }
    .status-pending { background: #FEF9C3; color: #854D0E; }
    .status-late { background: #FEE2E2; color: #991B1B; }

    .belt-bg-Branca { background: #f3f4f6; color: #1f2937; }
    .belt-bg-Cinza { background: #94a3b8; color: #ffffff; }
    .belt-bg-Cinza-Branca { background: linear-gradient(to bottom, #94a3b8 40%, #ffffff 40%, #ffffff 60%, #94a3b8 60%); color: #1f2937; border: 1px solid #94a3b8; }
    .belt-bg-Cinza-Preta { background: linear-gradient(to bottom, #94a3b8 40%, #000000 40%, #000000 60%, #94a3b8 60%); color: #ffffff; }
    .belt-bg-Amarela { background: #facc15; color: #854d0e; }
    .belt-bg-Amarela-Branca { background: linear-gradient(to bottom, #facc15 40%, #ffffff 40%, #ffffff 60%, #facc15 60%); color: #854d0e; border: 1px solid #facc15; }
    .belt-bg-Amarela-Preta { background: linear-gradient(to bottom, #facc15 40%, #000000 40%, #000000 60%, #facc15 60%); color: #ffffff; }
    .belt-bg-Laranja { background: #fb923c; color: #7c2d12; }
    .belt-bg-Laranja-Branca { background: linear-gradient(to bottom, #fb923c 40%, #ffffff 40%, #ffffff 60%, #fb923c 60%); color: #7c2d12; border: 1px solid #fb923c; }
    .belt-bg-Laranja-Preta { background: linear-gradient(to bottom, #fb923c 40%, #000000 40%, #000000 60%, #fb923c 60%); color: #ffffff; }
    .belt-bg-Verde { background: #22c55e; color: #ffffff; }
    .belt-bg-Verde-Branca { background: linear-gradient(to bottom, #22c55e 40%, #ffffff 40%, #ffffff 60%, #22c55e 60%); color: #166534; border: 1px solid #22c55e; }
    .belt-bg-Verde-Preta { background: linear-gradient(to bottom, #22c55e 40%, #000000 40%, #000000 60%, #22c55e 60%); color: #ffffff; }
    .belt-bg-Azul { background: #dbeafe; color: #1e40af; }
    .belt-bg-Roxa { background: #f3e8ff; color: #6b21a8; }
    .belt-bg-Marrom { background: #78350f; color: #ffffff; }
    .belt-bg-Preta { background: #111827; color: #ffffff; }
    
    .belt-indicator {
        display: inline-block;
        width: 10px;
        height: 10px;
        border-radius: 50%;
        margin-right: 4px;
    }
</style>

<div class="profile-container">
    <!-- Header -->
    <div class="profile-header">
        <div class="profile-info">
            <div class="profile-avatar">
                @if($user->photo)
                    <img src="{{ Storage::disk('public')->url($user->photo) }}" alt="{{ $user->name }}" style="width: 100%; height: 100%; object-fit: cover; border-radius: 50%;">
                @else
                    <i class="fas fa-user-ninja"></i>
                @endif
            </div>
            <div class="profile-name">
                <div style="display: flex; align-items: center; gap: 0.75rem;">
                    <h1>{{ $user->name }}</h1>
                    <span class="status-pill {{ $user->status == 'active' ? 'status-paid' : 'status-late' }}">
                        {{ $user->status == 'active' ? 'Ativo' : 'Inativo' }}
                    </span>
                </div>
                <div class="profile-meta">
                    <div class="belt-badge belt-bg-{{ str_replace('/', '-', $user->faixa) }}">
                        <span class="belt-indicator" style="background: {{ strtolower(explode('/', $user->faixa)[0]) }}"></span>
                        {{ $user->faixa }} - {{ $user->grau }}º Grau
                    </div>
                    <span>•</span>
                    <span><i class="fas fa-calendar-alt"></i> Membro desde {{ $user->start_date->format('M Y') }}</span>
                </div>
            </div>
        </div>
        <div class="header-actions" style="display: flex; gap: 0.75rem;">
            <a href="{{ route('users.edit', $user) }}" class="btn" style="background: #F1F5F9; color: #475569; font-weight: 700;">
                <i class="fas fa-edit"></i> Editar Perfil
            </a>
            <a href="{{ route('users.index') }}" class="btn" style="background: white; border: 1px solid #E2E8F0; color: #64748B;">
                <i class="fas fa-arrow-left"></i> Voltar
            </a>
        </div>
    </div>

    <div class="grid-layout">
        <!-- Sidebar -->
        <div class="sidebar-info">
            <!-- Stats -->
            <div class="stats-grid">
                <div class="stat-box">
                    <span class="stat-value">{{ $user->attendances->count() }}</span>
                    <span class="stat-label">Aulas</span>
                </div>
                <div class="stat-box">
                    <span class="stat-value">{{ $user->payments->where('status', 'paid')->count() }}</span>
                    <span class="stat-label">Pagos</span>
                </div>
                <div class="stat-box">
                    <span class="stat-value">{{ $user->start_date->diffInMonths(now()) }}</span>
                    <span class="stat-label">Meses</span>
                </div>
            </div>

            <!-- Basic Info -->
            <div class="info-card">
                <h2><i class="fas fa-info-circle"></i> Dados Pessoais</h2>
                <div class="data-row">
                    <span class="data-label">CPF</span>
                    <span class="data-value">{{ preg_replace('/(\d{3})(\d{3})(\d{3})(\d{2})/', '$1.$2.$3-$4', $user->cpf) }}</span>
                </div>
                <div class="data-row">
                    <span class="data-label">E-mail</span>
                    <span class="data-value">{{ $user->email }}</span>
                </div>
                <div class="data-row">
                    <span class="data-label">Telefone</span>
                    <span class="data-value">{{ $user->telefone }}</span>
                </div>
                <div class="data-row">
                    <span class="data-label">Data de Nascimento</span>
                    <span class="data-value">{{ $user->data_nascimento ? $user->data_nascimento->format('d/m/Y') : 'Não informada' }}</span>
                </div>
            </div>

            <!-- Plan Info -->
            <div class="info-card" style="border-left: 4px solid #3B82F6;">
                <h2><i class="fas fa-wallet"></i> Plano e Cobrança</h2>
                <div class="data-row">
                    <span class="data-label">Plano Atual</span>
                    <span class="data-value" style="color: #2563EB;">{{ $user->plan ? $user->plan->name : 'Sem Plano' }}</span>
                </div>
                <div class="data-row">
                    <span class="data-label">Valor Mensal</span>
                    <span class="data-value">R$ {{ number_format($user->custom_price ?? ($user->plan ? $user->plan->price : 0), 2, ',', '.') }}</span>
                </div>
                <div class="data-row">
                    <span class="data-label">Dia do Vencimento</span>
                    <span class="data-value">Todo dia {{ $user->vencimento_mensalidade ?? '10' }}</span>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <div class="main-info">
            <!-- Health/Notes -->
            @if($user->notes)
            <div class="info-card">
                <h2><i class="fas fa-notes-medical"></i> Observações Importantes</h2>
                <div class="health-alert">
                    <i class="fas fa-exclamation-triangle" style="margin-top: 3px;"></i>
                    <div>
                        <strong>Aviso Médico/Geral:</strong><br>
                        {{ $user->notes }}
                    </div>
                </div>
            </div>
            @endif

            <!-- Attendance -->
            <div class="info-card">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.25rem;">
                    <h2 style="margin-bottom: 0;"><i class="fas fa-calendar-check"></i> Frequência Recente</h2>
                    <span style="font-size: 0.75rem; color: #64748B; font-weight: 600;">Últimas 15 aulas</span>
                </div>
                <div class="attendance-dots">
                    @forelse($user->attendances->sortByDesc('date')->take(15) as $attendance)
                        <div class="attendance-dot">
                            <span class="dot-day">{{ $attendance->date->format('d') }}</span>
                            <span class="dot-month">{{ $attendance->date->translatedFormat('M') }}</span>
                        </div>
                    @empty
                        <div style="padding: 1rem; color: #94A3B8; font-style: italic; font-size: 0.875rem;">
                            Nenhuma presença registrada ainda.
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Financial History -->
            <div class="info-card">
                <h2><i class="fas fa-history"></i> Histórico de Pagamentos</h2>
                <table class="payment-table">
                    <thead>
                        <tr>
                            <th>Referência</th>
                            <th>Vencimento</th>
                            <th>Valor</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($user->payments->sortByDesc('due_date')->take(10) as $payment)
                        <tr>
                            <td>{{ $payment->reference_month }}</td>
                            <td>{{ $payment->due_date->format('d/m/Y') }}</td>
                            <td>R$ {{ number_format($payment->amount, 2, ',', '.') }}</td>
                            <td>
                                <span class="status-pill {{ $payment->status == 'paid' ? 'status-paid' : ($payment->due_date < now() ? 'status-late' : 'status-pending') }}">
                                    {{ $payment->status == 'paid' ? 'Pago' : ($payment->due_date < now() ? 'Atrasado' : 'Pendente') }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" style="text-align: center; color: #94A3B8; padding: 2rem;">Nenhum pagamento registrado.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
