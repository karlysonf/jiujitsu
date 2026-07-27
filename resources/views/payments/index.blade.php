@extends('layouts.app')

@section('content')
<!-- Content Canvas -->
<div class="max-w-[1600px] mx-auto">
    <!-- Page Header -->
    <div class="mb-6 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-rose-500/10 border border-rose-500/20 text-rose-400 text-xs font-bold uppercase tracking-wider mb-2">
                <span class="w-2 h-2 rounded-full bg-rose-500 animate-pulse"></span>
                Módulo Financeiro
            </div>
            <h2 class="font-['Outfit'] font-black text-2xl md:text-4xl text-white tracking-tight">Gestão Financeira & Caixa</h2>
            <p class="text-slate-400 text-xs md:text-sm mt-0.5">Controle de mensalidades, cobranças avulsas e fluxo de caixa.</p>
        </div>
    </div>

    <div class="grid grid-cols-12 gap-6">
        <!-- Left Column: Dar Baixa (Form) -->
        <div class="col-span-12 lg:col-span-4">
            <section class="bg-[#111726] rounded-2xl border border-white/10 p-6 shadow-xl sticky top-24">
                <div class="flex items-center justify-between mb-6 pb-4 border-b border-white/10">
                    <h3 class="font-['Outfit'] font-bold text-lg text-white flex items-center gap-2">
                        <span class="material-symbols-outlined text-rose-500">add_circle</span>
                        Dar Baixa em Pagamento
                    </h3>
                </div>

                <form action="{{ route('payments.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-xs font-bold uppercase text-slate-400 mb-1.5 ml-1">Selecionar Aluno</label>
                        <div class="relative">
                            <select name="user_id" id="user_id_select" class="w-full h-12 pl-10 pr-4 rounded-xl border border-white/10 bg-[#090d16] text-white focus:outline-none focus:border-rose-500 focus:ring-1 focus:ring-rose-500 transition-all appearance-none text-sm" required>
                                <option value="" class="bg-[#090d16]">Buscar por nome...</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" class="bg-[#090d16]">{{ $user->name }} (Faixa {{ $user->belt }})</option>
                                @endforeach
                            </select>
                            <span class="material-symbols-outlined absolute left-3 top-3.5 text-slate-500 text-lg">search</span>
                        </div>
                    </div>

                    <div id="open_payments_container" class="hidden">
                        <label class="block text-xs font-bold uppercase text-slate-400 mb-1.5 ml-1">Mensalidade em Aberto</label>
                        <div class="relative">
                            <select name="payment_id" id="payment_id_select" class="w-full h-12 pl-10 pr-4 rounded-xl border border-white/10 bg-[#090d16] text-white focus:outline-none focus:border-rose-500 focus:ring-1 focus:ring-rose-500 transition-all appearance-none text-sm">
                                <option value="" class="bg-[#090d16]">Cobrança Avulsa / Novo Recebimento</option>
                            </select>
                            <span class="material-symbols-outlined absolute left-3 top-3.5 text-cyan-400 text-lg">receipt_long</span>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase text-slate-400 mb-1.5 ml-1">Valor do Pagamento</label>
                        <div class="relative">
                            <input name="amount" class="w-full h-12 pl-10 pr-4 rounded-xl border border-white/10 bg-[#090d16] text-white placeholder-slate-500 focus:outline-none focus:border-rose-500 focus:ring-1 focus:ring-rose-500 transition-all text-sm font-semibold" placeholder="R$ 0,00" type="text" required />
                            <span class="material-symbols-outlined absolute left-3 top-3.5 text-rose-500 text-lg">payments</span>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold uppercase text-slate-400 mb-1.5 ml-1">Forma de Recebimento</label>
                        <div class="grid grid-cols-3 gap-2">
                            <label class="cursor-pointer">
                                <input name="payment_method" value="pix" checked class="peer hidden" type="radio" />
                                <div class="flex flex-col items-center justify-center p-3 rounded-xl border border-white/10 bg-[#090d16] text-slate-300 peer-checked:bg-rose-600 peer-checked:text-white peer-checked:border-rose-500 transition-all hover:bg-white/5">
                                    <span class="material-symbols-outlined mb-1 text-lg">qr_code_2</span>
                                    <span class="text-[10px] font-bold uppercase">Pix</span>
                                </div>
                            </label>
                            <label class="cursor-pointer">
                                <input name="payment_method" value="credit_card" class="peer hidden" type="radio" />
                                <div class="flex flex-col items-center justify-center p-3 rounded-xl border border-white/10 bg-[#090d16] text-slate-300 peer-checked:bg-rose-600 peer-checked:text-white peer-checked:border-rose-500 transition-all hover:bg-white/5">
                                    <span class="material-symbols-outlined mb-1 text-lg">credit_card</span>
                                    <span class="text-[10px] font-bold uppercase">Cartão</span>
                                </div>
                            </label>
                            <label class="cursor-pointer">
                                <input name="payment_method" value="cash" class="peer hidden" type="radio" />
                                <div class="flex flex-col items-center justify-center p-3 rounded-xl border border-white/10 bg-[#090d16] text-slate-300 peer-checked:bg-rose-600 peer-checked:text-white peer-checked:border-rose-500 transition-all hover:bg-white/5">
                                    <span class="material-symbols-outlined mb-1 text-lg">payments</span>
                                    <span class="text-[10px] font-bold uppercase">Dinheiro</span>
                                </div>
                            </label>
                        </div>
                    </div>

                    <input type="hidden" name="due_date" id="due_date_input" value="{{ now()->format('Y-m-d') }}">
                    <input type="hidden" name="payment_date" value="{{ now()->format('Y-m-d') }}">
                    <input type="hidden" name="reference_month" id="reference_month_input" value="{{ now()->format('Y-m') }}">

                    <div class="pt-2">
                        <button class="w-full h-13 bg-gradient-to-r from-rose-600 to-rose-700 text-white rounded-xl font-['Outfit'] font-bold text-base flex items-center justify-center gap-2 hover:shadow-lg hover:shadow-rose-600/30 transition-all active:scale-[0.98]" type="submit">
                            <span class="material-symbols-outlined">task_alt</span>
                            Confirmar Recebimento
                        </button>
                        <p class="text-[11px] text-center text-slate-500 mt-2.5 font-medium">O comprovante será registrado automaticamente no sistema.</p>
                    </div>
                </form>
            </section>
        </div>

        <!-- Right Column: Histórico (Table/List) -->
        <div class="col-span-12 lg:col-span-8 space-y-6">
            <section class="bg-[#111726] rounded-2xl border border-white/10 shadow-xl overflow-hidden">
                <div class="p-5 border-b border-white/10 flex items-center justify-between bg-[#182234]/50">
                    <h3 class="font-['Outfit'] font-bold text-lg text-white flex items-center gap-2">
                        <span class="material-symbols-outlined text-cyan-400">history</span>
                        Histórico Recente de Pagamentos
                    </h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-white/10 text-slate-400 text-xs font-bold uppercase tracking-wider bg-[#0d1320]/50">
                                <th class="px-6 py-3.5">Aluno</th>
                                <th class="px-6 py-3.5">Data</th>
                                <th class="px-6 py-3.5">Método</th>
                                <th class="px-6 py-3.5">Valor</th>
                                <th class="px-6 py-3.5 text-right">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/5">
                            @foreach($payments as $payment)
                            <tr class="hover:bg-white/5 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-9 h-9 rounded-xl bg-[#182234] border border-white/10 flex items-center justify-center font-bold text-xs text-rose-400">
                                            {{ strtoupper(substr($payment->user->name, 0, 2)) }}
                                        </div>
                                        <div>
                                            <p class="font-semibold text-sm text-white">{{ $payment->user->name }}</p>
                                            <p class="text-[11px] text-slate-400">
                                                {{ $payment->user->plan->name ?? 'Sem Plano' }} • Faixa {{ $payment->user->belt }}
                                            </p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-xs text-slate-300">
                                    {{ $payment->payment_date ? $payment->payment_date->format('d/m, H:i') : $payment->due_date->format('d/m/Y') }}
                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center gap-1.5 text-xs font-medium text-slate-300 bg-[#182234] px-2.5 py-1 rounded-lg border border-white/5">
                                        @if($payment->payment_method == 'pix')
                                            <span class="material-symbols-outlined text-sm text-emerald-400">qr_code_2</span> Pix
                                        @elseif($payment->payment_method == 'credit_card')
                                            <span class="material-symbols-outlined text-sm text-cyan-400">credit_card</span> Cartão
                                        @else
                                            <span class="material-symbols-outlined text-sm text-amber-400">payments</span> Dinheiro
                                        @endif
                                    </span>
                                </td>
                                <td class="px-6 py-4 font-['Outfit'] font-bold text-white text-base">
                                    R$ {{ number_format($payment->amount, 2, ',', '.') }}
                                </td>
                                <td class="px-6 py-4 text-right">
                                    @if($payment->status == 'paid')
                                        <span class="inline-flex items-center px-3 py-1 rounded-full text-[10px] font-bold bg-emerald-500/10 border border-emerald-500/30 text-emerald-400 uppercase tracking-wider">Confirmado</span>
                                    @else
                                        <div class="flex flex-col items-end gap-1">
                                            <span class="inline-flex items-center px-3 py-1 rounded-full text-[10px] font-bold bg-amber-500/10 border border-amber-500/30 text-amber-400 uppercase tracking-wider">Pendente</span>
                                            @if($payment->asaas_invoice_url)
                                                <a href="{{ $payment->asaas_invoice_url }}" target="_blank" class="text-[10px] text-cyan-400 hover:text-cyan-300 hover:underline flex items-center gap-1 mt-1 font-semibold">
                                                    <span class="material-symbols-outlined text-[10px]">qr_code_2</span> Pagar / Link
                                                </a>
                                            @endif
                                        </div>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="p-4 bg-[#0d1320] border-t border-white/10 flex items-center justify-between text-xs text-slate-400">
                    <p class="font-medium">Mostrando {{ $payments->count() }} de {{ $payments->total() }} registros</p>
                    <div class="flex items-center gap-2">
                        {{ $payments->links() }}
                    </div>
                </div>
            </section>

            <!-- Summary Mini-Bento -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="bg-[#111726] p-5 rounded-2xl border border-white/10 shadow-xl">
                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">Total Hoje</p>
                    <p class="font-['Outfit'] font-extrabold text-2xl text-emerald-400">R$ {{ number_format($stats['total_today'], 2, ',', '.') }}</p>
                </div>
                <div class="bg-[#111726] p-5 rounded-2xl border border-white/10 shadow-xl">
                    <p class="text-xs font-semibold text-slate-400 uppercase tracking-wider mb-1">Pendente</p>
                    <p class="font-['Outfit'] font-extrabold text-2xl text-rose-400">R$ {{ number_format($stats['total_pending'], 2, ',', '.') }}</p>
                </div>
                <div class="bg-gradient-to-br from-cyan-950 via-[#111726] to-[#182234] p-5 rounded-2xl border border-cyan-500/30 shadow-xl text-white relative overflow-hidden">
                    <div class="relative z-10">
                        <p class="text-xs font-semibold text-cyan-300 uppercase tracking-wider mb-1">Taxa de Adimplência</p>
                        <p class="font-['Outfit'] font-extrabold text-2xl text-white">{{ $stats['adimplencia'] }}%</p>
                    </div>
                    <span class="material-symbols-outlined absolute -right-2 -bottom-2 text-6xl text-cyan-500/10">trending_up</span>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function () {
    const studentSelect = document.getElementById('user_id_select');
    const openPaymentsContainer = document.getElementById('open_payments_container');
    const paymentSelect = document.getElementById('payment_id_select');
    const amountInput = document.querySelector('input[name="amount"]');
    const dueDateInput = document.getElementById('due_date_input');
    const referenceMonthInput = document.getElementById('reference_month_input');

    let openPayments = [];

    const currentDateStr = "{{ now()->format('Y-m-d') }}";
    const currentMonthStr = "{{ now()->format('Y-m') }}";

    studentSelect.addEventListener('change', function () {
        const userId = this.value;
        if (!userId) {
            openPaymentsContainer.classList.add('hidden');
            resetFields();
            return;
        }

        fetch(`/payments/open-by-user/${userId}`)
            .then(response => response.json())
            .then(data => {
                openPayments = data;
                paymentSelect.innerHTML = '';

                if (openPayments.length > 0) {
                    const avulsoOpt = document.createElement('option');
                    avulsoOpt.value = '';
                    avulsoOpt.textContent = 'Cobrança Avulsa / Novo Recebimento';
                    avulsoOpt.className = 'bg-[#090d16]';
                    paymentSelect.appendChild(avulsoOpt);

                    openPayments.forEach(p => {
                        const opt = document.createElement('option');
                        opt.value = p.id;
                        opt.className = 'bg-[#090d16]';
                        const formattedAmount = parseFloat(p.amount).toLocaleString('pt-BR', { style: 'currency', currency: 'BRL' });
                        opt.textContent = `Mensalidade de ${p.reference_month_formatted} (Vence em ${p.due_date_formatted}) - ${formattedAmount}`;
                        paymentSelect.appendChild(opt);
                    });

                    paymentSelect.value = openPayments[0].id;
                    updateFieldsForPayment(openPayments[0]);
                } else {
                    const opt = document.createElement('option');
                    opt.value = '';
                    opt.textContent = 'Nenhuma mensalidade em aberto (Novo recebimento avulso)';
                    opt.className = 'bg-[#090d16]';
                    paymentSelect.appendChild(opt);
                    resetFields();
                }
                
                openPaymentsContainer.classList.remove('hidden');
            })
            .catch(error => {
                console.error('Erro ao buscar mensalidades:', error);
                openPaymentsContainer.classList.add('hidden');
                resetFields();
            });
    });

    paymentSelect.addEventListener('change', function () {
        const paymentId = this.value;
        if (!paymentId) {
            resetFields();
        } else {
            const selected = openPayments.find(p => p.id == paymentId);
            if (selected) {
                updateFieldsForPayment(selected);
            }
        }
    });

    function updateFieldsForPayment(payment) {
        amountInput.value = payment.amount;
        dueDateInput.value = payment.due_date;
        referenceMonthInput.value = payment.reference_month;
    }

    function resetFields() {
        amountInput.value = '';
        dueDateInput.value = currentDateStr;
        referenceMonthInput.value = currentMonthStr;
    }
});
</script>
@endsection
