<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * This is an example migration demonstrating how to add a unique subdomain column
     * to the clients/academies table (tenants) or create it if not already present.
     */
    public function up(): void
    {
        // Option A: If adding to an existing table
        if (Schema::hasTable('tenants')) {
            Schema::table('tenants', function (Blueprint $table) {
                if (!Schema::hasColumn('tenants', 'subdomain')) {
                    $table->string('subdomain')->unique()->after('name');
                }
            });
        } 
        // Option B: Creating the table from scratch (reference)
        else {
            Schema::create('tenants', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('subdomain')->unique(); // Field that captures subdomain
                $table->string('domain')->unique()->nullable(); // Optional custom domain
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
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('tenants')) {
            Schema::table('tenants', function (Blueprint $table) {
                if (Schema::hasColumn('tenants', 'subdomain')) {
                    $table->dropColumn('subdomain');
                }
            });
        }
    }
};
