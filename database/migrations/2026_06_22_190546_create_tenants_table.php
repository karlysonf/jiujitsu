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
        Schema::create('tenants', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('subdomain')->unique();
            $table->string('domain')->unique()->nullable();
            $table->string('logo')->nullable();
            $table->string('primary_color')->default('#3b82f6');
            $table->string('secondary_color')->default('#1e3a8a');
            $table->text('asaas_api_key')->nullable();
            $table->string('asaas_environment')->default('sandbox');
            $table->string('status')->default('trial');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tenants');
    }
};
