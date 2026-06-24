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
        Schema::table('tenants', function (Blueprint $table) {
            $table->string('plan_tier')->default('bronze')->after('status');
            $table->integer('max_users')->default(50)->nullable()->after('plan_tier');
            $table->timestamp('expires_at')->nullable()->after('max_users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tenants', function (Blueprint $table) {
            $table->dropColumn(['plan_tier', 'max_users', 'expires_at']);
        });
    }
};
