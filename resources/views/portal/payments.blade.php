@extends('layouts.app')

@section('content')
<div class="max-w-4xl mx-auto py-6">
    <div class="mb-6">
        <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-cyan-500/10 border border-cyan-500/20 text-cyan-400 text-xs font-bold uppercase tracking-wider mb-2">
            <span class="w-2 h-2 rounded-full bg-cyan-400 animate-pulse"></span>
            Financeiro Pessoal
        </div>
        <h1 class="font-['Outfit'] font-black text-2xl md:text-3xl text-white tracking-tight">Meu Histórico de Pagamentos</h1>
        <p class="text-slate-400 text-xs md:text-sm mt-0.5">Acompanhe suas mensalidades, vencimentos e links de pagamento.</p>
    </div>

    <div class="bg-[#111726] rounded-2xl shadow-xl border border-white/10 overflow-hidden">
        <table class="w-full text-left border-collapse">
            <thead>
                <tr class="border-b border-white/10 text-slate-400 text-xs font-bold uppercase tracking-wider bg-[#0d1320]/60">
                    <th class="px-6 py-4">Mês de Referência</th>
                    <th class="px-6 py-4">Vencimento</th>
                    <th class="px-6 py-4">Valor</th>
                    <th class="px-6 py-4">Status / Ações</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-white/5">
                @forelse($payments as $payment)
                <tr class="hover:bg-white/5 transition-colors">
                    <td class="px-6 py-4">
                        <span class="font-semibold text-white text-sm">{{ $payment->reference_month ?? $payment->due_date->format('m/Y') }}</span>
                    </td>
                    <td class="px-6 py-4 text-xs text-slate-300">
                        {{ $payment->due_date->format('d/m/Y') }}
                    </td>
                    <td class="px-6 py-4 font-['Outfit'] font-extrabold text-white text-base">
                        R$ {{ number_format($payment->amount, 2, ',', '.') }}
                    </td>
                    <td class="px-6 py-4">
                        @if($payment->status === 'paid')
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-emerald-500/10 border border-emerald-500/30 text-emerald-400">
                                <span class="material-symbols-outlined text-sm">check_circle</span>
                                Pago
                            </span>
                        @elseif($payment->status === 'late')
                            <div class="flex flex-col items-start gap-1.5">
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-rose-500/10 border border-rose-500/30 text-rose-400 uppercase">
                                    <span class="material-symbols-outlined text-sm">warning</span>
                                    Atrasado
                                </span>
                                @if($payment->asaas_invoice_url)
                                    <a href="{{ $payment->asaas_invoice_url }}" target="_blank" class="mt-1 inline-flex items-center gap-1 px-3 py-1.5 text-xs font-bold bg-gradient-to-r from-rose-600 to-rose-700 text-white hover:shadow-lg hover:shadow-rose-600/30 transition-all rounded-xl">
                                        <span class="material-symbols-outlined text-xs">qr_code_2</span> Pagar Pix / Cartão
                                    </a>
                                @endif
                            </div>
                        @else
                            <div class="flex flex-col items-start gap-1.5">
                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-bold bg-amber-500/10 border border-amber-500/30 text-amber-400 uppercase">
                                    <span class="material-symbols-outlined text-sm">schedule</span>
                                    Pendente
                                </span>
                                @if($payment->asaas_invoice_url)
                                    <a href="{{ $payment->asaas_invoice_url }}" target="_blank" class="mt-1 inline-flex items-center gap-1 px-3 py-1.5 text-xs font-bold bg-gradient-to-r from-rose-600 to-rose-700 text-white hover:shadow-lg hover:shadow-rose-600/30 transition-all rounded-xl">
                                        <span class="material-symbols-outlined text-xs">qr_code_2</span> Pagar Pix / Cartão
                                    </a>
                                @endif
                            </div>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" class="px-6 py-12 text-center text-slate-500 italic">
                        Nenhum registro de pagamento encontrado.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
