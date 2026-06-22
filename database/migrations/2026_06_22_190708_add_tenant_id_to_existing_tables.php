<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Add tenant_id column as nullable
        Schema::table('plans', function (Blueprint $table) {
            $table->unsignedBigInteger('tenant_id')->nullable()->after('id');
        });
        
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedBigInteger('tenant_id')->nullable()->after('id');
        });

        Schema::table('payments', function (Blueprint $table) {
            $table->unsignedBigInteger('tenant_id')->nullable()->after('id');
        });

        Schema::table('attendances', function (Blueprint $table) {
            $table->unsignedBigInteger('tenant_id')->nullable()->after('id');
        });

        // 2. Create the default tenant if none exists
        $tenantId = DB::table('tenants')->insertGetId([
            'name' => 'CT Denyson Anderson',
            'subdomain' => 'ctdenyson',
            'primary_color' => '#3b82f6',
            'secondary_color' => '#1e3a8a',
            'status' => 'active',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 3. Update existing records
        DB::table('plans')->update(['tenant_id' => $tenantId]);
        DB::table('users')->update(['tenant_id' => $tenantId]);
        DB::table('payments')->update(['tenant_id' => $tenantId]);
        DB::table('attendances')->update(['tenant_id' => $tenantId]);

        // 4. Set tenant_id to be not nullable and add foreign key constraint
        Schema::table('plans', function (Blueprint $table) {
            $table->unsignedBigInteger('tenant_id')->nullable(false)->change();
            $table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('cascade');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->unsignedBigInteger('tenant_id')->nullable(false)->change();
            $table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('cascade');

            // Drop original unique indexes
            $table->dropUnique('users_email_unique');
            $table->dropUnique('users_cpf_unique');
            $table->dropUnique('users_login_unique');

            // Create new unique indexes scoped by tenant_id
            $table->unique(['tenant_id', 'email']);
            $table->unique(['tenant_id', 'cpf']);
            $table->unique(['tenant_id', 'login']);
        });

        Schema::table('payments', function (Blueprint $table) {
            $table->unsignedBigInteger('tenant_id')->nullable(false)->change();
            $table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('cascade');
        });

        Schema::table('attendances', function (Blueprint $table) {
            $table->unsignedBigInteger('tenant_id')->nullable(false)->change();
            $table->foreign('tenant_id')->references('id')->on('tenants')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('attendances', function (Blueprint $table) {
            $table->dropForeign(['tenant_id']);
            $table->dropColumn('tenant_id');
        });

        Schema::table('payments', function (Blueprint $table) {
            $table->dropForeign(['tenant_id']);
            $table->dropColumn('tenant_id');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['tenant_id']);
            $table->dropUnique(['tenant_id', 'email']);
            $table->dropUnique(['tenant_id', 'cpf']);
            $table->dropUnique(['tenant_id', 'login']);

            $table->string('email')->unique()->nullable()->change();
            $table->string('cpf')->unique()->nullable()->change();
            $table->string('login')->unique()->nullable()->change();

            $table->dropColumn('tenant_id');
        });

        Schema::table('plans', function (Blueprint $table) {
            $table->dropForeign(['tenant_id']);
            $table->dropColumn('tenant_id');
        });
    }
};
