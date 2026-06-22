<?php

namespace App\Services;

use App\Models\Tenant;
use App\Models\User;
use App\Models\Payment;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AsaasService
{
    protected ?string $apiKey;
    protected string $baseUrl;

    public function __construct()
    {
        $tenant = Tenant::current();

        $this->apiKey = $tenant ? $tenant->asaas_api_key : null;
        $environment = $tenant ? $tenant->asaas_environment : 'sandbox';

        $this->baseUrl = $environment === 'production' 
            ? 'https://api.asaas.com/v3' 
            : 'https://sandbox.asaas.com/v3';
    }

    /**
     * Check if Asaas is configured for the active tenant.
     */
    public function isConfigured(): bool
    {
        return !empty($this->apiKey);
    }

    /**
     * Get HTTP client with headers preset.
     */
    protected function client()
    {
        return Http::withHeaders([
            'access_token' => $this->apiKey,
            'Content-Type' => 'application/json',
        ]);
    }

    /**
     * Create or update customer in Asaas.
     */
    public function getOrCreateCustomer(User $user): ?string
    {
        if (!$this->isConfigured()) return null;

        if ($user->asaas_customer_id) {
            return $user->asaas_customer_id;
        }

        try {
            // Clean phone number (leave digits only)
            $phone = preg_replace('/[^0-9]/', '', $user->telefone);
            
            $response = $this->client()->post("{$this->baseUrl}/customers", [
                'name' => $user->name,
                'email' => $user->email ?? 'aluno.' . $user->id . '@sememail.com', // Asaas requires email
                'cpfCnpj' => preg_replace('/[^0-9]/', '', $user->cpf),
                'mobilePhone' => $phone,
                'notificationDisabled' => true,
            ]);

            if ($response->successful()) {
                $customerId = $response->json('id');
                $user->update(['asaas_customer_id' => $customerId]);
                return $customerId;
            }

            Log::error('Asaas customer creation failed', [
                'user_id' => $user->id,
                'response' => $response->body()
            ]);
        } catch (\Exception $e) {
            Log::error('Asaas customer creation exception: ' . $e->getMessage());
        }

        return null;
    }

    /**
     * Create a payment/billing in Asaas.
     */
    public function createPayment(Payment $payment, string $billingType = 'UNDEFINED'): bool
    {
        if (!$this->isConfigured()) return false;

        $user = $payment->user;
        $customerId = $this->getOrCreateCustomer($user);

        if (!$customerId) return false;

        try {
            // clean due date
            $dueDate = $payment->due_date->format('Y-m-d');

            $response = $this->client()->post("{$this->baseUrl}/payments", [
                'customer' => $customerId,
                'billingType' => $billingType, // 'PIX', 'BOLETO', 'CREDIT_CARD' or 'UNDEFINED'
                'value' => (float) $payment->amount,
                'dueDate' => $dueDate,
                'description' => "Mensalidade - Ref: " . $payment->reference_month,
                'externalReference' => (string) $payment->id,
            ]);

            if ($response->successful()) {
                $transactionId = $response->json('id');
                $invoiceUrl = $response->json('invoiceUrl');

                $paymentData = [
                    'gateway_transaction_id' => $transactionId,
                    'asaas_invoice_url' => $invoiceUrl,
                ];

                // If billingType is PIX or UNDEFINED (which supports PIX), get PIX copy & paste + QR Code
                if ($billingType === 'PIX' || $billingType === 'UNDEFINED') {
                    $this->fetchPixDetails($transactionId, $paymentData);
                }

                $payment->update($paymentData);
                return true;
            }

            Log::error('Asaas payment creation failed', [
                'payment_id' => $payment->id,
                'response' => $response->body()
            ]);
        } catch (\Exception $e) {
            Log::error('Asaas payment creation exception: ' . $e->getMessage());
        }

        return false;
    }

    /**
     * Fetch Pix copy & paste code and base64 QR Code.
     */
    protected function fetchPixDetails(string $transactionId, array &$paymentData): void
    {
        try {
            $response = $this->client()->get("{$this->baseUrl}/payments/{$transactionId}/pixQrCode");

            if ($response->successful()) {
                $paymentData['asaas_pix_code'] = $response->json('payload');
                $paymentData['asaas_pix_qrcode'] = $response->json('encodedImage');
            }
        } catch (\Exception $e) {
            Log::error('Asaas fetch Pix details exception: ' . $e->getMessage());
        }
    }
}
