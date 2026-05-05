<?php

namespace Database\Seeders;

use App\Models\Plan;
use Illuminate\Database\Seeder;

class PlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Plan::firstOrCreate(
            ['name' => 'Plano Padrão'],
            ['price' => 65.00]
        );

        Plan::firstOrCreate(
            ['name' => 'Cortesia'],
            ['price' => 0.00]
        );
    }
}
