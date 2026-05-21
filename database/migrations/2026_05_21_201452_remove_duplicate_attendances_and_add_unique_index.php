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
        // Remove duplicates
        $duplicates = \DB::table('attendances')
            ->select('user_id', 'date')
            ->groupBy('user_id', 'date')
            ->havingRaw('COUNT(*) > 1')
            ->get();

        foreach ($duplicates as $duplicate) {
            $idsToDelete = \DB::table('attendances')
                ->where('user_id', $duplicate->user_id)
                ->where('date', $duplicate->date)
                ->orderBy('id')
                ->pluck('id')
                ->slice(1) // Keep the first one
                ->toArray();

            \DB::table('attendances')->whereIn('id', $idsToDelete)->delete();
        }

        // Schema change removed to avoid SoftDeletes index complications
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
