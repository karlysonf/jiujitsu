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
        Schema::table('users', function (Blueprint $table) {
            $table->decimal('custom_price', 8, 2)->nullable()->after('plan_id');
        });

        DB::table('plans')->where('name', 'Plano Padrão')->update(['price' => 75.00]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('plans')->where('name', 'Plano Padrão')->update(['price' => 65.00]);

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('custom_price');
        });
    }
};
