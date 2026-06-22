<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class GenerateMonthlyPayments implements ShouldQueue
{
    use Queueable;

    protected ?int $tenantId;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        $this->tenantId = \App\Models\Tenant::current()?->id;
    }

    /**
     * Execute the job.
     */
    public function handle(\App\Services\PaymentService $paymentService): void
    {
        if ($this->tenantId) {
            $tenant = \App\Models\Tenant::find($this->tenantId);
            if ($tenant) {
                app()->instance('currentTenant', $tenant);
            }
        }

        $paymentService->generateMonthlyBilling();
    }
}
