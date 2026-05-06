@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto py-8">
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-slate-900">Meu Histórico de Pagamentos</h1>
        <p class="text-slate-500">Acompanhe suas mensalidades e status financeiro.</p>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <table class="w-full text-left border-collapse">
            <thead class="bg-slate-50">
                <tr>
                    <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Mês de Referência</th>
                    <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Vencimento</th>
                    <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Valor</th>
                    <th class="px-6 py-4 text-xs font-bold text-slate-500 uppercase tracking-wider">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($payments as $payment)
                <tr class="hover:bg-slate-50 transition-colors">
                    <td class="px-6 py-4">
                        <span class="font-medium text-slate-900">{{ $payment->reference_month ?? $payment->due_date->format('m/Y') }}</span>
                    </td>
                    <td class="px-6 py-4 text-slate-600">
                        {{ $payment->due_date->format('d/m/Y') }}
                    </td>
                    <td class="px-6 py-4 text-slate-900 font-bold">
                        R$ {{ number_format($payment->amount, 2, ',', '.') }}
                    </td>
                    <td class="px-6 py-4">
                        @if($payment->status === 'paid')
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-green-100 text-green-700">
                                <span class="material-symbols-outlined text-sm">check_circle</span>
                                Pago
                            </span>
                        @elseif($payment->status === 'late')
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-red-100 text-red-700">
                                <span class="material-symbols-outlined text-sm">warning</span>
                                Atrasado
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-yellow-100 text-yellow-700">
                                <span class="material-symbols-outlined text-sm">schedule</span>
                                Pendente
                            </span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-6 py-12 text-center text-slate-400 italic">
                        Nenhum registro de pagamento encontrado.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
