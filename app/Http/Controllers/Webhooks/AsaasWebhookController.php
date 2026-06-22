<?php

namespace App\Http\Controllers\Webhooks;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AsaasWebhookController extends Controller
{
    /**
     * Handle incoming Asaas webhook.
     */
    public function handle(Request $request)
    {
        Log::info('Asaas Webhook received', $request->all());

        $event = $request->input('event');
        $paymentData = $request->input('payment');

        if (!$paymentData) {
            return response()->json(['message' => 'Invalid payload'], 400);
        }

        $gatewayId = $paymentData['id'];
        $externalReference = $paymentData['externalReference'] ?? null;

        // Query payment by externalReference first, then gateway ID
        $payment = null;
        if ($externalReference) {
            $payment = Payment::find($externalReference);
        }
        
        if (!$payment) {
            $payment = Payment::where('gateway_transaction_id', $gatewayId)->first();
        }

        if (!$payment) {
            Log::warning('Payment not found for Asaas webhook', ['gateway_id' => $gatewayId, 'ref' => $externalReference]);
            return response()->json(['message' => 'Payment not found'], 200); // 200 to prevent Asaas retry loops
        }

        // Apply tenant context before updating
        app()->instance('currentTenant', $payment->tenant);

        switch ($event) {
            case 'PAYMENT_RECEIVED':
                $payment->update([
                    'status' => 'paid',
                    'payment_date' => now(),
                    'payment_method' => strtolower($paymentData['billingType'] ?? 'pix'),
                    'notes' => ($payment->notes ? $payment->notes . "\n" : "") . "Pago via Asaas Webhook em " . now()->format('d/m/Y H:i'),
                ]);
                Log::info("Payment {$payment->id} marked as PAID via Asaas Webhook.");
                break;

            case 'PAYMENT_OVERDUE':
                if ($payment->status === 'pending') {
                    $payment->update([
                        'status' => 'late',
                    ]);
                    Log::info("Payment {$payment->id} marked as OVERDUE/LATE via Asaas Webhook.");
                }
                break;

            case 'PAYMENT_DELETED':
                $payment->delete();
                Log::info("Payment {$payment->id} deleted via Asaas Webhook.");
                break;
        }

        return response()->json(['message' => 'Webhook processed successfully'], 200);
    }
}
