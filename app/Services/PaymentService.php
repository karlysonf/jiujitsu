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

                return Payment::updateOrCreate(
                    [
                        'idempotency_key' => $idempotencyKey,
                    ],
                    [
                        'user_id' => $data['student_id'], // Map student_id from request to user_id
                        'amount' => $data['amount'],
                        'due_date' => $data['due_date'],
                        'payment_date' => $data['payment_date'] ?? now(),
                        'status' => 'paid',
                        'payment_method' => $data['payment_method'],
                        'reference_month' => $data['reference_month'],
                        'notes' => $data['notes'] ?? null,
                        'gateway_transaction_id' => $data['gateway_transaction_id'] ?? null,
                    ]
                );
            });
        });
    }

    /**
     * Gera uma chave de idempotência baseada nos dados do pagamento se não fornecida.
     */
    protected function generateIdempotencyKey(array $data): string
    {
        return md5(implode('|', [
            $data['student_id'],
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
                    Payment::firstOrCreate(
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
