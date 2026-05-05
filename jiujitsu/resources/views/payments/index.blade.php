@extends('layouts.app')

@section('content')
<!-- Content Canvas -->
<div class="p-gutter max-w-[1600px] mx-auto">
    <!-- Page Header -->
    <div class="mb-lg">
        <h2 class="font-headline-lg text-headline-lg text-primary">Gestão Financeira</h2>
        <p class="font-body-md text-body-md text-outline">Controle de mensalidades e fluxo de caixa da academia.</p>
    </div>

    <div class="grid grid-cols-12 gap-gutter">
        <!-- Left Column: Dar Baixa (Form) -->
        <div class="col-span-12 lg:col-span-4">
            <section class="bg-white rounded-xl border border-slate-200 p-md shadow-sm sticky top-24">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="font-label-bold text-label-bold text-primary flex items-center gap-2">
                        <span class="material-symbols-outlined text-on-tertiary-container">add_circle</span>
                        Dar Baixa em Pagamento
                    </h3>
                </div>

                <form action="{{ route('payments.store') }}" method="POST" class="space-y-md">
                    @csrf
                    <div>
                        <label class="block text-label-sm font-label-sm text-outline-variant mb-1 ml-1">Selecionar Aluno</label>
                        <div class="relative">
                            <select name="user_id" class="w-full h-12 pl-10 pr-4 rounded-lg border border-slate-200 bg-surface-container-lowest focus:ring-2 focus:ring-on-tertiary-container focus:border-on-tertiary-container transition-all appearance-none font-body-md text-body-md" required>
                                <option value="">Buscar por nome...</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }} ({{ $user->belt }})</option>
                                @endforeach
                            </select>
                            <span class="material-symbols-outlined absolute left-3 top-3 text-outline">search</span>
                        </div>
                    </div>

                    <div>
                        <label class="block text-label-sm font-label-sm text-outline-variant mb-1 ml-1">Valor do Pagamento</label>
                        <div class="relative">
                            <input name="amount" class="w-full h-12 pl-10 pr-4 rounded-lg border border-slate-200 bg-surface-container-lowest focus:ring-2 focus:ring-on-tertiary-container focus:border-on-tertiary-container transition-all font-body-md text-body-md" placeholder="R$ 0,00" type="text" required />
                            <span class="material-symbols-outlined absolute left-3 top-3 text-outline">payments</span>
                        </div>
                    </div>

                    <div>
                        <label class="block text-label-sm font-label-sm text-outline-variant mb-1 ml-1">Forma de Recebimento</label>
                        <div class="grid grid-cols-3 gap-2">
                            <label class="cursor-pointer">
                                <input name="payment_method" value="pix" checked class="peer hidden" type="radio" />
                                <div class="flex flex-col items-center justify-center p-3 rounded-lg border border-slate-200 bg-white peer-checked:bg-on-tertiary-container peer-checked:text-white peer-checked:border-on-tertiary-container transition-all text-slate-600">
                                    <span class="material-symbols-outlined mb-1">qr_code_2</span>
                                    <span class="text-[10px] font-bold">Pix</span>
                                </div>
                            </label>
                            <label class="cursor-pointer">
                                <input name="payment_method" value="credit_card" class="peer hidden" type="radio" />
                                <div class="flex flex-col items-center justify-center p-3 rounded-lg border border-slate-200 bg-white peer-checked:bg-on-tertiary-container peer-checked:text-white peer-checked:border-on-tertiary-container transition-all text-slate-600">
                                    <span class="material-symbols-outlined mb-1">credit_card</span>
                                    <span class="text-[10px] font-bold">Cartão</span>
                                </div>
                            </label>
                            <label class="cursor-pointer">
                                <input name="payment_method" value="cash" class="peer hidden" type="radio" />
                                <div class="flex flex-col items-center justify-center p-3 rounded-lg border border-slate-200 bg-white peer-checked:bg-on-tertiary-container peer-checked:text-white peer-checked:border-on-tertiary-container transition-all text-slate-600">
                                    <span class="material-symbols-outlined mb-1">payments</span>
                                    <span class="text-[10px] font-bold">Dinheiro</span>
                                </div>
                            </label>
                        </div>
                    </div>

                    <input type="hidden" name="due_date" value="{{ now()->format('Y-m-d') }}">
                    <input type="hidden" name="payment_date" value="{{ now()->format('Y-m-d') }}">
                    <input type="hidden" name="reference_month" value="{{ now()->format('Y-m') }}">

                    <div class="pt-4">
                        <button class="w-full h-14 bg-primary text-white rounded-lg font-headline-md text-body-lg flex items-center justify-center gap-3 hover:bg-slate-800 transition-all shadow-md active:scale-[0.98]" type="submit">
                            <span class="material-symbols-outlined">task_alt</span>
                            Confirmar Recebimento
                        </button>
                        <p class="text-[11px] text-center text-outline mt-3 font-medium">O comprovante será enviado automaticamente para o aluno.</p>
                    </div>
                </form>
            </section>
        </div>

        <!-- Right Column: Histórico (Table/List) -->
        <div class="col-span-12 lg:col-span-8">
            <section class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="p-md border-b border-slate-100 flex items-center justify-between bg-slate-50/50">
                    <h3 class="font-label-bold text-label-bold text-primary flex items-center gap-2">
                        <span class="material-symbols-outlined text-on-tertiary-container">history</span>
                        Histórico Recente de Pagamentos
                    </h3>
                    <button class="text-label-sm font-label-bold text-on-tertiary-container flex items-center gap-1 hover:underline">
                        <span class="material-symbols-outlined text-sm">download</span>
                        Exportar Relatório
                    </button>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-slate-50">
                                <th class="px-6 py-4 text-label-sm font-label-bold text-outline uppercase tracking-wider">Aluno</th>
                                <th class="px-6 py-4 text-label-sm font-label-bold text-outline uppercase tracking-wider">Data</th>
                                <th class="px-6 py-4 text-label-sm font-label-bold text-outline uppercase tracking-wider">Método</th>
                                <th class="px-6 py-4 text-label-sm font-label-bold text-outline uppercase tracking-wider">Valor</th>
                                <th class="px-6 py-4 text-label-sm font-label-bold text-outline uppercase tracking-wider text-right">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @foreach($payments as $payment)
                            <tr class="hover:bg-slate-50/80 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-full bg-slate-100 flex items-center justify-center font-bold text-xs text-slate-600">
                                            {{ strtoupper(substr($payment->user->name, 0, 2)) }}
                                        </div>
                                        <div>
                                            <p class="font-label-bold text-label-bold text-slate-900">{{ $payment->user->name }}</p>
                                            <p class="text-[10px] text-slate-500 font-medium">
                                                {{ $payment->user->plan->name ?? 'Sem Plano' }} • {{ $payment->user->belt }}
                                            </p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-body-md text-label-sm text-slate-600">
                                    {{ $payment->payment_date ? $payment->payment_date->format('d/m, H:i') : $payment->due_date->format('d/m/Y') }}
                                </td>
                                <td class="px-6 py-4">
                                    <span class="flex items-center gap-1.5 text-label-sm font-medium text-slate-600">
                                        @if($payment->payment_method == 'pix')
                                            <span class="material-symbols-outlined text-sm">qr_code_2</span> Pix
                                        @elseif($payment->payment_method == 'credit_card')
                                            <span class="material-symbols-outlined text-sm">credit_card</span> Cartão
                                        @else
                                            <span class="material-symbols-outlined text-sm">payments</span> Dinheiro
                                        @endif
                                    </span>
                                </td>
                                <td class="px-6 py-4 font-label-bold text-label-bold text-slate-900">
                                    R$ {{ number_format($payment->amount, 2, ',', '.') }}
                                </td>
                                <td class="px-6 py-4 text-right">
                                    @if($payment->status == 'paid')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-black bg-emerald-100 text-emerald-700 uppercase">Confirmado</span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-black bg-amber-100 text-amber-700 uppercase">Pendente</span>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="p-4 bg-slate-50 border-t border-slate-100 flex items-center justify-between">
                    <p class="text-label-sm font-medium text-outline">Mostrando {{ $payments->count() }} de {{ $payments->total() }} registros</p>
                    <div class="flex items-center gap-2">
                        {{ $payments->links() }}
                    </div>
                </div>
            </section>

            <!-- Summary Mini-Bento -->
            <div class="grid grid-cols-3 gap-md mt-gutter">
                <div class="bg-white p-md rounded-xl border border-slate-200 shadow-sm">
                    <p class="text-label-sm font-medium text-outline mb-1">Total Hoje</p>
                    <p class="text-headline-md font-headline-md text-primary">R$ {{ number_format($stats['total_today'], 2, ',', '.') }}</p>
                </div>
                <div class="bg-white p-md rounded-xl border border-slate-200 shadow-sm">
                    <p class="text-label-sm font-medium text-outline mb-1">Pendente</p>
                    <p class="text-headline-md font-headline-md text-error">R$ {{ number_format($stats['total_pending'], 2, ',', '.') }}</p>
                </div>
                <div class="bg-on-tertiary-container p-md rounded-xl shadow-sm text-white relative overflow-hidden">
                    <div class="relative z-10">
                        <p class="text-label-sm font-medium opacity-80 mb-1">Taxa de Adimplência</p>
                        <p class="text-headline-md font-headline-md">{{ $stats['adimplencia'] }}%</p>
                    </div>
                    <span class="material-symbols-outlined absolute -right-2 -bottom-2 text-6xl opacity-10">trending_up</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
