@extends('layouts.app')

@section('content')
<div class="max-w-[1600px] mx-auto">
    <div class="mb-6 flex flex-col md:flex-row justify-between items-start md:items-end gap-4 no-print">
        <div>
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-rose-500/10 border border-rose-500/20 text-rose-400 text-xs font-bold uppercase tracking-wider mb-2">
                <span class="w-2 h-2 rounded-full bg-rose-500 animate-pulse"></span>
                Relatório de Faturamento
            </div>
            <h1 class="font-['Outfit'] font-black text-2xl md:text-4xl text-white tracking-tight">Competência: {{ \Carbon\Carbon::parse($month . '-01')->translatedFormat('F Y') }}</h1>
        </div>
        <div>
            <button onclick="window.print()" class="bg-gradient-to-r from-rose-600 to-rose-700 text-white font-bold py-2.5 px-5 rounded-xl shadow-lg hover:shadow-rose-600/30 transition-all flex items-center gap-2 text-sm">
                <i class="fas fa-print"></i> Imprimir Relatório
            </button>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
        <div class="bg-[#111726] rounded-2xl p-6 border border-emerald-500/20 shadow-xl flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-emerald-500/10 border border-emerald-500/30 flex items-center justify-center text-emerald-400 text-xl">
                <i class="fas fa-hand-holding-usd"></i>
            </div>
            <div>
                <p class="text-xs text-slate-400 uppercase font-semibold tracking-wider">Total Recebido</p>
                <h3 class="font-['Outfit'] font-extrabold text-2xl md:text-3xl text-emerald-400">R$ {{ number_format($payments->where('status', 'paid')->sum('amount'), 2, ',', '.') }}</h3>
            </div>
        </div>

        <div class="bg-[#111726] rounded-2xl p-6 border border-amber-500/20 shadow-xl flex items-center gap-4">
            <div class="w-12 h-12 rounded-xl bg-amber-500/10 border border-amber-500/30 flex items-center justify-center text-amber-400 text-xl">
                <i class="fas fa-clock"></i>
            </div>
            <div>
                <p class="text-xs text-slate-400 uppercase font-semibold tracking-wider">Pendente / Atrasado</p>
                <h3 class="font-['Outfit'] font-extrabold text-2xl md:text-3xl text-amber-400">R$ {{ number_format($payments->where('status', '!=', 'paid')->sum('amount'), 2, ',', '.') }}</h3>
            </div>
        </div>
    </div>

    <div class="bg-[#111726] rounded-2xl border border-white/10 shadow-xl overflow-hidden p-6">
        <h3 class="font-['Outfit'] font-bold text-xl text-white mb-4">Detalhamento de Pagamentos</h3>
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-white/10 text-slate-400 text-xs font-bold uppercase tracking-wider bg-[#0d1320]/60">
                        <th class="py-3.5 px-4">Aluno</th>
                        <th class="py-3.5 px-4">Vencimento</th>
                        <th class="py-3.5 px-4">Valor</th>
                        <th class="py-3.5 px-4">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5">
                    @foreach($payments as $payment)
                        <tr class="hover:bg-white/5 transition-colors">
                            <td class="py-3.5 px-4 font-semibold text-white text-sm">{{ $payment->user->name }}</td>
                            <td class="py-3.5 px-4 text-xs text-slate-300">{{ $payment->due_date->format('d/m/Y') }}</td>
                            <td class="py-3.5 px-4 font-['Outfit'] font-bold text-white text-sm">R$ {{ number_format($payment->amount, 2, ',', '.') }}</td>
                            <td class="py-3.5 px-4">
                                @if($payment->status == 'paid')
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-bold bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 uppercase">Pago</span>
                                @elseif($payment->status == 'pending')
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-bold bg-amber-500/10 border border-amber-500/30 text-amber-400 uppercase">Pendente</span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-bold bg-rose-500/10 border border-rose-500/30 text-rose-400 uppercase">Atrasado</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<style>
    @media print {
        .no-print, aside, header, #sidebar-backdrop { display: none !important; }
        main { margin: 0 !important; padding: 0 !important; background: white !important; color: black !important; }
        .bg-\[\#111726\] { background: white !important; border: 1px solid #ccc !important; color: black !important; }
        .text-white { color: black !important; }
        .text-slate-400 { color: #555 !important; }
    }
</style>
@endsection
