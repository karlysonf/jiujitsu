<?php

namespace App\Services;

use App\Models\Payment;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PaymentService
{
    /**
     * Registra um pagamento de forma segura contra duplicidade.
     * 
     * @param array $data
     * @return Payment
     * @throws \Exception
     */
    public function registerPayment(array $data)
    {
        // Chave de idempotência (vinda do request ou gerada a partir dos dados)
        $idempotencyKey = $data['idempotency_key'] ?? $this->generateIdempotencyKey($data);

        // Lock Atômico (Cache Lock) para impedir processamento simultâneo
        return Cache::lock("payment_process_{$idempotencyKey}", 10)->block(5, function () use ($data, $idempotencyKey) {
            
            return DB::transaction(function () use ($data, $idempotencyKey) {
                $userId = $data['user_id'] ?? $data['student_id'] ?? null;

                // 1. Validação de Idempotência
                $existingPayment = Payment::where('idempotency_key', $idempotencyKey)
                    ->orWhere(function($query) use ($data) {
                        if (isset($data['gateway_transaction_id'])) {
                            $query->where('gateway_transaction_id', $data['gateway_transaction_id']);
                        }
                    })->first();

                if ($existingPayment) {
                    Log::info("Pagamento ignorado por duplicidade (Idempotency Key: {$idempotencyKey})");
                    return $existingPayment;
                }

                // 2. Busca por um pagamento existente para o aluno (por payment_id se fornecido, ou pelo mês de referência)
                $payment = null;
                if (!empty($data['payment_id'])) {
                    $payment = Payment::find($data['payment_id']);
                }

                if (!$payment) {
                    $payment = Payment::where('user_id', $userId)
                        ->where('reference_month', $data['reference_month'])
                        ->first();
                }

                if ($payment) {
                    if ($payment->status === 'paid') {
                        Log::info("Pagamento ignorado por duplicidade (Já pago para o mês de referência: {$data['reference_month']})");
                        return $payment;
                    }

                    // Atualiza a cobrança existente para 'paid' com o novo valor recebido
                    $payment->update([
                        'idempotency_key' => $idempotencyKey,
                        'amount' => $data['amount'],
                        'payment_date' => $data['payment_date'] ?? now(),
                        'status' => 'paid',
                        'payment_method' => $data['payment_method'],
                        'notes' => $data['notes'] ?? $payment->notes,
                        'gateway_transaction_id' => $data['gateway_transaction_id'] ?? $payment->gateway_transaction_id,
                    ]);

                    return $payment;
                }

                // 3. Se não houver cobrança pré-existente no mês de referência, cria um novo registro
                return Payment::create([
                    'idempotency_key' => $idempotencyKey,
                    'user_id' => $userId,
                    'amount' => $data['amount'],
                    'due_date' => $data['due_date'],
                    'payment_date' => $data['payment_date'] ?? now(),
                    'status' => 'paid',
                    'payment_method' => $data['payment_method'],
                    'reference_month' => $data['reference_month'],
                    'notes' => $data['notes'] ?? null,
                    'gateway_transaction_id' => $data['gateway_transaction_id'] ?? null,
                ]);
            });
        });
    }

    /**
     * Gera uma chave de idempotência baseada nos dados do pagamento se não fornecida.
     */
    protected function generateIdempotencyKey(array $data): string
    {
        return md5(implode('|', [
            $data['user_id'] ?? $data['student_id'] ?? '',
            $data['amount'],
            $data['reference_month'],
            $data['due_date']
        ]));
    }

    public function generateMonthlyBilling()
    {
        $activeStudents = User::role(['aluno', 'professor', 'instrutor'])->where('status', 'active')->get();
        $referenceMonth = now()->format('Y-m');

        foreach ($activeStudents as $student) {
            // Skip if inactive or has a "Cortesia" plan
            if ($student->status === 'inactive' || ($student->plan && $student->plan->name === 'Cortesia')) {
                continue;
            }

            $dueDay = $student->vencimento_mensalidade ?? 10;
            $clampedDay = min($dueDay, now()->daysInMonth);
            $dueDate = now()->day($clampedDay);

            DB::transaction(function () use ($student, $referenceMonth, $dueDate) {
                $lockKey = "billing_{$student->id}_{$referenceMonth}";
                
                Cache::lock($lockKey, 30)->get(function () use ($student, $referenceMonth, $dueDate) {
                    $payment = Payment::firstOrCreate(
                        [
                            'user_id' => $student->id,
                            'reference_month' => $referenceMonth,
                        ],
                        [
                            'amount' => $student->custom_price ?? ($student->plan ? $student->plan->price : 150.00),
                            'due_date' => $dueDate,
                            'status' => 'pending',
                        ]
                    );

                    if ($payment->wasRecentlyCreated) {
                        $asaas = app(\App\Services\AsaasService::class);
                        if ($asaas->isConfigured()) {
                            $asaas->createPayment($payment, 'PIX');
                        }
                    }
                });
            });
        }
    }

    public function getFinacialMetrics()
    {
        $currentMonth = now()->format('Y-m');

        return [
            'total_received_month' => Payment::paid()
                ->where('reference_month', $currentMonth)
                ->sum('amount'),
            'pending_payments' => Payment::pending()
                ->where('reference_month', $currentMonth)
                ->count(),
            'late_payments' => Payment::overdue()->count(),
            'total_active_students' => User::role(['aluno', 'professor', 'instrutor'])->where('status', 'active')->count(),
        ];
    }
}
