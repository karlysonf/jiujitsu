<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('asaas_customer_id')->nullable()->after('photo');
        });

        Schema::table('payments', function (Blueprint $table) {
            $table->string('asaas_invoice_url')->nullable()->after('gateway_transaction_id');
            $table->text('asaas_pix_code')->nullable()->after('asaas_invoice_url');
            $table->text('asaas_pix_qrcode')->nullable()->after('asaas_pix_code');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('asaas_customer_id');
        });

        Schema::table('payments', function (Blueprint $table) {
            $table->dropColumn(['asaas_invoice_url', 'asaas_pix_code', 'asaas_pix_qrcode']);
        });
    }
};
