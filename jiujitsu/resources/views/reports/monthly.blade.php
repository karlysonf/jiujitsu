@extends('layouts.app')

@section('content')
<div style="margin-bottom: 2.5rem; display: flex; justify-content: space-between; align-items: flex-end; flex-wrap: wrap; gap: 1rem;" class="no-print">
    <div>
        <h1 style="margin-bottom: 0.5rem;">Relatório de Faturamento</h1>
        <p>Competência: <strong>{{ \Carbon\Carbon::parse($month . '-01')->translatedFormat('F Y') }}</strong></p>
    </div>
    <div>
        <button onclick="window.print()" class="btn btn-primary">
            <i class="fas fa-print"></i> Imprimir Relatório
        </button>
    </div>
</div>

<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 1.5rem; margin-bottom: 2.5rem;">
    <div class="card stats-card hover-lift">
        <div>
            <div class="stats-icon" style="color: var(--secondary); background: #f0fdf4;">
                <i class="fas fa-hand-holding-usd"></i>
            </div>
            <div class="stats-label">Total Recebido</div>
        </div>
        <div class="stats-value">R$ {{ number_format($payments->where('status', 'paid')->sum('amount'), 2, ',', '.') }}</div>
    </div>
    <div class="card stats-card hover-lift">
        <div>
            <div class="stats-icon" style="color: var(--warning); background: #fffbeb;">
                <i class="fas fa-clock"></i>
            </div>
            <div class="stats-label">Pendente / Atrasado</div>
        </div>
        <div class="stats-value">R$ {{ number_format($payments->where('status', '!=', 'paid')->sum('amount'), 2, ',', '.') }}</div>
    </div>
</div>

<div class="card">
    <h3 style="margin-bottom: 1.5rem;">Detalhamento de Pagamentos</h3>
    <div class="table-container" style="border: none;">
        <table>
            <thead>
                <tr>
                    <th>Aluno</th>
                    <th>Vencimento</th>
                    <th>Valor</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($payments as $payment)
                    <tr>
                        <td style="font-weight: 600;">{{ $payment->user->name }}</td>
                        <td>{{ $payment->due_date->format('d/m/Y') }}</td>
                        <td>R$ {{ number_format($payment->amount, 2, ',', '.') }}</td>
                        <td>
                            <span class="badge {{ $payment->status == 'paid' ? 'badge-success' : ($payment->status == 'pending' ? 'badge-warning' : 'badge-danger') }}">
                                {{ $payment->status == 'paid' ? 'Pago' : ($payment->status == 'pending' ? 'Pendente' : 'Atrasado') }}
                            </span>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<style>
    @media print {
        .no-print, aside, .top-bar, .mobile-toggle { display: none !important; }
        main { margin: 0 !important; padding: 0 !important; }
        .card { box-shadow: none !important; border: 1px solid #eee !important; }
    }
</style>
@endsection
